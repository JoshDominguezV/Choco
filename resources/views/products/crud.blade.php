@extends('layouts.app')

@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-success mt-3" role="alert">
                {{ session('success') }}
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger mt-3" role="alert">
                {{ session('error') }}
            </div>
        @endif
        <h1>Productos</h1>

        <!-- Botón para abrir el modal de crear producto -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalCrearProducto">Crear Producto</button>

        <!-- Tabla para mostrar los productos -->
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Stock</th>
                    <th>Precio</th>
                    <th>Categoría</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Iterar a través de los productos y mostrarlos en la tabla -->
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>${{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>
                            <!-- Formulario para abrir el modal de edición -->
                            <form style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalEditarProducto{{ $product->id }}">Editar</button>
                            </form>
                            <!-- Formulario para eliminar el producto -->
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Eliminar</button>
                            </form>
                        </td>
                    </tr>

                    <!-- Modal para editar producto -->
                    <div class="modal fade" id="modalEditarProducto{{ $product->id }}" tabindex="-1" aria-labelledby="modalEditarProducto{{ $product->id }}Label" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="modalEditarProducto{{ $product->id }}Label">Editar Producto</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <!-- Formulario para editar el producto -->
                                    <form action="{{ route('products.update', $product->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <!-- Campos del formulario de edición -->
                                        <div class="form-group">
                                            <label for="nombre">Nombre</label>
                                            <input type="text" name="nombre" id="nombre" class="form-control" value="{{ $product->name }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="stock">Stock</label>
                                            <input type="text" name="stock" id="stock" class="form-control" value="{{ $product->stock }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="precio">Precio</label>
                                            <input type="text" name="precio" id="precio" class="form-control" value="{{ $product->price }}">
                                        </div>

                                        <div class="form-group">
                                            <label for="categoria">Categoría</label>
                                            <select name="categoria" id="categoria" class="form-control">
                                                <!-- Iterar a través de las categorías para seleccionar la categoría actual -->
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" @if ($category->id === $product->category_id) selected @endif>{{ $category->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Guardar cambios</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>

        <!-- Modal para crear producto -->
        <div class="modal fade" id="modalCrearProducto" tabindex="-1" aria-labelledby="modalCrearProductoLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCrearProductoLabel">Crear Producto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <!-- Formulario para crear un nuevo producto -->
                        <form action="{{ route('products.store') }}" method="POST">
                            @csrf

                            <!-- Campos del formulario de creación -->
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <input type="text" name="nombre" id="nombre" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="stock">Stock</label>
                                <input type="text" name="stock" id="stock" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="precio">Precio</label>
                                <input type="text" name="precio" id="precio" class="form-control">
                            </div>

                            <div class="form-group">
                                <label for="categoria">Categoría</label>
                                <select name="categoria" id="categoria" class="form-control">
                                    <!-- Iterar a través de las categorías para seleccionar la categoría predeterminada -->
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <button type="submit" class="btn btn-primary">Crear</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
