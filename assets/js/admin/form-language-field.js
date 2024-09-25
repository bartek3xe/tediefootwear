document.addEventListener('DOMContentLoaded', () => {
    const languageSelector = document.getElementById('product_language');
    const languageFields = document.querySelectorAll('.language-dependent-field');
    const languages = {};

    function updateLanguageSelector() {
        Object.keys(languages).forEach((lang) => {
            const option = languageSelector.querySelector(`option[value="${lang}"]`);
            let hasDescription = false;

            languageFields.forEach((field) => {
                if (field.name.includes('description')
                    && field.closest('.language-fields').dataset.language === lang) {
                    hasDescription = true;
                }
            });

            if (
                languages[lang].nameFilled
                && (languages[lang].descriptionFilled || !hasDescription)
            ) {
                option.style.color = '';
            } else {
                option.style.color = 'red';
            }
        });
    }

    function updateLanguageFields() {
        const selectedLanguage = languageSelector.value;
        languageFields.forEach((field) => {
            const fieldContainer = field.closest('.language-fields'); // Zmienna lokalna
            const fieldLang = fieldContainer.dataset.language;
            fieldContainer.style.display = (fieldLang === selectedLanguage) ? '' : 'none';
        });
    }

    function checkMissingFields() {
        Object.keys(languages).forEach((key) => delete languages[key]);

        languageFields.forEach((field) => {
            const fieldLang = field.closest('.language-fields').dataset.language;
            const isFilled = field.value.trim() !== '';
            if (!languages[fieldLang]) {
                languages[fieldLang] = { nameFilled: false, descriptionFilled: false };
            }

            if (field.name.includes('name')) {
                languages[fieldLang].nameFilled = isFilled;
            } else if (field.name.includes('description')) {
                languages[fieldLang].descriptionFilled = isFilled;
            }
        });

        updateLanguageSelector();
    }

    languageFields.forEach((field) => {
        field.addEventListener('input', checkMissingFields);
    });

    languageSelector.addEventListener('change', updateLanguageFields);

    updateLanguageFields();
    checkMissingFields();
});
