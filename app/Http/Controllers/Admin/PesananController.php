<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Pesanan, Produk};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller {
    /** Pesanan masuk yang belum diverifikasi (terlama di atas). */
    public function index() {
        return view('admin.pesanan.index', [
            'pesanan' => Pesanan::belumFix()->with('detail.produk')->orderBy('dibuat_pada')->orderBy('id_pesanan')->paginate(15),
        ]);
    }

    /** Riwayat pesanan yang sudah fix/selesai. */
    public function fix(Request $r) {
        $q = Pesanan::fix()->with('detail.produk')->orderByDesc('tanggal_selesai')->orderByDesc('id_pesanan');
        if (in_array($r->query('metode'), ['Tunai', 'Nontunai'])) $q->where('metode_bayar', $r->query('metode'));
        if ($s = $r->query('q')) {
            $q->where(fn ($w) => $w->where('kode_pesanan', 'like', "%$s%")->orWhere('nama_pelanggan', 'like', "%$s%"));
        }
        return view('admin.pesanan.fix', ['pesanan' => $q->paginate(10)->withQueryString()]);
    }

    /** Admin sudah memverifikasi, pesanan diantar/diambil dan dibayar: pindah ke Fix. */
    public function selesai(Pesanan $pesanan) {
        if ($pesanan->status === 'Belum fix') {
            $pesanan->update(['status' => 'Fix', 'tanggal_selesai' => now()->toDateString()]);
        }
        return redirect()->route('admin.pesanan.index')->with('sukses', "Pesanan {$pesanan->kode_pesanan} dipindahkan ke Pesanan fix.");
    }

    /** Tolak pesanan palsu/iseng (hanya yang belum fix). */
    public function destroy(Pesanan $pesanan) {
        if ($pesanan->status !== 'Belum fix') return back()->with('gagal', 'Pesanan yang sudah fix tidak bisa dihapus.');
        $kode = $pesanan->kode_pesanan;
        $pesanan->delete(); // detail_pesanan ikut terhapus (cascade)
        return redirect()->route('admin.pesanan.index')->with('sukses', "Pesanan $kode ditolak dan dihapus.");
    }

    /** Catat manual (mis. pembeli datang langsung): langsung berstatus Fix. */
    public function create() {
        return view('admin.pesanan.create', [
            'produk' => Produk::where('status_produk', 'Aktif')->orderBy('nama_produk')->get(),
            'kode'   => Pesanan::kodeBerikutnya(),
        ]);
    }
    public function store(Request $r) {
        $data = $r->validate([
            'nama_pelanggan'   => 'required|string|max:255',
            'no_hp'            => ['required', 'regex:/^(\+62|62|0)8\d{8,12}$/'],
            'alamat'           => 'required|string',
            'metode_bayar'     => 'required|in:Tunai,Nontunai',
            'tanggal_selesai'  => 'required|date',
            'item'             => 'required|array|min:1',
            'item.*.id_produk' => 'required|exists:produk,id_produk',
            'item.*.jumlah'    => 'required|integer|min:1',
        ], ['no_hp.regex' => 'Nomor HP tidak valid, contoh 081234567890.', 'item.required' => 'Tambahkan minimal satu produk.']);

        DB::transaction(function () use ($data) {
            $harga = Produk::whereIn('id_produk', collect($data['item'])->pluck('id_produk'))->pluck('harga', 'id_produk');
            $baris = collect($data['item'])->map(fn ($i) => [
                'id_produk' => $i['id_produk'], 'jumlah' => (int) $i['jumlah'],
                'harga_satuan' => $harga[$i['id_produk']], 'subtotal' => $harga[$i['id_produk']] * (int) $i['jumlah'],
            ]);
            $pesanan = Pesanan::create([
                'kode_pesanan' => Pesanan::kodeBerikutnya(), 'nama_pelanggan' => $data['nama_pelanggan'], 'no_hp' => $data['no_hp'],
                'alamat' => $data['alamat'], 'metode_bayar' => $data['metode_bayar'], 'total_harga' => $baris->sum('subtotal'),
                'status' => 'Fix', 'tanggal_selesai' => $data['tanggal_selesai'],
            ]);
            $pesanan->detail()->createMany($baris->all());
        });
        return redirect()->route('admin.pesanan.fix')->with('sukses', 'Transaksi dicatat.');
    }
}
