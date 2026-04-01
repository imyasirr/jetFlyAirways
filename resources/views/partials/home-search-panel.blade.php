<div class="search-shell container" id="search">
    <div class="card search-panel">
        <div class="search-panel-head">
            <h2>Search &amp; book</h2>
            <p>Choose a service — live inventory from our database.</p>
        </div>
        <details open class="search-details">
            <summary>✈ Flights — From / To / Date</summary>
            <div class="search-body">
                <form method="get" action="{{ route('module.index', 'flights') }}" class="search-grid">
                    <div><label>From</label><input name="from" placeholder="City" value="{{ request('from') }}"></div>
                    <div><label>To</label><input name="to" placeholder="City" value="{{ request('to') }}"></div>
                    <div><label>Departure</label><input type="date" name="date" value="{{ request('date') }}"></div>
                    <div><button class="btn" type="submit">Search flights</button></div>
                </form>
            </div>
        </details>
        <details class="search-details">
            <summary>🏨 Hotels — City</summary>
            <div class="search-body">
                <form method="get" action="{{ route('module.index', 'hotels') }}" class="search-grid">
                    <div><label>City</label><input name="city" placeholder="e.g. Mumbai" value="{{ request('city') }}"></div>
                    <div><label>Name (optional)</label><input name="q" placeholder="Hotel name" value="{{ request('q') }}"></div>
                    <div><button class="btn secondary" type="submit">Search hotels</button></div>
                </form>
            </div>
        </details>
        <details class="search-details">
            <summary>🌍 Holiday packages</summary>
            <div class="search-body">
                <form method="get" action="{{ route('module.index', 'packages') }}" class="search-grid">
                    <div><label>Destination</label><input name="destination" placeholder="Place" value="{{ request('destination') }}"></div>
                    <div><label>Category</label><input name="category" placeholder="Honeymoon, Family…" value="{{ request('category') }}"></div>
                    <div><label>Keyword</label><input name="q" placeholder="Search name" value="{{ request('q') }}"></div>
                    <div><button class="btn secondary" type="submit">Search packages</button></div>
                </form>
            </div>
        </details>
    </div>
</div>
