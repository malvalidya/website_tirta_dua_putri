<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('pesanan', function (Blueprint $t) {
            $t->enum('status', ['Belum fix', 'Fix'])->default('Belum fix')->after('total_harga');
            $t->enum('pengambilan', ['Ambil di tempat', 'Diantar'])->default('Ambil di tempat')->after('metode_bayar');
            $t->unsignedInteger('ongkir')->default(0)->after('pengambilan');
            $t->string('detail_alamat')->nullable()->after('alamat');
            $t->decimal('lat', 10, 7)->nullable();
            $t->decimal('lng', 10, 7)->nullable();
            $t->string('catatan')->nullable();
            $t->timestamp('dibuat_pada')->useCurrent();
        });
        Schema::table('pesanan', function (Blueprint $t) {
            $t->text('alamat')->nullable()->change();          // ambil di tempat tidak perlu alamat
            $t->date('tanggal_selesai')->nullable()->change(); // baru terisi saat admin menekan Selesai
        });
        DB::table('pesanan')->update(['status' => 'Fix']);     // data lama sudah selesai
    }
    public function down(): void {
        Schema::table('pesanan', function (Blueprint $t) {
            $t->dropColumn(['status', 'pengambilan', 'ongkir', 'detail_alamat', 'lat', 'lng', 'catatan', 'dibuat_pada']);
        });
    }
};
