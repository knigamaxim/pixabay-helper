<?php

/**
 * class PixabayHelper
 */

class PixabayHelper
{

	private $key;
	protected $categories;
	protected $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads'
							. DIRECTORY_SEPARATOR;
	const MAX_PHOTOS_IN_PAGE = 200;

	public static function init($key)
	{
		return new self($key);
	}


	function __construct($key)
	{
		$this->key = $key;
		$this->categories = $this->getCategories();
	}

	protected function getCategories()
	{
		return file(__DIR__.'/_categories', FILE_SKIP_EMPTY_LINES);
	}

	protected function getRandomCategory()
	{
		return $this->categories[rand(0, count($this->categories - 1)];
	}


	public function getPixabayPhotosURLs(array ...$params)
	{
		$res = [];
		$lastChunk = false;
		if(empty($params)) $params = [[]];
		foreach ($params as $k => $v) {
			$cnt = 1;
			$v['query'] = $v['query'] ?? $this->getRandomCategory();
			$v['orientation'] = $v['orientation'] ?? 'horizontal';
			$v['numPhotos'] = $v['numPhotos'] ?? self::MAX_PHOTOS_IN_PAGE;
			$numPages = 1;
			if($v['numPhotos'] <= self::MAX_PHOTOS_IN_PAGE) --$numPages;		
			if($v['numPhotos'] > self::MAX_PHOTOS_IN_PAGE) {
				$numPages = floor($v['numPhotos'] / self::MAX_PHOTOS_IN_PAGE);
				$lastChunk = $v['numPhotos'] - (self::MAX_PHOTOS_IN_PAGE * $numPages);
				if($v['numPhotos'] % self::MAX_PHOTOS_IN_PAGE == 0) --$numPages;
				$v['numPhotos'] = self::MAX_PHOTOS_IN_PAGE;
			}
			while ($cnt <= $numPages+1) {
				if($lastChunk && $cnt == $numPages+1) $v['numPhotos'] = $lastChunk;
				$query = 'https://pixabay.com/api/?key='.$this->key.'&q='.$v['query'].'&orientation='.$v['orientation'].'&image_type=photo&per_page='.
					$v['numPhotos'].'&page='.($cnt);
				$response = @file_get_contents($query);
				foreach($http_response_header as $param){
				    if(preg_match("~HTTP/[0-9].[0-9] 400 Bad Request~", $param)){
				        $response = false; 
				        break;
				    } 
				}			
				if(!$response) break;
				$res = array_merge($res, json_decode($response)->hits);
				$cnt++;	
			}

		}
		return $res;
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

	public function loadPhotosToDirByLinks($arr)
	{
		$downloadedFilesArr = [];
		if(!is_array($arr) || empty($arr)) return;
		foreach ($arr as $k => $v) {
			$img = file_get_contents($v->largeImageURL);
			$file_name = str_replace('https://pixabay.com/get/', '', $v->largeImageURL);
			$path = $this->uploadDir;
			$file = $path.$file_name;
			if(!file_exists($path)) mkdir($path, 0775);
			if(file_put_contents($file, $img)) $downloadedFilesArr[] = $file_name;
		}
		return $downloadedFilesArr;
	}		


}