@php $c = $career ?? null; @endphp
<div style="display:grid;gap:12px;max-width:720px;">
    <label>Job title <input type="text" name="job_title" value="{{ old('job_title', $c?->job_title) }}" required></label>
    <label>Department <input type="text" name="department" value="{{ old('department', $c?->department) }}"></label>
    <label>Location <input type="text" name="location" value="{{ old('location', $c?->location) }}"></label>
    <label>Salary <input type="text" name="salary" value="{{ old('salary', $c?->salary) }}"></label>
    <label>Openings <input type="number" name="openings" min="1" value="{{ old('openings', $c?->openings ?? 1) }}"></label>
    <label>Apply last date <input type="date" name="apply_last_date" value="{{ old('apply_last_date', $c?->apply_last_date?->format('Y-m-d')) }}"></label>
    <label>Job description <textarea name="job_description" rows="10">{{ old('job_description', $c?->job_description) }}</textarea></label>
    <label>Required skills <textarea name="required_skills" rows="6">{{ old('required_skills', $c?->required_skills) }}</textarea></label>
    <label style="display:flex;align-items:center;gap:8px;">
        <input type="checkbox" name="is_hiring" value="1" @checked(old('is_hiring', $c?->is_hiring ?? true))> Accepting applications (show on /jobs)
    </label>
</div>
