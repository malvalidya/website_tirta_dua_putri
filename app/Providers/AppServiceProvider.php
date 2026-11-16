<?php
namespace App\Providers;
use App\Models\Produk;
use App\Models\ProfilUsaha;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider {
    public function register(): void {}
    public function boot(): void {
        // Data untuk semua halaman pembeli: layout, halaman, dan partial-nya.
        // (Halaman anak dirender sebelum layout, jadi composer harus mencakup 'customer.*' juga.)
        View::composer(['layouts.customer', 'customer.*', 'partials.customer.*'], function ($view) {
            static $data = null;
            $data ??= (function () {
                $profil = Schema::hasTable('profil_usaha') ? ProfilUsaha::first() : null;
                $profil ??= new ProfilUsaha(['nama_usaha' => 'Depo Tirta Dua Putri', 'nomor_wa' => '62812xxxxxxx']);
                $produkJs = Schema::hasTable('produk') ? Produk::tampil()->orderBy('id_produk')->get()->map(fn ($p) => [
                    'id' => $p->id_produk, 'nama' => $p->nama_produk, 'kategori' => $p->kategori,
                    'desk' => $p->deskripsi, 'harga' => (int) $p->harga, 'aktif' => $p->aktif,
                    'icon' => $p->ikon, 'foto' => $p->foto_url,
                ])->values() : collect();
                return compact('profil', 'produkJs');
            })();
            $view->with($data);
        });
    }
}
