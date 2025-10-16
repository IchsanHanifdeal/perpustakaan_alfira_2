<x-dashboard.main title="Kunjungan">
    <div class="flex flex-col lg:flex-row gap-5">
        @if (Auth::user()->role === 'user')
            @foreach (['ambil_absensi'] as $item)
                <div onclick="{{ $item . '_modal' }}.showModal()"
                    class="bg-neutral flex items-center justify-between p-5 sm:p-7 hover:shadow-md active:scale-[.97] border border-blue-200 cursor-pointer border-back rounded-xl w-full">
                    <div>
                        <h1
                            class="text-white font-semibold flex items-start gap-3 font-semibold font-[onest] sm:text-lg capitalize">
                            {{ str_replace('_', ' ', $item) }}
                        </h1>
                        <p class="text-sm opacity-60 text-white">
                            {{ $item == 'ambil_absensi' ? 'Ambil Absensi Tiap Kali Berkunjung.' : '' }}
                        </p>
                    </div>
                    <x-lucide-clock
                        class="{{ $item == 'ambil_absensi' ? '' : 'hidden' }} size-5 sm:size-7 font-semibold text-white" />
                </div>
            @endforeach
        @endif
    </div>
    <div class="flex gap-5">
        @foreach (['Daftar_kunjungan'] as $item)
            <div class="flex flex-col border-back rounded-xl w-full">
                <div class="p-5 sm:p-7 bg-white rounded-t-xl">
                    <h1 class="flex items-start gap-3 font-semibold font-[onest] text-lg capitalize">
                        {{ str_replace('_', ' ', $item) }}
                    </h1>
                    <p class="text-sm opacity-60">
                        Jelajahi dan ketahui kunjungan terbaru.
                    </p>
                </div>
                <div class="flex flex-col rounded-b-xl gap-3 divide-y pt-0 p-5 sm:p-7 bg-neutral">
                    <div class="overflow-x-auto">
                        <table class="table table-zebra w-full">
                            <thead>
                                <tr>
                                    @foreach (['No', 'nama', 'email', 'nisn', 'kelas', 'waktu kunjungan'] as $header)
                                        <th class="uppercase font-bold text-center text-white">{{ $header }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kunjungan as $item => $i)
                                    <tr>
                                        <td class="text-center text-white">{{ $item + 1 }}</td>
                                        <td class="text-center text-white">{{ $i->pengunjung?->user?->nama }}</td>
                                        <td class="text-center text-white">{{ $i->pengunjung?->user?->email }}</td>
                                        <td class="text-center text-white">{{ $i->pengunjung?->nisn }}</td>
                                        <td class="text-center text-white">{{ $i->pengunjung?->kelas }}</td>
                                        <td class="text-center text-white">
                                            {{ \Carbon\Carbon::parse($i->visit_time)->format('d/m/Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-white">Tidak ada data kunjungan.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <dialog id="ambil_absensi_modal" class="modal modal-bottom sm:modal-middle">
        <div
            class="modal-box bg-gradient-to-br from-[#0d1b2a] to-[#1b263b] text-white rounded-xl border border-orange-500/30 shadow-lg">
            <h3 class="text-lg font-bold text-orange-400">Ambil Absensi Kunjungan</h3>
            <form method="POST" action="{{ route('kunjungan.store') }}">
                @csrf

                <p class="mt-3 text-gray-300">
                    Apakah Anda yakin ingin mengambil absensi kunjungan
                </p>

                <div class="modal-action mt-5">
                    <button type="button"
                        onclick="document.getElementById('ambil_absensi_modal').close()"
                        class="btn border border-orange-500/30 bg-gray-700 hover:bg-gray-600 text-white">
                        Batal
                    </button>

                    <button type="submit"
                        class="btn bg-orange-600 hover:bg-orange-700 text-white border border-orange-500/30"
                        onclick="closeAllModals(event)">
                        Ya, Ambil Absensi
                    </button>
                </div>
            </form>
        </div>
    </dialog>

</x-dashboard.main>
