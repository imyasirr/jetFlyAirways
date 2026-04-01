<?php

namespace App\Http\Controllers;

use App\Mail\NewContactInquiryMail;
use App\Models\ContactInquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ContactFormController extends Controller
{
    public function create(): View
    {
        return view('contact.create');
    }

    public function store(Request $request): RedirectResponse
    {
        abort_unless(Schema::hasTable('contact_inquiries'), 503);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120'],
            'phone' => ['nullable', 'string', 'max:20'],
            'subject' => ['nullable', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $inquiry = ContactInquiry::create([
            ...$data,
            'status' => 'new',
        ]);

        $to = config('jetfly.admin_notify_email');
        if (is_string($to) && filter_var($to, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($to)->send(new NewContactInquiryMail($inquiry));
            } catch (\Throwable) {
                // Mail may fail in local dev without SMTP; inquiry is still stored.
            }
        }

        return redirect()->route('contact.create')->with('status', 'Thanks — we have received your message and will reply soon.');
    }
}
