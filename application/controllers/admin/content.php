<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content extends MY_Admin_Controller{
	
	// Check that the user is always logged in
	function __construct()
	{
		parent::__construct();
		//$this->output->enable_profiler(TRUE);
	}
	
	function index()
	{
		$data['title'] = 'Manage website content';
		$data['javascript'] = $this->load_js();
		$data['info'] = "Please select a option from the menu";
		$this->load->view('admin/content/content', $data);
	}

	function table($page_type = null)
	{
		if($this->uri->segment(4) == null)
		{
			$data['javascript'] = $this->load_js();
			$this->load->view('admin/content/content', $data);
		}

		// Select all from database where page type = $param
		$this->load->model('content_model');

		$rows = $this->db->query('SELECT * FROM '.$this->current_content_table.' WHERE type = "'.$this->uri->segment(4).'" ');

		// load pagination class
		$this->load->library('pagination');
		$config['base_url'] = base_url().'index.php/admin/content/view/'.$this->uri->segment(4);
		$config['total_rows'] = $rows->num_rows()-1; //$this->db->count_all('content');
		$config['per_page'] = '20';
		$config['full_tag_open'] = '<div id="pagination-links">';
		$config['full_tag_close'] = '</div>';
		$config['cur_tag_open'] = '<li class="current">';
		$config['cur_tag_close'] = '</li>';
		$config['uri_segment'] = 5;

		$this->pagination->initialize($config);

		if($query = $this->content_model->get_content($config['per_page'], $this->uri->segment(5), $this->uri->segment(4), $this->current_content_table))
			//if($query = $this->MPTtree->get_children($root['lft'],$root['rgt']))
			{
			// For the sidemenu
			$data[$this->uri->segment(4)] = 'style="display: block;"';
			$data['pages'] = $query;
			$data['javascript'] = $this->load_js();
			$data['title'] = 'Manage '.$this->uri->segment(4);
			$this->load->view('admin/content/content', $data);
		}
		else
		{
			$data[$this->uri->segment(4)] = 'style="display: block;"';
			$data['javascript'] = $this->load_js();
			$data['error'] = "There are no items in this category";
			$data['title'] = 'Manage '.$this->uri->segment(4);
			$this->load->view('admin/content/content', $data);
		}
	}

	function tree($page_type = null)
	{
		
		if($this->current_content_table != NULL)
		{
			$this->MPTtree->get_root();
			$this->db->where('type','page');
			$tree = $this->MPTtree->tree2array();
			$data[$this->uri->segment(4)] = 'style="display: block;"';

			$data['children'] = $tree;
			$data['title'] = 'Manage '.$this->edition_title.' edition';
		}
		else
		{
			$data['title'] = 'Whoops!';
			$this->message->set('success','You have no Editons in draft mode');			
		}

		$this->jload->add('jquery-1.6.2.min.js');
		$this->jload->add('jquery-ui-1.8.2.custom.min.js');
		$this->jload->add('jquery.ui.nestedSortable.js');
		$this->jload->add('jConfirmAction/jconfirmaction.jquery.js');
		$this->jload->add('cms_tree.js');

		$data['javascript'] = $this->jload->generate();
		$this->load->view('admin/content/content', $data);
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

		// Get the header images gallery list
		$this->load->model('gallery_model');
		
		if($header_images = $this->gallery_model->get_gallery_images(0,100,NULL,'header-images', $this->current_lightbox_table))
		{
			$images_array = array('' => 'Select One...');
			
			foreach($header_images as $image)
			{
				$images_array[$image->image_id] = $image->caption;
			}
									
			// Set the galleries
			$data['headerimg'] = $images_array;
		}

		// Load galleries
		$this->load->model('gallery_model');

		if($galleries = $this->gallery_model->get_galleries('',''))
		{
			$data['galleries'] = (object)$galleries;
		}

		if($this->uri->segment(4) == "blog")
		{

			// Get Sub pages only
			// $toplevel = $this->db->query('SELECT * FROM content WHERE type =  "'.$this->uri->segment(4).'" ORDER BY lft ASC LIMIT 1');

			$this->db->select('lft, title');
			$this->db->from($this->current_content_table);
			$this->db->where('type', $this->uri->segment(4));
			$this->db->order_by("lft", "asc");
			$this->db->limit(1, 0);
			$toplevel = $this->db->get();

			if($toplevel->num_rows() > 0)
			{
				$parent = $toplevel->row();
				$data['parent'] = $parent;
			}
			else
			{
				$parent = NULL;
			}
		}

		else

		{
			// Get the page hirarachy tree
			$root = $this->MPTtree->get_root();
			$tree = $this->MPTtree->get_children($root['lft'],$root['rgt']);
			$data['children'] = $tree;
			$data['root'] = $root;
		}

		// Load the js files
		$data['javascript'] = $this->load_js();

		// Set title Message
		$data['title'] = 'Add a new '.$this->uri->segment(4);

		// Load the view
		$this->load->view('admin/content/content-edit-red', $data);
	}

	//Edit Content
	function edit()
	{
		$this->load->model('content_model');
		$this->load->model('users_model');

		if($form = $this->content_model->get_page($this->uri->segment(5), $this->current_content_table))
		{
			// Get the page hirarachy tree
			//$tree = $this->MPTtree->tree2array();

			$root = $this->MPTtree->get_root();
			$tree = $this->MPTtree->get_children($root['lft'],$root['rgt']);
			$node = $this->MPTtree->get_node($form->lft);
			$parent = $this->MPTtree->get_parent($node['lft'],$node['rgt']);

			// Getting a list of Authors
			$users = $this->users_model->get_all_users();

			
			// Add them to the view data
			$data = array(
				'formdata' => $form,
				'children' => $tree,
				'root' => $root,
				'parent' => (object)$parent
			);

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


			// Get the header images gallery list
			$this->load->model('gallery_model');
			
			if($header_images = $this->gallery_model->get_gallery_images(0,100,NULL,'header-images', $this->current_lightbox_table))
			{
				$images_array = array('' => 'Select One...');
				
				foreach($header_images as $image)
				{
					$images_array[$image->image_id] = $image->caption;
				}
										
				// Set the galleries
				$data['headerimg'] = $images_array;
			}

			// Load the js files
			$data['javascript'] = $this->load_js();

			// Load galleries
			$this->load->model('gallery_model');

			if($galleries = $this->gallery_model->get_galleries('',''))
			{
				$data['galleries'] = (object)$galleries;
			}

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

			if($galleries = $this->gallery_model->get_galleries('',''))
			{
				$data['galleries'] = (object)$galleries;
			}

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
		$this->form_validation->set_rules('lft', 'Left', 'trim|required');
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
				'header_image' => $this->input->post('header_image'),
				'type' => $this->input->post('type'),
				'date_created' => $this->input->post('date_created') ,
				'nested' => $this->input->post('nested'),
			);

			$this->load->model('content_model');


			if($this->input->post('id'))
			{
				$post['last_edited'] = date('Y-m-d H:m:s');
				print_r($this->input->post());

				//if($form = $this->content_model->update_page($post, $this->input->post('id')))
				if($form = $this->MPTtree->update_node($this->input->post('lft'), $post))
				{
					// Move the page to new position if changed
					if($this->input->post('lft') != $this->input->post('position'))
					{
						$this->MPTtree->move_node_append($this->input->post('lft'),$this->input->post('position'));
					}

					// Load the js files
					$data['javascript'] = $this->load_js();

					// Set success Message
					$this->message->set('success','Your '.$this->input->post('type').' has been saved');

					// Redirect the page
					redirect('admin/content/edit/'.$this->input->post('type').'/'.$this->input->post('id'));
				}
			}
			else
			{
				$post['date_created'] = date('Y-m-d H:m:s');
				$post['last_edited'] = date('Y-m-d H:m:s');

				print_r($this->input->post());

				//if($form = $this->content_model->insert_page($post))
				if($form = $this->MPTtree->append_node_last($this->input->post('position'), $post))
				{
				
					// Load the js files
					$data['javascript'] = $this->load_js();

					// From the MPTTree
					$query = $this->db->query('SELECT LAST_INSERT_ID()');
					$row = $query->row_array();
					$form = $row['LAST_INSERT_ID()'];

					// Set title Message
					$this->message->set('success','Your '.$this->input->post('type').' has been saved');

					// Redirect the page
					redirect('admin/content/edit/'.$this->input->post('type').'/'.$form);
				}
				else
				{
					echo "Article not saved!!";
				}
			}

			// Save Data to the database

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

			if($this->uri->segment(4) == "blog")
			{
	
				// Get Sub pages only
				// $toplevel = $this->db->query('SELECT * FROM content WHERE type =  "'.$this->uri->segment(4).'" ORDER BY lft ASC LIMIT 1');
	
				$this->db->select('lft, title');
				$this->db->from($this->current_content_table);
				$this->db->where('type', $this->uri->segment(4));
				$this->db->order_by("lft", "asc");
				$this->db->limit(1, 0);
				$toplevel = $this->db->get();
	
				if($toplevel->num_rows() > 0)
				{
					$parent = $toplevel->row();
				}
	
				$data['parent'] = $parent;
			}
	
			else
	
			{
				// Get the page hirarachy tree
				$root = $this->MPTtree->get_root();
				$tree = $this->MPTtree->get_children($root['lft'],$root['rgt']);
				$data['children'] = $tree;
				$data['root'] = $root;
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

		if($post = $this->input->post('checkbox'))
		{
			//delete multiple pages
			if($form = $this->content_model->delete_pages($post, $this->current_content_table))
			{
				redirect('admin/content/table/'.$this->uri->segment(4));
			}
			elseif($form = $this->content_model->delete_pages($id, $this->current_content_table))
			{
				redirect('admin/content/table/'.$this->uri->segment(4));
			}
		}
		elseif($form = $this->content_model->delete_page($this->uri->segment(5), $this->current_content_table))
		{
			// delete single page
			redirect('admin/content/tree/'.$this->uri->segment(4));
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

}