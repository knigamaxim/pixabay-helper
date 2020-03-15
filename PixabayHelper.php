<?php

/**
 * class PixabayHelper
 */

class PixabayHelper
{

	private $key;

	const MAX_PHOTOS_IN_PAGE = 200;

	public static function init($key)
	{
		return new self($key);
	}


	function __construct($key)
	{
		$this->key = $key;
	}

	public function getCategories()
	{
	}

	public function getRandomCategory()
	{
	}


	public function downloadPhotos($arr)
	{
	}	


}