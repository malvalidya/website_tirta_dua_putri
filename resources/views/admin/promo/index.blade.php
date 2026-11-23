@extends('layouts.admin')
@section('title', 'Promo')
@section('content')
<div class="card">
  <div class="head"><h2>Promo di halaman utama</h2><a class="btn fill" href="{{ route('admin.promo.create') }}">+ Buat promo</a></div>
  <div class="flash warn">Promo yang berstatus <b>Tayang</b> muncul di panel biru halaman utama pembeli. Hanya satu promo yang tayang pada satu waktu. Menayangkan promo lain otomatis menghentikan yang lama.</div>
  <div class="scroll"><table>
    <thead><tr><th>Promo</th><th>Diskon</th><th>Kode</th><th>Periode</th><th>Status</th><th></th></tr></thead>
    <tbody>
    @forelse($promo as $p)
      <tr>
        <td><b>{{ $p->judul }}</b><br><small style="color:var(--mut)">{{ $p->label }}</small></td>
        <td>{{ $p->diskon_teks ?: '-' }}</td><td>{{ $p->kode_promo ?: '-' }}</td>
        <td>{{ $p->mulai ? $p->mulai->translatedFormat('d M Y') : 'Sekarang' }} – {{ $p->selesai ? $p->selesai->translatedFormat('d M Y') : 'Tanpa batas' }}</td>
        <td><span class="pill {{ $p->status == 'Tayang' ? 'aktif' : ($p->status == 'Terjadwal' ? 'pend' : 'habis') }}">{{ $p->status }}</span></td>
        <td class="aksi">
          <form class="inl" method="POST" action="{{ route('admin.promo.toggle', $p) }}">@csrf<button class="btn {{ $p->aktif ? '' : 'ok' }}" type="submit">{{ $p->aktif ? 'Hentikan' : 'Tayangkan' }}</button></form>
          <a class="btn" href="{{ route('admin.promo.edit', $p) }}">Ubah</a>
          <form class="inl" method="POST" action="{{ route('admin.promo.destroy', $p) }}" onsubmit="return confirm('Hapus promo ini?')">@csrf @method('DELETE')<button class="btn del" type="submit">Hapus</button></form>
        </td>
      </tr>
    @empty<tr><td colspan="6" class="note">Belum ada promo. Tanpa promo, panel biru menampilkan keunggulan depo seperti biasa.</td></tr>@endforelse
    </tbody>
  </table></div>
</div>
@endsection
