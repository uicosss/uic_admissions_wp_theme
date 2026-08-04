# Install the theme package uic_admissions_wp_theme

Using the uic_admissions_wp_theme API (contact UIC OSSS for additional details on API)

## To use the package, you need to include in composer.json
```
"require": {
        "uicosss/uic_admissions_wp_theme": "^1.0"
    },
    "extra": {
        "installer-paths": {
            "web/wp-content/themes/{$name}/": ["type:wordpress-theme"],
            "web/wp-content/plugins/{$name}/": ["type:wordpress-plugin"],
            "web/wp-content/mu-plugins/{$name}/": ["type:wordpress-muplugin"]
        }
```

## Then install dependencies 
```
composer install
```

## Or, if updating an existing project:
```
composer update
```