<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{Pesanan, Produk};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesananController extends Controller {
    public function index(Request $r) {
        $q = Pesanan::with('detail.produk')->orderByDesc('tanggal_selesai')->orderByDesc('id_pesanan');
        if (in_array($r->query('metode'), ['Tunai', 'Nontunai'])) $q->where('metode_bayar', $r->query('metode'));
        if (is_string($s = $r->query('q')) && trim($s) !== '') {
            $s = '%' . addcslashes(trim($s), '%_\\') . '%';
            $q->where(fn ($w) => $w->where('kode_pesanan', 'like', $s)->orWhere('nama_pelanggan', 'like', $s));
        }
        return view('admin.pesanan.index', ['pesanan' => $q->paginate(10)->withQueryString()]);
    }

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
                'kode_pesanan'    => Pesanan::kodeBerikutnya(),
                'nama_pelanggan'  => $data['nama_pelanggan'],
                'no_hp'           => $data['no_hp'],
                'alamat'          => $data['alamat'],
                'metode_bayar'    => $data['metode_bayar'],
                'total_harga'     => $baris->sum('subtotal'),
                'tanggal_selesai' => $data['tanggal_selesai'],
            ]);
            $pesanan->detail()->createMany($baris->all());
        });
        return redirect()->route('admin.pesanan.index')->with('sukses', 'Transaksi dicatat.');
    }
}
