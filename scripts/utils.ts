export function checkSelector<E extends Element>(el: E | null, desc: string): asserts el is E {
    if (!el) throw new Error(`Could not locate ${desc} element!`)
};

export const sleep = (ms: number) => new Promise(r => setTimeout(r, ms));

export function onReady(fn: () => void) {
    if (document.readyState === 'interactive' || document.readyState === 'complete') {
        fn();
    } else {
        document.addEventListener('DOMContentLoaded', fn);
    }
}

export function throttle(fn: (...args: any[]) => any, ms: number) {
    let timeoutId: ReturnType<typeof setTimeout> | null = null;
    return (...args: any[]) => {
        if (timeoutId) return;
        timeoutId = setTimeout(() => {
            fn(...args);
            timeoutId = null;
        }, ms);
    };
}

export function throttledWatchForChildren<T extends Element, K extends HTMLElement>(
    target: T,
    update: (added: K[], removed: K[]) => void,
    selector: string | null = null,
    throttleMs: number = 100
) {
    // const throttled = throttle(cb, throttleMs);
    const additions = new Set<K>();
    const removals = new Set<K>();
    const throttledCb = throttle(() => {
        update(Array.from(additions), Array.from(removals));
        additions.clear();
        removals.clear();
    }, throttleMs);
    const observer = new MutationObserver((mutations) => {
        for (const mutation of mutations) {
            if (mutation.type !== 'childList') continue; // we only care about nodes coming and going
            for (const node of mutation.addedNodes) {
                if (node.nodeType === Node.ELEMENT_NODE) {
                    const el = node as K;
                    if (selector && !el.matches(selector)) continue;
                    additions.add(el);
                }
            }
            for (const node of mutation.removedNodes) {
                if (node.nodeType === Node.ELEMENT_NODE) {
                    const el = node as K;
                    if (selector && !el.matches(selector)) continue;
                    removals.add(el);
                }
            }
        }
        throttledCb();
    });
    observer.observe(target, {
        childList: true,
        subtree: true,
    });
    return observer;
}

// detect if the user is using a touch device
export const isTouchDevice = () => {
    // test media query pointer coarse
    if (window.matchMedia('(pointer: coarse)').matches) return true;
    return 'ontouchstart' in window || navigator.maxTouchPoints > 0 || (navigator as any).msMaxTouchPoints > 0;
}


/**
 * The listener is fired when the user clicks outside the `targetElement`.
 * @description Adds an event listener to the 'click' event to the document and returns a function that removes the listener.
 * @param {HTMLElement} targetElement the element to check if the click was inside of
 * @param {function} listener the listener to fire when the user clicks outside the `targetElement`
 * @returns {function} a function that removes the event listener
 */
export function onOutsideClick<E extends HTMLElement>(targetElement: E, listener:  (e: MouseEvent) => any): () => void {
    const cb = (e) => {
        if (targetElement.contains(e.target as Node)) return;
        listener(e);
    };
    document.addEventListener('click', listener);
    return () => {
        document.removeEventListener('click', listener);
    };
}

/**
 * Polls the selector until it matches an element in the DOM
 * @param selector {string} the CSS selector to match
 * @param interval {number} the poll interval in ms
 * @param timeout {number} how long to watch before giving up (in ms) (-1 for indefinite watching)
 * @returns {Promise<HTMLElement | null>} a promise that resolves to the matching element or null if the timeout is reached
 */
export function pollSelector<E extends Element>(selector: string, interval: number):  Promise<E>;
export function pollSelector<E extends Element>(selector: string, interval: number, timeout: number): Promise<E | null>;
export function pollSelector<E extends Element>(selector: string, interval: number, timeout: -1): Promise<E>;
export function pollSelector<E extends Element>(selector: string, interval: number = 1000, timeout: number = -1): Promise<E | null> {
    return new Promise((resolve, reject) => {
        let resolved = false;
        const el = document.querySelector<E>(selector);
        if (el) return resolve(el);
        const intervalId = setInterval(() => {
            if (resolved) return;
            const el = document.querySelector<E>(selector);
            if (el) {
                resolved = true;
                clearInterval(intervalId);
                resolve(el);
            }
        }, interval);
        if (timeout !== -1) {
            setTimeout(() => {
                if (resolved) return;
                resolved = true;
                clearInterval(intervalId);
                resolve(null);
            }, timeout);
        }
    });
}

/**
 * Waits for the selector to match an element in the DOM
 * @param selector {string} the selector to match against
 * @param timeout {number} the number of milliseconds to wait before discontinuing watching (-1 for indefinite watching)
 * @returns {Promise<HTMLElement | null>} a promise that resolves with the element that matches the selector or null if the timeout is reached
 */
export function waitForSelector<E extends Element>(selector: string): Promise<E>;
export function waitForSelector<E extends Element>(selector: string, timeout: number): Promise<E | null>;
export function waitForSelector<E extends Element>(selector: string, timeout: -1): Promise<E>;
export function waitForSelector<E extends Element>(selector: string, timeout: number = -1): Promise<E | null> {
    return new Promise((resolve, reject) => {
        let resolved = false;
        const el = document.querySelector<E>(selector);
        if (el) return resolve(el);
        const observer = new MutationObserver((mutations) => {
            if (resolved) return;
            const el = document.querySelector<E>(selector);
            if (el) {
                observer.disconnect();
                resolved = true;
                resolve(el);
            }
        });
        if (timeout !== -1) {
            setTimeout(() => {
                if (resolved) return;
                else {
                    // we've run out of time; discontinue watching, resolve with null
                    observer.disconnect();
                    resolved = true;
                    resolve(null);
                }
            }, timeout);
        }
        observer.observe(document.body, {
            childList: true,
            subtree: true
        });
    });
}