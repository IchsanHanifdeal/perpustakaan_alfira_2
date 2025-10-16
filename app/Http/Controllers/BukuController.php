<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class BukuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.buku', [
            'buku_terbaru' => Buku::latest()->first(),
            'jumlah_buku' => Buku::count(),
            'buku' => Buku::all(),
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
        $request->validate([
            'cover' => 'required',
            'judul' => 'required|string',
            'penerbit' => 'required|string|max:255',
            'penulis' => 'required|string',
            'tahun' => 'required|digits:4',
            'stock' => 'required|integer',
            'jenis' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $fileName = null;

            if ($request->hasFile('cover')) {
                $cover = $request->file('cover');
                $extension = $cover->extension();

                $formattedDate = now()->format('md');
                $judulFormatted = str_replace(' ', '_', $request->judul); // Replace spaces with underscores for filename
                $fileName = 'COV' . $formattedDate . '' . $judulFormatted . '.' . $extension;

                $cover->storeAs('buku', $fileName, 'public');
            }

            $buku = new Buku();
            $buku->judul = $request->judul;
            $buku->penerbit = $request->penerbit;
            $buku->penulis = $request->penulis;
            $buku->tahun = $request->tahun;
            $buku->stock = $request->stock;
            $buku->jenis = $request->jenis;
            $buku->cover = $fileName;

            $buku->save();
            DB::commit();

            ToastMagic::success('Buku dengan judul ' . $buku->judul . ' berhasil ditambahkan!.');
            return redirect()->back()->with('toast', [
                'message' => 'Buku berhasil ditambahkan.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            ToastMagic::error('Buku dengan judul ' . $request->judul . ' gagal ditambahkan!.');
            return redirect()->back()->withErrors(['file_materi' => $e->getMessage()])
                ->withInput()
                ->with('toast', [
                    'message' => $e->getMessage(),
                    'type' => 'error'
                ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Buku $buku)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Buku $buku)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'cover' => 'nullable|image',
            'judul' => 'required|string',
            'penerbit' => 'required|string|max:255',
            'penulis' => 'required|string',
            'tahun' => 'required|digits:4',
            'stock' => 'required|integer',
            'jenis' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $buku = Buku::findOrFail($id);
            $fileName = $buku->cover; // Default: gunakan cover lama jika tidak diganti

            // Jika user upload cover baru
            if ($request->hasFile('cover')) {
                // Hapus cover lama (jika ada)
                if ($buku->cover && Storage::disk('public')->exists('buku/' . $buku->cover)) {
                    Storage::disk('public')->delete('buku/' . $buku->cover);
                }

                $cover = $request->file('cover');
                $extension = $cover->extension();

                $formattedDate = now()->format('md');
                $judulFormatted = str_replace(' ', '_', $request->judul);
                $fileName = 'COV' . $formattedDate . $judulFormatted . '.' . $extension;

                // Simpan file baru
                $cover->storeAs('buku', $fileName, 'public');
            }

            // Update data buku
            $buku->update([
                'judul' => $request->judul,
                'penerbit' => $request->penerbit,
                'penulis' => $request->penulis,
                'tahun' => $request->tahun,
                'stock' => $request->stock,
                'jenis' => $request->jenis,
                'cover' => $fileName,
            ]);

            DB::commit();

            ToastMagic::success('Buku dengan judul ' . $buku->judul . ' berhasil diperbarui!');
            return redirect()->back()->with('toast', [
                'message' => 'Buku berhasil diperbarui.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            ToastMagic::error('Buku dengan judul ' . $request->judul . ' gagal diperbarui!');
            return redirect()->back()
                ->withErrors(['update_error' => $e->getMessage()])
                ->withInput()
                ->with('toast', [
                    'message' => $e->getMessage(),
                    'type' => 'error'
                ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $buku = Buku::findOrFail($id);

            if ($buku->cover && Storage::exists('public/buku/' . $buku->cover)) {
                Storage::delete('public/buku/' . $buku->cover);
            }

            $buku->delete();
            DB::commit();

            ToastMagic::success('Buku dengan judul ' . $buku->judul . ' berhasil dihapus!.');
            return redirect()->back()->with('toast', [
                'message' => 'Buku berhasil dihapus.',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors(['file_materi' => $e->getMessage()])
                ->with('toast', [
                    'message' => $e->getMessage(),
                    'type' => 'error'
                ]);
        }
    }
}
