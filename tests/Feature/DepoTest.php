<?php
namespace Tests\Feature;

use App\Models\{Admin, Pesanan, Produk, Promo};
use Database\Seeders\{AdminSeeder, ProdukSeeder, ProfilUsahaSeeder};
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DepoTest extends TestCase {
    use RefreshDatabase;

    protected function setUp(): void {
        parent::setUp();
        $this->seed([AdminSeeder::class, ProfilUsahaSeeder::class, ProdukSeeder::class]);
    }
    private function admin() { return Admin::first(); }
    private function pesan(array $o = []) {
        return $this->postJson('/pesan', $o + ['nama' => 'Tes Pembeli', 'hp' => '081234567890', 'pengambilan' => 'ambil', 'metode' => 'Tunai', 'items' => ['1' => 2, '2' => 1]]);
    }

    public function test_halaman_pembeli_tampil_dengan_pencarian_dan_tombol_tema(): void {
        foreach (['/', '/info', '/keranjang'] as $u) $this->get($u)->assertOk()->assertSee('id="themeBtn"', false);
        $this->get('/')->assertSee('id="cariProduk"', false)->assertSee('Isi Ulang Galon')->assertDontSee('Antar ke Rumah')->assertDontSee('Ongkos Kirim');
        $this->get('/')->assertDontSee('id="promoLive"', false)->assertSee('Tanpa akun');
    }

    public function test_pesanan_ambil_dan_antar_masuk_sebagai_belum_fix_dengan_harga_dari_server(): void {
        $kode = $this->pesan()->assertOk()->json('kode');
        $p = Pesanan::where('kode_pesanan', $kode)->first();
        $this->assertSame('Belum fix', $p->status);
        $this->assertSame(0, (int) $p->ongkir);
        $this->assertSame(2 * 6000 + 45000, (int) $p->total_harga);
        $this->assertNull($p->tanggal_selesai);

        $k2 = $this->pesan(['hp' => '081234567891', 'pengambilan' => 'antar', 'alamat' => 'Jl. Veteran, Malang', 'detail' => 'No 5', 'lat' => -7.95, 'lng' => 112.6, 'items' => ['1' => 3]])->assertOk()->json('kode');
        $p2 = Pesanan::where('kode_pesanan', $k2)->first();
        $this->assertSame(5000, (int) $p2->ongkir);
        $this->assertSame(3 * 6000 + 5000, (int) $p2->total_harga);
        $this->assertSame('Diantar', $p2->pengambilan);
    }

    public function test_pemesanan_menolak_data_tidak_sah(): void {
        $this->pesan(['pengambilan' => 'antar'])->assertStatus(422);                    // antar tanpa alamat
        $this->pesan(['hp' => '123'])->assertStatus(422);                              // HP salah
        $biaya = Produk::where('kategori', 'Biaya')->value('id_produk');
        $this->pesan(['items' => [(string) $biaya => 1]])->assertStatus(422);          // item Biaya tidak boleh dipesan
        $this->pesan(['items' => ['999' => 1]])->assertStatus(422);                    // produk tidak ada
        $this->pesan(['items' => ['1' => 0]])->assertStatus(422);                      // jumlah 0
        $this->assertSame(0, Pesanan::count());
    }

    public function test_jebakan_bot_dan_batas_pesanan_menggantung(): void {
        $this->pesan(['website' => 'spam'])->assertOk();
        $this->assertSame(0, Pesanan::count());
        for ($i = 0; $i < 3; $i++) $this->pesan(['hp' => '081200000009'])->assertOk();
        $this->pesan(['hp' => '081200000009'])->assertStatus(422);
        $this->assertSame(3, Pesanan::count());
    }

    public function test_admin_wajib_login(): void {
        foreach (['/admin', '/admin/pesanan', '/admin/pesanan/fix', '/admin/promo', '/admin/produk'] as $u) $this->get($u)->assertRedirect('/admin/login');
        $this->post('/admin/login', ['email' => 'admin@tirtaduaputri.id', 'password' => 'salah'])->assertSessionHasErrors('email');
        $this->post('/admin/login', ['email' => 'admin@tirtaduaputri.id', 'password' => 'admin12345'])->assertRedirectContains('/admin');   // bisa kembali ke halaman tujuan semula
    }

    public function test_alur_belum_fix_ke_fix(): void {
        $kode = $this->pesan()->json('kode');
        $p = Pesanan::where('kode_pesanan', $kode)->first();
        $this->actingAs($this->admin(), 'admin');

        $this->get('/admin/pesanan')->assertOk()->assertSee($kode)->assertSee('Selesai')->assertSee('Tolak');
        $this->get('/admin/pesanan/fix')->assertOk()->assertDontSee($kode);
        $this->get('/admin')->assertOk()->assertSee('Menunggu konfirmasi');

        $this->post("/admin/pesanan/{$p->id_pesanan}/selesai")->assertRedirect('/admin/pesanan');
        $p->refresh();
        $this->assertSame('Fix', $p->status);
        $this->assertSame(now()->toDateString(), $p->tanggal_selesai->toDateString());
        $this->get('/admin/pesanan')->assertViewHas('pesanan', fn ($x) => $x->count() === 0);   // daftar belum fix kosong
        $this->get('/admin/pesanan/fix')->assertOk()->assertSee($kode);
        $this->delete("/admin/pesanan/{$p->id_pesanan}")->assertSessionHas('gagal');    // fix tidak bisa dihapus
        $this->assertNotNull(Pesanan::find($p->id_pesanan));
    }

    public function test_tolak_pesanan_iseng_dan_dashboard_hanya_menghitung_fix(): void {
        $a = Pesanan::where('kode_pesanan', $this->pesan(['hp' => '081211111111'])->json('kode'))->first();
        $b = Pesanan::where('kode_pesanan', $this->pesan(['hp' => '081222222222'])->json('kode'))->first();
        $this->actingAs($this->admin(), 'admin');
        $this->delete("/admin/pesanan/{$a->id_pesanan}")->assertRedirect('/admin/pesanan');
        $this->assertNull(Pesanan::find($a->id_pesanan));
        $this->assertSame(0, \DB::table('detail_pesanan')->where('id_pesanan', $a->id_pesanan)->count());

        $r = $this->get('/admin')->assertOk();
        $this->assertSame(0, $r->viewData('totalPesanan'));
        $this->assertEquals(0, $r->viewData('pendapatan'));
        $this->post("/admin/pesanan/{$b->id_pesanan}/selesai");
        $r = $this->get('/admin')->assertOk();
        $this->assertSame(1, $r->viewData('totalPesanan'));
        $this->assertEquals(57000, $r->viewData('pendapatan'));
    }

    public function test_catat_manual_langsung_fix(): void {
        $this->actingAs($this->admin(), 'admin');
        $this->post('/admin/pesanan', ['nama_pelanggan' => 'Walk In', 'no_hp' => '081234567890', 'alamat' => 'Di tempat', 'metode_bayar' => 'Tunai',
            'tanggal_selesai' => now()->toDateString(), 'item' => [['id_produk' => 1, 'jumlah' => 2]]])->assertRedirect('/admin/pesanan/fix');
        $this->assertSame('Fix', Pesanan::first()->status);
    }

    public function test_promo_live_diatur_admin_dan_tampil_di_beranda(): void {
        $this->actingAs($this->admin(), 'admin');
        $this->get('/admin/promo')->assertOk();
        $this->get('/admin/promo/create')->assertOk();
        $data = ['judul' => 'Promo Akhir Pekan', 'label' => 'Promo Live', 'diskon_teks' => 'Diskon 20%', 'teks_tombol' => 'Pesan sekarang', 'kode_promo' => 'AIR20', 'aktif' => 1];
        $this->post('/admin/promo', $data)->assertRedirect('/admin/promo');
        $this->get('/')->assertSee('id="promoLive"', false)->assertSee('Promo Akhir Pekan')->assertSee('Diskon 20%')->assertSee('AIR20');

        $this->post('/admin/promo', ['judul' => 'Promo Baru', 'aktif' => 1] + $data);       // menayangkan yang baru menghentikan yang lama
        $this->assertSame(1, Promo::where('aktif', true)->count());
        $this->get('/')->assertSee('Promo Baru')->assertDontSee('Promo Akhir Pekan');

        $baru = Promo::where('judul', 'Promo Baru')->first();
        $this->post("/admin/promo/{$baru->id_promo}/toggle");                               // hentikan
        $this->get('/')->assertDontSee('id="promoLive"', false)->assertSee('Tanpa akun');

        $baru->refresh()->update(['aktif' => true, 'selesai' => now()->subDay()]);                      // sudah berakhir
        $this->get('/')->assertDontSee('id="promoLive"', false);
        $this->assertSame('Berakhir', $baru->fresh()->status);
        $baru->update(['selesai' => null, 'mulai' => now()->addDays(2)]);                    // terjadwal
        $this->get('/')->assertDontSee('id="promoLive"', false);
        $this->post('/admin/promo', ['selesai' => '2020-01-01', 'mulai' => '2020-02-01'] + $data)->assertSessionHasErrors('selesai');
        $this->delete("/admin/promo/{$baru->id_promo}")->assertRedirect('/admin/promo');
    }

    public function test_halaman_admin_punya_tombol_tema_dan_menu_promo(): void {
        $this->actingAs($this->admin(), 'admin');
        foreach (['/admin', '/admin/pesanan', '/admin/pesanan/fix', '/admin/produk', '/admin/promo', '/admin/profil', '/admin/akun', '/admin/pesanan/catat'] as $u) {
            $this->get($u)->assertOk()->assertSee('id="themeBtn"', false)->assertSee('Promo');
        }
    }
}
