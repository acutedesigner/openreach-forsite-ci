<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content extends MY_Controller {

	function __construct()
	{
		parent::__construct();
	}
	
	function index($page_title = NULL)
	{
	
		if($page_title == NULL)
		{
			echo "404 Error";
		}
		else
		{

			$this->load->model('content_model');

			//Get data for sidebar articles	and offers	
			$newsletter_parent = $this->content_model->get_page_title('newsletter-articles');
			$offers_parent = $this->content_model->get_page_title('current-offers');
					
			$data['sidebar_articles'] = $this->content_model->get_sidebar($newsletter_parent->lft,$newsletter_parent->rgt);
			$data['sidebar_offers'] = $this->MPTtree->get_descendants($offers_parent->lft,$offers_parent->rgt);
	
			//Get the menus
			$news_articles_menu = $this->content_model->get_menu('newsletter-articles');
			$data['news_menu'] = $news_articles_menu;
			
			$footer_menu = $this->content_model->get_menu('footer-links');
			$data['footer_menu'] = $footer_menu;
						
			if($query = $this->content_model->get_page_title($page_title))
			{			
				$data['current_article'] = $query->id;				
				$data['page'] = $query;
			}
			else
			{
				redirect('/');
			}

			// get the promos
			$this->load->model('promos_model');
			$data['promos'] = $this->promos_model->display_promos(); 
			$data['title'] = $query->title;
			$data['link_newsletter'] = 0;		

			//$this->template->write('title', );
			$this->template->write_view('header', 'template/header', $data);
			$this->template->write_view('content', 'template/index', $data);
			$this->template->write_view('sidebar', 'template/sidebar', $data);
			$this->template->write_view('footer', 'template/footer', $data);
			$this->template->render();

		}			
			
	}

}