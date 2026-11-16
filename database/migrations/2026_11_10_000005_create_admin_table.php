<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('admin', function (Blueprint $t) {
            $t->id('id_admin');
            $t->string('email')->unique();
            $t->string('kata_sandi'); // hash bcrypt
        });
    }
    public function down(): void { Schema::dropIfExists('admin'); }
};
