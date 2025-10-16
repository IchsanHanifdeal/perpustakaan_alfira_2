<x-dashboard.main title="Dashboard">
    <div class="p-6 text-white">
        {{-- Salam Dinamis --}}
        @php
            $hour = now()->format('H');
            if ($hour < 11) {
                $greeting = 'Selamat Pagi';
            } elseif ($hour < 15) {
                $greeting = 'Selamat Siang';
            } elseif ($hour < 18) {
                $greeting = 'Selamat Sore';
            } else {
                $greeting = 'Selamat Malam';
            }
        @endphp

        <h1 class="text-2xl font-bold mb-2 text-black">
            {{ $greeting }}, {{ Auth::user()->nama }}! 👋
        </h1>
        <p class="text-gray-400 mb-6">
            Berikut ringkasan aktivitas Anda di sistem perpustakaan.
        </p>

        @if (Auth::user()->role === 'admin')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div
                    class="p-5 bg-neutral rounded-xl border border-orange-500/30 shadow-lg hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <x-lucide-library-big class="w-10 h-10 text-orange-400" />
                        <span class="text-gray-400 text-sm font-semibold">Total Buku</span>
                    </div>
                    <h2 class="text-3xl font-bold mt-2 text-black">{{ $totalBuku ?? 0 }}</h2>
                </div>

                <div
                    class="p-5 bg-neutral rounded-xl border border-orange-500/30 shadow-lg hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <x-lucide-users class="w-10 h-10 text-orange-400" />
                        <span class="text-gray-400 text-sm font-semibold">Total Pengunjung</span>
                    </div>
                    <h2 class="text-3xl font-bold mt-2 text-black">{{ $totalPengunjung ?? 0 }}</h2>
                </div>

                <div
                    class="p-5 bg-neutral rounded-xl border border-orange-500/30 shadow-lg hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <x-lucide-calendar class="w-10 h-10 text-orange-400" />
                        <span class="text-gray-400 text-sm font-semibold">Total Kunjungan</span>
                    </div>
                    <h2 class="text-3xl font-bold mt-2 text-black">{{ $totalKunjungan ?? 0 }}</h2>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div
                    class="p-5 bg-neutral rounded-xl border border-orange-500/30 shadow-lg hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <x-lucide-library-big class="w-10 h-10 text-orange-400" />
                        <span class="text-gray-400 text-sm font-semibold">Buku Tersedia</span>
                    </div>
                    <h2 class="text-3xl font-bold mt-2 text-black">{{ $totalBuku ?? 0 }}</h2>
                </div>

                <div
                    class="p-5 bg-neutral rounded-xl border border-orange-500/30 shadow-lg hover:shadow-xl transition">
                    <div class="flex items-center justify-between">
                        <x-lucide-calendar class="w-10 h-10 text-orange-400" />
                        <span class="text-gray-400 text-sm font-semibold">Kunjungan Anda</span>
                    </div>
                    <h2 class="text-3xl font-bold mt-2 text-black">{{ $kunjunganUser ?? 0 }}</h2>
                </div>
            </div>
        @endif
    </div>
</x-dashboard.main>
