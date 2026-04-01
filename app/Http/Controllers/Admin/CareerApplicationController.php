<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CareerApplication;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CareerApplicationController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $applications = CareerApplication::query()
            ->with('career')
            ->orderByDesc('id')
            ->paginate(25);

        return view('admin.career-applications.index', compact('applications'));
    }

    public function show(CareerApplication $career_application): \Illuminate\View\View
    {
        $career_application->load('career');

        return view('admin.career-applications.show', ['application' => $career_application]);
    }

    public function resume(CareerApplication $career_application): StreamedResponse|RedirectResponse
    {
        if (! $career_application->resume_path || ! Storage::disk('local')->exists($career_application->resume_path)) {
            return redirect()->route('admin.career-applications.show', $career_application)->with('error', 'Resume file not found.');
        }

        return Storage::disk('local')->download($career_application->resume_path);
    }

    public function destroy(CareerApplication $career_application): RedirectResponse
    {
        if ($career_application->resume_path) {
            Storage::disk('local')->delete($career_application->resume_path);
        }
        $career_application->delete();

        return redirect()->route('admin.career-applications.index')->with('status', 'Application deleted.');
    }
}
