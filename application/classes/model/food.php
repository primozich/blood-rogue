<?php defined('SYSPATH') or die('No direct script access.');

class Model_Food extends Model_Item
{
	private $message	= 'Mmmm...';
	private $letter		= '&';
	private $types	= array('Bread', 'Fruit', 'Wine');
	
	public function getMessage()
	{
		return $this->message . '. You found some ' . $this->getType() . '.';
	}
	
	public function getLetter()
	{
		return $this->letter;
	}
	
	public function __construct($dungeonLevel = 1)
	{
		parent::__construct();
		$this->setType($this->types[mt_rand(0, count($this->types)-1)]);
		$this->setImage('colon.gif');
		$this->setName($this->getType());
		$this->setMagicName($this->getType());
	}
	
	public function eat($avatar)
	{
		switch($this->getType())
		{
			case 'Bread':
			case 'Fruit':
			case 'Wine':
				//$amount = mt_rand(3, 8);
				$avatar->subtractHunger(12);
				break;
		}
	}
	
	public function getMyLink()
	{
		return 'http://' . Model_Constants::APP_SERVER . '/' . Model_Constants::APP_DIR . 'rogue/getfood/'.$this->getPosition(); 
	}

}
?>