@extends('layouts.app')

@section('title', 'FAQ — Jet Fly Airways')

@section('meta_description', 'Frequently asked questions about booking flights, hotels, packages and support with Jet Fly Airways.')

@section('content')
    <h1 class="section-title">Frequently asked questions</h1>
    @forelse($faqs as $faq)
        <details class="card faq-item">
            <summary>{{ $faq->question }}</summary>
            <div class="faq-item__body">{!! nl2br(e($faq->answer ?? '')) !!}</div>
        </details>
    @empty
        <p class="card empty-hint">No FAQs yet — add them from Admin → FAQs.</p>
    @endforelse
@endsection
