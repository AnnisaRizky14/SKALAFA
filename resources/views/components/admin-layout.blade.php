<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SKALAFA') }} - Admin Panel</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('storage/unib-logo.png') }}" type="image/x-icon">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/admin/dashboard.js'])

    {{-- Chart.js loaded via Vite --}}

</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-primary-600 shadow-lg border-b border-primary-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <!-- Logo -->
                        <div class="flex-shrink-0 flex items-center">
                            <img src="{{ asset('storage/unib-logo.png') }}" alt="Logo UNIB" class="h-10 w-10">
                            <div class="ml-3">
                                <div class="text-lg font-bold text-white">SKALAFA</div>
                                <div class="text-xs text-white">
                                    @if(auth()->user()->isFacultyAdmin())
                                        Admin {{ auth()->user()->faculty->short_name }}
                                    @else
                                        Admin Panel
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Links -->
                        <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                            <a href="{{ route('admin.dashboard') }}"
                               class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none {{ request()->routeIs('admin.dashboard') ? 'border-white text-white' : 'border-transparent text-white hover:text-primary-200 hover:border-primary-200' }}">
                                Dashboard
                            </a>
                            <a href="{{ route('admin.faculties.index') }}"
                               class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none {{ request()->routeIs('admin.faculties.*') ? 'border-white text-white' : 'border-transparent text-white hover:text-primary-200 hover:border-primary-200' }}">
                                Fakultas
                            </a>
                            <a href="{{ route('admin.questionnaires.index') }}"
                               class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none {{ request()->routeIs('admin.questionnaires.*') ? 'border-white text-white' : 'border-transparent text-white hover:text-primary-200 hover:border-primary-200' }}">
                                Kuisioner
                            </a>
                            <a href="{{ route('admin.complaints.index') }}"
                            class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none {{ request()->routeIs('admin.complaints.*') ? 'border-white text-white' : 'border-transparent text-white hover:text-primary-200 hover:border-primary-200' }}">
                                Pengaduan
                            </a>
                            <a href="{{ route('admin.responses.index') }}"
                            class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none {{ request()->routeIs('admin.responses.*') ? 'border-white text-white' : 'border-transparent text-white hover:text-primary-200 hover:border-primary-200' }}">
                                Respon
                            </a>
                            @if(auth()->user()->isSuperAdmin())
                            <a href="{{ route('admin.users.index') }}"
                            class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none {{ request()->routeIs('admin.users.*') ? 'border-white text-white' : 'border-transparent text-white hover:text-primary-200 hover:border-primary-200' }}">
                                Kelola User
                            </a>
                            @endif
                        </div>
                    </div>

                    <!-- Right Side Navigation -->
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <!-- Notification Dropdown -->
                        @include('components.notification-dropdown')

                        <!-- Spacer -->
                        <div class="ml-4"></div>

                        <!-- Settings Dropdown -->
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-primary-700 hover:text-primary-200 focus:outline-none transition ease-in-out duration-150">
                                    <div>{{ Auth::user()->name }}</div>
                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                <x-dropdown-link :href="route('profile.edit')">
                                    <svg class="w-4 h-4 mr-2 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4z"></path>
                                        <path d="M6 20v-2a4 4 0 014-4h4a4 4 0 014 4v2"></path>
                                    </svg>
                                    Profil
                                </x-dropdown-link>

                                <!-- Authentication -->
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf

                                    <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                    this.closest('form').submit();">
                                        <svg class="w-4 h-4 mr-2 inline-block" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M17 16l4-4m0 0l-4-4m4 4H7"></path>
                                            <path d="M7 16v-1a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Keluar
                                    </x-dropdown-link>
                                </form>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    <!-- Mobile menu button and notification icon -->
                    <div class="-mr-2 flex items-center sm:hidden space-x-4">
                        <!-- Notification Dropdown Icon next to Hamburger -->
                        <div class="flex items-center">
                            @include('components.notification-dropdown')
                        </div>
                        <button id="mobile-menu-button" class="inline-flex items-center justify-center p-2 rounded-md text-white hover:text-primary-200 hover:bg-primary-700 focus:outline-none focus:bg-primary-700 focus:text-primary-200 transition duration-150 ease-in-out">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Mobile Navigation Menu -->
                <div id="mobile-menu" class="hidden sm:hidden bg-primary-700 border-t border-primary-800">
                    <div class="px-2 pt-2 pb-3 space-y-1">
                        <a href="{{ route('admin.dashboard') }}"
                           class="block px-3 py-2 text-base font-medium {{ request()->routeIs('admin.dashboard') ? 'bg-primary-800 text-white' : 'text-primary-200 hover:bg-primary-800 hover:text-white' }} transition duration-150 ease-in-out">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.faculties.index') }}"
                           class="block px-3 py-2 text-base font-medium {{ request()->routeIs('admin.faculties.*') ? 'bg-primary-800 text-white' : 'text-primary-200 hover:bg-primary-800 hover:text-white' }} transition duration-150 ease-in-out">
                            Fakultas
                        </a>
                        <a href="{{ route('admin.questionnaires.index') }}"
                           class="block px-3 py-2 text-base font-medium {{ request()->routeIs('admin.questionnaires.*') ? 'bg-primary-800 text-white' : 'text-primary-200 hover:bg-primary-800 hover:text-white' }} transition duration-150 ease-in-out">
                            Kuisioner
                        </a>
                        <a href="{{ route('admin.complaints.index') }}"
                           class="block px-3 py-2 text-base font-medium {{ request()->routeIs('admin.complaints.*') ? 'bg-primary-800 text-white' : 'text-primary-200 hover:bg-primary-800 hover:text-white' }} transition duration-150 ease-in-out">
                            Pengaduan
                        </a>
                        <a href="{{ route('admin.responses.index') }}"
                           class="block px-3 py-2 text-base font-medium {{ request()->routeIs('admin.responses.*') ? 'bg-primary-800 text-white' : 'text-primary-200 hover:bg-primary-800 hover:text-white' }} transition duration-150 ease-in-out">
                            Respon
                        </a>
                        @if(auth()->user()->isSuperAdmin())
                        <a href="{{ route('admin.users.index') }}"
                           class="block px-3 py-2 text-base font-medium {{ request()->routeIs('admin.users.*') ? 'bg-primary-800 text-white' : 'text-primary-200 hover:bg-primary-800 hover:text-white' }} transition duration-150 ease-in-out">
                            Kelola User
                        </a>
                        @endif
                        <!-- Notification Dropdown for mobile menu -->
                        <!--<div class="border-t border-primary-800 mt-2 px-3 py-2">
                            @include('components.notification-dropdown')
                        </div>-->
                    </div>

                    <!-- Mobile User Menu -->
                    <div class="pt-4 pb-3 border-t border-primary-800">
                        <div class="px-2">
                            <div class="flex items-center px-3">
                                <div class="text-base font-medium text-white">{{ Auth::user()->name }}</div>
                            </div>
                            <div class="mt-3 space-y-1">
                                <a href="{{ route('profile.edit') }}"
                                   class="block px-3 py-2 text-base font-medium text-primary-200 hover:bg-primary-800 hover:text-white transition duration-150 ease-in-out">
                                    Profil
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="block w-full text-left px-3 py-2 text-base font-medium text-primary-200 hover:bg-primary-800 hover:text-white transition duration-150 ease-in-out">
                                        Keluar
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
            {{ $slot }}
        </main>
    </div>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center">
            <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-primary-600 mx-auto mb-4"></div>
            <p class="text-gray-600">Memuat...</p>
        </div>
    </div>

    <!-- Scripts -->
    <script>
        // CSRF Token Setup
        window.csrf_token = '{{ csrf_token() }}';

        // Toast Notification Function
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');

            const bgColor = type === 'success' ? 'bg-success-500' :
                           type === 'error' ? 'bg-danger-500' :
                           type === 'warning' ? 'bg-warning-500' : 'bg-primary-500';

            toast.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
            toast.innerHTML = `
                <div class="flex items-center justify-between">
                    <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round" stroke-width="2">
                    <path d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;

            container.appendChild(toast);

            // Animate in
            setTimeout(() => {
                toast.classList.remove('translate-x-full');
            }, 100);

            // Auto remove
            setTimeout(() => {
                toast.classList.add('translate-x-full');
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }

        // Loading Overlay Functions
        function showLoading() {
            document.getElementById('loading-overlay').classList.remove('hidden');
            document.getElementById('loading-overlay').classList.add('flex');
        }

        function hideLoading() {
            document.getElementById('loading-overlay').classList.add('hidden');
            document.getElementById('loading-overlay').classList.remove('flex');
        }

        // Mobile Menu Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener('click', function() {
                    mobileMenu.classList.toggle('hidden');
                });

                // Close menu when clicking outside
                document.addEventListener('click', function(event) {
                    if (!mobileMenuButton.contains(event.target) && !mobileMenu.contains(event.target)) {
                        mobileMenu.classList.add('hidden');
                    }
                });
            }
        });
    </script>

    <!-- Show flash messages as toasts -->
    @php
        $toastJs = '';
        if (session('success')) {
            $toastJs .= 'showToast("' . addslashes(session('success')) . '", "success");';
        }
        if (session('error')) {
            $toastJs .= 'showToast("' . addslashes(session('error')) . '", "error");';
        }
        if (session('warning')) {
            $toastJs .= 'showToast("' . addslashes(session('warning')) . '", "warning");';
        }
        if (session('info')) {
            $toastJs .= 'showToast("' . addslashes(session('info')) . '", "info");';
        }
    @endphp

    @if($toastJs)
        <script>
            {!! $toastJs !!}
        </script>
    @endif
</body>
</html>
