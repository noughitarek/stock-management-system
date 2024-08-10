<?php

namespace App\Http\Controllers;

use App\Models\Rubrique;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreRubriqueRequest;
use App\Http\Requests\UpdateRubriqueRequest;

class RubriqueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rubriques = Rubrique::with(['createdBy', 'updatedBy', 'deletedBy'])
        ->whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->orderBy('id', 'desc')
        ->paginate(25)
        ->onEachSide(2);

        return view('pages.rubriques.index')
        ->with('rubriques', $rubriques);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRubriqueRequest $request)
    {
        $rubrique = Rubrique::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);
        return redirect()
        ->route('rubriques')
        ->with('success', 'Rubrique créé avec succès');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRubriqueRequest $request, Rubrique $rubrique)
    {
        $rubrique->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'updated_by' => Auth::id(),
        ]);
        return redirect()
        ->route('rubriques')
        ->with('success', 'Rubrique édité avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rubrique $rubrique)
    {
        $rubrique->update([
            'deleted_by' => Auth::id(),
            'deleted_at' => now(),
        ]);
        foreach($rubrique->products as $product){
            $product->update([
                'deleted_by' => Auth::id(),
                'deleted_at' => now(),
            ]);
        }
        return redirect()
        ->route('rubriques')
        ->with('success', 'Rubrique supprimé avec succès');
    }
}
