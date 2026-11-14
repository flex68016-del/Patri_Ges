<header class="fixed top-0 left-0 right-0 bg-white border-b border-gray-200 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <div class="flex items-center">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <span class="ml-3 text-lg font-semibold text-gray-900">Patrimoine</span>
            </div>

            <div class="flex items-center space-x-4">
                <nav class="hidden md:flex space-x-6">
                    <a href="{{ route('dashboard') }}" class="text-gray-600 hover:text-blue-600 font-medium">Tableau de bord</a>
                    <a href="{{ route('biens.index') }}" class="text-gray-600 hover:text-blue-600 font-medium">Biens</a>
                    <a href="{{ route('maintenances.index') }}" class="text-gray-600 hover:text-blue-600 font-medium">Maintenances</a>
                    <a href="{{ route('inventaires.index') }}" class="text-gray-600 hover:text-blue-600 font-medium">Inventaires</a>
                </nav>

                <div class="relative">
                    <div class="flex items-center space-x-2 text-gray-600">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-sm font-medium text-blue-700">U</span>
                        </div>
                        <span class="hidden md:block text-sm font-medium">Utilisateur</span>
                    </div>

                    <form method="POST" action="{{ route('logout') }}" class="absolute right-0 mt-2 w-48">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                            Deconnexion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
