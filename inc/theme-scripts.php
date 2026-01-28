<?php

function register_theme_scripts() {
    $manifest = json_decode(file_get_contents(__DIR__ . '/../dist/manifest.json'), true);
    wp_enqueue_script('runtime', THEME_ASSET_BASE . '/' . $manifest['runtime.js'], [], false, true);
    wp_enqueue_script('vendor', THEME_ASSET_BASE . '/' . $manifest['vendor.js'], [], false, true);
    wp_enqueue_script('client', THEME_ASSET_BASE . '/' . $manifest['client.js'], [], false, true);
    wp_enqueue_script('chat', '//www.socialintents.com/api/chat/socialintents.1.3.js#2c9fa23c71582b2e0171656ba08c1ad0', [], false, true);
}

add_action('wp_enqueue_scripts', 'register_theme_scripts');

add_action( 'wp_footer', function() {
?>
    <script src="https://cdn.jsdelivr.net/npm/cookieconsent@3/build/cookieconsent.min.js" data-cfasync="false"></script>
    <script>
        if ('cookieconsent' in window
            && typeof window.cookieconsent === 'object'
            && 'initialise' in window.cookieconsent
            && typeof window.cookieconsent.initialise === 'function'
        ) {
            window.cookieconsent.initialise({
                "palette": {
                    "popup": {
                        "background": "#00539b",
                        "text": "#ffffff"
                    },
                    "button": {
                        "background": "#ffffff",
                        "text": "#00539b"
                    }
                }
            });
        }
    </script>
<?php
}, 100 );