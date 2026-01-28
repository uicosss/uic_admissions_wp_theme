import Player from '@vimeo/player';
import { onReady, sleep } from './utils';


onReady(() => {
    const heros = document.querySelectorAll<HTMLDivElement>('.uic-video-hero');

    for (const hero of heros) {
        const videoId = Number((hero.attributes['data-video-id'] ?? { value: null }).value);
        if (!videoId) {
            // console.log('no video id attached to this video hero', hero);
            continue; // don't do anything else with this hero
        }

        let playerIFrame: HTMLDivElement | null = null;
        const contentEl = hero.querySelector<HTMLDivElement>('.uic-video-hero__content');
        const videoEl = hero.querySelector<HTMLDivElement>('.uic-video-hero__video');
        const controlEl = hero.querySelector<HTMLDivElement>('.uic-video-hero__control');

        if (!contentEl) throw new Error('could not locate content element!');
        if (!videoEl) throw new Error('could not locate video element!');
        if (!controlEl) throw new Error('could not locate control element');

        let playerReady = false;
        let playQueued = false;
        let playerState: 'unloaded' | 'loading' | 'playing' | 'paused' = 'unloaded';
        const player = new Player(videoEl, {
            id: videoId,
            responsive: false,
            loop: false
        });
        player.on('loaded', () => {
            playerReady = true;
            playerIFrame = hero.querySelector<HTMLDivElement>('.uic-video-hero__video iframe');
            if (playerIFrame) {
                playerIFrame.setAttribute('tabindex', '-1');
            }
            if (playQueued) {
                playQueued = false;
                player.play().catch(e => {});
            }
        });
        player.on('play', () => {
            playerState = 'playing';
            if (!playerIFrame) playerIFrame = hero.querySelector<HTMLDivElement>('.uic-video-hero__video iframe');
            if (playerIFrame) {
                playerIFrame.setAttribute('tabindex', '0');
                playerIFrame.focus();           
            }
            controlEl.setAttribute('aria-label', 'Pause video');
            controlEl.setAttribute('title', 'Pause video');
            controlEl.textContent = 'Pause video';
            hero.classList.add('uic-video-hero--playing');
            controlEl.classList.add('uic-video-hero__control--pause');
            controlEl.classList.remove('uic-video-hero__control--play');
            controlEl.classList.remove('uic-video-hero__control--loading');
        });
        player.on('ended', () => {
            playerState = 'paused';
            if (playerIFrame) {
                playerIFrame.setAttribute('tabindex', '-1');
                controlEl.focus();
            }
            controlEl.setAttribute('aria-label', 'Play video');
            controlEl.setAttribute('title', 'Play video');
            controlEl.textContent = 'Play video';
            hero.classList.remove('uic-video-hero--playing');
            controlEl.classList.add('uic-video-hero__control--play');
            controlEl.classList.remove('uic-video-hero__control--pause');
            controlEl.classList.remove('uic-video-hero__control--loading');
        });

        // we'll load the video, try to get its true dimensions
        (Promise.all([ player.getVideoWidth(), player.getVideoHeight() ])
            .then(async ([width, height]) => {
                // to avoid layout trashing, we'll apply as class which should will animate any change in height
                hero.classList.add('uic-video-hero--height-adjusting');
                await sleep(50);
                hero.style.setProperty('--uic-video-hero--video-width', String(width));
                hero.style.setProperty('--uic-video-hero--video-height', String(height));
                await sleep(2250);
                // but we'll remove the animation when we're done (although we can't truly be sure animation has completed) because we
                // don't want the height to animate if say the page is resized.
                hero.classList.remove('uic-video-hero--height-adjusting');
            })
            .catch(e => console.error('error getting video dimensions', e))
        );

    
        const startVideo = () => {
            if (playerState === 'unloaded') {
                playerState = 'loading';
                controlEl.setAttribute('aria-label', 'Loading video');
                controlEl.setAttribute('title', 'Loading video');
                controlEl.textContent = 'Loading video';
                controlEl.classList.add('uic-video-hero__control--loading');
                controlEl.classList.remove('uic-video-hero__control--pause');
                controlEl.classList.remove('uic-video-hero__control--play');     
                
                // if player is not fully loaded, to avoid issue described here
                // https://developer.chrome.com/blog/play-request-was-interrupted/
                // we mark this flag so the listener for `loaded` event knows to begin playback
                if (playerReady) player.play().catch(e => {});
                else {
                    playQueued = true;
                }
            } else if (playerState === 'loading') {
                playerState = 'unloaded';
                if (playerReady) player.pause().catch(e => {});
                else {
                    playQueued = false;
                }
                controlEl.classList.add('uic-video-hero__control--play');
                controlEl.classList.remove('uic-video-hero__control--pause');
                controlEl.classList.remove('uic-video-hero__control--loading');      
            } else if (playerState === 'paused') {
                playerState = 'playing';
                controlEl.setAttribute('aria-label', 'Pause video');
                controlEl.setAttribute('title', 'Pause video');
                controlEl.textContent = 'Pause video';
                controlEl.classList.add('uic-video-hero__control--playing');
                controlEl.classList.remove('uic-video-hero__control--pause');
                controlEl.classList.remove('uic-video-hero__control--loading');      
                player.play().catch(e => {});
            } else if (playerState === 'playing') {
                playerState = 'paused';
                controlEl.setAttribute('aria-label', 'Resume video');
                controlEl.setAttribute('title', 'Resume video');
                controlEl.textContent = 'Resume video';
                controlEl.classList.add('uic-video-hero__control--pause');
                controlEl.classList.remove('uic-video-hero__control--loading');
                controlEl.classList.remove('uic-video-hero__control--play');      
                player.pause().catch(e => {});
            }
        };
        controlEl.addEventListener('click', startVideo);
    }
});
 