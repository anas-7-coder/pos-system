{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            @yield('content')

        </div>
    </body>
</html> --}}


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>POS System</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body>

    <div class="app-layout">
        {{-- <aside class="app-sidebar">
            Sidebar content
        </aside> --}}
        
        @include('layouts.navigation')

        <main class="app-main">
            <header class="app-topbar">
                <div class="topbar-title">
                    @yield('title', 'Dashboard')
                </div>

                <div>
                    {{ auth()->user()->name }}
                </div>
            </header>

            <div class="app-content">
                {{-- @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif --}}

                @yield('content')
            </div>
        </main>
    </div>

    {{-- <script>
        document.addEventListener('DOMContentLoaded', function () {

            const sidebar = document.querySelector('.app-sidebar');

            if (!sidebar) {
                return;
            }

            const storageKey = 'pos_sidebar_scroll';

            // Restore previous sidebar position
            const savedPosition = sessionStorage.getItem(storageKey);

            if (savedPosition !== null) {
                requestAnimationFrame(function () {
                    sidebar.scrollTop = parseInt(savedPosition, 10);
                });
            }

            // Save position before leaving the page
            window.addEventListener('beforeunload', function () {
                sessionStorage.setItem(
                    storageKey,
                    sidebar.scrollTop
                );
            });

        });
    </script> --}}

    <script>
        const sidebarKey = 'pos_sidebar_scroll';

        document.addEventListener('DOMContentLoaded', function () {
            const sidebar = document.querySelector('.app-sidebar');

            if (!sidebar) return;

            const navigation = performance.getEntriesByType('navigation')[0];

            if (navigation && navigation.type === 'back_forward') {
                // Browser Back/Forward → go to top
                sidebar.scrollTop = 0;
                sessionStorage.removeItem(sidebarKey);
            } else {
                // Normal page navigation → restore previous position
                const savedPosition = sessionStorage.getItem(sidebarKey);

                if (savedPosition !== null) {
                    sidebar.scrollTop = parseInt(savedPosition, 10);
                }
            }

            // Save position when clicking a sidebar link
            sidebar.addEventListener('click', function (e) {
                const link = e.target.closest('a');

                if (link) {
                    sessionStorage.setItem(
                        sidebarKey,
                        sidebar.scrollTop
                    );
                }
            });
        });
    </script>

</body>


{{-- <body>

    @include('layouts.navigation')

    <main>
        @yield('content')
    </main>

</body>
</html> --}}