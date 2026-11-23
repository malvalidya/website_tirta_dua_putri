@extends('layouts.admin')
@section('title', $produk->exists ? 'Ubah produk' : 'Tambah produk')
@section('content')
<form method="POST" class="card" action="{{ $produk->exists ? route('admin.produk.update', $produk) : route('admin.produk.store') }}">
  @csrf @if($produk->exists) @method('PUT') @endif
  <div class="form">
    <div><label for="n">Nama produk</label><input id="n" name="nama_produk" value="{{ old('nama_produk', $produk->nama_produk) }}" required></div>
    <div><label for="k">Kategori</label><input id="k" name="kategori" list="kat" value="{{ old('kategori', $produk->kategori) }}" required>
      <datalist id="kat">@foreach($kategori as $k)<option value="{{ $k }}">@endforeach</datalist></div>
    <div class="full"><label for="d">Deskripsi</label><textarea id="d" name="deskripsi">{{ old('deskripsi', $produk->deskripsi) }}</textarea></div>
    <div><label for="h">Harga (Rp)</label><input id="h" type="number" min="0" name="harga" value="{{ old('harga', $produk->harga) }}" required></div>
    <div><label for="s">Status produk</label>
      <select id="s" name="status_produk">@foreach(['Aktif','Habis'] as $s)<option @selected(old('status_produk', $produk->status_produk) == $s)>{{ $s }}</option>@endforeach</select></div>
    <div class="full"><label for="f">Foto (URL, opsional)</label><input id="f" name="foto_url" value="{{ old('foto_url', $produk->foto_url) }}" placeholder="https://…"></div>
    <div class="full"><button class="btn fill" type="submit">Simpan produk</button> <a class="btn" href="{{ route('admin.produk.index') }}">Batal</a></div>
  </div>
</form>
@endsection
