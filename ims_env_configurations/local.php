<?php

/**
 * Local host configuration file
 */

// Default Timezone
date_default_timezone_set("Asia/Karachi");

define('BASE_URL',                        'http://local.ims.com:8081/');
define('USERS_PER_PAGE',                  2);
define('PRODUCTS_PER_PAGE',               2);
define('COMPANIES_PER_PAGE',              2);
define('PURCHASE_ORDERS_PER_PAGE',        1);
define('URI_SEGMENT',                     4);
define('AUTOCOMPLETE_RECORD_LIMIT',       6);

/**
 * DB Configurations
 */

define('DB_HOST',       'localhost');
define('DB_USER',       'root');
define('DB_PASSWORD',   'password');
define('DB_NAME',       'ims');
define('DB_DRIVER',     'mysqli');

/**
 * Cookie Domain
 */

define('DOMAIN',     'local.ims.com');