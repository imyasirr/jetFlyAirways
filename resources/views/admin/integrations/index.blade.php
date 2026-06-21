@extends('layouts.admin')

@section('title', 'API Integrations')

@section('content')
    <div class="card">
        <h1 class="section-title">API Integrations</h1>
        <p style="margin:0 0 14px;color:#64748b;max-width:78ch;">
            Manage third-party integration endpoints and credentials for booking, payments, messaging, maps, and support channels.
            Use Test to quickly verify reachability of the configured base URL.
        </p>

        @php
            $livePayment = $paymentGatewayStatus ?? ['configured' => false, 'source' => 'env', 'provider' => 'razorpay'];
        @endphp
        <div style="margin:0 0 16px;padding:12px 14px;border:1px solid var(--admin-border);border-radius:12px;background:#f8fafc;font-size:14px;line-height:1.55;">
            <strong>Live payment gateway:</strong>
            @if($livePayment['configured'])
                Razorpay is configured via <strong>{{ $livePayment['source'] === 'admin' ? 'Admin panel' : '.env file' }}</strong>.
            @else
                Payments are disabled until Razorpay keys are saved here (Active + Key ID + Secret) or in <code>.env</code>.
            @endif
        </div>

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
                            @if($integration->service === 'payment_gateway')
                                <p class="admin-field-full" style="margin:0;font-size:13px;color:#64748b;line-height:1.55;">
                                    Razorpay checkout uses these credentials on the public site. When <strong>Active</strong>, admin keys override <code>RAZORPAY_KEY</code> / <code>RAZORPAY_SECRET</code> in <code>.env</code>.
                                </p>
                                <label>Razorpay Key ID
                                    <input type="text" name="integrations[{{ $index }}][api_key]" value="{{ old("integrations.$index.api_key", $integration->api_key) }}" placeholder="rzp_test_... or rzp_live_..." autocomplete="off">
                                </label>
                                <label>Razorpay Key Secret
                                    <input type="password" name="integrations[{{ $index }}][api_secret]" value="{{ old("integrations.$index.api_secret", $integration->api_secret) }}" placeholder="Secret key from Razorpay dashboard" autocomplete="off">
                                </label>
                                <label class="admin-field-full">Webhook / notes (optional)
                                    <textarea rows="2" name="integrations[{{ $index }}][notes]" placeholder="Internal notes, webhook URL, or callback info">{{ old("integrations.$index.notes", $integration->notes) }}</textarea>
                                </label>
                                <input type="hidden" name="integrations[{{ $index }}][base_url]" value="">
                                <p class="admin-field-full" style="margin:0;font-size:12px;color:#64748b;">
                                    Payment verify URL: <code>{{ route('payments.verify') }}</code>
                                </p>
                            @else
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
                            @endif
                        </div>
                        @if($integration->service !== 'payment_gateway')
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
                        @else
                        <div style="margin-top:10px;padding:10px 12px;border:1px dashed #cbd5e1;border-radius:10px;background:#f8fafc;">
                            <p style="margin:0 0 8px;font-size:12px;font-weight:700;color:#475569;">Checkout flow</p>
                            <ol style="margin:0;padding-left:18px;font-size:13px;color:#475569;line-height:1.6;">
                                <li>Customer completes booking on the website.</li>
                                <li>Confirmation page shows <strong>Pay now with Razorpay</strong>.</li>
                                <li>Razorpay popup opens using the Key ID saved here.</li>
                                <li>Payment is verified at <code>{{ route('payments.verify') }}</code>.</li>
                            </ol>
                        </div>
                        @endif

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

