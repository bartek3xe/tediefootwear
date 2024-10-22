document.addEventListener('DOMContentLoaded', () => {
    const entityListId = document.getElementById('entityListId');
    const apiUrl = entityListId.getAttribute('data-api-url');
    const dataUrl = entityListId.getAttribute('data-default-url');
    const initialPage = 1;
    const { itemsPerPage } = entityListId.dataset;
    const fieldValues = JSON.parse(entityListId.dataset.fieldValues);
    const templateRow = document.querySelector('tbody > tr');

    function buildUrl(url, parameters, entity) {
        let builtUrl = url;
        Object.keys(parameters).forEach((param) => {
            const entityField = parameters[param];
            const value = entity[entityField] !== undefined ? entity[entityField] : entityField;
            builtUrl += builtUrl.includes('?') ? `&${param}=${value}` : `?${param}=${value}`;
        });
        return builtUrl;
    }

    function handleCheckboxes() {
        const selectAllCheckbox = document.getElementById('selectAllCheckbox');
        const checkboxes = document.querySelectorAll(`.${entityListId.dataset.suffix}-checkbox`);
        const deleteButton = document.querySelector('.delete-btn');

        const deleteButtonClone = deleteButton.cloneNode(true);
        deleteButton.parentNode.replaceChild(deleteButtonClone, deleteButton);

        function toggleDeleteButton() {
            const anyChecked = Array.from(checkboxes).some((checkbox) => checkbox.checked);
            if (anyChecked) {
                deleteButtonClone.classList.remove('d-none');
            } else {
                deleteButtonClone.classList.add('d-none');
            }
        }

        selectAllCheckbox.addEventListener('change', () => {
            const isChecked = selectAllCheckbox.checked;
            checkboxes.forEach((checkbox) => {
                /* eslint-disable-next-line no-param-reassign */
                checkbox.checked = isChecked;
            });
            toggleDeleteButton();
        });

        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', toggleDeleteButton);
            checkbox.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        });

        deleteButtonClone.addEventListener('click', () => {
            if (window.confirm('Aby na pewno chcesz usunąć?')) {
                const checkedCheckboxes = Array.from(checkboxes)
                    .filter((checkbox) => checkbox.checked);
                const deletePromises = checkedCheckboxes.map((checkbox) => {
                    const row = checkbox.closest('tr');
                    checkbox.checked = false;
                    return deleteProduct(row, checkbox.value);
                });

                Promise.all(deletePromises).then(() => {
                    checkedCheckboxes.forEach((checkbox) => {
                        const row = checkbox.closest('tr');
                        row.remove();
                    });

                    toggleDeleteButton();
                });
            }
        });
    }

    function setRowData(rowClone, field, value) {
        const fieldElement = rowClone.querySelector(`.${field}`);
        if (typeof value === 'boolean') {
            fieldElement.textContent = value ? '✅' : '❌';
        } else if (typeof value === 'string' && value.startsWith('http')) {
            const url = new URL(value);
            const domain = url.hostname.replace('www.', '');
            fieldElement.innerHTML = `<a href="${value}" target="_blank">🔗 (${domain})</a>`;
        } else {
            fieldElement.textContent = value || 'Brak danych';
            if (field === 'id') {
                const checkInput = rowClone.querySelector('.form-check-input');
                checkInput.value = value;
            }
        }

        /* eslint-disable-next-line no-param-reassign */
        rowClone.style.cursor = 'pointer';
    }

    async function generateTableContent(entities) {
        const tbody = document.querySelector('.list-class');
        tbody.innerHTML = '';

        const entityPromises = entities.map(async (entity) => {
            const rowClone = templateRow.cloneNode(true);
            const promises = Object.keys(fieldValues).map(async (field) => {
                const fieldConfig = fieldValues[field];
                if (fieldConfig.source === 'direct') {
                    const value = entity[field];
                    setRowData(rowClone, field, value);
                } else if (fieldConfig.source === 'api') {
                    const url = buildUrl(fieldConfig.url, fieldConfig.parameters, entity);
                    try {
                        const response = await fetch(url);
                        const apiData = await response.json();
                        /* eslint-disable-next-line dot-notation */
                        const data = apiData['member'];
                        const value = data[0]?.[field] || 'Brak danych';
                        setRowData(rowClone, field, value);
                    } catch (error) {
                        throw new Error('39776a27-63e1-44a6-8157-a38b9d4fad96');
                    }
                }
            });
            await Promise.all(promises);
            rowClone.addEventListener('click', (event) => {
                if (!event.target.closest('a') && !event.target.closest('button')) {
                    window.location.href = dataUrl + entity.id;
                }
            });
            tbody.appendChild(rowClone);
        });

        await Promise.all(entityPromises);
        handleCheckboxes();
    }

    function generatePagination(view) {
        const paginationContainer = document.querySelector('.pagination-container');
        paginationContainer.innerHTML = '';
        const currentPage = parseInt(new URLSearchParams(view['@id']).get('page'), 10) || 1;
        const totalPages = parseInt(new URLSearchParams(view.last).get('page'), 10);

        let paginationHtml = '<div class="pagination">';
        paginationHtml += view.previous ? `<a class="btn" href="#" data-page="${currentPage - 1}">Poprzednia</a>` : '<span class="btn prev-next-btn">Poprzednia</span>';
        paginationHtml += '<div class="desktop-pagination">';
        for (let i = 1; i <= totalPages; i += 1) {
            paginationHtml += i === currentPage ? `<span class="btn current-page">${i}</span>` : `<a class="btn" href="#" data-page="${i}">${i}</a>`;
        }
        paginationHtml += '</div>';
        paginationHtml += view.next ? `<a class="btn" href="#" data-page="${currentPage + 1}">Następna</a>` : '<span class="btn prev-next-btn">Następna</span>';
        paginationHtml += '</div>';

        paginationContainer.innerHTML = paginationHtml;
        setupPaginationLinks();
    }

    function setupPaginationLinks() {
        const paginationLinks = document.querySelectorAll('.pagination-container a');
        paginationLinks.forEach((link) => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const page = parseInt(e.target.dataset.page, 10);
                if (!Number.isNaN(page)) {
                    fetchEntities(page);
                }
            });
        });
    }

    function fetchEntities(page = 1) {
        const url = `${apiUrl}?page=${page}&itemsPerPage=${itemsPerPage}`;
        fetch(url)
            .then((response) => response.json())
            .then((data) => {
                /* eslint-disable-next-line dot-notation */
                const entities = data['member'];
                generateTableContent(entities).then(() => generatePagination(data.view));
            })
            .catch(() => {
                throw new Error('39776a27-63e1-44a6-8157-a38b9d4fad96');
            });
    }

    function deleteProduct(row, entityId) {
        return fetch(dataUrl + entityId, {
            method: 'DELETE',
        })
            .then((response) => {
                if (response.ok) {
                    row.remove();
                } else {
                    alert('Błąd podczas usuwania produktu');
                }
            })
            .catch(() => {
                throw new Error('82a4bd8a-0882-4c7b-b1c3-6fe274f1490d');
            });
    }

    fetchEntities(initialPage);
});
