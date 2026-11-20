<?php

namespace App\Http\Controllers;

use App\Models\Penawaran;
use App\Models\GambarPenawaran;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;

class PenawaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Menggunakan whereIn agar aman jika di DB tertulis 'aktif', 'Active', atau 'active'
        $query = \App\Models\Penawaran::with(['Pengguna', 'gambarPenawaran'])
            ->whereIn('status', ['aktif', 'Active', 'active']);

        // 1. Fitur Filter Game (dari dropdown H1)
        if ($request->filled('game')) {
            $query->where('jenis', $request->game);
        }

        // TAMBAHAN: Menangkap parameter 'jenis' dari klik gambar di halaman Beranda
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // 2. Fitur Pencarian (Search by judul atau deskripsi)
        if ($request->filled('q')) {
            $search = $request->q;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'LIKE', '%' . $search . '%')
                    ->orWhere('deskripsi', 'LIKE', '%' . $search . '%');
            });
        }

        // 3 & 4. Fitur Urutkan (Harga & Lainnya digabung agar tidak saling bentrok)
        if ($request->filled('sort_harga')) {

            // Gunakan CAST AS UNSIGNED agar string dikonversi ke angka yang valid (Mencegah error VARCHAR)
            if ($request->sort_harga == 'termurah') {
                $query->orderBy(\Illuminate\Support\Facades\DB::raw('CAST(harga AS UNSIGNED)'), 'asc');
            } elseif ($request->sort_harga == 'termahal') {
                $query->orderBy(\Illuminate\Support\Facades\DB::raw('CAST(harga AS UNSIGNED)'), 'desc');
            }
        } elseif ($request->filled('sort_lainnya')) {

            if ($request->sort_lainnya == 'terbaru') {
                $query->orderBy('id_penawaran', 'desc');
            } elseif ($request->sort_lainnya == 'terlaris') {
                $query->inRandomOrder(); // Opsional: Ganti dengan logic terlaris yang sesuai
            }
        } else {
            // Default urutan: HANYA jalan jika user TIDAK menggunakan filter sort_harga maupun sort_lainnya
            $query->orderBy('id_penawaran', 'desc');
        }

        $totalItem = $query->count();

        $penawarans = $query->paginate(12)->appends($request->query());

        // Pastikan nama view ini sesuai dengan file blade Anda (misal: 'general.penawaran' atau lainnya)
        return view('penawaran.index', compact('penawarans', 'totalItem'));
    }
    public function detail_penawaran($id_penawaran)
    {
        // 1. Dekripsi (pecah) ID acak menjadi angka asli
        $decoded = \Vinkla\Hashids\Facades\Hashids::decode($id_penawaran);

        // 2. Keamanan: Jika URL dimanipulasi/ngawur, Hashids akan gagal dan return array kosong
        if (empty($decoded)) {
            abort(404, 'Penawaran tidak valid atau tidak ditemukan.');
        }

        // 3. Ambil ID aslinya (berada di index ke-0)
        $idAsli = $decoded[0];

        // 4. Cari ke database menggunakan ID yang sudah kembali jadi angka
        $data['penawaran'] = Penawaran::with([
            'Pengguna',
            'gambarPenawaran' => function ($query) {
                $query->orderBy('id_gambar', 'asc');
            }
        ])->findOrFail($idAsli);

        // (Opsional) Jika di view Anda butuh ID acak ini untuk tombol "Beli", Anda bisa menyisipkannya:
        $data['hashed_id'] = $id_penawaran;

        return view('penawaran.detail_penawaran', $data);
    }
    public function buat_penawaran()
    {
        return view('penawaran.buat_penawaran');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */

    private function generateKodePenawaran()
    {
        do {
            $nomor = Penawaran::count() + 1;
            $random = strtoupper(Str::random(15));
            $kode = 'MRT' . $random . $nomor . '112025';
        } while (Penawaran::where('kode_penawaran', $kode)->exists());

        return $kode;
    }
    public function store(Request $request)
    {
        $validate = $request->validate([
            'jenis' => 'required|string|max:100',
            'judul' => 'required|string|max:255',
            'gambar' => 'required|array|min:1',
            'gambar.*' => 'image|mimes:jpg,jpeg,png|max:2048',
            'deskripsi' => 'required|string',
            'informasi_penawaran' => 'required|string',
            'harga' => 'required|string|max:100',
        ]);

        if (!auth()->check()) {
            return redirect('/sign-in');
        }
        $validate['id_pengguna'] = auth()->user()->id_pengguna;
        $validate['kode_penawaran'] = $this->generateKodePenawaran();
        $validate['status'] = 'aktif';
        $validate['created_at'] = now();
        $validate['updated_at'] = now();
        unset($validate['gambar']);
        $penawaran = Penawaran::create($validate);
        foreach ($request->file('gambar') as $file) {

            $namaFile = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $file->move(
                public_path('assets/resource/penawaran'),
                $namaFile
            );

            GambarPenawaran::create([
                'id_penawaran' => $penawaran->id_penawaran,
                'gambar' => $namaFile,
            ]);
        }

        return redirect('/penawaran')
            ->with('Sukses', 'Penawaran berhasil ditambahkan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('penawaran.edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
