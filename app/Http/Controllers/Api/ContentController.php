<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Faq;
use App\Models\Page;
use Illuminate\Http\JsonResponse;
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
            ->orderBy('sort_order')
            ->get()
            ->map(fn ($f) => [
                'id' => $f->id,
                'question' => $f->question,
                'answer' => $f->answer,
                'category' => $f->category,
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
}
