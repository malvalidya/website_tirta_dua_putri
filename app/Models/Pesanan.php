<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Pesanan extends Model {
    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';
    public $timestamps = false;
    protected $fillable = ['kode_pesanan','nama_pelanggan','no_hp','alamat','metode_bayar','total_harga','tanggal_selesai'];
    protected $casts = ['tanggal_selesai' => 'date'];
    public function detail() { return $this->hasMany(DetailPesanan::class, 'id_pesanan', 'id_pesanan'); }
    /** Kode otomatis TDP-0001, TDP-0002, ... (KF-13). Panggil di dalam transaksi DB. */
    public static function kodeBerikutnya(): string {
        $n = (int) static::lockForUpdate()->max('id_pesanan') + 1;
        return 'TDP-' . str_pad($n, 4, '0', STR_PAD_LEFT);
    }
}
