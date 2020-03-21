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
		return [
			'animals', 
			'architecture', 
			'buildings',
			'backgrounds',
			'textures',
			'beauty',
			'fashion',
			'business',
			'finance',
			'computer',
			'communication',
			'education',
			'emotions',
			'food',
			'drink',
			'health',
			'medicalIndustry',
			'craft',
			'music',
			'nature',
			'landscapes',
			'people',
			'places',
			'monuments',
			'religion',
			'science',
			'technology',
			'sports',
			'transportation',
			'traffic',
			'travel',
			'vacation'
		];
	}

	public function getRandomCategory()
	{
	}


	public function downloadPhotos($arr)
	{
	}	


}