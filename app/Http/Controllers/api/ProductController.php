<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductForm;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        // Retrieve all products
        $products = Product::all();
        return response()->json(['data' => $products]);
    }

    public function show($id)
    {
        // Retrieve a specific product by ID
        $product = Product::findOrFail($id);
        return response()->json(['data' => $product]);
    }

    public function store(StoreProductForm $request)
    {

        $productData = [
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price
        ];

        // Store image
        if ($request->has('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('products');
            $productData['image'] = $imagePath;
        }

        // Create a new product
        $product = Product::create($productData);

        return response()->json(['data' => $product], 201);
    }

    public function update(Request $request, $id)
    {
        // Update a specific product by ID
        $product = Product::findOrFail($id);
        $product->update($request->all());
        return response()->json(['data' => $product]);
    }

    public function destroy($id)
    {
        // Delete a specific product by ID
        $product = Product::findOrFail($id);
        $product->delete();
        return response()->json(['message' => 'Product deleted']);
    }
}
