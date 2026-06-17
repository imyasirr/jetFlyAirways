@extends('layouts.account')

@section('title', 'Saved travellers')
@section('heading', 'Saved travellers')

@section('content')
    @if(session('status'))
        <div class="jfa-form-success" role="status" style="margin-bottom:16px;">
            <span class="material-symbols-outlined filled">check_circle</span>
            {{ session('status') }}
        </div>
    @endif

    <div class="jfa-traveller-list">
        @forelse($savedTravellers as $traveller)
            @php
                $initial = mb_strtoupper(mb_substr(trim($traveller->full_name), 0, 1)) ?: 'T';
            @endphp
            <div class="jfa-traveller-row" data-traveller-row>
                <div class="jfa-traveller-row__view" data-traveller-view>
                    <span class="jfa-traveller-row__avatar" aria-hidden="true">{{ $initial }}</span>
                    <div class="jfa-traveller-row__info">
                        <strong>{{ $traveller->full_name }}</strong>
                        <span>
                            {{ collect([$traveller->email, $traveller->phone])->filter()->implode(' · ') ?: 'No contact details' }}
                        </span>
                    </div>
                    <div class="jfa-traveller-row__actions">
                        <button type="button" class="jfa-icon-btn" title="Edit traveller" data-traveller-edit aria-expanded="false">
                            <span class="material-symbols-outlined">edit</span>
                        </button>
                        <form method="post" action="{{ route('account.saved-travellers.destroy', $traveller) }}" onsubmit="return confirm('Remove this traveller?');">
                            @csrf
                            @method('delete')
                            <button type="submit" class="jfa-icon-btn jfa-icon-btn--danger" title="Remove traveller">
                                <span class="material-symbols-outlined">delete</span>
                            </button>
                        </form>
                    </div>
                </div>

                <form method="post" action="{{ route('account.saved-travellers.update', $traveller) }}" class="jfa-traveller-row__edit" data-traveller-edit-panel hidden>
                    @csrf
                    @method('put')
                    <div class="jfa-traveller-form-grid">
                        <div>
                            <label class="jfa-label" for="edit-name-{{ $traveller->id }}">Full name</label>
                            <input id="edit-name-{{ $traveller->id }}" type="text" name="full_name" value="{{ old('full_name', $traveller->full_name) }}" required>
                        </div>
                        <div>
                            <label class="jfa-label" for="edit-email-{{ $traveller->id }}">Email</label>
                            <input id="edit-email-{{ $traveller->id }}" type="email" name="email" value="{{ old('email', $traveller->email) }}">
                        </div>
                        <div>
                            <label class="jfa-label" for="edit-phone-{{ $traveller->id }}">Phone</label>
                            <input id="edit-phone-{{ $traveller->id }}" type="text" name="phone" value="{{ old('phone', $traveller->phone) }}">
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="submit" class="btn jfa-profile-save">Save changes</button>
                        <button type="button" class="btn secondary" data-traveller-cancel>Cancel</button>
                    </div>
                </form>
            </div>
        @empty
            <div class="jfa-traveller-empty">
                <span class="material-symbols-outlined">group</span>
                <h3>No saved travellers yet</h3>
                <p>Add family or friends to speed up future bookings.</p>
            </div>
        @endforelse
    </div>

    @if($savedTravellers->hasPages())
        <div class="jfa-module-pagination">{{ $savedTravellers->links() }}</div>
    @endif

    <div class="jfa-traveller-add-wrap">
        <button type="button" class="jfa-traveller-add" id="jfa-traveller-add-btn" aria-expanded="false">
            <span class="material-symbols-outlined">add</span>
            Add new traveller
        </button>

        <div class="jfa-acct-card jfa-traveller-form-panel" id="jfa-traveller-add-panel" hidden>
            <h2>Add traveller</h2>
            <form method="post" action="{{ route('account.saved-travellers.store') }}" class="jfa-profile-form">
                @csrf
                <div class="jfa-traveller-form-grid">
                    <div>
                        <label class="jfa-label" for="add-name">Full name</label>
                        <input id="add-name" type="text" name="full_name" value="{{ old('full_name') }}" required placeholder="Full name">
                        @error('full_name')<span class="jfa-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="jfa-label" for="add-email">Email</label>
                        <input id="add-email" type="email" name="email" value="{{ old('email') }}" placeholder="email@example.com">
                        @error('email')<span class="jfa-field-error">{{ $message }}</span>@enderror
                    </div>
                    <div>
                        <label class="jfa-label" for="add-phone">Phone</label>
                        <input id="add-phone" type="text" name="phone" value="{{ old('phone') }}" placeholder="+91 98765 43210">
                        @error('phone')<span class="jfa-field-error">{{ $message }}</span>@enderror
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn jfa-profile-save">Save traveller</button>
                    <button type="button" class="btn secondary" id="jfa-traveller-add-cancel">Cancel</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
<script>
(function () {
    document.querySelectorAll('[data-traveller-row]').forEach(function (row) {
        var view = row.querySelector('[data-traveller-view]');
        var panel = row.querySelector('[data-traveller-edit-panel]');
        var editBtn = row.querySelector('[data-traveller-edit]');
        var cancelBtn = row.querySelector('[data-traveller-cancel]');
        if (!view || !panel || !editBtn) return;

        function openEdit() {
            view.hidden = true;
            panel.hidden = false;
            editBtn.setAttribute('aria-expanded', 'true');
        }
        function closeEdit() {
            view.hidden = false;
            panel.hidden = true;
            editBtn.setAttribute('aria-expanded', 'false');
        }

        editBtn.addEventListener('click', openEdit);
        if (cancelBtn) cancelBtn.addEventListener('click', closeEdit);
    });

    var addBtn = document.getElementById('jfa-traveller-add-btn');
    var addPanel = document.getElementById('jfa-traveller-add-panel');
    var addCancel = document.getElementById('jfa-traveller-add-cancel');
    if (addBtn && addPanel) {
        function openAdd() {
            addPanel.hidden = false;
            addBtn.hidden = true;
            addBtn.setAttribute('aria-expanded', 'true');
            addPanel.querySelector('input')?.focus();
        }
        function closeAdd() {
            addPanel.hidden = true;
            addBtn.hidden = false;
            addBtn.setAttribute('aria-expanded', 'false');
        }
        addBtn.addEventListener('click', openAdd);
        if (addCancel) addCancel.addEventListener('click', closeAdd);
        @if($errors->any())
        openAdd();
        @endif
    }
})();
</script>
@endpush
