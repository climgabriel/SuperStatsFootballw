/**
 * 1X2 Statistics Table Filter
 * Handles filtering of table rows based on League, Season, and Analytics Model using checkboxes
 */

(function() {
  'use strict';

  // Filter state
  let activeFilters = {
    leagues: [],
    seasons: [],
    models: []
  };

  // Initialize on DOM ready
  document.addEventListener('DOMContentLoaded', function() {
    initializeFilters();
  });

  function initializeFilters() {
    const applyBtn = document.getElementById('applyFilters');
    const clearBtn = document.getElementById('clearFilters');

    if (applyBtn) {
      applyBtn.addEventListener('click', applyFilters);
    }

    if (clearBtn) {
      clearBtn.addEventListener('click', clearAllFilters);
    }
  }

  function applyFilters() {
    // Get checked leagues
    const leagueCheckboxes = document.querySelectorAll('.filter-league:checked');
    activeFilters.leagues = Array.from(leagueCheckboxes).map(cb => cb.value);

    // Get checked seasons
    const seasonCheckboxes = document.querySelectorAll('.filter-season:checked');
    activeFilters.seasons = Array.from(seasonCheckboxes).map(cb => cb.value);

    // Get checked models
    const modelCheckboxes = document.querySelectorAll('.filter-model:checked');
    activeFilters.models = Array.from(modelCheckboxes).map(cb => cb.value);

    // Filter table rows
    filterTableRows();

    // Update filter button to show active filters
    updateFilterButton();

    // Close modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('filterModal'));
    if (modal) {
      modal.hide();
    }
  }

  function filterTableRows() {
    const tbody = document.querySelector('.stats-table tbody');
    if (!tbody) return;

    const rows = tbody.querySelectorAll('tr:not(.no-results-message)');
    let visibleCount = 0;

    rows.forEach(row => {
      let shouldShow = true;

      // Check league filter
      if (activeFilters.leagues.length > 0) {
        const leagueCell = row.querySelector('td:first-child');
        if (leagueCell) {
          const leagueText = leagueCell.textContent.trim().toLowerCase();
          // Check if any selected league is contained in the text
          shouldShow = activeFilters.leagues.some(league =>
            leagueText.includes(league.toLowerCase())
          );
        }
      }

      // Season filter (placeholder - implement when season data is available in rows)
      if (activeFilters.seasons.length > 0 && shouldShow) {
        // Get season data from row attribute if available
        const seasonData = row.getAttribute('data-season');
        if (seasonData) {
          shouldShow = activeFilters.seasons.includes(seasonData);
        }
      }

      // Model filter (placeholder - implement when model data is available in rows)
      if (activeFilters.models.length > 0 && shouldShow) {
        // Get model data from row attribute if available
        const modelData = row.getAttribute('data-model');
        if (modelData) {
          shouldShow = activeFilters.models.includes(modelData);
        }
      }

      if (shouldShow) {
        row.style.display = '';
        visibleCount++;
      } else {
        row.style.display = 'none';
      }
    });

    // Show message if no results
    showNoResultsMessage(visibleCount === 0);
  }

  function showNoResultsMessage(show) {
    let messageRow = document.querySelector('.no-results-message');

    if (show) {
      if (!messageRow) {
        const tbody = document.querySelector('.stats-table tbody');
        const headCells = document.querySelectorAll('.stats-table thead tr:last-child th');
        const colspan = headCells.length;

        messageRow = document.createElement('tr');
        messageRow.className = 'no-results-message';
        messageRow.innerHTML = `<td colspan="${colspan}" class="text-center py-5">
          <div class="text-muted">
            <i class="bx bx-search-alt bx-lg" style="color: #106147;"></i>
            <p class="mt-2 mb-3">No matches found for the selected filters.</p>
            <button class="btn btn-sm" style="background-color: #106147; border-color: #106147; color: white;" onclick="document.getElementById('clearFilters').click()">
              <i class="bx bx-x me-1"></i>Clear Filters
            </button>
          </div>
        </td>`;
        tbody.appendChild(messageRow);
      }
      messageRow.style.display = '';
    } else {
      if (messageRow) {
        messageRow.style.display = 'none';
      }
    }
  }

  function clearAllFilters() {
    // Uncheck all checkboxes
    const allCheckboxes = document.querySelectorAll('.filter-league, .filter-season, .filter-model');
    allCheckboxes.forEach(checkbox => {
      checkbox.checked = false;
    });

    // Reset active filters
    activeFilters = {
      leagues: [],
      seasons: [],
      models: []
    };

    // Show all rows
    const tbody = document.querySelector('.stats-table tbody');
    if (tbody) {
      const rows = tbody.querySelectorAll('tr:not(.no-results-message)');
      rows.forEach(row => {
        row.style.display = '';
      });
    }

    // Hide no results message
    showNoResultsMessage(false);

    // Update filter button
    updateFilterButton();

    // Close modal if open
    const modal = bootstrap.Modal.getInstance(document.getElementById('filterModal'));
    if (modal) {
      modal.hide();
    }
  }

  function updateFilterButton() {
    const filterBtn = document.querySelector('[data-bs-target="#filterModal"]');
    if (!filterBtn) return;

    const totalFilters = activeFilters.leagues.length +
                        activeFilters.seasons.length +
                        activeFilters.models.length;

    if (totalFilters > 0) {
      filterBtn.innerHTML = `<i class="bx bx-filter me-1"></i> Filter (${totalFilters})`;
      filterBtn.style.backgroundColor = '#005440';
      filterBtn.style.borderColor = '#005440';
    } else {
      filterBtn.innerHTML = `<i class="bx bx-filter me-1"></i> Filter`;
      filterBtn.style.backgroundColor = '#106147';
      filterBtn.style.borderColor = '#106147';
    }
  }

  // Make clearAllFilters available globally for the no-results button
  window.clearTableFilters = clearAllFilters;

})();
