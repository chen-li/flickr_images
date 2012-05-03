<?php
define('FLICKR_CACHE_DIR',CACHE . 'flickr/');
App::import('Vendor', 'phpflickr/phpFlickr');

class FlickrComponent extends Component {
	
	public function __construct(ComponentCollection $collection, $settings = array()) {
		$settings = array_merge($this->settings, (array)$settings);
		$this->Controller = $collection->getController();
		parent::__construct($collection, $settings);
	}

	/**
	 * connect to Flickr
	 * @param string $_api_key
	 * @return void
	 */
	public function connectFlickr($_api_key){
		//FlickrComponent instance of controller is replaced by a phpFlickr instance
		$this->Controller =& new phpFlickr($_api_key);

		//create the cache folder for phpFlickr to cache
		if (!is_dir(FLICKR_CACHE_DIR)){
			mkdir(FLICKR_CACHE_DIR,0777);
		}
		//phpFlickr uses caching to speed the process up
		$this->Controller->enableCache('fs', 'cache');
	}
	
	/**
	 * connect to the flickr and return the photos in an array
	 * @param int $current_page
	 * @param int $per_page the number of the images showing up per page
	 * @return array
	 */
	public function findPhotos($username, $current_page=null, $per_page=10){
		//grab the user id
		$user = $this->Controller->people_findByUsername($username);
		$nsid = $user['id'];
		
		//indicates the current page number
		$page = (isset($current_page))?$current_page:1;
		
		//grab the photos
		$photos = $this->Controller->people_getPublicPhotos($nsid, NULL, NULL, $per_page, $page);
		
		//generate the image links
		for($i=0; $i<sizeof($photos['photos']['photo']); $i++){
			$photos['photos']['photo'][$i]['url'] = $this->Controller->buildPhotoURL($photos['photos']['photo'][$i], "small");
			$photos['photos']['photo'][$i]['big_url'] = $this->Controller->buildPhotoURL($photos['photos']['photo'][$i], "large");
		}
		
		return $photos;
	}

}
?>