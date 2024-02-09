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
        <h1>Informe de Ventas</h1>
        @if (count($orders) > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>ID del Pedido</th>
                        <th>Fecha</th>
                        <th>Producto</th>
                        <th>Categoria</th>
                        <th>Cantidad</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $totalDelReporte = 0; // Inicializa la variable para el total del informe ?>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->date }}</td>
                            <td>{{ $order->product->name }}</td>
                            <td>{{ $order->product->category->name }}</td>
                            <td>{{ $order->quantity }}</td>
                            <td>${{ number_format($order->total, 2) }}</td>
                            <?php $totalDelReporte += $order->total; // Agrega el total del pedido al total del informe ?>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <h2><!-- Muestra el total del informe al final de la tabla -->
                        Total del Informe de Venta: ${{ number_format($totalDelReporte, 2) }}</h2>
                </tfoot>
            </table>
            
        @else
            <p>No se encontraron ventas que coincidan con los criterios seleccionados.</p>
        @endif
    </div>
@endsection
