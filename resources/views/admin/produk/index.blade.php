@extends('layouts.admin')
@section('title', 'Produk')
@section('content')
<div class="card">
  <div class="head"><h2>Daftar produk</h2><a class="btn fill" href="{{ route('admin.produk.create') }}">+ Tambah produk</a></div>
  <div class="scroll"><table>
    <thead><tr><th>Produk</th><th>Kategori</th><th>Deskripsi</th><th>Harga</th><th>Status</th><th></th></tr></thead>
    <tbody>
    @forelse($produk as $p)
      <tr>
        <td><div style="display:flex;align-items:center;gap:12px"><div class="thumb">@if($p->foto_url)<img src="{{ $p->foto_url }}" alt="">@else<i class="fa-solid {{ $p->ikon }}"></i>@endif</div><b>{{ $p->nama_produk }}</b></div></td>
        <td>{{ $p->kategori }}</td><td style="max-width:240px;color:var(--mut)">{{ $p->deskripsi }}</td>
        <td><b>Rp {{ number_format($p->harga, 0, ',', '.') }}</b></td>
        <td><span class="pill {{ $p->aktif ? 'aktif' : 'habis' }}">{{ $p->status_produk }}</span></td>
        <td style="white-space:nowrap"><a class="btn" href="{{ route('admin.produk.edit', $p) }}">Ubah</a>
          <form method="POST" action="{{ route('admin.produk.destroy', $p) }}" style="display:inline" data-konfirmasi="Hapus produk {{ $p->nama_produk }}?">@csrf @method('DELETE')<button class="btn del" type="submit">Hapus</button></form></td>
      </tr>
    @empty<tr><td colspan="6" class="note">Belum ada produk.</td></tr>@endforelse
    </tbody>
  </table></div>
</div>
@endsection
