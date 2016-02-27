
# PHP + MySQL Website Example



## Config project

* Edit `web/config.php` with your database details
```php
$db_host = "localhost";
$db_user = "username";
$db_pass = "password";
$db_name = "database";
```

* Place `web/` folder in your _apache_ server

* Init database with `init/tables.sql`
```ssh
$ mysql -h hostname -u username -ppassword
mysql> use database;
mysql> source tables.sql;
```


## Run project

* Insert example data in database (`init/insert_data.sql`)
```ssh
$ mysql -h hostname -u username -ppassword
mysql> use database;
mysql> source insert_data.sql;
```

* Browse to `http://hostname/index.php`


