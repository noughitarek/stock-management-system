<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Rubrique;
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
        ->get()->toArray();
        
        return Inertia::render('Rubriques/Index', [
            'rubriques' => $rubriques,
            'from' => 1,
            'to' => count($rubriques),
            'total' => count($rubriques),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Rubriques/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRubriqueRequest $request)
    {
        $rubrique = Rubrique::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'created_by' => Auth::user()->id,
        ]);
        if ($rubrique) {
            return redirect()->route('rubriques.index')->with('success', 'Rubrique created successfully.');
        } else {
            return redirect()->back()->with('error', 'Rubrique could not be created.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Rubrique $rubrique)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Rubrique $rubrique)
    {
        return Inertia::render('Rubriques/Edit', ['rubrique' => $rubrique]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRubriqueRequest $request, Rubrique $rubrique)
    {
        $rubrique->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'updated_by' => Auth::user()->id,
        ]);
    
        if ($rubrique->wasChanged()) {
            return redirect()->route('rubriques.index')->with('success', 'Rubrique edited successfully.');
        } else {
            return redirect()->back()->with('error', 'Rubrique could not be edited.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Rubrique $rubrique)
    {
        $rubrique->update([
            'deleted_by' => Auth::user()->id,
            'deleted_at' => now(),
        ]);
    
        if ($rubrique->wasChanged()) {
            return redirect()->route('rubriques.index')->with('success', 'Rubrique deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Rubrique could not be deleted.');
        }
    }
}
