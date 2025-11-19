@php
    $user = auth()->user();
@endphp

<div class="drawer-side border-r z-20">
    <label for="aside-dashboard" class="drawer-overlay" aria-label="close sidebar"></label>
    <ul
        class="menu p-4 w-64 lg:w-72 min-h-full bg-[#6E8CFB]
        [&>li>a]:gap-4 [&>li]:my-1.5 [&>li]:text-[14.3px]
        [&>li]:font-medium [&>li]:text-opacity-80 [&>li]:text-base
        [&>_*_svg]:stroke-[1.5] [&>_*_svg]:size-[23px] [&>.label]:mt-6">

        <div class="pb-4 border-b border-gray-300 text-center">
            @include('components.brands', ['class' => 'btn btn-ghost text-2xl'])
        </div>

        <span class="label text-xs font-extrabold opacity-50">GENERAL</span>
        <li>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'menu-active' : '' }}">
                <x-lucide-layout-dashboard />
                Dashboard
            </a>
        </li>

        {{-- ADMIN PANEL --}}
        @if (Auth::user()->role == 'admin')
            <span class="label text-xs font-extrabold opacity-50 mt-4">ADMIN PANEL</span>
            <li>
                <a href="{{ route('buku') }}" class="{{ request()->is('dashboard/buku*') ? 'menu-active' : '' }}">
                    <x-lucide-library-big /> Data Buku
                </a>
            </li>
            <li>
                <a href="{{ route('pengunjung') }}"
                    class="{{ request()->is('dashboard/pengunjung*') ? 'menu-active' : '' }}">
                    <x-lucide-users /> Data Pengunjung
                </a>
            </li>
            <li>
                <a href="{{ route('kunjungan') }}"
                    class="{{ request()->is('dashboard/kunjungan*') ? 'menu-active' : '' }}">
                    <x-lucide-calendar /> Data Kunjungan
                </a>
            </li>
            <li>
                <a href="{{ route('peminjaman') }}"
                    class="{{ request()->is('dashboard/peminjaman*') ? 'menu-active' : '' }}">
                    <x-lucide-calendar /> Data Peminjaman
                </a>
            </li>
            <li>
                <a href="{{ route('pengembalian') }}"
                    class="{{ request()->is('dashboard/pengembalian*') ? 'menu-active' : '' }}">
                    <x-lucide-calendar /> Data Pengembalian
                </a>
            </li>
        @endif
        @if (Auth::user()->role == 'user')
            <span class="label text-xs font-extrabold opacity-50 mt-4">USER MENU</span>
            <li>
                <a href="{{ route('buku') }}" class="{{ request()->is('dashboard/buku*') ? 'menu-active' : '' }}">
                    <x-lucide-library-big /> Data Buku
                </a>
            </li>
            <li>
                <a href="{{ route('kunjungan') }}"
                    class="{{ request()->is('dashboard/kunjungan*') ? 'menu-active' : '' }}">
                    <x-lucide-calendar /> Kunjungan
                </a>
            </li>
            <li>
                <a href="{{ route('peminjaman') }}"
                    class="{{ request()->is('dashboard/peminjaman*') ? 'menu-active' : '' }}">
                    <x-lucide-calendar /> Riwayat Peminjaman
                </a>
            </li>
        @endif

        <span class="label text-xs font-extrabold opacity-50 mt-4">ADVANCE</span>
        {{-- <li>
            <a href="{{ route('profile.edit') }}"
                class="{{ request()->is('dashboard/profile*') ? 'menu-active' : '' }}">
                <x-lucide-user-cog />
                Profil
            </a>
        </li> --}}
        <li>
            <a class="cursor-pointer" onclick="document.getElementById('logout_modal').showModal();">
                <x-lucide-log-out />
                Logout
            </a>
        </li>
    </ul>
</div>

{{-- Logout Modal --}}
<dialog id="logout_modal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box rounded-xl shadow-lg">
        <h3 class="text-xl font-bold text-gray-800">Konfirmasi Logout</h3>
        <p class="mt-2 text-gray-600">Apakah Anda yakin ingin keluar?</p>
        <div class="modal-action mt-4">
            <button type="button" onclick="document.getElementById('logout_modal').close()"
                class="btn bg-gray-200 text-gray-700 hover:bg-gray-300 border-0">
                Batal
            </button>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-primary bg-red-600 hover:bg-red-700 text-white border-0"
                    onclick="closeAllModals(event)">Logout</button>
            </form>
        </div>
    </div>
</dialog>
