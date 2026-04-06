@extends('layouts.admin')

@section('title', 'API Integrations')

@section('content')
    <div class="card">
        <h1 class="section-title">API Integrations</h1>
        <p style="margin:0 0 14px;color:#64748b;max-width:78ch;">
            Manage third-party integration endpoints and credentials for booking, payments, messaging, maps, and support channels.
            Use Test to quickly verify reachability of the configured base URL.
        </p>

        <form method="post" action="{{ route('admin.integrations.update') }}">
            @csrf
            @method('PUT')

            <div style="display:grid;gap:14px;">
                @foreach($integrations as $index => $integration)
                    <div class="admin-card" style="padding:14px;">
                        <input type="hidden" name="integrations[{{ $index }}][service]" value="{{ $integration->service }}">
                        <input type="hidden" name="integrations[{{ $index }}][display_name]" value="{{ $integration->display_name }}">

                        <div class="row" style="align-items:flex-start;">
                            <div>
                                <h2 style="margin:0 0 6px;font-size:1rem;">{{ $integration->display_name }}</h2>
                                <p style="margin:0;font-size:12px;color:#64748b;">Service key: {{ $integration->service }}</p>
                            </div>
                            <label style="display:flex;align-items:center;gap:8px;font-size:14px;margin:0;">
                                <input type="checkbox" name="integrations[{{ $index }}][is_active]" value="1" @checked(old("integrations.$index.is_active", $integration->is_active)) style="width:auto;">
                                Active
                            </label>
                        </div>

                        <div class="admin-form-grid" style="margin-top:10px;">
                            <label>Base URL / Endpoint
                                <input type="url" name="integrations[{{ $index }}][base_url]" value="{{ old("integrations.$index.base_url", $integration->base_url) }}" placeholder="https://api.example.com/...">
                            </label>
                            <label>API Key
                                <input type="text" name="integrations[{{ $index }}][api_key]" value="{{ old("integrations.$index.api_key", $integration->api_key) }}">
                            </label>
                            <label class="admin-field-full">API Secret / Token
                                <input type="text" name="integrations[{{ $index }}][api_secret]" value="{{ old("integrations.$index.api_secret", $integration->api_secret) }}">
                            </label>
                            <label class="admin-field-full">Notes
                                <textarea rows="2" name="integrations[{{ $index }}][notes]">{{ old("integrations.$index.notes", $integration->notes) }}</textarea>
                            </label>
                        </div>
                        <div style="margin-top:10px;padding:10px 12px;border:1px dashed #cbd5e1;border-radius:10px;background:#f8fafc;">
                            <p style="margin:0 0 8px;font-size:12px;font-weight:700;color:#475569;">Endpoint preview</p>
                            @foreach(($endpointTemplates[$integration->service] ?? []) as $endpoint)
                                <p style="margin:0 0 4px;font-family:ui-monospace,monospace;font-size:12px;color:#334155;">
                                    {{ rtrim((string) ($integration->base_url ?: 'https://api.example.com'), '/') }}{{ $endpoint }}
                                </p>
                            @endforeach
                            <p style="margin:10px 0 6px;font-size:12px;font-weight:700;color:#475569;">Sample payload</p>
                            <pre style="margin:0;font-size:12px;line-height:1.45;white-space:pre-wrap;word-break:break-word;">{{ json_encode($samplePayloads[$integration->service] ?? [], JSON_PRETTY_PRINT|JSON_UNESCAPED_SLASHES) }}</pre>
                        </div>

                        <div class="form-actions">
                            @if($integration->exists)
                                <a href="{{ route('admin.integrations.test', $integration) }}" class="btn ghost">Test</a>
                            @else
                                <button type="button" class="btn ghost" disabled>Test</button>
                            @endif
                            @if($integration->last_checked_at)
                                <span style="font-size:12px;color:#64748b;">
                                    Last check: {{ $integration->last_checked_at->format('d M Y H:i') }} · {{ $integration->last_check_status ?: 'n/a' }}
                                </span>
                            @elseif(!$integration->exists)
                                <span style="font-size:12px;color:#64748b;">Save settings first to enable Test.</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="form-actions" style="margin-top:16px;">
                <button type="submit" class="btn">Save integration settings</button>
            </div>
        </form>
    </div>
@endsection

