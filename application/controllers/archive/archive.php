<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Archive extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
		$this->load->model('archive_model');
	}

	function is_logged_in()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');
		
		if(!isset($is_logged_in) || $is_logged_in != TRUE)
		{
			$data['failed'] = "You are not logged in. Please login";
			redirect('archive/login');
		}
	}
	
	function index()
	{
		//Get a list of all the editions;
		if($result = $this->archive_model->retrieve(100, 0))
		{

			$data['edition_menu'] = $result;
			$data['edition_title'] = 'Choose edition from menu';

			$this->template->write_view('header', 'archive/header', $data);
			$this->template->write_view('content', 'archive/index', $data);
			$this->template->write_view('sidebar', 'archive/sidebar', $data);
			$this->template->write_view('footer', 'archive/footer', $data);
			$this->template->render();
		}
	}
		
	function content($id, $page_title = NULL)
	{
	
		// Get the label for the edition
		if($label = $this->archive_model->retrieve(100, 0, $id))
		{
			$this->current_content_table = "ed_".$label->edition_label."_content";
			$data['edition_title'] = $label->edition_title;
			$this->MPTtree->set_table($this->current_content_table);
		}
		
		$this->load->model('content_model');
		
		//Get data for sidebar articles	and offers	
		$newsletter_parent = $this->content_model->get_page_title('newsletter-articles', $this->current_content_table);
		$offers_parent = $this->content_model->get_page_title('current-offers', $this->current_content_table);
				
		$data['sidebar_articles'] = $this->content_model->get_sidebar($newsletter_parent->lft,$newsletter_parent->rgt,$this->current_content_table);
		$data['sidebar_offers'] = $this->MPTtree->get_descendants($offers_parent->lft,$offers_parent->rgt,$this->current_content_table);

		//Get the menus
		$news_articles_menu = $this->content_model->get_menu('newsletter-articles', $this->current_content_table);
		$data['news_menu'] = $news_articles_menu;
		
		$footer_menu = $this->content_model->get_menu('footer-links', $this->current_content_table);
		$data['footer_menu'] = $footer_menu;
					
		if($query = $this->content_model->get_page_title(($page_title == NULL ? $news_articles_menu[0]->friendly_title : $page_title), $this->current_content_table))
		{			
			$data['current_article'] = $query->id;				
			$data['page'] = $query;
		}
		else
		{
			redirect('archive/');
		}

		if($result = $this->archive_model->retrieve(100, 0))
		{
			$data['edition_menu'] = $result;
		}
		
		$data['edition_id'] = $id;

		// get the promos
		$this->load->model('promos_model');
		$data['promos'] = $this->promos_model->display_promos(); 
		$data['link_newsletter'] = 0;		

		$this->template->write('title', $query->title);
		$this->template->write_view('header', 'archive/header', $data);
		$this->template->write_view('content', 'archive/index', $data);
		$this->template->write_view('sidebar', 'archive/sidebar', $data);
		$this->template->write_view('footer', 'archive/footer', $data);
		$this->template->render();
			
			
	}

	function current_offers($id)
	{

		// Get the label for the edition
		if($label = $this->archive_model->retrieve(100, 0, $id))
		{
			$this->current_content_table = "ed_".$label->edition_label."_content";
			$data['edition_title'] = $label->edition_title;
			$this->MPTtree->set_table($this->current_content_table);
		}

		//Get data for sidebar articles	and offers	
		$this->load->model('content_model');
		$newsletter_parent = $this->content_model->get_page_title('newsletter-articles', $this->current_content_table);
		$offers_parent = $this->content_model->get_page_title('current-offers', $this->current_content_table);
						
		$data['sidebar_articles'] = $this->content_model->get_sidebar($newsletter_parent->lft,$newsletter_parent->rgt, $this->current_content_table);
		$data['current_offers'] = $this->content_model->get_sidebar($offers_parent->lft,$offers_parent->rgt, $this->current_content_table);

		//Get the menus
		$news_articles_menu = $this->content_model->get_menu('newsletter-articles', $this->current_content_table);
		$data['news_menu'] = $news_articles_menu;
		
		$footer_menu = $this->content_model->get_menu('footer-links', $this->current_content_table);
		$data['footer_menu'] = $footer_menu;
		
		$data['current_article'] = 0;
		$data['link_currentoffers'] = 0;		

		if($result = $this->archive_model->retrieve(100, 0))
		{
			$data['edition_menu'] = $result;
		}
		
		$data['edition_id'] = $id;

		// get the promos
		$this->load->model('promos_model');
		$data['promos'] = $this->promos_model->display_promos(); 

		$this->template->write('title', 'Welcome to Openreach connected');
		$this->template->write_view('header', 'archive/header', $data);
		$this->template->write_view('content', 'archive/page-current-offers', $data);
		$this->template->write_view('sidebar', 'archive/sidebar', $data);
		$this->template->write_view('footer', 'archive/footer', $data);
		$this->template->render();

	}


}