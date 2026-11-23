(() => {
  const D = window.TDP || { produk: [] }, KEY = 'tdp_cart', MKEY = 'tdp_mode';
  const ONGKIR = Number(D.ongkir ?? 5000);
  const rp = n => 'Rp ' + Number(n).toLocaleString('id-ID');
  const $ = s => document.querySelector(s), $$ = s => document.querySelectorAll(s);
  const esc = s => String(s ?? '').replace(/[&<>"']/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));
  const find = id => D.produk.find(p => p.id == id);
  let cart = {}, mode = 'ambil';
  try { cart = JSON.parse(localStorage.getItem(KEY) || '{}'); mode = localStorage.getItem(MKEY) === 'antar' ? 'antar' : 'ambil'; } catch (e) {}
  Object.keys(cart).forEach(id => { const p = find(id); if (!p || !p.aktif || !(cart[id] >= 1)) delete cart[id]; });

  const sub = () => Object.entries(cart).reduce((s, [id, q]) => s + find(id).harga * q, 0);
  const jumlah = () => Object.values(cart).reduce((a, b) => a + b, 0);
  const ongkir = () => (mode === 'antar' && jumlah()) ? ONGKIR : 0;
  const grand = () => sub() + ongkir();
  const pic = p => p.foto ? `<img src="${esc(p.foto)}" alt="">` : `<i class="fa-solid ${esc(p.icon)}"></i>`;

  function toast(t) { const e = $('#toast'); if (!e) return; e.textContent = t; e.classList.add('on'); clearTimeout(toast.t); toast.t = setTimeout(() => e.classList.remove('on'), 1700); }
  function ui() {
    const n = jumlah(), c = $('#cnt');
    if (c) { c.textContent = n || ''; c.dataset.n = n; }
  }
  function simpan() { try { localStorage.setItem(KEY, JSON.stringify(cart)); localStorage.setItem(MKEY, mode); } catch (e) {} ui(); }
  function tambah(id) { cart[id] = (cart[id] || 0) + 1; simpan(); toast(find(id).nama + ' masuk keranjang'); }

  // ---- Beranda ----
  const list = $('#list');
  if (list) {
    list.onclick = e => {
      const a = e.target.closest('[data-add]');
      if (a) { e.stopPropagation(); tambah(a.dataset.add); return; }
      const c = e.target.closest('[data-d]'); if (c) detail(c.dataset.d);
    };
    let kat = 'Semua', cari = '';
    const terapkan = () => {
      let n = 0;
      $$('#list [data-kat]').forEach(c => { const ok = (kat === 'Semua' || c.dataset.kat === kat) && (!cari || c.dataset.q.includes(cari)); c.hidden = !ok; if (ok) n++; });
      $('#kosong').hidden = n > 0;
    };
    $('#filter').onclick = e => {
      const b = e.target.closest('[data-k]'); if (!b) return;
      kat = b.dataset.k; $$('#filter button').forEach(x => x.classList.toggle('on', x === b)); terapkan();
    };
    $('#cariProduk').oninput = e => { cari = e.target.value.trim().toLowerCase(); terapkan(); };
  }
  const dlg = $('#dlg');
  function detail(id) {
    const p = find(id); if (!p) return;
    $('#dlgIn').innerHTML = `<div class="pic">${pic(p)}</div><small style="color:var(--mut)">${esc(p.kategori)}</small>
      <h2 style="margin:2px 0 6px">${esc(p.nama)}</h2><p style="color:var(--mut);margin-bottom:18px">${esc(p.desk)}</p>
      <div style="display:flex;align-items:center;justify-content:space-between"><b style="font-size:20px">${rp(p.harga)}</b>
      <span><button class="btn ghost" data-x>Tutup</button> <button class="btn" data-a="${p.id}" ${p.aktif ? '' : 'disabled'}>Tambah</button></span></div>`;
    dlg.showModal();
  }
  if (dlg) dlg.addEventListener('click', e => {
    if (e.target === dlg || e.target.closest('[data-x]')) dlg.close();
    const a = e.target.closest('[data-a]'); if (a) { tambah(a.dataset.a); dlg.close(); }
  });

  // ---- Keranjang: 3 langkah ----
  if ($('#cartPage')) {
    let step = 1, lokasi = null, map, pin, seq = 0;
    const bayar = () => document.querySelector('input[name=bayar]:checked').value;
    const radio = document.querySelector(`input[name=mode][value=${mode}]`); if (radio) radio.checked = true;

    function showStep() {
      [1, 2, 3, 4].forEach(i => $('#s' + i).hidden = i !== step);
      [...$('#steps').children].forEach((d, i) => d.className = i + 1 === step ? 'on' : i + 1 < step ? 'done' : '');
      if (step === 1) renderItems();
      if (step === 2) syncMode();
      if (step === 3) renderConfirm();
    }
    function renderSummary() {
      $('#s1sum').innerHTML = !jumlah() ? '' : `<div class="sum"><span>Subtotal</span><span>${rp(sub())}</span></div>
        <div class="sum"><span>Ongkos kirim</span><span>${mode === 'antar' ? rp(ONGKIR) : 'Gratis'}</span></div>
        <div class="sum total"><span>Total</span><span>${rp(grand())}</span></div>`;
    }
    function renderItems() {
      const ids = Object.keys(cart);
      $('#items').innerHTML = ids.length ? ids.map(id => { const p = find(id); return `
        <div class="line"><div class="pic">${pic(p)}</div><div class="t"><b>${esc(p.nama)}</b><small>${rp(p.harga)}</small></div>
        <div class="qty"><button data-q="${id}" data-d="-1" aria-label="Kurangi">−</button><b>${cart[id]}</b><button data-q="${id}" data-d="1" aria-label="Tambah">+</button></div>
        <button class="qty" style="background:none;border:0;color:var(--bad);cursor:pointer;font-weight:600" data-rm="${id}"><i class="fa-regular fa-trash-can"></i> Hapus</button></div>`; }).join('')
        : `<div class="empty"><b>Keranjang masih kosong</b>Pilih produk di beranda untuk mulai memesan.</div>`;
      $('#modeBox').hidden = !ids.length;
      renderSummary(); ui();
    }
    $('#items').onclick = e => {
      const q = e.target.closest('[data-q]'), r = e.target.closest('[data-rm]');
      if (q) { const n = cart[q.dataset.q] + +q.dataset.d; if (n >= 1) cart[q.dataset.q] = n; simpan(); renderItems(); }
      if (r) { delete cart[r.dataset.rm]; simpan(); renderItems(); }
    };
    $('#modeBox').onchange = e => { if (e.target.name === 'mode') { mode = e.target.value; simpan(); renderSummary(); } };

    // ---- Alamat ala Grab: cari, lokasi saya, pin di peta, detail, catatan ----
    function syncMode() {
      $('#alamatBox').hidden = mode !== 'antar';
      $('#ambilInfo').hidden = mode !== 'ambil';
      if (mode === 'antar') initMap();
    }
    function initMap() {
      if (map) { setTimeout(() => map.invalidateSize(), 60); return; }
      if (!window.L) { $('#peta').innerHTML = '<div class="ga-off">Peta belum bisa dimuat. Periksa koneksi internet lalu muat ulang halaman.</div>'; return; }
      map = L.map('peta', { zoomControl: false }).setView(D.pusat, 15);
      L.control.zoom({ position: 'bottomright' }).addTo(map);
      L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19, attribution: '© OpenStreetMap' }).addTo(map);
      pin = L.marker(D.pusat, { draggable: true, icon: L.divIcon({ className: 'pin', html: '<i class="fa-solid fa-location-dot"></i>', iconSize: [40, 40], iconAnchor: [20, 38] }) }).addTo(map);
      pin.on('dragend', () => { const p = pin.getLatLng(); pilih(p.lat, p.lng); });
      map.on('click', e => pilih(e.latlng.lat, e.latlng.lng));
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
        const j = await r.json(); if (my !== seq) return;
        lokasi.alamat = j.display_name || `${lat.toFixed(5)}, ${lng.toFixed(5)}`; lokasi.nama = (j.display_name || '').split(',')[0];
      } catch (e) { if (my !== seq) return; lokasi.alamat = `${lat.toFixed(5)}, ${lng.toFixed(5)}`; lokasi.nama = 'Titik di peta'; }
      tampilPick();
    }
    const sug = $('#sug'); let tmr, hasil = [];
    $('#cari').oninput = e => {
      clearTimeout(tmr); const q = e.target.value.trim();
      if (q.length < 3) { sug.hidden = true; return; }
      tmr = setTimeout(async () => {
        try {
          const r = await fetch(`https://nominatim.openstreetmap.org/search?format=jsonv2&limit=5&countrycodes=id&accept-language=id&viewbox=112.45,-7.8,112.8,-8.15&q=${encodeURIComponent(q)}`);
          hasil = await r.json();
          sug.innerHTML = hasil.length ? hasil.map((x, i) => `<li data-i="${i}"><i class="fa-solid fa-location-dot"></i><div><b>${esc(x.name || x.display_name.split(',')[0])}</b><small>${esc(x.display_name)}</small></div></li>`).join('')
            : '<li class="none">Alamat tidak ditemukan. Coba kata kunci lain atau geser pin di peta.</li>';
        } catch (err) { sug.innerHTML = '<li class="none">Pencarian gagal. Periksa koneksi internet.</li>'; }
        sug.hidden = false;
      }, 500);
    };
    sug.onclick = e => {
      const li = e.target.closest('[data-i]'); if (!li) return;
      const x = hasil[li.dataset.i]; sug.hidden = true; $('#cari').value = x.name || x.display_name.split(',')[0];
      pilih(+x.lat, +x.lon, x.display_name);
    };
    $('#lokasiku').onclick = () => {
      if (!navigator.geolocation) { toast('Browser tidak mendukung lokasi'); return; }
      toast('Mencari lokasi Anda…');
      navigator.geolocation.getCurrentPosition(p => pilih(p.coords.latitude, p.coords.longitude), () => toast('Izin lokasi ditolak. Cari alamat atau geser pin.'), { enableHighAccuracy: true, timeout: 10000 });
    };

    function valid() {
      const n = $('#nama').value.trim(), h = $('#hp').value.replace(/[\s-]/g, '');
      const ok = { fNama: n.length > 1, fHp: /^(\+62|62|0)8\d{8,12}$/.test(h) };
      if (mode === 'antar') { ok.fLokasi = !!lokasi; ok.fDetail = $('#detail').value.trim().length > 2; }
      ['fNama', 'fHp', 'fLokasi', 'fDetail'].forEach(k => { if (k in ok) $('#' + k).classList.toggle('err', !ok[k]); else $('#' + k).classList.remove('err'); });
      return Object.values(ok).every(Boolean);
    }
    function toStep(n) {
      if (n > 1 && !jumlah()) { toast('Keranjang masih kosong'); step = 1; showStep(); return; }
      if (n === 3 && !valid()) { toast('Lengkapi data yang ditandai merah'); return; }
      step = n; showStep(); scrollTo(0, 0);
    }
    function pesan(kode) {
      const L = [`Halo ${D.namaUsaha}, saya ingin memesan:`,
        ...Object.entries(cart).map(([id, q], i) => `${i + 1}. ${find(id).nama} x${q}`),
        `Nama: ${$('#nama').value.trim()}`,
        `Pengambilan: ${mode === 'antar' ? 'Diantar ke alamat' : 'Ambil di tempat'}`];
      if (mode === 'antar') {
        L.push(`Alamat: ${lokasi.alamat}`, `Detail alamat: ${$('#detail').value.trim()}`, `Titik lokasi: https://maps.google.com/?q=${lokasi.lat},${lokasi.lng}`);
        if ($('#catatan').value.trim()) L.push(`Catatan kurir: ${$('#catatan').value.trim()}`);
      }
      L.push(`Pembayaran: ${bayar()}`, `Subtotal: ${rp(sub())}`, `Ongkos kirim: ${mode === 'antar' ? rp(ONGKIR) : 'Gratis (ambil di tempat)'}`, `Total: ${rp(grand())}`);
      L.push('Kode pesanan: ' + (kode || '(dibuat otomatis setelah dikirim)'));
      return L.join('\n');
    }
    function renderConfirm() {
      $('#s3sum').innerHTML = Object.entries(cart).map(([id, q]) => `<div class="sum"><span>${esc(find(id).nama)} × ${q}</span><span>${rp(find(id).harga * q)}</span></div>`).join('')
        + `<div class="sum"><span>Ongkos kirim</span><span>${mode === 'antar' ? rp(ONGKIR) : 'Gratis'}</span></div>
           <div class="sum total"><span>Total</span><span>${rp(grand())}</span></div>
           <div class="sum" style="color:var(--mut)"><span>Pembayaran</span><span>${bayar()}</span></div>
           <div class="sum" style="color:var(--mut)"><span>Pengambilan</span><span>${mode === 'antar' ? 'Diantar' : 'Ambil di tempat'}</span></div>`;
      $('#pre').textContent = pesan();
    }
    // Simpan pesanan sebagai "Belum fix", lalu buka WhatsApp dengan kode pesanan
    async function kirim(btn) {
      if (btn.disabled) return;
      if (!jumlah() || !valid()) { toast('Lengkapi data pesanan dulu'); return; }
      const lama = btn.innerHTML; btn.disabled = true; btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Mengirim…';
      const w = window.open('', '_blank'); // dibuka sekarang agar tidak diblokir browser
      try {
        const r = await fetch(D.pesanUrl, { method: 'POST', headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': D.csrf },
          body: JSON.stringify({ nama: $('#nama').value.trim(), hp: $('#hp').value.trim(), pengambilan: mode, metode: bayar(),
            alamat: mode === 'antar' ? lokasi.alamat : null, detail: $('#detail').value.trim(), lat: lokasi ? lokasi.lat : null, lng: lokasi ? lokasi.lng : null,
            catatan: $('#catatan').value.trim(), items: cart, website: $('#website').value }) });
        const j = await r.json().catch(() => ({}));
        if (!r.ok) throw new Error(j.message || (j.errors && Object.values(j.errors)[0][0]) || 'Gagal mengirim pesanan. Coba lagi.');
        const url = 'https://wa.me/' + D.wa + '?text=' + encodeURIComponent(pesan(j.kode));
        cart = {}; simpan();
        $('#kodeOut').textContent = j.kode; $('#waAgain').href = url; step = 4; showStep(); scrollTo(0, 0);
        if (w) w.location.href = url;
      } catch (err) {
        if (w) w.close(); toast(err.message || 'Gagal mengirim pesanan.'); btn.disabled = false; btn.innerHTML = lama;
      }
    }
    document.addEventListener('click', e => {
      const s = e.target.closest('[data-step]'); if (s) toStep(+s.dataset.step);
      const kb = e.target.closest('[data-kirim]'); if (kb) kirim(kb);
      if (!e.target.closest('.ga-search') && !e.target.closest('#sug')) sug.hidden = true;
    });
    showStep();
  }
  ui();
})();
