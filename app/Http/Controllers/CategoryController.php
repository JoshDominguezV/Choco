<?php


namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function crud()
    {
        $categories = Category::all();
    
        return view('categories.crud', compact('categories'));
    }

    public function update(Request $request, $id)
    {
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

    public function store(Request $request)
    {
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

    public function destroy(Category $category)
    {
        // Elimina la categoría
        $category->delete();

        // Redirige a la lista de categorías o a donde desees.
        return redirect()->route('categories.crud');
    }
}
