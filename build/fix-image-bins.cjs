/**
 * The imagemin binary packages (optipng-bin, mozjpeg) vendor prebuilt x86_64
 * Linux binaries that can't run on linux/arm64 (Apple Silicon Docker), and
 * their compile-from-source fallbacks also fail there (optipng's bundled
 * libpng breaks at link time on ARM NEON). npm silently drops the optional
 * ones, and buildImages crashes with errors like:
 *   Couldn't load default plugin "optipng"
 *   rosetta error: failed to open elf at /lib64/ld-linux-x86-64.so.2
 *
 * Runs as postinstall: for each tool, if the vendored binary doesn't work,
 * (re-)installs the package with install scripts disabled and points the
 * vendored path at the system binary instead. System binaries come from
 * `apt-get install optipng libjpeg-turbo-progs` (see docker/app.dockerfile);
 * on macOS, `brew install optipng jpeg-turbo`.
 *
 * cjpeg gets a wrapper rather than a symlink: mozjpeg's cjpeg accepts JPEG
 * input and defaults to progressive output; stock libjpeg-turbo's cjpeg does
 * neither, so the wrapper decodes with djpeg first and adds -progressive.
 */
const { execSync } = require('child_process');
const fs = require('fs');
const path = require('path');

const themeDir = path.join(__dirname, '..');

// Versions match package-lock.json. --ignore-scripts prevents the broken
// vendor-binary postinstalls (and this script) from running on re-install.
const TOOLS = [
    {
        install: 'imagemin-optipng@7.1.0',
        vendorBin: 'node_modules/optipng-bin/vendor/optipng',
        systemBin: 'optipng',
        testArgs: '--version',
    },
    {
        install: 'imagemin-mozjpeg@8.0.0',
        vendorBin: 'node_modules/mozjpeg/vendor/cjpeg',
        systemBin: 'cjpeg',
        testArgs: '-version',
        wrapper: (cjpeg) => {
            const djpeg = execSync('which djpeg').toString().trim();
            return `#!/bin/sh\n"${djpeg}" | "${cjpeg}" -progressive "$@"\n`;
        },
    },
];

const works = (bin, testArgs) => {
    try {
        execSync(`"${bin}" ${testArgs}`, { stdio: 'ignore' });
        return true;
    } catch {
        return false;
    }
};

for (const tool of TOOLS) {
    const vendorBin = path.join(themeDir, tool.vendorBin);
    if (works(vendorBin, tool.testArgs)) {
        continue;
    }

    let systemBin;
    try {
        systemBin = execSync(`which ${tool.systemBin}`).toString().trim();
    } catch {
        console.warn(`[fix-image-bins] no system ${tool.systemBin} found; buildImages will fail without it`);
        continue;
    }

    const pkgDir = path.join(themeDir, 'node_modules', tool.install.replace(/@[^@]*$/, ''));
    if (!fs.existsSync(pkgDir)) {
        execSync(`npm install ${tool.install} --no-save --ignore-scripts`, {
            cwd: themeDir,
            stdio: 'inherit',
        });
    }

    fs.mkdirSync(path.dirname(vendorBin), { recursive: true });
    fs.rmSync(vendorBin, { force: true });
    if (tool.wrapper) {
        fs.writeFileSync(vendorBin, tool.wrapper(systemBin), { mode: 0o755 });
    } else {
        fs.symlinkSync(systemBin, vendorBin);
    }
    console.log(`[fix-image-bins] ${tool.vendorBin} -> ${systemBin}${tool.wrapper ? ' (wrapper)' : ''}`);
}
