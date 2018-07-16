<?php defined('SYSPATH') or die('No direct script access.');

class Model_Fighter
{
	private $name;
	private $isMonster;
	private $damageDone;
	private $hitPoints;
	private $numberOfAttacks;
	
	public function getName()
	{
		return $this->name;
	}
	public function setName($val)
	{
		$this->name = $val;
	}
	
	public function getIsMonster()
	{
		return $this->isMonster;
	}
	public function setIsMonster($val)
	{
		$this->isMonster = $val;
	}
	
	public function getDamageDone()
	{
		return $this->damageDone;
	}
	public function setDamageDone($val)
	{
		$this->damageDone = $val;
	}
	
	public function getHitPoints()
	{
		return $this->hitPoints;
	} 
	public function setHitPoints($val)
	{
		$this->hitPoints = $val;
	}
	
	public function getNumberOfAttacks()
	{
		return $this->numberOfAttacks;
	}
	public function setNumberOfAttacks($val)
	{
		$this->numberOfAttacks = $val;
	}
	
	public function __construct()
	{
		$this->name				= '';
		$this->isMonster		= false;
		$this->damageDone		= 0;
		$this->hitPoints		= 0;
		$this->numberOfAttacks	= 0;
	}
	
	public function addDamage($dmg)
	{
		$this->damageDone += $dmg;
	}
	
	public function addAttack()
	{
		$this->numberOfAttacks++;
	}
}
?>