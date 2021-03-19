### Installation guide

1. Composer requirements 

    `composer install`
2. Create Database
3. Create DB tables with doctrine
    ```
    ./vendor/bin/doctrine orm:schema-tool:create
    ./vendor/bin/doctrine orm:generate-proxies
    ```
4. Local configuration 
    `cp config/settings.php.dist config/settings.php`

5. Make specific folders writable
    ```
    chown -R :www-data var/
    chmod -R g+w var/
    chown -R :www-data public/img/article/
    chmod -R g+w public/img/article/
    ```
6 Server configuration - Use the provided `nginx.conf` template