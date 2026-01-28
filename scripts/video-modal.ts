import BiggerPicture from 'bigger-picture/vanilla';
import { checkSelector, onReady } from './utils';

onReady(() => {
    const modalTriggers = document.querySelectorAll('.uic-video-modal__trigger');
    if (modalTriggers) {
        /**
         * Put a class on body so we can gain specificity & override css.
         * Providing our own target container to bigger-picture causes video not
         * to be displayed until window resizes for some reason event though overlay and close button appear. 
         */
        const container = document.body.classList.add('uic-video-modal__container');

        const modal = BiggerPicture({
            target: document.body
        });
        for (const modalTrigger of modalTriggers) {
            modalTrigger.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                const modalTriggerTarget = e.currentTarget as HTMLDivElement;
                checkSelector(modalTriggerTarget, 'modal trigger')
                const height = Number((modalTriggerTarget.attributes['data-height'] ?? { value: '1080' }).value);
                const width = Number((modalTriggerTarget.attributes['data-width'] ?? { value: '1920' }).value);
                document.body.style.setProperty(`--uic-video-modal--height`, String(height));
                document.body.style.setProperty(`--uic-video-modal--width`, String(width));
                modal.open({
                    items: modalTriggerTarget,
                    el: modalTriggerTarget,
                    intro: 'fadeup'
                });
            })
        }
    }
});
