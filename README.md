# UIC Admissions WordPress Theme

Shared WordPress theme distributed as a Composer package (`uicosss/uic_admissions_wp_theme`). This repo is not a standalone WordPress install — it's meant to be pulled into a parent site's `wp-content/themes` via Composer.

## Requirements

- PHP `>=7.4`
- Node `v22.23.2` (pinned in `.nvmrc` — run `nvm use`)
- Composer 2.x

## Setup

### Add installer-paths to composer.json

```
    "extra": {
        "installer-paths": {
            "web/wp-content/themes/{$name}/": ["type:wordpress-theme"],
            "web/wp-content/plugins/{$name}/": ["type:wordpress-plugin"],
            "web/wp-content/mu-plugins/{$name}/": ["type:wordpress-muplugin"]
        }
    },
```

### Run `composer require uicosss/uic_admissions_wp_theme` after the codeblock above is added.
**Note**: Failure to follow the sequence will cause the assets not to be published.

```bash
composer install
nvm use
npm install
npm run build
```

For Lando-based local development, use `lando composer install`; `nvm`/`npm` commands can be run on the host.

## Development workflow

- `npm run dev` — watches Panini templates and webpack assets together (browser-sync), rebuilding into `dist/`. Clears `dist/` on start.
- `npm run build` — production build (webpack, then Panini), also clears `dist/` first.
- `npm run build:webpack` / `npm run build:panini` / `npm run build:images` — run a single build step in isolation.

Compiled output goes to `dist/`, which is gitignored — never commit built assets.

## Project structure

- `inc/` — theme includes: setup, ACF blocks, forms, navigation, options, script/style enqueues, utilities.
- `template-parts/` — reusable PHP template partials.
- `styleguide/` — Panini source (basics, components, blocks, layouts, partials, pages) compiled by `build:panini`; use this to build and preview markup patterns in isolation from WordPress.
- `scripts/` — TypeScript sources bundled by webpack into `dist/`.
- `styles/` — Sass sources.
- `acf-json/` — local JSON sync for Advanced Custom Fields field groups. Export any ACF field group changes here and commit them — don't leave field definitions only in the database.
- `stubs/` — PHP stubs (for IDE/static analysis support).

## Branching & versioning

- `main` and `qa` are the long-lived branches; feature work happens on feature branches.
- Consuming sites pull this theme as a versioned Composer dependency (see `composer.json`). When a change should be picked up downstream, cut a new tag (e.g. `v1.0.0`) after merging to `main`, and bump the constraint in the consuming site's `composer.json`.
