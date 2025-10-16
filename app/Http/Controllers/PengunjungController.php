<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kunjungan;
use App\Models\Pengunjung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class PengunjungController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.pengunjung', [
            'pengunjung' => Pengunjung::all(),
            'pengunjung_terbaru' => Pengunjung::latest()->first(),
            'jumlah_pengunjung' => Pengunjung::count(),
        ]);
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
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $validatedData = $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'nisn' => 'required|string|unique:pengunjungs,nisn',
                'kelas' => 'required|string|max:255',
            ]);

            $user = User::create([
                'nama' => $validatedData['nama'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['nisn']),
            ]);

            Pengunjung::create([
                'user_id' => $user->id,
                'nisn' => $validatedData['nisn'],
                'kelas' => $request->input('kelas'),
            ]);
            DB::commit();

            ToastMagic::success('Pengunjung berhasil ditambahkan.');
            return redirect()->route('pengunjung')->with('toast', [
                'message' => 'Pengunjung berhasil ditambahkan.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->withErrors(['message' => 'Gagal menyimpan nilai. Silakan coba lagi.'])->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengunjung $pengunjung)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengunjung $pengunjung)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            // Cari data pengunjung berdasarkan id
            $pengunjung = Pengunjung::findOrFail($id);
            $user = $pengunjung->user;

            // Validasi input
            $validatedData = $request->validate([
                'nama' => 'required|string|max:255',
                'email' => 'nullable|email',
                'nisn' => 'required|string|unique:pengunjungs,nisn,' . $pengunjung->id,
                'kelas' => 'required|string|max:255',
            ]);

            // Update data user
            $user->update([
                'nama' => $validatedData['nama'],
                'email' => $validatedData['email'],
                // jika ingin password direset otomatis saat NISN berubah
                'password' => $user->password, // default tidak berubah
            ]);

            // Jika NISN berubah, reset password agar sesuai NISN baru
            if ($validatedData['nisn'] !== $pengunjung->nisn) {
                $user->update([
                    'password' => Hash::make($validatedData['nisn']),
                ]);
            }

            // Update data pengunjung
            $pengunjung->update([
                'nisn' => $validatedData['nisn'],
                'kelas' => $validatedData['kelas'],
            ]);

            DB::commit();

            ToastMagic::success('Data pengunjung berhasil diperbarui.');
            return redirect()->route('pengunjung')->with('toast', [
                'message' => 'Data pengunjung berhasil diperbarui.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->withErrors(['message' => 'Gagal memperbarui data. Silakan coba lagi.'])
                ->withInput();
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        DB::beginTransaction();
        try {
            $id_user = Pengunjung::findOrFail($id)->user_id;
            $user = User::findOrFail($id_user);
            $pengunjung = Pengunjung::findOrFail($id);
            $pengunjung->delete();
            $user->delete();
            DB::commit();

            ToastMagic::success('Pegunjung berhasil dihapus.');
            return redirect()->back()->with('toast', [
                'message' => 'Petugas berhasil dihapus.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['error' => $e->getMessage()])
                ->with('toast', [
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                    'type' => 'error'
                ]);
        }
    }

    public function kunjungan()
    {
        if (Auth::user()->role == 'admin') {
            return view('dashboard.kunjungan', [
                'kunjungan' => Kunjungan::all()
            ]);
        } else {
            $id_user = Auth::user()->id;
            $pengunjung_id = Pengunjung::where('user_id', $id_user)->first();

            return view('dashboard.kunjungan', [
                'kunjungan' => Kunjungan::where('pengunjung_id', $pengunjung_id->id)->get()
            ]);
        }
    }

    public function kunjungan_store(Request $request)
    {
        $id_user = Auth::user()->id;
        $pengunjung_id = Pengunjung::where('user_id', $id_user)->first();
        Kunjungan::create([
            'pengunjung_id' => $pengunjung_id->id,
            'visit_time' => now(),
        ]);

        ToastMagic::success('Absensi kunjungan berhasil diambil.');
        return redirect()->route('kunjungan')->with('toast', [
            'message' => 'Absensi kunjungan berhasil diambil.',
            'type' => 'success'
        ]);
    }
}
