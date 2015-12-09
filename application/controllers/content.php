<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('content_model');
		$this->load->model('newsletters_model');
	}
	
	function index($issue = NULL)
	{
	
		if($issue == NULL)
		{

			$parent = $this->newsletters_model->get_latest_nl();
			$data['issue'] = $parent->issue;

			$children = $this->newsletters_model->get_children($parent->id);

			// Extract the sections Articles | Current Offers
			foreach ($children as $child) {

				$clean_title = str_replace('-', '_', $child['friendly_title']);

				// For sidebar menus
				$data['sidebar_'.$clean_title] = $this->newsletters_model->get_children($child['id']);

				// Full array
				$data[$clean_title.'_array'] = $child;							

			}

			// get the first article / node from the articles
			$first_article = $this->newsletters_model->get_first_child($data['articles_array']);
			$data['page'] = $first_article;		
			$data['title'] = $first_article->title;
			$data['current_article'] = $first_article->id;				

			//Get the menus
			$data['news_menu'] = $data['sidebar_articles'];

		}
		else
		{

			$issue = explode("-", $issue);
			$issue_number = $issue[1];

			//get node where issue = $issue_number
			$parent = $this->newsletters_model->get_issue($issue_number);
			$data['issue'] = $parent['issue'];

			$children = $this->newsletters_model->get_children($parent['id']);

			// Extract the sections Articles | Current Offers
			foreach ($children as $child) {

				$clean_title = str_replace('-', '_', $child['friendly_title']);

				// For sidebar menus
				$data['sidebar_'.$clean_title] = (object)$this->newsletters_model->get_children($child['id']);

				// Full array
				$data[$clean_title.'_array'] = $child;							

			}

			//Get the menus
			$data['news_menu'] = $data['sidebar_articles'];

			if($query = $this->content_model->get_page_title($this->uri->segment(3)))
			{			
				$data['current_article'] = $query->id;				
				$data['page'] = $query;
			}
			else
			{
				redirect('/');
			}

		}			

		// get the promos
		$this->load->model('promos_model');
		$data['promos'] = $this->promos_model->display_promos(); 
		$data['link_newsletter'] = 0;		

		$this->template->write_view('header', 'template/header', $data);
		$this->template->write_view('content', 'template/index', $data);
		$this->template->write_view('sidebar', 'template/sidebar', $data);
		$this->template->write_view('footer', 'template/footer', $data);
		$this->template->render();
			
	}

}