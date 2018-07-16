<?php defined('SYSPATH') or die('No direct script access.');

class Model_Item extends Model_Placeable
{
	private $id;
	private $name;
	private $type;
	private $isIdentified;
	private $magicName;
	private $letter;
	private $words = array(
					    "blech ",
					    "foo ",
					    "barf ",
					    "rech ",
					    "bar ",
					    "blech ",
					    "quo ",
					    "bloto ",
					    "oh ",
					    "caca ",
					    "blorp ",
					    "erp ",
					    "festr ",
					    "rot ",
					    "slie ",
					    "snorf ",
					    "iky ",
					    "yuky ",
					    "ooze ",
					    "ah ",
					    "bahl ",
					    "zep ",
					    "druhl ",
					    "flem ",
					    "behil ",
					    "arek ",
					    "mep ",
					    "zihr ",
					    "grit ",
					    "kona ",
					    "kini ",
					    "ichi ",
					    "tims ",
					    "ogr ",
					    "oo ",
					    "ighr ",
					    "coph ",
					    "swerr "
					);
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getName()
	{
		$name = '';
		if ($this->isIdentified)
		{
			$name = $this->name;
		}
		else
		{
			$name = $this->magicName;
		}
		return $name;
	}
	public function setName($val)
	{
		$this->name = $val;
	}
	
	public function getType()
	{
		return $this->type;
	}
	public function setType($val)
	{
		$this->type = $val;
	}
	
	public function getIsIdentified()
	{
		return $this->isIdentified;
	}
	public function setIsIdentified($val)
	{
		$this->isIdentified = $val;
	}
	
	public function getMagicName()
	{
		return $this->magicName;
	}
	public function setMagicName($val)
	{
		$this->magicName = $val;
	}
	
	public function getLetter()
	{
		return $this->letter;
	}
	public function setLetter($val)
	{
		$this->letter = $val;
	}
	
	public function getRealName()
	{
		return $this->name;
	}
	
	public static function copy($copyFrom, $copyTo)
	{
		$copyTo->setMagicName($copyFrom->getMagicName());
		$copyTo->setName($copyFrom->getRealName());
	}
	
	public function __construct()
	{
		parent::__construct();
		$this->isIdentified	= false;
		$this->id = Model_Avatar::getUuid();
		/*
		$typeCount		= count($this->mainTypes);		
		$type			= $this->mainTypes(mt_rand(0, $typeCount - 1));
		$subTypes		= $this->subTypes($type);
		$subTypeCount	= count($subTypes);
		$subType		= $subTypes(mt_rand(0, $subTypeCount - 1));
		
		$this->name		= $subType . ' ' . $type;
		*/
	}
	
	public function getMyLink()
	{
		return 'http://' . Model_Constants::APP_SERVER . '/' . Model_Constants::APP_DIR . '/';
	}
	
	public function getRandomWord()
	{
		return $this->words[mt_rand(0, count($this->words) - 1)];
	}
}
?>