// Detail pesanan (buka/tutup baris)
document.addEventListener('click', e => {
  const b = e.target.closest('[data-k]');
  if (b) { const r = document.getElementById('d-' + b.dataset.k); r.hidden = !r.hidden; b.textContent = r.hidden ? 'Detail' : 'Tutup'; }
});
// Form "Catat transaksi": tambah/hapus baris produk dan hitung total
const body = document.getElementById('itemBody');
if (body) {
  const tpl = document.getElementById('rowTpl'), rp = n => 'Rp ' + n.toLocaleString('id-ID');
  let i = 0;
  const hitung = () => {
    let total = 0;
    body.querySelectorAll('tr').forEach(tr => {
      const h = +tr.querySelector('select').selectedOptions[0].dataset.harga, q = Math.max(1, +tr.querySelector('input').value || 1), s = h * q;
      tr.querySelector('.hs').textContent = rp(h); tr.querySelector('.c-sub').textContent = rp(s); total += s;
    });
    document.getElementById('totalHarga').textContent = rp(total);
  };
  const tambah = () => { body.insertAdjacentHTML('beforeend', tpl.innerHTML.replaceAll('__i__', i++)); hitung(); };
  document.getElementById('addRow').onclick = tambah;
  body.addEventListener('input', hitung);
  body.addEventListener('click', e => { if (e.target.closest('.rm') && body.rows.length > 1) { e.target.closest('tr').remove(); hitung(); } });
  tambah();
}

// Pratinjau promo: isi pratinjau mengikuti ketikan
document.querySelectorAll('[data-pv]').forEach(i => i.addEventListener('input', () => {
  const t = document.getElementById(i.dataset.pv); if (t) t.textContent = i.value;
}));
