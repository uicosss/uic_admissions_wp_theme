import { onReady } from './utils';

// returns true if users's browser is on macOS
const isMacos = () => {
    if ('userAgentData' in navigator && navigator.userAgentData) {
        if ('platform' in (navigator.userAgentData as {})) {
            return (navigator.userAgentData as any).platform.toLowerCase().includes('mac');
        }
    } else return /(macintosh|macintel|macppc|mac68k|macos)/ig.test(navigator.userAgent);
    
}

onReady(() => {
    const skipLinks = document.querySelectorAll<HTMLAnchorElement>('.uic-skip-to-content');
    if (isMacos()) {
        for (const skipLink of skipLinks) {
            const keyCombo = skipLink.querySelector<HTMLSpanElement>('span:nth-child(2)');
            if (keyCombo) keyCombo.innerHTML = '(Option + 0)';
        }       
    }
    for (const skipLink of skipLinks) {
        skipLink.addEventListener('click', (e) => {
            skipLink.blur();
        });
    }
    if (skipLinks.length) {
        document.addEventListener('keydown', (e) => {
            if (e.key === '0' && (e.altKey || e.metaKey)) {
                skipLinks[0].focus();
                console.log(' do focus', e.key, e.altKey, e.metaKey)
            }
        });
    }
});

