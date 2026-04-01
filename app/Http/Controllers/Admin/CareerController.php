<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Career;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CareerController extends Controller
{
    public function index(): View
    {
        $careers = Career::query()->orderByDesc('id')->paginate(20);

        return view('admin.careers.index', compact('careers'));
    }

    public function create(): View
    {
        return view('admin.careers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Career::create($this->validated($request));

        return redirect()->route('admin.careers.index')->with('status', 'Vacancy created.');
    }

    public function edit(Career $career): View
    {
        return view('admin.careers.edit', compact('career'));
    }

    public function update(Request $request, Career $career): RedirectResponse
    {
        $career->update($this->validated($request));

        return redirect()->route('admin.careers.index')->with('status', 'Vacancy updated.');
    }

    public function destroy(Career $career): RedirectResponse
    {
        $career->delete();

        return redirect()->route('admin.careers.index')->with('status', 'Vacancy deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request): array
    {
        $data = $request->validate([
            'job_title' => ['required', 'string', 'max:200'],
            'department' => ['nullable', 'string', 'max:120'],
            'location' => ['nullable', 'string', 'max:120'],
            'salary' => ['nullable', 'string', 'max:120'],
            'openings' => ['nullable', 'integer', 'min:1', 'max:9999'],
            'job_description' => ['nullable', 'string', 'max:20000'],
            'required_skills' => ['nullable', 'string', 'max:10000'],
            'apply_last_date' => ['nullable', 'date'],
            'is_hiring' => ['boolean'],
        ]);
        $data['is_hiring'] = $request->boolean('is_hiring');
        $data['openings'] = $data['openings'] ?? 1;

        return $data;
    }
}
