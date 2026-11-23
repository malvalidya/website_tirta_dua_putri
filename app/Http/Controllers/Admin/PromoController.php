<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Promo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PromoController extends Controller {
    private function rules(): array {
        return [
            'judul'       => 'required|string|max:120',
            'label'       => 'required|string|max:30',
            'diskon_teks' => 'nullable|string|max:60',
            'deskripsi'   => 'nullable|string|max:300',
            'kode_promo'  => 'nullable|string|max:30',
            'teks_tombol' => 'required|string|max:30',
            'mulai'       => 'nullable|date',
            'selesai'     => 'nullable|date|after_or_equal:mulai',
        ];
    }

    public function index() { return view('admin.promo.index', ['promo' => Promo::orderByDesc('id_promo')->get()]); }

    public function create() {
        return view('admin.promo.form', ['promo' => new Promo(['label' => 'Promo Live', 'teks_tombol' => 'Pesan sekarang', 'aktif' => true])]);
    }

    public function store(Request $r) {
        $d = $r->validate($this->rules()) + ['aktif' => $r->boolean('aktif')];
        DB::transaction(function () use ($d) {
            if ($d['aktif']) Promo::query()->update(['aktif' => false]); // hanya satu promo tayang
            Promo::create($d);
        });
        return redirect()->route('admin.promo.index')->with('sukses', 'Promo disimpan.');
    }

    public function edit(Promo $promo) { return view('admin.promo.form', ['promo' => $promo]); }

    public function update(Request $r, Promo $promo) {
        $d = $r->validate($this->rules()) + ['aktif' => $r->boolean('aktif')];
        DB::transaction(function () use ($d, $promo) {
            if ($d['aktif']) Promo::where('id_promo', '!=', $promo->id_promo)->update(['aktif' => false]);
            $promo->update($d);
        });
        return redirect()->route('admin.promo.index')->with('sukses', 'Promo diperbarui.');
    }

    /** Tayangkan / hentikan promo dengan satu klik. */
    public function toggle(Promo $promo) {
        DB::transaction(function () use ($promo) {
            if (!$promo->aktif) Promo::where('id_promo', '!=', $promo->id_promo)->update(['aktif' => false]);
            $promo->update(['aktif' => !$promo->aktif]);
        });
        return back()->with('sukses', $promo->aktif ? 'Promo ditayangkan.' : 'Promo dihentikan.');
    }

    public function destroy(Promo $promo) {
        $promo->delete();
        return redirect()->route('admin.promo.index')->with('sukses', 'Promo dihapus.');
    }
}
