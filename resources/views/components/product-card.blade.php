@props(['p'])
<div class="card prod {{ $p->aktif ? '' : 'habis' }}" data-d="{{ $p->id_produk }}" data-kat="{{ $p->kategori }}" tabindex="0" aria-label="Lihat detail {{ $p->nama_produk }}">
  <div class="pic"><span class="kat">{{ $p->kategori }}</span>@unless($p->aktif)<span class="stok">Habis</span>@endunless
    @if($p->foto_url)<img src="{{ $p->foto_url }}" alt="{{ $p->nama_produk }}" loading="lazy" onerror="this.hidden=true;this.nextElementSibling.hidden=false"><i class="fa-solid {{ $p->ikon }}" hidden></i>@else<i class="fa-solid {{ $p->ikon }}"></i>@endif
  </div>
  <div><b>{{ $p->nama_produk }}</b><small>{{ $p->deskripsi }}</small></div>
  <div class="bt"><b class="h">Rp {{ number_format($p->harga, 0, ',', '.') }}</b>
    <button type="button" class="plus" data-add="{{ $p->id_produk }}" aria-label="Tambah {{ $p->nama_produk }} ke keranjang" {{ $p->aktif ? '' : 'disabled' }}>+</button></div>
</div>
