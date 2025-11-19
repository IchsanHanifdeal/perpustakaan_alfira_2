<x-dashboard.main title="Dashboard">
    <div class="p-6 text-white">

        <div class="flex items-center justify-between mb-2">
            <div>
                <h1 id="greeting" class="text-2xl font-bold text-black">
                </h1>
                <p id="subgreeting" class="text-gray-500">Berikut ringkasan aktivitas perpustakaan hari ini.</p>
            </div>

            <div class="text-right">
                <div class="text-sm text-gray-500">Waktu Lokal</div>
                <div id="clock" class="text-lg font-mono font-semibold text-black">--:--:--</div>
            </div>
        </div>

        @if (Auth::user()->role === 'admin')
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-xl p-5 shadow border border-orange-200">
                    <div class="flex justify-between">
                        <x-lucide-library-big class="w-10 h-10 text-orange-500" />
                        <span class="text-sm text-gray-500">Total Buku</span>
                    </div>
                    <h2 class="text-3xl font-semibold text-black mt-2">{{ $totalBuku }}</h2>
                </div>

                <div class="bg-white rounded-xl p-5 shadow border border-orange-200">
                    <div class="flex justify-between">
                        <x-lucide-users class="w-10 h-10 text-orange-500" />
                        <span class="text-sm text-gray-500">Total Pengunjung</span>
                    </div>
                    <h2 class="text-3xl font-semibold text-black mt-2">{{ $totalPengunjung }}</h2>
                </div>

                <div class="bg-white rounded-xl p-5 shadow border border-orange-200">
                    <div class="flex justify-between">
                        <x-lucide-calendar class="w-10 h-10 text-orange-500" />
                        <span class="text-sm text-gray-500">Total Kunjungan</span>
                    </div>
                    <h2 class="text-3xl font-semibold text-black mt-2">{{ $totalKunjungan }}</h2>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow border border-orange-200 mb-8">
                <h2 class="text-xl font-bold mb-4 text-black">🏆 Ranking Pengunjung Teraktif</h2>

                <div class="overflow-x-auto">
                    <table class="table w-full text-black">
                        <thead class="bg-orange-100">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>NISN</th>
                                <th>Kelas</th>
                                <th>Total Kunjungan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rankingPengunjung as $i => $p)
                                <tr class="hover:bg-orange-50">
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $p->user->nama }}</td>
                                    <td>{{ $p->nisn }}</td>
                                    <td>{{ $p->kelas }}</td>
                                    <td class="font-bold text-orange-600">{{ $p->absensis_count }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if (Auth::user()->role !== 'admin')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-xl p-5 shadow border border-orange-200">
                    <div class="flex justify-between">
                        <x-lucide-library-big class="w-10 h-10 text-orange-500" />
                        <span class="text-sm text-gray-500">Buku Tersedia</span>
                    </div>
                    <h2 class="text-3xl font-semibold text-black mt-2">{{ $totalBuku }}</h2>
                </div>

                <div class="bg-white rounded-xl p-5 shadow border border-orange-200">
                    <div class="flex justify-between">
                        <x-lucide-calendar class="w-10 h-10 text-orange-500" />
                        <span class="text-sm text-gray-500">Kunjungan Anda</span>
                    </div>
                    <h2 class="text-3xl font-semibold text-black mt-2">{{ $kunjunganUser }}</h2>
                </div>
            </div>
        @endif

    </div>

    <script>
        (function() {
            const userName = @json(Auth::user()->nama ?? 'Pengguna');

            const greetingEl = document.getElementById('greeting');
            const subgreetingEl = document.getElementById('subgreeting');
            const clockEl = document.getElementById('clock');

            function getGreetingText(hour) {
                if (hour < 11) return 'Selamat Pagi';
                if (hour < 15) return 'Selamat Siang';
                if (hour < 18) return 'Selamat Sore';
                return 'Selamat Malam';
            }

            function tick() {
                const now = dayjs();
                const hour = now.hour();
                const minuteSecond = now.format('HH:mm:ss');

                if (clockEl) clockEl.textContent = minuteSecond;

                const greeting = getGreetingText(hour);
                if (greetingEl) greetingEl.textContent = `${greeting}, ${userName}! 👋`;
            }

            tick();
            setInterval(tick, 1000);
        })();
    </script>
</x-dashboard.main>
