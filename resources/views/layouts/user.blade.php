<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'AsetData - User')</title>
    <!-- Tailwind via CDN for quick styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom theme and animations */
        body { background-color: #f8fafc; }
        .brand-gradient { 
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .card-soft {
            background: white;
            border: 1px solid rgba(0, 0, 0, 0.05);
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            transition: all 0.2s ease;
        }
        .card-soft:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transform: translateY(-1px);
        }
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slideDown {
            animation: slideDown 0.2s ease-out;
        }
    </style>
    @stack('head')
</head>
<body>
    <header class="brand-gradient text-white sticky top-0 z-50">
        <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-8">
                    <a href="{{ route('dashboard.user') }}" class="text-xl font-semibold hover:text-white/90 transition-colors duration-150">Panel Pengguna</a>
                </div>

                <div class="flex items-center gap-6">
                    <div class="flex items-center gap-3">
                        <span class="hidden sm:flex items-center gap-2 text-sm">
                            <div class="w-7 h-7 rounded-full overflow-hidden border-2 border-white/20">
                                <img 
                                    src="{{ auth()->user()->foto ? url('storage/' . auth()->user()->foto) : asset('default.png') }}" 
                                    alt="{{ auth()->user()->name }}"
                                    class="w-full h-full object-cover"
                                >
                            </div>
                            <span class="font-medium">{{ auth()->user()?->name ?? 'Guest' }}</span>
                        </span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="inline-flex items-center px-3.5 py-1.5 border border-white/20 rounded-lg text-sm text-white hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-white/20 transition-colors duration-150">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if (session('success'))
            <div class="mb-6 animate-slideDown">
                <div class="rounded-lg bg-green-50 p-4 text-sm text-green-600 flex items-start">
                    <svg class="w-5 h-5 mr-3 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 animate-slideDown">
                <div class="rounded-lg bg-red-50 p-4 text-sm text-red-600 flex items-start">
                    <svg class="w-5 h-5 mr-3 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="mt-12 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center text-sm text-gray-500">
                <p>&copy; {{ date('Y') }} AsetData. Seluruh hak cipta dilindungi.</p>
            </div>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>