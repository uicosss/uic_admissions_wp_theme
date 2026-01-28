import { onReady } from './utils';

onReady(() => {
    setTimeout(() => {
        const sliders = document.querySelectorAll<HTMLDivElement>('.uic-social-slider');
        for (const slider of sliders) {
            const navs = slider.querySelectorAll<HTMLDivElement>('nav');
            for (const nav of navs) {
                if (nav.children.length === 0) continue;
                const parent = nav.parentElement as HTMLDivElement;
                const fakeNav = document.createElement('span');
                fakeNav.classList.add('fake-nav');
                parent.append(fakeNav);
                for (const child of nav.children) {
                    fakeNav.append(child);
                }
                nav.remove();
            }
        }

    }, 750);
})