<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('pesanan', function (Blueprint $t) {
            $t->id('id_pesanan');
            $t->string('kode_pesanan', 20)->unique();
            $t->string('nama_pelanggan');
            $t->string('no_hp', 20);
            $t->text('alamat');
            $t->enum('metode_bayar', ['Tunai', 'Nontunai']);
            $t->unsignedInteger('total_harga');
            $t->date('tanggal_selesai');
        });
    }
    public function down(): void { Schema::dropIfExists('pesanan'); }
};
