<?php defined('SYSPATH') or die('No direct script access.');

class Model_Hallway extends Model_Location
{
	private $hallwayImages	= array(1 => array('hallway1.gif', 'hallway2.gif', 'hallway3.gif'), 2 => array('hallway1.gif'), 
								3 => array('hallway1.gif'), 4 => array('hallway1.gif'), 5 => array('hallway1.gif'), 6 => array('hallway1.gif'));

	public function getAllImages()
	{
		return $this->hallwayImages;
	}
	
	public function __construct($position, $image = null)
	{
		$this->setAllImages($this->hallwayImages);
		parent::__construct($position, $image);
	}
}
?>