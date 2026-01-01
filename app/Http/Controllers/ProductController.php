<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Armazena um novo produto.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('photo')) {
            $imagePath = $request->file('photo')->store('products', 'public');
        }

        Product::create([
            'name' => $validatedData['name'],
            'category_id' => $validatedData['category_id'],
            'image_path' => $imagePath,
            'status' => 'inactive', // Default status
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Produto cadastrado com sucesso!');
    }

    /**
     * Atualiza um produto existente.
     */
    public function update(Request $request, Product $product)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
        ]);

        $product->update($validatedData);

        return redirect()->route('admin.dashboard')->with('success', 'Produto atualizado com sucesso!');
    }

    /**
     * Remove um produto.
     */
    public function destroy(Product $product)
    {
        // Opcional: deletar a imagem do armazenamento se existir
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        
        $product->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Produto removido com sucesso!');
    }
}

