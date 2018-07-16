<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller_Template
{
	public $template = 'template';
	private $avatar;
	
	public function __construct(Kohana_Request $request)
	{
		parent::__construct($request);

		$session = Session::instance();
		$userId = $session->get('user_id');
		
		//$avatar = $session->get('avatar');
		if (!isset($avatar))
		{
			//print('no avatar in session');exit;
			$avatar = ORM::factory('avatar', array('user_id' => $userId));
			if (!$avatar->loaded())
			{
				//print($userId);exit;
				$avatar->id			= Model_Avatar::getUuid();
				$avatar->user_id	= $userId;
				$avatar->name		= '';
				$avatar->strength	= Model_Avatar::STRENGTH_START;
				$avatar->hit_points	= Model_Avatar::HIT_POINTS_START;
				$avatar->strength_max	= Model_Avatar::STRENGTH_START;
				$avatar->hit_points_max	= Model_Avatar::HIT_POINTS_START;
				$avatar->pack_size	= Model_Avatar::PACK_SIZE_START;
				$avatar->level		= 1;
				$avatar->save();
				if (!($avatar->saved()))
				{
					throw new Exception("Couldn't create a new avatar. Please try refreshing and email support@mososh.com if you continue to have trouble.");
				}
			}
			$weapon = $session->get(Model_Constants::SESSION_WEAPON);
			if (!isset($weapon))
			{
				$dagger = new Model_Weapon('Dagger', '');
				$avatar->equipItem($dagger, $session);
			}
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
		$session->delete('items');
		$session->delete('found_item');
		$session->delete(Model_Constants::SESSION_WEAPON);
		$this->request->redirect('welcome/index');
	}
	
	public function action_opendoor($direction)
	{
		//print($nextPosition);exit;
		$session = Session::instance();
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
		$level->moveAvatar($direction);
		$session->set(Model_Constants::SESSION_DUNGEON_LEVEL, $level);
		$this->request->redirect('welcome/index');
	}

	public function action_index()
	{
		//$this->template->message = 'hello, world!';
		//$this->request->response = 'hello, world!';
		
		// Check for avatar
		$view = new View('index');
		
		$session = Session::instance();
		$level = $session->get(Model_Constants::SESSION_DUNGEON_LEVEL);	// Comment out for new level w/every refresh
		if (!isset($level))
		{
			$level = new Model_Level('Lvl 1');
			$session->set(Model_Constants::SESSION_DUNGEON_LEVEL, $level);	// Comment out for new level w/every refresh
			$level->addAvatar();
		}
		$view->level = $level;

		$view->popup = new View('elements/popup');
		$view->popup->showPopup		= false;
		$view->popup->popupTitle	= '';
		$view->popup->ascii			= new View('ascii/potion');
		$view->dimTheLights			= false;
		$foundItem = $session->get(Model_Constants::SESSION_FOUND_ITEM);
		if (isset($foundItem))
		{
			$view->dimTheLights		= true;
			$view->popup->showPopup	= true;
			if ($foundItem instanceof Model_Scroll)
			{
				$view->popup->popupTitle = 'You found a scroll that reads "'.trim($foundItem->getName()).'"';
				$view->popup->ascii			= new View('ascii/scroll');
			}
			elseif ($foundItem instanceof Model_Fight)
			{
				$fight	= $foundItem;
				$hero	= $fight->getHeroFighter();
				$badguy	= $fight->getMonsterFighter();
				$view->popup->popupTitle = 'You defeated a '.$badguy->getName().' in '.$hero->getNumberOfAttacks().' swings and lost '.
					$badguy->getDamageDone().' hit points';
				$view->popup->ascii			= new View('ascii/sword');
			}
			else
			{
				$view->popup->popupTitle= 'You found a '.$foundItem->getName();
			}
			$session->delete('found_item');
		}

		$view->avatar = $this->avatar;
		$view->infocolumn = new View('elements/infocolumn');
		print($level->getDungeonLevel());exit;
		$view->infocolumn->dungeonLevel		= $level->getDungeonLevel();
		$view->infocolumn->playerLevel		= $this->avatar->level;
		$view->infocolumn->xp				= $this->avatar->xp;
		$view->infocolumn->xp_next			= Model_Avatar::getXpForNextLevel($this->avatar->level);
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
		$view->infocolumn->env		= Kohana::config('env');
		$view->env = Kohana::config('env');
		$this->template->content	= $view;
	}
	
	public function action_hello()
	{
		$game = ORM::factory('game', '3ec5357c-b11c-11df-961f-ce6db64b54c2');
		//$game = new Model_Game();
		if ($game->loaded())
		{
			print $game->name;
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

} // End Welcome
