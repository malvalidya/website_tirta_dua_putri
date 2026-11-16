@extends('layouts.auth')
@section('title', 'Masuk – Panel Admin')
@section('content')
<div class="login"><div class="card">
  <div class="brand"><i class="fa-solid fa-droplet"></i> Tirta Dua Putri</div>
  <h1>Masuk admin</h1><p>Kelola produk dan catat pesanan yang sudah selesai.</p>
  <form method="POST" action="{{ route('admin.login.proses') }}">@csrf
    <div class="fld"><label for="email">Email</label><input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
      @error('email')<div class="err-t">{{ $message }}</div>@enderror</div>
    <div class="fld"><label for="password">Kata sandi</label><input id="password" type="password" name="password" required autocomplete="current-password">
      @error('password')<div class="err-t">{{ $message }}</div>@enderror</div>
    <button class="btn fill" type="submit">Masuk</button>
  </form>
</div></div>
@endsection
