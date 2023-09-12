<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    //
    public function crud()
    {
        $products = Product::all();
        $categories = Category::all();
    
        return view('products.crud', compact('products', 'categories'));
    }

    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }
    public function purchase(Request $request, Product $product)
{
    // Validar la solicitud
    $request->validate([
        'quantity' => 'required|integer|min:1',
    ]);

    // Verificar si hay suficiente stock
    if ($product->stock < $request->quantity) {
        return redirect()->back()->with('error', 'No hay suficiente stock disponible.');
    }

    // Calcular el total
    $total = $product->price * $request->quantity;

    // Crear un registro en la tabla de órdenes
    Order::create([
        'date' => now(),
        'product_id' => $product->id,
        'quantity' => $request->quantity,
        'total' => $total,
    ]);

    // Actualizar el stock del producto
    $product->decrement('stock', $request->quantity);

    return redirect()->back()->with('success', 'Compra realizada con éxito.');
}
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
    return redirect()->route('products.crud', $product->id);
}
public function destroy(Product $product)
{
    // Elimina el producto
    $product->delete();

    // Redirige a la lista de productos o a donde desees.
    return redirect()->route('products.crud');
}



}