@extends('layouts.admin')
@section('title', 'Catat transaksi')
@section('content')
<form method="POST" action="{{ route('admin.pesanan.store') }}" class="card">@csrf
  <h2>Transaksi selesai</h2>
  <div class="form">
    <div><label>Kode pesanan</label><input value="{{ $kode }}" disabled></div>
    <div><label for="tgl">Tanggal selesai</label><input id="tgl" type="date" name="tanggal_selesai" value="{{ old('tanggal_selesai', now()->toDateString()) }}" required></div>
    <div><label for="nm">Nama pelanggan</label><input id="nm" name="nama_pelanggan" value="{{ old('nama_pelanggan') }}" required></div>
    <div><label for="hp">No. HP</label><input id="hp" name="no_hp" value="{{ old('no_hp') }}" placeholder="08xxxxxxxxxx" required></div>
    <div class="full"><label for="al">Alamat</label><textarea id="al" name="alamat" required>{{ old('alamat') }}</textarea></div>
    <div><label for="mb">Metode bayar</label>
      <select id="mb" name="metode_bayar">@foreach(['Tunai','Nontunai'] as $m)<option @selected(old('metode_bayar') == $m)>{{ $m }}</option>@endforeach</select></div>
    <div><label>Total harga</label><input id="totalHarga" value="Rp 0" disabled style="font-weight:700"></div>
    <div class="full"><label>Detail pesanan</label>
      <div class="scroll"><table class="sub" style="min-width:560px">
        <thead><tr><th>Produk</th><th>Jumlah</th><th>Harga satuan</th><th>Subtotal</th><th></th></tr></thead>
        <tbody id="itemBody"></tbody></table></div>
      <button type="button" class="btn" id="addRow" style="margin-top:10px">+ Tambah produk</button>
    </div>
    <div class="full"><button class="btn fill" type="submit">Simpan transaksi</button> <a class="btn" href="{{ route('admin.pesanan.fix') }}">Batal</a></div>
  </div>
  <p class="note">Kode pesanan dibuat otomatis oleh sistem. Harga satuan disalin dari produk agar riwayat tidak berubah saat harga diperbarui.</p>
</form>
<template id="rowTpl">
  <tr>
    <td><select name="item[__i__][id_produk]" required>@foreach($produk as $p)<option value="{{ $p->id_produk }}" data-harga="{{ $p->harga }}">{{ $p->nama_produk }}</option>@endforeach</select></td>
    <td><input type="number" name="item[__i__][jumlah]" value="1" min="1" required></td>
    <td class="hs"></td><td class="c-sub"></td>
    <td><button type="button" class="btn del rm">Hapus</button></td>
  </tr>
</template>
@endsection
