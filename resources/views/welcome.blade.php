<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mi Sitio Web</title>
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <header>
        <!-- Agrega aquí el contenido del encabezado común a todas las páginas -->
        <h1>Choco Bania</h1>
    </header>

    <main>
    <h2>Lista de Productos</h2>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Stock</th>
                <th>Precio</th>
                <th>Categoría</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>{{ $product->price }}</td>
                    <td>{{ $product->category->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </main>

    <footer>
        <!-- Agrega aquí el contenido del pie de página común a todas las páginas -->
        <p>&copy; {{ date('Y') }} Choco Bania. Todos los derechos reservados.</p>
    </footer>

    <!-- Agrega aquí tus enlaces JavaScript y otros elementos comunes al final del cuerpo -->
</body>
</html>
