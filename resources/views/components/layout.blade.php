<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($title) ? $title . ' - ' . config('app.name') : config('app.name') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen font-sans antialiased bg-base-200">

    {{-- NAVBAR mobile only --}}
    <x-nav sticky class="lg:hidden">
        <x-slot:brand>
            <x-app-brand />
        </x-slot:brand>
        <x-slot:actions>
            <label for="main-drawer" class="lg:hidden me-3">
                <x-icon name="o-bars-3" class="cursor-pointer" />
            </label>
        </x-slot:actions>
    </x-nav>

    {{-- MAIN --}}
    <x-main>
        @if ($user = auth()->user())
            {{-- SIDEBAR --}}
            <x-slot:sidebar drawer="main-drawer" collapsible class="bg-base-100 lg:bg-inherit">

                {{-- BRAND --}}
                <x-app-brand class="px-5 pt-4" />

                {{-- MENU --}}
                <x-menu activate-by-route>

                    {{-- User --}}

                    <x-menu-separator />

                    <x-list-item :item="$user" value="name" sub-value="email" no-separator no-hover
                        class="-mx-2 !-my-2 rounded">




                        <x-slot:actions>
                            <x-dropdown>

                                <x-menu-item title="Theme" icon="o-swatch" @click.stop="setTheme()" />
                                <x-menu-item title="Logout" icon="o-power" @click.prevent="fetchLogout()" />
                            </x-dropdown>

                        </x-slot:actions>
                    </x-list-item>

                    <x-menu-separator />


                    <x-menu-item title="DASHBOARD" icon="s-home" link="/home" />
                    <x-menu-item title="NASABAH" icon="s-user-group" link="/nasabah" />


                    <x-menu-sub title="LAPORAN" icon="o-document-text">
                        <x-menu-item title="Transaksi" icon="o-clipboard" link="{{ route('laporan.transaksi') }}" />
                    </x-menu-sub>
                    <x-menu-sub title="TRANSAKSI" icon="o-credit-card">
                        <x-menu-item title="Setor Tunai" icon="o-arrow-down-circle"
                            link="{{ route('transaksi.setor') }}" />
                        <x-menu-item title="Tarik Tunai" icon="o-arrow-up-circle"
                            link="{{ route('transaksi.tarik') }}" />
                    </x-menu-sub>

                    <x-menu-sub title="WHATSAPP" icon="o-chat-bubble-oval-left">
                        <x-menu-item title="API" icon="o-server" link="{{ route('whatsapp') }}" />
                        <x-menu-item title="PESAN" icon="o-chat-bubble-bottom-center-text"
                            link="{{ route('whatsapp.pesan') }}" />

                    </x-menu-sub>

                    <x-menu-item title="Setting" icon="s-cog-8-tooth" link="/setting" />



                </x-menu>
            </x-slot:sidebar>
        @endif

        {{-- The `$slot` goes here --}}
        <x-slot:content>
            {{ $slot }}
        </x-slot:content>
    </x-main>

    {{--  TOAST area --}}
    <x-toast />


    <script data-navigate-once>
        const themes = ['pastel', 'light', 'dark']; // Daftar tema
        let currentThemeIndex = 0; // Indeks tema saat ini

        function setTheme(theme) {
            currentThemeIndex = (currentThemeIndex + 1) % themes.length; // Memutar indeks
            const nextTheme = themes[currentThemeIndex];
            document.documentElement.setAttribute('data-theme', nextTheme);
            localStorage.setItem("theme", nextTheme);
        }

        function fetchLogout() {
            fetch('{{ route('logout') }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
            }).then(() => {
                window.location.href = '/'; // redirect setelah logout
            });
        }
    </script>


</body>

</html>
