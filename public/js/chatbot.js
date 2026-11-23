// Chatbot bantuan lokal: tanpa server, jawaban dari data produk/profil di halaman.
(() => {
  const D = window.TDP || {}, root = document.getElementById('chat');
  if (!root) return;
  const $ = s => root.querySelector(s);
  const rp = n => 'Rp ' + Number(n).toLocaleString('id-ID');
  const norm = s => s.toLowerCase().replace(/[^a-z0-9\s]/g, ' ').replace(/\s+/g, ' ').trim();
  const aktif = () => (D.produk || []).filter(p => p.aktif);
  const ongkir = rp(D.ongkir ?? 5000);
  const wa = t => `https://wa.me/${D.wa}?text=${encodeURIComponent(t)}`;

  const KB = [
    { k: ['jam', 'buka', 'tutup', 'operasional'], a: () => `Jam operasional kami: ${D.jam || 'silakan tanya admin'}.` },
    { k: ['alamat', 'lokasi', 'dimana', 'di mana', 'tempat', 'maps', 'peta'], a: () => `Lokasi depo: ${D.alamatDepo || 'belum diisi'}.\nPeta lengkap ada di menu Info usaha.` },
    { k: ['promo', 'diskon', 'potongan', 'voucher', 'kode promo', 'hemat'], a: () => D.promo ? `Promo saat ini: ${D.promo.judul}.${D.promo.diskon ? '\n' + D.promo.diskon : ''}${D.promo.kode ? '\nKode promo: ' + D.promo.kode : ''}${D.promo.sampai ? '\nBerlaku sampai ' + D.promo.sampai : ''}` : 'Saat ini belum ada promo. Pantau terus halaman utama ya.' },
    { k: ['harga', 'produk', 'daftar', 'menu', 'galon', 'isi ulang', 'langganan', 'paket'], a: () => aktif().length ? `Produk yang tersedia:\n${aktif().map(p => `• ${p.nama}: ${rp(p.harga)}`).join('\n')}` : 'Daftar produk belum tersedia.' },
    { k: ['cara pesan', 'pesan', 'order', 'beli', 'checkout', 'gimana', 'bagaimana'], a: () => '1. Pilih produk di Beranda lalu tekan tombol +.\n2. Buka Keranjang, atur jumlah, dan pilih Ambil di tempat atau Diantar.\n3. Isi data dan pilih pembayaran.\n4. Tekan Kirim lewat WhatsApp, lalu kirim pesannya ke admin.' },
    { k: ['ongkir', 'ongkos', 'biaya kirim', 'antar', 'kirim', 'delivery', 'diantar'], a: () => `Jika diantar ke alamat, ongkos kirim rata ${ongkir}. Jika ambil di tempat, tidak ada biaya tambahan.` },
    { k: ['ambil', 'ditempat', 'di tempat', 'datang', 'langsung'], a: () => `Anda bisa ambil di tempat tanpa biaya tambahan.${D.alamatDepo ? '\nAlamat: ' + D.alamatDepo : ''}` },
    { k: ['bayar', 'pembayaran', 'tunai', 'nontunai', 'transfer', 'qris', 'ewallet', 'cash'], a: () => 'Pembayaran bisa Tunai (dibayar saat air diterima atau diambil) atau Nontunai (rincian pembayaran dikirim admin lewat WhatsApp).' },
    { k: ['akun', 'login', 'daftar', 'registrasi'], a: () => 'Tidak perlu membuat akun. Cukup isi nama, nomor HP, dan alamat saat memesan.' },
    { k: ['keranjang', 'hapus', 'ubah jumlah', 'kurangi', 'tambah'], a: () => 'Di halaman Keranjang, tombol + dan − mengubah jumlah, dan tombol Hapus mengeluarkan produk. Jumlah minimal 1.' },
    { k: ['kebersihan', 'higiene', 'sertifikat', 'legal', 'steril', 'kualitas'], a: () => 'Informasi kebersihan dan legalitas depo ada di menu Info usaha. Untuk pertanyaan lebih detail, silakan tanya admin.' },
    { k: ['admin', 'cs', 'manusia', 'orang', 'hubungi', 'kontak', 'whatsapp', 'wa'], a: () => 'Baik, Anda bisa langsung chat dengan admin lewat tombol di bawah.', admin: true },
    { k: ['terima kasih', 'makasih', 'thanks', 'oke'], a: () => 'Sama-sama! Kalau butuh bantuan lagi, tanya saja.' },
  ];
  const SALAM = { k: ['halo', 'hai', 'hi', 'pagi', 'siang', 'sore', 'malam', 'assalamualaikum'], a: () => 'Halo! Ada yang bisa dibantu? Pilih topik di bawah atau ketik pertanyaan Anda.' };
  const CHIPS = ['Jam buka', 'Harga produk', 'Cara pesan', 'Ongkos kirim', 'Pembayaran', 'Lokasi depo'];

  function jawab(teks) {
    const n = ' ' + norm(teks) + ' ';
    const prod = aktif().find(p => n.includes(' ' + norm(p.nama) + ' '));
    if (prod) return { t: `${prod.nama}: ${rp(prod.harga)}.${prod.desk ? '\n' + prod.desk : ''}` };
    let best = null, skor = 0;
    for (const e of KB) {
      const s = e.k.filter(w => n.includes(w.length > 3 ? w : ' ' + w + ' ')).length;
      if (s > skor) { best = e; skor = s; }
    }
    if (!best && n.trim().split(' ').length <= 3 && SALAM.k.some(w => n.includes(' ' + w + ' '))) best = SALAM;
    if (best) return { t: best.a(), admin: best.admin };
    return { t: 'Maaf, aku belum paham pertanyaan itu. Coba pilih topik di bawah, atau tanyakan langsung ke admin.', admin: true };
  }

  const msgs = $('#chatMsgs'), chips = $('#chatChips'), panel = $('#chatPanel'), fab = $('#chatFab');
  const scroll = () => msgs.scrollTop = msgs.scrollHeight;
  function bubble(kls, html) { const d = document.createElement('div'); d.className = 'm ' + kls; d.innerHTML = html; msgs.appendChild(d); scroll(); return d; }
  const safe = s => s.replace(/[&<>]/g, c => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;' }[c]));
  function adminLink(konteks) {
    return `<br><a class="chat-wa" target="_blank" rel="noopener" href="${wa('Halo admin ' + D.namaUsaha + ', saya butuh bantuan' + (konteks ? ': ' + konteks : '.'))}"><i class="fa-brands fa-whatsapp"></i> Chat admin</a>`;
  }
  function tanya(teks) {
    teks = teks.trim(); if (!teks) return;
    bubble('me', safe(teks));
    const t = bubble('bot typing', '<i></i><i></i><i></i>');
    setTimeout(() => { t.remove(); const r = jawab(teks); bubble('bot', safe(r.t) + (r.admin ? adminLink(teks) : '')); }, 450);
  }
  chips.innerHTML = CHIPS.map(c => `<button type="button">${c}</button>`).join('') + '<button type="button" class="adm"><i class="fa-brands fa-whatsapp"></i> Chat admin</button>';
  chips.onclick = e => {
    const b = e.target.closest('button'); if (!b) return;
    if (b.classList.contains('adm')) { bubble('bot', 'Silakan chat langsung dengan admin kami.' + adminLink()); return; }
    tanya(b.textContent);
  };
  $('#chatForm').onsubmit = e => { e.preventDefault(); const i = $('#chatIn'); tanya(i.value); i.value = ''; };

  let sapa = false;
  function buka(v) {
    panel.classList.toggle('open', v); fab.setAttribute('aria-expanded', v);
    if (v && !sapa) { sapa = true; bubble('bot', 'Halo! Aku asisten ' + safe(D.namaUsaha || 'depo') + '. Ada yang bisa dibantu? Pilih topik di bawah atau ketik pertanyaan Anda.'); }
    if (v) setTimeout(() => $('#chatIn').focus(), 300);
  }
  fab.onclick = () => buka(!panel.classList.contains('open'));
  $('#chatX').onclick = () => buka(false);
  document.addEventListener('keydown', e => { if (e.key === 'Escape') buka(false); });
})();
