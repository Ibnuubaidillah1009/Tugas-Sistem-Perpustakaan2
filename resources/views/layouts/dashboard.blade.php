<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') - Sistem Perpustakaan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        },
                        secondary: {
                            50: '#f8fafc',
                            100: '#f1f5f9',
                            200: '#e2e8f0',
                            300: '#cbd5e1',
                            400: '#94a3b8',
                            500: '#64748b',
                            600: '#475569',
                            700: '#334155',
                            800: '#1e293b',
                            900: '#0f172a',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-gray-50">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="w-64 bg-gradient-to-b from-primary-800 to-primary-900 text-white shadow-lg">
            <div class="p-6">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-book-open text-2xl text-primary-200"></i>
                    <h1 class="text-xl font-bold">Perpustakaan</h1>
                </div>
            </div>
            
            <nav class="mt-8">
                <div class="px-6 py-2">
                    <p class="text-primary-200 text-xs uppercase tracking-wider font-semibold">Menu Utama</p>
                </div>
                
                @if(auth()->user()->isPerpustakawan())
                    <a href="{{ route('perpustakawan.dashboard') }}" class="flex items-center px-6 py-3 text-primary-100 hover:bg-primary-700 transition-colors {{ request()->routeIs('perpustakawan.dashboard') ? 'bg-primary-700 border-r-4 border-primary-300' : '' }}">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('perpustakawan.books.index') }}" class="flex items-center px-6 py-3 text-primary-100 hover:bg-primary-700 transition-colors {{ request()->routeIs('perpustakawan.books.*') ? 'bg-primary-700 border-r-4 border-primary-300' : '' }}">
                        <i class="fas fa-book mr-3"></i>
                        Kelola Buku
                    </a>
                    <a href="{{ route('perpustakawan.borrowings.index') }}" class="flex items-center px-6 py-3 text-primary-100 hover:bg-primary-700 transition-colors {{ request()->routeIs('perpustakawan.borrowings.*') ? 'bg-primary-700 border-r-4 border-primary-300' : '' }}">
                        <i class="fas fa-exchange-alt mr-3"></i>
                        Peminjaman
                    </a>
                    <a href="{{ route('perpustakawan.users.index') }}" class="flex items-center px-6 py-3 text-primary-100 hover:bg-primary-700 transition-colors {{ request()->routeIs('perpustakawan.users.*') ? 'bg-primary-700 border-r-4 border-primary-300' : '' }}">
                        <i class="fas fa-users mr-3"></i>
                        Kelola User
                    </a>
                @elseif(auth()->user()->isGuru())
                    <a href="{{ route('guru.dashboard') }}" class="flex items-center px-6 py-3 text-primary-100 hover:bg-primary-700 transition-colors {{ request()->routeIs('guru.dashboard') ? 'bg-primary-700 border-r-4 border-primary-300' : '' }}">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('guru.books.index') }}" class="flex items-center px-6 py-3 text-primary-100 hover:bg-primary-700 transition-colors {{ request()->routeIs('guru.books.*') ? 'bg-primary-700 border-r-4 border-primary-300' : '' }}">
                        <i class="fas fa-book mr-3"></i>
                        Katalog Buku
                    </a>
                    <a href="{{ route('guru.borrowings.index') }}" class="flex items-center px-6 py-3 text-primary-100 hover:bg-primary-700 transition-colors {{ request()->routeIs('guru.borrowings.*') ? 'bg-primary-700 border-r-4 border-primary-300' : '' }}">
                        <i class="fas fa-exchange-alt mr-3"></i>
                        Peminjaman Saya
                    </a>
                @else
                    <a href="{{ route('siswa.dashboard') }}" class="flex items-center px-6 py-3 text-primary-100 hover:bg-primary-700 transition-colors {{ request()->routeIs('siswa.dashboard') ? 'bg-primary-700 border-r-4 border-primary-300' : '' }}">
                        <i class="fas fa-tachometer-alt mr-3"></i>
                        Dashboard
                    </a>
                    <a href="{{ route('siswa.books.index') }}" class="flex items-center px-6 py-3 text-primary-100 hover:bg-primary-700 transition-colors {{ request()->routeIs('siswa.books.*') ? 'bg-primary-700 border-r-4 border-primary-300' : '' }}">
                        <i class="fas fa-book mr-3"></i>
                        Katalog Buku
                    </a>
                    <a href="{{ route('siswa.borrowings.index') }}" class="flex items-center px-6 py-3 text-primary-100 hover:bg-primary-700 transition-colors {{ request()->routeIs('siswa.borrowings.*') ? 'bg-primary-700 border-r-4 border-primary-300' : '' }}">
                        <i class="fas fa-exchange-alt mr-3"></i>
                        Peminjaman Saya
                    </a>
                @endif
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                        <p class="text-gray-600">@yield('page-description', 'Selamat datang di sistem perpustakaan')</p>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm font-medium text-gray-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 capitalize">{{ auth()->user()->role }}</p>
                        </div>
                        
                        <div class="relative">
                            <button class="flex items-center space-x-2 text-gray-600 hover:text-gray-800 focus:outline-none">
                                <i class="fas fa-user-circle text-2xl"></i>
                            </button>
                        </div>
                        
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center space-x-2 text-red-600 hover:text-red-800 focus:outline-none">
                                <i class="fas fa-sign-out-alt"></i>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6">
                @if(session('success'))
                    <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>