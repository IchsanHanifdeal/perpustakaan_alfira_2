<x-dashboard.main title="Buku">
    <div class="grid sm:grid-cols-2 xl:grid-cols-2 gap-5 md:gap-6">
        @foreach (['buku_terbaru', 'jumlah_buku'] as $type)
            <div class="flex items-center px-4 py-3 bg-neutral border rounded-xl shadow-sm">
                <span
                    class="
                    {{ $type == 'buku_terbaru' ? 'bg-pink-300' : '' }}
                    {{ $type == 'jumlah_buku' ? 'bg-pink-300' : '' }}
                    p-3 mr-4 rounded-full">
                </span>
                <div>
                    <p class="text-sm font-medium capitalize text-white">
                        {{ str_replace('_', ' ', $type) }}
                    </p>
                    <p id="{{ $type }}" class="text-lg font-semibold text-white capitalize">
                        {{ $type == 'buku_terbaru' ? $buku_terbaru->judul ?? 'Tidak ada buku terbaru' : '' }}
                        {{ $type == 'jumlah_buku' ? $jumlah_buku ?? '0' : '' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="flex flex-col lg:flex-row gap-5">
        @if (Auth::user()->role === 'admin')
            @foreach (['tambah_buku'] as $item)
                <div onclick="{{ $item . '_modal' }}.showModal()"
                    class="bg-neutral flex items-center justify-between p-5 sm:p-7 hover:shadow-md active:scale-[.97] border border-blue-200 cursor-pointer border-back rounded-xl w-full">
                    <div>
                        <h1
                            class="text-white font-semibold flex items-start gap-3 font-semibold font-[onest] sm:text-lg capitalize">
                            {{ str_replace('_', ' ', $item) }}
                        </h1>
                        <p class="text-sm opacity-60 text-white">
                            {{ $item == 'tambah_buku' ? 'Fitur Tambah buku memungkinkan pengguna untuk menambahkan buku baru.' : '' }}
                        </p>
                    </div>
                    <x-lucide-plus
                        class="{{ $item == 'tambah_buku' ? '' : 'hidden' }} size-5 sm:size-7 font-semibold text-white" />
                </div>
            @endforeach
        @endif
    </div>
    <div class="flex gap-5">
        <div class="flex flex-col border-back rounded-xl w-full">
            <div class="p-5 sm:p-7 bg-white rounded-t-xl flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] text-lg capitalize">
                        Daftar Buku
                    </h1>
                    <p class="text-sm opacity-60">
                        Jelajahi dan ketahui koleksi buku terbaru.
                    </p>
                </div>
                
                <form action="{{ route('buku') }}" method="GET" class="w-full sm:w-80">
                    <div class="relative">
                        <input type="text" name="q" value="{{ $search }}"
                            placeholder="Cari judul, penulis..." 
                            class="input input-sm input-bordered w-full pr-10 bg-gray-100 text-black border-gray-300 focus:border-primary">
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2">
                            <x-lucide-search class="size-4 opacity-50 text-black" />
                        </button>
                    </div>
                </form>
            </div>
                <div class="flex flex-col rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7 bg-neutral">
                    <div class="overflow-x-auto">
                        <table class="table table-zebra w-full">
                            <thead>
                                <tr>
                                    @foreach (['No', 'cover', 'judul', 'jenis', 'penerbit', 'penulis', 'tahun', 'stok'] as $header)
                                        <th class="uppercase font-bold text-center text-white">{{ $header }}</th>
                                    @endforeach

                                    @if (Auth::user()->role == 'admin')
                                        @foreach (['aksi'] as $header)
                                            <th class="uppercase font-bold text-center text-white">{{ $header }}
                                            </th>
                                        @endforeach
                                    @endif
                                </tr>
                            </thead>
                            <tbody id="buku-tbody">
                                @forelse ($buku as $i => $item)
                                    <tr>
                                        <td class="text-center whitespace-nowrap text-white">{{ $i + 1 }}</td>
                                        <td class="font-semibold capitalize text-center">
                                            <button type="button" 
                                                onclick="document.getElementById('lihat_modal_{{ $item->id }}').showModal()"
                                                class="w-full btn btn-primary flex items-center justify-center gap-2 text-white font-bold transition-all hover:scale-105 active:scale-95">
                                                <x-lucide-eye class="size-4" />
                                                <span>Lihat</span>
                                            </button>
                                        </td>

                                        <dialog id="lihat_modal_{{ $item->id }}" class="modal modal-middle">
                                            @php
                                                $imagePath = null;
                                                if ($item->cover) {
                                                    if (str_starts_with($item->cover, 'http')) {
                                                        $imagePath = $item->cover;
                                                    } else {
                                                        $imagePath = asset('storage/buku/' . $item->cover);
                                                    }
                                                }
                                            @endphp
                                            <div class="modal-box bg-white p-0 overflow-hidden max-w-2xl border-none shadow-2xl">
                                                <div class="flex flex-col md:flex-row min-h-[400px]">
                                                    <!-- Sisi Kiri: Gambar Cover -->
                                                    <div class="w-full md:w-5/12 bg-gray-50 flex items-center justify-center p-6 border-r border-gray-100">
                                                        @if($imagePath)
                                                            <div class="relative group cursor-zoom-in">
                                                                <img src="{{ $imagePath }}" 
                                                                    alt="{{ $item->judul }}" 
                                                                    class="w-full h-auto max-h-[350px] object-contain rounded shadow-lg transition-transform duration-300 group-hover:scale-[1.02]"
                                                                    loading="lazy"
                                                                    onerror="this.style.display='none'; this.nextElementSibling.style.display='flex'">
                                                                <div class="hidden flex-col items-center justify-center p-8 text-gray-400 text-center">
                                                                    <x-lucide-image-off class="size-16 opacity-20 mb-3" />
                                                                    <p class="text-xs font-medium">Gambar cover tidak ditemukan</p>
                                                                </div>
                                                            </div>
                                                        @else
                                                            <div class="flex flex-col items-center justify-center p-8 text-gray-300 text-center">
                                                                <div class="w-24 h-32 bg-gray-100 rounded-md border-2 border-dashed border-gray-200 flex items-center justify-center mb-4">
                                                                    <x-lucide-book-open class="size-10 opacity-20" />
                                                                </div>
                                                                <p class="text-sm font-semibold text-gray-400">Tidak ada cover</p>
                                                            </div>
                                                        @endif
                                                    </div>

                                                    <!-- Sisi Kanan: Detail Buku -->
                                                    <div class="w-full md:w-7/12 p-8 flex flex-col justify-between bg-white text-gray-800">
                                                        <div>
                                                            <div class="flex justify-between items-start mb-4">
                                                                <div class="badge badge-outline border-primary text-primary badge-sm font-bold uppercase tracking-wider">
                                                                    {{ $item->jenis }}
                                                                </div>
                                                                <form method="dialog">
                                                                    <button class="btn btn-xs btn-circle btn-ghost text-gray-400 hover:text-red-500">✕</button>
                                                                </form>
                                                            </div>
                                                            
                                                            <h2 class="text-2xl font-extrabold text-gray-900 leading-tight mb-6">
                                                                {{ $item->judul }}
                                                            </h2>

                                                            <div class="space-y-4">
                                                                <div class="flex items-start gap-3">
                                                                    <div class="p-2 bg-gray-50 rounded-lg">
                                                                        <x-lucide-user class="size-4 text-gray-500" />
                                                                    </div>
                                                                    <div>
                                                                        <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Penulis</p>
                                                                        <p class="text-sm font-semibold text-gray-700">{{ $item->penulis }}</p>
                                                                    </div>
                                                                </div>

                                                                <div class="flex items-start gap-3">
                                                                    <div class="p-2 bg-gray-50 rounded-lg">
                                                                        <x-lucide-building class="size-4 text-gray-500" />
                                                                    </div>
                                                                    <div>
                                                                        <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Penerbit</p>
                                                                        <p class="text-sm font-semibold text-gray-700">{{ $item->penerbit }}</p>
                                                                    </div>
                                                                </div>

                                                                <div class="flex items-start gap-3">
                                                                    <div class="p-2 bg-gray-50 rounded-lg">
                                                                        <x-lucide-calendar class="size-4 text-gray-500" />
                                                                    </div>
                                                                    <div>
                                                                        <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold">Tahun Terbit</p>
                                                                        <p class="text-sm font-semibold text-gray-700">{{ $item->tahun }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="mt-10 pt-6 border-t border-gray-100 flex items-center justify-between">
                                                            <div class="flex flex-col">
                                                                <span class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-1">Ketersediaan Stok</span>
                                                                <div class="flex items-center gap-2">
                                                                    <span class="text-xl font-black text-gray-900">{{ $item->stock }}</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </dialog>
                                        <td class="text-center whitespace-nowrap capitalize text-white">
                                            {{ $item->judul ?? '-' }}</td>
                                        <td class="text-center whitespace-nowrap capitalize text-white">
                                            {{ $item->jenis ?? '-' }}</td>
                                        <td class="text-center whitespace-nowrap capitalize text-white">
                                            {{ $item->penerbit ?? '-' }}</td>
                                        <td class="text-center whitespace-nowrap capitalize text-white">
                                            {{ $item->penulis ?? '-' }}</td>
                                        <td class="text-center whitespace-nowrap capitalize text-white">
                                            {{ $item->tahun ?? '-' }}</td>
                                        <td class="text-center whitespace-nowrap capitalize text-white">
                                            {{ $item->stock ?? '-' }}</td>
                                        @if (Auth::user()->role == 'admin')
                                            <td class="flex items-center whitespace-nowrap gap-4 justify-center">
                                                <div class="tooltip" data-tip="Edit Buku">
                                                    <button type="button" class="btn btn-xs btn-outline btn-warning"
                                                        onclick="document.getElementById('update-modal-{{ $item->id }}').showModal()">
                                                        <x-lucide-pen class="w-4 h-4" />
                                                    </button>
                                                </div>
                                                <dialog id="update-modal-{{ $item->id }}"
                                                    class="modal modal-bottom sm:modal-middle">
                                                    <div class="modal-box bg-neutral text-white">
                                                        <h3 class="text-lg font-bold">Edit Buku</h3>
                                                        <div class="mt-3">
                                                            <form method="POST"
                                                                action="{{ route('buku.update', $item->id) }}"
                                                                enctype="multipart/form-data">
                                                                @csrf
                                                                @method('PUT')

                                                                {{-- Cover Buku --}}
                                                                <div class="mb-4">
                                                                    <label for="cover-{{ $item->id }}"
                                                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cover
                                                                        Buku</label>
                                                                    <input type="file"
                                                                        id="cover-{{ $item->id }}" name="cover"
                                                                        accept="image/*"
                                                                        class="file-input w-full bg-gray-300 text-black"
                                                                        onchange="previewImageUpdate(event, {{ $item->id }})" />

                                                                    @php
                                                                        $prevPath = 'https://www.seoptimer.com/storage/images/2019/05/2744-404-redirection-1.png';
                                                                        if ($item->cover) {
                                                                            if (str_starts_with($item->cover, 'http')) {
                                                                                $prevPath = $item->cover;
                                                                            } else {
                                                                                $prevPath = asset('storage/buku/' . $item->cover);
                                                                            }
                                                                        }
                                                                    @endphp
                                                                    <div id="preview_update_{{ $item->id }}" class="mt-2">
                                                                        <img src="{{ $prevPath }}" alt="Cover Buku" class="h-24 rounded-md">
                                                                    </div>

                                                                    @error('cover')
                                                                        <span
                                                                            class="text-red-500 text-sm">{{ $message }}</span>
                                                                    @enderror
                                                                </div>

                                                                {{-- Input teks: judul, penerbit, penulis --}}
                                                                @foreach (['judul', 'penerbit', 'penulis'] as $type)
                                                                    <div class="mb-4 capitalize">
                                                                        <label
                                                                            for="{{ $type }}-{{ $item->id }}"
                                                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                                            {{ ucfirst(str_replace('_', ' ', $type)) }}
                                                                        </label>
                                                                        <input type="text"
                                                                            id="{{ $type }}-{{ $item->id }}"
                                                                            name="{{ $type }}"
                                                                            placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                                                            class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror capitalize"
                                                                            value="{{ old($type, $item->$type) }}" />
                                                                        @error($type)
                                                                            <span
                                                                                class="text-red-500 text-sm">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                @endforeach

                                                                {{-- Input angka: tahun, stock --}}
                                                                @foreach (['tahun', 'stock'] as $type)
                                                                    <div class="mb-4 capitalize">
                                                                        <label
                                                                            for="{{ $type }}-{{ $item->id }}"
                                                                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                                            {{ ucfirst(str_replace('_', ' ', $type)) }}
                                                                        </label>
                                                                        <input type="number"
                                                                            id="{{ $type }}-{{ $item->id }}"
                                                                            name="{{ $type }}"
                                                                            placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                                                            class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror capitalize"
                                                                            value="{{ old($type, $item->$type) }}" />
                                                                        @error($type)
                                                                            <span
                                                                                class="text-red-500 text-sm">{{ $message }}</span>
                                                                        @enderror
                                                                    </div>
                                                                @endforeach

                                                                {{-- Jenis Buku --}}
                                                                <div class="mb-4 capitalize">
                                                                    <label for="jenis-{{ $item->id }}"
                                                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis
                                                                        Buku</label>
                                                                    <select id="jenis-{{ $item->id }}"
                                                                        name="jenis"
                                                                        class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error('jenis') border-red-500 @enderror capitalize">
                                                                        <option value="">--- Pilih Jenis Buku ---
                                                                        </option>
                                                                        @foreach (['Pelajaran', 'Novel', 'Majalah', 'Kamus', 'Komik', 'Manga', 'Ensiklopedia', 'Kitab Suci', 'Biografi', 'Lainnya'] as $jenis)
                                                                            <option value="{{ $jenis }}"
                                                                                @selected(old('jenis', $item->jenis) == $jenis)>
                                                                                {{ $jenis }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('jenis')
                                                                        <span
                                                                            class="text-red-500 text-sm">{{ $message }}</span>
                                                                    @enderror
                                                                </div>

                                                                {{-- Tombol Aksi --}}
                                                                <div class="modal-action">
                                                                    <button type="button"
                                                                        onclick="document.getElementById('update-modal-{{ $item->id }}').close()"
                                                                        class="btn">Batal</button>
                                                                    <button type="submit" class="btn btn-primary"
                                                                        onclick="closeAllModals(event)">Update</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </dialog>

                                                <div class="tooltip" data-tip="Hapus Buku">
                                                    <button type="button"
                                                        onclick="document.getElementById('hapus-modal-{{ $item->id }}').showModal()"
                                                        class="btn btn-xs btn-outline btn-error">
                                                        <x-lucide-trash class="w-4 h-4" />
                                                    </button>
                                                </div>
                                                <dialog id="hapus-modal-{{ $item->id }}" class="modal">
                                                    <div
                                                        class="modal-box bg-gradient-to-br from-[#0d1b2a] to-[#1b263b] text-white rounded-xl border border-orange-500/30 shadow-lg">
                                                        <h3 class="text-lg font-bold text-orange-400">Hapus Buku</h3>
                                                        <form method="POST"
                                                            action="{{ route('buku.delete', $item->id) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <p class="mt-2 text-gray-300">Apakah Anda yakin ingin
                                                                menghapus
                                                                Buku
                                                                <span class="font-bold">{{ $item->judul }}</span>?
                                                            </p>
                                                            <div class="modal-action">
                                                                <button type="button"
                                                                    onclick="document.getElementById('hapus-modal-{{ $item->id }}').close()"
                                                                    class="btn bg-gray-700 text-white border border-orange-500/30 hover:bg-gray-600">
                                                                    Batal
                                                                </button>
                                                                <button type="submit"
                                                                    class="btn bg-red-600 hover:bg-red-700 text-white border border-red-700/30"
                                                                    onclick="closeAllModals(event)">
                                                                    Hapus
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </dialog>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-center text-white" colspan="8">Tidak ada data buku</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    <div class="mt-4 px-2 pb-2">
                        {{ $buku->links('vendor.pagination.tailwind') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <dialog id="tambah_buku_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box bg-neutral text-white">
            <h3 class="text-lg font-bold">Tambah Buku</h3>
            <div class="mt-3">
                <form method="POST" action="{{ route('buku.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label for="cover"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cover
                            Buku</label>
                        <input type="file" id="cover" name="cover" accept="image/*"
                            class="file-input w-full bg-gray-300 text-black" onchange="previewImage(event)" />
                        @error('cover')
                            <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                        <div id="preview_tambah" class="mt-2"></div>
                    </div>
                    @foreach (['judul', 'penerbit', 'penulis'] as $type)
                        <div class="mb-4 capitalize">
                            <label for="{{ $type }}"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $type)) }}</label>
                            <input type="text" id="{{ $type }}" name="{{ $type }}"
                                placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror capitalize"
                                value="{{ old($type) }}" />
                            @error($type)
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    @endforeach
                    @foreach (['tahun', 'stock'] as $type)
                        <div class="mb-4 capitalize">
                            <label for="{{ $type }}"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $type)) }}</label>
                            <input type="number" id="{{ $type }}" name="{{ $type }}"
                                placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror capitalize"
                                value="{{ old($type) }}" />
                            @error($type)
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    @endforeach
                    <div class="mb-4 capitalize">
                        <label for="jenis"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jenis
                            Buku</label>
                        <select id="jenis" name="jenis" placeholder="Masukan jenis..."
                            class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error('jenis') border-red-500 @enderror capitalize">
                            <option value="">--- Pilih Jenis Buku ---</option>
                            <option value="Pelajaran">Pelajaran</option>
                            <option value="Novel">Novel</option>
                            <option value="Majalah">Majalah</option>
                            <option value="Kamus">Kamus</option>
                            <option value="Komik">Komik</option>
                            <option value="Manga">Manga</option>
                            <option value="Ensiklopedia">Ensiklopedia</option>
                            <option value="Kitab Suci">Kitab Suci</option>
                            <option value="Biografi">Biografi</option>
                            <option value="Lainnya">lainnya</option>
                            @error('jenis')
                                <span class="text-red-500 text-sm">{{ $message }}</span>
                            @enderror
                        </select>
                    </div>
                    <div class="modal-action">
                        <button type="button" onclick="document.getElementById('tambah_buku_modal').close()"
                            class="btn">Batal</button>
                        <button type="submit" class="btn btn-primary"
                            onclick="closeAllModals(event)">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </dialog>

    <script>
        function previewImage(event) {
            const input = event.target;
            const previewContainer = document.getElementById('preview_tambah');
            previewContainer.innerHTML = '';

            if (input.files && input.files[0]) {
                const file = input.files[0];
                const fileType = file.type;

                if (fileType.startsWith('image/')) {
                    const previewElement = document.createElement('img');
                    previewElement.src = URL.createObjectURL(file);
                    previewElement.classList.add('rounded-lg', 'cursor-pointer');
                    previewElement.style.maxWidth = '100%'; // Make sure the image fits within its container
                    previewElement.style.maxHeight = '500px'; // Set a max height for the image

                    previewElement.onclick = function() {
                        input.value = ''; // Clear the input value
                        previewContainer.innerHTML = ''; // Clear the preview container
                    };

                    previewContainer.appendChild(previewElement);
                }
            }
        }

        function previewImageUpdate(event, id) {
            const input = event.target;
            const preview = document.getElementById('preview_update_' + id);
            preview.innerHTML = '';

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    const img = document.createElement('img');
                    img.src = e.target.result;
                    img.classList.add('h-24', 'rounded-md');
                    preview.appendChild(img);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-dashboard.main>
