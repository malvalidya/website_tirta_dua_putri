<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Produk extends Model {
    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    public $timestamps = false;
    protected $fillable = ['nama_produk','kategori','deskripsi','harga','foto_url','status_produk'];
    /** Produk yang tampil ke pembeli (item kategori 'Biaya' seperti Ongkos Kirim hanya untuk pencatatan admin). */
    public function scopeTampil($q) { return $q->where('kategori', '!=', 'Biaya'); }
    public function detail() { return $this->hasMany(DetailPesanan::class, 'id_produk', 'id_produk'); }
    public function getAktifAttribute(): bool { return $this->status_produk === 'Aktif'; }
    public function getIkonAttribute(): string {
        $k = strtolower($this->kategori ?? '');
        return match (true) {
            str_contains($k, 'galon') => 'fa-bottle-water',
            str_contains($k, 'layanan'), str_contains($k, 'biaya') => 'fa-motorcycle',
            str_contains($k, 'paket') => 'fa-box-open',
            default => 'fa-droplet',
        };
    }
}
