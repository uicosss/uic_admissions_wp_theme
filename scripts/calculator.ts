import Choices, { EventMap } from 'choices.js';
import { checkSelector, onReady } from './utils';

export interface SerializedCalculatorData {
    base_tuition: {
        resident_tuition: number;
        non_resident_tuition: number;
        international_tuition: number;
    };
    cost_of_attendance: {
        books: number;
        personal: number;
        transportation: number;
    };
    elective_fees: {
        insurance_fee: number;
        housing_fee: number;
    };
    mandatory_fees: {
        assessment_fee: number;
        service_fee: number;
        general_fee: number;
        health_services_fee: number;
        transportation_fee: number;
        student_to_student_fee: number;
        sustainability_fee: number;
    };
    differentials: {
        department: string;
        programs: {
            name: string;
            value: number;
        }[];
    }[];
    result_links: {
        resident: ResultLink[];
        non_resident: ResultLink[];
        international: ResultLink[];
    }
};

export interface ResultLink {
    link: {
        title: string;
        url: string;
        target?: string;
    };
};

const tuitionKeyMap = {
    'resident': 'resident_tuition',
    'non-resident': 'non_resident_tuition',
    'international': 'international_tuition'
} as const;

const resultLinkKeyMap = {
    'resident': 'resident',
    'non-resident': 'non_resident',
    'international': 'international'
} as const;

const currencyFormatter = new Intl.NumberFormat('en-US', { style: 'decimal' });
const formatCurrency = (n) => currencyFormatter.format(Math.ceil(n));

onReady(() => {
    const calculators = document.querySelectorAll<HTMLDivElement>('.uic-calculator');
    for (const calculator of calculators) {

        if (!('__UIC_CALCULATOR_DATA__' in window)) {
            throw new Error('missing calculator data!')
        }
        const data = window['__UIC_CALCULATOR_DATA__'] as SerializedCalculatorData;

        const selectEls = {
            location: calculator.querySelector<HTMLSelectElement>('.uic-calculator__select[name="location"]'),
            program: calculator.querySelector<HTMLSelectElement>('.uic-calculator__select[name="program"]'),
            housing: calculator.querySelector<HTMLSelectElement>('.uic-calculator__select[name="housing"]')
        };
        checkSelector(selectEls.location, 'location select');
        checkSelector(selectEls.program, 'program select');
        checkSelector(selectEls.housing, 'housing select');

        const locationSelect = new Choices(selectEls.location, {
            allowHTML: true,
            shouldSort: false,
            resetScrollPosition: false,
            itemSelectText: '',
            searchEnabled: false,
            removeItemButton: false,
            labelId: 'uic-calculator__select-label--location'
        });
        const programSelect = new Choices(selectEls.program, {
            allowHTML: true,
            shouldSort: false,
            resetScrollPosition: false,
            itemSelectText: '',
            searchEnabled: false,
            removeItemButton: false,
            labelId: 'uic-calculator__select-label--program'
        });
        const housingSelect = new Choices(selectEls.housing, {
            allowHTML: true,
            shouldSort: false,
            resetScrollPosition: false,
            itemSelectText: '',
            searchEnabled: false,
            removeItemButton: false,
            labelId: 'uic-calculator__select-label--housing'
        });

        const selectWrappers = calculator.querySelectorAll('.choices[data-type="select-one"]');
        for (const selectWrapper of selectWrappers) {
            selectWrapper.setAttribute('role', 'combobox');
            const labelledBy = selectWrapper.getAttribute('aria-labelledby') ?? false;
            if (labelledBy) selectWrapper.setAttribute('aria-labelledby', labelledBy);
            const displayedOptionList = selectWrapper.querySelector('.choices__inner .choices__list');
            if (displayedOptionList) {
                if (labelledBy) {
                    const id = labelledBy.replace('__select-label--', '__selection--');
                    displayedOptionList.setAttribute('id', id);
                    selectWrapper.setAttribute('aria-controls', id);
                    displayedOptionList.setAttribute('aria-labelledby', labelledBy);
                }
                for (const child of displayedOptionList.children) {
                    child.setAttribute('role', 'option');
                }
                displayedOptionList.setAttribute('role', 'listbox');
            }

            const dropdownList = selectWrapper.querySelector<HTMLDivElement>('.choices__list--dropdown .choices__list');
            if (dropdownList && labelledBy) {
                dropdownList.setAttribute('aria-labelledby', labelledBy);
            }
        }

        const values = {
            location: null as 'resident' | 'non-resident' | 'international' | null,
            program: null as number | null,
            housing: null as 'on-campus' | 'off-campus' | null,
            lineItems: {
                differential: 0,
                tuition: 0,
                fees: 0,
                universityCharge: 0,
                variableExpenses: 0,
                grandTotal: 0
            }
        };

        const updateSubmitVisibility = () => {
            const submitLabel = calculator.querySelector<HTMLDivElement>('.uic-calculator__submit-label');
            const submitButton = calculator.querySelector<HTMLDivElement>('.uic-calculator__submit');

            checkSelector(submitLabel, 'submit label')
            checkSelector(submitButton, 'submit button')

            let formFilled = true;
            if (values.location === null) formFilled = false;
            else if (values.program === null) formFilled = false;
            else if (values.housing === null) formFilled = false;

            if (formFilled) {
                if (submitLabel.classList.contains('uic-calculator__submit-label--hidden')) {
                    submitLabel.classList.remove('uic-calculator__submit-label--hidden');
                }
                submitButton.removeAttribute('disabled')
            } else {
                if (!submitLabel.classList.contains('uic-calculator__submit-label--hidden')) {
                    submitLabel.classList.add('uic-calculator__submit-label--hidden');
                }
                submitButton.setAttribute('disabled', String(true));
            }
        };
        selectEls.location.addEventListener('choice', (e: EventMap['choice']) => {
            const choice = e.detail.choice.value;
            const isPlaceholder = e.detail.choice.placeholder;
            if (isPlaceholder) {
                values.location = null;
            } else if (['resident', 'non-resident', 'international'].includes(choice)) {
                values.location = choice;
            } else {
                values.location = null;
            }
            updateSubmitVisibility();
        });
        selectEls.program.addEventListener('choice', (e: EventMap['choice']) => {
            const choice = e.detail.choice.value.length ? Number.parseFloat(e.detail.choice.value) : null;
            const isPlaceholder = e.detail.choice.placeholder;
            if (isPlaceholder) {
                values.program = null;
            } else if (!Number.isNaN(choice) && choice !== null) {
                values.program = choice;
            } else {
                values.program = null;
            }
            updateSubmitVisibility();
        });
        selectEls.housing.addEventListener('choice', (e: EventMap['choice']) => {
            const choice = e.detail.choice.value;
            const isPlaceholder = e.detail.choice.placeholder;
            if (isPlaceholder) {
                values.housing = null;
            } else if (['on-campus', 'off-campus'].includes(choice)) {
                values.housing = choice;
            } else {
                values.housing = null;
            }
            updateSubmitVisibility();
        });

        const questionnaireEl = calculator.querySelector<HTMLDivElement>('.uic-calculator__questionnaire-container');
        const resultsEl = calculator.querySelector<HTMLDivElement>('.uic-calculator__results');
        const submitEl = calculator.querySelector<HTMLButtonElement>('.uic-calculator__submit');

        checkSelector(questionnaireEl, 'questionnaire');
        checkSelector(resultsEl, 'results');
        checkSelector(submitEl, 'submit');

        if (submitEl) {
            submitEl.addEventListener('click', e => {
                e.stopPropagation();
                e.preventDefault();
                questionnaireEl.classList.add('uic-calculator__questionnaire-container--hidden');
                resultsEl.classList.add('uic-calculator__results--visible');

                values.lineItems.tuition = data.base_tuition[tuitionKeyMap[values.location!]];
                values.lineItems.differential = values.program!;
                values.lineItems.fees = (
                    + data.mandatory_fees.assessment_fee
                    + data.mandatory_fees.service_fee
                    + data.mandatory_fees.general_fee
                    + data.mandatory_fees.health_services_fee
                    + data.mandatory_fees.transportation_fee
                    + data.mandatory_fees.student_to_student_fee
                    + data.mandatory_fees.sustainability_fee
                    + data.elective_fees.insurance_fee
                );
				values.lineItems.universityCharge =
					values.lineItems.tuition
					+ values.lineItems.differential
                    + (values.housing === 'on-campus' ? data.elective_fees.housing_fee : 0)
					+ values.lineItems.fees
				;
                values.lineItems.variableExpenses = (
                    data.cost_of_attendance.books
                    + (values.housing === 'on-campus' ? 0 : data.elective_fees.housing_fee)
                    + data.cost_of_attendance.personal
                    + data.cost_of_attendance.transportation
                );
                values.lineItems.grandTotal =
					values.lineItems.universityCharge
					+ values.lineItems.variableExpenses;

                const linksEl = resultsEl.querySelector('.uic-calculator__results__links');
                checkSelector(linksEl, 'links')
                linksEl.innerHTML = ``;

                for (const { link: { title, url, target } } of data.result_links[resultLinkKeyMap[values.location!]]) {
                    const words = title.replace(/\s+/g, ' ').trim().split(/\s/g);
                    linksEl.innerHTML += `
                        <a href="${url}" ${target ? `target="${target}" `: ''}class="uic-calculator__results__link" title="${title}">
                            ${words.map((word, i) => {
                                if (i === words.length - 1) {
                                    return `<span>${word} <span class="uic-calculator__results__link__arrow"></span></span>`
                                } else {
                                    return `<span>${word} </span>`;
                                }
                            }).join(' ')}
                        </a>
                    `;
                }

                linksEl.setAttribute('data-links-count', String(data.result_links[resultLinkKeyMap[values.location!]].length));

				const residencyDislplayText = values.location == "resident" ? "Resident / In-State"
					: values.location == "non-resident" ? "Non-Resident / Out of State"
							: values.location;
				const userChoices = resultsEl.querySelector('.uic-calculator__line-item__choices-desc');
				checkSelector(userChoices, 'user choices');
				const tuitionValueEl = resultsEl.querySelector('.uic-calculator__line-item__tuition-value');
                checkSelector(tuitionValueEl, 'tuition value');
                const differentialValueEl = resultsEl.querySelector('.uic-calculator__line-item__differential-value');
                checkSelector(differentialValueEl, 'differential value');
                const feesValueEl = resultsEl.querySelector('.uic-calculator__line-item__fees-value');
                checkSelector(feesValueEl, 'fees value');
				const attendanceValueEl = resultsEl.querySelector('.uic-calculator__line-item__attendance-value');
				checkSelector(attendanceValueEl, 'attendance value');
				const booksValueEl = resultsEl.querySelector('.uic-calculator__line-item__books-value');
                checkSelector(booksValueEl, 'books value');
                const housingEstimateSymbolEl = resultsEl.querySelector('.uic-calculator__line-item__housing-estimate-symbol');
                checkSelector(housingEstimateSymbolEl, 'housing estimate symbol');
                const housingEstimateValueEl = resultsEl.querySelector('.uic-calculator__line-item__housing-estimate-value');
                checkSelector(housingEstimateValueEl, 'housing estimate value');
                const housingVariableSymbolEl = resultsEl.querySelector('.uic-calculator__line-item__housing-variable-symbol');
                checkSelector(housingVariableSymbolEl, 'housing variable symbol');
                const housingVariableValueEl = resultsEl.querySelector('.uic-calculator__line-item__housing-variable-value');
                checkSelector(housingVariableValueEl, 'housing variable value');
                const personalValueEl = resultsEl.querySelector('.uic-calculator__line-item__personal-value');
                checkSelector(personalValueEl, 'personal value');
                const transportationValueEl = resultsEl.querySelector('.uic-calculator__line-item__transportation-value');
                checkSelector(transportationValueEl, 'transportation value');
                const variableValueEl = resultsEl.querySelector('.uic-calculator__line-item__variable-value');
				checkSelector(variableValueEl, 'total variable expenses');
				const totalValueEl = resultsEl.querySelector('.uic-calculator__line-item__total-value');
                checkSelector(totalValueEl, 'total value');
                const isOnCampus = values.housing === "on-campus";
				userChoices.innerHTML = residencyDislplayText
					// @ts-ignore
					+ '&nbsp;|&nbsp;' + selectEls.program.options[selectEls.program.selectedIndex].text
					+ '&nbsp;|&nbsp;' + values.housing;
				tuitionValueEl.innerHTML = formatCurrency(values.lineItems.tuition);
                differentialValueEl.innerHTML = formatCurrency(values.lineItems.differential);
                housingEstimateSymbolEl.innerHTML = isOnCampus ? "$" : "";
                housingEstimateValueEl.innerHTML = isOnCampus ? formatCurrency(data.elective_fees.housing_fee) : "listed below";
                housingVariableSymbolEl.innerHTML = isOnCampus ? "" : "$";
                housingVariableValueEl.innerHTML = isOnCampus ? "listed above" : formatCurrency(data.elective_fees.housing_fee);
                feesValueEl.innerHTML = formatCurrency(values.lineItems.fees);
				attendanceValueEl.innerHTML = formatCurrency(values.lineItems.universityCharge);
                booksValueEl.innerHTML = formatCurrency(data.cost_of_attendance.books);
                personalValueEl.innerHTML = formatCurrency(data.cost_of_attendance.personal);
                transportationValueEl.innerHTML = formatCurrency(data.cost_of_attendance.transportation);
				variableValueEl.innerHTML = formatCurrency(values.lineItems.variableExpenses);
                totalValueEl.innerHTML = formatCurrency(values.lineItems.grandTotal);
                calculator.focus();
            });
        }
        const closeButtonEl = calculator.querySelector('.uic-calculator__results-close');
        checkSelector(closeButtonEl, 'close button')
        closeButtonEl.addEventListener('click', e => {
            e.stopPropagation();
            e.preventDefault();
            questionnaireEl.classList.remove('uic-calculator__questionnaire-container--hidden');
            resultsEl.classList.remove('uic-calculator__results--visible');
        });
        calculator.addEventListener('keydown', e => {
            if (resultsEl.classList.contains('uic-calculator__results--visible') && e.key === 'Escape') {
                e.stopPropagation();
                e.preventDefault();
                questionnaireEl.classList.remove('uic-calculator__questionnaire-container--hidden');
                resultsEl.classList.remove('uic-calculator__results--visible');
            }
        }, { capture: true });
        calculator.addEventListener('click', e => {
            if (document.activeElement && !calculator.contains(document.activeElement)) {
                calculator.focus();
            }
        });
    }
});
