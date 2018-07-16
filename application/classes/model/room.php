<?php defined('SYSPATH') or die('No direct script access.');

class Model_Room extends Model_Location
{
	const DARK_DIR			= 'dark';
	const WIDTH_OF_LOCATION	= 11;
	const HEIGHT_OF_LOCATION= 16;

	// image,width,height,offset x, offset y,doors(x,y,direction)
	private $roomData		= array(
								array(
									array('room1.gif',130,78,30,91,array(0 => array(120,0,1)))							// top left
								), 
								array(
									array('room1.gif',82,62,71,36,array(0 => array(-4,40,-1), 1 => array(79,10,1))),	// top middle
									array('room2.gif',63,44,69,95,array(0 => array(-4,30,-1), 1 => array(66,-1,1))),
									array('room3.gif',82,60,65,19,array(0 => array(-7,38,-1), 1 => array(77,23,1))),
								), 
								array(
									array('room1.gif',52,79,82,42,array(0 => array(-8,34,-1), 1 => array(27,68,1))),	// top right
								),
								array(
									array('room1.gif',98,79,47,37,array(0 => array(31,-7,-1), 1 => array(-4,56,1))),	// middle right
								), 
								array(
									array('room1.gif',56,47,47,18,array(0 => array(12,41,1), 1 => array(53,24,-1))),	// middle left
								),
								array(
									array('room1.gif',84,47,51,55,array(0 => array(44,-11,-1), 1 => array(80,22,1))),	// bottom left
								),
								array(
									array('room1.gif',70,95,91,54,array(0 => array(-5,71,-1))),							// bottom right
								)
							);
	
	private $isDark;
	private $objects;
	private $locations;
	private $numLocations;
	private $rows;
	private $columns;
	private $roomImages;
	
	public function getAllImages()
	{
		return $this->roomImages;
	}
	
	public function getWebImage()
	{
		if ($this->getShowOnMap())
		{
			$path = self::MAP_DIR . self::getImageType($this->getPosition()) . '/';
			if ($this->isDark)
			{
				$path .= self::DARK_DIR . '/';
			}
			return $path . $this->getImage();
		}
		else
		{
			return 'black.gif';
		}
	}
	
	public function getObjects()
	{
		return $this->objects;
	}

	public function __construct($position, $image = null)
	{
		$offset		= Model_Location::getImageType($position);
		$data		= $this->roomData[$offset];
		$max		= count($data) - 1;
		$selected	= $data[mt_rand(0, $max)];
		
		$image		= $selected[0];
		$width		= $selected[1];
		$height		= $selected[2];
		$offsetX	= $selected[3];
		$offsetY	= $selected[4];
		$doors		= $selected[5];
		if (!$doors)
		{
			print('Position: '.$position.', width: '.$width.', height: '.$height);exit;
		}
		
		parent::__construct($position, $image);
		
		$this->id = Model_Avatar::getUuid();

		//$width			= 130;	// Actual width and height of room not the image which fills the quadrant
		//$height			= 78;
		
		$this->setX($offsetX);		// Offsets of upper left corner of room
		$this->setY($offsetY);
		$this->setWidth($width);
		$this->setHeight($height);
		$this->isDark	= true;
		$this->objects	= array();
		$this->locations= array();
		
		$this->rows		= floor($this->getHeight() / self::HEIGHT_OF_LOCATION);
		$this->columns	= floor($this->getWidth() / self::WIDTH_OF_LOCATION);
		$this->numLocations	= $this->rows * $this->columns;
		for ($i = 0; $i < $this->numLocations; $i++)
		{
			$this->locations[] = null;
			//print('Location: '.$i.', x: '.$this->getXForLocation($i).', y: '.$this->getYForLocation($i).'<br />');
		}
		foreach ($doors as $door)
		{
			$this->addDoor($door);
		}
		//$this->dInfo = 'Position: '.$position.', width: '.$width.', height: '.$height.', doors: '.print_r($doors, true);
		//$this->checkForDoors();
	}
	//public $dInfo;
	
	/*
	public function checkForDoors($info = null)
	{
		$hasDoors = false;
		foreach ($this->objects as $obj)
		{
			$obj->setShowOnMap(true);
			if ($obj instanceof Model_Door)
			{
				$hasDoors = true;
			}
		}
		if (!$hasDoors)
		{
			if($info)
			{
				print($info.'<br>');
			}
			print('no doors<br><br>');print($this->dInfo.'<br>');$this->printAll();exit;
		}
	}
	*/
	
	public function getXForLocation($location)
	{
		$col = $location % $this->columns;
		$x = self::WIDTH_OF_LOCATION * $col;
		//print('X Loc: '.$location.', Cols: '.$this->columns.', Col: '.$col.', X: '.$x.'<br>');
		return $x; 
	}
	
	public function getYForLocation($location)
	{
		$row = floor($location / $this->columns);
		$y = self::HEIGHT_OF_LOCATION * $row;
		//print('Y Loc: '.$location.', Cols: '.$this->columns.', Row: '.$row.', Y: '.$y.'<br>');
		return $y;
	}
	
	public function addObject($object)
	{
		$location = $this->getOpenLocation();
		// TODO: check whether all locations are full
		if (false)
		{
			throw new Exception('This room is full! ['.$this->getPosition().']');
		}
		$this->locations[$location] = $object;
		$object->setPosition($location);
		$x = $this->getXForLocation($location);
		$object->setX($x);
		$y = $this->getYForLocation($location);
		$object->setY($y);
		//$info = 'Rooms position: '.$this->getPosition().', location: '.$location.', set x: '.$x.', set y: '.$y.', class: '.get_class($object).'<br>';
		$this->objects[$location] = $object;
		//$this->checkForDoors($info);
	}
	
	public function getPotion($position)
	{
		return $this->getItem('Model_Potion', $position);
	}
	
	public function getScroll($position)
	{
		return $this->getItem('Model_Scroll', $position);
	}
	
	public function getMonster($position)
	{
		return $this->getItem('Model_Mmonster', $position);
	}
	
	public function checkForMonster()
	{
		foreach ($this->objects as $object)
		{
			if ($object instanceof Model_Mmonster)
			{
				return $object;
			}
		}
		return null;
	}
	
	public function getWeapon($position)
	{
		return $this->getItem('Model_Weapon', $position);
	}
	
	public function getGold($position)
	{
		return $this->getItem('Model_Gold', $position);
	}
	
	public function getFood($position)
	{
		return $this->getItem('Model_Food', $position);
	}
	
	private function getItem($itemType, $position)
	{
		$index = 0;
		foreach ($this->objects as $object)
		{
			if (($object instanceof $itemType) && ($position == $object->getPosition()))
			{
				$items = array_splice($this->objects, $index, 1);
				$item = $items[0];
				break;
			}
			$index++;
		}
		return $item;
	}
	
	// Returns an integer representing a location in the room from which x, y coords can be extracted
	public function getOpenLocation()
	{
		$numTries = 0;
		$location = null;
		do
		{
			$location = null;
			$index = mt_rand(0, $this->numLocations - 1);
			if (isset($this->locations[$index]))
			{
				$location = $this->locations[$index];
			}
			$numTries++;
		} while ((isset($location)) && ($numTries < $this->numLocations));
		//print('Location: '.$index.'<br>');
		if ($location)
		{
			throw new Exception('No open locations! Location: '.$location->getName().', tries: '.$numTries.', locations: '.
				$this->numLocations.', position: '.$this->getPosition().', image: '.$this->getImage().'.');
		}
		return $index;
	}
	
	public function turnOnLights()
	{
		$this->isDark = false;
		$this->setShowOnMap(true);
		foreach ($this->objects as $obj)
		{
			$obj->setShowOnMap(true);
		}
		//$this->checkForDoors();
	}
	
	public function turnOffLights()
	{
		$this->isDark = true;
		//$this->setShowOnMap(false);
		foreach ($this->objects as $obj)
		{
			$obj->setShowOnMap(false);
		}
	}
	
	public function addDoor($doorData)
	{
		$door = new Model_Door();
		$door->setX($doorData[0]);
		$door->setY($doorData[1]);
		$direction = $doorData[2];
		// Hack for the center square
		if ($this->getPosition() == 4)
		{
			$direction *= -1;
		}
		$door->setNextPosition($direction);
		// All other objects use a location as the index so we'll just use an index that others won't use
		$this->objects[1000+count($this->objects)] = $door;
		/*
		switch ($this->getPosition())
		{
			case 0:
				$door = new Model_Door();
				$door->setX($door[0]);
				$door->setY($door[1]);
				$door->setNextPosition($this->getPosition() + $direction);
				$this->objects[] = $door;
				break;
			case 1:
				$door1 = new Model_Door();
				$door1->setX(20);
				$door1->setY();
				$door1->setNextPosition($this->getPosition() + 1);
				$this->objects[] = $door1;
			case 6:
			case 7:
				break;
			case 2:
			case 5:
				break;
			case 3:
			case 4:
				break;
		}
		$this->objects[] = $door;
		*/
	}
	
	public function printAll()
	{
		print('----- Room ID: '.$this->getId().'<br>');
		foreach ($this->objects as $o)
		{
			print('OID: '.$o->getId().', '.$o->getType().', '.get_class($o).'<br>');
		}
	}
}
?>