# uic_admissions_wp_theme

## Add installer-paths to composer.json

```
    "extra": {
        "installer-paths": {
            "web/wp-content/themes/{$name}/": ["type:wordpress-theme"],
            "web/wp-content/plugins/{$name}/": ["type:wordpress-plugin"],
            "web/wp-content/mu-plugins/{$name}/": ["type:wordpress-muplugin"]
        }
    },
```

## Run `composer require uicosss/uic_admissions_wp_theme` after the codeblock above is added.
## Failure to follow the sequence will cause the assets not to be published.

