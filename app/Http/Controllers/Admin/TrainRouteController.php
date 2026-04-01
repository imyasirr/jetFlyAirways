<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTrainRouteRequest;
use App\Http\Requests\UpdateTrainRouteRequest;
use App\Models\TrainRoute;

class TrainRouteController extends Controller
{
    public function index()
    {
        $routes = TrainRoute::latest()->paginate(15);

        return view('admin.train-routes.index', compact('routes'));
    }

    public function create()
    {
        return view('admin.train-routes.create');
    }

    public function store(StoreTrainRouteRequest $request)
    {
        TrainRoute::create($request->validated() + ['is_active' => $request->boolean('is_active')]);

        return redirect()->route('admin.train-routes.index')->with('status', 'Train route created successfully.');
    }

    public function show(TrainRoute $train_route)
    {
        return view('admin.train-routes.show', ['route' => $train_route]);
    }

    public function edit(TrainRoute $train_route)
    {
        return view('admin.train-routes.edit', ['route' => $train_route]);
    }

    public function update(UpdateTrainRouteRequest $request, TrainRoute $train_route)
    {
        $train_route->update($request->validated() + ['is_active' => $request->boolean('is_active')]);

        return redirect()->route('admin.train-routes.index')->with('status', 'Train route updated successfully.');
    }

    public function destroy(TrainRoute $train_route)
    {
        $train_route->delete();

        return redirect()->route('admin.train-routes.index')->with('status', 'Train route deleted successfully.');
    }
}
