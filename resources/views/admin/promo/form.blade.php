@extends('layouts.admin')
@section('title', $promo->exists ? 'Ubah promo' : 'Buat promo')
@section('content')
<div class="grid g2">
<form method="POST" class="card" action="{{ $promo->exists ? route('admin.promo.update', $promo) : route('admin.promo.store') }}">
  @csrf @if($promo->exists) @method('PUT') @endif
  <div class="form">
    <div><label for="label">Label</label><input id="label" name="label" data-pv="pvLabel" value="{{ old('label', $promo->label) }}" required></div>
    <div><label for="diskon">Teks diskon</label><input id="diskon" name="diskon_teks" data-pv="pvDiskon" value="{{ old('diskon_teks', $promo->diskon_teks) }}" placeholder="Diskon 20%"></div>
    <div class="full"><label for="judul">Judul promo</label><input id="judul" name="judul" data-pv="pvJudul" value="{{ old('judul', $promo->judul) }}" required placeholder="Promo Isi Ulang Akhir Pekan"></div>
    <div class="full"><label for="desk">Deskripsi singkat</label><textarea id="desk" name="deskripsi" data-pv="pvDesk" maxlength="300">{{ old('deskripsi', $promo->deskripsi) }}</textarea></div>
    <div><label for="kode">Kode promo (opsional)</label><input id="kode" name="kode_promo" data-pv="pvKode" value="{{ old('kode_promo', $promo->kode_promo) }}"></div>
    <div><label for="tombol">Teks tombol</label><input id="tombol" name="teks_tombol" data-pv="pvTombol" value="{{ old('teks_tombol', $promo->teks_tombol) }}" required></div>
    <div><label for="mulai">Mulai (opsional)</label><input id="mulai" type="date" name="mulai" value="{{ old('mulai', $promo->mulai?->toDateString()) }}"></div>
    <div><label for="selesai">Selesai (opsional)</label><input id="selesai" type="date" name="selesai" value="{{ old('selesai', $promo->selesai?->toDateString()) }}"></div>
    <div class="full"><label style="display:flex;gap:10px;align-items:center;color:var(--navy)"><input type="checkbox" name="aktif" value="1" style="width:auto" @checked(old('aktif', $promo->aktif))> Tayangkan sekarang (promo lain otomatis dihentikan)</label></div>
    <div class="full"><button class="btn fill" type="submit">Simpan promo</button> <a class="btn" href="{{ route('admin.promo.index') }}">Batal</a></div>
  </div>
  <p class="note">Promo ini hanya tampilan informasi. Harga produk tidak berubah otomatis, ubah harga di menu Produk bila perlu.</p>
</form>
<div class="card"><h2>Pratinjau</h2>
  <div class="promo-prev">
    <span class="lv"><i></i><b id="pvLabel">{{ $promo->label ?: 'Promo Live' }}</b></span>
    <h3 id="pvJudul">{{ $promo->judul ?: 'Judul promo' }}</h3>
    <div class="dc" id="pvDiskon">{{ $promo->diskon_teks }}</div>
    <p id="pvDesk">{{ $promo->deskripsi }}</p>
    <div class="kd">Kode: <b id="pvKode">{{ $promo->kode_promo }}</b></div>
    <span class="tb" id="pvTombol">{{ $promo->teks_tombol ?: 'Pesan sekarang' }}</span>
  </div>
</div>
</div>
@endsection
