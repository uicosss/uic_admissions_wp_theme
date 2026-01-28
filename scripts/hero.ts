import Glide from '@glidejs/glide';
import { checkSelector, onReady } from './utils';

onReady(() => {
    /**
     * how long each slide will be shown for
     * linked to `--uic-hero__slider--duration` in the css and `data-slide-duration` in the HTML
     */
    const SLIDE_DURATION = 5000;
    /**
     * how long the slide transition animation should taken (in ms)
     * linked to `--uic-hero__slider--transition-duration` in the css and `data-slide-transition-duration` in the HTML
     */
    const SLIDE_TRANSITION_DURATION = 1500;
    /** 
     * how long the previous slide (which is now a layer below the active slide) is still visible for (in ms)
     * linked to `--uic-hero__slider--linger-duration` in the css and `data-slide-linger-duration` in the HTML
     */
    const SLIDE_LINGER_DURATION = SLIDE_TRANSITION_DURATION * 2.5;
    

    const heros = document.querySelectorAll<HTMLDivElement>('.uic-hero');
    for (const hero of heros) {
        const slider = hero.querySelector<HTMLDivElement>('.uic-hero__slider');
        if (!slider) throw new Error('could not locate slider')
        let activeSlideIdx = 0;
        slider.setAttribute('data-active-slide', String(activeSlideIdx));
        const slideDuration = Number((slider.attributes['data-slide-duration'] ?? { value: SLIDE_DURATION }).value);
        slider.style.setProperty('--uic-hero__slider--duration', `${slideDuration}ms`);
        const slideLingerDuration = Number((slider.attributes['data-slide-linger-duration'] ?? { value: SLIDE_LINGER_DURATION }).value);
        slider.style.setProperty('--uic-hero__slider--linger-duration', `${slideLingerDuration}ms`);
        const slideTransitionDuration = Number((slider.attributes['data-slide-transition-duration'] ?? { value: SLIDE_TRANSITION_DURATION }).value);
        slider.style.setProperty('--uic-hero__slider--transition-duration', `${slideTransitionDuration}ms`);

        const glide = new Glide(slider, {
            type: 'slider',
            startAt: activeSlideIdx,
            perView: 1,
            autoplay: slideDuration
        });

        glide.mount();

        glide.on('run.after', () => {
            const exitingSlideEl = slider.querySelector<HTMLDivElement>(`.uic-hero__slider__item:nth-child(${activeSlideIdx + 1})`); // +1 because nth-child selector is not 0-indexed
            checkSelector(exitingSlideEl, 'exiting slide');
            exitingSlideEl.classList.remove('uic-hero__slider__item--active');
            exitingSlideEl.classList.add('uic-hero__slider__item--exiting');
            setTimeout(() => exitingSlideEl.classList.remove('uic-hero__slider__item--exiting'), slideTransitionDuration);
            slider.setAttribute('data-active-slide', String(glide.index));
            activeSlideIdx = glide.index;
            
            const activeSlideEl = slider.querySelector<HTMLDivElement>(`.uic-hero__slider__item:nth-child(${activeSlideIdx + 1})`); // +1 because nth-child selector is not 0-indexed
            checkSelector(activeSlideEl, 'active slide');
            activeSlideEl.classList.add('uic-hero__slider__item--active');
        });

        let playing = true;
        const pausePlayButtonEl = hero.querySelector('.uic-hero__controls__autoplay');
        checkSelector(pausePlayButtonEl, 'pause play button')
        const pausePlaySRText = pausePlayButtonEl.querySelector('.sr-only');
        pausePlayButtonEl.addEventListener('click', (e) => {
            e.preventDefault();
            e.stopPropagation();
            if (playing) {
                playing = false;
                glide.pause();
                pausePlayButtonEl.setAttribute('title', 'Play Slideshow');
                pausePlayButtonEl.setAttribute('aria-label', 'Play Slideshow');
                pausePlayButtonEl.classList.add('uic-hero__controls__autoplay--paused');
                if (pausePlaySRText) pausePlaySRText.textContent = 'Play Slideshow';
            } else {
                playing = true;
                glide.play();
                pausePlayButtonEl.setAttribute('title', 'Pause Slideshow');
                pausePlayButtonEl.setAttribute('aria-label', 'Pause Slideshow');
                pausePlayButtonEl.classList.remove('uic-hero__controls__autoplay--paused');
                if (pausePlaySRText) pausePlaySRText.textContent = 'Pause Slideshow';
            }
        });
    }
});
