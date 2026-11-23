@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')
@php $max = max(1, $hari->max('n')); $rp = fn($n) => 'Rp '.number_format($n, 0, ',', '.'); @endphp
<div class="grid g3 sec">
  <div class="card stat a"><div><small>Pendapatan bulan ini</small><b>{{ $rp($pendapatan) }}</b></div><p>dari total harga pesanan selesai</p></div>
  <div class="card stat b"><div><small>Pesanan selesai bulan ini</small><b>{{ $selesaiBulan }}</b></div><p>{{ $totalPesanan }} pesanan seluruhnya</p></div>
  <a class="card stat c" href="{{ route('admin.pesanan.index') }}" style="text-decoration:none"><div><small>Menunggu konfirmasi</small><b>{{ $menunggu }}</b></div><p>{{ $produkAktif }} dari {{ $produkTotal }} produk aktif</p></a>
</div>
<div class="grid g2 sec">
  <div class="card"><h2>Pesanan selesai 7 hari terakhir</h2>
    <div class="bars">@foreach($hari as $h)<div><span style="height:{{ $h['n'] / $max * 85 }}%" title="{{ $h['n'] }} pesanan"></span></div>@endforeach</div>
    <div class="lbl">@foreach($hari as $h)<span>{{ $h['label'] }}</span>@endforeach</div>
  </div>
  <div class="card"><h2>Metode pembayaran</h2>
    <div class="pie" style="background:conic-gradient(var(--blue) 0 {{ $persenTunai }}%,var(--aqua) {{ $persenTunai }}% 100%)"></div>
    <div class="leg"><span><i style="background:var(--blue)"></i>Tunai {{ $persenTunai }}%</span><span><i style="background:var(--aqua)"></i>Nontunai {{ $totalPesanan ? 100 - $persenTunai : 0 }}%</span></div>
  </div>
</div>
<div class="grid g2b">
  <div class="card"><h2>Pesanan terbaru</h2>
    @forelse($terbaru as $o)
      <div class="row"><div class="ico">{{ strtoupper(substr($o->nama_pelanggan, 0, 1)) }}</div>
        <div class="t"><b>{{ $o->nama_pelanggan }}</b><small>{{ $o->kode_pesanan }} · {{ $o->tanggal_selesai->translatedFormat('d M Y') }}</small></div>
        <span class="pos">{{ $rp($o->total_harga) }}</span></div>
    @empty<p class="note">Belum ada transaksi yang dicatat.</p>@endforelse
  </div>
  <div class="card"><h2>Produk terlaris</h2>
    @forelse($terlaris as $d)
      <div class="row"><div class="thumb"><i class="fa-solid {{ $d->produk->ikon }}"></i></div>
        <div class="t"><b>{{ $d->produk->nama_produk }}</b><small>{{ $d->terjual }} terjual</small></div><b>{{ $rp($d->omzet) }}</b></div>
    @empty<p class="note">Belum ada data penjualan.</p>@endforelse
  </div>
</div>
@endsection
