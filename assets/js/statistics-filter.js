/**
 * Statistics Filter Functionality
 *
 * Handles filter interactions on statistics pages
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get filter elements
    const applyFiltersBtn = document.getElementById('applyFilters');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const filterModal = document.getElementById('filterModal');

    if (!applyFiltersBtn) return; // Exit if no filter button found

    /**
     * Get selected league IDs from checkboxes
     */
    function getSelectedLeagues() {
        const checkboxes = document.querySelectorAll('.filter-league:checked');
        const leagues = [];
        checkboxes.forEach(cb => leagues.push(cb.value));
        return leagues;
    }

    /**
     * Get date range values
     */
    function getDateRange() {
        return {
            from: document.getElementById('dateFrom')?.value || '',
            to: document.getElementById('dateTo')?.value || ''
        };
    }

    /**
     * Apply filters and reload page with parameters
     */
    applyFiltersBtn.addEventListener('click', function() {
        const selectedLeagues = getSelectedLeagues();
        const dateRange = getDateRange();

        // Build query parameters
        const params = new URLSearchParams();

        if (selectedLeagues.length > 0) {
            params.append('leagues', selectedLeagues.join(','));
        }

        if (dateRange.from) {
            params.append('date_from', dateRange.from);
        }

        if (dateRange.to) {
            params.append('date_to', dateRange.to);
        }

        // Reload page with filters
        const queryString = params.toString();
        window.location.href = queryString ? `?${queryString}` : window.location.pathname;
    });

    /**
     * Clear all filters
     */
    clearFiltersBtn.addEventListener('click', function() {
        // Uncheck all league checkboxes
        document.querySelectorAll('.filter-league').forEach(cb => {
            cb.checked = false;
        });

        // Clear date inputs
        const dateFrom = document.getElementById('dateFrom');
        const dateTo = document.getElementById('dateTo');
        if (dateFrom) dateFrom.value = '';
        if (dateTo) dateTo.value = '';

        // Reload page without parameters
        window.location.href = window.location.pathname;
    });

    /**
     * Load current filters from URL parameters
     */
    function loadFiltersFromURL() {
        const params = new URLSearchParams(window.location.search);

        // Load leagues
        const leagues = params.get('leagues');
        if (leagues) {
            const leagueList = leagues.split(',');
            leagueList.forEach(league => {
                const checkbox = document.querySelector(`.filter-league[value="${league}"]`);
                if (checkbox) checkbox.checked = true;
            });
        }

        // Load dates
        const dateFrom = params.get('date_from');
        const dateTo = params.get('date_to');
        if (dateFrom && document.getElementById('dateFrom')) {
            document.getElementById('dateFrom').value = dateFrom;
        }
        if (dateTo && document.getElementById('dateTo')) {
            document.getElementById('dateTo').value = dateTo;
        }
    }

    // Initialize filters from URL on page load
    loadFiltersFromURL();

    /**
     * Close modal on apply
     */
    if (filterModal && applyFiltersBtn) {
        applyFiltersBtn.addEventListener('click', function() {
            const modalInstance = bootstrap.Modal.getInstance(filterModal);
            if (modalInstance) {
                modalInstance.hide();
            }
        });
    }
});
