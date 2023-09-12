@extends('layouts.app')

@section('title', 'Lista de Productos')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Seleccionar todos los botones de incremento y decremento
        const increaseButtons = document.querySelectorAll('.increase-btn');
        const decreaseButtons = document.querySelectorAll('.decrease-btn');

        // Agregar un evento de clic a cada botón de incremento
        increaseButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const input = button.parentElement.querySelector('input[type="number"]');
                const max = parseInt(input.getAttribute('max')) || 100; // Valor máximo predeterminado de 10
                const currentValue = parseInt(input.value);

                if (currentValue < max) {
                    input.value = currentValue + 1;
                }
            });
        });

        // Agregar un evento de clic a cada botón de decremento
        decreaseButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const input = button.parentElement.querySelector('input[type="number"]');
                const min = parseInt(input.getAttribute('min')) || 0; // Valor mínimo predeterminado de 0
                const currentValue = parseInt(input.value);

                if (currentValue > min) {
                    input.value = currentValue - 1;
                }
            });
        });
    });
</script>

@section('content')
<h2>Lista de Productos</h2>

<div class="container-fluid">
    <div>
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Stock</th>
                    <th>Precio</th>
                    <th>Categoría</th>
                    <th class="w-25">Cantidad</th>
                    <th>Aplicar Venta</th> <!-- Agrega la clase w-25 para ajustar el ancho -->
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                <tr>
                    <td>{{ $product->id }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->stock }}</td>
                    <td>${{ number_format($product->price, 2) }}</td>
                    <td>{{ $product->category->name }}</td>
                    <td>
                        <form action="{{ route('product.purchase', $product->id) }}" method="POST">
                            @csrf
                            <div class="btn-group" >
                                <button class="btn btn-sm btn-outline-secondary decrease-btn" type="button">-</button>
                                <input type="number" name="quantity" value="1" min="1" class="form-control form-control-sm my-custom-input">
                                <button class="btn btn-sm btn-outline-secondary increase-btn" type="button">+</button>
                                <span></span>
                                <th><button type="submit" class="btn btn-sm btn-outline-secondary">Vender</button></th>
                            </div>
                        </form>                        

                    </td>

                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
