<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    public function index()
    {
        return view('profil.index'); // Sesuaikan dengan nama folder view Anda
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        // 1. Validasi fleksibel (semua nullable/opsional, termasuk identitas)
        $request->validate([
            'gambar'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'username'  => 'nullable|string|max:255|unique:users,username,' . $user->id,
            'email'     => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'password'  => 'nullable|min:6',
            'identitas' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validasi file identitas
        ]);

        // 2. Hanya ubah data jika input tersebut benar-benar diisi/dikirim oleh user
        if ($request->filled('username')) {
            $user->username = $request->username;
        }

        if ($request->filled('email')) {
            $user->email = $request->email;
        }

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        // 3. Proses ganti gambar profil (hanya jika ada file gambar yang di-upload)
        if ($request->hasFile('gambar')) {
            // Hapus file gambar lama jika ada
            if ($user->gambar && file_exists(public_path('assets/resource/profil/' . $user->gambar))) {
                unlink(public_path('assets/resource/profil/' . $user->gambar));
            }

            $file = $request->file('gambar');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('assets/resource/profil'), $namaFile);
            $user->gambar = $namaFile;
        }

        // 4. Proses Upload Identitas dan Transformasi Role (Upgrade Seller)
        if ($request->hasFile('identitas')) {
            // Hapus file identitas lama jika sebelumnya sudah pernah upload (opsional)
            if ($user->identitas && file_exists(public_path('assets/resource/identitas/' . $user->identitas))) {
                unlink(public_path('assets/resource/identitas/' . $user->identitas));
            }

            $fileIdentitas = $request->file('identitas');
            $namaFileIdentitas = time() . '_ID_' . $fileIdentitas->getClientOriginalName();

            // Simpan ke folder public/assets/resource/identitas
            $fileIdentitas->move(public_path('assets/resource/identitas'), $namaFileIdentitas);

            // Update kolom identitas di database
            $user->identitas = $namaFileIdentitas;

            // Otomatis ubah role menjadi penjual jika role saat ini adalah pembeli/buyer
            if (strtolower($user->role) === 'pembeli' || strtolower($user->role) === 'buyer') {
                $user->role = 'seller'; // Sesuaikan string 'penjual' dengan enum di database Anda
            }
        }

        // Simpan perubahan ke database
        $user->save();

        return redirect()->back()->with('success', 'Profil berhasil diperbarui!');
    }
}
