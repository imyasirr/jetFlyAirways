<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\SavedTraveller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SavedTravellerController extends Controller
{
    public function index(Request $request): View
    {
        $savedTravellers = $request->user()
            ->savedTravellers()
            ->orderBy('full_name')
            ->paginate(10)
            ->withQueryString();

        return view('account.saved-travellers.index', compact('savedTravellers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $request->user()->savedTravellers()->create($data);

        return back()->with('status', 'Traveller saved.');
    }

    public function update(Request $request, SavedTraveller $savedTraveller): RedirectResponse
    {
        abort_unless($savedTraveller->user_id === $request->user()->id, 403);

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email'],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $savedTraveller->update($data);

        return back()->with('status', 'Traveller updated.');
    }

    public function destroy(Request $request, SavedTraveller $savedTraveller): RedirectResponse
    {
        abort_unless($savedTraveller->user_id === $request->user()->id, 403);
        $savedTraveller->delete();

        return back()->with('status', 'Traveller removed.');
    }
}

