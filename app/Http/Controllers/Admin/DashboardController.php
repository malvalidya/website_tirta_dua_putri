<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\{DetailPesanan, Pesanan, Produk};

class DashboardController extends Controller {
    public function index() {
        $now = now();
        $bulanIni = Pesanan::fix()->whereYear('tanggal_selesai', $now->year)->whereMonth('tanggal_selesai', $now->month);
        $totalPesanan = Pesanan::fix()->count();
        $tunai = Pesanan::fix()->where('metode_bayar', 'Tunai')->count();

        return view('admin.dashboard', [
            'pendapatan'   => (clone $bulanIni)->sum('total_harga'),
            'selesaiBulan' => (clone $bulanIni)->count(),
            'totalPesanan' => $totalPesanan,
            'menunggu'     => Pesanan::belumFix()->count(),
            'produkAktif'  => Produk::where('status_produk', 'Aktif')->count(),
            'produkTotal'  => Produk::count(),
            'persenTunai'  => $totalPesanan ? round($tunai / $totalPesanan * 100) : 0,
            'hari' => collect(range(6, 0))->map(function ($i) {
                $d = now()->subDays($i);
                return ['label' => $d->locale('id')->isoFormat('ddd'), 'n' => Pesanan::fix()->whereDate('tanggal_selesai', $d)->count()];
            }),
            'terbaru'  => Pesanan::fix()->orderByDesc('tanggal_selesai')->orderByDesc('id_pesanan')->limit(5)->get(),
            'terlaris' => DetailPesanan::whereHas('pesanan', fn ($q) => $q->fix())
                ->selectRaw('id_produk, SUM(jumlah) as terjual, SUM(subtotal) as omzet')
                ->groupBy('id_produk')->orderByDesc('omzet')->with('produk')->limit(4)->get(),
        ]);
    }
}
