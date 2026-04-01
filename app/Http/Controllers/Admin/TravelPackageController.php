<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTravelPackageRequest;
use App\Http\Requests\UpdateTravelPackageRequest;
use App\Models\TravelPackage;

class TravelPackageController extends Controller
{
    public function index()
    {
        $packages = TravelPackage::latest()->paginate(15);

        return view('admin.travel-packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.travel-packages.create');
    }

    public function store(StoreTravelPackageRequest $request)
    {
        TravelPackage::create($request->validated() + ['is_published' => $request->boolean('is_published')]);

        return redirect()->route('admin.travel-packages.index')->with('status', 'Package created successfully.');
    }

    public function show(TravelPackage $travel_package)
    {
        return view('admin.travel-packages.show', ['package' => $travel_package]);
    }

    public function edit(TravelPackage $travel_package)
    {
        return view('admin.travel-packages.edit', ['package' => $travel_package]);
    }

    public function update(UpdateTravelPackageRequest $request, TravelPackage $travel_package)
    {
        $travel_package->update($request->validated() + ['is_published' => $request->boolean('is_published')]);

        return redirect()->route('admin.travel-packages.index')->with('status', 'Package updated successfully.');
    }

    public function destroy(TravelPackage $travel_package)
    {
        $travel_package->delete();

        return redirect()->route('admin.travel-packages.index')->with('status', 'Package deleted successfully.');
    }
}
