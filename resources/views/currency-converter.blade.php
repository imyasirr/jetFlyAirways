@extends('layouts.app')

@section('title', 'Currency Converter — Jet Fly Airways')

@section('content')
    <div class="card" style="max-width:760px;">
        <h1 class="section-title">Currency Converter</h1>
        <p style="color:#64748b;margin-top:0;">Quick estimate converter for travel planning. Final payment is charged in INR unless otherwise specified.</p>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <label>Amount
                <input id="cc-amount" type="number" min="0" step="0.01" value="1000">
            </label>
            <label>From
                <select id="cc-from">
                    <option value="INR">INR</option>
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                    <option value="GBP">GBP</option>
                </select>
            </label>
            <label>To
                <select id="cc-to">
                    <option value="USD">USD</option>
                    <option value="INR">INR</option>
                    <option value="EUR">EUR</option>
                    <option value="GBP">GBP</option>
                </select>
            </label>
        </div>

        <div class="form-actions">
            <button id="cc-convert" type="button" class="btn">Convert</button>
        </div>

        <p id="cc-result" style="margin:10px 0 0;font-weight:700;color:#0b2f71;">Result: -</p>
        <p style="margin:8px 0 0;font-size:12px;color:#94a3b8;">Rates are indicative static rates for UI estimation.</p>
    </div>

    <script>
        (function () {
            var rates = { INR: 1, USD: 83.0, EUR: 90.0, GBP: 105.0 };
            var amount = document.getElementById('cc-amount');
            var from = document.getElementById('cc-from');
            var to = document.getElementById('cc-to');
            var button = document.getElementById('cc-convert');
            var result = document.getElementById('cc-result');
            if (!amount || !from || !to || !button || !result) return;
            button.addEventListener('click', function () {
                var value = Number(amount.value || 0);
                if (value < 0) value = 0;
                var fromRate = rates[from.value] || 1;
                var toRate = rates[to.value] || 1;
                var inr = value * fromRate;
                var converted = inr / toRate;
                result.textContent = 'Result: ' + converted.toFixed(2) + ' ' + to.value;
            });
        })();
    </script>
@endsection

