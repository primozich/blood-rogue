<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Items extends Controller_Template
{
	public $template = 'template';
	private $avatar;
	
	public function __construct(Kohana_Request $request)
	{
		parent::__construct($request);
		
		$session = Session::instance();
		$userId = $session->get('user_id');
		
		if (!isset($avatar))
		{
			//print('no avatar in session');exit;
			$avatar = ORM::factory('avatar', array('user_id' => $userId));
			if (!$avatar->loaded())
			{
				throw new Exception("You need an adventurer to do that. Please try refreshing and email support@mososh.com if you continue to have trouble.");
			}
			$weapon = $session->get(Model_Constants::SESSION_WEAPON);
			if (!isset($weapon))
			{
				$dagger = new Model_Weapon('Dagger', '');
				$avatar->addItem($dagger, $session);
				$avatar->equipItem($dagger, $session);
			}
		}
		$this->avatar = $avatar;
	}
	
	public function action_reqtest()
	{
		$c = new Controller_Rogue($this->request);
		$c->action_index();
	}
	
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
			}
			elseif ($item instanceof Model_Potion)
			{
				$item->drink($this->avatar);
				//$this->avatar->removeItem($items, $index, $session);
				array_splice($items, $index, 1);
				$level = $session->get(Model_Constants::SESSION_DUNGEON_LEVEL);
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
			}
			elseif ($item instanceof Model_Scroll)
			{
				$session->set(Model_Constants::SESSION_ACTIVE_ITEM, $index);
				//$this->request->redirect('items/inventory');
				$this->action_inventory();
			}
		}
		$this->request->redirect('rogue/index');
	}
	
	public function action_getpotion($position)
	{
		$session	= Session::instance();
		$level		= $session->get(Model_Constants::SESSION_DUNGEON_LEVEL);
		$potion		= $level->getPotion($position);
		/*
		$items		= $session->get('items');
		if (!isset($items))
		{
			$items = array();
		}
		$items[]	= $potion;
		$session->set('items', $items);
		*/
		$this->avatar->addItem($potion, $session);
		$session->set(Model_Constants::SESSION_DUNGEON_LEVEL, $level);
		$session->set(Model_Constants::SESSION_FOUND_ITEM, $potion);
		$this->request->redirect('rogue/index');
	}

	public function action_getscroll($position)
	{
		$session	= Session::instance();
		$level		= $session->get(Model_Constants::SESSION_DUNGEON_LEVEL);
		$scroll		= $level->getScroll($position);
		$this->avatar->addItem($scroll, $session);
		$session->set(Model_Constants::SESSION_DUNGEON_LEVEL, $level);
		$session->set(Model_Constants::SESSION_FOUND_ITEM, $scroll);
		$this->request->redirect('rogue/index');
	}
	
	public function action_getweapon($position)
	{
		$session	= Session::instance();
		$level		= $session->get(Model_Constants::SESSION_DUNGEON_LEVEL);
		$weapon		= $level->getWeapon($position);
		$this->avatar->addItem($weapon, $session);
		$session->set(Model_Constants::SESSION_DUNGEON_LEVEL, $level);
		$session->set(Model_Constants::SESSION_FOUND_ITEM, $weapon);
		$this->request->redirect('rogue/index');
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
			
			if ($itemToIdentify instanceof Model_Potion)
			{
				$level->updatePotions($itemToIdentify->getType());
			}

			foreach ($items as $i)
			{
				if (($itemToIdentify->getType() == $i->getType()) || ($i->getType() == 'Identify'))
				{
					$i->setIsIdentified(true);
				}
			}

			$session->set(Model_Constants::SESSION_ITEMS, $items);
			$session->set(Model_Constants::SESSION_DUNGEON_LEVEL, $level);
			
			$view = new View('inventory');
			$view->inventory = $items;
			$view->selectedIndex = 1000; // Just need something that isn't going to be in the list
			$view->dimTheLights = true;
			//$view->ascii = new View('ascii/scroll');
			$this->template->content = $view;

			$view->ascii = new View('ascii/scroll');
			$view->popup = new View('elements/popupwlink');
			//$view->popup->ascii			= new View('ascii/potion');
			$view->popup->popupTitle	= 'You found a '.$itemToIdentify->getName();
			$env = Kohana::config('env');
			$view->popup->link		= 'http://'.$env->www_root.'/rogue/';
			$this->template->content = $view;
			return;
		}
		print('huh?');exit;
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
				$view = new View('inventory');
				$view->inventory = $items;
				$view->selectedIndex = $index;
				$view->dimTheLights = false;
				$view->ascii = new View('ascii/scroll');
				$this->template->content = $view;
			}
			else
			{
				$this->request->redirect('rogue/index');
			}
		}
		else
		{
			$this->request->redirect('rogue/index');
		}
	}
	
	public function action_attack($position)
	{
		$session	= Session::instance();
		$level		= $session->get(Model_Constants::SESSION_DUNGEON_LEVEL);
		$monster	= $level->getMonster($position);
		if ($monster)
		{
			$fight = new Model_Fight();
			$weapon = $session->get(Model_Constants::SESSION_WEAPON);
			$this->avatar->setWeapon($weapon);
			$fight->battle($this->avatar, $monster);
			$this->avatar->save();
			$session->set(Model_Constants::SESSION_FOUND_ITEM, $fight);
		}
		$session->set(Model_Constants::SESSION_DUNGEON_LEVEL, $level);
		//$session->set('monster', $monster);
		$this->request->redirect('rogue/index');
	}
	
} // End Items
