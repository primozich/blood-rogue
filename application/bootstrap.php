<?php defined('SYSPATH') or die('No direct script access.');

//-- Environment setup --------------------------------------------------------

/**
 * Set the default time zone.
 *
 * @see  http://kohanaframework.org/guide/using.configuration
 * @see  http://php.net/timezones
 */
date_default_timezone_set('America/Chicago');

/**
 * Set the default locale.
 *
 * @see  http://kohanaframework.org/guide/using.configuration
 * @see  http://php.net/setlocale
 */
setlocale(LC_ALL, 'en_US.utf-8');
/**
 * Enable the Kohana auto-loader.
 *
 * @see  http://kohanaframework.org/guide/using.autoloading
 * @see  http://php.net/spl_autoload_register
 */
spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @see  http://php.net/spl_autoload_call
 * @see  http://php.net/manual/var.configuration.php#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

//-- Configuration and initialization -----------------------------------------

/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 */
Kohana::init(array(
	'base_url'   => '/',
	'index_file' => FALSE,
));

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Kohana_Log_File(APPPATH.'logs'));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Kohana_Config_File);

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules(array(
	// 'auth'       => MODPATH.'auth',       // Basic authentication
	// 'cache'      => MODPATH.'cache',      // Caching with multiple backends
	// 'codebench'  => MODPATH.'codebench',  // Benchmarking tool
	'database'   => MODPATH.'database',   // Database access
	// 'image'      => MODPATH.'image',      // Image manipulation
	'orm'        => MODPATH.'orm',        // Object Relationship Mapping
	'mososh'        => MODPATH.'mososh',        // Mososh
	// 'oauth'      => MODPATH.'oauth',      // OAuth authentication
	// 'pagination' => MODPATH.'pagination', // Paging of results
	// 'unittest'   => MODPATH.'unittest',   // Unit testing
	// 'userguide'  => MODPATH.'userguide',  // User guide and API documentation
	));

/**
 * Translated from the old mososh config code
 */
$env = Kohana::config('env');
//print(dirname(__FILE__));exit;
require_once(dirname(__FILE__) . '/./client/facebook.php');

$game = ORM::factory('game', '3ec5357c-b11c-11df-961f-ce6db64b54c2');
if (!$game->loaded())
{
	die('No game found.');
}

$facebook = null;
if ($env->current_env == 1)
{
	// Create the Facebook client object
	$fbApiKey	= $game->fb_api_key;
	$fbSecret	= $game->fb_secret;
	$facebook = new Facebook($fbApiKey, $fbSecret);
	$user = $facebook->require_login();
	$fbUserId = $facebook->get_loggedin_user();
	
	$user_details	= $facebook->api_client->users_getInfo($fbUserId, 'sex');
	$sex = '';
	$name = '';
	if (isset($user_details[0]['sex']))
	{
		$sex			= $user_details[0]['sex'];
	}
	if (isset($user_details[0]['name']))
	{
		$name			= $user_details[0]['name'];
	}
	
	// TBD: figure out if I need this
	//$facebook->require_frame();
}
else
{
	$sex		= 'male';
	$fbUserId	= 8;
	//$fbUserId	= 25;
	$name		= $fbUserId;
}

$user = ORM::factory('user', array('facebook_id' => $fbUserId));
$bonusBait = 0;
$showDailyBonus = true;
$session = Session::instance();

if (!$user->loaded())
{
	$user = ORM::factory('user');
	
	// Create the user
	$uuid = Model_User::getUuid();
	$time = time();
	
	$user->id			= $uuid;
	$user->facebook_id	= $fbUserId;
	$user->sex			= $sex;
	$user->name			= $name;
	$user->real_money_currency	= 15;
	$user->game_only_currency	= 0;
	$user->energy		= 1;
	$user->energy_max	= 1;
	$user->energy_refill= $time;
	$user->stamina		= 1;
	$user->stamina_max	= 1;
	$user->stamina_refill		= $time;
	$user->xp			= 0;
	$user->level		= 0;
	$user->friend_count	= 0;
	$user->bookmark_id	= 0;
	$user->current_zone_id		= 0;
	$user->login_streak	= 1;
	$user->last_login	= date('Y-m-d H:i:s');
	
	$user->save();
	if (!($user->saved()))
	{
		throw new Exception("Couldn't create a new user. Please try refreshing and email support@mososh.com if you continue to have trouble.");
	}
	
	//$session('the_user', $user);
	
	//print($uuid);exit;
	
}
else
{
	$lastLogin = $user->last_login;
	$unixLastLogin = strtotime($lastLogin);
	$lastLoginDay = date('m-d', $unixLastLogin);
	$todaysDay = date('m-d');
	if ($lastLoginDay != $todaysDay)
	{
		$user->last_login = date('Y-m-d H:i:s');
		$user->save();
		//AchievementsManager::givePoints($user->getId(), ConstantsObj::ACHIEVEMENT_LOGIN, 1);
		//$bonusBait = $user->getBonus();
	}
	else
	{
		//$showDailyBonus = false;
	}	
	/*
	
	$sql = "update tbt_users set last_login=now() where facebook_id=$fbUserId";
	db_execute($sql);
	*/
}
$session->set('user_id', $user->id);

/**
 * Set the routes. Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */
Route::set('default', '(<controller>(/<action>(/<id>)))')
	->defaults(array(
		'controller' => 'rogue',
		'action'     => 'index',
	));

if ( ! defined('SUPPRESS_REQUEST'))
{
	/**
	 * Execute the main request. A source of the URI can be passed, eg: $_SERVER['PATH_INFO'].
	 * If no source is specified, the URI will be automatically detected.
	 */
	echo Request::instance()
		->execute()
		->send_headers()
		->response;
}
