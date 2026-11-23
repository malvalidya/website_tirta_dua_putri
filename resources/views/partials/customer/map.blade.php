<div class="map">
  @if($profil->alamat)
    <iframe title="Lokasi depo" loading="lazy" src="https://maps.google.com/maps?q={{ urlencode($profil->alamat) }}&output=embed"></iframe>
  @else Lokasi depo belum diisi @endif
</div>
