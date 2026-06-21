<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Support\PublicImageStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TestimonialController extends Controller
{
    public function index(): View
    {
        $testimonials = Testimonial::query()->orderByDesc('id')->paginate(20);

        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create(): View
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $path = PublicImageStorage::storeUpload($request->file('photo_file'), 'testimonials');
        if ($path !== null) {
            $data['photo'] = $path;
        }
        Testimonial::create($data);

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimonial created.');
    }

    public function edit(Testimonial $testimonial): View
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial): RedirectResponse
    {
        $data = $this->validated($request);

        if ($request->boolean('clear_photo')) {
            PublicImageStorage::deleteIfExists($testimonial->photo);
            $data['photo'] = null;
        } elseif ($request->hasFile('photo_file')) {
            $path = PublicImageStorage::storeUpload($request->file('photo_file'), 'testimonials', $testimonial->photo);
            if ($path !== null) {
                $data['photo'] = $path;
            }
        }

        unset($data['clear_photo']);
        $testimonial->update($data);

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimonial updated.');
    }

    public function destroy(Testimonial $testimonial): RedirectResponse
    {
        PublicImageStorage::deleteIfExists($testimonial->photo);
        $testimonial->delete();

        return redirect()->route('admin.testimonials.index')->with('status', 'Testimonial deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'designation' => ['nullable', 'string', 'max:120'],
            'photo_file' => ['nullable', 'image', 'mimes:jpeg,png,webp,gif', 'max:4096'],
            'clear_photo' => ['nullable', 'boolean'],
            'review' => ['required', 'string', 'max:2000'],
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'is_active' => ['boolean'],
        ]);
        $data['is_active'] = $request->boolean('is_active');
        unset($data['photo_file']);

        return $data;
    }
}
