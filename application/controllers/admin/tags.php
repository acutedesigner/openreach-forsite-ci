<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tags extends CI_Controller {

	// Setup CRUD

    function __construct()
    {
		parent::__construct();
		$this->is_logged_in();
		$this->load->model('tags_model');
		// To show messages make sure this is installed
		// http://getsparks.org/packages/message/versions/HEAD/show
    }

	function is_logged_in()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');

		if(!isset($is_logged_in) || $is_logged_in != TRUE)
		{
			$data['failed'] = "You are not logged in. Please login";
			redirect('admin/login');
		}
	}


	function load_js()
	{
		// Add Javascript files
		$this->jload->add('jquery-1.8.3.min.js');
		$this->jload->add('jquery-ui-1.8.2.custom.min.js');
		$this->jload->add('menu.js');
		$this->jload->add('redactor/redactor.js');
		$this->jload->add('redactor/plugins/table.js');
		$this->jload->add('redactor/plugins/buttons.js');
		$this->jload->add('redactor/plugins/imagemanager.js');
		$this->jload->add('redactor/plugins/filemanager.js');

		return $this->jload->generate();		
	}

	// AKA retrieve
    function index($id = NULL)
    {

		$data['javascript'] = $this->load_js();

		if($id != NULL)
		{
			// Load single entry
			//function retrieve($limit = NULL, $start = NULL, $id = NULL)
	   		if($result = $this->tags_model->retrieve(NULL, NULL, $id))
			{
				$data['result'] = $result;
				$data['title'] = 'Article Tags';
				$this->load->view('index', $data);
			}		
		}
		else // We're gonna load up multiple entries
		{
			// Load up the pagination options if needed
			$limit = 100;
			$start = 0;
		
			// Load multiple entries
	   		if($result = $this->tags_model->retrieve($limit, $start))
			{
				$data['tags'] = $result;
				$data['title'] = 'Article Tags';
				$this->load->view('admin/tags/tags', $data);
			}
			else
			{
				$data['title'] = 'Article Tags';
				$this->load->view('admin/tags/tags', $data);				
			}					
		}
    }

	// Also Doubles up for the edit    
    function create($id = NULL)
    {

		$data['javascript'] = $this->load_js();

    	if($id != NULL)
    	{

	   		if($results = $this->tags_model->retrieve(NULL, NULL, $id))
			{
												
				// Setup all the view data
				$data['formdata'] = (object)$results;
				$data['title'] = 'Edit Tag';
	
				$this->load->view('admin/tags/tags-edit', $data);
			}

		}
		else // We are creating a new item so load blank form
		{
			$data['title'] = 'Create a new Tag';
			$this->load->view('admin/tags/tags-edit', $data);
		}    
    }
    
    function update()
    {

		$this->load->library('form_validation');

		$this->form_validation->set_rules('tag_name', 'Tag Name', 'trim|required|max_length[35]');
		
		if($this->form_validation->run() == TRUE )
		{
			// Add post to array. Don't forget remove last comma
			$post = array(
				'tag_name' => $this->input->post('tag_name'),
				'tag_slug' => url_title($this->input->post('tag_name'), 'dash', TRUE)
			);
			
			// Check if this is a new entry
			if($this->input->post('id'))
			{
				// Update entry
				if($form = $this->tags_model->update($post, $this->input->post('id')))
				{
					$this->message->set('success','Tag has been updated');
					redirect('admin/tags/create/'.$this->input->post('id'));
				}
			}
			else // Create new entry
			{
				if($form = $this->tags_model->create($post))
				{
					$this->message->set('success','New Tag has been saved');
					redirect('admin/tags/create/'.$form); // ID has been returned from DB
				}				
			}
		}
		else // Send them back to form with validation errors
		{
			$data['formdata'] = (object)$this->input->post();
			$data['title'] = 'Edit Tag';
			$this->load->view('admin/tags/create', $data);
		}	
    }

	function delete($id = NULL)
	{
		$this->load->model('tags_model');

		if($post = $this->input->post('checkbox'))
		{
			//delete multiple pages
			if($form = $this->tags_model->delete_tags($post))
			{
				$this->message->set('success','Tags has been deleted');
				redirect('admin/tags');
			}
			// Delete Single
			elseif($form = $this->tags_model->delete($id))
			{
				$this->message->set('success','Tag has been deleted');
				redirect('admin/tags/');
			}
		}
		else
		{
			// Go back to page with error message
			$this->message->set('error','No data was selected');
			redirect('admin/tags');
		}	
		
	}

}