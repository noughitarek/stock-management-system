<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreServiceRequest;
use App\Http\Requests\UpdateServiceRequest;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $services = Service::with(['createdBy', 'updatedBy', 'deletedBy'])
        ->whereNull('deleted_at')
        ->whereNull('deleted_by')
        ->orderBy('id', 'desc')
        ->paginate(25)
        ->onEachSide(2);

        return view('pages.services.index')
        ->with('services', $services);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        $service = Service::create([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'responsible_name' => $request->input('responsible_name'),
            'responsible_phone' => $request->input('responsible_phone'),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);
        return redirect()
        ->route('services')
        ->with('success', 'Service créé avec succès');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        $service->update([
            'name' => $request->input('name'),
            'description' => $request->input('description'),
            'responsible_name' => $request->input('responsible_name'),
            'responsible_phone' => $request->input('responsible_phone'),
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);
        return redirect()
        ->route('services')
        ->with('success', 'Service édité avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->update([
            'deleted_by' => Auth::id(),
            'deleted_at' => now(),
        ]);
        return redirect()
        ->route('services')
        ->with('success', 'Service supprimé avec succès');
    }
}
