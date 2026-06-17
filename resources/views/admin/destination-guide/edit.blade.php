@extends('layouts.admin')

@section('content')
    <div class="card" style="margin-bottom:20px;">
        <div class="row" style="margin-bottom:12px;">
            <h1 class="section-title" style="margin:0;">Destination guide</h1>
            <a class="btn secondary" href="{{ url('/p/destination-guide') }}" target="_blank" rel="noopener">View page</a>
        </div>
        <p style="margin:0;color:var(--muted, #64748b);">Manage intro, feature cards, trending destinations, tips and callout. Page title and SEO meta come from the <a href="{{ route('admin.pages.index') }}">CMS page</a> slug <code>destination-guide</code>.</p>
    </div>

    <div class="card" style="margin-bottom:20px;">
        <h2 class="section-title">Page content settings</h2>
        <form method="post" action="{{ route('admin.destination-guide.settings.update') }}" class="admin-form-grid">
            @csrf
            @method('PUT')
            <div class="admin-field-full"><label>Intro paragraph<textarea name="intro" rows="3">{{ old('intro', $settings->intro) }}</textarea></label></div>
            <div><label>Destinations heading<input name="spots_heading" value="{{ old('spots_heading', $settings->spots_heading) }}" required></label></div>
            <div><label>Tips heading<input name="tips_heading" value="{{ old('tips_heading', $settings->tips_heading) }}" required></label></div>
            <div class="admin-field-full"><label>Destinations subheading<textarea name="spots_subheading" rows="2">{{ old('spots_subheading', $settings->spots_subheading) }}</textarea></label></div>
            <div><label>Callout title<input name="callout_title" value="{{ old('callout_title', $settings->callout_title) }}"></label></div>
            <div><label>Callout link<input name="callout_link" value="{{ old('callout_link', $settings->callout_link) }}" placeholder="/p/contact"></label></div>
            <div class="admin-field-full"><label>Callout body<textarea name="callout_body" rows="2">{{ old('callout_body', $settings->callout_body) }}</textarea></label></div>
            <div><label>Callout link label<input name="callout_link_label" value="{{ old('callout_link_label', $settings->callout_link_label) }}"></label></div>
            <div class="admin-field-full"><button type="submit" class="btn">Save settings</button></div>
        </form>
    </div>

    <div class="card" style="margin-bottom:20px;">
        <h2 class="section-title">Feature cards</h2>
        <div class="admin-table-scroll">
            <table class="admin-table">
                <thead><tr><th>Icon</th><th>Title</th><th>Order</th><th>Active</th><th></th></tr></thead>
                <tbody>
                    @foreach($features as $feature)
                        <tr>
                            <td colspan="5" style="background:#f8fafc;">
                                <form method="post" action="{{ route('admin.destination-guide.features.update', $feature) }}" class="admin-form-grid" style="padding:12px 0;">
                                    @csrf
                                    @method('PUT')
                                    <div><label>Icon<input name="icon" value="{{ $feature->icon }}" placeholder="calendar_month"></label></div>
                                    <div><label>Title<input name="title" value="{{ $feature->title }}" required></label></div>
                                    <div><label>Sort<input type="number" name="sort_order" value="{{ $feature->sort_order }}" min="0"></label></div>
                                    <div><label><input type="checkbox" name="is_active" value="1" @checked($feature->is_active)> Active</label></div>
                                    <div class="admin-field-full"><label>Body<textarea name="body" rows="2" required>{{ $feature->body }}</textarea></label></div>
                                    <div class="admin-field-full" style="display:flex;gap:8px;">
                                        <button type="submit" class="btn secondary">Update</button>
                                    </div>
                                </form>
                                <form method="post" action="{{ route('admin.destination-guide.features.destroy', $feature) }}" onsubmit="return confirm('Delete feature?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <h3 style="margin:20px 0 10px;font-size:1rem;">Add feature</h3>
        <form method="post" action="{{ route('admin.destination-guide.features.store') }}" class="admin-form-grid">
            @csrf
            <div><label>Icon<input name="icon" placeholder="calendar_month"></label></div>
            <div><label>Title<input name="title" required></label></div>
            <div><label>Sort<input type="number" name="sort_order" value="0" min="0"></label></div>
            <div class="admin-field-full"><label>Body<textarea name="body" rows="2" required></textarea></label></div>
            <div class="admin-field-full"><button type="submit" class="btn">Add feature</button></div>
        </form>
    </div>

    <div class="card" style="margin-bottom:20px;">
        <h2 class="section-title">Trending destinations</h2>
        @foreach($spots as $spot)
            <div style="border:1px solid #e2e8f0;border-radius:12px;padding:16px;margin-bottom:12px;">
                <form method="post" action="{{ route('admin.destination-guide.spots.update', $spot) }}" enctype="multipart/form-data" class="admin-form-grid">
                    @csrf
                    @method('PUT')
                    <div><label>Name<input name="name" value="{{ $spot->name }}" required></label></div>
                    <div><label>Tag line<input name="tag_line" value="{{ $spot->tag_line }}"></label></div>
                    <div><label>Best season<input name="best_season" value="{{ $spot->best_season }}"></label></div>
                    <div><label>Package destination<input name="package_destination" value="{{ $spot->package_destination }}"></label></div>
                    <div><label>Custom link<input name="link_url" value="{{ $spot->link_url }}"></label></div>
                    <div><label>Sort<input type="number" name="sort_order" value="{{ $spot->sort_order }}" min="0"></label></div>
                    <div><label><input type="checkbox" name="is_active" value="1" @checked($spot->is_active)> Active</label></div>
                    <div class="admin-field-full"><label>Image URL<input name="image" value="{{ $spot->image }}" placeholder="https://… or leave after upload"></label></div>
                    <div class="admin-field-full"><label>Upload image<input type="file" name="image_file" accept="image/*"></label></div>
                    @if($spot->imageUrl())
                        <div class="admin-field-full"><img src="{{ $spot->imageUrl() }}" alt="" style="max-width:200px;border-radius:8px;"></div>
                    @endif
                    <div class="admin-field-full" style="display:flex;gap:8px;">
                        <button type="submit" class="btn secondary">Update destination</button>
                    </div>
                </form>
                <form method="post" action="{{ route('admin.destination-guide.spots.destroy', $spot) }}" onsubmit="return confirm('Delete destination?');" style="margin-top:8px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn">Delete</button>
                </form>
            </div>
        @endforeach
        <h3 style="margin:20px 0 10px;font-size:1rem;">Add destination</h3>
        <form method="post" action="{{ route('admin.destination-guide.spots.store') }}" enctype="multipart/form-data" class="admin-form-grid">
            @csrf
            <div><label>Name<input name="name" required></label></div>
            <div><label>Tag line<input name="tag_line"></label></div>
            <div><label>Best season<input name="best_season"></label></div>
            <div><label>Package destination<input name="package_destination"></label></div>
            <div><label>Image URL<input name="image" placeholder="https://…"></label></div>
            <div class="admin-field-full"><label>Upload image<input type="file" name="image_file" accept="image/*"></label></div>
            <div class="admin-field-full"><button type="submit" class="btn">Add destination</button></div>
        </form>
    </div>

    <div class="card">
        <h2 class="section-title">Planning tips</h2>
        @foreach($tips as $tip)
            <div style="border:1px solid #e2e8f0;border-radius:12px;padding:16px;margin-bottom:12px;">
                <form method="post" action="{{ route('admin.destination-guide.tips.update', $tip) }}" class="admin-form-grid">
                    @csrf
                    @method('PUT')
                    <div><label>Title<input name="title" value="{{ $tip->title }}" required></label></div>
                    <div><label>Sort<input type="number" name="sort_order" value="{{ $tip->sort_order }}" min="0"></label></div>
                    <div><label><input type="checkbox" name="is_active" value="1" @checked($tip->is_active)> Active</label></div>
                    <div class="admin-field-full"><label>Body<textarea name="body" rows="2">{{ $tip->body }}</textarea></label></div>
                    <div class="admin-field-full"><button type="submit" class="btn secondary">Update tip</button></div>
                </form>
                <form method="post" action="{{ route('admin.destination-guide.tips.destroy', $tip) }}" onsubmit="return confirm('Delete tip?');" style="margin-top:8px;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn">Delete</button>
                </form>
            </div>
        @endforeach
        <h3 style="margin:20px 0 10px;font-size:1rem;">Add tip</h3>
        <form method="post" action="{{ route('admin.destination-guide.tips.store') }}" class="admin-form-grid">
            @csrf
            <div><label>Title<input name="title" required></label></div>
            <div class="admin-field-full"><label>Body<textarea name="body" rows="2"></textarea></label></div>
            <div class="admin-field-full"><button type="submit" class="btn">Add tip</button></div>
        </form>
    </div>
@endsection
