<x-dashboard.main title="Data Peminjaman">
    @if (Auth::user()->role === 'admin')
        <div class="grid sm:grid-cols-2 xl:grid-cols-2 gap-5 md:gap-6">
            @foreach (['peminjaman_terbaru', 'tanggal_peminjaman'] as $type)
                <div class="flex items-center px-4 py-3 bg-neutral border rounded-xl shadow-sm">
                    <span
                        class="
                    {{ $type == 'peminjaman_terbaru' ? 'bg-pink-300' : '' }}
                    {{ $type == 'tanggal_peminjaman' ? 'bg-pink-300' : '' }}
                    p-3 mr-4 rounded-full">
                    </span>
                    <div>
                        <p class="text-sm font-medium capitalize text-white">
                            {{ str_replace('_', ' ', $type) }}
                        </p>
                        <p id="{{ $type }}" class="text-lg font-semibold text-white capitalize">
                            {{ $type == 'peminjaman_terbaru' ? $peminjaman_terbaru->buku->judul ?? 'Tidak ada buku terbaru' : '' }}
                            {{ $type == 'tanggal_peminjaman' ? $tanggal_peminjaman ?? '0' : '' }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="flex flex-col lg:flex-row gap-5">
            @if (Auth::user()->role === 'admin')
                @foreach (['tambah_peminjaman'] as $item)
                    <div onclick="{{ $item . '_modal' }}.showModal()"
                        class="bg-neutral flex items-center justify-between p-5 sm:p-7 hover:shadow-md active:scale-[.97] border border-blue-200 cursor-pointer border-back rounded-xl w-full">
                        <div>
                            <h1
                                class="text-white font-semibold flex items-start gap-3 font-semibold font-[onest] sm:text-lg capitalize">
                                {{ str_replace('_', ' ', $item) }}
                            </h1>
                            <p class="text-sm opacity-60 text-white">
                                {{ $item == 'tambah_peminjaman' ? 'Fitur Peminjaman Buku memungkinkan pengguna untuk meminjam buku yang tersedia di perpustakaan. Pada fitur ini, pengguna dapat melihat detail buku, mengecek ketersediaan stok, lalu melakukan proses peminjaman dengan mudah.' : '' }}
                            </p>
                        </div>
                        <x-lucide-plus
                            class="{{ $item == 'tambah_peminjaman' ? '' : 'hidden' }} size-5 sm:size-7 font-semibold text-white" />
                    </div>
                @endforeach
            @endif
        </div>
    @endif
    <div class="flex gap-5">
        @foreach (['Daftar_peminjaman'] as $item)
            <div class="flex flex-col border-back rounded-xl w-full">
                <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] text-lg capitalize">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60">
                        Jelajahi dan ketahui daftar peminjaman buku.
                    </p>
                </div>
                <div class="flex flex-col rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7 bg-neutral">
                    <div class="overflow-x-auto">
                        <table class="table table-zebra w-full">
                            <thead>
                                <tr>
                                    @foreach (['No', 'buku', 'nama peminjam', 'tanggal peminjaman', 'jatuh tempo', 'status', ''] as $header)
                                        <th class="uppercase font-bold text-center text-white">{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($peminjaman as $item => $i)
                                    <tr>
                                        <td class="text-center text-white">{{ $item + 1 }}</td>
                                        <td class="text-center text-white">{{ $i->buku?->judul }}</td>
                                        <td class="text-center text-white">{{ $i->pengunjung?->user?->nama }}</td>
                                        <td class="text-center text-white">
                                            {{ \Carbon\Carbon::parse($i->tanggal_pinjam)->format('d-m-Y') }}
                                        </td>
                                        <td class="text-center text-white">
                                            {{ \Carbon\Carbon::parse($i->tanggal_kembali)->format('d-m-Y') }}
                                        </td>
                                        <td class="text-center text-white">
                                            {{ $i->status == 'dipinjam' ? 'Dipinjam' : 'Dikembalikan' }}</td>
                                        <td class="flex items-center whitespace-nowrap gap-4 justify-center">
                                            <div class="tooltip" data-tip="Edit Peminjaman">
                                                <button type="button" class="btn btn-xs btn-outline btn-warning"
                                                    onclick="document.getElementById('update-modal-{{ $i->id }}').showModal()">
                                                    <x-lucide-pen class="w-4 h-4" />
                                                </button>
                                            </div>
                                            <div class="tooltip" data-tip="Hapus Peminjaman">
                                                <button type="button"
                                                    onclick="document.getElementById('hapus-modal-{{ $i->id }}').showModal()"
                                                    class="btn btn-xs btn-outline btn-error">
                                                    <x-lucide-trash class="w-4 h-4" />
                                                </button>
                                            </div>
                                        </td>
                                        <dialog id="hapus-modal-{{ $i->id }}"
                                            class="modal modal-bottom sm:modal-middle">
                                            <div class="modal-box bg-neutral text-white">
                                                <h3 class="text-lg font-bold">Hapus Peminjaman</h3>

                                                <form method="POST" action="{{ route('peminjaman.delete', $i->id) }}">
                                                    @csrf
                                                    @method('DELETE')

                                                    <p class="mt-2">
                                                        Apakah Anda yakin ingin menghapus peminjaman
                                                        <span class="font-bold">{{ $i->buku?->judul }}</span>?
                                                    </p>

                                                    <div class="modal-action mt-5">
                                                        <button type="button" class="btn"
                                                            onclick="document.getElementById('hapus-modal-{{ $i->id }}').close()">
                                                            Batal
                                                        </button>

                                                        <button type="submit" class="btn btn-error"
                                                            onclick="closeAllModals(event)">
                                                            Hapus
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </dialog>

                                        <dialog id="update-modal-{{ $i->id }}"
                                            class="modal modal-bottom sm:modal-middle">
                                            <div class="modal-box bg-neutral text-white">
                                                <h3 class="text-lg font-bold">Update Peminjaman</h3>

                                                <div class="mt-3">
                                                    <form method="POST"
                                                        action="{{ route('peminjaman.update', $i->id) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf
                                                        @method('PUT')

                                                        <div class="mb-4">
                                                            <label
                                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Buku</label>
                                                            <select name="buku"
                                                                class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5 @error('buku') border-red-500 @enderror">
                                                                <option value="" disabled hidden>Pilih Buku
                                                                </option>
                                                                @foreach ($buku as $b)
                                                                    <option value="{{ $b->id }}"
                                                                        {{ old('buku', $i->buku_id) == $b->id ? 'selected' : '' }}>
                                                                        {{ $b->judul }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('buku')
                                                                <span
                                                                    class="text-red-500 text-sm">{{ $message }}</span>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-4">
                                                            <label
                                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                                                                Peminjam</label>
                                                            <select name="nama_peminjam"
                                                                class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5 @error('nama_peminjam') border-red-500 @enderror">
                                                                <option value="" disabled hidden>Pilih
                                                                    Peminjam</option>
                                                                @foreach ($pengunjung as $p)
                                                                    <option value="{{ $p->id }}"
                                                                        {{ old('nama_peminjam', $i->user_id) == $p->id ? 'selected' : '' }}>
                                                                        {{ $p->user?->nama }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('nama_peminjam')
                                                                <span
                                                                    class="text-red-500 text-sm">{{ $message }}</span>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-4">
                                                            <label
                                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal
                                                                Peminjaman</label>
                                                            <input type="date" name="tanggal_pinjam"
                                                                class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5 @error('tanggal_pinjam') border-red-500 @enderror"
                                                                value="{{ old('tanggal_pinjam', date('Y-m-d', strtotime($i->tanggal_pinjam))) }}">
                                                            @error('tanggal_pinjam')
                                                                <span
                                                                    class="text-red-500 text-sm">{{ $message }}</span>
                                                            @enderror
                                                        </div>

                                                        <div class="mb-4">
                                                            <label
                                                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jatuh
                                                                Tempo</label>
                                                            <input type="date" name="jatuh_tempo"
                                                                class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg block w-full p-2.5 @error('jatuh_tempo') border-red-500 @enderror"
                                                                value="{{ old('jatuh_tempo', date('Y-m-d', strtotime($i->tanggal_kembali))) }}">
                                                            @error('jatuh_tempo')
                                                                <span
                                                                    class="text-red-500 text-sm">{{ $message }}</span>
                                                            @enderror
                                                        </div>

                                                        <div class="modal-action mt-5">
                                                            <button type="button" class="btn"
                                                                onclick="document.getElementById('update-modal-{{ $i->id }}').close()">
                                                                Batal
                                                            </button>
                                                            <button type="submit" class="btn btn-primary"
                                                                onclick="closeAllModals(event)">Update</button>
                                                        </div>

                                                    </form>
                                                </div>
                                            </div>
                                        </dialog>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-white">Tidak ada data peminjaman.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</x-dashboard.main>

<dialog id="tambah_peminjaman_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box bg-neutral text-white">
        <h3 class="text-lg font-bold">Tambah Peminjaman</h3>
        <div class="mt-3">
            <form method="POST" action="{{ route('peminjaman.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-4">
                    <label for="buku"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Buku</label>
                    <select name="buku" id="buku"
                        class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error('buku') border-red-500 @enderror">
                        <option value="" selected disabled hidden>Pilih Buku</option>
                        @foreach ($buku as $item)
                            <option value="{{ $item->id }}">{{ $item->judul }}</option>
                        @endforeach
                    </select>
                    @error('buku')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="user" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama
                        Peminjam</label>
                    <select name="nama_peminjam" id="nama_peminjam"
                        class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error('nama_peminjam') border-red-500 @enderror">
                        <option value="" selected disabled hidden>Pilih Peminjam</option>
                        @foreach ($pengunjung as $item)
                            <option value="{{ $item->id }}">{{ $item->user?->nama }}</option>
                        @endforeach
                    </select>
                    @error('nama_peminjam')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="tanggal_pinjam"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Peminjaman</label>
                    <input type="date" name="tanggal_pinjam" id="tanggal_pinjam"
                        class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error('tanggal_pinjam') border-red-500 @enderror"
                        value="{{ date('Y-m-d') }}">
                    @error('tanggal_pinjam')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mb-4">
                    <label for="jatuh_tempo"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jatuh
                        Tempo</label>
                    <input type="date" name="jatuh_tempo" id="jatuh_tempo"
                        class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error('jatuh_tempo') border-red-500 @enderror"
                        value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                    @error('jatuh_tempo')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
                <div class="modal-action mt-5">
                    <button type="button" onclick="document.getElementById('tambah_peminjaman_modal').close()"
                        class="btn">Batal</button>
                    <button type="submit" class="btn btn-primary" onclick="closeAllModals(event)">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</dialog>
