<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\Pengembalian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class PengembalianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengembalian_terbaru = Pengembalian::with(['peminjaman.buku', 'peminjaman.pengunjung.user'])
            ->orderBy('created_at', 'desc')
            ->first();

        $tanggal_pengembalian = $pengembalian_terbaru
            ? $pengembalian_terbaru->created_at->format('d-m-Y')
            : null;

        return view('dashboard.pengembalian', [
            'pengembalian'          => Pengembalian::with(['peminjaman.buku', 'peminjaman.pengunjung.user'])->get(),
            'pengembalian_terbaru'  => $pengembalian_terbaru,
            'tanggal_pengembalian'  => $tanggal_pengembalian,
            'peminjamanDipinjam'  => Peminjaman::where('status', 'dipinjam')->get(),
            'peminjaman'            => Peminjaman::with(['buku', 'pengunjung.user'])
                ->where('status', 'dipinjam')
                ->get(),
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
            Log::info('Memulai proses store pengembalian', [
                'request' => $request->all()
            ]);

            $validatedData = $request->validate([
                'peminjaman_id'    => 'required|exists:peminjamen,id',
                'tanggal_kembali'  => 'required|date',
                'denda'            => 'nullable|string|max:255',
            ]);

            Log::info('Validasi pengembalian berhasil', $validatedData);

            $peminjaman = Peminjaman::with('buku')->findOrFail($validatedData['peminjaman_id']);

            Log::info('Data peminjaman ditemukan', [
                'peminjaman_id' => $peminjaman->id,
                'status'        => $peminjaman->status
            ]);

            if ($peminjaman->status === 'dikembalikan') {
                Log::warning('Peminjaman sudah dikembalikan sebelumnya', [
                    'peminjaman_id' => $peminjaman->id
                ]);

                return redirect()->back()
                    ->withErrors(['message' => 'Buku sudah dikembalikan sebelumnya.'])
                    ->withInput();
            }

            $pengembalian = Pengembalian::create([
                'peminjaman_id'   => $validatedData['peminjaman_id'],
                'tanggal_kembali' => $validatedData['tanggal_kembali'],
                'denda'           => $validatedData['denda'] ?? null,
            ]);

            Log::info('Pengembalian berhasil dibuat', [
                'pengembalian_id' => $pengembalian->id
            ]);

            $peminjaman->update([
                'status' => 'dikembalikan'
            ]);

            $peminjaman->buku->increment('stock', 1);

            Log::info('Stok buku ditambah setelah pengembalian', [
                'buku_id' => $peminjaman->buku->id,
                'stock'   => $peminjaman->buku->fresh()->stock
            ]);

            DB::commit();

            Log::info('Transaksi pengembalian berhasil disimpan');

            ToastMagic::success('Pengembalian berhasil disimpan.');

            return redirect()->route('pengembalian')->with('toast', [
                'message' => 'Pengembalian berhasil disimpan.',
                'type'    => 'success'
            ]);
        } catch (\Exception $e) {

            Log::error('Error saat menyimpan pengembalian', [
                'error_message' => $e->getMessage(),
                'trace'         => $e->getTraceAsString()
            ]);

            DB::rollBack();

            return redirect()->back()
                ->withErrors(['message' => 'Gagal menyimpan pengembalian. Silakan coba lagi.'])
                ->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(Pengembalian $pengembalian)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengembalian $pengembalian)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengembalian $pengembalian)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengembalian $pengembalian)
    {
        //
    }
}
