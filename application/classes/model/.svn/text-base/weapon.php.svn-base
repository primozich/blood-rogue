<?php defined('SYSPATH') or die('No direct script access.');

class Model_Weapon extends Model_Item
{
	/*
	 * Dagger:	Base dmg 1-4
	 * 		Bone:	Bonus 8
	 * 		Ruby:	Bonus 12
	 * Club:	Base dmg 1-6
	 * 		Wooden	Bonus 0
	 * 		Spiked	Bonus 2
	 * Mace:	Base dmg 2-8
	 * 		Iron	Bonus 3
	 * 		Skull	Bonus 5
	 * Axe:		Base dmg 4-10
	 * 		Hand	Bonus -2
	 * 		Warrior's	Bonus 4
	 * 		Battle	Bonus 6
	 * Sword:	Base dmg 4-12
	 * 		Short	-2
	 * 		Long	Bonus 1
	 * 		Bastard	Bonus 4
	 * 		Two-handed Bonus 5
	 */
	private $mainTypes	= array('Axe', 'Club', 'Mace', 'Sword', 'Dagger');
	private $subTypes	= array(
							'Club'	=> array('Wooden', 'Spiked'),
							'Mace'	=> array('', 'Iron', 'Skull'),
							'Axe'	=> array('', 'Hand', 'Warrior\'s', 'Battle'),
							'Sword'	=> array('Short', 'Long', 'Bastard', 'Two-Handed'),
							'Dagger'=> array('', 'Bone', 'Ruby')
						);

	private $subType;
	private $hitBonus;
	private $dmgBonus;
	private $dmgMin;
	private $dmgMax;
	private $isBeingUsed;
	
	public function getName()
	{
		if ($this->getIsIdentified())
		{
			return '+'.$this->dmgBonus.' '.$this->subType.' '.$this->type;
		}
		else
		{
			return $this->subType.' '.$this->type;
		}
	}
	
	public function getSubType()
	{
		return $this->subType;
	}
	public function setSubType($val)
	{
		$this->subType = $val;
	}
	
	public function getHitBonus()
	{
		return $this->hitBonus;
	}
	public function setHitBonus($val)
	{
		$this->hitBonus = $val;
	}
	
	public function getDmgBonus()
	{
		return $this->dmgBonus;
	}
	public function setDmgBonus($val)
	{
		$this->dmgBonus = $val;
	}
	
	public function getDmgMin()
	{
		return $this->dmgMin;
	}
	public function getDmgMax()
	{
		return $this->dmgMax;
	}
	
	public function getIsBeingUsed()
	{
		return $this->isBeingUsed;
	}
	public function setIsBeingUsed($val)
	{
		$this->isBeingUsed = $val;
	}
	
	public function getDamage()
	{
		$base = mt_rand($this->dmgMin, $this->dmgMax);
		$total = $base + $this->dmgBonus;
		return $total;
	}
	
	public function getMyLink()
	{
		return 'http://' . Model_Constants::APP_SERVER . '/' . Model_Constants::APP_DIR . 'rogue/getweapon/' . $this->getPosition(); 
	}
	
	public function __construct($type = null, $subType = null)
	{
		$this->isBeingUsed = false;
		
		if ($type)
		{
			$this->type = $type;
			$this->subType = $subType;
		}
		else
		{
			$this->setRandom();
		}
		
		$dieRoll = mt_rand(1, 3);
		switch ($dieRoll)
		{
			case 1:
				$this->setLetter(']');
				break;
			case 2:
				$this->setLetter('/');
				break;
			case 3:
				$this->setLetter(')');
				break;
		}
		
		switch ($this->type)
		{
			case 'Dagger':
				$this->dmgMin = 1;
				$this->dmgMax = 4;
				break;
			case 'Club':
				$this->dmgMin = 1;
				$this->dmgMax = 6;
				break;
			case 'Mace':
				$this->dmgMin = 2;
				$this->dmgMax = 8;
				break;
			case 'Axe':
				$this->dmgMin = 4;
				$this->dmgMax = 10;
				break;
			case 'Sword':
				$this->dmgMin = 4;
				$this->dmgMax = 12;
				break;
		}
		$this->dmgBonus = 0;
		switch ($this->subType)
		{
			case 'Bone':
				$this->dmgBonus = 8;
				break;
			case 'Ruby':
				$this->dmgBonus = 12;
				break;
			case 'Spiked':
				$this->dmgBonus = 2;
				break;
			case 'Iron':
				$this->dmgBonus = 3;
				break;
			case 'Skull':
				$this->dmgBonus = 5;
				break;
			case 'Hand':
				$this->dmgBonus = -2;
				break;
			case 'Warrior\'s':
				$this->dmgBonus = 4;
				break;
			case 'Battle':
				$this->dmgBonus = 6;
				break;
			case 'Short':
				$this->dmgBonus = -2;
				break;
			case 'Long':
				$this->dmgBonus = 1;
				break;
			case 'Bastard':
				$this->dmgBonus = 4;
				break;
			case 'Two-handed':
				$this->dmgBonus = 5;
				break;
		}
	}
	
	public function getRandom()
	{
		$type = $this->types[mt_rand(0, count($this->types) - 1)];
		$subType = $this->subTypes[$type][mt_rand(0, count($this->subTypes[$type]) - 1)];
		
		$weapon = new Model_Weapon($type, $subType);
		$weapon->setName($subType . ' ' . $type);
		return $weapon;
	}

	public function setRandom()
	{
		$type = $this->mainTypes[mt_rand(0, count($this->mainTypes) - 1)];
		$subType = $this->subTypes[$type][mt_rand(0, count($this->subTypes[$type]) - 1)];
				
		$this->type = $type;
		$this->subType = $subType;
		
		$dieRoll = mt_rand(1, 100);
		if ($dieRoll > 90)
		{
			$this->dmgBonus = 3;
		}
		elseif ($dieRoll > 80)
		{
			$this->dmgBonus = 2;
		}
		elseif ($dieRoll > 70)
		{
			$this->dmgBonus = 1;
		}
	}
	
}
?>