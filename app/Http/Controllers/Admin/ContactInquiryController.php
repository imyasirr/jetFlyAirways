<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactInquiry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactInquiryController extends Controller
{
    public function index(): View
    {
        $inquiries = ContactInquiry::query()->orderByDesc('id')->paginate(25);

        return view('admin.contact-inquiries.index', compact('inquiries'));
    }

    public function show(ContactInquiry $contact_inquiry): View
    {
        return view('admin.contact-inquiries.show', ['inquiry' => $contact_inquiry]);
    }

    public function update(Request $request, ContactInquiry $contact_inquiry): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:new,read,replied,closed'],
        ]);
        $contact_inquiry->update($data);

        return redirect()->route('admin.contact-inquiries.show', $contact_inquiry)->with('status', 'Status updated.');
    }
}
