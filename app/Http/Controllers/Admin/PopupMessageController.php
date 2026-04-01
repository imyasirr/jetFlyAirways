<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PopupMessage;
use App\Support\PublicImageStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PopupMessageController extends Controller
{
    public function index(): View
    {
        $popups = PopupMessage::query()->orderByDesc('id')->paginate(20);

        return view('admin.popup-messages.index', compact('popups'));
    }

    public function create(): View
    {
        return view('admin.popup-messages.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $path = PublicImageStorage::storeUpload($request->file('image_file'), 'popups');
        if ($path !== null) {
            $data['image'] = $path;
        }
        PopupMessage::create($data);

        return redirect()->route('admin.popup-messages.index')->with('status', 'Popup created.');
    }

    public function edit(PopupMessage $popup_message): View
    {
        return view('admin.popup-messages.edit', ['popup' => $popup_message]);
    }

    public function update(Request $request, PopupMessage $popup_message): RedirectResponse
    {
        $data = $this->validated($request);
        if ($request->hasFile('image_file')) {
            $path = PublicImageStorage::storeUpload($request->file('image_file'), 'popups', $popup_message->image);
            if ($path !== null) {
                $data['image'] = $path;
            }
        }
        $popup_message->update($data);

        return redirect()->route('admin.popup-messages.index')->with('status', 'Popup updated.');
    }

    public function destroy(PopupMessage $popup_message): RedirectResponse
    {
        PublicImageStorage::deleteIfExists($popup_message->image);
        $popup_message->delete();

        return redirect()->route('admin.popup-messages.index')->with('status', 'Popup deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:200'],
            'message' => ['nullable', 'string', 'max:5000'],
            'image_file' => ['nullable', 'image', 'mimes:jpeg,png,webp,gif', 'max:10240'],
            'button_text' => ['nullable', 'string', 'max:80'],
            'redirect_link' => ['nullable', 'string', 'max:500'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date', 'after_or_equal:start_date'],
            'is_active' => ['boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        unset($data['image_file']);

        return $data;
    }
}
