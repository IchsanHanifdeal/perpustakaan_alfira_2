<x-dashboard.main title="Data Pengembalian">
    <div class="grid sm:grid-cols-2 xl:grid-cols-2 gap-5 md:gap-6">
        @foreach (['pengembalian_terbaru', 'tanggal_pengembalian'] as $type)
            <div class="flex items-center px-4 py-3 bg-neutral border rounded-xl shadow-sm">
                <span
                    class="
                    {{ $type == 'pengembalian_terbaru' ? 'bg-green-300' : '' }}
                    {{ $type == 'tanggal_pengembalian' ? 'bg-green-300' : '' }}
                    p-3 mr-4 rounded-full">
                </span>
                <div>
                    <p class="text-sm font-medium capitalize text-white">
                        {{ str_replace('_', ' ', $type) }}
                    </p>
                    <p id="{{ $type }}" class="text-lg font-semibold text-white capitalize">
                        {{ $type == 'pengembalian_terbaru' ? $pengembalian_terbaru->peminjaman->buku->judul ?? 'Tidak ada pengembalian terbaru' : '' }}
                        {{ $type == 'tanggal_pengembalian' ? $tanggal_pengembalian ?? '0' : '' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    <div class="flex flex-col lg:flex-row gap-5">
        @if (Auth::user()->role === 'admin')
            @foreach (['tambah_pengembalian'] as $item)
                <div onclick="{{ $item . '_modal' }}.showModal()"
                    class="bg-neutral flex items-center justify-between p-5 sm:p-7 hover:shadow-md active:scale-[.97] border border-green-300 cursor-pointer rounded-xl w-full">
                    <div>
                        <h1 class="text-white font-semibold flex items-start gap-3 sm:text-lg capitalize">
                            {{ str_replace('_', ' ', $item) }}
                        </h1>
                        <p class="text-sm opacity-60 text-white">
                            {{ $item == 'tambah_pengembalian' ? 'Fitur ini digunakan untuk mencatat proses pengembalian buku oleh peminjam dan menghitung denda jika ada.' : '' }}
                        </p>
                    </div>
                    <x-lucide-check class="size-5 sm:size-7 text-white" />
                </div>
            @endforeach
        @endif
    </div>

    <div class="flex gap-5 mt-5">
        <div class="flex flex-col border-green-300 rounded-xl w-full">
            <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                <h1 class="flex items-start gap-3 font-semibold text-lg capitalize">
                    Daftar Pengembalian
                </h1>
                <p class="text-sm opacity-60">Data pengembalian buku perpustakaan.</p>
            </div>
            <div class="flex flex-col rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7 bg-neutral">
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr>
                                @foreach (['No', 'Buku', 'Nama Peminjam', 'Tgl Pinjam', 'Jatuh Tempo', 'Tgl Kembali', 'Denda'] as $header)
                                    <th class="uppercase font-bold text-center text-white">{{ $header }}</th>
                                @endforeach
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($pengembalian as $index => $item)
                                <tr>
                                    <td class="text-center text-white">{{ $index + 1 }}</td>
                                    <td class="text-center text-white">{{ $item->peminjaman->buku->judul }}</td>
                                    <td class="text-center text-white">{{ $item->peminjaman->pengunjung?->user?->nama }}</td>

                                    <td class="text-center text-white">
                                        {{ \Carbon\Carbon::parse($item->peminjaman->tanggal_pinjam)->format('d-m-Y') }}
                                    </td>

                                    <td class="text-center text-white">
                                        {{ \Carbon\Carbon::parse($item->peminjaman->jatuh_tempo)->format('d-m-Y') }}
                                    </td>

                                    <td class="text-center text-white">
                                        {{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d-m-Y') }}
                                    </td>

                                    <td class="text-center text-white">
                                        {{ $item->denda ? 'Rp ' . number_format($item->denda, 0, ',', '.') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-white">Belum ada pengembalian.</td>
                                </tr>
                            @endforelse
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>
</x-dashboard.main>


{{-- MODAL TAMBAH PENGEMBALIAN --}}
<dialog id="tambah_pengembalian_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box bg-neutral text-white">
        <h3 class="text-lg font-bold">Tambah Pengembalian</h3>

        <form method="POST" action="{{ route('pengembalian.store') }}" class="mt-3">
            @csrf

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium">Pilih Peminjaman</label>
                <select name="peminjaman_id"
                    class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5">
                    <option value="" disabled selected>Pilih Peminjaman</option>

                    @foreach ($peminjamanDipinjam as $item)
                        <option value="{{ $item->id }}">
                            {{ $item->buku->judul }} — {{ $item->pengunjung?->user?->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium">Tanggal Kembali</label>
                <input type="date" name="tanggal_kembali"
                    class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5"
                    value="{{ date('Y-m-d') }}">
            </div>

            <div class="mb-4">
                <label class="block mb-2 text-sm font-medium">Denda (Opsional)</label>
                <input type="number" name="denda"
                    class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5"
                    placeholder="Masukkan jumlah denda jika ada">
            </div>

            <div class="modal-action mt-5">
                <button type="button" onclick="tambah_pengembalian_modal.close()" class="btn">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</dialog>
