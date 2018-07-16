<?php defined('SYSPATH') or die('No direct script access.');

class Model_Location extends Model_Placeable
{
	const MAP_DIR		= 'type';
	
	private $id;
	private $width;
	private $height;
	private $allImages;
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getWidth()
	{
		return $this->width;
	}
	public function setWidth($val)
	{
		$this->width = $val;
	}
	
	public function getHeight()
	{
		return $this->height;
	}
	public function setHeight($val)
	{
		$this->height = $val;
	}
	
	public function getAllImages()
	{
		return $this->allImages;
	}
	public function setAllImages($val)
	{
		$this->allImages = $val;
	}

	public function __construct($position, $image = null)
	{
		parent::__construct();
		
		$this->id		= Model_Avatar::getUuid();
		$this->allImages= array();
		
		$this->setPosition($position);
		if ($image)
		{
			$this->setImage($image);
		}
		else
		{
			$this->setImage($this->getRandomImage($position));	
		}
	}

	// Gets a random room image for that position (upper left, middle, etc)
	public function getRandomImage($position)
	{
		$min	= 0;
		$offset	= Model_Location::getImageType($position);
		$imagesArray = $this->getAllImages();
		$images	= $imagesArray[$offset];
		$max	= count($images) - 1;
		$image	= $images[mt_rand($min, $max)];
		return $image;
	}

	public function getWebImage()
	{
		if ($this->getShowOnMap())
		{
			$path = self::MAP_DIR . self::getImageType($this->getPosition()) . '/';
			return $path . $this->getImage();
		}
		else
		{
			return 'black.gif';
		}
	}
	
	public static function getImageType($position)
	{
		//print('<b>** '.__CLASS__.' -> '.__FUNCTION__.'</b><br>');
		$imageType = 0;
		switch ($position)
		{
			case 0:
				$imageType = 0;
				break;
			case 1:
			case 4:
			case 7:
				$imageType = 1;
				break;
			case 2:
				$imageType = 2;
				break;
			case 3:
				$imageType = 4;
				break;
			case 5:
				$imageType = 3;
				break;
			case 6:
				$imageType = 5;
				break;
			case 8:
				$imageType = 6;
				break;
		}
		//print('Position: '.$position.', imageType: '.$imageType.'<br>');
		return $imageType;
	}
}
?>