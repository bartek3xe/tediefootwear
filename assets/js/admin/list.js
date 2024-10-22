document.addEventListener('DOMContentLoaded', () => {
    const entityListId = document.getElementById('entityListId');
    const apiUrl = entityListId.getAttribute('data-api-url');
    const dataUrl = entityListId.getAttribute('data-default-url');

    let currentPage = 1;
    const itemsPerPage = entityListId.dataset.itemsPerPage;
    const fieldValues = JSON.parse(entityListId.dataset.fieldValues);
    const templateRow = document.querySelector('tbody > tr');

    function buildUrl(url, parameters, entity) {
        let builtUrl = url;

        Object.keys(parameters).forEach(param => {
            const entityField = parameters[param];
            const value = entity[entityField] !== undefined ? entity[entityField] : entityField;

            if (builtUrl.includes('?')) {
                builtUrl += `&${param}=${value}`;
            } else {
                builtUrl += `?${param}=${value}`;
            }
        });

        return builtUrl;
    }

    function setRowData(rowClone, field, value) {
        if (typeof value === 'boolean') {
            rowClone.querySelector(`.${field}`).textContent = value ? '✅' : '❌';
        } else if (typeof value === 'string' && value.startsWith('http')) {
            const url = new URL(value);
            const domain = url.hostname.replace('www.', '');
            rowClone.querySelector(`.${field}`).innerHTML = `<a href="${value}" target="_blank">🔗 (${domain})</a>`;
        } else {
            rowClone.querySelector(`.${field}`).textContent = value || 'Brak danych';

            if (field === 'id') {
               rowClone.querySelector('.form-check-input').value = value;
            }
        }

        rowClone.style.cursor = 'pointer';
    }

    async function generateTableContent(entities) {
        const tbody = document.querySelector('.list-class');
        tbody.innerHTML = '';

        for (const entity of entities) {
            const rowClone = templateRow.cloneNode(true);

            for (const field in fieldValues) {
                const fieldConfig = fieldValues[field];

                if (fieldConfig.source === 'direct') {
                    const value = entity[field];
                    setRowData(rowClone, field, value);
                } else if (fieldConfig.source === 'api') {
                    const apiUrl = buildUrl(fieldConfig.url, fieldConfig.parameters, entity);

                    try {
                        const response = await fetch(apiUrl);
                        const apiData = await response.json();
                        const data = apiData['member'];
                        const value = data[0]?.[field] || 'Brak danych';
                        setRowData(rowClone, field, value);
                    } catch (error) {
                        console.error(`Error fetching ${field}:`, error);
                    }
                }
            }

            rowClone.addEventListener('click', (event) => {
                if (!event.target.closest('a') && !event.target.closest('button')) {
                    window.location.href = dataUrl + entity.id;
                }
            });

            tbody.appendChild(rowClone);
        }

        handleCheckboxes();
    }

    function generatePagination(view) {
        const paginationContainer = document.querySelector('.pagination-container');
        paginationContainer.innerHTML = '';

        const currentPage = new URLSearchParams(view['@id']).get('page') || 1;
        const totalPages = parseInt(new URLSearchParams(view.last).get('page'), 10);

        let paginationHtml = '<div class="pagination">';

        if (view.previous) {
            paginationHtml += `<a class="btn" href="#" data-page="${parseInt(currentPage) - 1}">Poprzednia</a>`;
        } else {
            paginationHtml += '<span class="btn prev-next-btn">Poprzednia</span>';
        }

        paginationHtml += '<div class="desktop-pagination">';

        for (let i = 1; i <= totalPages; i++) {
            if (i === parseInt(currentPage)) {
                paginationHtml += `<span class="btn current-page">${i}</span>`;
            } else {
                paginationHtml += `<a class="btn" href="#" data-page="${i}">${i}</a>`;
            }
        }

        paginationHtml += '</div>';

        if (view.next) {
            paginationHtml += `<a class="btn" href="#" data-page="${parseInt(currentPage) + 1}">Następna</a>`;
        } else {
            paginationHtml += '<span class="btn prev-next-btn">Następna</span>';
        }

        paginationHtml += '</div>';

        paginationContainer.innerHTML = paginationHtml;

        setupPaginationLinks();
    }

    function setupPaginationLinks() {
        const paginationLinks = document.querySelectorAll('.pagination-container a');
        paginationLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const page = parseInt(e.target.dataset.page);
                if (!isNaN(page)) {
                    fetchProducts(page);
                }
            });
        });
    }

    function fetchProducts(page = 1) {
        const url = `${apiUrl}?page=${page}&itemsPerPage=${itemsPerPage}`;
        fetch(url)
            .then(response => response.json())
            .then(data => {
                const products = data['member'];
                generateTableContent(products).then(r => {
                    generatePagination(data.view);
                });
            })
            .catch(error => console.error('Error fetching products:', error));
    }

    function handleCheckboxes() {
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const checkboxes = document.querySelectorAll(`.${entityListId.dataset.suffix}-checkbox`);
        const deleteButton = document.querySelector('.delete-btn');

        const deleteButtonClone = deleteButton.cloneNode(true);
        deleteButton.parentNode.replaceChild(deleteButtonClone, deleteButton);

        function toggleDeleteButton() {
            const anyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
            if (anyChecked) {
                deleteButtonClone.classList.remove('d-none');
            } else {
                deleteButtonClone.classList.add('d-none');
            }
        }

        selectAllCheckbox.addEventListener('change', () => {
            const isChecked = selectAllCheckbox.checked;
            checkboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            toggleDeleteButton();
        });

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', toggleDeleteButton);
            checkbox.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        });

        deleteButtonClone.addEventListener('click', () => {
            if (confirm('Aby na pewno chcesz usunąć?')) {
                const checkedCheckboxes = Array.from(checkboxes).filter(checkbox => checkbox.checked);
                const deletePromises = checkedCheckboxes.map(checkbox => {
                    const row = checkbox.closest('tr');
                    checkbox.checked = false;
                    return deleteProduct(row, checkbox.value);
                });

                Promise.all(deletePromises).then(() => {
                    checkedCheckboxes.forEach(checkbox => {
                        const row = checkbox.closest('tr');
                        row.remove();
                    });

                    toggleDeleteButton();
                });
            }
        });
    }

    function deleteProduct(row, productId) {
        return fetch(dataUrl + productId, {
            method: 'DELETE',
        })
            .then(response => {
                if (response.ok) {
                    row.remove();
                } else {
                    alert('Błąd podczas usuwania produktu');
                }
            })
            .catch(error => {
                console.error('Error during product deletion:', error);
            });
    }

    fetchProducts(currentPage);
});
