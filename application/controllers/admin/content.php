<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * File: admin/content.php
 *
 * A system to create and update the content articles
 *
 * @author Nigel M Peters @acute_designer
 * 
 */

class Content extends MY_Admin_Controller{
	
	// Check that the user is always logged in
	function __construct()
	{
		parent::__construct();
		//$this->output->enable_profiler(TRUE);
	}

	// Create Content
	function create()
	{
		// Setup the variables
		$postdata = array(
			'author' => NULL,
			'title' => NULL,
			'content' => NULL,
			'status' => NULL,
			'type' => $this->uri->segment(4),
			'parent_id' => $this->uri->segment(5),
			'date_created' => date("Y-m-d"),
			'tag_id' => NULL,
			'id' => NULL,
			'lft' => 0,
			'gallery' => NULL,
			'header_image' => NULL,
			'userid' => $this->session->userdata('userid'),
			'firstname' => $this->session->userdata('firstname'),
			'lastname' => $this->session->userdata('lastname'),
			'nested' => 1
		);

		// Add them to the view data
		$data = array('formdata' => (object)$postdata);

		$this->load->model('users_model');
		if($users = $this->users_model->get_all_users())
		{
			// Set the users
			$data['users'] = $users;
		}

		// Get the tags list
		$this->load->model('tags_model');
		if($tags = $this->tags_model->retrieve())
		{
			$tagarray = array('' => 'Select One...');
			
			foreach($tags as $tag)
			{
				$tagarray[$tag->id] = $tag->tag_name;
			}
						
			// Set the tags
			$data['tags'] = $tagarray;
		}

		// Load the js files
		$data['javascript'] = $this->load_js();

		// Set title Message
		$data['title'] = 'Add a new '.$this->uri->segment(4);

		// Load the view
		$this->load->view('admin/content/content-edit-red', $data);
	}
	

	public function edit()
	{
		$this->load->model('content_model');
		$this->load->model('users_model');

		if($form = $this->content_model->get_page($this->uri->segment(6)))
		{

			// Getting a list of Authors
			$users = $this->users_model->get_all_users();
			
			// Add them to the view data
			$data = array(
				'formdata' => $form
			);

			// Get header image
			if($form->header_image != 0)
			{
				$this->load->model('media_model');
				$header_image = $this->media_model->get_file($form->header_image);
				$data['header_image'] = '<img src="'.base_url().'media/'.$header_image->filename.'_150x150'.$header_image->ext.'" alt="'.$header_image->caption.'" />';
			}
			
			// Get the tags list
			$this->load->model('tags_model');
			if($tags = $this->tags_model->retrieve())
			{
				$tagarray = array('' => 'Select One...');
				
				foreach($tags as $tag)
				{
					$tagarray[$tag->id] = $tag->tag_name;
				}
							
				// Set the tags
				$data['tags'] = $tagarray;
			}

			// Load the js files
			$data['javascript'] = $this->load_js();

			// Load galleries
			$this->load->model('gallery_model');

			// Set the users
			$data['users'] = $users;

			// Set title Message
			$data['title'] = 'Edit '.$this->uri->segment(4);

			// Load the view
			$this->load->view('admin/content/content-edit-red', $data);
		}
		else
		{
			// Getting a list of Authors
			$users = $this->users_model->get_all_users();

			// Load the js files
			$data['javascript'] = $this->load_js();

			// Load galleries
			$this->load->model('gallery_model');

			// Set the users
			$data['users'] = $users;

			// Set title Message
			$data['title'] = 'Edit '.$this->uri->segment(4);

			// Load the view
			$this->load->view('admin/content/content-edit-red', $data);
		}

	}
	function save()
	{

		$this->load->library('form_validation');

		$this->form_validation->set_rules('author', 'Author', 'trim|integer');
		$this->form_validation->set_rules('title', 'Title', 'trim|required');
		$this->form_validation->set_rules('content', 'Content', 'trim|required');
		$this->form_validation->set_rules('status', 'Status', 'trim|integer');
		$this->form_validation->set_rules('gallery', 'Gallery', 'trim|integer');
		$this->form_validation->set_rules('type', 'Type', 'trim|required');
		$this->form_validation->set_rules('date_created', 'Post Date', 'trim');
		$this->form_validation->set_rules('id', 'id', 'trim|integer');
		$this->form_validation->set_rules('nested', 'nested', 'trim|integer');

		if($this->form_validation->run() == TRUE )
		{

			// Add post to array
			$post = array(
				'author' => $this->input->post('author'),
				'title' => $this->input->post('title'),
				'friendly_title' => url_title($this->input->post('title'), 'dash', TRUE),
				'content' => $this->input->post('content', FALSE),
				'status' => $this->input->post('status'),
				'tag_id' => $this->input->post('tag_id'),
				'gallery' => $this->input->post('gallery'),
				'parent_id' => $this->input->post('parent_id'),
				'header_image' => $this->input->post('header_image'),
				'type' => $this->input->post('type'),
				'date_created' => $this->input->post('date_created') ,
				'nested' => $this->input->post('nested'),
			);

			$this->_save_data($post);

		}
		else
		{
			// Getting a list of Authors
			$this->load->model('users_model');

			$users = $this->users_model->get_all_users();
			$nmp = $this->users_model->get_user($this->input->post('author'));

			// Set the users
			$data['users'] = $users;

			// Add them to the view data
			$postdata = $this->input->post();
			$postdata = array_merge($postdata, (array)$nmp);
			$postdata = (object)$postdata;

			$data['formdata'] = $postdata;

			// Load galleries
			$this->load->model('gallery_model');

			if($galleries = $this->gallery_model->get_galleries('',''))
			{
				$data['galleries'] = (object)$galleries;
			}

			// Load the js files
			$data['javascript'] = $this->load_js();

			// Set title Message
			$data['title'] = 'Add a new '.$this->input->post('type');

			// Load the view
			$this->load->view('admin/content/content-edit-red', $data);
		}

	}

	function post_status_update($status)
	{
		$this->load->model('content_model');

		// set the new status
		if($status == '1'){ $status = '0'; } else { $status = '1'; }

		// Add post to array
		$status = array(
			'status' => $status
		);

		if($this->content_model->status_update($status, $this->uri->segment(5), $this->current_content_table))
		{

			$data['status'] = $status['status'];

			if ($this->input->post('ajax')) {
				echo $data['status'];
			} else {
				$this->load->view('admin/includes/template', $data);
				redirect($this->input->post('current'));
			}
		}
	}

	function delete()
	{
		$this->load->model('content_model');

		if($form = $this->content_model->delete_page($this->uri->segment(5)))
		{
			// delete single page
			redirect('admin/newsletters/content/'.$this->uri->segment(4));
		}
	}

	function move_node(){
		$node = $this->MPTtree->get_node($_POST['selitems']);
		$target = $this->MPTtree->get_node($_POST['new_dir']);
		switch($_POST['point']){
		case 'append':
			// add as child
			$this->MPTtree->move_node_append($node['lft'],$target['lft']);
			break;
		case 'above':
			// add above new_dir
			$this->MPTtree->move_node_before($node['lft'],$target['lft']);
			break;
		case 'below':
			// add below
			$this->MPTtree->move_node_after($node['lft'],$target['lft']);
			break;
		}
		//echo '{success: \'true\'}';
	}


	private function _save_data($formdata)
	{

		$this->load->model('content_model');

		if($this->input->post('id'))
		{
			$formdata['friendly_title'] = $this->_check_title($formdata['friendly_title'], $this->input->post('id'));
			$formdata['last_edited'] = date('Y-m-d H:m:s');

			// updating content
			if($form = $this->content_model->update_page($this->input->post('id'), $formdata))
			{
				// Load the js files
				$data['javascript'] = $this->load_js();

				// Set success Message
				$this->message->set('success','Your '.$formdata['type'].' has been updated');

				// Redirect the page
				redirect('admin/content/edit/'.$formdata['type'].'/'.$formdata['parent_id'].'/'.$this->input->post('id'));
			}
		}
		else
		{
			//creating new content
			$formdata['date_created'] = date('Y-m-d H:m:s');
			$formdata['last_edited'] = date('Y-m-d H:m:s');

			$formdata['friendly_title'] = $this->_check_title($formdata['friendly_title']);

			if($new_content_id = $this->content_model->insert_page($formdata))
			{
			
				// Load the js files
				$data['javascript'] = $this->load_js();

				// Set title Message
				$this->message->set('success','Your '.$formdata['type'].' has been saved');

				// Redirect the page
				redirect('admin/content/edit/'.$formdata['type'].'/'.$formdata['parent_id'].'/'.$new_content_id);
			}
			else
			{
				echo "Article not saved!!";
			}
		}		
	}

	private function _check_title($title, $id = NULL)
	{
		// Check title does not exist
		$this->load->model('content_model');
		if($count = $this->content_model->check_title($title, $id))
		{
			// If something found then we need to update the title url
			$title = $title.'-'.($count+1);
		}
		return $title;
	}

}