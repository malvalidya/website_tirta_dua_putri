<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('produk', function (Blueprint $t) {
            $t->id('id_produk');
            $t->string('nama_produk');
            $t->string('kategori', 60);
            $t->text('deskripsi')->nullable();
            $t->unsignedInteger('harga');
            $t->string('foto_url', 500)->nullable();
            $t->enum('status_produk', ['Aktif', 'Habis'])->default('Aktif');
        });
    }
    public function down(): void { Schema::dropIfExists('produk'); }
};
