<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\NewContactInquiryMail;
use App\Models\Blog;
use App\Models\ContactInquiry;
use App\Models\Faq;
use App\Models\Offer;
use App\Models\Page;
use App\Models\SiteSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class ContentController extends Controller
{
    public function faqs(): JsonResponse
    {
        if (! Schema::hasTable('faqs')) {
            return response()->json(['faqs' => []]);
        }

        $faqs = Faq::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->get()
            ->map(fn ($f) => [
                'id' => $f->id,
                'question' => $f->question,
                'answer' => $f->answer,
            ]);

        return response()->json(['faqs' => $faqs]);
    }

    public function blogs(): JsonResponse
    {
        if (! Schema::hasTable('blogs')) {
            return response()->json(['blogs' => []]);
        }

        $blogs = Blog::query()
            ->published()
            ->orderByDesc('publish_at')
            ->paginate(12);

        return response()->json([
            'blogs' => $blogs->getCollection()->map(fn ($b) => [
                'id' => $b->id,
                'slug' => $b->slug,
                'title' => $b->title,
                'excerpt' => $b->excerpt,
                'image_url' => $b->cover_url,
                'published_at' => $b->publish_at?->toIso8601String(),
            ]),
            'meta' => [
                'current_page' => $blogs->currentPage(),
                'last_page' => $blogs->lastPage(),
                'per_page' => $blogs->perPage(),
                'total' => $blogs->total(),
            ],
        ]);
    }

    public function blogShow(string $slug): JsonResponse
    {
        $blog = Blog::query()->where('slug', $slug)->published()->firstOrFail();

        return response()->json([
            'blog' => [
                'id' => $blog->id,
                'slug' => $blog->slug,
                'title' => $blog->title,
                'excerpt' => $blog->excerpt,
                'content' => $blog->rendered_content,
                'image_url' => $blog->cover_url,
                'published_at' => $blog->publish_at?->toIso8601String(),
            ],
        ]);
    }

    public function page(string $slug): JsonResponse
    {
        $page = Page::query()->where('slug', $slug)->where('is_active', true)->firstOrFail();

        return response()->json([
            'page' => [
                'slug' => $page->slug,
                'title' => $page->title,
                'content' => $page->body,
            ],
        ]);
    }

    public function offers(): JsonResponse
    {
        if (! Schema::hasTable('offers')) {
            return response()->json(['offers' => []]);
        }

        $offers = Offer::query()->activeWindow()->orderByDesc('id')->get()
            ->map(fn ($o) => [
                'id' => $o->id,
                'title' => $o->title,
                'description' => $o->description,
                'redirect_url' => $o->redirect_url,
            ]);

        return response()->json(['offers' => $offers]);
    }

    public function siteInfo(): JsonResponse
    {
        $setting = Schema::hasTable('site_settings') ? SiteSetting::query()->first() : null;

        return response()->json([
            'site' => [
                'name' => $setting?->brand_name ?? 'Jet Fly Airways',
                'email' => $setting?->primarySupportEmail(),
                'phone' => $setting?->primarySupportPhone(),
                'whatsapp' => config('jetfly.whatsapp_number'),
                'address' => $setting?->officeAddressList()[0]['address'] ?? null,
            ],
        ]);
    }

    public function contact(Request $request): JsonResponse
    {
        abort_unless(Schema::hasTable('contact_inquiries'), 503);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:120'],
            'phone' => ['nullable', 'string', 'max:20'],
            'subject' => ['nullable', 'string', 'max:200'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $inquiry = ContactInquiry::create([...$data, 'status' => 'new']);

        $to = config('jetfly.admin_notify_email');
        if (is_string($to) && filter_var($to, FILTER_VALIDATE_EMAIL)) {
            try {
                Mail::to($to)->send(new NewContactInquiryMail($inquiry));
            } catch (\Throwable) {
                //
            }
        }

        return response()->json(['message' => 'Thanks — we have received your message and will reply soon.']);
    }
}
