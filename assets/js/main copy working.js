document.addEventListener('DOMContentLoaded', function () {
    const verticalButton = document.querySelector('.sector button');
    const regionButton = document.querySelector('.region button');
    const statusButton = document.querySelector('.status button');
    const activeFiltersDesktop = document.querySelector('.active-filters-desktop');
    const searchInput = document.querySelector('#company-search input[name="name"]');
    const loadMoreBtn = document.querySelector('.load-more-btn');


    let activeFilters = {
        'sector': [],
        'region': [],
        'status': []
    };

    verticalButton.addEventListener('click', function () {
        toggleFilters('sector', `
        <div class="sector-filters">
            <button class="filter-option" data-filter="sector" data-value="AI/ML + Data">AI / ML + Data</button>
            <button class="filter-option" data-filter="sector" data-value="BioTech">BioTech</button>
            <button class="filter-option" data-filter="sector" data-value="Business Operations">Business Operations</button>
            <button class="filter-option" data-filter="sector" data-value="ClimateTech">ClimateTech</button>
            <button class="filter-option" data-filter="sector" data-value="Consumer">Consumer</button>
            <button class="filter-option" data-filter="sector" data-value="Cybersecurity">Cybersecurity</button>
            <button class="filter-option" data-filter="sector" data-value="DevOps">DevOps</button>
            <button class="filter-option" data-filter="sector" data-value="eCommerce">eCommerce</button>
            <button class="filter-option" data-filter="sector" data-value="EdTech">EdTech</button>
            <button class="filter-option" data-filter="sector" data-value="Fintech">Fintech</button>
            <button class="filter-option" data-filter="sector" data-value="Future of Work">Future of Work</button>
            <button class="filter-option" data-filter="sector" data-value="Gaming">Gaming</button>
            <button class="filter-option" data-filter="sector" data-value="GovTech">GovTech</button>
            <button class="filter-option" data-filter="sector" data-value="GTM Tech">GTM Tech</button>
            <button class="filter-option" data-filter="sector" data-value="HealthTech">HealthTech</button>
            <button class="filter-option" data-filter="sector" data-value="Horizontal SaaS">Horizontal SaaS</button>
            <button class="filter-option" data-filter="sector" data-value="HR Tech">HR Tech</button>
            <button class="filter-option" data-filter="sector" data-value="IT Infrastructure">IT Infrastructure</button>
            <button class="filter-option" data-filter="sector" data-value="LegalTech">LegalTech</button>
            <button class="filter-option" data-filter="sector" data-value="Life Sciences">Life Sciences</button>
            <button class="filter-option" data-filter="sector" data-value="Logistics / Supply Chain">Logistics / Supply Chain</button>
            <button class="filter-option" data-filter="sector" data-value="Other Vertical SaaS">Other Vertical SaaS</button>
            <button class="filter-option" data-filter="sector" data-value="PropertyTech">PropertyTech</button>
            <button class="filter-option" data-filter="sector" data-value="Web3">Web3</button>
        </div>

        `);
    });

    regionButton.addEventListener('click', function () {

        toggleFilters('region', `
        <div class="region-filters">
            <button class="filter-option" data-filter="region" data-value="North America">North America</button>
            <button class="filter-option" data-filter="region" data-value="Africa">Africa</button>
            <button class="filter-option" data-filter="region" data-value="Western Europe">Western Europe</button>
            <button class="filter-option" data-filter="region" data-value="Eastern Europe">Eastern Europe</button>
            <button class="filter-option" data-filter="region" data-value="Asia Pacific">Asia Pacific</button>
            <button class="filter-option" data-filter="region" data-value="Israel">Israel</button>
            <button class="filter-option" data-filter="region" data-value="South America">South America</button>
        </div>

        `);
    });

    statusButton.addEventListener('click', function () {
        toggleFilters('status', `
            <div class="status-filters">
                <button class="filter-option" data-filter="status" data-value="Current Investment">Current Investment</button>
                <button class="filter-option" data-filter="status" data-value="Prior Investment">Prior Investment</button>
            </div>
        `);
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
