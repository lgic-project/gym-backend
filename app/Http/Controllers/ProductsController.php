<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::paginate(20);
        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $sanitized = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $product = Product::create($sanitized);

        if ($request->hasFile('image')) {
            try {
                $product->addMedia($request->file('image'))->toMediaCollection();
            } catch (\Exception $e) {
                // Handle error if file upload fails
                return redirect()->back()->withErrors(['image' => 'Failed to upload image.']);
            }
        }

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Currently not implemented
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $sanitized = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $product->update($sanitized);

        if ($request->hasFile('image')) {
            try {
                $product->clearMediaCollection();
                $product->addMedia($request->file('image'))->toMediaCollection();
            } catch (\Exception $e) {
                // Handle error if file upload fails
                return redirect()->back()->withErrors(['image' => 'Failed to upload image.']);
            }
        }

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
        } catch (\Exception $e) {
            // Handle error if deletion fails
            return redirect()->back()->withErrors(['product' => 'Failed to delete product.']);
        }
        
        return redirect()->back()->with('success', 'Product deleted successfully.');
    }
}
