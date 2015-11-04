<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {

	function __construct()
	{
		parent::__construct();
	}

	public function index()
	{

		$this->load->model('content_model');

		//Get data for sidebar articles	and offers	
		$newsletter_parent = $this->content_model->get_page_title('newsletter-articles', $this->current_content_table);
		$offers_parent = $this->content_model->get_page_title('current-offers', $this->current_content_table);
				
		$data['sidebar_articles'] = $this->content_model->get_sidebar($newsletter_parent->lft,$newsletter_parent->rgt, $this->current_content_table);
		$data['sidebar_offers'] = $this->MPTtree->get_descendants($offers_parent->lft,$offers_parent->rgt);

		//Get the menus
		$news_articles_menu = $this->content_model->get_menu('newsletter-articles', $this->current_content_table);

		$data['news_menu'] = $news_articles_menu;
		
		$footer_menu = $this->content_model->get_menu('footer-links', $this->current_content_table);
		$data['footer_menu'] = $footer_menu;
		
		// Get a page from the news_menu array
		if($query = $this->content_model->get_page_title($news_articles_menu[0]->friendly_title, $this->current_content_table))
		{			
			$data['current_article'] = $query->id;				
			$data['page'] = $query;
		}

		// get the promos
		$this->load->model('promos_model');
		$data['promos'] = $this->promos_model->display_promos(); 
		$data['title'] = 'Welcome to Openreach ForSite'; 
		$data['link_newsletter'] = 0;		
				
		$this->template->write('title', 'Welcome to Openreach ForSite');
		$this->template->write_view('header', 'template/header', $data);
		$this->template->write_view('content', 'template/index', $data);
		$this->template->write_view('sidebar', 'template/sidebar', $data);
		$this->template->write_view('footer', 'template/footer', $data);
		$this->template->render();

	}

	function content($title)
	{
		$data['pagetitle'] = $title;

		$this->template->write('title', 'Welcome to Openreach ForSite');
		$this->template->write_view('content', 'template/index', $data);
		$this->template->write_view('sidebar', 'template/sidebar', $data);
		$this->template->write_view('footer', 'template/footer', $data);
		$this->template->render();
	}

	function gallery()
	{

		$this->load->view('template/gallery');

	}

	function slideshowimages($number)
	{
		$this->load->model('media_model');

		if($query = $this->media_model->get_cropped_images($number, 0, 'images'))
		{
			return $query;
		}
	}

}