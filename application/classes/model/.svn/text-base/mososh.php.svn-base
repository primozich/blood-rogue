<?php
defined('SYSPATH') or die('No direct script access.');

class Model_Mososh extends ORM
{
	protected $_created_column = array('column' => 'created', 'format' => 'Y-m-d H:i:s');
	protected $_updated_column = array('column' => 'updated', 'format' => 'Y-m-d H:i:s');
	
	public function __construct($id = NULL)
	{
		parent::__construct($id);
	}

	public static function getUuid()
	{
		$query = DB::query(Database::SELECT, 'select uuid() as uuid');
		$query->execute();
		$result = $query->execute();
		$uuid = $result->get('uuid');
		return $uuid;
	}
}
?>
