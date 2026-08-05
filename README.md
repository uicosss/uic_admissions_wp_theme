# uic_admissions_wp_theme

## Use composer `composer require uicosss/uic_admissions_wp_theme`

## Add additional installer-paths, run composer require uicosss/uic_admissions_wp_theme after the codeblock below is added.

```
    "extra": {
        "installer-paths": {
            "web/wp-content/themes/{$name}/": ["type:wordpress-theme"],
            "web/wp-content/plugins/{$name}/": ["type:wordpress-plugin"],
            "web/wp-content/mu-plugins/{$name}/": ["type:wordpress-muplugin"]
        }
    },
```

## Failure to follow the sequence will cause the assets to not be published.

