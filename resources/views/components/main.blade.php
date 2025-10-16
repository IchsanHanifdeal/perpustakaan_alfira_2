<!DOCTYPE html>
<html lang="id" data-theme="synthwave">

<head>
    @include('components.head')
    <style>
        .toast {
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .toast-show {
            opacity: 1;
        }

        @keyframes spin-reverse {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(-360deg);
            }
        }

        .reverse-spin {
            animation: spin-reverse 1s linear infinite;
        }
    </style>
    {!! ToastMagic::styles() !!}
</head>

<body class="flex flex-col mx-auto min-h-screen font-sans text-gray-800 bg-[#F6F8D5]">
    <!-- Splash Screen -->
    <div id="splash-screen"
        class="fixed inset-0 z-[9999] flex items-center justify-center min-h-screen bg-gradient-to-br from-[#d8e9f0] via-[#e3f2ed] to-[#f3f9f7] transition-opacity duration-500 opacity-100">

        <div
            class="relative flex flex-col items-center justify-center p-10 bg-white/80 rounded-[2rem] shadow-2xl border border-blue-200 backdrop-blur-lg transition-transform duration-700 scale-100">

            <!-- Loading Animation -->
            <div class="relative w-24 h-24 mb-6">
                <div
                    class="absolute inset-0 rounded-full border-[10px] border-t-[#205781] border-r-transparent border-b-[#205781] border-l-transparent animate-spin">
                </div>
                <div
                    class="absolute inset-2 rounded-full border-[10px] border-t-[#4F959D] border-r-transparent border-b-[#4F959D] border-l-transparent animate-spin reverse-spin">
                </div>
            </div>

            <!-- Branding -->
            <h1 class="text-4xl font-extrabold text-[#205781] drop-shadow-sm tracking-widest text-center">PERPUSTAKAAN <br> MTS Negeri 2 KOTA DUMAI</h1>
        </div>
    </div>

    {{-- @if (!str_contains(request()->path(), 'dashboard') && !request()->is('login') && !request()->is('register'))
        @include('components.navbar')
    @endif --}}

    <main class="{{ $class ?? 'p-4' }}" role="main">
        {{ $slot }}

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                var splashScreen = document.getElementById('splash-screen');
                document.body.classList.add('overflow-hidden');

                splashScreen.classList.add('show');

                window.addEventListener('load', function() {
                    splashScreen.classList.remove('show');
                    splashScreen.classList.add('opacity-0', 'pointer-events-none');
                    document.body.classList.remove('overflow-hidden');
                });
            });

            window.addEventListener('beforeunload', function() {
                var splashScreen = document.getElementById('splash-screen');
                splashScreen.classList.add('show');
                document.body.classList.add('overflow-hidden');
            });

            function closeAllModals(event) {
                const form = event.target.closest("form");

                if (form) {
                    form.submit();

                    const modals = document.querySelectorAll("dialog.modal");

                    modals.forEach((modal) => {
                        if (modal.hasAttribute("open")) {
                            modal.close();
                        }
                    });
                }
            }
        </script>
{{--
        @if (!str_contains(request()->path(), 'dashboard') && !request()->is('login') && !request()->is('register'))
            @include('components.footer')
        @endif --}}
    </main>

    {!! ToastMagic::scripts() !!}
</body>

</html>
