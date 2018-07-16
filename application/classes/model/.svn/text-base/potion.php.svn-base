<?php defined('SYSPATH') or die('No direct script access.');

class Model_Potion extends Model_Item
{
	//private $types	= array('Raise Level', 'Detect Objects', 'Detect Monsters', 'Strength', 'Vitality', 'Healing',
	//					'Extra Healing', 'Poison', 'Weakness');
	private $types	= array('Healing', 'Confusion', 'X-Factor', 'Clairvoyance');
	private $colors	= array('Blue', 'Green', 'Purple', 'Red', 'Plaid', 'Yellow', 'Ochre', 'Clear');
	
	private $message;
	private $duration;
	
	private $messages = array(
							'Healing'			=> 'A warm, tingly sensation fills your body.',
							'Confusion'			=> 'You feel disoriented.',
							'X-Factor'			=> 'Your senses start to operate at a superhuman level.',
							'Clairvoyance'		=> 'Everything seems a bit more vivid for a brief moment.',
	);
	private $durations = array(
							'Healing'			=> 0,
							'Confusion'			=> 4,
							'X-Factor'			=> 0,
							'Clairvoyance'		=> 0,
						);
	
	public function getMessage()
	{
		return $this->message;
	}
	
	public function __construct($type = null)
	{
		parent::__construct();
		$this->setImage('bang.gif');
		
		if (!$type)
		{
			$this->setType($this->getRandomType());
			$this->message = $this->messages[$this->getType()];
			$this->duration = $this->durations[$this->getType()];
		}
	}
	
	public function drink($avatar, $level)
	{
		$amount = 0;
		switch($this->getType())
		{
			case 'Healing':
				$amount = mt_rand(3, 8);
				$amount = $avatar->addHitPoints($amount);
				break;
			case 'Confusion':
				$amount = mt_rand(4, 6);
				$level->setConfusedAmount($amount);
				break;
			case 'Clairvoyance':
				$rooms = $level->getRooms();
				foreach ($rooms as $room)
				{
					foreach ($room->getObjects() as $item)
					{
						if (($item instanceof Model_Potion) || ($item instanceof Model_Scroll) || ($item instanceof Model_Weapon) || 
							($item instanceof Model_Armor) || ($item instanceof Model_Jewelry))
						{
							$item->setShowOnMap(true);
						}
					}
				}
				break;
			case 'X-Factor':
				$rooms = $level->getRooms();
				foreach ($rooms as $room)
				{
					foreach ($room->getObjects() as $item)
					{
						if ($item instanceof Model_Mmonster)
						{
							$item->setShowOnMap(true);
						}
					}
				}
				break;
		}
		return $amount;
	}
	
	public function getMyLink()
	{
		return 'http://' . Model_Constants::APP_SERVER . '/' . Model_Constants::APP_DIR . 'rogue/getpotion/'.$this->getPosition(); 
	}

	public function getRandomType()
	{
		return $this->types[mt_rand(0, count($this->types) - 1)];
	}
	
	public function shuffle()
	{
		$i = 0;
		$potions = array();
		shuffle($this->colors);
		foreach ($this->types as $type)
		{
			$p = new Model_Potion();
			$p->setMagicName($this->colors[$i] . ' Potion');
			$p->setName('Potion of ' . $type);
			$p->setType($type);
			$potions[$type] = $p;
			$i++;
		}
		return $potions;
	}
	
}
?>