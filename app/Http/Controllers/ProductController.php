<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{

public function store(Request $request)
{
    $validatedData = $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            Rule::unique('products', 'name'),
        ],
        'category_id' => 'required|integer|exists:categories,id',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ], [
        'name.unique' => __('ui.product_exists'), // mensagem traduzida
    ]);

    $imagePath = null;
    if ($request->hasFile('photo')) {
        $imagePath = $request->file('photo')->store('products', 'public');
    }

    Product::create([
        'name' => $validatedData['name'],
        'category_id' => $validatedData['category_id'],
        'image_path' => $imagePath,
        'status' => 'inactive',
    ]);

    return redirect()->route('admin.dashboard')->with('success', __('ui.product_created_success'));
}

public function update(Request $request, Product $product)
{
    $validatedData = $request->validate([
        'name' => [
            'required',
            'string',
            'max:255',
            Rule::unique('products', 'name')->ignore($product->id),
        ],
        'category_id' => 'required|integer|exists:categories,id',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ], [
        'name.unique' => __('ui.product_exists'),
    ]);

    if ($request->hasFile('photo')) {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }
        $validatedData['image_path'] = $request->file('photo')->store('products', 'public');
    }

    $product->update($validatedData);

    return redirect()->route('admin.dashboard')->with('success', __('ui.product_updated_success'));
}


    public function destroy(Product $product)
    {
        if ($product->image_path) {
            Storage::disk('public')->delete($product->image_path);
        }

        $product->delete();

        return redirect()->route('admin.dashboard')
                         ->with('success', 'Produto removido com sucesso!');
    }
}


