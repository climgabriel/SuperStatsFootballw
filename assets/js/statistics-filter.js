/**
 * Statistics Filter Functionality - Enhanced with validation
 *
 * Features:
 * - League selection with user limits (5 for users, 10 for admins)
 * - Real-time validation
 * - Date range filtering
 * - URL parameter management
 */

document.addEventListener('DOMContentLoaded', function() {
    // Get filter elements
    const applyFiltersBtn = document.getElementById('applyFilters');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const filterModal = document.getElementById('filterModal');
    const leagueCheckboxes = document.querySelectorAll('.filter-league');

    if (!applyFiltersBtn) return; // Exit if no filter button found

    // Get user limits from modal data attributes (set by PHP)
    const maxLeagues = parseInt(filterModal?.dataset.maxLeagues || '5');
    const userRole = filterModal?.dataset.userRole || 'user';

    // Initialize
    updateLimitInfo();
    loadFiltersFromURL();

    // Add real-time validation for league selection
    leagueCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            validateLeagueSelection();
        });
    });

    // ===== SELECT ALL / DESELECT ALL FUNCTIONALITY =====

    // Leagues Select All/Deselect All
    const selectAllLeagues = document.getElementById('selectAllLeagues');
    const deselectAllLeagues = document.getElementById('deselectAllLeagues');

    if (selectAllLeagues) {
        selectAllLeagues.addEventListener('click', function() {
            document.querySelectorAll('.filter-league').forEach(cb => cb.checked = true);
            validateLeagueSelection();
            updateSelectedCount();
        });
    }

    if (deselectAllLeagues) {
        deselectAllLeagues.addEventListener('click', function() {
            document.querySelectorAll('.filter-league').forEach(cb => cb.checked = false);
            validateLeagueSelection();
            updateSelectedCount();
        });
    }

    // Seasons Select All/Deselect All
    const selectAllSeasons = document.getElementById('selectAllSeasons');
    const deselectAllSeasons = document.getElementById('deselectAllSeasons');

    if (selectAllSeasons) {
        selectAllSeasons.addEventListener('click', function() {
            document.querySelectorAll('.filter-season').forEach(cb => cb.checked = true);
        });
    }

    if (deselectAllSeasons) {
        deselectAllSeasons.addEventListener('click', function() {
            document.querySelectorAll('.filter-season').forEach(cb => cb.checked = false);
        });
    }

    // Models Select All/Deselect All
    const selectAllModels = document.getElementById('selectAllModels');
    const deselectAllModels = document.getElementById('deselectAllModels');

    if (selectAllModels) {
        selectAllModels.addEventListener('click', function() {
            document.querySelectorAll('.filter-model').forEach(cb => cb.checked = true);
        });
    }

    if (deselectAllModels) {
        deselectAllModels.addEventListener('click', function() {
            document.querySelectorAll('.filter-model').forEach(cb => cb.checked = false);
        });
    }

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
     * Get selected days ahead value
     */
    function getDaysAhead() {
        const selected = document.querySelector('.filter-days:checked');
        return selected ? selected.value : '7'; // Default to 7 days
    }

    /**
     * Get selected seasons
     */
    function getSelectedSeasons() {
        const checkboxes = document.querySelectorAll('.filter-season:checked');
        const seasons = [];
        checkboxes.forEach(cb => seasons.push(cb.value));
        return seasons;
    }

    /**
     * Get selected analytics models
     */
    function getSelectedModels() {
        const checkboxes = document.querySelectorAll('.filter-model:checked');
        const models = [];
        checkboxes.forEach(cb => models.push(cb.value));
        return models;
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
     * Validate league selection against user limits
     */
    function validateLeagueSelection() {
        const selectedLeagues = getSelectedLeagues();
        const count = selectedLeagues.length;

        // Remove any existing error message
        removeErrorMessage();

        if (count > maxLeagues) {
            showErrorMessage(
                `You can select a maximum of ${maxLeagues} leagues. ` +
                `Currently selected: ${count}. Please uncheck ${count - maxLeagues} league(s).`
            );
            applyFiltersBtn.disabled = true;
            applyFiltersBtn.classList.add('disabled');
            return false;
        }

        applyFiltersBtn.disabled = false;
        applyFiltersBtn.classList.remove('disabled');
        return true;
    }

    /**
     * Show error message in modal
     */
    function showErrorMessage(message) {
        removeErrorMessage();

        const modalBody = document.querySelector('#filterModal .modal-body');
        if (modalBody) {
            const alert = document.createElement('div');
            alert.className = 'alert alert-warning alert-dismissible fade show';
            alert.id = 'league-limit-error';
            alert.role = 'alert';
            alert.innerHTML = `
                <i class="bx bx-error-circle me-2"></i>
                <strong>Selection Limit:</strong> ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            `;
            modalBody.insertBefore(alert, modalBody.firstChild);
        }
    }

    /**
     * Remove error message
     */
    function removeErrorMessage() {
        const existingError = document.getElementById('league-limit-error');
        if (existingError) {
            existingError.remove();
        }
    }

    /**
     * Update limit information in modal header
     */
    function updateLimitInfo() {
        const modalTitle = document.querySelector('#filterModal .modal-title');
        if (modalTitle && !modalTitle.querySelector('.league-limit-badge')) {
            const limitBadge = document.createElement('span');
            limitBadge.className = 'badge bg-info ms-2 league-limit-badge';
            limitBadge.style.fontSize = '0.75rem';
            limitBadge.style.fontWeight = '400';
            limitBadge.textContent = `Max: ${maxLeagues} leagues`;
            limitBadge.title = userRole === 'admin' ? 'Admin limit' : 'User limit';
            modalTitle.appendChild(limitBadge);
        }
    }

    /**
     * Apply filters and reload page with parameters
     */
    applyFiltersBtn.addEventListener('click', function() {
        const selectedLeagues = getSelectedLeagues();

        // Validate before applying
        if (!validateLeagueSelection()) {
            return; // Stop if validation fails
        }

        const daysAhead = getDaysAhead();
        const selectedSeasons = getSelectedSeasons();
        const selectedModels = getSelectedModels();
        const dateRange = getDateRange();

        // Build query parameters
        const params = new URLSearchParams();

        // Add days ahead
        if (daysAhead) {
            params.append('days', daysAhead);
        }

        // Add leagues
        if (selectedLeagues.length > 0) {
            params.append('leagues', selectedLeagues.join(','));
        }

        // Add seasons
        if (selectedSeasons.length > 0) {
            params.append('seasons', selectedSeasons.join(','));
        }

        // Add analytics models
        if (selectedModels.length > 0) {
            params.append('models', selectedModels.join(','));
        }

        // Add date range (if still needed)
        if (dateRange.from) {
            params.append('date_from', dateRange.from);
        }

        if (dateRange.to) {
            params.append('date_to', dateRange.to);
        }

        // Show loading state
        applyFiltersBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
        applyFiltersBtn.disabled = true;

        // Reload page with filters
        const queryString = params.toString();
        window.location.href = queryString ? `?${queryString}` : window.location.pathname;
    });

    /**
     * Clear all filters
     */
    if (clearFiltersBtn) {
        clearFiltersBtn.addEventListener('click', function() {
            // Reset days ahead to default (7 days)
            const days7 = document.getElementById('days7');
            if (days7) days7.checked = true;

            // Uncheck all league checkboxes
            document.querySelectorAll('.filter-league').forEach(cb => {
                cb.checked = false;
            });

            // Uncheck all season checkboxes
            document.querySelectorAll('.filter-season').forEach(cb => {
                cb.checked = false;
            });

            // Uncheck all model checkboxes
            document.querySelectorAll('.filter-model').forEach(cb => {
                cb.checked = false;
            });

            // Clear date inputs
            const dateFrom = document.getElementById('dateFrom');
            const dateTo = document.getElementById('dateTo');
            if (dateFrom) dateFrom.value = '';
            if (dateTo) dateTo.value = '';

            // Remove error messages
            removeErrorMessage();

            // Re-enable apply button
            applyFiltersBtn.disabled = false;
            applyFiltersBtn.classList.remove('disabled');

            // Reload page without parameters
            window.location.href = window.location.pathname;
        });
    }

    /**
     * Load current filters from URL parameters
     */
    function loadFiltersFromURL() {
        const params = new URLSearchParams(window.location.search);

        // Load days ahead
        const days = params.get('days');
        if (days) {
            const daysRadio = document.querySelector(`.filter-days[value="${days}"]`);
            if (daysRadio) daysRadio.checked = true;
        }

        // Load leagues
        const leagues = params.get('leagues');
        if (leagues) {
            const leagueList = leagues.split(',');
            leagueList.forEach(league => {
                const checkbox = document.querySelector(`.filter-league[value="${CSS.escape(league)}"]`);
                if (checkbox) checkbox.checked = true;
            });

            // Validate after loading
            validateLeagueSelection();
        }

        // Load seasons
        const seasons = params.get('seasons');
        if (seasons) {
            const seasonList = seasons.split(',');
            seasonList.forEach(season => {
                const checkbox = document.querySelector(`.filter-season[value="${season}"]`);
                if (checkbox) checkbox.checked = true;
            });
        }

        // Load analytics models
        const models = params.get('models');
        if (models) {
            const modelList = models.split(',');
            modelList.forEach(model => {
                const checkbox = document.querySelector(`.filter-model[value="${CSS.escape(model)}"]`);
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

    /**
     * Close modal on apply
     */
    if (filterModal && applyFiltersBtn) {
        const originalClickHandler = applyFiltersBtn.onclick;
        applyFiltersBtn.addEventListener('click', function() {
            if (!applyFiltersBtn.disabled) {
                const modalInstance = bootstrap.Modal.getInstance(filterModal);
                if (modalInstance) {
                    modalInstance.hide();
                }
            }
        });
    }

    /**
     * Show selected count in real-time
     */
    function updateSelectedCount() {
        const count = getSelectedLeagues().length;
        let countBadge = document.getElementById('selected-count-badge');

        if (!countBadge) {
            const leaguesSection = document.querySelector('#filterModal .modal-body .mb-4 label');
            if (leaguesSection) {
                countBadge = document.createElement('span');
                countBadge.id = 'selected-count-badge';
                countBadge.className = 'badge bg-secondary ms-2';
                countBadge.style.fontSize = '0.75rem';
                leaguesSection.appendChild(countBadge);
            }
        }

        if (countBadge) {
            countBadge.textContent = `${count} selected`;
            countBadge.className = count > maxLeagues
                ? 'badge bg-danger ms-2'
                : 'badge bg-secondary ms-2';
            countBadge.style.fontSize = '0.75rem';
        }
    }

    // Update count on checkbox change
    leagueCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSelectedCount);
    });

    // Initial count update
    updateSelectedCount();
});
