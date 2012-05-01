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
	public function index($page_num=null){
		$photos = $this->findPhotos($page_num, 10);
		
		//generate pagination
		$total_pages = $photos['photos']['pages'];
		$current = $photos['photos']['page'];
		$this->set('pagination', $this->pagination($total_pages, $current));
		
		//generate the image links
		for($i=0; $i<sizeof($photos['photos']['photo']); $i++){
			$photos['photos']['photo'][$i]['url'] = $this->Flickr->flickr->buildPhotoURL($photos['photos']['photo'][$i], "small");
			$photos['photos']['photo'][$i]['big_url'] = $this->Flickr->flickr->buildPhotoURL($photos['photos']['photo'][$i], "large");
		}
		$this->set('photos', $photos['photos']['photo']);
		$this->set('page', $current);
		$this->set('pages', $total_pages);
		$this->set('total', $photos['photos']['total']);
	}
	
	/**
	 * connect to the flickr and return the photos in an array
	 * @param int $current_page
	 * @param int $per_page the number of the images showing up per page
	 * @return array
	 */
	public function findPhotos($current_page=null, $per_page=10){
		
		//grab the user id
		$user = $this->Flickr->flickr->people_findByUsername($this->Flickr->username);
		$nsid = $user['id'];
		
		//indicates the current page number
		$page = (isset($current_page))?$current_page:1;
		
		//grab the photos
		$photos = $this->Flickr->flickr->people_getPublicPhotos($nsid, NULL, NULL, $per_page, $page);
		
		return $photos;
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
		$ajax_link = '/'.$this->params->params['controller'].'/view/';
		
		//if there are too many pages to show, only show the number of the pages that we defined and hide the rest of them
		$this_page_section_start = (ceil($current/$page_num_allowed)*$page_num_allowed) - $page_num_allowed + 1;
		$this_page_section_end = (ceil($current/$page_num_allowed)*$page_num_allowed);
		
		//paginate
		$page_navg = array();
		if (($current-1) > 0) {
			$page_navg[] = '<a class="prev" href="'.$link.($current-1).'" rel="'.$ajax_link.($current-1).'"><span>&laquo; Prev</span> </a>';
		}
		if($this_page_section_start > $page_num_allowed){
			$page_navg[] = "<span>...</span>";
		}
		for($i=$this_page_section_start; $i<=$this_page_section_end; $i++){
			if ($i<=$all_pages){
				if ($current==$i)
					$page_navg[] = "<strong>".$i."</strong>";
				else
					$page_navg[] = '<a href="'.$link.$i.'" rel="'.$ajax_link.$i.'">'.$i.'</a>';
			}
		}
		if($this_page_section_end < $all_pages){
			$page_navg[] = "<span>...</span>";
		}
		if (($current+1) <= $all_pages){
			$page_navg[] = '<a class="next" href="'.$link.($current+1).'" rel="'.$ajax_link.($current+1).'"> <span>Next &raquo;</span></a>';
		}
		
		return $page_navg;
	}
}
?>