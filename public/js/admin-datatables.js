(function () {
    'use strict';

    function shouldInitTable(table) {
        if (!table || !table.tHead) {
            return false;
        }
        if (table.dataset.adminDt === 'off') {
            return false;
        }
        if (table.classList.contains('dataTable') || table.closest('.dt-container')) {
            return false;
        }
        if (table.querySelector('tbody form')) {
            return false;
        }
        if (table.querySelector('tbody input, tbody select, tbody textarea')) {
            return false;
        }

        return true;
    }

    function actionColumnIndex(table) {
        var sample = table.querySelector('tbody tr:not(.dataTables_empty)');
        if (sample && sample.lastElementChild && sample.lastElementChild.classList.contains('admin-table-actions')) {
            return sample.cells.length - 1;
        }

        return null;
    }

    function initAdminDataTables() {
        if (typeof DataTable === 'undefined') {
            return;
        }

        document.querySelectorAll('table.admin-table').forEach(function (table) {
            if (!shouldInitTable(table)) {
                return;
            }

            var actionCol = actionColumnIndex(table);
            var columnDefs = [];

            if (actionCol !== null) {
                columnDefs.push({
                    targets: actionCol,
                    orderable: false,
                    searchable: false,
                });
            }

            var scrollWrap = table.closest('.admin-table-scroll');
            if (scrollWrap) {
                scrollWrap.classList.add('admin-table-scroll--dt');
            }

            new DataTable(table, {
                pageLength: 25,
                lengthMenu: [[10, 25, 50, 100, 250, -1], [10, 25, 50, 100, 250, 'All']],
                order: [],
                autoWidth: false,
                layout: {
                    topStart: 'pageLength',
                    topEnd: 'search',
                    bottomStart: 'info',
                    bottomEnd: 'paging',
                },
                language: {
                    search: '',
                    searchPlaceholder: 'Search table…',
                    lengthMenu: 'Show _MENU_',
                    info: '_START_–_END_ of _TOTAL_',
                    infoEmpty: 'No rows',
                    infoFiltered: '(filtered from _MAX_)',
                    zeroRecords: 'No matching rows',
                    paginate: {
                        first: 'First',
                        last: 'Last',
                        next: 'Next',
                        previous: 'Prev',
                    },
                },
                columnDefs: columnDefs,
            });
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAdminDataTables);
    } else {
        initAdminDataTables();
    }
})();
