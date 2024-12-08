installation:

clone this repository to your folder 
--------------------------requirements----------------------------
requirements: php 8.2

  composer version 2 

 run: composer install
    added: guzzlehttp/guzzle and predis/predis ( in case you want to use redis caching , need redis setup here )

    in replacement of DB we will use caching mechanism

 ----------------------working environment--------------------------

 vagrant
 ubuntu 20.04
-----------------DATABASE-----------------------------------
 Database: no database / no migration 
   in your .env file 
   DB_CONNECTION=null  // set this to null 
	#DB_HOST=127.0.0.1  // comment out 
	#DB_PORT=3306          // comment out
	#DB_DATABASE=laravel // commment out
	#DB_USERNAME=root // comment out 
	#DB_PASSWORD= // comment 

Additional .env variable 
WILLUSECACHING = 0  // use file caching if set to 1  

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



