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
        <div class="text-end">
            <h2>
                <p>Total: <span id="total">$0.00</span></p>
            </h2>
        </div>
        <table class="table mt-4">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Stock</th>
                    <th>Precio</th>
                    <th>Categoría</th>
                    <th class="w-35">Cantidad</th>
                    <th>Sub-Total</th>
                </tr>
            </thead>
            <tbody>
                <form action="{{ route('product.purchase') }}" method="POST">
                    @csrf
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->stock }}</td>
                            <td>${{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-secondary decrease-btn" type="button">-</button>
                                    <input type="number" name="quantity[{{ $product->id }}]" value="0" min="0"
                                        class="form-control form-control-sm my-custom-input">
                                    <button class="btn btn-sm btn-outline-secondary increase-btn" type="button">+</button>
                                </div>
                            </td>
                            <td>
                                <span class="subtotal subtotal-{{ $product->id }}">$0.00</span>
                            </td>
                        </tr>
                    @endforeach
                    <button type="submit" class="btn btn-sm btn-outline-secondary">Vender</button>
                </form>
            </tbody>
        </table>
    </div>
    <script>
        // JavaScript para actualizar el subtotal
        document.addEventListener('DOMContentLoaded', function() {
            // Seleccionar todos los botones de incremento y decremento
            const increaseButtons = document.querySelectorAll('.increase-btn');
            const decreaseButtons = document.querySelectorAll('.decrease-btn');

            // Agregar un evento de clic a cada botón de incremento
            increaseButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const input = button.parentElement.querySelector('input[type="number"]');
                    const max = parseInt(input.getAttribute('max')) ||
                    100; // Valor máximo predeterminado de 100
                    const currentValue = parseInt(input.value);

                    if (currentValue < max) {
                        input.value = currentValue + 1;
                    }

                    updateSubtotal(input);
                    updateTotal();
                });
            });

            // Agregar un evento de clic a cada botón de decremento
            decreaseButtons.forEach(function(button) {
                button.addEventListener('click', function() {
                    const input = button.parentElement.querySelector('input[type="number"]');
                    const min = parseInt(input.getAttribute('min')) ||
                    0; // Valor mínimo predeterminado de 0
                    const currentValue = parseInt(input.value);

                    if (currentValue > min) {
                        input.value = currentValue - 1;
                    }

                    updateSubtotal(input);
                    updateTotal();
                });
            });

            // Calcular el subtotal inicial
            const initialInputs = document.querySelectorAll('.my-custom-input');
            initialInputs.forEach(function(input) {
                updateSubtotal(input);
            });

            // Función para actualizar el subtotal
            function updateSubtotal(input) {
                const row = input.closest('tr');
                const price = parseFloat(row.querySelector('td:nth-child(4)').textContent.replace('$', ''));
                const quantity = parseInt(input.value);
                const subtotal = price * quantity;
                row.querySelector('.subtotal').textContent = '$' + subtotal.toFixed(2);
            }

            // JavaScript para calcular y actualizar el total automáticamente
            function updateTotal() {
                const subtotalElements = document.querySelectorAll('.subtotal');
                let total = 0;

                subtotalElements.forEach(function(subtotalElement) {
                    const subtotalValue = parseFloat(subtotalElement.textContent.replace('$', ''));
                    total += subtotalValue;
                });

                document.querySelector('#total').textContent = '$' + total.toFixed(2);
            }

            // Agrega un evento input a los campos de cantidad para que el total se actualice automáticamente
            const quantityInputs = document.querySelectorAll('.my-custom-input');

            quantityInputs.forEach(function(input) {
                input.addEventListener('input', function() {
                    updateSubtotal(input);
                    updateTotal();
                });
            });
        });
    </script>
@endsection
