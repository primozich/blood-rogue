<?php defined('SYSPATH') or die('No direct access.');

class Model_Debug extends ORM 
{
	private $counter;
	
	public function getCounter()
	{
		return $this->counter;
	}
	public function setCounter($val)
	{
		$this->counter = $val;
	}
}
?>