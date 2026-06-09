# UIC Admissions


## Local Development
This codebase comes with Docker recipes to get you  up and going with an almost identical environment to PROD. 

### Prerequisites
1. [.env](.env) is created from a copy of [.env.example](.env.example)
1. While this file not explicitly needed to build the Docker containers (defaults are set in [docker-compose.yml](docker-compose.yml)), it is required to run [flash-db.sh](flash-db.sh) and [dump-db.sh](dump-db.sh).
2. Docker Desktop/Engine for your OS
3. A database dump from PROD: https://uofi.box.com/s/zh2887gkmffhdt5vvsh35f2qeeik81h1 .
   1. **Note:** This file should be saved to [database/backup](database/backup)
4. A dump of the [html/sites/default/files](html/sites/default/files) directory from PROD.
   5. SSH into the PROD server for Admissions, hint: `app2`
   6. Navigate to the root directory of the application `cd /www/sites/admissions.uic.edu`
   7. Create a compressed Tar of the site files: `tar -cvzf YYYYMMDD-HHMM-admissions-files.tar.gz html/sites/default/files`
      8. This will create the archive with the directory structure intact.
   9. Download the newly created `tar.gz` file into the root of your local Admissions directory.
### Configuration
1. Create the [.env](.env) file from a copy of [.env.example](.env.example).
   1. The values are mostly arbitrary, but make sure to avoid changing the `DB_*` values are not updated after your database container is built. Otherwise you'll have to destory the database container, image, and volume.
2. Fill out the .env
3. Run your Docker container setup `docker compose up --build -d`. **Note:** the `-d` flag is  so the containers run in the background.
3. Create the [settings.php](html/sites/default/settings.php) from a copy of [default.settings.php](html/sites/default/default.settings.php)
4. Update your [settings.php](html/sites/default/settings.php) around the line 213 with a custom value for `$databases`. **Note:** For local development, the values in this file should be referenced from [.env](.env) . Example:
```PHP
$databases = array (
  'default' =>
    array (
      'default' =>
        array (
          'database' => 'admissions_local', // The value for DB_DATABASE in .env
          'username' => 'mysql_user', // The value for DB_USERNAME in .env
          'password' => 'localdev', // The value for DB_PASSWORD in .env
          'host' => 'database', // The value for DB_HOST in .env
          'port' => '3306', // The value for DB_PORT in .env
          'driver' => 'mysql', // The value for DB_CONNECTION in .env
          'prefix' => '',
        ),
    ),
);
```
5. Take your database dump and unzip the gzip, eg. `docker compose exec app gunzip -d database/backup/YYMMDD-HHMMSS-admissions_prod.sql.gz`
6. Take your database dump and flash the dockerized mysql server with the data from the dump.
   1. Windows Users: Follow step 6 from the article [Updating Local mySQL Development Databases for Laravel Sites with Production Data](https://uicosss.atlassian.net/wiki/spaces/DEV/pages/36503553/Updating+Local+mySQL+Development+Databases+for+Laravel+Sites+with+Production+Data)
   2. Non-Windows Users: eg. `docker compose exec app bash flash-db.sh`. You will be prompted for the relative path to the non-gzipped dump file, eg. `database/backup/YYMMDD-HHMMSS-admissions_prod.sql`. Once this command completes the data from the dump will be imported to your dockerized MySql/MariaDB container
7. Decompress the PROD dump of the files directory into `html/sites/default/files`
   8. Make sure the `tar.gz` of the site files is in the root of the application
   9. Decompress the file `docker compose exec app tar -xvzf YYYYMMDD-HHMM-admissions-files.tar.gz` (this will automatically put the files into `html/sites/default/files` since the directory should be in the archive)
8. Access the site in a local browser: `http://localhost:8090`, where `8090` is the value of `APP_PORT` from your [.env](.env). If you did not setup a [.env](.env), then the default value is `8080`.
9. Install NPM and Grunt
   1. Grunt is the tool used to consolidate and uglify JavaScript and CSS. It's required to see these type of changes.
   2. In a terminal, navigate to this project and run `docker compose exec app /bin/bash` to access Bash (Windows-Users: This likely will require using PowerShell)
   3. In the Bash prompt, navigate to /var/www/html/sites/all/themes/custom/uicrec/
   4. Install NPM: run `npm ci`
   5. Run `node_modules/grunt-cli/bin/grunt` (from within `html/sites/all/themes/custom/uicrec`) to compile JavaScript and CSS assets
7. **Note**: Changes made to JavaScript or CSS assets that are compiled using `grunt` will only be seen on the site after flushing the cache. This is done within the site's admin dashboard. From the home icon in the admin navigation select "Flush all caches" or hovering into the subtree select "CSS and JavaScript".

## Additional Configuration
### Support for FQDNs
The default [.htaccess.example](.htaccess.example) has logic baked in to allow Apache webserver to upgrade requests to HTTPS when referring to local files, eg. CSS, JS, PNG, etc. However, if you are not using Apache, like in our Docker environment where we use Nginx, you can still force the Drupal instance to force the use of HTTPS by setting the `$base_url` value in [html/sites/default/settings.php](html/sites/default/settings.php).
```PHP
$base_url = 'https://earwig-topical-definitely.ngrok-free.app';  // NO trailing slash!
```
### Use HTTPS for fonts.google.com in Local Development
During local development the Google Fonts module, [html/sites/all/modules/contrib/fontyourface/modules/google_fonts_api/google_fonts_api.module](html/sites/all/modules/contrib/fontyourface/modules/google_fonts_api/google_fonts_api.module) line 115 only allows calling fonts.googleapis.com via HTTPS if `$_SERVER['HTTPS'] = 'on'`. This might not be set in your local development. You can add the SERVER var to [html/sites/default/settings.php](html/sites/default/settings.php) at the end.
```PHP
$_SERVER['HTTPS'] = 'on';
```
