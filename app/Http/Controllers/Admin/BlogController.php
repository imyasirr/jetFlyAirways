<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Support\PublicImageStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class BlogController extends Controller
{
    public function index(): View
    {
        $blogs = Blog::query()->orderByDesc('publish_at')->orderByDesc('id')->paginate(20);

        return view('admin.blogs.index', compact('blogs'));
    }

    public function show(Blog $blog): View
    {
        $isPublished = $blog->publish_at !== null && $blog->publish_at->lte(now());

        return view('admin.blogs.show', compact('blog', 'isPublished'));
    }

    public function create(): View
    {
        return view('admin.blogs.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request, null, true);
        $path = PublicImageStorage::storeUpload($request->file('cover_image_file'), 'blog-covers');
        if ($path !== null) {
            $data['cover_image'] = $path;
        }
        Blog::create($data);

        return redirect()->route('admin.blogs.index')->with('status', 'Blog post created.');
    }

    public function edit(Blog $blog): View
    {
        return view('admin.blogs.edit', compact('blog'));
    }

    public function update(Request $request, Blog $blog): RedirectResponse
    {
        $data = $this->validated($request, $blog, false);
        if ($request->hasFile('cover_image_file')) {
            $path = PublicImageStorage::storeUpload($request->file('cover_image_file'), 'blog-covers', $blog->cover_image);
            if ($path !== null) {
                $data['cover_image'] = $path;
            }
        }
        $blog->update($data);

        return redirect()->route('admin.blogs.index')->with('status', 'Blog post updated.');
    }

    public function destroy(Blog $blog): RedirectResponse
    {
        PublicImageStorage::deleteIfExists($blog->cover_image);
        $blog->delete();

        return redirect()->route('admin.blogs.index')->with('status', 'Blog post deleted.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validated(Request $request, ?Blog $blog, bool $creating): array
    {
        $coverRule = $creating
            ? ['nullable', 'image', 'mimes:jpeg,png,webp,gif', 'max:10240']
            : ['nullable', 'image', 'mimes:jpeg,png,webp,gif', 'max:10240'];

        $data = $request->validate([
            'title' => ['required', 'string', 'max:200'],
            'category' => ['nullable', 'string', 'max:80'],
            'tags' => ['nullable', 'string', 'max:500'],
            'author_name' => ['nullable', 'string', 'max:100'],
            'content' => ['nullable', 'string'],
            'cover_image_file' => $coverRule,
            'meta_title' => ['nullable', 'string', 'max:200'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'is_featured' => ['boolean'],
            'publish_at' => ['nullable', 'date'],
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        unset($data['cover_image_file']);

        $baseSlug = Str::slug($request->string('title'));
        if ($baseSlug === '') {
            $baseSlug = 'post-'.Str::lower(Str::random(8));
        }
        $data['slug'] = $this->ensureUniqueBlogSlug($baseSlug, $blog?->id);

        return $data;
    }

    private function ensureUniqueBlogSlug(string $slug, ?int $exceptId = null): string
    {
        $base = $slug;
        $n = 1;
        while (
            Blog::query()
                ->where('slug', $slug)
                ->when($exceptId, fn ($q) => $q->where('id', '!=', $exceptId))
                ->exists()
        ) {
            $slug = $base.'-'.$n++;
        }

        return $slug;
    }
}
