<?php defined('SYSPATH') or die('No direct script access.');

class Model_Constants
{
	const APP_SERVER	= 'v1.redrogue.local';
	
	// If this is a directory as opposed to '', it must be followed by a trailing slash.
	const APP_DIR		= '';
	
	const POPUP_POTION	= 1;
	const POPUP_SCROLL	= 2;
	const POPUP_WEAPON	= 3;
	const POPUP_ARMOR	= 4;
	const POPUP_RING	= 5;
	const POPUP_AMULET	= 6;
	const POPUP_DAGGER	= 7;
	const POPUP_SWORD	= 8;
	const POPUP_MACE	= 9;
	
	const SESSION_USER_ID		= 'user_id';
	const SESSION_DUNGEON_LEVEL	= 'current_level';	// The entire level object the avatar is currently playing
	const SESSION_WEAPON		= 'weapon';			// The weapon an avatar is currently using
	const SESSION_FOUND_ITEM	= 'found_item';		// The item an avatar just clicked on
	const SESSION_ITEMS			= 'items';			// The items an avatar has in their possession
	const SESSION_USED_ITEM		= 'used_item';		// An item (e.g. potion, scroll, etc) the avatar has just used
	const SESSION_ACTIVE_ITEM	= 'active_item';	// A spell or something that is going to be used by the player
	const SESSION_MESSAGE		= 'message';		// A simple text message
	const SESSION_ASCII			= 'ascii';			// The ascii file to be displayed
	
	const TYPE_WEAPON		= 1;
	const TYPE_ARMOR		= 2;
	const TYPE_POTION		= 3;
	const TYPE_SCROLL		= 4;
	const TYPE_FOOD			= 5;
	
	/*
	const HUNGER_HUNGRY	= 2;
	const HUNGER_WEAK	= 4;
	const HUNGER_FAINT	= 5;
	const HUNGER_STARVE = 6;
	*/
	const HUNGER_HUNGRY	= 15;
	const HUNGER_WEAK	= 22;
	const HUNGER_FAINT	= 27;
	const HUNGER_STARVE = 30;

	// Types
	const WEAPON_AXE	= 1;
	const WEAPON_CLUB	= 2;
	const WEAPON_DAGGER	= 3;
	const WEAPON_MACE	= 4;
	const WEAPON_SWORD	= 5;
	
	// Sub-types
	const AXE_HAND		= 1;
	const AXE_			= 2;
	const AXE_WARRIORS	= 3;
	const AXE_BATTLE	= 4;

	const CLUB_WOODEN	= 5;
	const CLUB_SPIKED	= 6;
	
	const DAGGER_		= 7;
	const DAGGER_BONE	= 8;
	const DAGGER_RUBY	= 9;
	
	const MACE_IRON		= 10;
	const MACE_SKULL	= 11;

	const SWORD_SHORT	= 12;
	const SWORD_LONG	= 13;
	const SWORD_BASTARD	= 14;
	const SWORD_TWO_HANDED = 15;	
}
?>