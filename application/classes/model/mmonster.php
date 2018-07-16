<?php defined('SYSPATH') or die('No direct script access.');

class Model_Mmonster extends Model_Item
{
	private static $allMonsters;
	
	private $monster;
	
	public function getMonster()
	{
		return $this->monster;
	}
	
	public function getMyLink()
	{
		return 'http://' . Model_Constants::APP_SERVER . '/' . Model_Constants::APP_DIR . 'rogue/attack/' . $this->getPosition(); 
	}

	public function __construct($monster)
	{
		parent::__construct();
		
		$this->monster = $monster;
		$this->setImage($this->monster->letter.'.gif');
	}
	
	public function getDamage()
	{
		$dmg = 0;
		$hitChance = $this->monster->hit_chance;
		if (mt_rand(1, 100) < $hitChance)
		{
			$dmg = mt_rand($this->monster->min_dmg, $this->monster->max_dmg);
		}
		return $dmg;
	}
	
	public function takeDamage($dmg)
	{
		$this->monster->hit_points -= $dmg;
	}
	
	public static function getRandomMonster($level)
	{
		//if (count(self::$allMonsters) = 0)
		if (true)
		{
			self::$allMonsters = ORM::factory('monster')->where('first_level', '<=', $level)->where('last_level', '>=', $level)->find_all();
		}
		$count = count(self::$allMonsters);
		if ($count == 0)
		{
			print('Error in getRandomMonster: '.ORM::factory('monster')->last_query());exit;
		}
		$dieRoll = mt_rand(0, $count - 1);
		$randomMonster = self::$allMonsters[$dieRoll];
		return new Model_Mmonster($randomMonster);
	}
}
?>