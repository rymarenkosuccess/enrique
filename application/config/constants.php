<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');

/*
|--------------------------------------------------------------------------
| Custom Define
|--------------------------------------------------------------------------
 */

define('RT_PATH', dirname(dirname(__DIR__)));

$base_url = "http://".$_SERVER['HTTP_HOST'];
$base_url .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);

define('BASE_URL',    $base_url);
define('ASSETS_DIR',	$base_url.'assets');
define('IMG_DIR',	$base_url.'assets/img');
define('CSS_DIR',	$base_url.'assets/css');
define('JS_DIR',	$base_url.'assets/js');
//define('EDT_DIR', 	RT_PATH.'/assets/fckeditor');
define('UPLOAD_DIR', RT_PATH.'/assets/ufile/');
define('UPLOAD_URL', $base_url.'assets/ufile/');

define('SITE_NAME', 'Starclub Inc.');
define('ADMIN_EMAIL', 'tga.developer@hotmail.com');

/* End of file constants.php */
/* Location: ./application/config/constants.php */