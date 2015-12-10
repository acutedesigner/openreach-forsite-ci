<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Preview extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
		$this->load->model('preview_model');
		$this->load->model('content_model');
		$this->load->model('newsletters_model');
	}

	function is_logged_in()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');
		
		if(!isset($is_logged_in) || $is_logged_in != TRUE)
		{
			$data['failed'] = "You are not logged in. Please login";
			redirect('preview/login');
		}
	}
	
	function index($issue = NULL)
	{
	
		$data = NULL;

		if($issue == NULL)
		{
			//Get a list of all the editions;
			if($result = $this->preview_model->get_drafts())
			{
				$data['edition_menu'] = $result;
				$data['edition_title'] = 'Choose issue from menu';
			}

		}
		else
		{

			$issue = explode("-", $issue);
			$issue_number = $issue[1];

			$data['edition_menu'] = $this->preview_model->get_drafts();

			//get node where issue = $issue_number
			$parent = $this->preview_model->get_issue($issue_number);
			$data['issue'] = $parent['issue'];
			$data['edition_title'] = $parent['title'];

			$children = $this->newsletters_model->get_children($parent['id']);

			// Extract the sections Articles | Current Offers
			foreach ($children as $child) {

				$clean_title = str_replace('-', '_', $child['friendly_title']);

				// For sidebar menus
				$data['sidebar_'.$clean_title] = (object)$this->newsletters_model->get_children($child['id']);

				// Full array
				$data[$clean_title.'_array'] = $child;							

			}

			//Get the main menu - current issue

			$parent = $this->newsletters_model->get_latest_nl();

			if($this->uri->segment(3))
			{			
				$query = $this->preview_model->get_page($this->uri->segment(3));
				$data['current_article'] = $query->id;				
				$data['title'] = $query->title;
				$data['page'] = $query;
			}
			else
			{
				// Get first child
				$query = (object)$this->preview_model->get_first_child($issue_number);
				$data['current_article'] = $query->id;				
				$data['title'] = $query->title;
				$data['page'] = $query;
			}

		}			

		$this->template->write_view('header', 'template/preview-header', $data);
		$this->template->write_view('content', 'template/index', $data);
		$this->template->write_view('sidebar', 'template/preview-sidebar', $data);
		$this->template->write_view('footer', 'template/footer', $data);
		$this->template->render();
			
	}

}