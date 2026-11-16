<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, Hash};
use Illuminate\Validation\Rule;

class AkunController extends Controller {
    public function edit() { return view('admin.akun.edit', ['admin' => Auth::guard('admin')->user()]); }

    public function update(Request $r) {
        $admin = Auth::guard('admin')->user();
        $data = $r->validate([
            'email'          => ['required', 'email', Rule::unique('admin', 'email')->ignore($admin->id_admin, 'id_admin')],
            'password_lama'  => 'required_with:password',
            'password'       => 'nullable|min:8|confirmed',
        ]);
        if ($r->filled('password')) {
            if (!Hash::check($r->password_lama, $admin->kata_sandi)) {
                return back()->withErrors(['password_lama' => 'Kata sandi lama salah.']);
            }
            $admin->kata_sandi = $r->password; // di-hash otomatis oleh cast
        }
        $admin->email = $data['email'];
        $admin->save();
        return back()->with('sukses', 'Akun diperbarui.');
    }
}
