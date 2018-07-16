<?php defined('SYSPATH') or die('No direct script access.');

class Model_Fight
{
	private $hero;
	private $monster;
	
	public function getHeroFighter()
	{
		return $this->hero;
	}
	
	public function getMonsterFighter()
	{
		return $this->monster;
	}
	
	public function battle($avatar, $monster)
	{
		$monsterModel = $monster->getMonster();
		
		$fighter1 = new Model_Fighter();
		$fighter2 = new Model_Fighter();
		$numberOfRounds = 0;
		do
		{
			$numberOfRounds++;
			$dmg = $avatar->getDamage($monster);
			$monster->takeDamage($dmg);
			$fighter1->addDamage($dmg);
			$fighter1->addAttack();
			
			if ($monsterModel->hit_points > 0)
			{
				$dmg = $monster->getDamage($avatar);
				$avatar->takeDamage($dmg);
				$fighter2->addDamage($dmg);
				$fighter2->addAttack();
			}
			
			if ($numberOfRounds > 20)
			{
				$monsterModel->hit_points = 0;
			}
		} while (($avatar->hit_points > 0) && ($monsterModel->hit_points > 0));
		
		if ($monsterModel->hit_points <= 0)
		{
			$avatar->addXp($monsterModel->xp);
		}
		
		$fighter1->setIsMonster(false);
		$fighter1->setHitPoints($avatar->hit_points - $fighter2->getDamageDone());
		$this->hero = $fighter1;
		
		$fighter2->setIsMonster(true);
		$fighter2->setHitPoints($monsterModel->hit_points - $fighter1->getDamageDone());
		$fighter2->setName($monsterModel->name);
		$this->monster = $fighter2;
	}
}
?>