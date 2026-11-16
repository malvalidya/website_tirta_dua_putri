<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ProfilUsaha extends Model {
    protected $table = 'profil_usaha';
    protected $primaryKey = 'id_profil';
    public $timestamps = false;
    protected $fillable = ['nama_usaha','deskripsi_singkat','alamat','jam_operasional','nomor_wa'];
    /** Nomor WA format internasional: tanpa +, tanpa 0 di depan (mis. 62812...). */
    public function waNomor(): string {
        $n = preg_replace('/\D/', '', (string) $this->nomor_wa);
        return str_starts_with($n, '0') ? '62' . substr($n, 1) : $n;
    }
}
