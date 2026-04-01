@if(isset($testimonials) && $testimonials->isNotEmpty())
    <h2 class="section-title section-title-spaced-lg">What travellers say</h2>
    <div class="grid">
        @foreach($testimonials as $t)
            <div class="card">
                <p class="card-meta" style="margin-bottom:8px;">{{ str_repeat('★', (int) $t->rating) }}</p>
                <p class="card-meta" style="font-style:italic;">“{{ $t->review }}”</p>
                <p class="card-title" style="margin-top:12px;margin-bottom:0;">{{ $t->name }}</p>
                @if($t->designation)<p class="card-meta" style="margin:4px 0 0;">{{ $t->designation }}</p>@endif
            </div>
        @endforeach
    </div>
@endif
