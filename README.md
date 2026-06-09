# Thirdwave WordPress Demo

## WordPress Local Setup

1. To initialize a local instance of WordPress for development, first ensure you have [ngrok](https://dashboard.ngrok.com/get-started/setup) installed. 


2. Duplicate `./web/wp-config-sample.php` and change the name to `./web/wp-config.php`


3. In `./web/wp-config.php`, define values for your local database instance.
	```php
	/** The name of the database for WordPress */
	define( 'DB_NAME', 'your_choice' );
	
	/** Database username */
	define( 'DB_USER', 'your_choice' );
	
	/** Database password */
	define( 'DB_PASSWORD', 'your_choice' );
	
	/** Database hostname */
	define( 'DB_HOST', 'database' );
 	```
		
4. In `./web/wp-config.php`, define values for authentication unique keys and salts. Copy values found in `./web/wp-config.php` on the QA instance.
	```php
	define('AUTH_KEY','get_from_qa');
	define('SECURE_AUTH_KEY','get_from_qa');
	define('LOGGED_IN_KEY','get_from_qa');
	define('NONCE_KEY','get_from_qa');
	define('AUTH_SALT','get_from_qa');
	define('SECURE_AUTH_SALT','get_from_qa');
	define('LOGGED_IN_SALT','get_from_qa');
	define('NONCE_SALT','get_from_qa');
	```
	
5. In `./web/wp-config.php`, define values for events api. Copy values found in `./web/wp-config.php` on the QA instance.
	```php
	define( 'EVENTS_API_USER', 'get_from_qa' );
	define( 'EVENTS_API_PASS', 'get_from_qa' );
	define( 'EVENTS_API_KEY', 'get_from_qa' );
	
	define( 'EVENTS_API_ENDPOINT', 'get_from_qa' );
	```
	
6. In `./web/wp-config.php`, define values for the tuition calculator. Values for the tuition calculator are pulled from the site designated by the "UIC_SITE_ROLE=provider" value, typically the UIC Discover site. Copy values found in `./web/wp-config.php` on the QA instance.
	```php
	define('UIC_SITE_ROLE', 'get_from_qa');
    define('CALC_API_ENDPOINT', 'get_from_qa');
    define('UIC_SHARED_API_KEY', 'get_from_qa');
	```

7. In `./web/wp-config.php`, add your ngrok url or other FQDN url as the value for `WP_HOME` and `WP_SITEURL`
   ```php
   define( 'WP_HOME', 'your_fdqn_url' );
   define( 'WP_SITEURL', 'your_fdqn_url' );
   ```

8. Update the local database. Retrieve a fresh database dump from https://uofi.app.box.com/folder/198203537835 and follow the last step of https://uicosss.atlassian.net/wiki/spaces/DEV/pages/36503553/Updating+Local+mySQL+Development+Databases+for+Laravel+Sites+with+Production+Data

9. Copy the contents of `./web/wp-content/uploads` from the production or QA server. Save these files to your local `./web/wp-content/uploads`

10. In the project root, run `composer install` to install the theme. Note that since this theme is brought in via Composer, changes to it cannot be committed to Github.

## WordPress Theme Build

1. In a terminal, navigate to this project and run `docker compose exec app /bin/bash` to access Bash (Windows-Users: This likely will require using PowerShell)

2. In the project root, run  `cd web/wp-content/themes/uic_admissions_wp_theme`

3. Run the following commands:
    ```bash
    composer install
    nvm use (does not work)
    npm install
    npm run build
    ```
4. This will build the supporting assets of the theme including css, js, etc.

5. If you wish to modify the theme, then refer to https://github.com/uicosss/uic_admissions_wp_theme

## Site domain name
There might be situations where you need to use production data in non-prod environments. Before you can do that run the following steps:
1. Make sure that the keys in web/wp-config.php `WP_HOME` and `WP_SITEURL` is updated to the domain used in your environment.
2. Update the database using WP-CLI to reference the non-prod domain name `wp search-replace 'admissions.uic.edu' 'non-prod-environment.uic.edu'`

## Site accounts
If you are working from a brand new database, you'll have to create user accounts to use in the UI. You can use the following command to create an administrtor type role.
- `wp user create yourusername your@email.com --role=administrator`
If `WP-CLI` is not installed, follow the steps here: https://make.wordpress.org/cli/handbook/guides/installing/#recommended-installation .

## Updating Site admin email
After installing a production database dump to a non-prod location such as a local environment, you will want to update the admin email used for the website. You can do so by running the following command:
`docker-compose exec app wp option update admin_email aes_webmaster@uic.edu --allow-root`

