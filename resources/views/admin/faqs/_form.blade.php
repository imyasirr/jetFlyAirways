@php $f = $faq ?? null; @endphp
<div style="display:grid;gap:12px;max-width:640px;">
    <label>Question <input type="text" name="question" value="{{ old('question', $f?->question) }}" required></label>
    <label>Answer <textarea name="answer" rows="8">{{ old('answer', $f?->answer) }}</textarea></label>
    <label style="display:flex;align-items:center;gap:8px;">
        <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $f?->is_active ?? true))> Active (show on /faq)
    </label>
</div>
