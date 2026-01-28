import { onReady } from './utils';

onReady(() => {
    document.querySelectorAll('a[href^="#"], a[href^="/#"]').forEach(anchor => {
        anchor.addEventListener('click', e => {
            const href = anchor.getAttribute('href');
            if (!href || href === '#') return;
            const el = document.querySelector(href.replace(/^\//g, ''));
            if (el) {
                e.preventDefault();
                e.stopPropagation();                
                el.scrollIntoView({ behavior: 'smooth' });
            } else {
                console.log(`Failed to find targeted element by id: ${href}`)
            }
        });
    });
});