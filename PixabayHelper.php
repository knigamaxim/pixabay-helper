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
		$categories = $this->getCategories();
		return $categories[rand(0, count($this->getCategories())-1)];
	}

	public function getPixAbayPhotos(array ...$params)
	{
	}

	public function createLinkedPhotosList(array $photosURLs)
	{
		if(!is_array($photosURLs) || empty($photosURLs)) return;
		$html = '';
		foreach ($photosURLs as $k => $v) {
			$html .= ++$k . '. <a href="'.$v->largeImageURL.'" target="_blank">'.$v->largeImageURL.'</a>'."<br>";
		}
		return $html;
	}	

	public function downloadPhotos($arr)
	{
	}	


}