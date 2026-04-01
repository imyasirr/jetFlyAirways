<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        $faqs = collect();
        if (Schema::hasTable('faqs')) {
            $faqs = Faq::query()->where('is_active', true)->orderBy('id')->get();
        }

        return view('faq.index', compact('faqs'));
    }
}
