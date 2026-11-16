<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('profil_usaha', function (Blueprint $t) {
            $t->id('id_profil');
            $t->string('nama_usaha');
            $t->text('deskripsi_singkat')->nullable();
            $t->text('alamat')->nullable();
            $t->string('jam_operasional')->nullable();
            $t->string('nomor_wa', 20); // format 62812xxxx
        });
    }
    public function down(): void { Schema::dropIfExists('profil_usaha'); }
};
