<!doctype html>
<html lang="es">

<head>
    <!-- Metadatos -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Choco Bania') }}</title>

    <!-- Fuentes y estilos -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="app">
        @if (Session::has('error'))
            <div class="alert alert-danger">
                {{ Session::get('error') }}
            </div>
        @endif
        <!-- Barra de navegación -->
        <nav class="navbar navbar-expand-md navbar-dark shadow-sm fixed-top">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <h1><a href="/">Choco Bania</a></h1>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Elementos del lado izquierdo de la barra de navegación -->
                    <ul class="navbar-nav me-auto">
                    </ul>
                    <!-- Botones para abrir los modales -->
                    <ul class="navbar-nav me-auto">
                        <li>
                            <!-- Botón para abrir el modal de agregar stock -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modalAgregarStock">
                                Agregar Stock
                            </button>
                        </li>
                        <li>
                            <!-- Botón para abrir el modal de generar informe -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#modalGenerarInforme">
                                Generar Informe
                            </button>
                        </li>
                    </ul>

                    <!-- Elementos del lado derecho de la barra de navegación -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Autenticación -->
                        @auth
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('products.crud') }}">Productos</a>
                            <a class="btn btn-sm btn-outline-secondary" href="{{ route('categories.crud') }}">Categorias</a>
                        @endauth
                        <!-- Enlaces de autenticación (Login, Registro, Logout) -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <!-- Dropdown para el usuario autenticado -->
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <!-- Enlace para Logout con formulario POST -->
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                    <!-- Formulario para Logout -->
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <!-- Contenido principal -->
        <div class="container-lg mt-md-5" style="overflow-y: auto;">
            @yield('content')
        </div>
    </div>

    <!-- Modal para agregar stock -->
    <div class="modal fade" id="modalAgregarStock" tabindex="-1" aria-labelledby="modalAgregarStockLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAgregarStockLabel">Agregar Stock</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulario para agregar stock -->
                    <form action="{{ route('products.addStock') }}" method="POST">
                        @csrf
                        <!-- Lista de productos con campos de cantidad -->
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre del Producto</th>
                                    <th>Stock Actual</th>
                                    <th>Cantidad a Agregar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>{{ $product->id }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->stock }}</td>
                                        <td>
                                            <!-- Campo de cantidad a agregar -->
                                            <input type="number" name="stock[]" class="form-control" value="0"
                                                min="0" placeholder="0"
                                                onfocus="if (this.value === '0') this.value = ''"
                                                onblur="if (this.value === '') this.value = '0'">
                                            <!-- Campo oculto para el ID del producto -->
                                            <input type="hidden" name="product_ids[]" value="{{ $product->id }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <!-- Botón para agregar stock -->
                        <button type="submit" class="btn btn-primary">Agregar Stock</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal para generar informe -->
    <div class="modal fade" id="modalGenerarInforme" tabindex="-1" aria-labelledby="modalGenerarInformeLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalGenerarInformeLabel">Generar Informe</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Formulario para generar informe -->
                    <form action="{{ route('products.report') }}" method="POST">
                        @csrf
                        <!-- Campo de fecha -->
                        <div class="mb-3">
                            <label for="fecha" class="form-label">Fecha:</label>
                            <input type="date" class="form-control" id="fecha" name="fecha">
                        </div>
                        <!-- Campo de producto (selección) -->
                        <div class="mb-3">
                            <label for="producto" class="form-label">Producto:</label>
                            <select class="form-control" id="producto" name="producto">
                                <option value="">Todos los productos</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Campo de categoría (selección) -->
                        <div class="mb-3">
                            <label for="categoria" class="form-label">Categoría:</label>
                            <select class="form-control" id="categoria" name="categoria">
                                <option value="">Todas las categorías</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <!-- Botón para generar el informe -->
                        <button type="submit" class="btn btn-primary">Generar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>

</html>
