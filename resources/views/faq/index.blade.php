@extends('layouts.app')

@section('title', 'FAQ — Jet Fly Airways')

@section('meta_description', 'Frequently asked questions about booking flights, hotels, packages and support with Jet Fly Airways.')

@section('content')
    <h1 class="section-title">Frequently asked questions</h1>
    @forelse($faqs as $faq)
        <details class="card" style="margin-bottom:12px;padding:0;overflow:hidden;">
            <summary style="padding:16px 18px;cursor:pointer;font-weight:800;color:var(--primary);list-style:none;">{{ $faq->question }}</summary>
            <div style="padding:0 18px 18px;font-size:15px;color:#475569;line-height:1.6;">{!! nl2br(e($faq->answer ?? '')) !!}</div>
        </details>
    @empty
        <p class="card empty-hint">No FAQs yet — add them from Admin → FAQs.</p>
    @endforelse
@endsection
