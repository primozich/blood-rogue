<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Rogue extends Controller_Template
{
	public $template = 'templateindex';
	private $avatar;
	public $title = 'Blood Rogue';
	
	public function before()
	{
		parent::before();
		View::bind_global('title', $this->title);
		$env = Kohana::config('env');
		View::bind_global('env', $env);
		
		$header = View::factory('elements/header');
		View::bind_global('header', $header);
	}
	
	private function createAvatar($avatar)
	{
		$avatar->id			= Model_Avatar::getUuid();
		$avatar->name		= '';
		$avatar->strength	= Model_Avatar::STRENGTH_START;
		$avatar->hit_points	= Model_Avatar::HIT_POINTS_START;
		$avatar->strength_max	= Model_Avatar::STRENGTH_START;
		$avatar->hit_points_max	= Model_Avatar::HIT_POINTS_START;
		$avatar->pack_size	= Model_Avatar::PACK_SIZE_START;
		$avatar->gold		= 0;
		$avatar->xp			= 0;
		$avatar->level		= 1;
		$avatar->save();
		if (!($avatar->saved()))
		{
			throw new Exception("Couldn't create a new avatar. Please try refreshing and email support@mososh.com if you continue to have trouble.");
		}
		$this->avatar = $avatar;
		$this->weaponCheck();
	}
	
	private function weaponCheck()
	{
		$session = Session::instance();
		if (!isset($this->avatar))
		{
			throw new Exception('No avatar found');
		}
		$weapon = $session->get(Model_Constants::SESSION_WEAPON);
		if (!isset($weapon))
		{
			$dagger = new Model_Weapon('Dagger', '');
			$session->set(Model_Constants::SESSION_ITEMS, array());
			$this->avatar->addItem($dagger, $session);
			$this->avatar->equipItem($dagger, $session);
		}
	}
	
	public function __construct(Kohana_Request $request)
	{
		parent::__construct($request);

		$session = Session::instance();
		$userId = $session->get(Model_Constants::SESSION_USER_ID);
		
		//$avatar = $session->get('avatar');
		if (!isset($avatar))
		{
			//print('no avatar in session');exit;
			$avatar = ORM::factory('avatar', array('user_id' => $userId));
			if (!$avatar->loaded())
			{
				$avatar->user_id = $userId;
				$this->createAvatar($avatar);
			}
			$level = $session->get(Model_Constants::SESSION_DUNGEON_LEVEL);
			if ((!isset($level)) && ($this->request->action != 'index'))
			{
				$this->request->action = 'index';
			}
			/*
			if (($avatar->hit_points <= 0) && ($this->request->action != 'death'))
			{
				//print('die');exit;
				//$avatar->hit_points = $avatar->hit_points_max;
				//$avatar->save();
				$env = Kohana::config('env');
				$redirect = 'http://'.$env->www_root.'/rogue/death';
				//$this->request->redirect($redirect);
				$this->avatar = $avatar;
				$this->action_death();
			}
			*/
		}
		$this->avatar = $avatar;
	}
	
	public function action_ai()
	{
		$this->avatar->addItem(new Model_Potion());
		print('done');
	}
	
	public function action_reset()
	{
		$session = Session::instance();
		$session->delete(Model_Constants::SESSION_DUNGEON_LEVEL);
		$session->delete(Model_Constants::SESSION_ITEMS);
		$session->delete(Model_Constants::SESSION_FOUND_ITEM);
		$session->delete(Model_Constants::SESSION_WEAPON);
		$session->delete(Model_Constants::SESSION_MESSAGE);
		$this->avatar->delete();
		
		$avatar = ORM::factory('avatar');
		$avatar->user_id = $session->get(Model_Constants::SESSION_USER_ID);
		$this->createAvatar($avatar);
		//$redirect = 'http://'.$env->www_root.'/rogue/index';
		//$this->request->redirect($redirect);
		$this->action_index();
	}
	
	// This is only going one way right now (not sure it's going to change)
	public function action_climbstairs()
	{
		$session = Session::instance();
		$level = $session->get(Model_Constants::SESSION_DUNGEON_LEVEL);
		if ($level->getDungeonLevel() % 1 == 0)
		{
			// Save
		}
		$level->increaseLevel();
		$session->set(Model_Constants::SESSION_DUNGEON_LEVEL, $level);
		$env = Kohana::config('env');
		//$redirect = 'http://'.$env->www_root.'/rogue/index';
		//$this->request->redirect($redirect);
		$this->action_index();
	}
	
	public function action_opendoor($direction)
	{
		if (($direction != -1) && ($direction != 1))
		{
			$this->action_index();
		}
		//print($nextPosition);exit;
		$session = Session::instance();
		
		// Check for monsters if the avatar is going deeper
		/**/
		if ($direction > 0)
		{
			$level = $session->get(Model_Constants::SESSION_DUNGEON_LEVEL);
			$monster = $level->monsterInTheRoom();
			if ($monster)
			{
				$session->set(Model_Constants::SESSION_MESSAGE, 'There is a ' . $monster->getMonster()->name . ' blocking your way.');
				$session->set(Model_Constants::SESSION_ASCII, 'monster');
				$this->action_index();
				return;
			}
		}
		/**/
		
		$this->avatar->addHunger();

		if ($this->avatar->hunger == Model_Constants::HUNGER_STARVE)
		{
			$session->set(Model_Constants::SESSION_MESSAGE, 'You have starved to death.');
			$this->action_death();
			return;
		}
		elseif ($this->avatar->hunger == Model_Constants::HUNGER_FAINT)
		{
			$session->set(Model_Constants::SESSION_MESSAGE, 'You are starting to feel faint.');
		}
		elseif ($this->avatar->hunger == Model_Constants::HUNGER_WEAK)
		{
			$session->set(Model_Constants::SESSION_MESSAGE, 'You are starting to feel weak.');
		}
		elseif ($this->avatar->hunger == Model_Constants::HUNGER_HUNGRY)
		{
			$session->set(Model_Constants::SESSION_MESSAGE, 'Your stomach starts to grumble a little.');
		}
		
		$level = $session->get(Model_Constants::SESSION_DUNGEON_LEVEL);
		if ($direction > 0)
		{
			$direction = 1;
		}
		elseif ($direction < 0)
		{
			$direction = -1;
		}
		
		// Give hit points
		$coinFlip = mt_rand(0, 1);
		$hp = 1;
		if ($coinFlip)
		{
			$hp++;
		}
		$this->avatar->addHitPoints($hp);
		$message = $level->moveAvatar($direction);
		if ($message)
		{
			$session->set(Model_Constants::SESSION_MESSAGE, $message);
			$session->set(Model_Constants::SESSION_ASCII, 'blank');
		}
		$session->set(Model_Constants::SESSION_DUNGEON_LEVEL, $level);
		//$this->request->redirect('rogue/index');
		$this->action_index();
	}

	public function action_index()
	{
		if ($this->avatar->bookmark_id == 0)
		{
			$this->action_story(0);
			return;
		}
		
		$view = View::factory('index');
		
		$session = Session::instance();
		$level = $session->get(Model_Constants::SESSION_DUNGEON_LEVEL);	// Comment out for new level w/every refresh
		if (!isset($level))
		{
			$level = new Model_Level('Lvl 1');
			$session->set(Model_Constants::SESSION_DUNGEON_LEVEL, $level);	// Comment out for new level w/every refresh
			$level->addAvatar();
		}
		$view->level = $level;

		$view->popup = View::factory('elements/popup');
		$view->popup->showPopup		= false;
		$view->popup->popupTitle	= '';
		$view->popup->ascii			= View::factory('ascii/potion');
		$view->popup->popupTitle	= 'You found a ';
		$view->dimTheLights			= false;
		$foundItem = $session->get(Model_Constants::SESSION_FOUND_ITEM);
		if (isset($foundItem))
		{
			$view->dimTheLights		= true;
			$view->popup->showPopup	= true;
			if ($foundItem instanceof Model_Scroll)
			{
				$view->popup->popupTitle = 'You found a scroll that reads "'.trim($foundItem->getName()).'"';
				$view->popup->ascii			= View::factory('ascii/scroll');
			}
			elseif ($foundItem instanceof Model_Fight)
			{
				$fight	= $foundItem;
				$hero	= $fight->getHeroFighter();
				$badguy	= $fight->getMonsterFighter();
				$view->popup->popupTitle = 'You defeated a '.$badguy->getName().' in '.$hero->getNumberOfAttacks().' swings and lost '.
					$badguy->getDamageDone().' hit points';
				$view->popup->ascii			= View::factory('ascii/monster');
			}
			elseif ($foundItem instanceof Model_Weapon)
			{
				$view->popup->ascii			= View::factory('ascii/sword');
				$view->popup->popupTitle	.= $foundItem->getName();
			}
			elseif ($foundItem instanceof Model_Gold)
			{
				$view->popup->ascii			= View::factory('ascii/gold');
				$view->popup->popupTitle	= $foundItem->getMessage() . ' You found ' . $foundItem->getAmount() . ' gold.';
			}
			elseif ($foundItem instanceof Model_Food)
			{
				$view->popup->ascii			= View::factory('ascii/food');
				$view->popup->popupTitle	= $foundItem->getMessage();
			}
			else
			{
				$view->popup->popupTitle	.= $foundItem->getName();
			}
			$session->delete(Model_Constants::SESSION_FOUND_ITEM);
		}
		else	// Check for a used item
		{
			$usedItem = $session->get(Model_Constants::SESSION_USED_ITEM);
			$message = $session->get(Model_Constants::SESSION_MESSAGE);
			if (isset($usedItem))
			{
				$view->dimTheLights		= true;
				$view->popup->showPopup	= true;
				if ($usedItem instanceof Model_Potion)
				{
					$view->popup->popupTitle	= $usedItem->getMessage();
					$view->popup->ascii			= View::factory('ascii/potion');
				}
				elseif ($usedItem instanceof Model_Food)
				{
					$view->popup->popupTitle	= 'Even a Blood Rogue needs to eat.';
					$view->popup->ascii			= View::factory('ascii/food');
				}
				elseif ($usedItem instanceof Model_Scroll)
				{
					$view->popup->popupTitle	= $usedItem->getMessage();
					$view->popup->ascii			= View::factory('ascii/scroll');
				}
				$session->delete(Model_Constants::SESSION_USED_ITEM);
			}
			elseif (isset($message))
			{
				$ascii = $session->get(Model_Constants::SESSION_ASCII);
				$view->dimTheLights		= true;
				$view->popup->showPopup	= true;
				$view->popup->popupTitle	= $message;
				if (isset($ascii))
				{
					$view->popup->ascii			= View::factory('ascii/monster');
				}
				else
				{
					$view->popup->ascii			= View::factory('ascii/food');
				}
				$session->delete(Model_Constants::SESSION_MESSAGE);
			}
		}

		$this->setup_infocolumn($view, $level, $session);
		
		$this->template->content	= $view;
	}
	
	public function action_death()
	{
		$session = Session::instance();
		$this->avatar->delete();
		$header			= View::factory('elements/header');
		$this->template->header = $header;
		$view			= View::factory('death');
		$view->ascii	= View::factory('ascii/skull');
		$view->message	= 'You have died!';
		$msg = $session->get(Model_Constants::SESSION_MESSAGE);
		if (isset($msg))
		{
			$view->message = $msg;
		}
		$this->template->content= $view;
	}
	
	public function action_hello()
	{
		$game = ORM::factory('game', '3ec5357c-b11c-11df-961f-ce6db64b54c2');
		//$game = new Model_Game();
		if ($game->loaded())
		{
			print $game->name;
			print '<br><br>';
			print_r($_GET);
			print '<br><br>';
			print $_SERVER['QUERY_STRING'];
		}
		else
		{
			//$this->request->response = 'hello, cp!<br /><br />';

			//$query = DB::query(Database::SELECT, 'SELECT * FROM payment_types WHERE username = :user');
			//$query->param(':user', 'john');
			$query = DB::select()->from('payment_types')->where('id', '=', '1');
			$result = $query->execute();
			print($result->get('payment_type'));
		}
		exit;
	}
	
	public function action_levels()
	{
		for ($level = 1; $level < 100; $level++)
		{
			$nextLevel = 10;
			$prevLevel = 10;
			$mult = 2;
			for ($i = 1; $i < $level; $i++)
			{
				$nextLevel = $mult * $prevLevel;
				$prevLevel = $nextLevel;
				/*
				1 = 0
				2 = 10
				3 = 20
				4 = 40
				5 = 80
				6 = 160
				*/
				if ($i > 1000)
					exit;
			}
			print('Level '.$level.', XP: '.$nextLevel.'<br>');
			//exit;
		}
		exit;
	}
	
	// ITEMS METHODS
	public function action_use($index)
	{
		$session	= Session::instance();
		$items		= $session->get(Model_Constants::SESSION_ITEMS);
		if (isset($items[$index]))
		{
			$item = $items[$index];
			if ($item instanceof Model_Weapon)
			{
				$this->avatar->equipItem($item, $session);
				$this->action_index();
			}
			elseif ($item instanceof Model_Potion)
			{
				$level = $session->get(Model_Constants::SESSION_DUNGEON_LEVEL);
				$item->drink($this->avatar, $level);
				//$this->avatar->removeItem($items, $index, $session);
				array_splice($items, $index, 1);
				$level->updatePotions($item->getType());
				foreach ($items as $itemToUpdate)
				{
					if ($item->getType() == $itemToUpdate->getType())
					{
						$itemToUpdate->setIsIdentified(true);
					}
				}
				$session->set(Model_Constants::SESSION_ITEMS, $items);
				$session->set(Model_Constants::SESSION_USED_ITEM, $item);
				$session->set(Model_Constants::SESSION_DUNGEON_LEVEL, $level);
				$this->action_index();
			}
			elseif ($item instanceof Model_Scroll)
			{
				$level = $session->get(Model_Constants::SESSION_DUNGEON_LEVEL);
				$level->updateScrolls($item->getType());
				foreach ($items as $itemToUpdate)
				{
					if ($item->getType() == $itemToUpdate->getType())
					{
						$itemToUpdate->setIsIdentified(true);
					}
				}
				$session->set(Model_Constants::SESSION_DUNGEON_LEVEL, $level);
				switch ($item->getType())
				{
					case 'Identify':
						$session->set(Model_Constants::SESSION_ACTIVE_ITEM, $index);
						$session->set(Model_Constants::SESSION_ITEMS, $items);
						$this->action_inventory();
						return;
						break;
					case 'Teleport':
						array_splice($items, $index, 1);
						$session->set(Model_Constants::SESSION_ITEMS, $items);
						$level->placeAvatarAtRandom();
						$session->set(Model_Constants::SESSION_USED_ITEM, $item);
						$session->set(Model_Constants::SESSION_DUNGEON_LEVEL, $level);
						$this->action_index();
						return;
						break;
				}
			}
			elseif ($item instanceof Model_Food)
			{
				$item->eat($this->avatar);
				//$this->avatar->removeItem($items, $index, $session);
				array_splice($items, $index, 1);
				$session->set(Model_Constants::SESSION_ITEMS, $items);
				$session->set(Model_Constants::SESSION_USED_ITEM, $item);
				$this->action_index();
			}
		}
		//$this->request->redirect('rogue/index');
	}

	public function action_getpotion($position)
	{
		$session	= Session::instance();
		$level		= $session->get(Model_Constants::SESSION_DUNGEON_LEVEL);
		try
		{
			$potion		= $level->getPotion($position);
			$this->avatar->addItem($potion, $session);
			$session->set(Model_Constants::SESSION_DUNGEON_LEVEL, $level);
			$session->set(Model_Constants::SESSION_FOUND_ITEM, $potion);
		}
		catch (Exception $ex)
		{
		}
		//$this->request->redirect('rogue/index');
		$this->action_index();
	}

	
	public function action_getgold($position)
	{
		$session	= Session::instance();
		$level		= $session->get(Model_Constants::SESSION_DUNGEON_LEVEL);
		try
		{
			$gold	= $level->getGold($position);
			$this->avatar->addGold($gold);
			$session->set(Model_Constants::SESSION_DUNGEON_LEVEL, $level);
			$session->set(Model_Constants::SESSION_FOUND_ITEM, $gold);
		}
		catch (Exception $ex)
		{
		}
		//$this->request->redirect('rogue/index');
		$this->action_index();
	}

	public function action_getfood($position)
	{
		$session	= Session::instance();
		$level		= $session->get(Model_Constants::SESSION_DUNGEON_LEVEL);
		try
		{
			$food	= $level->getFood($position);
			$this->avatar->addItem($food, $session);
			$session->set(Model_Constants::SESSION_DUNGEON_LEVEL, $level);
			$session->set(Model_Constants::SESSION_FOUND_ITEM, $food);
		}
		catch (Exception $ex)
		{
			//print($ex->getMessage());exit;
		}
		$this->action_index();
	}

	public function action_getscroll($position)
	{
		$session	= Session::instance();
		$level		= $session->get(Model_Constants::SESSION_DUNGEON_LEVEL);
		try
		{
			$scroll		= $level->getScroll($position);
			$this->avatar->addItem($scroll, $session);
			$session->set(Model_Constants::SESSION_DUNGEON_LEVEL, $level);
			$session->set(Model_Constants::SESSION_FOUND_ITEM, $scroll);
			//$this->request->redirect('rogue/index');
		}
		catch (Exception $ex)
		{
		}
		$this->action_index();
	}
	
	public function action_getweapon($position)
	{
		$session	= Session::instance();
		$level		= $session->get(Model_Constants::SESSION_DUNGEON_LEVEL);
		try
		{
			$weapon		= $level->getWeapon($position);
			$this->avatar->addItem($weapon, $session);
			$session->set(Model_Constants::SESSION_DUNGEON_LEVEL, $level);
			$session->set(Model_Constants::SESSION_FOUND_ITEM, $weapon);
		}
		catch (Exception $ex)
		{
		}
		//$this->request->redirect('rogue/index');
		$this->action_index();
	}
	
	public function action_identify($index)
	{
		$this->template->title = 'Blood Rogue';
		$session	= Session::instance();
		$items		= $session->get(Model_Constants::SESSION_ITEMS);
		$itemToIdentify = $items[$index];
		$scrollIndex= $session->get(Model_Constants::SESSION_ACTIVE_ITEM);
		$scroll		= $items[$scrollIndex];
		if ((isset($scroll)) && ($scroll instanceof Model_Scroll) && ($scroll->getType() == 'Identify'))
		{
			//$this->avatar->removeItem($items, $index, $session);
			array_splice($items, $scrollIndex, 1);
			$itemToIdentify->setIsIdentified(true);
			$level = $session->get(Model_Constants::SESSION_DUNGEON_LEVEL);
			$level->updateScrolls($scroll->getType());
			
			$identifyAll = false;
			if ($itemToIdentify instanceof Model_Potion)
			{
				$level->updatePotions($itemToIdentify->getType());
				$identifyAll = true;
			}
			elseif ($itemToIdentify instanceof Model_Scroll)
			{
				$level->updateScrolls($itemToIdentify->getType());
				$identifyAll = true;
			}
			
			if ($identifyAll)
			{
				foreach ($items as $i)
				{
					if (($itemToIdentify->getType() == $i->getType()) || ($i->getType() == 'Identify'))
					{
						$i->setIsIdentified(true);
					}
				}
			}

			$session->set(Model_Constants::SESSION_ITEMS, $items);
			$session->set(Model_Constants::SESSION_DUNGEON_LEVEL, $level);
			
			$view = View::factory('inventory');
			$view->inventory = $items;
			$view->selectedIndex = 1000; // Just need something that isn't going to be in the list
			$view->dimTheLights = true;
			$this->template->content = $view;

			$view->ascii = View::factory('ascii/scroll');
			$view->popup = View::factory('elements/popupwlink');
			$view->popup->popupTitle= 'You found a '.$itemToIdentify->getName();
			$view->popup->link		= 'http://'.$view->env->www_root.'/rogue/';
			$this->template->content= $view;
			//return;
		}
		//print('huh?');exit;
	}
	
	public function action_inventory()
	{
		$this->template->title = 'Blood Rogue';
		$session	= Session::instance();
		$index		= $session->get(Model_Constants::SESSION_ACTIVE_ITEM);
		if (isset($index))
		{
			$items = $session->get(Model_Constants::SESSION_ITEMS);
			if (isset($items))
			{
				$view = View::factory('inventory');
				$view->inventory = $items;
				$view->selectedIndex = $index;
				$view->dimTheLights = false;
				$view->ascii = View::factory('ascii/scroll');
				$this->template->content = $view;
			}
			else
			{
				print('no items');exit;
				//$this->request->redirect('rogue/index');
				$this->action_index();
			}
		}
		else
		{
			print('no index');exit;
			//$this->request->redirect('rogue/index');
			$this->action_index();
		}
	}
	
	public function action_attack($position)
	{
		$session	= Session::instance();
		$level		= $session->get(Model_Constants::SESSION_DUNGEON_LEVEL);
		try
		{
			$monster	= $level->getMonster($position);
			if ($monster)
			{
				$fight = new Model_Fight();
				$weapon = $session->get(Model_Constants::SESSION_WEAPON);
				$this->avatar->setWeapon($weapon);
				$fight->battle($this->avatar, $monster);
				if ($this->avatar->hit_points <= 0)
				{
					$this->action_death();
					return;
				}
				$this->avatar->subtractHunger(1);
				$this->avatar->save();
				$session->set(Model_Constants::SESSION_FOUND_ITEM, $fight);
			}
			$session->set(Model_Constants::SESSION_DUNGEON_LEVEL, $level);
		}
		catch (Exception $ex)
		{
		}
		//$session->set('monster', $monster);
		//$this->request->redirect('rogue/index');
		$this->action_index();
	}
	
	public function action_story($id)
	{
		$this->template = View::factory('template');
		$id = 0;
		switch ($id)
		{
			case 0:
				$view = View::factory('story');
				$view->ascii = View::factory('ascii/sword2');
				$view->story = View::factory('fiction/intro');
				$this->template->content = $view;
				break;
		}
		$this->avatar->bookmark_id = 1;
		$this->avatar->save();
	}
	
	public function action_store()
	{
		$session = Session::instance();
		$level = $session->get(Model_Constants::SESSION_DUNGEON_LEVEL);
		$view = View::factory('store');
		$this->setup_infocolumn($view, $level, $session);
		$this->template->content = $view;
	}

	public function action_friends()
	{
		$session = Session::instance();
		$level = $session->get(Model_Constants::SESSION_DUNGEON_LEVEL);
		$view = View::factory('friends');
		$this->setup_infocolumn($view, $level, $session);
		$this->template->content = $view;
	}

	public function action_pvp()
	{
		$session = Session::instance();
		$level = $session->get(Model_Constants::SESSION_DUNGEON_LEVEL);
		$view = View::factory('friends');
		$this->setup_infocolumn($view, $level, $session);
		$this->template->content = $view;
	}

	private function setup_infocolumn($view, $level, $session)
	{
		$view->avatar = $this->avatar;
		$view->infocolumn = View::factory('elements/infocolumn');
		$view->infocolumn->dungeonLevel		= $level->getDungeonLevel();
		$view->infocolumn->playerLevel		= $this->avatar->getLevelForXp($this->avatar->xp);
		$view->infocolumn->xp				= $this->avatar->xp;
		$view->infocolumn->gold				= $this->avatar->gold;
		$view->infocolumn->hit_points		= $this->avatar->hit_points;
		$view->infocolumn->hit_points_max	= $this->avatar->hit_points_max;
		$view->infocolumn->strength			= $this->avatar->strength;
		$view->infocolumn->strength_max		= $this->avatar->strength_max;		
		$items = $session->get('items');
		if (!isset($items))
		{
			$items = array();
		}
		$view->infocolumn->items	= $items;
	}
} // End Rogue
