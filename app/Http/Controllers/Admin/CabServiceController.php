<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCabServiceRequest;
use App\Http\Requests\UpdateCabServiceRequest;
use App\Models\CabService;

class CabServiceController extends Controller
{
    public function index()
    {
        $services = CabService::latest()->paginate(15);

        return view('admin.cab-services.index', compact('services'));
    }

    public function create()
    {
        return view('admin.cab-services.create');
    }

    public function store(StoreCabServiceRequest $request)
    {
        CabService::create($request->validated() + ['is_active' => $request->boolean('is_active')]);

        return redirect()->route('admin.cab-services.index')->with('status', 'Cab service created successfully.');
    }

    public function show(CabService $cab_service)
    {
        return view('admin.cab-services.show', ['service' => $cab_service]);
    }

    public function edit(CabService $cab_service)
    {
        return view('admin.cab-services.edit', ['service' => $cab_service]);
    }

    public function update(UpdateCabServiceRequest $request, CabService $cab_service)
    {
        $cab_service->update($request->validated() + ['is_active' => $request->boolean('is_active')]);

        return redirect()->route('admin.cab-services.index')->with('status', 'Cab service updated successfully.');
    }

    public function destroy(CabService $cab_service)
    {
        $cab_service->delete();

        return redirect()->route('admin.cab-services.index')->with('status', 'Cab service deleted successfully.');
    }
}
