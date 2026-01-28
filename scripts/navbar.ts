import { checkSelector, onReady } from './utils';

const findParent = element => {
    let parent = element.parentElement;
    while (parent) {
        if (parent.classList.contains('uic')) {
            return parent;
        } else {
            parent = parent.parentElement;
        }
    }
    return null;
};

const setNavToggleState = (el: HTMLDivElement, state?: boolean) => {
    if (state === true) {
        el.classList.add('uic-navbar--open');
        
    } else if ( (state === false)) {
        el.classList.remove('uic-navbar--open');
        
    } else { // toggle 
        if (el.classList.contains('uic-navbar--open')) {
            el.classList.remove('uic-navbar--open');
        } else {
            el.classList.add('uic-navbar--open');
        }
    }
};

onReady(() => {
    const navBars = document.querySelectorAll<HTMLDivElement>('.uic-navbar');
    for (const navbar of navBars) {
        const toggle = navbar.querySelector<HTMLDivElement>('.uic-navbar__toggle');
        checkSelector(toggle, 'navbar toggle')
        toggle.addEventListener('click', e => {
            e.stopPropagation();
            e.preventDefault();
            const root = findParent(e.target);
            checkSelector(root, 'uic root');
            setNavToggleState(root);

            if (root.classList.contains('uic-navbar--open')) {
                toggle.setAttribute('aria-expanded', 'true');
            }
        });

        const items = navbar.querySelectorAll<HTMLAnchorElement>('.uic-navbar__item');
        for (const item of items) {
            item.addEventListener('click', e => {
                const root = findParent(e.target);
                checkSelector(root, 'uic root');
                setNavToggleState(root, false);
            });
        }
    }
});
 