import { checkSelector, onReady, pollSelector, waitForSelector } from './utils';



/**
 * inject failsafe "chat is currently unavailable" message
 * 
 * @param chatWidget {HTMLDivElement} chat widget to inject message to
 * @returns {HTMLDivElement} the failsafe message div that was created
*/
function appendFailsafe(chatWidget: HTMLDivElement): HTMLDivElement {
    const failSafe = document.createElement('div');
    failSafe.classList.add('uic-chat__failsafe');
    chatWidget.appendChild(failSafe);

    const failSafeMsgContainer = document.createElement('div');
    failSafeMsgContainer.classList.add('uic-chat__failsafe-msg-container');
    failSafe.appendChild(failSafeMsgContainer);
    const failSafeMsg = document.createElement('div'); 
    failSafeMsg.classList.add('uic-chat__failsafe-msg');
    failSafeMsg.innerHTML = 'Chat is currently unavailable.<br />Please try again later.<br /><br />Our normal hours are:<br />Monday - Friday<br />1:00 pm - 5:00 pm';
    failSafeMsgContainer.appendChild(failSafeMsg);
    return failSafe;
}

/**
 * Adds a custom close button to the chat widget because their's only appears sometimes
 * 
 * @param chatWidget {HTMLDivElement} chat widget to inject close button into
 * @param onClose {() => void} function to call when the button is clicked
 * @returns {HTMLButtonElement} our custom close button
 */
function appendCloseBtn(chatWidget: HTMLDivElement, onClose: () => void) {
    const bar = document.createElement('div');
    bar.classList.add('uic-chat__bar');
    bar.innerHTML = 'UIC Admissions';
    chatWidget.appendChild(bar);
    const closeBtn = document.createElement('button');
    closeBtn.classList.add('uic-chat__close');
    closeBtn.setAttribute('type', 'button');
    closeBtn.setAttribute('aria-label', 'Close Chat');
    chatWidget.appendChild(closeBtn);
    closeBtn.addEventListener('click', e => {
        e.stopPropagation();
        e.preventDefault();
        onClose();
    });
    return closeBtn;
}

/**
 * Create a fake chat widget because the real one decided not to load.
 */
function createFakeWidget(): HTMLDivElement {
    const fakeWidget = document.createElement('div');
    document.body.append(fakeWidget);
    fakeWidget.setAttribute('id', 'siWidget-chat--fake');
    
    fakeWidget.classList.add('siClosed--fake', 'uic-chat--ready');

    appendFailsafe(fakeWidget);
    appendCloseBtn(fakeWidget, () => fakeWidget.classList.remove('siScale--fake'));
    return fakeWidget;
}

/**
 * Hides and removes the 3rd-party chat button from accessability tree because it
 * is redundant and we don't control it like we do the chat fab.
 * @returns {HTMLDivElement} the 3-rd party chat button
 */
function getNeutralizedChatBtn(): HTMLButtonElement {
    const chatBtn = document.querySelector<HTMLButtonElement>('#silc-btn');
    checkSelector(chatBtn, 'chat button');
    const innerBtn = chatBtn.querySelector('.silc-btn-button');
    checkSelector(innerBtn, 'inner chat button');
    chatBtn.setAttribute('tabindex', '-1'); // disable tabbing to the chat button 
    chatBtn.setAttribute('area-hidden', 'true');
    innerBtn.setAttribute('tabindex', '-1'); // because fab will take care of that
    innerBtn.setAttribute('area-hidden', 'true');
    return chatBtn;
}

/**
 * Hooks into the (non-faked) chat widget
 * @param chatWidget {HTMLDivElement} the DOM element of the real chat widget
 * @returns {() => void} a function that will open the chat widget (to be called by the fab click handler)
 */
function initRealChatWidget(chatWidget: HTMLDivElement): () => void {
    const chatBtn = getNeutralizedChatBtn();
    let chatOpen = false;
    let hasBeenManuallyOpened = false;  
    appendFailsafe(chatWidget);
    appendCloseBtn(chatWidget, () => chatBtn.click());

    const iframe = chatWidget.querySelector<HTMLIFrameElement>('iframe');
    iframe?.setAttribute('scrolling', 'auto');
    iframe?.setAttribute('title', 'Chat Window');

    // intercept embed script's height queries and break them so it's attempts to resize chat window fails (because otherwise it's jacked up)
    if ($) {
        const h = $.fn.height;
        $.fn.height = function(this: NodeListOf<HTMLElement>, ...args: any[]) {
            const result = h.apply(this, args)
            if (typeof result === 'number' && this.length === 1) {
                if (this[0].id === 'siWidget-chat') {
                    return null;
                }
            }
            return result;
        } as any;
    }

    const chatWatcher = new MutationObserver(mutations => {
        if (!hasBeenManuallyOpened) {
            // it tried to auto open. Such behavior is cancerous and cannot be permitted.
            // thus we will close it with prejudice. -- oh copilot, thou wax poetic
            if (chatWidget.classList.contains('siScale')) chatWidget.classList.remove('siScale');
            chatOpen = false;
        } else {
            chatOpen = chatWidget.classList.contains('siScale');
        }
    });
    chatWatcher.observe(chatWidget, { attributes: true, attributeFilter: ['class'] });

    return () => {
        checkSelector(chatBtn, 'chat button');
        if (!hasBeenManuallyOpened) {
            hasBeenManuallyOpened = true;
            chatWidget.classList.add('uic-chat--ready');
        }
        chatBtn.click();
    };
}
const openOnLoad = window.location.hash === '#chat';

onReady(async () => {
    let onFabClick: () => void = () => {};
    const chatFab = document.querySelector('.uic-chat__trigger');
    checkSelector(chatFab, 'chat fab');

    chatFab.addEventListener('click', e => {
        e.stopPropagation();
        e.preventDefault();
        onFabClick();
    });
    let chatWidget = document.querySelector<HTMLDivElement>('#siWidget-chat');
    if (chatWidget) {
        // chat is already loaded. We can just hook into it.
        onFabClick = initRealChatWidget(chatWidget);
        if (openOnLoad) onFabClick();
    } else {
        // chat hasn't been added to the DOM. It may never load probably never if e it's after
        // hours or whatever. But it could also be because the internet is slow and it could appears in
        // the DOM during the next tick... but we can't count on that. We'll create a simulacra of the 
        // chat widget but will keep an eye out for the real one (if it ever shows up).
        chatWidget = createFakeWidget();
        onFabClick = () => chatWidget?.classList.toggle('siScale--fake');
        if (openOnLoad) onFabClick();
        let realWidget = await waitForSelector<HTMLDivElement>('#siWidget-chat', 1000 * 90);
        if (!realWidget) {
            // it's been 90 seconds!! It's highly unlikely it will even but lets switch
            // to a polling every 5 seconds instead of watching the DOM.
            realWidget = await pollSelector<HTMLDivElement>('#siWidget-chat', 1000 * 5);
        }
        // console.log('Chat has been loaded! Replacing the fake widget with the real one.');
        const openAtLoad = chatWidget.classList.contains('siScale--fake');
        const fakeWidget = chatWidget;
        chatWidget = realWidget;
        onFabClick = initRealChatWidget(chatWidget);
        // the fake chat was open so we'll open the real one right away
        if (openAtLoad) onFabClick();
        // and we'll remove the fake chat
        fakeWidget.remove();
    } 


});
