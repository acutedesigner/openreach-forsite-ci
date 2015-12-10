<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gallery extends MY_Admin_Controller{

	// Check that the user is always logged in
	function __construct()
	{
		parent::__construct();
		$this->jload->add('menu.js');
		$this->jload->add('colorbox/jquery.colorbox-min.js');
		$this->jload->add('jConfirmAction/jconfirmaction.jquery.js');
		$this->jload->add('qtip/jquery.qtip.min.js');
	}

	function index()
	{
		$this->load->model('gallery_model');

		$rows = $this->db->query('SELECT * FROM galleries');

		// load pagination class
		$this->load->library('pagination');
		$config['base_url'] = base_url().'index.php/admin/galleries/';
		$config['total_rows'] = $rows->num_rows(); //$this->db->count_all('content');
		$config['per_page'] = '20';
		$config['full_tag_open'] = '<div id="pagination-links">';
		$config['full_tag_close'] = '</div>';
		$config['cur_tag_open'] = '<li class="current">';
		$config['cur_tag_close'] = '</li>';
		$config['uri_segment'] = 2;

		$this->pagination->initialize($config);

		if($query = $this->gallery_model->get_galleries($config['per_page'], $this->uri->segment(2)))
		{
			//Load the main gallery list
			$data['pages'] = $query;
			$data['title'] = 'Manage image galleries: '.$this->edition_title;
			$data['javascript'] = $this->jload->generate();
			$data[$this->uri->segment(2)] = 'style="display: block;"';
			$this->load->view('admin/gallery/gallery', $data);
		}
		else
		{
			//Load the main gallery list
			$data['title'] = 'Manage image galleries';
			$data['javascript'] = $this->jload->generate();
			$data['info'] = "No galleries are stored";
			$data[$this->uri->segment(2)] = 'style="display: block;"';
			$this->load->view('admin/gallery/gallery', $data);
		}

	}

	function create()
	{
		// Setup the variables
		$postdata = array(
			'id' => NULL,
			'name' => NULL,
			'date_created' => date("Y-m-d"),
			'locked' => 0
		);

		// Add them to the view data
		$data = array('formdata' => (object)$postdata);

		$data['title'] = 'Create a image gallery';
		$data['javascript'] = $this->jload->generate();
		$data[$this->uri->segment(2)] = 'style="display: block;"';
		$this->load->view('admin/gallery/gallery-edit', $data);
	}

	function edit($id)
	{
		$this->load->model('gallery_model');

		if($query = $this->gallery_model->get_gallery($id))
		{
			// Add them to the view data
			$data['formdata'] = $query;

			//echo '<pre>';print_r($query);echo '</pre>';

			$data['title'] = 'Edit Image Gallery';
			$data['javascript'] = $this->jload->generate();
			$data[$this->uri->segment(2)] = 'style="display: block;"';
			$this->load->view('admin/gallery/gallery-edit', $data);
		}
	}

	function save()
	{
		// Validation
		$this->load->library('form_validation');

		$this->form_validation->set_rules('id', 'id', 'trim|integer');
		$this->form_validation->set_rules('name', 'Gallery Name', 'trim|required');
		$this->form_validation->set_rules('date_created', 'Post Date', 'trim');
		$this->form_validation->set_rules('locked', 'Left', 'trim|required');

		if($this->form_validation->run() == TRUE )
		{

			// Saving gallery
			$this->load->model('gallery_model');

			// Add post to array
			$post = array(
				'name' => $this->input->post('name'),
				'date_created' => $this->input->post('date_created'),
				'locked' => $this->input->post('locked'),
				'friendly_name' => url_title($this->input->post('name'), 'dash', TRUE)
			);

			if($this->input->post('id'))
			{
				// Update gallery
				if($query = $this->gallery_model->update_gallery($post, $this->input->post('id')))
				{
					// Confirm save and reload page
					// Add the javascript files
					$data['javascript'] = $this->jload->generate();

					// Set success Message
					$this->message->set('success','Your gallery has been updated');

					// Redirect the page
					redirect('admin/gallery/edit/'.$this->input->post('id'));
				}
			}
			else
			{
				// Insert Gallery
				if($query = $this->gallery_model->insert_gallery($post))
				{
					// Confirm save and reload page
					// Add the javascript files
					$data['javascript'] = $this->jload->generate();

					// From the MPTTree
					$query = $this->db->query('SELECT LAST_INSERT_ID()');
					$row = $query->row_array();
					$form = $row['LAST_INSERT_ID()'];

					// Set title Message
					$this->message->set('success','Your gallery has been saved');

					// Redirect the page
					redirect('admin/gallery/edit/'.$form);
				}
			}
		}
		else
		{
			$postdata = $this->input->post();
			//echo '<pre>';print_r($this->input->post());echo '</pre>';

			$data['formdata'] = (object)$postdata;

			$data['title'] = 'Create a image gallery';
			$data['javascript'] = $this->jload->generate();
			$data[$this->uri->segment(2)] = 'style="display: block;"';
			$this->load->view('admin/gallery/gallery-edit', $data);
		}
	}

	function delete()
	{
		// Delete the galleries
		if($post = $this->input->post('checkbox'))
		{

			$this->load->model('gallery_model');

			if($form = $this->gallery_model->delete_galleries($post))
			{
				redirect('admin/gallery');
			}
			else
			{
				echo "Error!!!!";
			}
		}
		else
		{
			redirect('admin/gallery');
		}
	}

	function view($id)
	{
		// Select all from database where page type = $param
		$this->load->model('gallery_model');

		$rows = $this->db->query('SELECT * FROM '.$this->current_lightbox_table.' WHERE id = "'.$this->db->escape($id).'" ');

		// load pagination class
    	$this->load->library('pagination');
    	$config['base_url'] = base_url().'index.php/admin/gallery/view/'.$id.'/';
    	$config['total_rows'] = $rows->num_rows(); //$this->db->count_all('content');
    	$config['per_page'] = '12';
    	$config['full_tag_open'] = '<div id="pagination-links">';
    	$config['full_tag_close'] = '</div>';
		$config['cur_tag_open'] = '<span class="active">';
		$config['cur_tag_close'] = '</span>';
		$config['uri_segment'] = 5;

		$this->pagination->initialize($config);
				
		if($query = $this->gallery_model->get_gallery_images($config['per_page'], $this->uri->segment(5), $id, NULL, $this->current_lightbox_table))
		{
			// Get gallery title
			$title = $this->gallery_model->get_gallery($id);
			$data['images'] = $query;
			$data['javascript'] = $this->jload->generate();
			$data[$this->uri->segment(2)] = 'style="display: block;"';
			$data['title'] = 'Manage '.$title->name.' images';
			$this->load->view('admin/gallery/gallery-images', $data);
		}
		else
		{
			$this->index();
		}

	}

	function select_gallery($id)
	{
		// Add Images to the gallery
		$this->load->model('gallery_model');

		if($query = $this->gallery_model->get_galleries('',''))
		{
			$data['galleries'] = (object)$query;
			$data['image'] = $id;

			//$data['javascript'] = $this->jload->generate();
			$this->load->view('admin/gallery/gallery-select', $data);
		}
	}

	function add()
	{
		// check if image is already in the gallery
		$query = $this->db->query("SELECT id FROM ".$this->current_lightbox_table." WHERE image_id = ".$this->db->escape($this->input->post('image_id'))." AND gallery_id = ".$this->db->escape($this->input->post('gallery_id'))." LIMIT 1");

		if($query->num_rows() == 0)
		{
			// Add the image to the lightbox
			$this->load->model('gallery_model');

			// Add post to array
			$post = array(
				'image_id' => $this->input->post('image_id'),
				'gallery_id' => $this->input->post('gallery_id')
			);

			if($query = $this->gallery_model->insert_image($post, $this->current_lightbox_table))
			{
				$data['messages'] = array('Your image has been added to the gallery');
				$this->load->view('admin/messages/success_view',$data);
			}
		}
		else
		{
			
		// Load the list of galleries
		$this->load->model('gallery_model');

		if($query = $this->gallery_model->get_galleries('',''))
		{
			$data['galleries'] = (object)$query;
		}			

			$data['image'] = $this->input->post('image_id');	
			$data['javascript'] = $this->jload->generate();
			//$this->load->view('admin/gallery/gallery-select', $data);
			
			$data['messages'] = array('This image is already in the selected gallery');
			$this->load->view('admin/messages/error_view',$data);

		}
	}

	function remove($image_id)
	{

		$this->load->model('gallery_model');

		if($query = $this->gallery_model->remove_image($image_id,$this->uri->segment(5), $this->current_lightbox_table))
		{
			$this->message->set('success', 'Image has been removed from the gallery');
			redirect('admin/gallery/view/'.$this->uri->segment(5));			
		}

	}


}

?>