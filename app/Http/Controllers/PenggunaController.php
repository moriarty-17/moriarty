<?php

namespace App\Http\Controllers;

use App\Models\Penawaran;
use App\Models\Pengguna;
use App\Models\Ulasan;
use Vinkla\Hashids\Facades\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PenggunaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('user.index');
    }
    public function sign_up()
    {
        return view('user.sign_up');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required',
            'password' => 'required',
        ]);

        $login = $request->login;

        // Cek apakah input berupa email atau username
        $field = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'username';

        $credentials = [
            $field => $login,
            'password' => $request->password,
        ];

        // Coba login
        if (\Illuminate\Support\Facades\Auth::attempt($credentials)) {

            // Ambil data user yang baru saja berhasil login
            $user = \Illuminate\Support\Facades\Auth::user();

            // Pastikan nama kolom 'status' sesuai dengan yang ada di database Anda
            if (strtolower($user->status) === 'banned') {
                \Illuminate\Support\Facades\Auth::logout(); // Keluarkan kembali
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // Kembalikan pesan error banned
                return back()->withErrors([
                    'login' => 'Akun Anda di ban, silahkan hubungi bantuan.'
                ])->withInput($request->only('login'));
            }

            // Jika tidak dibanned, jalankan session dan redirect
            $request->session()->regenerate();

            // Redirect otomatis sesuai role di database
            if ($user->role === 'admin') {
                return redirect()->intended('/be/dashboard');
            } elseif ($user->role === 'seller') {
                return redirect()->intended('/seller-dashboard');
            } else {
                return redirect()->intended('/');
            }
        }

        // Jika gagal (password/email salah), kembalikan pesan error ke field 'login'
        return back()->withErrors([
            'login' => 'Email/username atau password salah.',
        ])->withInput($request->only('login'));
    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validate = $request->validate([
            'email'     => 'required|string|max:225',
            'username'  => 'required|string|max:125',
            'password'  => 'required|string|max:225',
            'role'      => 'required|string|max:16',
            'identitas' => $request->role === 'seller'
                ? 'required|image|mimes:jpg,jpeg,png|max:2048'
                : 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $validate['password'] = Hash::make($validate['password']);


        if ($request->hasFile('identitas')) {
            $file = $request->file('identitas');

            $namaFile = time() . '_' . $file->getClientOriginalName();

            $file->move(
                public_path('resource/identitas'),
                $namaFile
            );

            $validate['identitas'] = $namaFile;
        }

        Pengguna::create($validate);

        return redirect('/sign-in')->with('Sukses', 'Pendaftaran berhasil! Silakan Sign-in');
    }

    public function show(Request $request, $hash_id)
    {
        // 1. Decode ID
        $id_penjual = Hashids::decode($hash_id)[0] ?? null;
        if (!$id_penjual) abort(404);

        // 2. Ambil data Penjual (Ganti 'User' dengan 'Pengguna' jika perlu)
        $seller = Pengguna::findOrFail($id_penjual);

        // 3. Cek Tab yang sedang aktif (default: penawaran)
        $tab = $request->query('tab', 'penawaran');

        // 4. Hitung Statistik Ulasan Toko
        $totalUlasan = Ulasan::where('id_penjual', $id_penjual)->count();
        $avgRating = Ulasan::where('id_penjual', $id_penjual)->avg('rating') ?? 0;

        // 5. Ambil data sesuai tab yang diklik
        $penawarans = [];
        $ulasans = [];

        if ($tab == 'penawaran') {
            // Ambil semua produk milik penjual ini
            $penawarans = Penawaran::with('gambarPenawaran')
                ->where('id_pengguna', $id_penjual) 
                ->where('status', 'aktif')// Sesuaikan kolom foreign key penjual di tabel penawaran (id_user / id_pengguna)
                ->latest()
                ->get();
        } else {
            // Ambil semua ulasan yang masuk ke penjual ini, beserta data pembeli & produknya
            $ulasans = Ulasan::with(['pembeli', 'pemesanan.penawaran'])
                ->where('id_penjual', $id_penjual)
                ->latest()
                ->get();
        }

        return view('profil.seller', compact('seller', 'tab', 'totalUlasan', 'avgRating', 'penawarans', 'ulasans'));
    }

    public function cariPenjual(Request $request)
    {
        $keyword = $request->query('keyword');

        $query = Pengguna::where('role', 'seller');

        if ($keyword) {
            $query->where(function($q) use ($keyword) {
                // HANYA cari berdasarkan 'username'
                $q->where('username', 'like', '%' . $keyword . '%');
            });
        }

        $sellers = $query->paginate(12);

        return view('publik.daftar_seller', compact('sellers', 'keyword'));
    }

    public function cariSellerLive(Request $request)
    {
        $keyword = $request->query('keyword');

        if (!$keyword) {
            return response()->json([]);
        }

        $sellers = Pengguna::where('role', 'seller')
            ->where(function($q) use ($keyword) {
                $q->where('username', 'like', '%' . $keyword . '%');
            })
            ->limit(5)
            // Tambahkan kolom ID Anda di sini (misal: 'id_pengguna' atau 'id')
            ->get(['id_pengguna', 'username', 'gambar'])
            ->map(function ($seller) {
                // Encode ID menjadi hash_id
                $seller->hash_id = \Vinkla\Hashids\Facades\Hashids::encode($seller->id_pengguna);
                return $seller;
            });

        return response()->json($sellers);
    }

    /**
     * Display the specified resource.
     */
    
    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
