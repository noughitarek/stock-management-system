<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Service;
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
        ->get()->toArray();
        
        return Inertia::render('Services/Index', [
            'services' => $services,
            'from' => 1,
            'to' => count($services),
            'total' => count($services),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Services/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        $service = Service::create([
            "name" => $request->input('name'),
            "description" => $request->input('description'),
            "responsible_name"  => $request->input('responsible_name'),
            "responsible_phone" => $request->input('responsible_phone'),
            "created_by" => Auth::user()->id,
        ]);
        if($service){
            return redirect()->route('services.index')->with('success', 'Service created successfully.');
        }else{
            return redirect()->back()->with('error', 'Service could not be created.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Service $service)
    {
        return Inertia::render('Services/Edit', ['service' => $service]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, Service $service)
    {
        $service->update([
            "name" => $request->input('name'),
            "description" => $request->input('description'),
            "responsible_name"  => $request->input('responsible_name'),
            "responsible_phone" => $request->input('responsible_phone'),
            "updated_by" => Auth::user()->id,
        ]);
        if($service->wasChanged()){
            return redirect()->route('services.index')->with('success', 'Service updated successfully.');
        }else{
            return redirect()->back()->with('error', 'Service could not be updated.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Service $service)
    {
        $service->update([
            "deleted_at" => now(),
            "deleted_by" => Auth::user()->id,
        ]);
        if($service->wasChanged()){
            return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
        }else{
            return redirect()->back()->with('error', 'Service could not be deleted.');
        }
    }
}
