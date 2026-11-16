<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Admin extends Authenticatable {
    protected $table = 'admin';
    protected $primaryKey = 'id_admin';
    public $timestamps = false;
    protected $fillable = ['email','kata_sandi'];
    protected $hidden = ['kata_sandi'];
    protected $casts = ['kata_sandi' => 'hashed'];
    public function getAuthPassword() { return $this->kata_sandi; }
    public function getAuthPasswordName() { return 'kata_sandi'; }
    public function getRememberTokenName() { return ''; } // tabel admin tidak punya remember_token
}
