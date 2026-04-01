<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBusRouteRequest;
use App\Http\Requests\UpdateBusRouteRequest;
use App\Models\BusRoute;

class BusRouteController extends Controller
{
    public function index()
    {
        $routes = BusRoute::latest()->paginate(15);

        return view('admin.bus-routes.index', compact('routes'));
    }

    public function create()
    {
        return view('admin.bus-routes.create');
    }

    public function store(StoreBusRouteRequest $request)
    {
        BusRoute::create($request->validated() + ['is_active' => $request->boolean('is_active')]);

        return redirect()->route('admin.bus-routes.index')->with('status', 'Bus route created successfully.');
    }

    public function show(BusRoute $bus_route)
    {
        return view('admin.bus-routes.show', ['route' => $bus_route]);
    }

    public function edit(BusRoute $bus_route)
    {
        return view('admin.bus-routes.edit', ['route' => $bus_route]);
    }

    public function update(UpdateBusRouteRequest $request, BusRoute $bus_route)
    {
        $bus_route->update($request->validated() + ['is_active' => $request->boolean('is_active')]);

        return redirect()->route('admin.bus-routes.index')->with('status', 'Bus route updated successfully.');
    }

    public function destroy(BusRoute $bus_route)
    {
        $bus_route->delete();

        return redirect()->route('admin.bus-routes.index')->with('status', 'Bus route deleted successfully.');
    }
}
