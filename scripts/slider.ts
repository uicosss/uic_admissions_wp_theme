import Glide, { Options } from '@glidejs/glide';
import { checkSelector, onReady } from './utils';

const centerBoxArrows = (slider: HTMLDivElement) => {
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

onReady(() => {
    const boxSliders: HTMLDivElement[] = [];
    const sliders = document.querySelectorAll<HTMLDivElement>('.uic-slider');
    for (const slider of sliders) {
        const itemCount = slider.querySelectorAll('.uic-slider__item').length;
        let maxPageSize = Number((slider.attributes['data-max-page-size'] ?? { value: '4' }).value);
        const breakpoints: Record<number, Partial<Options>> = (maxPageSize === 4
            ? {
                767: {
                    perView: 1
                },
                979: {
                    perView: 2
                },
                1279: {
                    perView: 3
                },
                1280: {
                    perView: maxPageSize                        
                }
            }
            : {
                799: {
                    perView: 1
                },
                1199: { 
                    perView: 2
                },
                1200: {
                    perView: maxPageSize
                }
            }
        );

        const prevArrow = slider.querySelector('.uic-slider__prev')
        checkSelector(prevArrow, 'prevArrow');
        const nextArrow = slider.querySelector('.uic-slider__next')
        checkSelector(nextArrow, 'nextArrow');

        const glide = new Glide(slider, {
            type: 'slider',
            startAt: 0,
            perView: maxPageSize,
            gap: maxPageSize === 3 ? 64 : 24,
            animationDuration: 600,
            animationTimingFunc: 'ease-in-out',
            breakpoints
        });
        
        slider.setAttribute('data-active-slide', String(0));
        const state = {
            pageSize: maxPageSize,
            pageCount: Math.ceil(itemCount / maxPageSize),
            activePage: 0
        };
        prevArrow.addEventListener('click', e => {
            e.preventDefault();
            e.stopPropagation();
            if (state.activePage > 0) {
                glide.go(`=${(state.activePage-1)*state.pageSize}`);
            } else if (state.activePage === 0) {
                glide.go(`=0`);
            }
        });
        nextArrow.addEventListener('click', e => {
            e.preventDefault();
            e.stopPropagation();
            if (state.activePage < (state.pageCount - 1)) {
                glide.go(`=${(state.activePage+1)*state.pageSize}`);
            }
        });

        const dots = slider.querySelectorAll('.uic-slider__dot');
        for (const dot of dots) {
            dot.addEventListener('click', e => {
                e.preventDefault();
                e.stopPropagation();
                const index = Number((dot.getAttribute('data-glide-dir') ?? '=0').replaceAll('=', ''));
                const page = (index - (index % state.pageSize)) / state.pageSize;
                
                glide.go(`=${page * state.pageSize}`);
                const focusedSlide = slider.querySelector<HTMLDivElement>(`.uic-slider__item:nth-child(${index+1})`);
                if (focusedSlide) {
                    focusedSlide.focus();
                }

            });
        }

        glide.on(['run.after', 'resize', 'mount.after'], () => {
            state.pageSize = glide.settings.perView;
            slider.setAttribute('data-slide-count', String(itemCount));
            slider.setAttribute('data-page-size', String(state.pageSize));
            slider.setAttribute('data-active-slide', String(glide.index));
            state.pageCount =  Math.ceil(itemCount / state.pageSize);
            state.activePage =  (glide.index - (glide.index % state.pageSize)) / state.pageSize;
            slider.setAttribute('data-page-count', String(state.pageCount));
            slider.setAttribute('data-active-page', String(state.activePage));

            const hasAtStart = slider.hasAttribute('data-at-start');
            const hasAtEnd = slider.hasAttribute('data-at-end');
            if (hasAtStart && glide.index !== 0) {
                slider.removeAttribute('data-at-start')
            } else if (glide.index === 0 && !hasAtStart) {
                slider.setAttribute('data-at-start', 'true');
            }

            if (hasAtEnd && glide.index < (itemCount - state.pageSize)) {
                slider.removeAttribute('data-at-end')
            } else if (glide.index >= (itemCount - state.pageSize) && !hasAtEnd) {
                slider.setAttribute('data-at-end', 'true');
            }
        });          
        glide.mount();
        
        if (slider.querySelector('.uic-slider__box-container, .uic-slider__card__media')) {
            // this slider uses the double bordered box style,
            // lets update its nav arrow's top offset when the window resizes
            centerBoxArrows(slider);
            boxSliders.push(slider);
        }
    }

    if (boxSliders.length) {
        const resizeObserver = new ResizeObserver(() => {
            for (const boxSlider of boxSliders) {
                centerBoxArrows(boxSlider);
            }
        });
        resizeObserver.observe(document.body);
    }
});
