@extends('layouts.admin')
@section('title', 'Pesanan')
@section('content')
@php $rp = fn($n) => 'Rp '.number_format($n, 0, ',', '.'); $m = request('metode'); @endphp
<div class="card">
  <div class="head"><h2>Riwayat pesanan selesai</h2><a class="btn fill" href="{{ route('admin.pesanan.create') }}">+ Catat transaksi</a></div>
  <form class="srch" method="GET" style="margin-bottom:14px">
    <input name="q" type="search" value="{{ request('q') }}" placeholder="Cari kode atau nama pelanggan" aria-label="Cari pesanan">
    @if($m)<input type="hidden" name="metode" value="{{ $m }}">@endif
  </form>
  <div class="tabs">
    <a class="{{ !$m ? 'on' : '' }}" href="{{ route('admin.pesanan.index', request()->only('q')) }}">Semua</a>
    <a class="{{ $m == 'Tunai' ? 'on' : '' }}" href="{{ route('admin.pesanan.index', request()->only('q') + ['metode' => 'Tunai']) }}">Tunai</a>
    <a class="{{ $m == 'Nontunai' ? 'on' : '' }}" href="{{ route('admin.pesanan.index', request()->only('q') + ['metode' => 'Nontunai']) }}">Nontunai</a>
  </div>
  <div class="scroll"><table>
    <thead><tr><th>Kode pesanan</th><th>Nama pelanggan</th><th>No. HP</th><th>Metode bayar</th><th>Tanggal selesai</th><th>Total harga</th><th></th></tr></thead>
    <tbody>
    @forelse($pesanan as $o)
      <tr><td><b>{{ $o->kode_pesanan }}</b></td><td>{{ $o->nama_pelanggan }}</td><td>{{ $o->no_hp }}</td>
        <td><span class="pill {{ $o->metode_bayar == 'Tunai' ? 'tunai' : 'non' }}">{{ $o->metode_bayar }}</span></td>
        <td>{{ $o->tanggal_selesai->translatedFormat('d M Y') }}</td><td><b>{{ $rp($o->total_harga) }}</b></td>
        <td><button class="btn" type="button" data-k="{{ $o->id_pesanan }}">Detail</button></td></tr>
      <tr class="det" id="d-{{ $o->id_pesanan }}" hidden><td colspan="7">
        <small style="color:var(--mut)">Alamat: {{ $o->alamat }}</small>
        <table class="sub" style="margin-top:10px"><thead><tr><th>Produk</th><th>Jumlah</th><th>Harga satuan</th><th>Subtotal</th></tr></thead><tbody>
        @foreach($o->detail as $d)<tr><td>{{ $d->produk?->nama_produk ?? 'Produk dihapus' }}</td><td>{{ $d->jumlah }}</td><td>{{ $rp($d->harga_satuan) }}</td><td>{{ $rp($d->subtotal) }}</td></tr>@endforeach
        </tbody></table></td></tr>
    @empty
      <tr><td colspan="7" class="note">Belum ada transaksi. Catat transaksi setelah pesanan diantar dan dibayar.</td></tr>
    @endforelse
    </tbody>
  </table></div>
  @include('partials.admin.pager', ['p' => $pesanan])
  <p class="note">Pesanan masuk lewat WhatsApp. Catat di sini setelah pesanan diantar dan pembayaran diterima.</p>
</div>
@endsection
