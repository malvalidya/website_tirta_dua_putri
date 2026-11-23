<?php
namespace Database\Seeders;
use App\Models\{Pesanan, Produk};
use Illuminate\Database\Seeder;

class PesananDemoSeeder extends Seeder {
    public function run(): void {
        $produk = Produk::where('status_produk', 'Aktif')->get();
        $nama = ['Siti Rahmawati', 'Budi Santoso', 'Ayu Lestari', 'Rizky Pratama', 'Dewi Anggraini'];
        foreach ($nama as $i => $n) {
            $p = $produk->random(2); $q = [rand(1, 3), 1];
            $pesanan = Pesanan::create([
                'kode_pesanan' => 'TDP-' . str_pad(Pesanan::max('id_pesanan') + 1, 4, '0', STR_PAD_LEFT),
                'nama_pelanggan' => $n, 'no_hp' => '08123456' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'alamat' => 'Jl. Contoh No. ' . ($i + 1) . ', Malang',
                'metode_bayar' => $i % 2 ? 'Nontunai' : 'Tunai', 'total_harga' => 0,
                'status' => $i < 3 ? 'Fix' : 'Belum fix', // 2 pesanan terakhir menunggu konfirmasi
                'tanggal_selesai' => $i < 3 ? now()->subDays($i)->toDateString() : null,
            ]);
            $total = 0;
            foreach ($p->values() as $k => $prod) {
                $sub = $prod->harga * $q[$k]; $total += $sub;
                $pesanan->detail()->create(['id_produk' => $prod->id_produk, 'jumlah' => $q[$k], 'harga_satuan' => $prod->harga, 'subtotal' => $sub]);
            }
            $pesanan->update(['total_harga' => $total]);
        }
    }
}
