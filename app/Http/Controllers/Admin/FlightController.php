<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFlightRequest;
use App\Http\Requests\UpdateFlightRequest;
use App\Models\Flight;

class FlightController extends Controller
{
    public function index()
    {
        $flights = Flight::latest()->paginate(15);
        return view('admin.flights.index', compact('flights'));
    }

    public function create()
    {
        return view('admin.flights.create');
    }

    public function store(StoreFlightRequest $request)
    {
        Flight::create($request->validated() + ['is_active' => $request->boolean('is_active')]);
        return redirect()->route('admin.flights.index')->with('status', 'Flight created successfully.');
    }

    public function show(Flight $flight)
    {
        return view('admin.flights.show', compact('flight'));
    }

    public function edit(Flight $flight)
    {
        return view('admin.flights.edit', compact('flight'));
    }

    public function update(UpdateFlightRequest $request, Flight $flight)
    {
        $flight->update($request->validated() + ['is_active' => $request->boolean('is_active')]);
        return redirect()->route('admin.flights.index')->with('status', 'Flight updated successfully.');
    }

    public function destroy(Flight $flight)
    {
        $flight->delete();
        return redirect()->route('admin.flights.index')->with('status', 'Flight deleted successfully.');
    }
}
