<x-dashboard.main title="Pengunjung">
    <div class="grid sm:grid-cols-2 xl:grid-cols-2 gap-5 md:gap-6">
        @foreach (['pengunjung_terbaru', 'jumlah_pengunjung'] as $type)
            <div class="flex items-center px-4 py-3 bg-neutral border rounded-xl shadow-sm">
                <span
                    class="
                    {{ $type == 'pengunjung_terbaru' ? 'bg-pink-300' : '' }}
                    {{ $type == 'jumlah_pengunjung' ? 'bg-pink-300' : '' }}
                    p-3 mr-4 rounded-full">
                </span>
                <div>
                    <p class="text-sm font-medium capitalize text-white">
                        {{ str_replace('_', ' ', $type) }}
                    </p>
                    <p id="{{ $type }}" class="text-lg font-semibold text-white capitalize">
                        {{ $type == 'pengunjung_terbaru' ? $pengunjung_terbaru->user->nama ?? 'Tidak ada pengunjung terbaru' : '' }}
                        {{ $type == 'jumlah_pengunjung' ? $jumlah_pengunjung ?? '0' : '' }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>
    <div class="flex flex-col lg:flex-row gap-5">
        @if (Auth::user()->role === 'admin')
            @foreach (['tambah_pengunjung'] as $item)
                <div onclick="{{ $item . '_modal' }}.showModal()"
                    class="bg-neutral flex items-center justify-between p-5 sm:p-7 hover:shadow-md active:scale-[.97] border border-blue-200 cursor-pointer border-back rounded-xl w-full">
                    <div>
                        <h1
                            class="text-white font-semibold flex items-start gap-3 font-semibold font-[onest] sm:text-lg capitalize">
                            {{ str_replace('_', ' ', $item) }}
                        </h1>
                        <p class="text-sm opacity-60 text-white">
                            {{ $item == 'tambah_pengunjung' ? 'Fitur Tambah buku memungkinkan admin untuk menambahkan pengunjung baru.' : '' }}
                        </p>
                    </div>
                    <x-lucide-plus
                        class="{{ $item == 'tambah_pengunjung' ? '' : 'hidden' }} size-5 sm:size-7 font-semibold text-white" />
                </div>
            @endforeach
        @endif
    </div>
    <div class="flex gap-5">
        @foreach (['Daftar_pengunjung'] as $item)
            <div class="flex flex-col border-back rounded-xl w-full">
                <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] text-lg capitalize">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60">
                        Jelajahi dan ketahui pengunjung terbaru.
                    </p>
                </div>
                <div class="flex flex-col rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7 bg-neutral">
                    <div class="overflow-x-auto">
                        <table class="table table-zebra w-full">
                            <thead>
                                <tr>
                                    @foreach (['No', 'nama', 'email', 'nisn', 'kelas'] as $header)
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
                            <tbody>
                                @forelse ($pengunjung as $i => $item)
                                    <tr>
                                        <td class="text-center whitespace-nowrap text-white">{{ $i + 1 }}</td>
                                        <td class="text-center whitespace-nowrap text-white">
                                            {{ $item->user->nama ?? '-' }}</td>
                                        <td class="text-center whitespace-nowrap text-white">
                                            {{ $item->user->email ?? '-' }}</td>
                                        <td class="text-center whitespace-nowrap text-white">{{ $item->nisn ?? '-' }}
                                        </td>
                                        <td class="text-center whitespace-nowrap text-white">{{ $item->kelas ?? '-' }}
                                        </td>
                                        <td class="flex items-center whitespace-nowrap gap-4 justify-center">
                                            <div class="tooltip" data-tip="Edit Pengunjung">
                                                <button type="button" class="btn btn-xs btn-outline btn-warning"
                                                    onclick="document.getElementById('update-modal-{{ $item->id }}').showModal()">
                                                    <x-lucide-pen class="w-4 h-4" />
                                                </button>
                                            </div>
                                            <dialog id="update-modal-{{ $item->id }}"
                                                class="modal modal-bottom sm:modal-middle">
                                                <div class="modal-box bg-neutral text-white">
                                                    <h3 class="text-lg font-bold">Edit Pengunjung</h3>
                                                    <div class="mt-3">
                                                        <form method="POST"
                                                            action="{{ route('pengunjung.update', $item->id) }}">
                                                            @csrf
                                                            @method('PUT')

                                                            @foreach (['nama', 'email', 'nisn', 'kelas'] as $type)
                                                                <div class="mb-4 capitalize">
                                                                    <label
                                                                        for="{{ $type }}-{{ $item->id }}"
                                                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">
                                                                        {{ ucfirst(str_replace('_', ' ', $type)) }}
                                                                    </label>

                                                                    @if ($type === 'email')
                                                                        <input type="email"
                                                                            id="{{ $type }}-{{ $item->id }}"
                                                                            name="{{ $type }}"
                                                                            placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                                                            class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror capitalize"
                                                                            value="{{ old($type, $item->user->$type) }}" />
                                                                        @error($type)
                                                                            <span
                                                                                class="text-red-500 text-sm">{{ $message }}</span>
                                                                        @enderror
                                                                    @elseif ($type === 'nisn')
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
                                                                    @elseif ($type == 'nama')
                                                                        <input type="text"
                                                                            id="{{ $type }}-{{ $item->id }}"
                                                                            name="{{ $type }}"
                                                                            placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                                                            class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror capitalize"
                                                                            value="{{ old($type, $item->user->$type) }}" />
                                                                        @error($type)
                                                                            <span
                                                                                class="text-red-500 text-sm">{{ $message }}</span>
                                                                        @enderror
                                                                    @else
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
                                                                    @endif
                                                                </div>
                                                            @endforeach

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

                                            <div class="tooltip" data-tip="Hapus Pengunjung">
                                                <button type="button"
                                                    onclick="document.getElementById('hapus-modal-{{ $item->id }}').showModal()"
                                                    class="btn btn-xs btn-outline btn-error">
                                                    <x-lucide-trash class="w-4 h-4" />
                                                </button>
                                            </div>
                                            <dialog id="hapus-modal-{{ $item->id }}" class="modal">
                                                <div
                                                    class="modal-box bg-gradient-to-br from-[#0d1b2a] to-[#1b263b] text-white rounded-xl border border-orange-500/30 shadow-lg">
                                                    <h3 class="text-lg font-bold text-orange-400">Hapus Pengunjung</h3>
                                                    <form method="POST"
                                                        action="{{ route('pengunjung.delete', $item->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <p class="mt-2 text-gray-300">Apakah Anda yakin ingin
                                                            menghapus Pengunjung
                                                            <span class="font-bold">{{ $item->user->nama }}</span>?
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
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-white">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <dialog id="tambah_pengunjung_modal" class="modal modal-bottom sm:modal-middle">
        <div class="modal-box bg-neutral text-white">
            <h3 class="text-lg font-bold">Tambah Pengunjung</h3>
            <div class="mt-3">
                <form method="POST" action="{{ route('pengunjung.store') }}">
                    @csrf
                    @foreach (['nama', 'email', 'nisn', 'kelas'] as $type)
                        <div class="mb-4 capitalize">
                            <label for="{{ $type }}"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{ ucfirst(str_replace('_', ' ', $type)) }}</label>
                            @if ($type === 'email')
                                <input type="email" id="{{ $type }}" name="{{ $type }}"
                                    placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                    class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror capitalize"
                                    value="{{ old($type) }}" />
                                @error($type)
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            @elseif ($type == 'nisn')
                                <input type="number" id="{{ $type }}" name="{{ $type }}"
                                    placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                    class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror capitalize"
                                    value="{{ old($type) }}" />
                                @error($type)
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            @else
                                <input type="text" id="{{ $type }}" name="{{ $type }}"
                                    placeholder="Masukan {{ str_replace('_', ' ', $type) }}..."
                                    class="bg-gray-300 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 @error($type) border-red-500 @enderror capitalize"
                                    value="{{ old($type) }}" />
                                @error($type)
                                    <span class="text-red-500 text-sm">{{ $message }}</span>
                                @enderror
                            @endif
                        </div>
                    @endforeach
                    <div class="modal-action">
                        <button type="button" onclick="document.getElementById('tambah_pengunjung_modal').close()"
                            class="btn">Batal</button>
                        <button type="submit" class="btn btn-primary"
                            onclick="closeAllModals(event)">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </dialog>
</x-dashboard.main>
