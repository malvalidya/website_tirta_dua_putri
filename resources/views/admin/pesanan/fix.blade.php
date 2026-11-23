@extends('layouts.admin')
@section('title', 'Pesanan fix')
@section('content')
@php $rp = fn($n) => 'Rp '.number_format($n, 0, ',', '.'); $m = request('metode'); @endphp
<div class="card">
  <div class="head"><h2>Riwayat pesanan fix</h2><span><a class="btn" href="{{ route('admin.pesanan.index') }}">Belum fix</a> <a class="btn fill" href="{{ route('admin.pesanan.create') }}">+ Catat manual</a></span></div>
  <form class="srch" method="GET" style="margin-bottom:14px">
    <input name="q" value="{{ request('q') }}" placeholder="Cari kode atau nama pelanggan">
    @if($m)<input type="hidden" name="metode" value="{{ $m }}">@endif
  </form>
  <div class="tabs">
    <a class="{{ !$m ? 'on' : '' }}" href="{{ route('admin.pesanan.fix', request()->only('q')) }}">Semua</a>
    <a class="{{ $m == 'Tunai' ? 'on' : '' }}" href="{{ route('admin.pesanan.fix', request()->only('q') + ['metode' => 'Tunai']) }}">Tunai</a>
    <a class="{{ $m == 'Nontunai' ? 'on' : '' }}" href="{{ route('admin.pesanan.fix', request()->only('q') + ['metode' => 'Nontunai']) }}">Nontunai</a>
  </div>
  <div class="scroll"><table>
    <thead><tr><th>Kode pesanan</th><th>Nama pelanggan</th><th>Pengambilan</th><th>Metode bayar</th><th>Tanggal selesai</th><th>Total harga</th><th></th></tr></thead>
    <tbody>
    @forelse($pesanan as $o)
      <tr><td><b>{{ $o->kode_pesanan }}</b></td><td>{{ $o->nama_pelanggan }}</td>
        <td><span class="pill {{ $o->pengambilan == 'Diantar' ? 'antar' : 'ambil' }}">{{ $o->pengambilan }}</span></td>
        <td><span class="pill {{ $o->metode_bayar == 'Tunai' ? 'tunai' : 'non' }}">{{ $o->metode_bayar }}</span></td>
        <td>{{ $o->tanggal_selesai->translatedFormat('d M Y') }}</td><td><b>{{ $rp($o->total_harga) }}</b></td>
        <td><button class="btn" type="button" data-k="{{ $o->id_pesanan }}">Detail</button></td></tr>
      @include('partials.admin.pesanan-detail', ['o' => $o, 'cols' => 7])
    @empty
      <tr><td colspan="7" class="note">Belum ada pesanan fix. Pesanan pindah ke sini setelah Anda menekan Selesai.</td></tr>
    @endforelse
    </tbody>
  </table></div>
  @include('partials.admin.pager', ['p' => $pesanan])
</div>
@endsection
