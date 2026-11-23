<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{HomeController, PemesananController};
use App\Http\Controllers\Admin\{AuthController, DashboardController, ProdukController, PesananController, ProfilUsahaController, AkunController, PromoController};

// ---- Pembeli (publik, tanpa akun) ----
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/info', [HomeController::class, 'info'])->name('info');
Route::get('/keranjang', [HomeController::class, 'keranjang'])->name('keranjang');
// Simpan pesanan dari pembeli sebagai "Belum fix" (dibatasi 6 kali per 10 menit per IP)
Route::post('/pesan', [PemesananController::class, 'simpan'])->middleware('throttle:6,10')->name('pesan.simpan');

// ---- Admin ----
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthController::class, 'form'])->name('login');
        Route::post('login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.proses');
    });
    Route::middleware('auth:admin')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('produk', ProdukController::class)->except('show');
        Route::get('pesanan', [PesananController::class, 'index'])->name('pesanan.index');      // belum fix
        Route::get('pesanan/fix', [PesananController::class, 'fix'])->name('pesanan.fix');      // sudah fix
        Route::get('pesanan/catat', [PesananController::class, 'create'])->name('pesanan.create');
        Route::post('pesanan', [PesananController::class, 'store'])->name('pesanan.store');
        Route::post('pesanan/{pesanan}/selesai', [PesananController::class, 'selesai'])->name('pesanan.selesai');
        Route::delete('pesanan/{pesanan}', [PesananController::class, 'destroy'])->name('pesanan.destroy');
        Route::resource('promo', PromoController::class)->except('show');
        Route::post('promo/{promo}/toggle', [PromoController::class, 'toggle'])->name('promo.toggle');
        Route::get('profil', [ProfilUsahaController::class, 'edit'])->name('profil.edit');
        Route::put('profil', [ProfilUsahaController::class, 'update'])->name('profil.update');
        Route::get('akun', [AkunController::class, 'edit'])->name('akun.edit');
        Route::put('akun', [AkunController::class, 'update'])->name('akun.update');
    });
});
