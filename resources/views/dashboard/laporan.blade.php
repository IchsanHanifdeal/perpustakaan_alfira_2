<x-dashboard.main title="Laporan">
    <div class="flex flex-col gap-5">
        <div class="bg-neutral p-5 sm:p-7 rounded-xl border border-blue-200">
            <h1 class="text-white font-semibold text-lg mb-4 font-[onest]">Filter Laporan</h1>
            <form action="{{ route('laporan') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                <div class="flex flex-col gap-2">
                    <label class="text-white text-sm font-medium">Jenis Laporan</label>
                    <select name="type" class="select select-bordered w-full bg-gray-300 text-black border-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Pilih Jenis Laporan</option>
                        <option value="pengunjung" {{ request('type') == 'pengunjung' ? 'selected' : '' }}>Data Pengunjung</option>
                        <option value="kunjungan" {{ request('type') == 'kunjungan' ? 'selected' : '' }}>Data Kunjungan</option>
                        <option value="peminjaman" {{ request('type') == 'peminjaman' ? 'selected' : '' }}>Data Peminjaman</option>
                        <option value="pengembalian" {{ request('type') == 'pengembalian' ? 'selected' : '' }}>Data Pengembalian</option>
                    </select>
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-white text-sm font-medium">Tanggal Mulai</label>
                    <input type="date" name="start_date" value="{{ request('start_date') }}" class="input input-bordered w-full bg-gray-300 text-black border-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex flex-col gap-2">
                    <label class="text-white text-sm font-medium">Tanggal Selesai</label>
                    <input type="date" name="end_date" value="{{ request('end_date') }}" class="input input-bordered w-full bg-gray-300 text-black border-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="flex gap-2 mt-2">
                    <button type="submit" class="btn btn-primary flex-1">
                        <x-lucide-search class="size-4 mr-2" /> Filter
                    </button>
                    @if($type && $data && count($data) > 0)
                        <button type="button" onclick="window.print()" class="btn btn-secondary">
                            <x-lucide-printer class="size-4 mr-2" /> Cetak
                        </button>
                    @endif
                </div>
            </form>
        </div>

        @if($type)
            <div id="printable-area" class="bg-neutral p-5 sm:p-7 rounded-xl border border-blue-200 overflow-x-auto shadow-xl">
                <div class="mb-8 text-center hidden print:block text-black">
                    <h1 class="text-3xl font-extrabold uppercase tracking-wider">Perpustakaan Alfira 2</h1>
                    <p class="text-lg mt-1 italic">Laporan {{ str_replace('_', ' ', $type) }}</p>
                    @if($start_date && $end_date)
                        <p class="text-sm mt-2">Periode: {{ \Carbon\Carbon::parse($start_date)->format('d F Y') }} - {{ \Carbon\Carbon::parse($end_date)->format('d F Y') }}</p>
                    @endif
                    <div class="mt-4 border-b-4 border-black w-full"></div>
                    <div class="mt-1 border-b border-black w-full"></div>
                </div>
                
                <div class="flex justify-between items-center mb-6 print:hidden">
                    <h1 class="text-white font-semibold text-xl capitalize font-[onest]">Hasil Laporan {{ str_replace('_', ' ', $type) }}</h1>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full text-white border-separate border-spacing-0">
                        <thead>
                            <tr class="bg-blue-600 text-white">
                                <th class="text-center rounded-tl-lg py-4">No</th>
                                @if($type == 'pengunjung')
                                    <th class="py-4">Nama</th>
                                    <th class="py-4">Email</th>
                                    <th class="py-4">NISN</th>
                                    <th class="py-4">Kelas</th>
                                    <th class="py-4 rounded-tr-lg">Terdaftar</th>
                                @elseif($type == 'kunjungan')
                                    <th class="py-4">Nama</th>
                                    <th class="py-4">NISN</th>
                                    <th class="py-4">Kelas</th>
                                    <th class="py-4 rounded-tr-lg">Waktu Kunjungan</th>
                                @elseif($type == 'peminjaman')
                                    <th class="py-4">Peminjam</th>
                                    <th class="py-4">Buku</th>
                                    <th class="py-4">Tgl Pinjam</th>
                                    <th class="py-4">Tgl Kembali</th>
                                    <th class="py-4 rounded-tr-lg">Status</th>
                                @elseif($type == 'pengembalian')
                                    <th class="py-4">Peminjam</th>
                                    <th class="py-4">Buku</th>
                                    <th class="py-4">Tgl Kembali</th>
                                    <th class="py-4 rounded-tr-lg">Denda</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-700">
                            @forelse($data as $index => $item)
                                <tr class="hover:bg-gray-800 transition-colors">
                                    <td class="text-center font-medium">{{ $index + 1 }}</td>
                                    @if($type == 'pengunjung')
                                        <td>{{ $item->user->nama ?? '-' }}</td>
                                        <td>{{ $item->user->email ?? '-' }}</td>
                                        <td>{{ $item->nisn }}</td>
                                        <td>{{ $item->kelas }}</td>
                                        <td>{{ $item->created_at->format('d/m/Y') }}</td>
                                    @elseif($type == 'kunjungan')
                                        <td>{{ $item->pengunjung->user->nama ?? '-' }}</td>
                                        <td>{{ $item->pengunjung->nisn ?? '-' }}</td>
                                        <td>{{ $item->pengunjung->kelas ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->visit_time)->format('d/m/Y H:i') }}</td>
                                    @elseif($type == 'peminjaman')
                                        <td>{{ $item->pengunjung->user->nama ?? '-' }}</td>
                                        <td>{{ $item->buku->judul ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d/m/Y') }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="px-3 py-1 rounded-full text-xs font-bold uppercase {{ $item->status == 'dipinjam' ? 'bg-amber-500 text-black' : ($item->status == 'dikembalikan' ? 'bg-emerald-500 text-white' : 'bg-gray-500 text-white') }}">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                    @elseif($type == 'pengembalian')
                                        <td>{{ $item->peminjaman->pengunjung->user->nama ?? '-' }}</td>
                                        <td>{{ $item->peminjaman->buku->judul ?? '-' }}</td>
                                        <td>{{ \Carbon\Carbon::parse($item->tanggal_kembali)->format('d/m/Y') }}</td>
                                        <td class="font-bold {{ ($item->denda ?? 0) > 0 ? 'text-red-400' : 'text-emerald-400' }}">
                                            Rp {{ number_format($item->denda ?? 0, 0, ',', '.') }}
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center py-10 opacity-50 italic">Tidak ada data ditemukan untuk periode ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-10 hidden print:block text-black">
                    <div class="flex justify-end">
                        <div class="text-center w-64">
                            <p>Dicetak pada: {{ now()->format('d/m/Y H:i') }}</p>
                            <div class="mt-20 border-b border-black w-full"></div>
                            <p class="mt-2 font-bold">( Petugas Perpustakaan )</p>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(request('type'))
             <div class="bg-neutral p-10 rounded-xl border border-blue-200 text-center">
                 <p class="text-white opacity-60">Silakan pilih jenis laporan yang valid.</p>
             </div>
        @else
            <div class="bg-neutral p-10 rounded-xl border border-blue-200 text-center flex flex-col items-center justify-center gap-4">
                <x-lucide-file-text class="size-16 text-blue-400 opacity-20" />
                <p class="text-white opacity-60 max-w-md">Pilih jenis laporan dan rentang tanggal di atas untuk melihat dan mencetak laporan perpustakaan.</p>
            </div>
        @endif
    </div>

    <style>
        @media print {
            body {
                background: white !important;
                margin: 0;
                padding: 0;
            }
            .sidebar, .navbar, .drawer-side, .btn, form, h1:not(.print\:block) {
                display: none !important;
            }
            #printable-area {
                border: none !important;
                box-shadow: none !important;
                background: white !important;
                color: black !important;
                padding: 0 !important;
                margin: 0 !important;
                width: 100% !important;
                position: static !important;
            }
            table {
                width: 100% !important;
                border-collapse: collapse !important;
                color: black !important;
            }
            th {
                background-color: #f3f4f6 !important;
                color: black !important;
                border: 1px solid #000 !important;
            }
            td {
                color: black !important;
                border: 1px solid #000 !important;
                background-color: transparent !important;
            }
            tr:nth-child(even) {
                background-color: #f9fafb !important;
            }
            .text-red-400 { color: black !important; font-weight: bold; }
            .text-emerald-400 { color: black !important; }
            .bg-amber-500, .bg-emerald-500, .bg-gray-500 {
                background: transparent !important;
                color: black !important;
                border: 1px solid #000 !important;
            }
        }
    </style>
</x-dashboard.main>