<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFlightRequest;
use App\Http\Requests\UpdateFlightRequest;
use App\Models\Flight;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function index(Request $request)
    {
        $query = Flight::query();

        if ($search = trim((string) $request->get('q'))) {
            $query->where(function ($w) use ($search) {
                $w->where('airline', 'like', "%{$search}%")
                    ->orWhere('flight_number', 'like', "%{$search}%")
                    ->orWhere('from_city', 'like', "%{$search}%")
                    ->orWhere('to_city', 'like', "%{$search}%");
            });
        }

        if ($request->get('status') === 'active') {
            $query->where('is_active', true);
        } elseif ($request->get('status') === 'inactive') {
            $query->where('is_active', false);
        }

        $flights = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Flight::count(),
            'active' => Flight::where('is_active', true)->count(),
            'inactive' => Flight::where('is_active', false)->count(),
            'upcoming' => Flight::where('departure_at', '>', now())->count(),
        ];

        return view('admin.flights.index', compact('flights', 'stats'));
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
