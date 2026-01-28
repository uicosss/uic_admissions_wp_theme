import { resolve } from 'path';
import inspector from 'inspector';

import * as url from 'url';

const __filename = url.fileURLToPath(import.meta.url);
const __dirname = url.fileURLToPath(new URL('.', import.meta.url));

export const NODE_ENV = process.env.NODE_ENV;

export const MODE = (NODE_ENV === 'production'
    ? 'production'
    : 'development'
);
export const IS_JENKINS = !!process.env['JENKINS_URL'];

export const DEBUGGER_PRESENT = (() => {
    if ('DEBUGGER_PRESENT' in process.env) return true;
    else if (typeof v8debug === 'object') return true;
    else if (/--debug|--inspect/.test(process.execArgv.join(' '))) return true;
    else {
        if (inspector.url()) {
            return true;
        } else {
            return false;
        }
    }
})();

export const BUILD_DIR = __dirname;
export const ROOT_DIR = resolve(__dirname, '..');
export const BUNDLE_STATS = !!process.env.BUNDLE_STATS;

export const PLATFORM = process.env.PLATFORM || 'web';

export const PORT = process.env.PORT || 3000;
export const SERVER_PORT = process.env.SERVER_PORT || 8080;

export const HOSTNAME =  process.env.HOSTNAME || 'localhost';
export const PROTOCOL = (process.env.PROTOCOL || 'http').toLowerCase();
export const BASE_URL = process.env.BASE_URL || `${PROTOCOL}://${HOSTNAME}:${PORT}/`;
export const PUBLIC_BASE = process.env.PUBLIC_BASE  || '/wp-content/themese/thirdwavellc/dist';

export const DIST_DIR = resolve(ROOT_DIR, 'dist');
export const SCRIPTS_DIR = resolve(ROOT_DIR, 'scripts');
export const STYLES_DIR = resolve(ROOT_DIR, 'styles');

export let baseIndent = 0;
export const setBaseIndent = (indent) => baseIndent = indent;

export default {
    MODE,
    DEBUGGER_PRESENT,
    NODE_ENV,
    BUNDLE_STATS,
    PORT,
    SERVER_PORT,
    HOSTNAME,
    PROTOCOL,
    PUBLIC_BASE,
    BASE_URL,
    ROOT_DIR,
    DIST_DIR,
    SCRIPTS_DIR,
    STYLES_DIR
};