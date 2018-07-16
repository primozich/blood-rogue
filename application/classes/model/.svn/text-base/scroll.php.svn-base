<?php defined('SYSPATH') or die('No direct script access.');

class Model_Scroll extends Model_Item
{
	//private $types	= array('Protect Armor', 'Hold Monster', 'Create Monster', 'Remove Curse',
	//					'Enchant Armor', 'Enchant Weapon', 'Clairvoyance');
	private $types	= array('Identify', 'Teleport');
	
	private $message;
	
	private $messages = array('Identify' => 'A vision fills your mind.', 'Teleport' => 'You feel a little dizzy.');
	
	public function getMessage()
	{
		return $this->message;
	}
	
	public function __construct($type = null)
	{
		parent::__construct();
		$this->setImage('question.gif');

		if (!$type)
		{
			$this->setType($this->getRandomType());
			$this->message = $this->messages[$this->getType()];
		}
	}
	
	public function getMyLink()
	{
		return 'http://' . Model_Constants::APP_SERVER . '/' . Model_Constants::APP_DIR . 'rogue/getscroll/' . $this->getPosition(); 
	}

	public function getRandomType()
	{
		return $this->types[mt_rand(0, count($this->types) - 1)];
	}
	
	public function shuffle()
	{
		$scrolls = array();
		foreach ($this->types as $type)
		{
			$name = '';
			for ($i = 0; $i < 3; $i++)
			{
				$name .= $this->getRandomWord();
			}
			$s = new Model_Scroll();
			$s->setMagicName($name);
			$s->setType($type); 
			$s->setName('Scroll of ' . $type);
			$scrolls[$type]	= $s; 
		}
		return $scrolls;
	}
	
	public function makeRandom()
	{
		$type = $this->types[mt_rand(0, count($this->types) - 1)];
		$this->setType($type);
	}

}
?>