<?php defined('SYSPATH') or die('No direct script access.');

class Model_Door extends Model_Item
{
	private $isLocked;
	private $nextRoom;
	
	public function getIsLocked()
	{
		return $this->isLocked;
	}
	public function setIsLocked($val)
	{
		$this->isLocked = $val;
	}
	
	public function getNextPosition()
	{
		return $this->nextPosition;
	}
	public function setNextPosition($val)
	{
		$this->nextPosition = $val;
	}
	
	public function __construct()
	{
		parent::__construct();
		$this->setImage('plus.gif');
		$this->setShowOnMap(false);
		
		$this->isLocked		= true;
		$this->nextPosition	= 0;
	}
	
	public function getMyLink()
	{
		return 'http://' . Model_Constants::APP_SERVER . '/' . Model_Constants::APP_DIR . 'rogue/opendoor/' . $this->getNextPosition();
	}
}
?>