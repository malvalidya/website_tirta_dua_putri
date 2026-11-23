@extends('layouts.admin')
@section('title', 'Pesanan belum fix')
@section('content')
@php $rp = fn($n) => 'Rp '.number_format($n, 0, ',', '.'); @endphp
<div class="card">
  <div class="head"><h2>Menunggu konfirmasi ({{ $pesanan->total() }})</h2><a class="btn" href="{{ route('admin.pesanan.fix') }}">Lihat pesanan fix</a></div>
  <div class="flash warn">Cek pesanan di sini dulu. Hubungi pelanggan lewat WhatsApp untuk memastikan pesanannya asli. Tekan <b>Selesai</b> setelah pesanan diantar atau diambil dan sudah dibayar, atau <b>Tolak</b> kalau pesanan palsu.</div>
  <div class="scroll"><table>
    <thead><tr><th>Kode</th><th>Masuk</th><th>Pelanggan</th><th>Pengambilan</th><th>Metode bayar</th><th>Total</th><th>Aksi</th></tr></thead>
    <tbody>
    @forelse($pesanan as $o)
      <tr>
        <td><b>{{ $o->kode_pesanan }}</b></td>
        <td>{{ $o->dibuat_pada->translatedFormat('d M, H:i') }}</td>
        <td>{{ $o->nama_pelanggan }}<br><small style="color:var(--mut)">{{ $o->no_hp }}</small></td>
        <td><span class="pill {{ $o->pengambilan == 'Diantar' ? 'antar' : 'ambil' }}">{{ $o->pengambilan }}</span></td>
        <td><span class="pill {{ $o->metode_bayar == 'Tunai' ? 'tunai' : 'non' }}">{{ $o->metode_bayar }}</span></td>
        <td><b>{{ $rp($o->total_harga) }}</b></td>
        <td class="aksi">
          <button class="btn" type="button" data-k="{{ $o->id_pesanan }}">Detail</button>
          <a class="btn wa" href="{{ $o->waLink() }}" target="_blank" rel="noopener"><i class="fa-brands fa-whatsapp"></i> Hubungi</a>
          <form class="inl" method="POST" action="{{ route('admin.pesanan.selesai', $o) }}" onsubmit="return confirm('Tandai {{ $o->kode_pesanan }} selesai? Pastikan pesanan sudah diantar atau diambil dan dibayar.')">@csrf<button class="btn ok" type="submit">Selesai</button></form>
          <form class="inl" method="POST" action="{{ route('admin.pesanan.destroy', $o) }}" onsubmit="return confirm('Tolak dan hapus pesanan {{ $o->kode_pesanan }}?')">@csrf @method('DELETE')<button class="btn del" type="submit">Tolak</button></form>
        </td>
      </tr>
      @include('partials.admin.pesanan-detail', ['o' => $o, 'cols' => 7])
    @empty
      <tr><td colspan="7" class="note">Tidak ada pesanan yang menunggu konfirmasi.</td></tr>
    @endforelse
    </tbody>
  </table></div>
  @include('partials.admin.pager', ['p' => $pesanan])
</div>
@endsection
