<?php
App::uses('AppController', 'Controller');
/**
 * Gallery Controller
 *
 * @property Gallery $Gallery
 */
class GalleryController extends AppController {
	
	/**
	 * include the components
	 * @var array
	 */
	public $components = array('Flickr');

	/**
	 * auto-loading model is not required
	 * @var array
	 */
	public $uses = null;

	/**
	 * Helpers
	 *
	 * @var array
	 */
	public $helpers = array('HTML');
	
	/**
	 * show the index page
	 * @param int $page_num
	 * @return void
	 */
	
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
	
	
	
	public function index($page_num=null){
		$this->Flickr->connectFlickr($this->_api_key);
		$photos = $this->Flickr->findPhotos($this->username, $page_num, 10);
		
		//generate pagination
		$total_pages = $photos['photos']['pages'];
		$current = $photos['photos']['page'];
		$this->set('pagination', $this->pagination($total_pages, $current));
		
		$this->set('photos', $photos['photos']['photo']);
		$this->set('page', $current);
		$this->set('pages', $total_pages);
		$this->set('total', $photos['photos']['total']);
	}
	
	/**
	 * generate the pagination
	 * @param int $all_pages
	 * @param int $current
	 * @param int $page_num_allowed
	 * @return array
	 */
	public function pagination($all_pages, $current, $page_num_allowed=2){
		$link = '/'.$this->params->params['controller'].'/'.$this->params->params['action'].'/';
		
		//if there are too many pages to show, only show the number of the pages that we defined and hide the rest of them
		$this_page_section_start = (ceil($current/$page_num_allowed)*$page_num_allowed) - $page_num_allowed + 1;
		$this_page_section_end = (ceil($current/$page_num_allowed)*$page_num_allowed);
		
		//paginate
		$page_navg = array();
		if (($current-1) > 0) {
			$page_navg[] = '<a class="prev" href="'.$link.($current-1).'"><span>&laquo; Prev</span> </a>';
		}
		if($this_page_section_start > $page_num_allowed){
			$page_navg[] = "<span>...</span>";
		}
		for($i=$this_page_section_start; $i<=$this_page_section_end; $i++){
			if ($i<=$all_pages){
				if ($current==$i)
					$page_navg[] = "<strong>".$i."</strong>";
				else
					$page_navg[] = '<a href="'.$link.$i.'">'.$i.'</a>';
			}
		}
		if($this_page_section_end < $all_pages){
			$page_navg[] = "<span>...</span>";
		}
		if (($current+1) <= $all_pages){
			$page_navg[] = '<a class="next" href="'.$link.($current+1).'"> <span>Next &raquo;</span></a>';
		}
		
		return $page_navg;
	}	
}
?>