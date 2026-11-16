<?php
namespace App\Http\Controllers;
use App\Models\Produk;

class HomeController extends Controller {
    public function index() {
        $produk = Produk::tampil()->orderBy('id_produk')->get();
        return view('customer.home', [
            'produk' => $produk,
            'kategori' => $produk->pluck('kategori')->filter()->unique()->values(),
        ]);
    }
    public function info() { return view('customer.info'); }
    public function keranjang() { return view('customer.cart'); }
}
