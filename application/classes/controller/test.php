<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Test extends Controller_Template
{
	public $template = 'templateindex';
	
	public function action_insert()
	{
		$session = Session::instance();
		$debug = new Model_Debug();
		$debug->setCounter(99);
		$debug->debug = 'testing1';
		$session->set('test', $debug);
		$debug->save();
		var_dump($debug);
		print('done');exit;
	}
	
	public function action_read()
	{
		$session = Session::instance();
		$debug = $session->get('test');
		var_dump($debug);
		print($debug->getCounter());exit;
	}
	
	public function action_newlevel()
	{
		$level = new Model_Level('Lvl 1');
		$session->set(Model_Constants::SESSION_DUNGEON_LEVEL, $level);	// Comment out for new level w/every refresh
		$level->addAvatar();
		
		print('done');exit;
	}
	
	public function action_getlevel()
	{
		$level = $session->get(Model_Constants::SESSION_DUNGEON_LEVEL);
		
	}
}
?>