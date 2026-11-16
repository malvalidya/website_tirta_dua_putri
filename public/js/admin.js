// Detail pesanan (buka/tutup baris) dan konfirmasi hapus
document.addEventListener('click', e => {
  const b = e.target.closest('[data-k]');
  if (!b) return;
  const r = document.getElementById('d-' + b.dataset.k);
  if (!r) return;
  r.hidden = !r.hidden;
  b.textContent = r.hidden ? 'Detail' : 'Tutup';
  b.setAttribute('aria-expanded', String(!r.hidden));
});
document.addEventListener('submit', e => {
  const t = e.target.dataset && e.target.dataset.konfirmasi;
  if (t && !confirm(t)) e.preventDefault();
});

// Form "Catat transaksi": tambah/hapus baris produk dan hitung total
const body = document.getElementById('itemBody');
if (body) {
  const tpl = document.getElementById('rowTpl'), rp = n => 'Rp ' + n.toLocaleString('id-ID');
  let i = 0;
  const hitung = () => {
    let total = 0;
    body.querySelectorAll('tr').forEach(tr => {
      const opt = tr.querySelector('select').selectedOptions[0];
      const h = opt ? Number(opt.dataset.harga) || 0 : 0, q = Math.max(1, parseInt(tr.querySelector('input').value, 10) || 1), s = h * q;
      tr.querySelector('.hs').textContent = rp(h); tr.querySelector('.c-sub').textContent = rp(s); total += s;
    });
    document.getElementById('totalHarga').value = rp(total);
  };
  const tambah = (isi) => {
    body.insertAdjacentHTML('beforeend', tpl.innerHTML.replaceAll('__i__', i++));
    const tr = body.lastElementChild;
    if (isi) {
      const sel = tr.querySelector('select'), qty = tr.querySelector('input');
      if (isi.id_produk && [...sel.options].some(o => o.value === String(isi.id_produk))) sel.value = String(isi.id_produk);
      if (isi.jumlah) qty.value = isi.jumlah;
    }
    hitung();
  };
  document.getElementById('addRow').addEventListener('click', () => tambah());
  body.addEventListener('input', hitung);
  body.addEventListener('change', hitung);
  body.addEventListener('click', e => {
    const rm = e.target.closest('.rm');
    if (rm && body.rows.length > 1) { rm.closest('tr').remove(); hitung(); }
  });
  const lama = Array.isArray(window.OLD_ITEMS) ? window.OLD_ITEMS : [];
  if (lama.length) lama.forEach(tambah); else tambah();
}
