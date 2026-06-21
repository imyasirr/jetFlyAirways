@if(isset($trustCards) && $trustCards->isNotEmpty())
<section class="jfa-section jfa-section--muted">
    <div class="jfa-container">
        <div class="jfa-grid jfa-grid--3">
            @foreach($trustCards as $card)
                <article class="jfa-trust-card" style="flex-direction:column;text-align:center;">
                    <span class="jfa-trust-card__icon" style="margin-inline:auto;">
                        <span class="material-symbols-outlined filled">{{ $card->icon ?: 'verified' }}</span>
                    </span>
                    <span>
                        <p class="jfa-trust-card__title">{{ $card->title }}</p>
                        <p class="jfa-trust-card__sub">{{ $card->description }}</p>
                    </span>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
