<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'SKALAFA'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('storage/unib-logo.png') }}" type="image/x-icon">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Chart.js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chart.js/3.9.1/chart.min.js"></script>

    <!-- Custom Styles -->
    <style>
        :root {
            --primary-color: #003f7f;
            --secondary-color: #ff6b35;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --error-color: #ef4444;
        }
        
        .bg-primary { background-color: var(--primary-color); }
        .bg-secondary { background-color: var(--secondary-color); }
        .text-primary { color: var(--primary-color); }
        .text-secondary { color: var(--secondary-color); }
        .border-primary { border-color: var(--primary-color); }
        .border-secondary { border-color: var(--secondary-color); }
        
        .btn-primary {
            background-color: var(--primary-color);
            color: white;
            border: 1px solid var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: #002a5c;
            border-color: #002a5c;
        }
        
        .btn-secondary {
            background-color: var(--secondary-color);
            color: white;
            border: 1px solid var(--secondary-color);
        }
        
        .btn-secondary:hover {
            background-color: #e55a2b;
            border-color: #e55a2b;
        }
        
        .gradient-bg {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        
        .rating-star {
            color: #fbbf24;
            font-size: 1.25rem;
        }
        
        .loading-spinner {
            border: 2px solid #f3f3f3;
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .progress-bar {
            height: 8px;
            border-radius: 4px;
            background-color: #e5e7eb;
            overflow: hidden;
        }
        
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--primary-color), var(--secondary-color));
            transition: width 0.3s ease;
        }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
<div class="min-h-screen">
    @hasSection('header')
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                @yield('header')
            </div>
        </header>
    @endif

    <main class="py-0">
        @yield('content')
    </main>
</div>

    <!-- Toast Notification Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <!-- Loading Overlay -->
    <div id="loading-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white p-6 rounded-lg shadow-lg text-center">
            <div class="loading-spinner mx-auto mb-4"></div>
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
            
            const bgColor = type === 'success' ? 'bg-green-500' : 
                           type === 'error' ? 'bg-red-500' : 
                           type === 'warning' ? 'bg-yellow-500' : 'bg-blue-500';
            
            toast.className = `${bgColor} text-white px-6 py-4 rounded-lg shadow-lg transform transition-all duration-300 translate-x-full`;
            toast.innerHTML = `
                <div class="flex items-center justify-between">
                    <span>${message}</span>
                    <button onclick="this.parentElement.parentElement.remove()" class="ml-4 text-white hover:text-gray-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
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
        
        // Show flash messages as toasts
        /*
        @if(session('success'))
            showToast('{{ session('success') }}', 'success');
        @endif
        
        @if(session('error'))
            showToast('{{ session('error') }}', 'error');
        @endif
        
        @if(session('warning'))
            showToast('{{ session('warning') }}', 'warning');
        @endif
        
        @if(session('info'))
            showToast('{{ session('info') }}', 'info');
        @endif
        */
    </script>

    <div id="flash-messages"
        data-success="{{ session('success') }}"
        data-error="{{ session('error') }}"
        data-warning="{{ session('warning') }}"
        data-info="{{ session('info') }}">
    </div>

    <script>
    (function () {
        const el = document.getElementById('flash-messages');
        if (!el) return;

        ['success','error','warning','info'].forEach(type => {
            const msg = el.dataset[type];
            if (msg) showToast(msg, type); // pakai fungsi showToast yang sudah kamu punya
        });

        el.remove(); // optional
    })();
    </script>

    @stack('scripts')
</body>
</html>