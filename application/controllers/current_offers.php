<?php if(!defined('BASEPATH'))exit('No direct script access allowed');

class Current_offers extends MY_Controller {

	var $success = NULL;

	function __construct()
	{
		parent::__construct();
	}
	
	function index()
	{
		//Get data for sidebar articles	and offers	
		$this->load->model('content_model');
		$newsletter_parent = $this->content_model->get_page_title('newsletter-articles');
		$offers_parent = $this->content_model->get_page_title('current-offers');
				
		$data['sidebar_articles'] = $this->content_model->get_sidebar($newsletter_parent->lft,$newsletter_parent->rgt);
		$data['current_offers'] = $this->content_model->get_sidebar($offers_parent->lft,$offers_parent->rgt);

		//Get the menus
		$news_articles_menu = $this->content_model->get_menu('newsletter-articles');
		$data['news_menu'] = $news_articles_menu;
		
		$footer_menu = $this->content_model->get_menu('footer-links');
		$data['footer_menu'] = $footer_menu;
		
		$data['current_article'] = 0;
		$data['link_currentoffers'] = 0;		

		// get the promos
		$this->load->model('promos_model');
		$data['promos'] = $this->promos_model->display_promos();
		$data['title'] = 'Welcome to Openreach connected'; 

		$this->template->write('title', 'Welcome to Openreach connected');
		$this->template->write_view('header', 'template/header', $data);
		$this->template->write_view('content', 'template/page-current-offers', $data);
		$this->template->write_view('sidebar', 'template/sidebar', $data);
		$this->template->write_view('sidebar', 'template/footer', $data);
		$this->template->render();

	}

}