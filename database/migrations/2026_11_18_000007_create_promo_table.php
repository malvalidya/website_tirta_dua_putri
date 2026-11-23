<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('promo', function (Blueprint $t) {
            $t->id('id_promo');
            $t->string('judul', 120);
            $t->string('label', 30)->default('Promo Live');
            $t->string('diskon_teks', 60)->nullable();   // contoh: "Diskon 20%" / "Gratis ongkir"
            $t->string('deskripsi', 300)->nullable();
            $t->string('kode_promo', 30)->nullable();
            $t->string('teks_tombol', 30)->default('Pesan sekarang');
            $t->date('mulai')->nullable();
            $t->date('selesai')->nullable();
            $t->boolean('aktif')->default(false);
        });
    }
    public function down(): void { Schema::dropIfExists('promo'); }
};
