<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * File: admin/newsletters.php
 *
 * A system to manage mutiple issues of an online newsletter
 *
 * The newsletter system lives in a mptt structure.
 * Each issue of the newsletter is a new tree in the database table
 *
 *
 * @author Nigel M Peters @acute_designer
 * 
 */


class Newsletters extends MY_Admin_Controller
{

	/**
	 *
	 * Newsletter Statuses
	 * '2'	=>	'Draft',
	 * '1'	=>	'Active',
	 * '0'	=>	'De-activated',
	 * 
	 */
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('newsletters_model');
	}

	/**
	 * The initial landing page for newsletters
	 * Page loaded to view
	 */
	public function index()
	{

		if($results = $this->newsletters_model->get_all_nl())
		{
			$data['newsletters'] = $results;
		}
		else
		{
			$data['info'] = 'No newsletters available!';
		}

		$data['title'] = 'Manage Newsletters';
		$data['javascript'] = $this->load_js();
		$this->load->view('admin/newsletters/newsletters', $data);
	}

	/**
	 * Edit newsletter
	 * This will both edit and edit and create a new newsletter
	 * @param int $newsletter_id id of newsletter
	 * 
	 */
	public function edit($newsletter_id = NULL)
	{

		if($newsletter_id != NULL)
		{
			//get results from db
			$data['formdata'] = $this->newsletters_model->get_nl($newsletter_id);
			$data['title'] = 'Edit issue';
		}
		else
		{
			$data['title'] = 'Create a new issue';
		}

		$data['javascript'] = $this->load_js();
		$this->load->view('admin/newsletters/newsletters-edit', $data);

	}

	/**
	 * Update a newsletter
	 * This will both update an existing and save a new newsletter to the db
	 * All the params come from the $_POST 
	 * 
	 */
	public function update()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('title', 'Title', 'trim|required');

		if($this->form_validation->run() == TRUE )
		{
			
			$save_data = array(
				'author' => $this->session->userdata('userid'),
				'title' => $this->input->post('title'),
				'friendly_title' => url_title($this->input->post('title'), 'dash', TRUE),
				'status' => $this->input->post('status'),
				'type' => 'newsletter',
			);

			if($id = $this->_save_data($save_data))
			{
				// Load the js files
				$data['javascript'] = $this->load_js();

				// Set success Message
				$this->message->set('success','Isssue saved');

				// Redirect the page
				redirect('admin/newsletters/edit/'.$id);
			}
			else
			{
				// Error saving
				$this->message->set('error','There was a problem creating the new issue');

				$data['formdata'] = (object)$this->input->post();
				$data['javascript'] = $this->load_js();
				$data['title'] = ($this->input->post('id') ? 'Edit issue' : 'Create a new issue');

				// Load the view
				$this->load->view('admin/newsletters/newsletters-edit', $data);
			}
		}
		else
		{
			// Validation Failed
			$data['formdata'] = (object)$this->input->post();
			$data['javascript'] = $this->load_js();
			$data['title'] = ($this->input->post('id') ? 'Edit issue' : 'Create a new issue');

			// Load the view
			$this->load->view('admin/newsletters/newsletters-edit', $data);
		}
	}

	/**
	 * Delete an existing newsletter
	 * @param int $newsletter_id id of newsletter
	 */
	public function delete($newsletter_id)
	{
		// NOTE! need to ensure that this correct the whole tree on delete
	}

	/**
	 * Gets the content for the specified newsletter
	 * @param  int $newsletter_id id of the newsletter from the url
	 * @return [type]                [description]
	 */
	public function content($newsletter_id)
	{
		// Get the newsletter data	

		// Get the children of the newsletter

			// Get the grandchildren

			// Set them as a data type array
			
			
		// Load the view
		$this->load->view('admin/newsletters/content-manage', $data);
	}


	// -------------------------------------------------------------------------
	//  PRIVATE HELPER FUNCTIONS
	//
	// -------------------------------------------------------------------------

	/**
	 * _save_data Saves data to the database
	 * @param arary $save_data form postdata to be saved to the db
	 * @return int 	id of the newsletter if created/updated
	 */
	private function _save_data($save_data)
	{

		// If there is an id in the postdata
		if($this->input->post('id'))
		{
			// Add current date for last edited
			$save_data['last_edited'] = date('Y-m-d H:m:s');

			// We need to inform if the newsletter issue has not been updated
			return ($this->newsletters_model->update_nl($save_data, $this->input->post('id')) ? $this->input->post('id') : FALSE);
		}
		else
		{
		// else if not its a fresh one

			// Add current date
			$save_data['issue'] = $this->newsletters_model->count_nl();
			$save_data['date_created'] = date('Y-m-d H:m:s');
			$save_data['last_edited'] = date('Y-m-d H:m:s');
		
			// We are creating a NEW newsletter which returns the newsletter id
			return $this->newsletters_model->create_new_nl($save_data);
		}
	}
}


