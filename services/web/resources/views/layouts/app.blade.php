<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestion du Patrimoine')</title>
    <style>
        body { font-family: sans-serif; }
        .container { max-width: 400px; margin: 100px auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 8px; }
        button { width: 100%; padding: 10px; background: #2563eb; color: white; border: none; }
    </style>
</head>
<body>
    @if(session()->has('user'))
        <nav style="padding: 20px; background: #f3f4f6;">
            <a href="{{ route('dashboard') }}">Dashboard</a>
            <a href="{{ route('logout') }}">Deconnexion</a>
        </nav>
    @endif

    <main>
        @yield('content')
    </main>
</body>
</html>
