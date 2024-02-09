<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use Carbon\Carbon; // Importa la clase Carbon
use Illuminate\Http\Request;

class ProductController extends Controller
{
    // Método para mostrar la vista CRUD de productos
    public function crud()
    {
        $products = Product::all();
        $categories = Category::all();
        return view('products.crud', compact('products', 'categories'));
    }

    // Método para mostrar la vista de índice de productos
    public function index()
    {
        $products = Product::all();
        $categories = Category::all();
        return view('products.index', compact('products', 'categories'));
    }

    // Método para procesar la compra de productos
    public function purchase(Request $request)
    {
        // Obtener todos los datos de cantidad del formulario
        $quantities = $request->input('quantity');

        // Recorrer las cantidades y crear órdenes si la cantidad es mayor a 0
        foreach ($quantities as $productId => $quantity) {
            // Validar que la cantidad sea un número entero positivo
            if (!is_numeric($quantity) || $quantity <= 0) {
                continue; // Saltar a la próxima iteración si la cantidad no es válida
            }

            // Obtener el producto por su ID
            $product = Product::find($productId);

            // Verificar si hay suficiente stock
            if ($product && $product->stock >= $quantity) {
                // Calcular el total
                $total = $product->price * $quantity;

                // Obtener la fecha y hora actual
                $currentDateTime = Carbon::now(); // Utiliza Carbon para obtener la fecha y hora actual

                // Crear una nueva orden
                Order::create([
                    'date' => $currentDateTime,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'total' => $total,
                ]);

                // Actualizar el stock del producto
                $product->decrement('stock', $quantity);
            } else {
                // Redireccionar a la página anterior con un mensaje de error
                return redirect()->back()->with('error', 'No hay suficiente stock disponible para uno o más productos.');
            }
        }

        // Redireccionar a la página anterior con un mensaje de éxito
        return redirect()->back()->with('success', 'Venta realizada con éxito.');
    }

    // Método para actualizar un producto
    public function update(Request $request, $id)
    {
        // Validación de datos
        $request->validate([
            'nombre' => 'required|string|max:255',
            'stock' => 'required|integer',
            'precio' => 'required|numeric',
            'categoria' => 'required|exists:categories,id', // Asegúrate de que la categoría exista
        ]);

        // Obtén el producto que deseas actualizar
        $product = Product::findOrFail($id);

        // Actualiza los campos del producto
        $product->name = $request->input('nombre');
        $product->stock = $request->input('stock');
        $product->price = $request->input('precio');
        $product->category_id = $request->input('categoria');

        // Guarda los cambios en la base de datos
        $product->save();

        // Redirige de vuelta a la vista de productos con un mensaje de éxito
        return redirect()->route('products.crud')->with('success', 'Producto actualizado correctamente');
    }

    // Método para almacenar un nuevo producto
    public function store(Request $request)
    {
        // Valida los datos del formulario
        $validatedData = $request->validate([
            'nombre' => 'required|max:255',
            'stock' => 'required|integer',
            'precio' => 'required|numeric',
            'categoria' => 'required|exists:categories,id',
        ]);

        // Crea un nuevo producto
        $product = new Product();
        $product->name = $validatedData['nombre'];
        $product->stock = $validatedData['stock'];
        $product->price = $validatedData['precio'];
        $product->category_id = $validatedData['categoria'];
        $product->save();

        // Redirige a la página de detalles del producto o a donde desees.
        return redirect()->route('products.crud', $product->id)->with('success', 'Producto creado correctamente');
    }

    // Método para eliminar un producto
    public function destroy(Product $product)
    {
        // Elimina el producto
        $product->delete();

        // Redirige a la lista de productos con un mensaje de éxito.
        return redirect()->route('products.crud')->with('success', 'Producto eliminado correctamente');
    }

    // Método para agregar stock a productos
    public function addStock(Request $request)
    {
        // Valida los datos del formulario
        $request->validate([
            'stock' => 'required|array', // Debe ser un array
            'stock.*' => 'integer|min:0', // Cada elemento del array debe ser un número entero no negativo
            'product_ids' => 'required|array', // Debe ser un array
            'product_ids.*' => 'exists:products,id', // Cada elemento del array debe ser un ID válido de producto
        ]);

        // Itera a través de los datos enviados y actualiza el stock
        foreach ($request->product_ids as $key => $product_id) {
            $product = Product::find($product_id);

            // Actualiza el stock del producto sumando la cantidad enviada
            $product->stock += $request->stock[$key];
            $product->save();
        }

        // Redirige de vuelta con un mensaje de éxito
        return redirect()->back()->with('success', 'Stock actualizado correctamente.');
    }

    // Método para generar un informe de ventas
    public function report(Request $request)
    {
        // Obtener los parámetros del formulario
        $fecha = $request->input('fecha');
        $producto = $request->input('producto');
        $categoria = $request->input('categoria');

        // Construir la consulta para obtener los datos de los pedidos
        $query = Order::query();

        if ($fecha) {
            $query->where('date', $fecha);
        }

        if ($producto) {
            $query->whereHas('product', function ($subquery) use ($producto) {
                $subquery->where('product_id', 'like', "%$producto%");
            });
        }

        if ($categoria) {
            $query->whereHas('product.category', function ($subquery) use ($categoria) {
                $subquery->where('category_id', 'like', "%$categoria%");
            });
        }

        // Obtener los resultados de la consulta
        $orders = $query->get();

        // Obtenemos todos los productos para mostrar en el formulario
        $products = Product::all();
        $categories = Category::all();

        // Redirige a la vista de informe de ventas con los resultados y un mensaje de éxito.
        return view('products.report', compact('orders', 'products', 'categories'))->with('success', 'Informe generado correctamente');
    }
}
