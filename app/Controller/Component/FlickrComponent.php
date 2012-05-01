<?php
define('FLICKR_CACHE_DIR',CACHE . 'flickr/');
App::import('Vendor', 'phpflickr/phpFlickr');

class FlickrComponent extends Object
{
	/**
	 * Flickr API Key
	 * @var string
	 */
	public $_api_key='4adb53d4d20cbc08011dd91438908f0d';
	
	/**
	 * Flickr username
	 * @var string
	 */
	public $username="Chen 31";

	/**
	 * Flickr Object
	 * @var object
	 */
	public $flickr;
	

	public function startup(&$controller){
		//FlickrComponent instance of controller is replaced by a phpFlickr instance
		$this->flickr =& new phpFlickr($this->_api_key);

		//create the cache folder for phpFlickr to cache
		if (!is_dir(FLICKR_CACHE_DIR)){
			mkdir(FLICKR_CACHE_DIR,0777);
		}
		//phpFlickr uses caching to speed the process up
		$this->flickr->enableCache('fs', 'cache');
	}

	public function initialize(&$controller, $settings = array()){
		// saving the controller reference for later use
		$this->controller =& $controller;
	}
	
	//called after Controller::beforeRender()
	function beforeRender(&$controller){
	}
	
	//called after Controller::render()
	function shutdown(&$controller){
	}
	
	//called before Controller::redirect()
	function beforeRedirect(&$controller, $url, $status=null, $exit=true){
	}
	
	function redirectSomewhere($value){
		// utilizing a controller method
		$this->controller->redirect($value);
	}
}
?>