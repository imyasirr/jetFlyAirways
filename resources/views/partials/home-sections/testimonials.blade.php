@if(isset($testimonials) && $testimonials->isNotEmpty())
<section class="jfa-section jfa-section--blue">
    <div class="jfa-container">
        <div style="text-align:center;margin-bottom:32px;">
            <h2 class="jfa-section-title">Loved by Travellers</h2>
            <p class="jfa-section-sub">Here's what our customers say</p>
        </div>
        <div class="jfa-grid jfa-grid--3">
            @foreach($testimonials as $t)
                <article class="jfa-testimonial">
                    <div class="jfa-testimonial__stars">{{ str_repeat('★', min(5, (int) $t->rating)) }}</div>
                    <p class="jfa-testimonial__text">"{{ $t->review }}"</p>
                    <div class="jfa-testimonial__author">
                        @if($t->photoUrl())
                            <span class="jfa-testimonial__avatar jfa-testimonial__avatar--photo">
                                <img src="{{ $t->photoUrl() }}" alt="{{ $t->name }}" loading="lazy" decoding="async">
                            </span>
                        @else
                            <span class="jfa-testimonial__avatar">{{ mb_substr($t->name, 0, 1) }}</span>
                        @endif
                        <span>
                            <strong style="display:block;font-size:14px;">{{ $t->name }}</strong>
                            @if($t->designation)<small style="color:var(--jfa-muted);">{{ $t->designation }}</small>@endif
                        </span>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
