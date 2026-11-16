<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ProfilUsaha;
use Illuminate\Http\Request;

class ProfilUsahaController extends Controller {
    public function edit() { return view('admin.profil.edit', ['profil' => ProfilUsaha::first() ?? new ProfilUsaha()]); }

    public function update(Request $r) {
        $data = $r->validate([
            'nama_usaha'        => 'required|string|max:255',
            'deskripsi_singkat' => 'nullable|string',
            'alamat'            => 'nullable|string',
            'jam_operasional'   => 'nullable|string|max:255',
            'nomor_wa'          => ['required', 'regex:/^(\+?62|0)8\d{8,12}$/'],
        ], ['nomor_wa.regex' => 'Nomor WhatsApp tidak valid, contoh 6281234567890.']);
        $profil = ProfilUsaha::first() ?? new ProfilUsaha();
        $profil->fill($data)->save();
        return back()->with('sukses', 'Profil usaha disimpan.');
    }
}
