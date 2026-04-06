<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Str;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $blogs = Blog::query()
            ->published()
            ->orderByDesc('publish_at')
            ->paginate(12)
            ->withQueryString();

        return view('blog.index', compact('blogs'));
    }

    public function show(Blog $blog): View
    {
        abort_unless(
            $blog->publish_at !== null && $blog->publish_at->lte(now()),
            404
        );

        // ✅ IMAGE URL FIX YAHAN
        if ($blog->cover_url) {
            if (Str::startsWith($blog->cover_url, ['http://', 'https://'])) {
                $blog->cover_image = $blog->cover_url;
            } else {
                $blog->cover_image = asset('storage/' . $blog->cover_url);
            }
        } else {
            $blog->cover_image = null;
        }

        return view('blog.show', compact('blog'));
    }
}
