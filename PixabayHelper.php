<?php

/**
 * class PixabayHelper
 */


class PixabayHelper
{
	private $key;
	protected $categories;
	const MAX_PHOTOS_IN_PAGE = 200;
	const DS = DIRECTORY_SEPARATOR;
	const ROOT = __DIR__ . DS;
	const API_URL = 'https://pixabay.com/api/';
	protected $uploadsDir = ROOT . 'uploads' . DS;
		
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
		$rest = false;
		if(empty($params)) $params = [[]];
		foreach ($params as $k => $v) {
			$i = 1;
			$v['query'] = $v['query'] ?? $this->getRandomCategory();
			$v['orientation'] = $v['orientation'] ?? 'horizontal';
			$v['cnt'] = $v['cnt'] ?? self::MAX_PHOTOS_IN_PAGE;
			$numPages = 1;
			if($v['cnt'] <= self::MAX_PHOTOS_IN_PAGE) --$numPages;		
			if($v['cnt'] > self::MAX_PHOTOS_IN_PAGE) {
				$numPages = floor($v['cnt'] / self::MAX_PHOTOS_IN_PAGE);
				$rest = $v['cnt'] - (self::MAX_PHOTOS_IN_PAGE * $numPages);
				if($v['cnt'] % self::MAX_PHOTOS_IN_PAGE == 0) --$numPages;
				$v['cnt'] = self::MAX_PHOTOS_IN_PAGE;
			}
			while ($i <= $numPages+1) {
				if($rest && $i == $numPages+1) $v['cnt'] = $rest;
				$query =  API_URL . '?key='.$this->key.'&q='.$v['query'].'&orientation='.$v['orientation'].'&image_type=photo&per_page='.
					$v['cnt'].'&page='.($i);
				$response = @file_get_contents($query);
				foreach($http_response_header as $param){
				    if(preg_match("~HTTP/[0-9].[0-9] 400 Bad Request~", $param)){
				        $response = false; 
				        break;
				    } 
				}			
				if(!$response) break;
				$res = array_merge($res, json_decode($response)->hits);
				$i++;	
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
			try{
				if(!file_exists($path)) mkdir($path, 0775);
			} catch(\Exception $e) {
				if(!file_put_contents($file, $img)) throw new \Exception("Failed to write file \"$file\": " . $e->getMessage());
				$downloadedFilesArr[] = $file_name;
			}
		}
		return $downloadedFilesArr;
	}		


}
