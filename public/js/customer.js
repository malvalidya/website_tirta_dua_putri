(() => {
  const D = window.TDP || { produk: [] }, KEY = 'tdp_cart', MKEY = 'tdp_mode', MAX = 99;
  const ONGKIR = Number(D.ongkir ?? 5000);
  const rp = n => 'Rp ' + Number(n).toLocaleString('id-ID');
  const $ = (s, r = document) => r.querySelector(s);
  const $$ = (s, r = document) => [...r.querySelectorAll(s)];
  const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
  const find = id => (D.produk || []).find(p => String(p.id) === String(id));
  const isCart = !!$('#cartPage');
  let cart = {}, mode = 'ambil', onSync = () => {};

  // ---- keranjang (disimpan di browser) ----
  function muat() {
    let raw = {}, m = 'ambil';
    try {
      const j = JSON.parse(localStorage.getItem(KEY) || '{}');
      if (j && typeof j === 'object' && !Array.isArray(j)) raw = j;
      m = localStorage.getItem(MKEY) === 'antar' ? 'antar' : 'ambil';
    } catch (e) {}
    cart = {};
    Object.entries(raw).forEach(([id, q]) => {
      const p = find(id), n = Math.floor(Number(q));
      if (p && p.aktif && n >= 1) cart[id] = Math.min(n, MAX);
    });
    mode = m;
  }
  const sub = () => Object.entries(cart).reduce((s, [id, q]) => s + (find(id)?.harga || 0) * q, 0);
  const jumlah = () => Object.values(cart).reduce((a, b) => a + b, 0);
  const ongkir = () => (mode === 'antar' && jumlah()) ? ONGKIR : 0;
  const grand = () => sub() + ongkir();
  const pic = p => p.foto
    ? `<img src="${esc(p.foto)}" alt="" loading="lazy" onerror="this.hidden=true;this.nextElementSibling.hidden=false"><i class="fa-solid ${esc(p.icon)}" hidden></i>`
    : `<i class="fa-solid ${esc(p.icon)}"></i>`;

  function toast(t) {
    const e = $('#toast'); if (!e) return;
    e.textContent = t; e.classList.add('on');
    clearTimeout(toast.t); toast.t = setTimeout(() => e.classList.remove('on'), 1900);
  }
  // footer = ringkasan keranjang (selalu tampil di bawah)
  function ui() {
    const n = jumlah(), f = $('#foot'), c = $('#cnt');
    if (c) c.textContent = n ? (n > 99 ? '99+' : n) : '';
    if (f) {
      f.dataset.n = n;
      $('#barN').textContent = n ? n + ' item' + (isCart ? ' · total pesanan' : '') : 'Keranjang kosong';
      $('#barT').textContent = n ? rp(isCart ? grand() : sub()) : 'Belum ada produk';
    }
  }
  function simpan() { try { localStorage.setItem(KEY, JSON.stringify(cart)); localStorage.setItem(MKEY, mode); } catch (e) {} ui(); }
  function tambah(id) {
    const p = find(id);
    if (!p || !p.aktif) { toast('Produk sedang habis'); return; }
    if ((cart[id] || 0) >= MAX) { toast('Maksimal ' + MAX + ' per produk'); return; }
    cart[id] = (cart[id] || 0) + 1; simpan(); toast(p.nama + ' masuk keranjang');
  }

  muat();

  // ---- Beranda ----
  const list = $('#list');
  if (list) {
    list.addEventListener('click', e => {
      const a = e.target.closest('[data-add]');
      if (a) { e.stopPropagation(); tambah(a.dataset.add); return; }
      const c = e.target.closest('[data-d]'); if (c) detail(c.dataset.d);
    });
    list.addEventListener('keydown', e => {
      if ((e.key === 'Enter' || e.key === ' ') && e.target.matches('[data-d]')) { e.preventDefault(); detail(e.target.dataset.d); }
    });
    $('#filter').addEventListener('click', e => {
      const b = e.target.closest('[data-k]'); if (!b) return;
      $$('#filter button').forEach(x => { x.classList.toggle('on', x === b); x.setAttribute('aria-pressed', x === b); });
      $$('#list [data-kat]').forEach(c => c.hidden = b.dataset.k !== 'Semua' && c.dataset.kat !== b.dataset.k);
    });
  }
  const dlg = $('#dlg');
  function detail(id) {
    const p = find(id); if (!p || !dlg) return;
    $('#dlgIn').innerHTML = `<div class="pic">${pic(p)}</div><span class="k">${esc(p.kategori)}</span>
      <h2 id="dlgTitle">${esc(p.nama)}</h2><p>${esc(p.desk)}</p>
      <div class="dlg-foot"><b>${rp(p.harga)}</b>
      <span><button type="button" class="btn ghost" data-x>Tutup</button><button type="button" class="btn" data-a="${esc(p.id)}" ${p.aktif ? '' : 'disabled'}>${p.aktif ? 'Tambah' : 'Habis'}</button></span></div>`;
    dlg.showModal();
  }
  if (dlg) dlg.addEventListener('click', e => {
    const a = e.target.closest('[data-a]');
    if (a) { tambah(a.dataset.a); dlg.close(); return; }
    if (e.target === dlg || e.target.closest('[data-x]')) dlg.close();
  });

  // ---- Keranjang: 3 langkah ----
  if (isCart) {
    let step = 1, lokasi = null, map, pin, seq = 0, qseq = 0;
    const bayar = () => (document.querySelector('input[name=bayar]:checked') || {}).value || 'Tunai';
    const setRadio = () => { const r = document.querySelector(`input[name=mode][value=${mode}]`); if (r) r.checked = true; };
    setRadio();

    function showStep() {
      [1, 2, 3].forEach(i => $('#s' + i).hidden = i !== step);
      [...$('#steps').children].forEach((d, i) => d.className = i + 1 === step ? 'on' : i + 1 < step ? 'done' : '');
      if (step === 1) renderItems();
      if (step === 2) syncMode();
      if (step === 3) renderConfirm();
      ui();
    }
    function renderSummary() {
      $('#s1sum').innerHTML = !jumlah() ? '' : `<div class="sum"><span>Subtotal</span><span>${rp(sub())}</span></div>
        <div class="sum"><span>Ongkos kirim</span><span>${mode === 'antar' ? rp(ONGKIR) : 'Gratis'}</span></div>
        <div class="sum total"><span>Total</span><span>${rp(grand())}</span></div>`;
    }
    function renderItems() {
      const ids = Object.keys(cart);
      $('#items').innerHTML = ids.length ? ids.map(id => {
        const p = find(id), n = cart[id];
        return `<div class="line"><div class="pic">${pic(p)}</div>
          <div class="t"><b>${esc(p.nama)}</b><small>${rp(p.harga)}</small></div>
          <div class="qty"><button type="button" data-q="${esc(id)}" data-delta="-1" aria-label="Kurangi ${esc(p.nama)}" ${n <= 1 ? 'disabled' : ''}>−</button><b aria-live="polite">${n}</b><button type="button" data-q="${esc(id)}" data-delta="1" aria-label="Tambah ${esc(p.nama)}" ${n >= MAX ? 'disabled' : ''}>+</button></div>
          <div class="lt">${rp(p.harga * n)}</div>
          <button type="button" class="rm" data-rm="${esc(id)}" aria-label="Hapus ${esc(p.nama)}"><i class="fa-regular fa-trash-can"></i></button></div>`;
      }).join('')
        : `<div class="empty"><b>Keranjang masih kosong</b>Pilih produk di beranda untuk mulai memesan.<br><a class="btn" href="${esc(D.homeUrl || '/')}#produk">Lihat produk</a></div>`;
      $('#modeBox').hidden = !ids.length;
      $('#next1').hidden = !ids.length;
      renderSummary();
    }
    $('#items').addEventListener('click', e => {
      const q = e.target.closest('[data-q]'), r = e.target.closest('[data-rm]');
      if (q) {
        const id = q.dataset.q, n = (cart[id] || 0) + Number(q.dataset.delta);
        if (n < 1 || n > MAX) return;
        cart[id] = n; simpan(); renderItems();
      }
      if (r) { delete cart[r.dataset.rm]; simpan(); renderItems(); }
    });
    $('#modeBox').addEventListener('change', e => { if (e.target.name === 'mode') { mode = e.target.value === 'antar' ? 'antar' : 'ambil'; simpan(); renderSummary(); } });

    // ---- Alamat ala Grab: cari, lokasi saya, pin di peta, detail, catatan ----
    function syncMode() {
      $('#alamatBox').hidden = mode !== 'antar';
      $('#ambilInfo').hidden = mode !== 'ambil';
      if (mode === 'antar') initMap();
    }
    function initMap() {
      if (map) { setTimeout(() => map.invalidateSize(), 60); return; }
      if (!window.L) { $('#peta').innerHTML = '<div class="ga-off">Peta belum bisa dimuat. Anda tetap bisa memakai pencarian alamat atau Lokasi saya, atau periksa koneksi lalu muat ulang halaman.</div>'; return; }
      map = L.map('peta', { zoomControl: false, scrollWheelZoom: false }).setView(D.pusat, 15);
      L.control.zoom({ position: 'bottomright' }).addTo(map);
      L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '© OpenStreetMap' }).addTo(map);
      pin = L.marker(D.pusat, { draggable: true, icon: L.divIcon({ className: 'pin', html: '<i class="fa-solid fa-location-dot"></i>', iconSize: [40, 40], iconAnchor: [20, 38] }) }).addTo(map);
      pin.on('dragend', () => { const p = pin.getLatLng(); pilih(p.lat, p.lng); });
      map.on('click', e => pilih(e.latlng.lat, e.latlng.lng));
      // zoom roda mouse baru aktif setelah peta diklik, supaya halaman tetap bisa di-scroll
      map.on('focus', () => map.scrollWheelZoom.enable());
      map.on('blur', () => map.scrollWheelZoom.disable());
      setTimeout(() => map.invalidateSize(), 60);
    }
    function tampilPick() {
      $('#pickNama').textContent = lokasi ? (lokasi.nama || 'Titik terpilih') : 'Belum ada lokasi dipilih';
      $('#pickAlamat').textContent = lokasi ? lokasi.alamat : 'Cari alamat, pakai lokasi saya, atau geser pin pada peta';
      if (lokasi) $('#fLokasi').classList.remove('err');
    }
    async function pilih(lat, lng, label) {
      const my = ++seq;
      if (pin) { pin.setLatLng([lat, lng]); map.setView([lat, lng], Math.max(map.getZoom(), 17)); }
      lokasi = { lat, lng, nama: label ? label.split(',')[0] : '', alamat: label || 'Memuat alamat…' }; tampilPick();
      if (label) return;
      try {
        const r = await fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&zoom=18&accept-language=id&lat=${lat}&lon=${lng}`);
        if (!r.ok) throw new Error(r.status);
        const j = await r.json(); if (my !== seq) return;
        lokasi.alamat = j.display_name || `${lat.toFixed(5)}, ${lng.toFixed(5)}`; lokasi.nama = (j.display_name || '').split(',')[0] || 'Titik di peta';
      } catch (e) { if (my !== seq) return; lokasi.alamat = `${lat.toFixed(5)}, ${lng.toFixed(5)}`; lokasi.nama = 'Titik di peta'; }
      tampilPick();
    }
    const sug = $('#sug'); let tmr, hasil = [];
    $('#cari').addEventListener('input', e => {
      clearTimeout(tmr); const q = e.target.value.trim(), my = ++qseq;
      if (q.length < 3) { sug.hidden = true; return; }
      tmr = setTimeout(async () => {
        const [la, ln] = D.pusat, vb = [ln - .25, la + .25, ln + .25, la - .25].join(',');
        let html;
        try {
          const r = await fetch(`https://nominatim.openstreetmap.org/search?format=jsonv2&limit=5&countrycodes=id&accept-language=id&viewbox=${vb}&q=${encodeURIComponent(q)}`);
          if (!r.ok) throw new Error(r.status);
          const data = await r.json(); if (my !== qseq) return;
          hasil = Array.isArray(data) ? data : [];
          html = hasil.length ? hasil.map((x, i) => `<li data-i="${i}" tabindex="0"><i class="fa-solid fa-location-dot"></i><div><b>${esc(x.name || x.display_name.split(',')[0])}</b><small>${esc(x.display_name)}</small></div></li>`).join('')
            : '<li class="none">Alamat tidak ditemukan. Coba kata kunci lain atau geser pin di peta.</li>';
        } catch (err) { if (my !== qseq) return; html = '<li class="none">Pencarian gagal. Periksa koneksi internet.</li>'; }
        sug.innerHTML = html; sug.hidden = false;
      }, 500);
    });
    const pakai = li => {
      const x = hasil[li.dataset.i]; if (!x) return;
      sug.hidden = true; $('#cari').value = x.name || x.display_name.split(',')[0];
      pilih(+x.lat, +x.lon, x.display_name);
    };
    sug.addEventListener('click', e => { const li = e.target.closest('[data-i]'); if (li) pakai(li); });
    sug.addEventListener('keydown', e => { if (e.key === 'Enter') { const li = e.target.closest('[data-i]'); if (li) pakai(li); } });
    $('#cari').addEventListener('keydown', e => { if (e.key === 'Escape') sug.hidden = true; });
    $('#lokasiku').addEventListener('click', () => {
      if (!navigator.geolocation) { toast('Browser tidak mendukung lokasi'); return; }
      toast('Mencari lokasi Anda…');
      navigator.geolocation.getCurrentPosition(p => pilih(p.coords.latitude, p.coords.longitude), () => toast('Izin lokasi ditolak. Cari alamat atau geser pin.'), { enableHighAccuracy: true, timeout: 10000 });
    });

    function valid() {
      const n = $('#nama').value.trim(), h = $('#hp').value.replace(/[\s-]/g, '');
      const ok = { fNama: n.length > 1, fHp: /^(\+62|62|0)8\d{8,12}$/.test(h) };
      if (mode === 'antar') { ok.fLokasi = !!lokasi; ok.fDetail = $('#detail').value.trim().length > 2; }
      ['fNama', 'fHp', 'fLokasi', 'fDetail'].forEach(k => $('#' + k).classList.toggle('err', k in ok && !ok[k]));
      const salah = Object.keys(ok).find(k => !ok[k]);
      if (salah) { const t = salah === 'fNama' ? '#nama' : salah === 'fHp' ? '#hp' : salah === 'fLokasi' ? '#cari' : '#detail'; $(t).focus(); }
      return !salah;
    }
    function toStep(n) {
      if (!jumlah()) { if (n > 1) toast('Keranjang masih kosong'); step = 1; showStep(); return; }
      if (n === 3 && !valid()) { toast('Lengkapi data yang ditandai merah'); return; }
      step = n; showStep(); window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    function pesan() {
      const L = [`Halo ${D.namaUsaha}, saya ingin memesan:`,
        ...Object.entries(cart).map(([id, q], i) => `${i + 1}. ${find(id).nama} x${q}`),
        `Nama: ${$('#nama').value.trim()}`,
        `No. HP: ${$('#hp').value.trim()}`,
        `Pengambilan: ${mode === 'antar' ? 'Diantar ke alamat' : 'Ambil di tempat'}`];
      if (mode === 'antar' && lokasi) {
        L.push(`Alamat: ${lokasi.alamat}`, `Detail alamat: ${$('#detail').value.trim()}`, `Titik lokasi: https://maps.google.com/?q=${lokasi.lat},${lokasi.lng}`);
        if ($('#catatan').value.trim()) L.push(`Catatan kurir: ${$('#catatan').value.trim()}`);
      }
      L.push(`Pembayaran: ${bayar()}`, `Subtotal: ${rp(sub())}`, `Ongkos kirim: ${mode === 'antar' ? rp(ONGKIR) : 'Gratis (ambil di tempat)'}`, `Total: ${rp(grand())}`);
      return L.join('\n');
    }
    function renderConfirm() {
      $('#s3sum').innerHTML = Object.entries(cart).map(([id, q]) => `<div class="sum"><span>${esc(find(id).nama)} × ${q}</span><span>${rp(find(id).harga * q)}</span></div>`).join('')
        + `<div class="sum"><span>Ongkos kirim</span><span>${mode === 'antar' ? rp(ONGKIR) : 'Gratis'}</span></div>
           <div class="sum total"><span>Total</span><span>${rp(grand())}</span></div>
           <div class="sum mut"><span>Pembayaran</span><span>${esc(bayar())}</span></div>
           <div class="sum mut"><span>Pengambilan</span><span>${mode === 'antar' ? 'Diantar' : 'Ambil di tempat'}</span></div>`;
      $('#pre').textContent = pesan();
    }
    document.addEventListener('click', e => {
      const s = e.target.closest('[data-step]'); if (s) toStep(+s.dataset.step);
      if (e.target.closest('[data-kirim]')) {
        // KF-08: tidak ada penyimpanan ke basis data, hanya membuka WhatsApp
        if (!/^\d{9,15}$/.test(D.wa || '')) { toast('Nomor WhatsApp depo belum diatur oleh admin'); return; }
        if (!jumlah() || (mode === 'antar' && !lokasi)) { toast('Data pesanan belum lengkap'); step = 1; showStep(); return; }
        window.open('https://wa.me/' + D.wa + '?text=' + encodeURIComponent(pesan()), '_blank', 'noopener');
      }
      if (!e.target.closest('.ga-top')) sug.hidden = true;
    });
    // keranjang berubah di tab lain / halaman dipulihkan dari cache → sinkronkan
    onSync = () => { setRadio(); if (!jumlah()) step = 1; showStep(); };
    showStep();
  }

  window.addEventListener('storage', e => { if (e.key === KEY || e.key === MKEY) { muat(); ui(); onSync(); } });
  window.addEventListener('pageshow', e => { if (e.persisted) { muat(); ui(); onSync(); } });
  ui();
})();
