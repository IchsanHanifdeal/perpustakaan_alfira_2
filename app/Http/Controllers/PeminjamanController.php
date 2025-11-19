<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Peminjaman;
use App\Models\Pengunjung;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class PeminjamanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $peminjaman_terbaru = Peminjaman::with('buku')
            ->orderBy('created_at', 'desc')
            ->first();

        $tanggal_peminjaman = $peminjaman_terbaru
            ? $peminjaman_terbaru->created_at->format('d-m-Y')
            : null;

        return view('dashboard.peminjaman', [
            'peminjaman'           => Peminjaman::all(),
            'peminjaman_terbaru'   => $peminjaman_terbaru,
            'tanggal_peminjaman'   => $tanggal_peminjaman,
            'buku'                 => Buku::all(),
            'pengunjung'           => Pengunjung::all(),
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
            Log::info('Memulai proses store peminjaman', [
                'request' => $request->all()
            ]);

            $validatedData = $request->validate([
                'buku'         => 'required|exists:buku,id',
                'nama_peminjam'   => 'required|string|max:255',
                'tanggal_pinjam'  => 'required|date',
                'jatuh_tempo' => 'required|date|after_or_equal:tanggal_pinjam',
            ]);

            Log::info('Validasi berhasil', $validatedData);

            $buku = Buku::findOrFail($validatedData['buku']);

            Log::info('Data buku ditemukan', [
                'buku_id' => $buku->id,
                'stock'   => $buku->stock
            ]);

            if ($buku->stock <= 0) {
                Log::warning('Stok buku habis', [
                    'buku' => $buku->id
                ]);

                return redirect()->back()
                    ->withErrors(['message' => 'Stok buku tidak tersedia.'])
                    ->withInput();
            }

            $peminjaman = Peminjaman::create([
                'buku_id'         => $validatedData['buku'],
                'user_id'   => $validatedData['nama_peminjam'],
                'tanggal_pinjam'  => $validatedData['tanggal_pinjam'],
                'tanggal_kembali'     => $validatedData['jatuh_tempo'],
                'status'          => 'dipinjam',
            ]);

            Log::info('Data peminjaman berhasil dibuat', [
                'peminjaman_id' => $peminjaman->id
            ]);

            $buku->decrement('stock', 1);

            Log::info('Stok buku berhasil dikurangi', [
                'buku' => $buku->id,
                'sisa_stock' => $buku->fresh()->stock
            ]);

            DB::commit();

            Log::info('Transaksi peminjaman berhasil disimpan');

            ToastMagic::success('Peminjaman berhasil ditambahkan.');

            return redirect()->route('peminjaman')->with('toast', [
                'message' => 'Peminjaman berhasil ditambahkan.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {

            Log::error('Error saat menyimpan peminjaman', [
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            DB::rollBack();

            return redirect()->back()
                ->withErrors(['message' => 'Gagal menambahkan peminjaman. Silakan coba lagi.'])
                ->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Peminjaman $peminjaman)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Peminjaman $peminjaman)
    {
        //
    }
}
