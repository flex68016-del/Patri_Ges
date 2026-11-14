<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestion du Patrimoine')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif; }
    </style>
</head>
<body class="bg-[#faf9f5]">
    @if(session()->has('user'))
        <div x-data="{ sidebarOpen: false }" class="flex h-screen">
            <!-- Sidebar -->
            <aside x-show="sidebarOpen" @click.away="sidebarOpen = false" x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
                x-transition:leave-end="-translate-x-full" class="fixed inset-y-0 left-0 z-50 w-64 bg-white shadow-md lg:static lg:inset-0 lg:translate-x-0">
                <div class="flex flex-col h-full">
                    <div class="flex items-center justify-center h-16 bg-[#047857]">
                        <h1 class="text-xl font-semibold text-white">Patrimoine</h1>
                    </div>
                    <nav class="flex-1 px-4 py-6 space-y-1">
                        <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-2 text-[#1f2937] hover:bg-[#f0fdf4] hover:text-[#047857] rounded-md transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                            </svg>
                            Dashboard
                        </a>
                        <a href="{{ route('biens.index') }}" class="flex items-center px-4 py-2 text-[#1f2937] hover:bg-[#f0fdf4] hover:text-[#047857] rounded-md transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Biens
                        </a>
                        <a href="{{ route('maintenances.index') }}" class="flex items-center px-4 py-2 text-[#1f2937] hover:bg-[#f0fdf4] hover:text-[#047857] rounded-md transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Maintenances
                        </a>
                        <a href="{{ route('inventaires.index') }}" class="flex items-center px-4 py-2 text-[#1f2937] hover:bg-[#f0fdf4] hover:text-[#047857] rounded-md transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                            </svg>
                            Inventaires
                        </a>
                    </nav>
                    <div class="p-4 border-t border-[#e5e7eb]">
                        <a href="{{ route('logout') }}" class="flex items-center px-4 py-2 text-[#6b7280] hover:bg-gray-100 rounded-md transition-colors duration-200">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Déconnexion
                        </a>
                    </div>
                </div>
            </aside>

            <!-- Main content -->
            <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
                <!-- Header -->
                <header class="flex items-center justify-between h-16 bg-white border-b border-[#e5e7eb] lg:hidden">
                    <button @click="sidebarOpen = true" class="p-4 text-[#6b7280] hover:text-[#1f2937]">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                    <h1 class="text-lg font-semibold text-[#1f2937]">Patrimoine</h1>
                    <div class="w-10"></div>
                </header>

                <!-- Page content -->
                <main class="flex-1 overflow-y-auto p-6">
                    @yield('content')
                </main>
            </div>
        </div>
    @else
        <main class="min-h-screen flex items-center justify-center">
            @yield('content')
        </main>
    @endif
</body>
</html>
