@extends('layouts.customer')
@section('title', 'Info usaha – '.$profil->nama_usaha)
@section('content')
@include('partials.customer.back')
<div class="sh"><h2>Informasi usaha</h2></div>
<div class="two">
  <div class="card about">
    <b class="nm">{{ $profil->nama_usaha }}</b>
    @if($profil->deskripsi_singkat)<p class="desc">{{ $profil->deskripsi_singkat }}</p>@endif
    @include('partials.customer.profil-detail')
  </div>
  @include('partials.customer.map')
</div>
<div class="card contact"><b>Hubungi kami</b>
  <p>Ada pertanyaan sebelum memesan? Chat langsung ke WhatsApp depo.</p>
  <a class="btn wa" target="_blank" rel="noopener" href="https://wa.me/{{ $profil->waNomor() }}?text={{ rawurlencode('Halo '.$profil->nama_usaha.', saya mau bertanya.') }}"><i class="fa-brands fa-whatsapp"></i> Chat WhatsApp</a>
</div>
@endsection
