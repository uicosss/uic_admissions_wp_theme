import { onReady } from './utils';

onReady(() => {
    const scrollBtns = document.querySelectorAll<HTMLDivElement>('.uic-back-to-top');
    for (const scrollBtn of scrollBtns) {
        scrollBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            e.preventDefault();
            window.scrollTo({ top: 0, behavior: "smooth" });
        })
    }
});
