<?php defined('SYSPATH') or die('No direct script access.');

class Model_Placeable
{	
	private $x;
	private $y;
	private $position;
	private $image;
	private $showOnMap;
	
	public function getX()
	{
		return $this->x;
	}
	public function setX($val)
	{
		$this->x = $val;
	}
	
	public function getY()
	{
		return $this->y;
	}
	public function setY($val)
	{
		$this->y = $val;
	}

	public function getPosition()
	{
		return $this->position;
	}
	public function setPosition($val)
	{
		$this->position = $val;
	}
	
	public function getImage()
	{
		return $this->image;
	}
	public function setImage($val)
	{
		$this->image = $val;
	}
	
	public function getShowOnMap()
	{
		return $this->showOnMap;
	}
	public function setShowOnMap($val)
	{
		$this->showOnMap = $val;
	}
	
	public function __construct()
	{
		$this->showOnMap = false;
	}
}
?>