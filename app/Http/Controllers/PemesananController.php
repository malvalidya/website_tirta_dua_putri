<?php
namespace App\Http\Controllers;
use App\Models\{Pesanan, Produk};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemesananController extends Controller {
    public function simpan(Request $r) {
        // Jebakan bot: kolom tersembunyi ini tidak pernah diisi manusia
        if ($r->filled('website')) return response()->json(['kode' => 'TDP-0000']);

        $d = $r->validate([
            'nama'        => 'required|string|min:2|max:100',
            'hp'          => ['required', 'regex:/^(\+62|62|0)8\d{8,12}$/'],
            'pengambilan' => 'required|in:ambil,antar',
            'metode'      => 'required|in:Tunai,Nontunai',
            'alamat'      => 'required_if:pengambilan,antar|nullable|string|max:500',
            'detail'      => 'required_if:pengambilan,antar|nullable|string|max:200',
            'lat'         => 'nullable|numeric|between:-90,90',
            'lng'         => 'nullable|numeric|between:-180,180',
            'catatan'     => 'nullable|string|max:150',
            'items'       => 'required|array|min:1|max:20',
            'items.*'     => 'integer|min:1|max:50',
        ], ['hp.regex' => 'Nomor HP tidak valid.']);

        $hp = preg_replace('/[\s-]/', '', $d['hp']);
        // Batasi pesanan menggantung dari nomor yang sama
        if (Pesanan::belumFix()->where('no_hp', $hp)->count() >= 3) {
            return response()->json(['message' => 'Masih ada pesanan yang menunggu konfirmasi admin. Mohon tunggu admin menghubungi Anda.'], 422);
        }

        // Harga SELALU dihitung ulang dari database, bukan dari data browser
        $ids = array_keys($d['items']);
        $produk = Produk::tampil()->where('status_produk', 'Aktif')->whereIn('id_produk', $ids)->get()->keyBy('id_produk');
        if ($produk->count() !== count($ids)) {
            return response()->json(['message' => 'Ada produk yang sudah tidak tersedia. Muat ulang halaman.'], 422);
        }
        $antar = $d['pengambilan'] === 'antar';
        $ongkir = $antar ? (int) config('depo.ongkir', 5000) : 0;

        $kode = DB::transaction(function () use ($d, $hp, $produk, $antar, $ongkir) {
            $baris = collect($d['items'])->map(fn ($q, $id) => [
                'id_produk' => $id, 'jumlah' => (int) $q, 'harga_satuan' => $produk[$id]->harga, 'subtotal' => $produk[$id]->harga * (int) $q,
            ])->values();
            $p = Pesanan::create([
                'kode_pesanan'   => Pesanan::kodeBerikutnya(),
                'nama_pelanggan' => $d['nama'], 'no_hp' => $hp,
                'alamat'         => $antar ? $d['alamat'] : null,
                'detail_alamat'  => $antar ? $d['detail'] : null,
                'lat' => $antar ? ($d['lat'] ?? null) : null, 'lng' => $antar ? ($d['lng'] ?? null) : null,
                'catatan'        => $d['catatan'] ?? null,
                'metode_bayar'   => $d['metode'],
                'pengambilan'    => $antar ? 'Diantar' : 'Ambil di tempat',
                'ongkir'         => $ongkir,
                'total_harga'    => $baris->sum('subtotal') + $ongkir,
                'status'         => 'Belum fix',
                'tanggal_selesai' => null,
            ]);
            $p->detail()->createMany($baris->all());
            return $p->kode_pesanan;
        });
        return response()->json(['kode' => $kode]);
    }
}
