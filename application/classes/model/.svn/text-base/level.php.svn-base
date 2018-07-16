<?php defined('SYSPATH') or die('No direct script access.');

class Model_Level
{
	const MAP_SIZE		= 9;	

	private $itemTypes		= array('Scroll', 'Potion', 'Weapon', 'Armor', 'Jewelry');

	private $map			= array();
	private $roomCount		= 0;
	private $objectCount	= 0;
	private $monsterCount	= 0;
	private $playerPosition	= 0;
	private $confusedAmount	= 0;
	private $avatar;
	private $rooms;
	private $name;
	private $dungeonLevel;
	private $foodCount;
	
	/* TODO: move these to game level */
	private $potions;
	private $scrolls;
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getMap()
	{
		return $this->map;
	}
	
	public function getRoomCount()
	{
		return $this->roomCount;
	}
	
	public function getObjectCount()
	{
		return $this->objectCount;
	}
	
	public function getMonsterCount()
	{
		return $this->monsterCount;
	}
	public function setMonsterCount($val)
	{
		$this->monsterCount = $val;
	}
	
	public function getPlayerPosition()
	{
		return $this->playerPosition;
	}

	public function getCurrentRoom()
	{
		return $this->currentRoom;
	}
	
	public function getRooms()
	{
		return $this->rooms;
	}
	
	public function getAvatar()
	{
		return $this->avatar;
	}
	
	public function getDungeonLevel()
	{
		return $this->dungeonLevel;
	}
	
	public function increaseLevel()
	{
		$this->dungeonLevel++;
		$this->reset(false);
		$this->placePlayerAtPosition(0);
	}
	
	public function getConfusedAmount()
	{
		return $this->confusedAmount;
	}
	public function setConfusedAmount($val)
	{
		$this->confusedAmount = $val;
	}
	
	public function __construct($name, $dungeonLevel = 1)
	{
		$this->dungeonLevel = $dungeonLevel;
		$this->reset();
	}
		
	private function reset($shuffle = true)
	{
		$this->setConfusedAmount(0);
		$this->items = array();
		$this->rooms = array();
		
		// 5-8 rooms per level
		// Put a room in square 0
		$this->setRoom(0);
		// Put a room in square 8
		$this->setRoom(8);
		// Add the stairs to square 8
		$stairs = new Model_Stairs();
		$this->placeObjectAtPosition(8, $stairs);
				
		$roomCount			= mt_rand(3, 6);
		$mapPositions		= array(1, 2, 3, 4, 5, 6, 7);
		
		// Set rooms
		for ($i = 0; $i < $roomCount; $i++)
		{
			// Put a room in a random empty spot
			$dieRoll = mt_rand(0, count($mapPositions) - 1);
			$this->setRoom($mapPositions[$dieRoll]);
			unset($mapPositions[$dieRoll]);
			$mapPositions = array_values($mapPositions);
		}

		// Set hallways
		foreach ($mapPositions as $key => $value)
		{
			$this->setHallway($value);
		}
		
		if ($shuffle)
		{
			$potion = new Model_Potion();
			$this->potions = $potion->shuffle();
			$scroll = new Model_Scroll();
			$this->scrolls = $scroll->shuffle();
		}
		
		$this->putObjects();
		$this->putMonsters();
		$this->putGold($roomCount);
	}
	
	// Returns a Model_Room/Model_Hallway
	public function getLocationAtPosition($position)
	{
		//print(__FUNCTION__.'<br>');
		$location = null;
		if (isset($this->map[$position]))
		{
			$location = $this->map[$position];
		}
		//print('Position: '.$position.', location image: '.$location->getWebImage()).'<br>';
		return $location;
	}
	
	public function addAvatar()
	{
		//$this->avatar = $avatar;
		$this->placePlayerAtPosition(0);
	}
	
	public function moveAvatar($direction)
	{
		$message = null;
		if ($this->getConfusedAmount())
		{
			$coinFlip = mt_rand(0, 1);
			if ($coinFlip)
			{
				$direction *= -1;
			}
			$newAmount = $this->getConfusedAmount() - 1;
			$this->setConfusedAmount($newAmount);
			if ($newAmount == 0)
			{
				$message = 'Your head clears.';
			}
		}
		$nextPosition = $this->playerPosition + $direction;
		if (($this->playerPosition == 2) && ($direction == 1))
		{
			$nextPosition = 5;
		}
		elseif (($this->playerPosition == 5) && ($direction == -1))
		{
			$nextPosition = 2;
		}
		elseif (($this->playerPosition == 5) && ($direction == 1))
		{
			$nextPosition = 4;
		}
		elseif (($this->playerPosition == 4) && ($direction == 1))
		{
			$nextPosition = 3;
		}
		elseif (($this->playerPosition == 4) && ($direction == -1))
		{
			$nextPosition = 5;
		}
		elseif (($this->playerPosition == 3) && ($direction == -1))
		{
			$nextPosition = 4;
		}
		elseif (($this->playerPosition == 3) && ($direction == 1))
		{
			$nextPosition = 6;
		}
		elseif (($this->playerPosition == 6) && ($direction == -1))
		{
			$nextPosition = 3;
		}
		//print('direction:'.$direction.', pp:'.$this->playerPosition.', np:'.$nextPosition);exit;
		$location = $this->getLocationAtPosition($this->playerPosition);
		$nextLocation = $this->getLocationAtPosition($nextPosition);
		if (($location instanceof Model_Room) && ($nextLocation))
		{
			$location->turnOffLights();
		}
		if ($nextLocation instanceof Model_Room)
		{
			$this->placePlayerAtPosition($nextPosition);
		}
		elseif ($nextLocation instanceof Model_Hallway)
		{
			$this->playerPosition = $nextPosition;
			$this->moveAvatar($direction);
			$nextLocation->setShowOnMap(true);
		}
		return $message;
	}
	
	public function placeAvatarAtRandom()
	{
		do
		{
			$dieRoll = mt_rand(0, 8);
			$location = $this->getLocationAtPosition($dieRoll);
		} while (!($location instanceof Model_Room));
		$this->placePlayerAtPosition($location->getPosition());
	}

	private function putObjects()
	{
		$objectCount = mt_rand(2, 5);
		while (mt_rand(0, 100) < 34)
		{
			$objectCount++;
			if ($objectCount > 6)
			{
				break;
			}
		}
		$this->objectCount = $objectCount;
		//$objectCount = 1;
		for ($i = 0; $i < $objectCount; $i++)
		{
			$obj = $this->getRandomObject();
			$this->placeObject($obj);
		}
	}
	
	private function putMonsters()
	{
		$monsterCount = mt_rand(4, 6);
		$this->monsterCount = $monsterCount;
		//$objectCount = 1;
		for ($i = 0; $i < $monsterCount; $i++)
		{
			$obj = Model_Mmonster::getRandomMonster($this->dungeonLevel);
			$this->placeObject($obj);
		}
	}
	
	private function putGold($objectCount)
	{
		for ($i = 0; $i < $objectCount; $i++)
		{
			$gold = new Model_Gold($this->getDungeonLevel());
			$this->placeObject($gold);
		}
	}
	
	public function placeObject($obj)
	{
		/* DEBUG */
		$room = $this->getRandomRoom();
		//$room = $this->getLocationAtPosition(0);
		$room->addObject($obj);
	}
	
	public function getRandomRoom()
	{
		$dieRoll = mt_rand(0, count($this->rooms) - 1);
		return $this->rooms[$dieRoll];
	}

	// Position = 0-8
	public function placePlayerAtPosition($position)
	{
		// TODO: add error checking to see if it's a room
		$location = $this->getLocationAtPosition($this->playerPosition);
		if ($location instanceof Model_Room)
		{
			$location->turnOffLights();
		}
		
		$location = $this->getLocationAtPosition($position);
		if ($location instanceof Model_Room)
		{
			$location->turnOnLights();
			$this->playerPosition = $position;
		}
	}
	
	public function placeObjectAtPosition($position, $object)
	{
		$room = $this->getLocationAtPosition($position);
		$room->addObject($object);
	}
	
	public function setRoom($position)
	{
		if (!isset($this->map[$position]))
		{
			$this->roomCount++;
		}
		$room = new Model_Room($position);
		$this->setLocation($position, $room);
		$this->rooms[] = $room;
	}

	public function setHallway($position)
	{
		$hallway = new Model_Hallway($position);
		$this->setLocation($position, $hallway);
	}

	// Places a location (hallway or room) at a position, 0-8
	public function setLocation($position, $location)
	{
		if (($position < 0) || ($position > 8))
		{
			throw new Exception('Invalid position.');
		}
		$this->map[$position] = $location;
	}
	
	public function monsterInTheRoom()
	{
		$room = $this->getLocationAtPosition($this->playerPosition);
		return $room->checkForMonster();
	}

	public function getRandomObject()
	{
		if ($this->foodCount < floor($this->dungeonLevel / 3))
		{
			$itemType = 'Food';
			$this->foodCount++;
		}
		else
		{
			$dieRoll = mt_rand(1, 81);
			if ($dieRoll < 35)
			{
				$itemType = 'Scroll';
			}
			elseif ($dieRoll < 70)
			{
				$itemType = 'Potion';
			}
			elseif ($dieRoll < 82)
			{
				$itemType = 'Weapon';
			}
			elseif ($dieRoll < 92)
			{
				$itemType = 'Armor';
			}
			else
			{
				$itemType = 'Jewelry';
			}
		}
		$item = null;
		switch ($itemType)
		{
			case 'Scroll':
				$s = new Model_Scroll();
				//$s->setType($s->getRandomType());
				$item = $s;
				break;
			case 'Potion':
				$p = new Model_Potion();
				//$p->setType($p->getRandomType());
				$item = $p;
				break;
			case 'Weapon':
				$item = new Model_Weapon();
				break;
			case 'Armor':
				$item = new Model_Armor();
				break;
			case 'Jewelry':
				$item = new Model_Jewelry();
				$item->makeRandom();
				break;
			case 'Food':
				$item = new Model_Food();
				break;
			default:
				throw new Exception("Not a valid item type: ".$itemType);
				break;
		}
		return $item;
	}
	
	public function getPotion($position)
	{
		$room = $this->getLocationAtPosition($this->playerPosition);
		//$room->printAll();
		$potion = $room->getPotion($position);
		if ($potion)
		{
			$selected = $this->potions[$potion->getType()];
			Model_Potion::copy($selected, $potion);
			if ($this->isIdentified($this->potions, $potion->getType()))
			{
				$potion->setIsIdentified(true);
			}
		}
		//$room->printAll();
		//exit;
		return $potion;
	}
	
	public function getScroll($position)
	{
		$room = $this->getLocationAtPosition($this->playerPosition);
		$scroll = $room->getScroll($position);
		if ($scroll)
		{
			$selected = $this->scrolls[$scroll->getType()];
			Model_Scroll::copy($selected, $scroll);
			if ($this->isIdentified($this->scrolls, $scroll->getType()))
			{
				$scroll->setIsIdentified(true);
			}
		}
		return $scroll;
	}
	
	public function getWeapon($position)
	{
		$room = $this->getLocationAtPosition($this->playerPosition);
		$item = $room->getWeapon($position);
		return $item;
	}
	
	public function getGold($position)
	{
		$room = $this->getLocationAtPosition($this->playerPosition);
		$item = $room->getGold($position);
		return $item;
	}
	
	public function getFood($position)
	{
		$room = $this->getLocationAtPosition($this->playerPosition);
		$item = $room->getFood($position);
		return $item;
	}
	
	public function getMonster($position)
	{
		$room = $this->getLocationAtPosition($this->playerPosition);
		$monster = $room->getMonster($position);
		return $monster;
	}
	
	public function updatePotions($type)
	{
		foreach ($this->potions as $item)
		{
			if ($item->getType() == $type)
			{
				$item->setIsIdentified(true);
			}
		}
	}
	
	public function updateScrolls($type)
	{
		foreach ($this->scrolls as $item)
		{
			if ($item->getType() == $type)
			{
				$item->setIsIdentified(true);
			}
		}
	}
	
	public function isIdentified($items, $type)
	{
		foreach ($items as $item)
		{
			if ($item->getType() == $type)
			{
				return $item->getIsIdentified();
			}
		}
		throw new Exception("Couldn't find type: ".$type);
	}
	
	public function printAll()
	{
		foreach ($this->rooms as $r)
		{
			print('Room at Position: '.$r->getPosition().'<br>');
			foreach ($r->getObjects() as $obj)
			{
				print('&nbsp;&nbsp;Object at ('.$obj->getX().', '.$obj->getY().'), '.$obj->getImage().'<br>');
			}
			if ($r->getPosition() == $this->getPlayerPosition())
			{
				$avatarLocation = $r->getOpenLocation();
				$avatarX		= $r->getXForLocation($avatarLocation);
				$avatarY		= $r->getYForLocation($avatarLocation);
				print('----- PLAYER at ('.($r->getX()+$avatarX).', '.($r->getY()+$avatarY).') ------<br>');
			}
		}
		print('<br><br>Player Position: '.$this->getPlayerPosition().'<br>');
	}
}
?>