<?php

namespace App\Http\Controllers;

use App\Models\Career;
use App\Models\CareerApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class JobController extends Controller
{
    public function index(): View
    {
        $careers = collect();
        if (Schema::hasTable('careers')) {
            $careers = Career::query()->openForApplications()->orderBy('job_title')->get();
        }

        return view('jobs.index', compact('careers'));
    }

    public function show(Career $career): View
    {
        abort_unless($career->is_hiring && $this->careerStillOpen($career), 404);

        return view('jobs.show', compact('career'));
    }

    public function apply(Request $request, Career $career): RedirectResponse
    {
        abort_unless($career->is_hiring && $this->careerStillOpen($career), 404);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120'],
            'phone' => ['nullable', 'string', 'max:30'],
            'cover_letter' => ['nullable', 'string', 'max:5000'],
            'resume' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:5120'],
        ]);

        $resumePath = null;
        if ($request->hasFile('resume')) {
            $resumePath = $request->file('resume')->store('resumes', 'local');
        }

        CareerApplication::create([
            'career_id' => $career->id,
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'cover_letter' => $data['cover_letter'] ?? null,
            'resume_path' => $resumePath,
        ]);

        return redirect()->route('jobs.index')->with('status', 'Application submitted. Our HR team will contact you.');
    }

    private function careerStillOpen(Career $career): bool
    {
        if ($career->apply_last_date === null) {
            return true;
        }

        return $career->apply_last_date->gte(today());
    }
}
