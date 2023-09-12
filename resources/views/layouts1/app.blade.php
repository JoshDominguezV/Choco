<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Choco Bania')</title>
    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-md navbar-dark fixed-top">
        <div class="container">
            <h1><a href="/">Choco Bania</a></h1>
            <button class="btn btn-sm btn-outline-secondary" type="button">Agregar Stock</button>
            </div>
            <div>
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('products.crud') }}">Lista de Productos</a>
                <a class="btn btn-sm btn-outline-secondary" href="{{ route('categories.crud') }}">Lista de Categorias</a>
            </div>
            <div>
                <button class="btn btn-sm btn-outline-secondary" type="button">Reporte</button>
            </div>
    </nav>
    
    <div class="container-lg mt-md-5">
        @yield('content')
    </div>
    

    <!-- Agrega aquí tus enlaces JavaScript y otros elementos comunes al final del cuerpo -->
</body>
</html>
