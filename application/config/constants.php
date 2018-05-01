<?php
defined('BASEPATH') OR exit('No direct script access allowed');

defined('DS') OR define('DS', DIRECTORY_SEPARATOR);


/*
|--------------------------------------------------------------------------
| Locate admin assets 
|--------------------------------------------------------------------------
|
| This will locate all the assets i.e. CSS files, JS files, Images, Fonts
| etc. 
| 
*/

defined('BASE_URL') OR define('BASE_URL','http://'.$_SERVER['HTTP_HOST'].'/easy-shop/');
defined('ADMIN_ASSETS') OR define('ADMIN_ASSETS', BASE_URL . 'assets/admin/');

/*
|--------------------------------------------------------------------------
| Locate front_end assets 
|--------------------------------------------------------------------------
|
| This will locate all the assets i.e. CSS files, JS files, Images, Fonts
| etc. 
| 
*/

defined('BASE_URL') OR define('BASE_URL','http://'.$_SERVER['HTTP_HOST'].'/easy-shop/');
defined('FRONT_END_ASSETS') OR define('FRONT_END_ASSETS', BASE_URL . 'assets/front_end/');

/*
|--------------------------------------------------------------------------
| Locate Excel Files 
|--------------------------------------------------------------------------
|
| This will locate all the Excel Files
| 
*/

define('ADMIN_EXCEL_PATH', realpath(FCPATH  . 'assets/admin/excel/'));

define('JSON_FILE_PATH', realpath(FCPATH  . 'assets/admin/json/'));

/*
|--------------------------------------------------------------------------
| Locate ADMIN images 
|--------------------------------------------------------------------------
|
| This will locate all the users images
| 
*/

define('ADMIN_IMAGES_PATH', BASE_URL . 'assets/admin/images/');
// define('USER_IMAGE_PATH', realpath(FCPATH  . 'assets/admin/images/users/'));

/*
|--------------------------------------------------------------------------
| Locate Front End images 
|--------------------------------------------------------------------------
|
| This will locate all the users images
| 
*/

// define('FRONT_END_IMAGES_PATH', realpath(FCPATH  . 'assets/front_end/images/'));
define('FRONT_END_IMAGES_PATH', BASE_URL . 'assets/front_end/images/');

/*
|--------------------------------------------------------------------------
| Locate images folder
|--------------------------------------------------------------------------
|
| This will locate folder containing all the images
| 
*/

define('PRODUCT_IMAGE_PATH', realpath(FCPATH  . 'assets/admin/images/products/'));
define('PRODUCT_IMAGE', ADMIN_IMAGES_PATH  . 'products/');

/*
|--------------------------------------------------------------------------
| Locate Gallery images folder
|--------------------------------------------------------------------------
|
| This will locate folder containing all the images
|
*/

define('GALLERY_IMAGE_PATH', realpath(FCPATH  . 'assets/admin/images/gallery/'));
define('GALLERY_IMAGE', ADMIN_IMAGES_PATH  . 'gallery/');

/*
|--------------------------------------------------------------------------
| Locate parties images 
|--------------------------------------------------------------------------
|
| This will locate all the politicians images
| 
*/

define('PARTY_IMAGE_PATH', realpath(FCPATH  . 'assets/admin/images/political_parties/'));
define('PARTY_IMAGE', ADMIN_IMAGES_PATH  . 'political_parties/');

/*
|--------------------------------------------------------------------------
| Locate newspaper images 
|--------------------------------------------------------------------------
|
| This will locate all the user images
| 
*/

define('NEWSPAPER_IMAGE_PATH', realpath(FCPATH  . 'assets/admin/images/newspapers/'));
define('NEWSPAPER_IMAGE', ADMIN_IMAGES_PATH  . 'newspapers/');

/*
|--------------------------------------------------------------------------
| Locate newspaper images 
|--------------------------------------------------------------------------
|
| This will locate all the user images
| 
*/

define('CATEGORY_IMAGE_PATH', realpath(FCPATH  . 'assets/admin/images/categories/'));
define('CATEGORY_IMAGE', ADMIN_IMAGES_PATH  . 'categories/');

/*
|--------------------------------------------------------------------------
| Locate user images 
|--------------------------------------------------------------------------
|
| This will locate all the user images
| 
*/

define('USER_IMAGE_PATH', ADMIN_ASSETS . 'images/users/');

/*
|--------------------------------------------------------------------------
| Locate user images path for upload 
|--------------------------------------------------------------------------
|
| This will locate user images path for upload 
| 
*/

define('USER_IMAGE_UPLOAD_PATH', realpath(FCPATH  . 'assets/admin/images/users/'));

/*
|--------------------------------------------------------------------------
| Image Prepend String 
|--------------------------------------------------------------------------
|
| This will locate all the parties images
| 
*/

define('IMAGE_PREPEND', 'Online_Store_');


/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/

defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code