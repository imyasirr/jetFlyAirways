@php $c = $career ?? null; @endphp
<div class="admin-form-grid">
    <label>Job title
        <input type="text" name="job_title" value="{{ old('job_title', $c?->job_title) }}" required>
    </label>
    <label>Department
        <input type="text" name="department" value="{{ old('department', $c?->department) }}">
    </label>
    <label>Location
        <input type="text" name="location" value="{{ old('location', $c?->location) }}">
    </label>
    <label>Salary
        <input type="text" name="salary" value="{{ old('salary', $c?->salary) }}">
    </label>
    <label>Openings
        <input type="number" name="openings" min="1" value="{{ old('openings', $c?->openings ?? 1) }}">
    </label>
    <label>Apply last date
        <input type="date" name="apply_last_date" value="{{ old('apply_last_date', $c?->apply_last_date?->format('Y-m-d')) }}">
    </label>
    <label class="admin-field-full">Job description
        <textarea name="job_description" rows="10">{{ old('job_description', $c?->job_description) }}</textarea>
    </label>
    <label class="admin-field-full">Required skills
        <textarea name="required_skills" rows="6">{{ old('required_skills', $c?->required_skills) }}</textarea>
    </label>
    <div class="admin-field-full">
        @include('admin.partials.toggle', [
            'name' => 'is_hiring',
            'label' => 'Accepting applications',
            'hint' => 'When on, this role appears on the public /jobs page and accepts applications.',
            'checked' => old('is_hiring', ($c?->is_hiring ?? true) ? '1' : '0') === '1',
        ])
    </div>
</div>
