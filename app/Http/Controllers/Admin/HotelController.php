<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHotelRequest;
use App\Http\Requests\UpdateHotelRequest;
use App\Models\Hotel;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::latest()->paginate(15);
        return view('admin.hotels.index', compact('hotels'));
    }

    public function create()
    {
        return view('admin.hotels.create');
    }

    public function store(StoreHotelRequest $request)
    {
        $data = $request->validated();
        $data['amenities'] = isset($data['amenities']) ? array_map('trim', explode(',', $data['amenities'])) : null;
        $data['is_active'] = $request->boolean('is_active');
        Hotel::create($data);

        return redirect()->route('admin.hotels.index')->with('status', 'Hotel created successfully.');
    }

    public function show(Hotel $hotel)
    {
        return view('admin.hotels.show', compact('hotel'));
    }

    public function edit(Hotel $hotel)
    {
        return view('admin.hotels.edit', compact('hotel'));
    }

    public function update(UpdateHotelRequest $request, Hotel $hotel)
    {
        $data = $request->validated();
        $data['amenities'] = isset($data['amenities']) ? array_map('trim', explode(',', $data['amenities'])) : null;
        $data['is_active'] = $request->boolean('is_active');
        $hotel->update($data);

        return redirect()->route('admin.hotels.index')->with('status', 'Hotel updated successfully.');
    }

    public function destroy(Hotel $hotel)
    {
        $hotel->delete();
        return redirect()->route('admin.hotels.index')->with('status', 'Hotel deleted successfully.');
    }
}
