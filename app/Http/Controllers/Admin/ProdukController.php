<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller {
    private function rules(): array {
        return [
            'nama_produk'   => 'required|string|max:255',
            'kategori'      => 'required|string|max:60',
            'deskripsi'     => 'nullable|string',
            'harga'         => 'required|integer|min:0',
            'foto_url'      => 'nullable|url|max:500',
            'status_produk' => 'required|in:Aktif,Habis',
        ];
    }
    private function kategori() { return Produk::distinct()->pluck('kategori'); }

    public function index() { return view('admin.produk.index', ['produk' => Produk::orderBy('id_produk')->get()]); }

    public function create() {
        return view('admin.produk.form', ['produk' => new Produk(['status_produk' => 'Aktif']), 'kategori' => $this->kategori()]);
    }
    public function store(Request $r) {
        Produk::create($r->validate($this->rules()));
        return redirect()->route('admin.produk.index')->with('sukses', 'Produk ditambahkan.');
    }
    public function edit(Produk $produk) {
        return view('admin.produk.form', ['produk' => $produk, 'kategori' => $this->kategori()]);
    }
    public function update(Request $r, Produk $produk) {
        $produk->update($r->validate($this->rules()));
        return redirect()->route('admin.produk.index')->with('sukses', 'Produk diperbarui.');
    }
    public function destroy(Produk $produk) {
        if ($produk->detail()->exists()) {
            return back()->with('gagal', 'Produk sudah ada di riwayat pesanan. Ubah statusnya menjadi Habis saja.');
        }
        $produk->delete();
        return redirect()->route('admin.produk.index')->with('sukses', 'Produk dihapus.');
    }
}
