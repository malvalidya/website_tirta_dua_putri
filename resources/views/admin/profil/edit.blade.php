@extends('layouts.admin')
@section('title', 'Profil usaha')
@section('content')
<form method="POST" action="{{ route('admin.profil.update') }}" class="card">@csrf @method('PUT')
  <h2>Profil usaha</h2>
  <div class="form">
    <div><label for="a">Nama usaha</label><input id="a" name="nama_usaha" value="{{ old('nama_usaha', $profil->nama_usaha) }}" required></div>
    <div><label for="b">Nomor WhatsApp tujuan</label><input id="b" name="nomor_wa" value="{{ old('nomor_wa', $profil->nomor_wa) }}" required></div>
    <div class="full"><label for="c">Deskripsi singkat</label><textarea id="c" name="deskripsi_singkat">{{ old('deskripsi_singkat', $profil->deskripsi_singkat) }}</textarea></div>
    <div class="full"><label for="d">Alamat</label><input id="d" name="alamat" value="{{ old('alamat', $profil->alamat) }}"></div>
    <div class="full"><label for="e">Jam operasional</label><input id="e" name="jam_operasional" value="{{ old('jam_operasional', $profil->jam_operasional) }}"></div>
    <div class="full"><button class="btn fill" type="submit">Simpan profil</button></div>
  </div>
  <p class="note">Nomor WhatsApp memakai format internasional tanpa +, tanpa 0 di depan (contoh: 62812…). Awalan 0 otomatis diubah ke 62.</p>
</form>
@endsection
