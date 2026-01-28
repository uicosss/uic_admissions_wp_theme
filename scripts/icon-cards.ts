import { onReady } from './utils';

/**
 * ██████  ███████  █████  ██████      ███    ███ ███████ ██ 
 * ██   ██ ██      ██   ██ ██   ██     ████  ████ ██      ██ 
 * ██████  █████   ███████ ██   ██     ██ ████ ██ █████   ██ 
 * ██   ██ ██      ██   ██ ██   ██     ██  ██  ██ ██         
 * ██   ██ ███████ ██   ██ ██████      ██      ██ ███████ ██ 
 *
 * when implemented in wordpress, this should be done server-side
 * as the implementation would be trivial and there is no reason
 * (and a few minor downsides) to doing this in browser
 * 
 * basically, what we want is:
 * 
 *   1. there is no sequential whitespace
 *      example input:
 *          once upon      a time
 *      example output:
 *          once upon a time
 *   2. each word gets put in a <span> tag, the last <span> tag also includes the arrow
 *      example input:
 *          <span class="uic-icon-cards__card__text">
 *              once upon a time <span class="uic-icon-cards__card__arrow"></span>
 *          </span>
 *      example output:
 *          <span class="uic-icon-cards__card__text">
 *              <span>once </span>
 *              <span>upon </span>
 *              <span>a </span>
 *              <span>time<span class="uic-icon-cards__card__arrow"></span></span>
 *          </span>
 */

onReady(() => {
    const cardTexts = document.querySelectorAll('.uic.uic-prototype .uic-icon-cards__card__text');
    for (const cardText of cardTexts) {
        const [ text ] = cardText.innerHTML.replace(/\s+/g, ' ').trim().split('<span');
        const words = text.trim().split(/\s/g);

        cardText.innerHTML = (
            words.map((word, i) => {
                if (i === words.length - 1) {
                    return `<span>${word} <span class="uic-icon-cards__card__arrow"></span></span>`
                } else {
                    return `<span>${word} </span>`;
                }
            }).join('')
        );
    }
});
