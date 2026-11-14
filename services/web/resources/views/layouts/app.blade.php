<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestion du Patrimoine de l\'État')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 text-gray-900">
    @if(session()->has('user'))
        @include('partials.header')
    @endif

    <main class="@if(session()->has('user')) pt-16 @else min-h-screen flex items-center justify-center">
        @yield('content')
    </main>

    @if(!session()->has('user'))
        <footer class="fixed bottom-0 left-0 right-0 text-center py-4 text-sm text-gray-400">
            &copy; 2026 - R&eacute;publique Togolaise
        </footer>
    @endif

    @stack('scripts')
</body>
</html>
