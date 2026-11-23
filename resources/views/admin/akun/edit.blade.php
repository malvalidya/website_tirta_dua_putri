@extends('layouts.admin')
@section('title', 'Akun admin')
@section('content')
<form method="POST" action="{{ route('admin.akun.update') }}" class="card" style="max-width:520px">@csrf @method('PUT')
  <h2>Akun admin</h2>
  <div class="form" style="grid-template-columns:1fr">
    <div><label for="em">ID</label><input id="em" type="email" name="email" value="{{ old('email', $admin->email) }}" required>@error('email')<div class="err-t">{{ $message }}</div>@enderror</div>
    <div><label for="pl">Kata sandi lama</label><input id="pl" type="password" name="password_lama" autocomplete="current-password">@error('password_lama')<div class="err-t">{{ $message }}</div>@enderror</div>
    <div><label for="pw">Kata sandi baru</label><input id="pw" type="password" name="password" placeholder="Kosongkan jika tidak diganti" autocomplete="new-password">@error('password')<div class="err-t">{{ $message }}</div>@enderror</div>
    <div><label for="pc">Ulangi kata sandi baru</label><input id="pc" type="password" name="password_confirmation" autocomplete="new-password"></div>
    <div><button class="btn fill" type="submit">Simpan</button></div>
  </div>
  <p class="note">Kata sandi disimpan terenkripsi, tidak pernah dalam teks biasa.</p>
</form>
@endsection
