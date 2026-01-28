import { checkSelector, onReady } from './utils';

onReady(() => {
    const feedbackBlocks = document.querySelectorAll<HTMLDivElement>('.uic-feedback');
    for (const feedbackBlock of feedbackBlocks) {
        const toggleButton = feedbackBlock.querySelector<HTMLAnchorElement>('.uic-feedback__toggle');
        checkSelector(toggleButton, 'feedback toggle button');
        const toggleText = feedbackBlock.querySelector<HTMLSpanElement>('.uic-feedback__toggle__text');
        checkSelector(toggleText, 'feedback toggle text');
        const container = feedbackBlock.querySelector<HTMLDivElement>('.uic-section__container');
        checkSelector(container, 'feedback container');
        toggleButton.addEventListener('click', e => {
            e.stopPropagation();
            e.preventDefault();
            if (feedbackBlock.classList.contains('uic-feedback--collapsed')) {
                toggleText.textContent = 'Close';
                toggleButton.setAttribute('title', 'Add Feedback');
                feedbackBlock.classList.remove('uic-feedback--collapsed');
                container.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                toggleText.textContent = 'Add Feedback';
                toggleButton.setAttribute('title', 'Close Feedback');
                feedbackBlock.classList.add('uic-feedback--collapsed');
            }
        });
        setTimeout(() => {
            if ('__UIC_FEEDBACK__' in window && window.__UIC_FEEDBACK__) {
                toggleText.textContent = 'Close';
                toggleButton.setAttribute('title', 'Add Feedback');
                feedbackBlock.classList.remove('uic-feedback--collapsed');
                container.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }, 500);

        
    }
});
 