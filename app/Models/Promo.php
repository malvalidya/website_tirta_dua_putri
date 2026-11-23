<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Promo extends Model {
    protected $table = 'promo';
    protected $primaryKey = 'id_promo';
    public $timestamps = false;
    protected $fillable = ['judul','label','diskon_teks','deskripsi','kode_promo','teks_tombol','mulai','selesai','aktif'];
    protected $casts = ['mulai' => 'date', 'selesai' => 'date', 'aktif' => 'boolean'];

    /** Promo yang sedang tayang: aktif dan dalam rentang tanggal (jika diisi). */
    public function scopeBerlaku($q) {
        $hari = now()->toDateString();
        return $q->where('aktif', true)
            ->where(fn ($w) => $w->whereNull('mulai')->orWhere('mulai', '<=', $hari))
            ->where(fn ($w) => $w->whereNull('selesai')->orWhere('selesai', '>=', $hari));
    }
    public function getStatusAttribute(): string {
        if (!$this->aktif) return 'Nonaktif';
        if ($this->mulai && $this->mulai->isFuture()) return 'Terjadwal';
        if ($this->selesai && $this->selesai->isPast() && !$this->selesai->isToday()) return 'Berakhir';
        return 'Tayang';
    }
}
