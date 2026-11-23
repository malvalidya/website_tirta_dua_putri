// Tombol gelap/terang (dipakai halaman pembeli dan admin). Pilihan disimpan di browser.
(() => {
  const KEY = 'tdp_theme', root = document.documentElement, btn = document.getElementById('themeBtn');
  const tampil = t => {
    if (!btn) return;
    btn.querySelector('i').className = 'fa-solid ' + (t === 'dark' ? 'fa-sun' : 'fa-moon');
    const teks = t === 'dark' ? 'Ganti ke tema terang' : 'Ganti ke tema gelap';
    btn.setAttribute('aria-label', teks); btn.title = teks;
  };
  tampil(root.dataset.theme === 'dark' ? 'dark' : 'light');
  if (btn) btn.onclick = () => {
    const t = root.dataset.theme === 'dark' ? 'light' : 'dark';
    root.dataset.theme = t; tampil(t);
    try { localStorage.setItem(KEY, t); } catch (e) {}
  };
})();
