<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Product;
use App\Models\Rubrique;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with(['createdBy', 'updatedBy', 'deletedBy', 'rubrique'])
        ->whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->orderBy('id', 'desc')
        ->get()->toArray();
        
        return Inertia::render('Products/Index', [
            'products' => $products,
            'from' => 1,
            'to' => count($products),
            'total' => count($products),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $rubriques = Rubrique::with(['createdBy', 'updatedBy', 'deletedBy'])
        ->whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->orderBy('id', 'desc')
        ->get()->toArray();
        return Inertia::render('Products/Create', ['rubriques' => $rubriques]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $product = Product::create([
            'designation' => $request->input('designation'),
            'rubrique' => $request->input('rubrique'),
            'description' => $request->input('description'),
            'created_by' => Auth::user()->id, 
        ]);
        if ($product) {
            return redirect()->route('products.index')->with('success', 'Product created successfully.');
        } else {
            return redirect()->back()->with('error', 'Product could not be created.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $product = Product::with('rubrique')->find($product->id);
        $rubriques = Rubrique::with(['createdBy', 'updatedBy', 'deletedBy'])
        ->whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->orderBy('id', 'desc')
        ->get()->toArray();
        return Inertia::render('Products/Edit', ['rubriques' => $rubriques, 'product' => $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update([
            'designation' => $request->input('designation'),
            'rubrique' => $request->input('rubrique'),
            'description' => $request->input('description'),
            'updated_by' => Auth::user()->id,
        ]);

        if ($product->wasChanged()) {
            return redirect()->route('products.index')->with('success', 'Product edited successfully.');
        } else {
            return redirect()->back()->with('error', 'Product could not be edited.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        $product->update([
            'deleted_by' => Auth::user()->id,
            'deleted_at' => now(),
        ]);
    
        if ($product->wasChanged()) {
            return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Product could not be deleted.');
        }
    }
}
