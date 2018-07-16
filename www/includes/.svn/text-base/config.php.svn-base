<?php
require_once(dirname(__FILE__) . '/../includes/classes/GameObj.php');
require_once(dirname(__FILE__) . '/../includes/classes/UserObj.php');
require_once(dirname(__FILE__) . '/../includes/classes/ConstantsObj.php');
require_once(dirname(__FILE__) . '/../includes/classes/AchievementsManager.php');
require_once(dirname(__FILE__) . '/../includes/classes/MultiInsertObj.php');
require_once(dirname(__FILE__) . '/../includes/classes/DebugObj.php');
require_once(dirname(__FILE__) . '/../includes/classes/CrowdManager.php');
require_once(dirname(__FILE__) . '/../env.php');

//Facebook Client Library - provides the Facebook object
require_once(dirname(__FILE__) . '/../includes/client/facebook.php');

//Database - functions for db access (************ TBD - review and migrate ***************)
require_once("db_utils.php");

//error_reporting(0);

//Get Application Settings From database
$sql = "select * from tbt_games where id = '06cfb944-9bfa-11df-84d4-8b50f0c1f58a';"; // Fish Feeder
$gameData = mysql_fetch_assoc(db_execute_return($sql));
$game = new GameObj($gameData);

//Constant
$facebook = null;
if (CURRENT_ENV == 1)
{
	// Create the Facebook client object
	$fbApiKey	= $game->getFbApiKey();
	$fbSecret	= $game->getFbSecret();
	$facebook = new Facebook($fbApiKey, $fbSecret);
	$user = $facebook->require_login();
	$fbUserId = $facebook->get_loggedin_user();

	// TBD: figure out if I need this
	//$facebook->require_frame();
}
else
{
	$sex = 'male';
	$fbUserId = 8;
	//$fbUserId = 25;
	$name = $fbUserId;
}

// Get user and create account if user doesn't exist
$user = new UserObj();
$bonusBait = 0;
$showDailyBonus = true;
if (!$user->userExistsByFbId($fbUserId))
{
	if (CURRENT_ENV == 1)
	{
		$user_details = $facebook->api_client->users_getInfo($fbUserId, 'sex');
		$sex = $user_details[0]['sex'];
		$name = $user_details[0]['name'];
	}

	// Get a uuid
	$uuid = GameObj::getUuid();

	// Create User Account
	$time = time();
	$startingCash	= 0;
	$startingBTB	= 15;
	$startingEnergy	= 1;
	$startingShows	= 1;
	$yellowTang		= 'f1d0f5c6-9cda-11df-b5ef-4f51a859c3c8';
	$sql = "insert into tbt_users (id, current_activity_id, facebook_id,sex,name,email,big_top_bucks,cash,energy,energy_max,energy_refill,show_times,show_times_max,show_times_refill,xp,level,crowd_size,bookmark_id,current_zone_id,karma,sneaky,skill_points,last_login,created,updated) values ('$uuid','$yellowTang',$fbUserId,'$sex','$name','',$startingBTB,$startingCash,$startingEnergy,$startingEnergy,$time,$startingShows,$startingShows,$time,0,1,0,0,1,0,0,0,now(),now(),now())";
	db_execute($sql);

	// Get the user
	if (!$user->userExistsByFbId($fbUserId))
	{
		throw new Exception("No user created.");
	}
	
	// Add the yellow tang
	$sql = "insert into tbt_users_items (user_id, item_id, created) values ('".$uuid."', '".$yellowTang."', now())";
	db_execute($sql);

	// Add 1st zone
	$sql = "insert into tbt_users_zones (user_id, zone_id, created) values ('$uuid',1,now())";
	db_execute($sql);

	// Check whether user was invited and update everyone's crowds
	CrowdManager::addCrowd($user);

	// Add achievements
	$trophyInsert = new MultiInsertObj('tbt_users_achievements_points', 'user_id, achievement_type_id, created');
	$trophyInsert->addRow("'".$uuid."', ".ConstantsObj::ACHIEVEMENT_LOGIN.", now()");
	$trophyInsert->addRow("'".$uuid."', ".ConstantsObj::ACHIEVEMENT_OVERALL.", now()");
	$trophyInsert->addRow("'".$uuid."', ".ConstantsObj::ACHIEVEMENT_THIS_WEEK.", now()");
	$trophyInsert->addRow("'".$uuid."', ".ConstantsObj::ACHIEVEMENT_LAST_WEEK.", now()");
	$trophyInsert->addRow("'".$uuid."', ".ConstantsObj::ACHIEVEMENT_GOLD_BAIT.", now()");
	$trophyInsert->addRow("'".$uuid."', ".ConstantsObj::ACHIEVEMENT_SILVER_BAIT.", now()");
	$trophyInsert->addRow("'".$uuid."', ".ConstantsObj::ACHIEVEMENT_BRONZE_BAIT.", now()");
	$sql = $trophyInsert->getSql();
	db_execute($sql);
	
	AchievementsManager::givePoints($uuid, ConstantsObj::ACHIEVEMENT_LOGIN, 1);
	
	$bonusBait = $user->getBonus();
}
else
{
	$lastLogin = $user->getLastLogin();
	$unixLastLogin = strtotime($lastLogin);
	$lastLoginDay = date('m-d', $unixLastLogin);
	$todaysDay = date('m-d');
	if ($lastLoginDay != $todaysDay)
	{
		AchievementsManager::givePoints($user->getId(), ConstantsObj::ACHIEVEMENT_LOGIN, 1);
		$bonusBait = $user->getBonus();
	}
	else
	{
		$showDailyBonus = false;
	}	
	
	$sql = "update tbt_users set last_login=now() where facebook_id=$fbUserId";
	db_execute($sql);
	
	CrowdManager::addCrowd($user);
}
$isMaint = false;
if (($isMaint) && ($_SERVER['REQUEST_URI'] != '/maint.php') && (!$user->isAdmin()))
{
	$header = 'Location: maint.php';
	header($header);
	exit;
}
?>
