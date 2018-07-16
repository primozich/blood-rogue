<?php
/*
if (isset($_GET['PC']))
{
	$newZoneId = (int)$_GET['PC'];
	if (($newZoneId > 0) && ($newZoneId < 6))
	{
		if ($user->getBigTopBucks() > 10)
		{
			$user->subtractBigTopBucks(10);
			$user->updateCurrentZoneId($newZoneId);
			$usersProductId = UserObj::getUuid();
			$sql = "insert into tbt_users_products (users_products_id, user_id, product_id, cost, currency_type_id, created, updated) values ('".
					$usersProductId."', '".$user->getId()."', '9fc70158-aca2-11df-b5ae-4dc804ba99cf', 10, 1, now(), now())";
			db_execute($sql);

			$messagePondColor = new MessageObj('<div class="title">Sweet New Look!</div>
				<div style="margin-bottom:10px;">Throw a little pond warming party for all your friends. Every click gets you more Bait so you can</div>
				<div>redecorate more often or collect more exotic fish. When they click your post, you both earn more Bait.</div>');
		}
	}
}
$showShareButtons = true;
if ((isset($_GET[ConstantsObj::GET_FEED_NOW])) && (!$message))
{
	if ($_GET[ConstantsObj::GET_FEED_NOW] == $user->getFacebookId())
	{
		$useEnergy = false;
		$message = new MessageObj('');
		if ($user->getBigTopBucks() > 10)
		{
			$feedingId = doFeeding($user, $useEnergy, $message);
			$user->subtractBigTopBucks(10);
			$energyTime = getEnergyTime($user->getEnergyRefill());
		}
		else
		{
			$message = new MessageObj("You do not have enough Bait to feed your fish right now. Wait for your next feeding time or buy more Bait.");
			$message->setIsError(true);
			$message->setErrorType(MessageObj::ERROR_INSUFFICIENT_FUNDS);
		}
	}
}
elseif (isset($_GET[ConstantsObj::GET_GET_BAIT]))
{
	$getBait = (int)$_GET[ConstantsObj::GET_GET_BAIT];
	switch ($getBait)
	{
		case ConstantsObj::GET_BAIT_SUCCESS:
			$message = new MessageObj('<div class="title">Free Bait!</div><div>You trolled the shallows and found some bait thanks to a little
				help from your friends.</div>');
			$showShareButtons = false;
			break;
		case ConstantsObj::GET_BAIT_BAD_CODE:
			$message = new MessageObj('<div class="title">Ooops!</div><div>Your fishing pole broke (which means we couldn\'t find 
				the post you clicked on).</div>');
			$message->setIsError(true);
			break;
		case ConstantsObj::GET_BAIT_ALL_TAKEN:
			$message = new MessageObj('<div class="title">Ooops!</div><div>You have a hole in your bait net (which means all the bait for 
				the post you submitted has already been picked up).</div>');
			$message->setIsError(true);
		case ConstantsObj::GET_BAIT_ALL_TAKEN:
			$message = new MessageObj('<div class="title">Ooops!</div><div>Your bait bucket is full (which means you already got some 
				free bait from that post).</div>');
			$message->setIsError(true);
			break;
	}
}
elseif (isset($_GET[ConstantsObj::GET_SWAP]))
{
	$fishId = $_REQUEST[ConstantsObj::GET_SWAP];
	$fishId = addslashes($fishId);
	if ($user->getItemCount($fishId))
	{
		$user->setCurrentActivityId($fishId);
		$sql = "update tbt_users set current_activity_id='".$fishId."' where id='".$user->getId()."'";
		db_execute($sql);
	}
}
include_once("elements/header.php");
include_once("elements/tabs.php");

if (isset($message))
{
	$class = 'message';
	$identifier = '';
	$streamMgr = new StreamPostManager(APP_SERVER, IMG_SERVER);
	if ($message->getIsError())
	{
		$class = 'error';
	}
	else
	{
		if (CURRENT_ENV == ConstantsObj::ENV_FACEBOOK)
		{
			$firstName = FacebookManager::getFirstName($facebook, $user->getFacebookId());
	
			$additionalData = array('FEED_ID' => $feedingId); // Necessary that this isn't null
			$postUrl = 'doshare.php?'.ConstantsObj::GET_FEEDING_ID.'='.$feedingId;
			$skipUrl = 'doshare.php';
			$identifier = 'feeding'; // In case there are multiple stream posts on a single page.
			$streamPostId = 1;
			print($streamMgr->writePostDataById($streamPostId, $identifier, $user, $firstName, $additionalData, $postUrl, $skipUrl));
		}
	}
?>
<div class="<?php echo $class; ?>" style="height:60px;" id="message_box">
	<div style="width:550px;height:60px;float:left;">
		<?php echo $message->getMessage(); ?>
	</div>
	<?php if ((!$message->getIsError()) && ($showShareButtons)) { ?>
	<form method="POST" action="">
	<div style="width:80px;float:left;">
		<input onclick="<?php echo $streamMgr->writePostLinkWithCallback($identifier); ?>return false;" type="image" src="http://<?php echo IMG_SERVER; ?>/images/buttons/share.png" />
	</div>
	<div style="width:70px;float:left;">
		<input onclick="hide_message();return false;" type="image" src="http://<?php echo IMG_SERVER; ?>/images/buttons/skip.png" />
	</div>
	</form>
	<?php } ?>
</div>
<div style="clear:both;"></div>
<?php
}
elseif (isset($messagePondColor))
{
	$class = 'message';
	$identifier = '';
	$streamMgr = new StreamPostManager(APP_SERVER, IMG_SERVER);
	if (CURRENT_ENV == ConstantsObj::ENV_FACEBOOK)
	{
		$firstName = FacebookManager::getFirstName($facebook, $user->getFacebookId());

		$additionalData = array('UP_ID' => $usersProductId); // Necessary that this isn't null
		$postUrl = 'doshare.php?'.ConstantsObj::GET_USERS_PRODUCT_ID.'='.$usersProductId;
		$skipUrl = 'doshare.php';
		$identifier = 'pondcolor'; // In case there are multiple stream posts on a single page.
		$streamPostId = 7;
		print($streamMgr->writePostDataById($streamPostId, $identifier, $user, $firstName, $additionalData, $postUrl, $skipUrl));
	}
?>
<div class="<?php echo $class; ?>" style="height:60px;" id="message_box">
	<div style="width:550px;height:60px;float:left;">
		<?php echo $messagePondColor->getMessage(); ?>
	</div>
	<?php if ((!$messagePondColor->getIsError()) && ($showShareButtons)) { ?>
	<form method="POST" action="">
	<div style="width:80px;float:left;">
		<input onclick="<?php echo $streamMgr->writePostLinkWithCallback($identifier); ?>return false;" type="image" src="http://<?php echo IMG_SERVER; ?>/images/buttons/share.png" />
	</div>
	<div style="width:70px;float:left;">
		<input onclick="hide_message();return false;" type="image" src="http://<?php echo IMG_SERVER; ?>/images/buttons/skip.png" />
	</div>
	</form>
	<?php } ?>
</div>
<div style="clear:both;"></div>
<?php
}
*/
	print('<div id="workspace">');

	print('<div style="float:left;margin-top:0px;width:530px;">');
	//include_once("elements/info_column.php");
	for ($i = 0; $i < Model_Level::MAP_SIZE; $i++)
	{
		$location = $level->getLocationAtPosition($i);
		print('<div class="bg"><img src="http://' . $env->img_server . '/images/map/' . $location->getWebImage() . 
			'" /></div>');
		/* DEBUG
		print('Position: '.$i.'<br>');
		print('<pre>');
		print(htmlentities('<div class="bg"><img src="http://' . $env->img_server . '/images/map/' . $location->getWebImage() . 
			'" /></div>'));
		print('</pre>');
		*/
	}
?>
<div class="redText" style="float:left;margin-top:0px;border:1px solid #ff0000;margin-left:15px;padding:5px;width:480px;">
	<div style="font-weight:bold;margin-bottom:5px;" class="grayText">Legend</div>
	<div style="font-weight:normal;">
		<div style="float:left;width:300px;"><span style="background-color:#ffffff;">@</span></div>
		<div style="float:left;">You are here - this is your hero</div>
		<div style="clear:both;"></div>
		<div style="float:left;width:300px;">A, B, C, etc.</div>
		<div style="float:left;">Monsters - click to attack</div>
		<div style="clear:both;"></div>
		<div style="float:left;width:300px;">), /, ], ?, !, &amp;</div>
		<div style="float:left;">Equipment - click to pick up</div>
		<div style="clear:both;"></div>
		<div style="float:left;width:300px;color:00ff00;">+</div>
		<div style="float:left;">Open Doors - click to exit</div>
		<div style="clear:both;"></div>
		<div style="float:left;width:300px;">%</div>
		<div style="float:left;">Stairs - click to go up/down</div>
	</div>
</div>
<?php
	print('</div>');
	print($infocolumn);
	print('<div style="clear:both;"></div>');
	/**/
	$rooms = $level->getRooms();
	$xOffset = 0;
	$yOffset = 0;
	$i = 0;
	foreach ($rooms as $room)
	{
		$xOffset = ($room->getPosition() % 3) * 165;
		$yOffset = floor($room->getPosition() / 3) * 180;
		/*
		if (($room->getPosition() < 3) || ($room->getPosition() > 5))
		{
			$xOffset = $room->getPosition() * 165;
		}
		elseif ($room->getPosition() < 6)
		{
			$xOffset = (5 - $room->getPosition()) * 165;
		}
		*/
		$yOffset += 29;	// accounting for the addition of the nav buttons
		foreach ($room->getObjects() as $obj)
		{
			if ($obj->getShowOnMap())
			{
				if ($obj instanceof Model_Gold)
				{
					print('<div style="top:'.($yOffset+$room->getY()+$obj->getY()).'px;left:'.($xOffset+$room->getX()+$obj->getX()).
						'px;position:absolute;z-index:40;width:11px;height:16px;background-color:#000000;"><a class="redText noUnder" target="_top" href="'.$obj->getMyLink().
						'">' . $obj->getLetter() . '</a></div>');
				}
				elseif ($obj instanceof Model_Food)
				{
					print('<div style="top:'.($yOffset+$room->getY()+$obj->getY()).'px;left:'.($xOffset+$room->getX()+$obj->getX()).
						'px;position:absolute;z-index:60;width:11px;height:16px;background-color:#000000;"><a class="redText noUnder" target="_top" href="'.$obj->getMyLink().
						'">' . $obj->getLetter() . '</a></div>');
				}
				elseif ($obj instanceof Model_Mmonster)
				{
					print('<div style="top:'.($yOffset+$room->getY()+$obj->getY()).'px;left:'.($xOffset+$room->getX()+$obj->getX()).
						'px;position:absolute;z-index:60;width:11px;height:16px;background-color:#000000;"><a class="redText" target="_top" href="'.$obj->getMyLink().
						'">' . $obj->getMonster()->letter . '</a></div>');
				}
				elseif ($obj instanceof Model_Weapon)
				{
					print('<div style="top:'.($yOffset+$room->getY()+$obj->getY()).'px;left:'.($xOffset+$room->getX()+$obj->getX()).
						'px;position:absolute;z-index:60;width:11px;height:16px;background-color:#000000;"><a class="redText" target="_top" href="'.$obj->getMyLink().
						'">' . $obj->getLetter() . '</a></div>');
				}
				else
				{
					print('<div style="top:'.($yOffset+$room->getY()+$obj->getY()).'px;left:'.($xOffset+$room->getX()+$obj->getX()).
						'px;position:absolute;z-index:60;"><a target="_top" href="'.$obj->getMyLink().'"><img src="http://' . 
						$env->img_server . '/images/icons/' . $obj->getImage() . '" /></a></div>');
				}
			}
		}
		if ($room->getPosition() == $level->getPlayerPosition())
		{
			$avatarLocation = $room->getOpenLocation();
			$avatarX		= $room->getXForLocation($avatarLocation);
			$avatarY		= $room->getYForLocation($avatarLocation);
			// Display avatar
			print('<div class="redText" style="top:'.($yOffset+$room->getY()+$avatarY).'px;left:'.($xOffset+$room->getX()+$avatarX).
				'px;position:absolute; z-index: 60;"><img src="http://' . 
				$env->img_server . '/images/icons/' . $avatar->getImage() . '" /></div>');
		}
		//$yOffset += 16;
		$i++;
	}
	/**/
	//print('<div style="top:86px;left:46px;position:absolute; z-index: 50;"><a href=""><img src="http://' . 
	//	$env->img_server . '/images/icons/at.gif" /></a></div>');
	print('</div>');		// End of workspace div
?>
<script type="text/javascript">
//function show_dialogue() {
	<?php //if (CURRENT_ENV == ConstantsObj::ENV_LOCAL) { ?>
	//show_element = document.getElementById("continue_dialogue");
	//show_element.style.display = "block";
	<?php
	if ($dimTheLights)
	{
	?>
		hide_element = document.getElementById("workspace");
		hide_element.style.opacity = 0.10;
		hide_element.style.filter = "alpha(opacity=10)";
	<?php
	}
	?>
	<?php //} else { ?>
	//mpmetrics.track('intro', {'bookmark_id':'<?php //echo $bookmarkId; ?>a'});
	//show_element = document.getElementById("continue_dialogue");
	//show_element.setStyle("display", "block");
	//hide_element = document.getElementById("workspace");
	//hide_element.setStyle("opacity", 0.5);
	//hide_element.setStyle("filter", "alpha(opacity=50)");
	<?php //} ?>
//}
</script>
<!-- div class="redText" style="top:79px;left:402px;position:absolute; z-index: 60;"><a href="/rogue"><img src="http://v1.redrogue.local/images/icons/plus.gif" /></a></div-->
<!-- div class="redText" style="clear:both;">
	Rooms: <?php echo $level->getRoomCount(); ?>
	<br />
	Objects: <?php echo $level->getObjectCount(); ?>
</div>
<div class="redText">
<?php 
	$level->printAll();
?>
</div-->
<?php
	print($popup);
?>
<script>
function popup_continue() {
	hide_element = document.getElementById("popup");
	hide_element.style.display = "none";
	show_element = document.getElementById("workspace");
	show_element.style.opacity = 1.0;
	show_element.style.filter = "alpha(opacity=100)";
}
</script>