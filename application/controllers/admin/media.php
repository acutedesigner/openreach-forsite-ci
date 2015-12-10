<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class media Extends CI_Controller{

	// Check that the user is always logged in
	function __construct()
	{
		parent::__construct();
		$this->is_logged_in();		
		$this->jload->add('jquery-1.8.3.min.js');
		$this->jload->add('menu.js');
		$this->jload->add('colorbox/jquery.colorbox-min.js');
		$this->jload->add('jConfirmAction/jconfirmaction.jquery.js');
		$this->jload->add('qtip/jquery.qtip.min.js');
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

	function index()
	{
		$data['javascript'] = $this->jload->generate();
		$data['title'] = 'Manage Media';
		$this->load->view('admin/media/media', $data);
	}
	
	function view($type)
	{
		// Select all from database where page type = $param
		$this->load->model('media_model');

		$rows = $this->db->query('SELECT * FROM media WHERE filetype = "'.$this->uri->segment(4).'" ');

		// load pagination class
    	$this->load->library('pagination');
    	$config['base_url'] = base_url().'admin/media/view/'.$this->uri->segment(4);
    	$config['total_rows'] = $rows->num_rows(); //$this->db->count_all('content');
    	$config['per_page'] = '12';
    	$config['full_tag_open'] = '<div id="pagination-links">';
    	$config['full_tag_close'] = '</div>';
		$config['cur_tag_open'] = '<span class="active">';
		$config['cur_tag_close'] = '</span>';
		$config['uri_segment'] = 5;

		$this->pagination->initialize($config);
				
		if($query = $this->media_model->get_files($config['per_page'], $this->uri->segment(5), $type))
		{	
			$data[$type] = $query;
			$data['javascript'] = $this->jload->generate();
			$data['title'] = 'Manage '.$type;
			$this->load->view('admin/media/media', $data);
		}
		else
		{
			$this->index();
		}

	}

	function select($type)
	{
		// Select all from database where page type = $param
		$this->load->model('media_model');

		$rows = $this->db->query('SELECT * FROM media WHERE filetype = "'.$this->uri->segment(4).'" ');

		// load pagination class
    	$this->load->library('pagination');
    	$config['base_url'] = base_url().'admin/media/select/'.$this->uri->segment(4);
    	$config['total_rows'] = $rows->num_rows(); //$this->db->count_all('content');
    	$config['per_page'] = '8';
    	$config['full_tag_open'] = '<div id="pagination-links">';
    	$config['full_tag_close'] = '</div>';
		$config['cur_tag_open'] = '<span class="active">';
		$config['cur_tag_close'] = '</span>';
		$config['uri_segment'] = 5;

		$this->pagination->initialize($config);
				
		if($query = $this->media_model->get_files($config['per_page'], $this->uri->segment(5), $type))
		{	
			$data[$type] = $query;
			$data['javascript'] = $this->jload->generate();
			$data['title'] = 'Manage '.$type;
			$this->load->view('admin/media/select-image', $data);
		}
		else
		{
			$this->index();
		}

	}	
	
	// This is to return a list of files of images to the redactor file and image manager
	function filesjson($type)
	{

		// load the media model
		$this->load->model('media_model');
		
		// if the query = $query
		if($files = $this->media_model->get_files("", "", $type))
		{
		
			$queryarray = array();
		
			// We need to loop the $query and build the new array
			foreach( $files as $file )
			{
			
				// inside the loop we will build the $filearray
				$filearray = array();
				
				// if the $type is "images" or "file" the array will be setup accordingly
				if( $type == "files")
				{
					// File: title, name, link, size
					$filearray['title'] = $file->filename;
					$filearray['name'] = $file->display_name;
					$filearray['link'] = base_url().'media/'.$file->display_name;
					$filearray['size'] = '0';
					
				}
				else
				{	
									
					// Image: thumb, image, title
					$filearray['thumb'] = base_url().'media/'.$file->filename.'_thumb'.$file->ext;
					$filearray['image'] = base_url().'media/'.$file->display_name;
					$filearray['title'] = $file->filename;
					
				}
				
				// add $filearray to $queryarray
				$queryarray[] = $filearray;
				
			}		
				// Encode the $queryarray
				$jsonarray = json_encode($queryarray);
					
				// echo the $jsonarray
				echo stripslashes($jsonarray);
		}			
		// if no $query
		else
		{
			// Return error message
		}

	}
	

	function deleteFiles($type)
	{
		// Load the form validation
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('checkbox', 'Select a file please', 'required');	
				
		// Validate the form
		if($this->form_validation->run() == TRUE )
		{		
		// if validated load db model & send the array to the db model
			$this->load->model('media_model');
			
			$data = $_POST['checkbox'];
			
			if($data) // from button name="delete"
			{
				$checkbox = $data; //from name="checkbox[]"
				$countCheck = count($data);
				
				for($i=0;$i<$countCheck;$i++)
				{
					$this->media_model->delete_file($checkbox[$i]);				
				}				

				redirect('admin/media/view/'.$type);			
			}			
		}
		else
		{
		// else return an error msg
		}		
	}

	function delete($filetype)
	{
		$this->load->model('media_model');
		
		// First Retrive the filename
		if($query = $this->media_model->get_file($this->uri->segment(5)))
		{
			
			// Return file name and delete files
			if($query->filetype == 'images')
			{				
				if (file_exists("./media/".$query->filename.$query->ext))
				{
					unlink("./media/".$query->filename.$query->ext);
				}
				if (file_exists("./media/".$query->filename."_150x150".$query->ext))
				{
					unlink("./media/".$query->filename."_150x150".$query->ext);
				}
				if(file_exists("./media/".$query->filename."_300x300".$query->ext))
				{
					unlink("./media/".$query->filename."_300x300".$query->ext);
				}
				if(file_exists("./media/".$query->filename."_620x620".$query->ext))
				{
					unlink("./media/".$query->filename."_620x620".$query->ext);
				}
			}
			else
			{
				unlink("./media/".$query->filename.$query->ext);
			}
					
		}
		
		if($query = $this->media_model->delete_image($this->uri->segment(5)))
		{
			$view = 'admin/media/view/'.$filetype;
		
			$data['title'] = 'Manage Media';
			$this->message->set('success','Your image was deleted');

			// Redirect the page
			redirect('admin/media/view/'.$filetype);			
		}
		else
		{
			$data['title'] = 'Manage Media';
			$this->message->set('failed','Your image was not deleted');
			$this->load->view('admin/media/view/'.$filetype, $data);			

			// Redirect the page
			redirect('admin/media/view/'.$filetype);			
		}
				
	}
	
	function browse($type)
	{
		// Select all from database where page type = $param
		$this->load->model('media_model');
		
		$rows = $this->db->query('SELECT * FROM media WHERE filetype = "'.$type.'" ');

		// load pagination class
    	$this->load->library('pagination');
    	$config['base_url'] = base_url().'admin/media/browse/'.$type;
    	$config['total_rows'] = $rows->num_rows(); //$this->db->count_all('content');
    	$config['per_page'] = '12';
    	$config['full_tag_open'] = '<div id="pagination-links">';
    	$config['full_tag_close'] = '</div>';
		$config['cur_tag_open'] = '<span class="active">';
		$config['cur_tag_close'] = '</span>';
		$config['uri_segment'] = 5;

		$this->pagination->initialize($config);
				
		if($query = $this->media_model->get_files($config['per_page'], $this->uri->segment(5), $type))
		{	
			$data[$type] = $query;
			$data['javascript'] = $this->jload->generate();
			$data['title'] = 'Manage '.$type;
			$this->load->view('admin/media/media-browser', $data);
		}
		else
		{
			$this->index();
		}

	}
	
	function selectimage($id)
	{
		$this->load->model('media_model');

		$rows = $this->db->query('SELECT * FROM media WHERE filetype = "'.$this->uri->segment(3).'" ');

		if($query = $this->media_model->get_file($id))
		{
			
			// Check if file exsists
			$file_original = "media/".$query->filename.$query->ext;
			$file_small = "media/".$query->filename."_150x150".$query->ext;
			$file_med = "media/".$query->filename."_300x300".$query->ext;
			$file_large = "media/".$query->filename."_1024x1024".$query->ext;
			$file_cropped = "media/".$query->filename."_cropped".$query->ext;
						
			$filelist = array();
			
			$filelist['original'] = $file_original;
			
			// Add file names to array
			if(is_file($file_small)) { $filelist['small'] = $file_small; } else { echo $file_small; }
			if(file_exists($file_med)) { $filelist['medium'] = $file_med; }
			if(file_exists($file_large)) { $filelist['large'] = $file_large; }
			if(file_exists($file_cropped)) { $filelist['cropped'] = $file_cropped; }
			
			$data['filelist'] = $filelist;
			$data['file'] = $file_original;
			$data['javascript'] = $this->jload->generate();
			
			$this->load->view('admin/media/image-select', $data);
			
		}
		
	}	

	function edit($id = NULL)
	{
		$this->load->model('media_model');
		
		$id = ($this->uri->segment(5) == NULL ? $id : $this->uri->segment(5));
		
		//$rows = $this->db->query('SELECT * FROM media WHERE filetype = "'.$id.'" ');

		if($query = $this->media_model->get_file($id))
		{
			$this->jload->add('jcrop/jquery.Jcrop.min.js');
			
			$data['file'] = $query;
			$data['javascript'] = $this->jload->generate();
			
			$this->load->view('admin/media/image-edit', $data);
		}
		else
		{
			echo "No play!";
		}
				
	}	

	function image_crop()
	{

		$this->load->library('image_lib');
		$oldfile = explode('.' , $this->input->post('filename'));
		
		$config['image_library'] = 'GD2';
		$config['source_image']	= 'media/'.$this->input->post('filename');
		$config['new_image'] = 'media/'.$oldfile[0].'_cropped.'.$oldfile[1];
		$config['width'] = $this->input->post('w');
		$config['height'] = $this->input->post('h');
		$config['x_axis'] = $this->input->post('x');
		$config['y_axis'] = $this->input->post('y');
		$config['maintain_ratio'] = FALSE;
		$config['quality'] = 100;
	
		$this->image_lib->initialize($config);		
		$this->image_lib->crop();
		
		$config['source_image'] = 'media/'.$oldfile[0].'_cropped.'.$oldfile[1];
		$config['new_image'] = 'media/'.$oldfile[0].'_cropped.'.$oldfile[1];
		$config['width'] = 625;
		$config['height'] = 300;
		$config['quality'] = 100;
		
		$this->image_lib->initialize($config);		
		$this->image_lib->resize();
		
		$this->load->model('media_model');

			// Add post to array
			$post = array(
				'cropped' => '1'	
			);
		
		// First Retrive the filename
		if($query = $this->media_model->update_file($this->input->post('id'), $post))
		{
			$this->message->set('success_crop', 'Image successfully cropped');
			$this->edit($this->input->post('id'));			
		}
				
        unset($config);            
        $this->image_lib->clear(); 
	}		

	function caption()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('caption', 'Image Caption', 'trim|required');	
		
		if($this->form_validation->run() == TRUE )
		{
			// Add post to array
			$post = array(
				'caption' => $this->input->post('caption')	
			);
			
			$this->load->model('media_model');			
			
			if($this->media_model->update_file($this->input->post('id'), $post))
			{
				$this->message->set('success_caption', 'Caption successfully updated');
				$this->edit($this->input->post('id'));			
			}
		
		}
		else
		{
			$this->edit($this->input->post('id'));			
		}

	}
	
}