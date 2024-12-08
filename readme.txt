installation:

----------------------working environment--------------------------

 vagrant
 ubuntu 20.04

clone this repository to your folder 
--------------------------requirements----------------------------
requirements: php 8.2

  composer version 2 

 run: composer install
    added: guzzlehttp/guzzle and predis/predis ( in case you want to use redis caching , need redis setup here )

    in replacement of DB we will use caching mechanism

  ----------------------------generation and .env----------------------------

  copy .env.example to .env 
  run in your cli: php artisan key generate 
  open your .env file edit the database 

 
-----------------DATABASE-----------------------------------
 Database: no database / no migration 
   in your .env file 
   DB_CONNECTION=null  // set this to null 
	#DB_HOST=127.0.0.1  // comment out 
	#DB_PORT=3306          // comment out
	#DB_DATABASE=laravel // commment out
	#DB_USERNAME=root // comment out 
	#DB_PASSWORD= // comment 

Additional .env variable , add this to last line 
WILLUSECACHING=0  // use  caching if set to 1  

API_BASE_URL=https://dummyjson.com/


-----------------ENDPOINT----------------------------------

endpoint in route/api.php 
 api/defaultproduct -- can apply caching
 api/detail/{id}  -- can apply caching
 api/search  -- no caching 


 ---------------LIMITATIONS------------------
 - no endpoint for categories 
 - no next page or limit applied 
 - only display the  https://dummyjson.com/products ( default  homepage )
 - search result display - no next page / limit applied 
 - data does not save in DB, as per instruction stated above there is no db or migration script to run 



--------------------apache virtual host configuration -----------------------

<VirtualHost *:80>
        
        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/public/laravel-products/laravel2/public
        ServerName laravel-product.local

        ErrorLog ${APACHE_LOG_DIR}/laravel-product.local.log
        CustomLog ${APACHE_LOG_DIR}/laravel-product-access.log combined
        
        <Directory "/var/www/public/laravel-products/laravel2/public">
            #Options Indexes FollowSymLinks
            Options +FollowSymlinks
            AllowOverride all
            Require all granted
        </Directory>
        #<FilesMatch  "*.php$">
         # SetHandler "proxy:unix:/var/run/php/php8.0-fpm.sock|fcgi://localhost/"
        #</FilesMatch>
        <FilesMatch ".php$">
                 SetHandler "proxy:unix:/var/run/php/php8.2-fpm.sock|fcgi://localhost/"
      </FilesMatch>
</VirtualHost>