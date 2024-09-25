document.addEventListener('DOMContentLoaded', () => {
    const searchContainer = document.querySelector('.search-btn');

    if (!searchContainer) {
        throw new Error('44fdb4d3-e848-48e5-b443-b6f3d6f5a5ef');
    }

    const input = searchContainer.querySelector('.search-input');
    const button = searchContainer.querySelector('button');

    if (!input || !button) {
        throw new Error('d62f095e-ef52-45cd-ae6a-0aa8c28f4624');
    }

    const resultsContainer = searchContainer.querySelector('.search-results-container');

    async function fetchSearchResults(query) {
        const baseUrl = window.location.origin;
        const locale = document.querySelector('html').getAttribute('lang');
        const url = `${baseUrl}/${locale}/search?q=${encodeURIComponent(query)}`;
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    }

    function displayResults(data) {
        resultsContainer.innerHTML = '';

        data.forEach((item) => {
            const resultItem = document.createElement('div');
            resultItem.classList.add('search-result-item');

            if (item.type === 'product') {
                resultItem.innerHTML = `
                    <a href="${item.link}">
                        <div class="main">
                            <img src="${item.photo}" alt="Product photo" class="search-result-photo">
                            <span class="title">${item.title}</span>
                        </div>
                        <div class="label"> 
                            ${item.is_new ? '<span class="badge-new badge">New</span>' : ''}
                            ${item.is_top ? '<span class="badge-top badge">Top</span>' : ''}
                        </div>
                    </a>
                `;
            } else if (item.type === 'category') {
                resultItem.innerHTML = `
                    <a href="${item.link}">
                        <div class="main">
                            <i class="icon ${item.icon}"></i>
                            <span class="title">${item.title}</span>
                        </div>
                        <div class="label"> 
                             <span class="badge-count badge">x${item.product_count}</span>
                        </div>
                    </a>
                `;
            }

            resultsContainer.appendChild(resultItem);
        });
    }

    const performSearch = async () => {
        const query = input.value.trim();

        if (query === '') {
            return;
        }

        try {
            const data = await fetchSearchResults(query);

            if (data.length > 0) {
                displayResults(data);
                resultsContainer.style.display = 'block';
            } else {
                resultsContainer.innerHTML = `
                    <div class="not-found-container">
                        <img class="not-found" alt="not found image from freepik.com" src="/build/images/not-found.jpg"/>
                        <a href="https://www.freepik.com/" target="_blank">Designed by Freepik</a>
                     </div>
                `;
                resultsContainer.style.display = 'block';
            }
        } catch (error) {
            resultsContainer.style.display = 'none';

            throw new Error('8169ab69-1477-4abd-8af5-d93c9fc1b1eb');
        }
    };

    button.addEventListener('click', async () => {
        await performSearch();
    });

    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            performSearch();
        }
    });

    document.addEventListener('click', (e) => {
        if (!searchContainer.contains(e.target) && resultsContainer.style.display === 'block') {
            resultsContainer.style.display = 'none';
        }
    });
});
