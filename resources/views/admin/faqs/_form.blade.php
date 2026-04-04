@php $f = $faq ?? null; @endphp
<div style="display:grid;gap:12px;max-width:640px;">
    <label>Question <input type="text" name="question" value="{{ old('question', $f?->question) }}" required></label>
    <label>Answer <textarea name="answer" rows="8">{{ old('answer', $f?->answer) }}</textarea></label>
    @include('admin.partials.toggle', [
        'name' => 'is_active',
        'label' => 'Active',
        'hint' => 'Inactive questions are hidden from the public FAQ page.',
        'checked' => old('is_active', ($f?->is_active ?? true) ? '1' : '0') === '1',
    ])
</div>
