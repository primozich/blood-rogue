<?php defined('SYSPATH') or die('No direct script access.');

class Model_Gold extends Model_Item
{
	private $amount;
	private $message	= 'Oooh...shiny!';
	private $letter		= '*';
	
	public function getAmount()
	{
		return $this->amount;
	}
	public function setAmount($val)
	{
		$this->amount = $val;
	}
	
	public function getMessage()
	{
		return $this->message;
	}
	
	public function getLetter()
	{
		return $this->letter;
	}
	
	public function __construct($dungeonLevel = 1)
	{
		parent::__construct();
		$this->amount = mt_rand((2 * $dungeonLevel), (15 * $dungeonLevel));
		$this->setImage('asterisk.gif');
	}
	
	public function getMyLink()
	{
		return 'http://' . Model_Constants::APP_SERVER . '/' . Model_Constants::APP_DIR . 'rogue/getgold/'.$this->getPosition(); 
	}

}
?>