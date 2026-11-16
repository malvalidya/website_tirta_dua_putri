<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('detail_pesanan', function (Blueprint $t) {
            $t->id('id_detail');
            $t->foreignId('id_pesanan')->constrained('pesanan', 'id_pesanan')->cascadeOnDelete();
            $t->foreignId('id_produk')->constrained('produk', 'id_produk')->restrictOnDelete();
            $t->unsignedInteger('jumlah');
            $t->unsignedInteger('harga_satuan'); // disalin dari produk.harga saat transaksi dicatat
            $t->unsignedInteger('subtotal');
        });
    }
    public function down(): void { Schema::dropIfExists('detail_pesanan'); }
};
