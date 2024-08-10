<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Http\Controllers\Controller;
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
        ->paginate(25)
        ->onEachSide(2);

        return view('pages.suppliers.index')
        ->with('suppliers', $suppliers);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {
        $supplier = Supplier::create([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'description' => $request->input('description'),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);
        return redirect()
        ->route('suppliers')
        ->with('success', 'Foucnisseur créé avec succès');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $supplier->update([
            'name' => $request->input('name'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'description' => $request->input('description'),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);
        return redirect()
        ->route('suppliers')
        ->with('success', 'Foucnisseur édité avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplier->update([
            'deleted_by' => Auth::id(),
            'deleted_at' => now(),
        ]);
        return redirect()
        ->route('suppliers')
        ->with('success', 'Fournisseur supprimé avec succès');
    }
}
