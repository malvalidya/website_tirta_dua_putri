<div id="chat">
  <button class="chat-fab" id="chatFab" type="button" aria-label="Buka bantuan" aria-expanded="false"><i class="fa-solid fa-comment-dots"></i><span>Bantuan</span></button>
  <section class="chat-panel" id="chatPanel" role="dialog" aria-label="Bantuan">
    <header><div class="av"><i class="fa-solid fa-droplet"></i></div><div><b>Bantuan Tirta</b><small>Asisten otomatis</small></div><button id="chatX" type="button" aria-label="Tutup"><i class="fa-solid fa-xmark"></i></button></header>
    <div class="chat-msgs" id="chatMsgs" aria-live="polite"></div>
    <div class="chat-chips" id="chatChips"></div>
    <form id="chatForm" autocomplete="off"><input id="chatIn" placeholder="Tulis pertanyaan…" aria-label="Pertanyaan"><button type="submit" aria-label="Kirim"><i class="fa-solid fa-paper-plane"></i></button></form>
  </section>
</div>
