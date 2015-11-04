<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Archive extends MY_Admin_Controller {

	// Setup CRUD
	function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
		$this->load->model('archive_model');
		//$this->output->enable_profiler(TRUE);
	}

	// Check that the user is always logged in
	function is_logged_in()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');

		if(!isset($is_logged_in) || $is_logged_in != TRUE)
		{
			$data['failed'] = "You are not logged in. Please login";
			redirect('admin/login');
		}
	}

	// AKA retrieve
    function index($id = NULL)
    {

   		if($result = $this->archive_model->retrieve(100, 0))
		{
			$data['editions'] = $result;
			$data['title'] = 'Available editions';
			$data['javascript'] = $this->load_js();
			$this->load->view('admin/archive/archive', $data);
		}
		else
		{
			$data['title'] = 'Current Archives';
			$data['javascript'] = $this->load_js();
			$this->load->view('admin/archive/archive', $data);			
		}					
    }

	// Also Doubles up for the edit    
    function create($id = NULL)
    {
    	if($id != NULL)
    	{
			// Load up any external Models if needed

			if($result = $this->archive_model->retrieve(NULL, NULL, $id))
			{
				// Setup all the view data
				$result->old_title = $result->edition_title;
				$data['formdata'] = $result;
				$data['javascript'] = $this->load_js();
				$data['title'] = 'Update an existing edition';
	
				$this->load->view('admin/archive/archive-edit', $data);
			}

		}
		else // We are creating a new item so load blank form
		{
			$data['title'] = 'Create a new edition';
			$data['javascript'] = $this->load_js();
			$this->load->view('admin/archive/archive-edit', $data);
		}    
    }


    function update()
    {

		$this->load->library('form_validation');

		// We need to perform a callback on the title name
		//$this->form_validation->set_rules('edition_title', 'Edition title', 'trim|required');
		
		$this->form_validation->set_rules('edition_title', 'Edition title', 'trim|required|callback_editiontitle_check');
		
		if($this->form_validation->run() == TRUE )
		{

			$this->load->model('edition_model');			

			// only if the title does'nt match the old title will we alter the database tables
			$alter_table = TRUE;
			
			if($this->input->post('edition_title') == $this->input->post('old_title'))
			{
				$alter_table = FALSE;
			}
			
			$table_label = $this->create_table_label($this->input->post('edition_title'));
			$old_table_label = $this->create_table_label($this->input->post('old_title'));	
								
			// Reset the status of all the editions
			if($this->input->post('status') == 1)
			{
				$this->archive_model->deactivate_editions();	
			}
			
			// Add post to array. Don't forget remove last comma
			$post = array(
				'edition_title' => $this->input->post('edition_title'),
				'edition_label' => $table_label,
				'status' => $this->input->post('status'),
				'date_created' => date('Y-m-d H:m:s')
			);
						
			// Check if this is a new entry
			if($this->input->post('edition_id'))
			{
				// Update entry
				if($form = $this->archive_model->update($post, $this->input->post('edition_id')))
				{					
					if($alter_table == TRUE)
					{
						$this->edition_model->update($old_table_label, $table_label);
						echo "tables altered";
					}
					$this->message->set('success','newsletter has been updated');
					redirect('admin/archive/create/'.$this->input->post('edition_id'));
				}
			}
			else // Create new entry
			{
				if($form = $this->archive_model->create($post))
				{										
					if($this->edition_model->create($post['edition_label']))
					{
						$this->message->set('success','newsletter have been saved');
						redirect('admin/archive/create/'.$form); // ID has been returned from DB
					}
				}				
			}

		}
		else // Send them back to form with validation errors
		{
			$data['formdata'] = (object)$this->input->post();
			$data['title'] = 'Update an existing edition';
			$data['javascript'] = $this->load_js();
			$this->load->view('admin/archive/archive-edit', $data);
		}	
    }

	function editiontitle_check($title)
	{
		// Create the label
		$table_label = $this->create_table_label($title);
		$edition_id = ($this->input->post("edition_id") != FALSE ? $this->input->post("edition_id") : NULL );
					
		// Run check in the database
		if($this->archive_model->check_label($table_label, $edition_id) == TRUE)
		{
			$this->form_validation->set_message('editiontitle_check', 'There is already a edition with the title '.$title);
			
			//put the old title back in the field
			if($this->input->post("edition_id") != FALSE)
			{
				$post_title = $this->input->post("old_title");
				$_POST["edition_title"] = $post_title;
			}
			
			return FALSE;
		}
		else
		{
			return TRUE;
		}				
	}	

	function create_table_label($title)
	{
		$table_label = strtolower(preg_replace('/\s+/', '', $title));			
		$table_label = preg_replace( "/[^a-z0-9 ]/i", "", $table_label);
		
		return $table_label;
	}
}
