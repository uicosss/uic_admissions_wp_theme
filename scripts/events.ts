import VanillaCalendar from '@uvarov.frontend/vanilla-calendar';
import Glide, {Options} from "@glidejs/glide";
import { checkSelector, isTouchDevice, onReady, throttledWatchForChildren as throttledWatchChildren } from './utils';

const centerPanelArrows = (slider: HTMLDivElement) => {
    const boxEl = slider.querySelector<HTMLDivElement>('.glide__slide--active .uic-slider__box-container, .glide__slide--active .uic-slider__card__media');
    checkSelector(boxEl, 'box');
    const height = boxEl.getBoundingClientRect().height;
    const trackContainer = slider.querySelector<HTMLDivElement>('.uic-slider__track-container');
    checkSelector(trackContainer, 'track container');
    trackContainer.style.setProperty(
        '--uic-slider__arrow--top-offset',
        `calc(${height/2}px - (var(--uic-slider__arrow--height)/2))`
    );
};

let backdropEl: HTMLDivElement;
let backdropExitTimeout: NodeJS.Timeout | null = null;
let onBackdropDismiss: null | (() => void) = null;

function dismissBackdrop() {
    backdropEl.classList.add('uic-calendar-popup__backdrop--exiting');
    backdropEl.classList.remove('uic-calendar-popup__backdrop--visible');
    if (backdropExitTimeout) {

        clearTimeout(backdropExitTimeout);
    }

    backdropExitTimeout = setTimeout(() => backdropEl.classList.remove('uic-calendar-popup__backdrop--exiting'), 500);
}
function openBackdrop() {
    backdropEl.classList.remove('uic-calendar-popup__backdrop--exiting');
    if (backdropExitTimeout) {
        clearTimeout(backdropExitTimeout);
        backdropExitTimeout = null;
    }
    backdropEl.classList.add('uic-calendar-popup__backdrop--visible');
}

function attachDayListeners(days: HTMLDivElement[], blockEl: HTMLDivElement) {
    let tailDays = 0;
    let headDays = 0;
    for (const day of days) {
        let button = day.querySelector<HTMLButtonElement>('.vanilla-calendar-day__btn');
        if (!button) continue;
        const selected = button.classList.contains('uic-calendar__day--selected');
        const next = button.classList.contains('vanilla-calendar-day__btn_next');
        const prev = button.classList.contains('vanilla-calendar-day__btn_prev');
        const disabled = button.classList.contains('vanilla-calendar-day__btn_disabled');
        const date = new Date((button.attributes['data-calendar-day']?.value ?? '').replaceAll('-', '/'));
        const monthShort = date.toLocaleDateString('en-US', { month: 'long'  });
        const monthLong = date.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

        if (disabled) {
            button.setAttribute('tabindex', '-1');
            button.setAttribute('disabled', '');
            button.setAttribute('aria-hidden', 'true');
            continue;
        }
        if (prev && !selected) {
            button.setAttribute('title', 'View Events in ' + monthLong)
            if (tailDays > 0) {
                button.setAttribute('tabindex', '-1');
                button.setAttribute('aria-hidden', 'true');
            } else {
                button.setAttribute('aria-label', 'Events in ' + monthShort);
            }
            tailDays++;
        } else if (next && !selected){
            button.setAttribute('title', 'View Events in ' + monthLong)
            if (headDays > 0) {
                button.setAttribute('tabindex', '-1');
                button.setAttribute('aria-hidden', 'true');
            } else {
                button.setAttribute('aria-label', 'Events in ' + monthShort);
            }
            headDays++;
        } else if (!selected && !next && !prev && !disabled) {
            button.setAttribute('tabindex', '-1');
            button.setAttribute('disabled', '');
            button.setAttribute('aria-hidden', 'true');
            continue; // not a day with events, so no need to attach a listener
        }
        if (selected) {
            const dateStr = date.toLocaleDateString('en-US', { day: 'numeric', year: 'numeric', month: 'long'  });

            const shortDateStr = date.toLocaleDateString('en-US', { day: 'numeric', month: 'long'  });
            button.setAttribute('aria-label', shortDateStr);
            button.setAttribute('title', `View events on ` + dateStr);

        } else {
            if (!next && !prev) {
                button.setAttribute('aria-label', 'true');
                button.setAttribute('tabindex', '-1');
            }

        }


        const popup = day.querySelector<HTMLDivElement>('.vanilla-calendar-day__popup');
        if (!popup) continue;
        const off = () => {
            day.classList.remove('uic-calendar-popup--activated');
            popup.classList.remove('uic-calendar-popup--focused');
        };
        const on = () => {
            if (!isTouchDevice()) return;
            popup.classList.add('uic-calendar-popup--focused');
            setTimeout(() => day.classList.add('uic-calendar-popup--activated'), 500);
            openBackdrop();
            onBackdropDismiss = off;
        };

        button.addEventListener('focus', on);
        button.addEventListener('ontouchstart', on);

        const events = day.querySelectorAll('a');
        for (const event of events) {
            event.addEventListener('blur', (e) => {
                if (!day.contains(e.relatedTarget as any)) {
                    off();
                }
                if (!blockEl.contains(e.relatedTarget as any)) {
                    dismissBackdrop();
                }
            });
        }
    }
}

onReady(() => {
    const eventBlocks = document.querySelectorAll<HTMLDivElement>('.uic-events');
    if (!eventBlocks.length) return;

    backdropEl = document.createElement('div')
    backdropEl.classList.add('uic-calendar-popup__backdrop');
    document.body.appendChild(backdropEl);
    backdropEl.addEventListener('click', () => {
        if (onBackdropDismiss) onBackdropDismiss();
        dismissBackdrop();
    });

    for (const eventBlock of eventBlocks) {
        const calendarEl = eventBlock.querySelector<HTMLDivElement>('.uic-calendar');
        checkSelector(calendarEl, 'calendar');

        const highlightedDates: Record<string, string> = {};
        const highlightedDateEls = calendarEl.querySelectorAll('.uic-calendar__highlighted-date');
        for (const highlightedDateEl of highlightedDateEls) {
            const date = (highlightedDateEl.attributes['data-date'] ?? { value: null }).value;
            if (!date) {
                console.error('expected to find data-date attribute, but got nothing', highlightedDateEl);
            } else {
                highlightedDates[date] = highlightedDateEl.innerHTML;;
            }
        }

        const popups = {};
        for (const [date, events] of Object.entries(highlightedDates)) {
            popups[date] = {
                modifier: 'uic-calendar__day--selected',
                html: events
            };
        }
        const startDate = new Date();
        startDate.setDate(1);
        const endDate = new Date();
        endDate.setFullYear(endDate.getFullYear() + 1);

        const calendar = new VanillaCalendar(calendarEl, {
            date: {
                min: startDate.toISOString().split('T')[0],
                max: endDate.toISOString().split('T')[0],
            },
            settings: {
                lang: 'define',
                range: {
                    min: (new Date()).toISOString().split('T')[0] as any,
                    max: endDate.toISOString().split('T')[0] as any
                }
            },
            locale: {
                months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                weekday: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
            },
            popups
        });
        calendar.init();

        setTimeout(() => {
            const days: HTMLDivElement[] = calendarEl.querySelectorAll('.vanilla-calendar-day') as any;
            attachDayListeners(days!, eventBlock);
            throttledWatchChildren(calendarEl!, (added, removed) => {
                attachDayListeners(added as HTMLDivElement[], eventBlock);
                const disabled = calendarEl.querySelectorAll('.vanilla-calendar-years__year_disabled, .vanilla-calendar-months__month_disabled');
                for (const el of disabled) {
                    el.setAttribute('tabindex', '-1');
                    el.setAttribute('disabled', 'true');
                }
            }, '.vanilla-calendar-day');
            const prevMonth = eventBlock.querySelector<HTMLButtonElement>('.vanilla-calendar-arrow_prev');
            const nextMonth = eventBlock.querySelector<HTMLButtonElement>('.vanilla-calendar-arrow_next');
            checkSelector(prevMonth, 'prev month button');
            checkSelector(nextMonth, 'next month button');
            prevMonth.setAttribute('title', 'View Previous Month');
            nextMonth.setAttribute('title', 'View Next Month');
        }, 333);
    }
});

onReady(() => {
    const sliders = document.querySelectorAll<HTMLDivElement>('.uic-events__slider');
    for (const slider of sliders) {
        const itemCount = slider.querySelectorAll('.uic-events__panel__item').length;
        let maxPageSize = 1;

        if (itemCount > 0) {
            const prevArrow = slider.querySelector('.uic-events__panel__prev')
            checkSelector(prevArrow, 'prevArrow');
            const nextArrow = slider.querySelector('.uic-events__panel__next')
            checkSelector(nextArrow, 'nextArrow');

            const glidePanel = new Glide(slider, {
                type: 'slider',
                startAt: 0,
                perView: maxPageSize,
                gap: maxPageSize === 3 ? 64 : 24,
                animationDuration: 600,
                animationTimingFunc: 'ease-in-out',
                keyboard: false,
            });

            slider.setAttribute('data-active-slide', String(0));
            const state = {
                pageSize: maxPageSize,
                pageCount: Math.ceil(itemCount / maxPageSize),
                activePage: 0
            };
            let prevArrowShowing = false;
            let nextArrowShowing = true;
            prevArrow.addEventListener('click', e => {
                e.preventDefault();
                e.stopPropagation();
                if (state.activePage > 0) {
                    glidePanel.go(`=${(state.activePage-1)*state.pageSize}`);
                } else if (state.activePage === 0) {
                    glidePanel.go(`=0`);
                }
            });
            nextArrow.addEventListener('click', e => {
                e.preventDefault();
                e.stopPropagation();
                if (state.activePage < (state.pageCount - 1)) {
                    glidePanel.go(`=${(state.activePage+1)*state.pageSize}`);
                }
            });

            glidePanel.on(['run.after', 'resize', 'mount.after'], () => {
                state.pageSize = glidePanel.settings.perView;
                slider.setAttribute('data-slide-count', String(itemCount));
                slider.setAttribute('data-page-size', String(state.pageSize));
                slider.setAttribute('data-active-slide', String(glidePanel.index));
                state.pageCount =  Math.ceil(itemCount / state.pageSize);
                state.activePage =  (glidePanel.index - (glidePanel.index % state.pageSize)) / state.pageSize;
                slider.setAttribute('data-page-count', String(state.pageCount));
                slider.setAttribute('data-active-page', String(state.activePage));

                const hasAtStart = slider.hasAttribute('data-at-start');
                const hasAtEnd = slider.hasAttribute('data-at-end');
                if (hasAtStart && glidePanel.index !== 0) {
                    slider.removeAttribute('data-at-start')
                } else if (glidePanel.index === 0 && !hasAtStart) {
                    slider.setAttribute('data-at-start', 'true');
                }

                if (hasAtEnd && glidePanel.index < (itemCount - state.pageSize)) {
                    slider.removeAttribute('data-at-end')
                } else if (glidePanel.index >= (itemCount - state.pageSize) && !hasAtEnd) {
                    slider.setAttribute('data-at-end', 'true');
                }

                if (!prevArrowShowing && glidePanel.index > 0) {
                    prevArrowShowing = true;
                    prevArrow.classList.remove('arrow_hidden');
                } else if (prevArrowShowing && glidePanel.index == 0) {
                    prevArrowShowing = false;
                    prevArrow.classList.add('arrow_hidden');
                }
                if (nextArrowShowing && glidePanel.index == (itemCount - 1)) {
                    nextArrowShowing = false;
                    nextArrow.classList.add('arrow_hidden');
                } else if (!nextArrowShowing && glidePanel.index < (itemCount - 1)) {
                    nextArrowShowing = true;
                    nextArrow.classList.remove('arrow_hidden');
                }
            });
            glidePanel.mount();
        }
    }
})

