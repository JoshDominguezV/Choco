<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Método para mostrar la vista de gestión de categorías
    public function crud()
    {
        // Obtener todas las categorías y productos
        $categories = Category::all();
        $products = Product::all();
    
        // Renderizar la vista 'categories.crud' y pasar las variables $products y $categories
        return view('categories.crud', compact('products', 'categories'));
    }

    // Método para actualizar una categoría
    public function update(Request $request, $id)
    {
        // Obtener todas las categorías y productos
        $products = Product::all();
        $categories = Category::all();

        // Validación de datos
        $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        // Obtén la categoría que deseas actualizar
        $category = Category::findOrFail($id);

        // Actualiza el nombre de la categoría
        $category->name = $request->input('nombre');

        // Guarda los cambios en la base de datos
        $category->save();

        // Redirige de vuelta a la vista de categorías con un mensaje de éxito
        return redirect()->route('categories.crud')->with('success', 'Categoría actualizada correctamente');
    }

    // Método para almacenar una nueva categoría
    public function store(Request $request)
    {
        // Obtener todas las categorías y productos
        $products = Product::all();
        $categories = Category::all();

        // Valida los datos del formulario
        $validatedData = $request->validate([
            'nombre' => 'required|max:255',
        ]);

        // Crea una nueva categoría
        $category = new Category();
        $category->name = $validatedData['nombre'];
        $category->save();

        // Redirige a la página de detalles de la categoría o a donde desees.
        return redirect()->route('categories.crud', $category->id);
    }

    // Método para eliminar una categoría
    public function destroy(Category $category)
    {
        // Obtener todas las categorías y productos
        $products = Product::all();
        $categories = Category::all();

        // Elimina la categoría
        $category->delete();

        // Redirige a la lista de categorías o a donde desees.
        return redirect()->route('categories.crud');
    }
}
