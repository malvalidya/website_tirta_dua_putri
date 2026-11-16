<div class="map">
  @if($profil->alamat)
    <iframe title="Lokasi depo" loading="lazy" referrerpolicy="no-referrer-when-downgrade" src="https://maps.google.com/maps?q={{ urlencode($profil->alamat) }}&output=embed"></iframe>
  @else
    <span>Lokasi depo belum diisi</span>
  @endif
</div>
