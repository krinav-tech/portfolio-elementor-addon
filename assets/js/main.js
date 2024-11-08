document.addEventListener('DOMContentLoaded', function () {
    const verticalButton = document.querySelector('.sector button');
    const regionButton = document.querySelector('.region button');
    const statusButton = document.querySelector('.status button');
    const activeFiltersDesktop = document.querySelector('.active-filters-desktop');
    const searchInput = document.querySelector('#company-search input[name="name"]');
    const loadMoreBtn = document.querySelector('.load-more-btn');

    // const filterHtml = generateSectorFilters();
    // const filterContainer = document.querySelector('.test-filters');
    // if (filterContainer) {
    //     filterContainer.innerHTML = filterHtml;
    // } else {
    //     console.log('No .test-filters element found on the page.');
    // }


    let activeFilters = {
        'sector': [],
        'region': [],
        'status': []
    };

    function generateSectorFilters() {
        let html = '<div class="sector-filters">';
        Object.keys(portfolioData.sectorData).forEach(sectorValue => {
            html += `<button class="filter-option" data-filter="sector" data-value="${sectorValue}">${portfolioData.sectorData[sectorValue]}</button>`;
        });
        html += '</div>';
        return html;
    }

    function generateRegionFilters() {
        let html = '<div class="region-filters">';
        Object.keys(portfolioData.regionData).forEach(value => {
            html += `<button class="filter-option" data-filter="region" data-value="${value}">${portfolioData.regionData[value]}</button>`;
        });
        html += '</div>';
        return html;
    }

    function generateStatusFilters() {
        let html = '<div class="status-filters">';
        Object.keys(portfolioData.statusData).forEach(value => {
            html += `<button class="filter-option" data-filter="status" data-value="${value}">${portfolioData.statusData[value]}</button>`;
        });
        html += '</div>';
        return html;
    }


    verticalButton.addEventListener('click', function () {
        toggleFilters('sector',generateSectorFilters());
    });

    regionButton.addEventListener('click', function () {

        toggleFilters('region',generateRegionFilters());
    });

    statusButton.addEventListener('click', function () {
        toggleFilters('status',generateStatusFilters());
    });

    function addResetButton() {
        const filterList = document.querySelector('.filter-list');
        const resetButtonHtml = `<li class="filter-reset"><label class="cursor-pointer flex items-start filter-reset"><span class="text-e15 uppercase font-semibold">X&nbsp;reset</span></label></li>`;
        filterList.insertAdjacentHTML('beforeend', resetButtonHtml);
        document.querySelector('.result-count').innerHTML ="";
    }

    function toggleFilters(filterType, filtersHtml) {
        console.log("Toggling Filters for: ", filterType);
        const existingFilters = activeFiltersDesktop.querySelector(`.${filterType}-filters`);

        console.log("filter: ", existingFilters);

        console.log("filter: ", `.${filterType}-filters`);


        // Clear the existing filters if any are displayed
        if (existingFilters) {
            activeFiltersDesktop.innerHTML = ''; // This clears any displayed filters
            activeFilters[filterType] = []; // Resets the filter list in activeFilters
            console.log("unload Filters for: ", filterType);
        } else {
            console.log("load Filters for: ", filterType);
            activeFiltersDesktop.innerHTML = filtersHtml; // Adds new filters if none are displayed
            document.querySelectorAll('.filter-option').forEach(button => {
                button.addEventListener('click', function () {
                    const filter = this.dataset.filter;
                    const value = this.dataset.value;
                    // Toggle the filter in the array
                    const index = activeFilters[filter].indexOf(value);
                    if (index > -1) {
                        activeFilters[filter].splice(index, 1);
                        this.classList.remove('active');
                        // Remove the span if it exists
                        const span = this.querySelector('span');
                        if (span) {
                            span.remove();
                        }
                    } else {
                        activeFilters[filter].push(value);
                        this.classList.add('active');
                        // Add the span inside the button
                        const span = document.createElement('span');
                        span.innerHTML = '&nbsp;×</span>';
                        this.appendChild(span);
                    }

                    // Check if the reset button needs to be added
                    if (!document.querySelector('.filter-reset')) {
                        addResetButton();
                    }

                    pageNumber = 1; // Reset to first page
                    fetchFilteredPosts(activeFilters, searchInput.value, pageNumber);
                });
            });
        }
    }


    function getSiteName(url) {
        const pathArray = url.pathname.split('/');
        return pathArray[1]; // Assuming the site name is the first part of the path
    }

    // function getSiteName(url) {
    //     return url.hostname; // Return only the hostname
    // }

    const siteName = getSiteName(window.location);

    function fetchFilteredPosts(filters, searchValue = '', page = 1) {
        const data = {
            'action': 'fetch_filtered_posts',
            'vertical': filters['sector'],
            'region': filters['region'],
            'status': filters['status'],
            'search': searchValue,
            'page': page
        };

        // Check if any filters or search term are set
        const hasActiveFilters = filters['sector'].length > 0 || filters['region'].length > 0 || filters['status'].length > 0 || searchValue.trim() !== '';

        fetch('/' + getSiteName(window.location) + '/wp-admin/admin-ajax.php', {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: new URLSearchParams(data)
        })
        .then(response => response.json())
        .then(data => {
            if (page === 1) {
                document.querySelector('#result-container .row-flex').innerHTML = data.posts_html; // Update the posts
            } else {
                document.querySelector('#result-container .row-flex').insertAdjacentHTML('beforeend', data.posts_html); // Append more posts
            }

            // Only update the result count if there are active filters
            if (hasActiveFilters) {
                document.querySelector('.result-count').innerHTML = data.total_results + ' results found';
            } else {
                document.querySelector('.result-count').innerHTML = ''; // Optionally clear or leave unchanged
            }

            loadMoreBtn.style.display = data.has_more ? 'block' : 'none';
        })
        .catch(error => {
            console.error('Error:', error);
            document.querySelector('.result-count').innerHTML = "Error fetching results.";
        });
    }


    function removeResetButton() {
        const filterReset = document.querySelector('.filter-reset');
        if (filterReset) {
            filterReset.remove();
        }
    }

    document.addEventListener('click', function(event) {
        if (event.target.closest('.filter-reset')) {
            activeFilters = {
                'sector': [],
                'region': [],
                'status': []
            };
            activeFiltersDesktop.innerHTML = '';
            document.querySelector('.result-count').innerHTML = "";
            fetchFilteredPosts(activeFilters);
            removeResetButton();

        }
    });


    searchInput.addEventListener('keyup', function() {
        pageNumber = 1; // Reset pagination to the first page
        fetchFilteredPosts(activeFilters, this.value, pageNumber);
    });


    pageNumber = 1;
    loadMoreBtn.addEventListener('click', function () {

        pageNumber++; // Increment the page number
        fetchFilteredPosts(activeFilters, searchInput.value, pageNumber);
    });

});

document.querySelectorAll('.filter-list li button').forEach(button => {
    button.addEventListener('click', function() {
        this.classList.toggle('active');
    });
});
