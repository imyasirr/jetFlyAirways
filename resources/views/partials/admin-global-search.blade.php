<div class="admin-global-search" data-admin-search>
    <label class="admin-global-search__field">
        <span class="material-symbols-outlined" aria-hidden="true">search</span>
        <input
            type="search"
            class="admin-global-search__input"
            data-admin-search-input
            placeholder="Search admin…"
            autocomplete="off"
            spellcheck="false"
            aria-label="Search admin panel"
            aria-expanded="false"
            aria-controls="admin-search-results"
            aria-autocomplete="list"
            role="combobox"
        >
        <kbd class="admin-global-search__hint" aria-hidden="true">Ctrl K</kbd>
    </label>

    <div class="admin-global-search__panel" id="admin-search-results" data-admin-search-panel hidden>
        <div class="admin-global-search__status" data-admin-search-status>Type at least 2 characters…</div>
        <div class="admin-global-search__results" data-admin-search-results role="listbox"></div>
    </div>
</div>

@once
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var root = document.querySelector('[data-admin-search]');
        if (!root) return;

        var input = root.querySelector('[data-admin-search-input]');
        var panel = root.querySelector('[data-admin-search-panel]');
        var status = root.querySelector('[data-admin-search-status]');
        var results = root.querySelector('[data-admin-search-results]');
        var endpoint = @json(route('admin.search'));
        var timer = null;
        var activeIndex = -1;
        var flatItems = [];

        function openPanel() {
            panel.hidden = false;
            input.setAttribute('aria-expanded', 'true');
        }

        function closePanel() {
            panel.hidden = true;
            input.setAttribute('aria-expanded', 'false');
            activeIndex = -1;
            flatItems = [];
        }

        function setActive(index) {
            var links = results.querySelectorAll('[data-admin-search-item]');
            links.forEach(function (link, i) {
                link.classList.toggle('is-active', i === index);
                if (i === index) {
                    link.setAttribute('aria-selected', 'true');
                    link.scrollIntoView({ block: 'nearest' });
                } else {
                    link.setAttribute('aria-selected', 'false');
                }
            });
            activeIndex = index;
        }

        function render(data) {
            flatItems = [];
            results.innerHTML = '';

            if (!data.groups || data.groups.length === 0) {
                status.textContent = data.query && data.query.length >= 2
                    ? 'No results for “' + data.query + '”.'
                    : 'Type at least 2 characters…';
                status.hidden = false;
                return;
            }

            status.hidden = true;
            data.groups.forEach(function (group) {
                var section = document.createElement('div');
                section.className = 'admin-global-search__group';

                var heading = document.createElement('div');
                heading.className = 'admin-global-search__group-label';
                heading.textContent = group.label;
                section.appendChild(heading);

                group.items.forEach(function (item) {
                    var link = document.createElement('a');
                    link.href = item.url;
                    link.className = 'admin-global-search__item';
                    link.setAttribute('data-admin-search-item', '');
                    link.setAttribute('role', 'option');
                    link.innerHTML =
                        '<span class="admin-global-search__item-icon"><span class="material-symbols-outlined">' +
                            (item.icon || 'search') +
                        '</span></span>' +
                        '<span class="admin-global-search__item-body">' +
                            '<strong>' + escapeHtml(item.title || '') + '</strong>' +
                            '<span>' + escapeHtml(item.subtitle || '') + '</span>' +
                        '</span>';

                    section.appendChild(link);
                    flatItems.push(link);
                });

                results.appendChild(section);
            });
        }

        function escapeHtml(value) {
            return String(value)
                .replace(/&/g, '&amp;')
                .replace(/</g, '&lt;')
                .replace(/>/g, '&gt;')
                .replace(/"/g, '&quot;');
        }

        function fetchResults(query) {
            fetch(endpoint + '?q=' + encodeURIComponent(query), {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            })
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    if (input.value.trim() !== query) return;
                    render(data);
                    openPanel();
                })
                .catch(function () {
                    status.textContent = 'Search failed. Try again.';
                    status.hidden = false;
                    openPanel();
                });
        }

        input.addEventListener('input', function () {
            clearTimeout(timer);
            var query = input.value.trim();
            activeIndex = -1;

            if (query.length < 2) {
                render({ query: query, groups: [] });
                openPanel();
                return;
            }

            timer = setTimeout(function () {
                fetchResults(query);
            }, 220);
        });

        input.addEventListener('focus', function () {
            openPanel();
            if (input.value.trim().length >= 2 && flatItems.length === 0) {
                fetchResults(input.value.trim());
            }
        });

        input.addEventListener('keydown', function (event) {
            if (event.key === 'Escape') {
                closePanel();
                input.blur();
                return;
            }

            if (!flatItems.length) return;

            if (event.key === 'ArrowDown') {
                event.preventDefault();
                setActive(Math.min(activeIndex + 1, flatItems.length - 1));
            } else if (event.key === 'ArrowUp') {
                event.preventDefault();
                setActive(Math.max(activeIndex - 1, 0));
            } else if (event.key === 'Enter' && activeIndex >= 0) {
                event.preventDefault();
                window.location.href = flatItems[activeIndex].href;
            }
        });

        document.addEventListener('click', function (event) {
            if (!root.contains(event.target)) {
                closePanel();
            }
        });

        document.addEventListener('keydown', function (event) {
            if ((event.ctrlKey || event.metaKey) && event.key.toLowerCase() === 'k') {
                event.preventDefault();
                input.focus();
                input.select();
                openPanel();
            }
        });
    });
    </script>
    @endpush
@endonce
