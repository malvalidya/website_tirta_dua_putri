@extends('layouts.customer')
@section('title', 'Info usaha – '.$profil->nama_usaha)
@section('content')
@include('partials.customer.back')
<div class="sh"><h2>Informasi usaha</h2></div>
<div class="two">
  <div class="card">
    <b style="font-size:17px">{{ $profil->nama_usaha }}</b>
    <p style="color:var(--mut);margin:8px 0 14px;line-height:1.6">{{ $profil->deskripsi_singkat }}</p>
    @include('partials.customer.profil-detail')
  </div>
  @include('partials.customer.map')
</div>
<div class="card" style="margin-top:18px"><b>Hubungi kami</b>
  <p style="color:var(--mut);margin:6px 0 14px">Ada pertanyaan sebelum memesan? Chat langsung ke WhatsApp depo.</p>
  <a target="_blank" rel="noopener" href="https://wa.me/{{ $profil->waNomor() }}?text={{ rawurlencode('Halo '.$profil->nama_usaha.', saya mau bertanya.') }}"><button class="btn wa" type="button"><i class="fa-brands fa-whatsapp"></i> Chat WhatsApp</button></a>
</div>
@endsection
