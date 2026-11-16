@extends('layouts.customer')
@section('content')
@include('partials.customer.hero')
<div class="sh" id="produk"><h2>Produk dan layanan</h2>
  <div class="filter" id="filter" role="group" aria-label="Filter kategori">
    <button type="button" class="on" data-k="Semua" aria-pressed="true">Semua</button>
    @foreach($kategori as $k)<button type="button" data-k="{{ $k }}" aria-pressed="false">{{ $k }}</button>@endforeach
  </div>
</div>
<div class="list" id="list">
  @forelse($produk as $p)<x-product-card :p="$p" />@empty<p class="empty"><b>Produk belum tersedia</b>Silakan kembali lagi nanti.</p>@endforelse
</div>
<div class="sh"><h2>Tentang depo</h2></div>
<div class="two">
  <div class="card">@include('partials.customer.profil-detail')</div>
  @include('partials.customer.map')
</div>
@endsection
