import { onReady } from './utils';

onReady(() => {
    try {
        const script = document.createElement('script');
        if ('async') {
            script.async = true;
        }
        script.src = `${location.origin}:3000/browser-sync/browser-sync-client.js?v=2.28.3`.replace("HOST", location.hostname);
        if (document.body) {
            document.body.appendChild(script);
        }
    } catch (e) {
        console.error("Browsersync: could not append script tag", e);
    }
});
