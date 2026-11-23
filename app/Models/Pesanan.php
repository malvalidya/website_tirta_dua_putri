<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model {
    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';
    public $timestamps = false;
    protected $fillable = ['kode_pesanan','nama_pelanggan','no_hp','alamat','detail_alamat','lat','lng','catatan',
        'metode_bayar','pengambilan','ongkir','total_harga','status','tanggal_selesai','dibuat_pada'];
    protected $casts = ['tanggal_selesai' => 'date', 'dibuat_pada' => 'datetime'];

    public function detail() { return $this->hasMany(DetailPesanan::class, 'id_pesanan', 'id_pesanan'); }
    public function scopeFix($q) { return $q->where('status', 'Fix'); }
    public function scopeBelumFix($q) { return $q->where('status', 'Belum fix'); }

    /** Kode otomatis TDP-0001, TDP-0002, ... Panggil di dalam transaksi DB. */
    public static function kodeBerikutnya(): string {
        $n = (int) static::lockForUpdate()->max('id_pesanan') + 1;
        return 'TDP-' . str_pad($n, 4, '0', STR_PAD_LEFT);
    }
    /** Link WhatsApp ke pelanggan untuk verifikasi pesanan. */
    public function waLink(): string {
        $n = preg_replace('/\D/', '', $this->no_hp);
        $n = str_starts_with($n, '0') ? '62' . substr($n, 1) : $n;
        $teks = "Halo {$this->nama_pelanggan}, kami dari Depo Tirta Dua Putri. Kami ingin mengonfirmasi pesanan {$this->kode_pesanan}. Apakah benar Anda yang memesan?";
        return 'https://wa.me/' . $n . '?text=' . rawurlencode($teks);
    }
}
