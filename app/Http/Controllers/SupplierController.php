<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Supplier;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::with(['createdBy', 'updatedBy', 'deletedBy'])
        ->whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->orderBy('id', 'desc')
        ->get()->toArray();
        
        return Inertia::render('Suppliers/Index', [
            'suppliers' => $suppliers,
            'from' => 1,
            'to' => count($suppliers),
            'total' => count($suppliers),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Suppliers/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {
        $supplier = Supplier::create([
            "name" => $request->input('name'),
            "phone" => $request->input('phone'),
            "address" => $request->input('address'),
            "description" => $request->input('description'),
            "created_by"  => Auth::user()->id,
        ]);
        if($supplier){
            return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
        }else{
            return redirect()->back()->with('error', 'Supplier could not be created.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        return Inertia::render('Suppliers/Edit', ["supplier" => $supplier]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $supplier->update([
            "name" => $request->input('name'),
            "phone" => $request->input('phone'),
            "address" => $request->input('address'),
            "description" => $request->input('description'),
            "updated_by"  => Auth::user()->id,
        ]);
        if($supplier->wasChanged()){
            return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
        }else{
            return redirect()->back()->with('error', 'Supplier could not be updated.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->update([
            "deleted_at"  => now(),
            "deleted_by"  => Auth::user()->id,
        ]);
        if($supplier->wasChanged()){
            return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
        }else{
            return redirect()->back()->with('error', 'Supplier could not be deleted.');
        }
    }
}
