<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Promos extends CI_Controller {

	// Setup CRUD

    function __construct()
    {
		parent::__construct();
		$this->is_logged_in();
		$this->load->model('promos_model');
// 		$this->output->enable_profiler();		
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
	   		if($result = $this->promos_model->retrieve(NULL, NULL, $id))
			{
				$data['promos'] = $result;
				$data['title'] = 'Promotional offers';
				$this->load->view('admin/promos/promos', $data);
			}		
		}
		else // We're gonna load up multiple entries
		{
			// Load up the pagination options if needed
			$limit = 100;
			$start = 0;
		
			// Load multiple entries
	   		if($results = $this->promos_model->retrieve($limit, $start))
			{
				$data['promos'] = $results;
				$data['title'] = 'Promotional offers';
				$this->load->view('admin/promos/promos', $data);
			}
			else
			{
				$data['title'] = 'Promotional offers';
				$this->load->view('admin/promos/promos', $data);				
			}					
		}

    }

	// Also Doubles up for the edit    
    function create($id = NULL)
    {

		$data['javascript'] = $this->load_js();

		// Get the promo gallery list
		$this->load->model('gallery_model');
		
		if($promo_offers = $this->gallery_model->get_gallery_images(0,100,NULL,'promotional-offers', NULL, $this->current_lightbox_table))
		{
			$images_array = array('' => 'Select One...');
			
			foreach($promo_offers as $image)
			{
				$images_array[$image->image_id] = $image->caption;
			}
									
			// Set the promo images
			$data['promo_offers'] = $images_array;
		}

    	if($id != NULL)
    	{
			// Load up any external Models if needed
	   		if($results = $this->promos_model->retrieve(NULL, NULL, $id))
			{
				// As only one result is returned it needs to be singled out.
				$formdata = array(
					'id' => $results->id,
					'promo' => $results->promo,
					'promo_title' => $results->promo_title,
					'promo_link' => $results->promo_link
				);
												
				// Setup all the view data
				$data['formdata'] = (object)$formdata;
				$data['title'] = 'Edit promotional offers';
	
				$this->load->view('admin/promos/promos-edit', $data);
			}

		}
		else // We are creating a new item so load blank form
		{
			$data['title'] = 'Setup promotional offers';
			$this->load->view('admin/promos/promos-edit', $data);
		}    
    }
    
    function update()
    {

		$this->load->library('form_validation');

		$this->form_validation->set_rules('promo', 'Offer Image', 'required');
		$this->form_validation->set_rules('promo_title', 'Promo Title', 'required|max_length[49]');

		if($this->form_validation->run() == TRUE )
		{
			// Add post to array. Don't forget remove last comma
			$post = array(
				'promo' => $this->input->post('promo'),
				'promo_title' => $this->input->post('promo_title'),
				'promo_link' => $this->input->post('promo_link')
			);
			
			// Check if this is a new entry
			if($this->input->post('id'))
			{
				// Update entry
				if($form = $this->promos_model->update($post, $this->input->post('id')))
				{
					$this->message->set('success','The promos have been updated');
					redirect('admin/promos/create/'.$this->input->post('id'));
				}
			}
			else // Create new entry
			{
				if($form = $this->promos_model->create($post))
				{
					$this->message->set('success','New Promos has been saved');
					redirect('admin/promos/create/'.$form); // ID has been returned from DB
				}				
			}
		}
		else // Send them back to form with validation errors
		{
			// Get the promo gallery list
			$this->load->model('gallery_model');
			
			if($promo_offers = $this->gallery_model->get_gallery_images(0,100,NULL,'promotional-offers', NULL, $this->current_lightbox_table))
			{
				$images_array = array('' => 'Select One...');
				
				foreach($promo_offers as $image)
				{
					$images_array[$image->image_id] = $image->caption;
				}
										
				// Set the promo images
				$data['promo_offers'] = $images_array;
			}

			$data['javascript'] = $this->load_js();
			$data['formdata'] = (object)$this->input->post();
			$data['title'] = 'Setup promotional offers';
			$this->load->view('admin/promos/promos-edit', $data);
		}	
    }

	function delete($id = NULL)
	{
		$this->load->model('promos_model');

		if($post = $this->input->post('checkbox'))
		{
			//delete multiple pages
			if($form = $this->promos_model->delete_promos($post))
			{
				$this->message->set('success','promos has been deleted');
				redirect('admin/promos');
			}
			// Delete Single
			elseif($form = $this->promos_model->delete($id))
			{
				$this->message->set('success','Tag has been deleted');
				redirect('admin/promos/');
			}
		}
		else
		{
			// Go back to page with error message
			$this->message->set('error','No data was selected');
			redirect('admin/promos');
		}	
		
	}

}