<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
		$this->load->helper('form');
		$this->load->helper('url');
		$this->jload->add('jquery-1.8.3.min.js');
		$this->jload->add('menu.js');
		$this->jload->add('fine-uploader/jquery.fine-uploader.min.js');
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
	
	/*
	*	Display upload form
	*/
	public function index()
	{
		$data['javascript'] = $this->jload->generate();
		$data['error'] = '';
		$this->load->view('admin/upload/upload_ajax', $data);
	}


	function image_thumbnail($filepath, $filename, $fileext = "", $size)
	{
		$config['image_library'] = 'gd2';
		$config['source_image']	= $filepath;
		$config['new_image'] = $filename.'_'.$size.'x'.$size.$fileext;
		$config['maintain_ratio'] = TRUE;
		$config['width'] = $size;
		$config['height'] = $size;
		
		$this->image_lib->initialize($config);		
		$this->image_lib->resize();		
        unset($config);            
        $this->image_lib->clear(); 
	}		

	function image_crop($filepath, $filename, $fileext = "")
	{
		$filepath = explode('.' , $filepath);
		$filepath = $filepath[0].'_300x300.'.$filepath[1];
	
		$config['image_library'] = 'gd2';
		$config['source_image']	= $filepath;
		$config['new_image'] = $filename.'_150x150'.$fileext;
		$config['width'] = '150';
		$config['height'] = '150';
		$config['x_axis'] = '0';
		$config['y_axis'] = '0';
	
		echo $filepath.'<br/>'.$config['new_image'];

		$this->image_lib->initialize($config);		
		$this->image_lib->crop();		
        unset($config);            
        $this->image_lib->clear(); 
	}
	
	function create_thumb($filepath, $filename, $fileext)
	{
	    $img_path = './media/'.$filename.$fileext;
	    $img_thumb = './media/'.$filename.'_thumb'.$fileext;
	
	    $config['image_library'] = 'gd2';
	    $config['source_image'] = $img_path;
	    $config['new_image'] = $img_thumb;
	    $config['create_thumb'] = TRUE;
	    $config['maintain_ratio'] = FALSE;
	    $config['quality'] = 100;
	
	    //$img = imagecreatefromjpeg($img_path);
	    $img = ($fileext == '.gif' ? imagecreatefromgif($img_path) : imagecreatefromjpeg($img_path));
	    $_width = imagesx($img);
	    $_height = imagesy($img);
	
	    $img_type = '';
	    $thumb_size = 150;
	
	    if ($_width > $_height)
	    {
	        // wide image
	        $config['width'] = intval(($_width / $_height) * $thumb_size);
	        if ($config['width'] % 2 != 0)
	        {
	            $config['width']++;
	        }
	        $config['height'] = $thumb_size;
	        $img_type = 'wide';
	    }
	    else if ($_width < $_height)
	    {
	        // landscape image
	        $config['width'] = $thumb_size;
	        $config['height'] = intval(($_height / $_width) * $thumb_size);
	        if ($config['height'] % 2 != 0)
	        {
	            $config['height']++;
	        }
	        $img_type = 'landscape';
	    }
	    else
	    {
	        // square image
	        $config['width'] = $thumb_size;
	        $config['height'] = $thumb_size;
	        $img_type = 'square';
	    }
	
	    $this->load->library('image_lib');
	    $this->image_lib->initialize($config);
	    $this->image_lib->resize();
	
	    // reconfigure the image lib for cropping
	    $conf_new = array(
	        'image_library' => 'gd2',
	        'source_image' => $img_thumb,
	        'create_thumb' => FALSE,
	        'maintain_ratio' => FALSE,
	        'width' => $thumb_size,
	        'height' => $thumb_size,
	        'thumb_marker' => '_thumb'
	    );
	
	    if ($img_type == 'wide')
	    {
	        $conf_new['x_axis'] = ($config['width'] - $thumb_size) / 2 ;
	        $conf_new['y_axis'] = 0;
	    }
	    else if($img_type == 'landscape')
	    {
	        $conf_new['x_axis'] = 0;
	        $conf_new['y_axis'] = ($config['height'] - $thumb_size) / 2;
	    }
	    else
	    {
	        $conf_new['x_axis'] = 0;
	        $conf_new['y_axis'] = 0;
	    }
	
	    $this->image_lib->initialize($conf_new);
	
	    $this->image_lib->crop();
	}

	/*
	*	Handles JSON returned from /js/uploadify/upload.php
	*/
	
	public function upload_files($source)
	{

		$config['upload_path'] = './media/';
		$config['allowed_types'] = 'gif|jpg|png|txt|csv|htm|html|xml|css|doc|docx|xls|rtf|ppt|pdf|swf|flv|avi|wmv|mov|jpg|jpeg|gif|png"';
		$config['max_size']	= '200000';
		$config['max_width']  = '6200';
		$config['max_height']  = '4650';
				
		// if($result): $dothis :else $do default;
		$field_name = $source == "content" ? "file" : "qqfile";		
		
		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload($field_name))
		{
			$error = array('error' => $this->upload->display_errors());

			// Error array encoded and sent back to js script
			echo stripslashes(json_encode($error));
		}
		else
		{
			
			// Get the file upload data array
			$file_info = $this->upload->data();
		
			// Set the file type
			$filetype = "files";
			
			//set_filename($path, $filename, $file_ext, $encrypt_name = TRUE);
			
			// Setup the array for the database
			$table_data = array(
				'filename' => $file_info['raw_name'],
				'display_name' => $file_info['client_name'],
				'ext' => $file_info['file_ext'],
				'cropped' => '0',
				'caption' => $file_info['client_name'],
				'description' => 'Detailed information about this image',
				'modified' => date('Y-m-d H:m:s'),
				'created' => date('Y-m-d H:m:s'),
				'filetype' => $filetype,
				'mime_type' => $file_info['file_type'],
				'url_link' => $file_info['full_path']
			);
			
			// If the file is an image, we'll change the file type and setup more attributes
			if(list($width, $height) = getimagesize($file_info['full_path']))
			{
				$filetype = "images";
				$table_data['filetype'] = $filetype;
				$table_data['height'] = $height;
				$table_data['width'] = $width;
			}
	
			$this->load->model('upload_model');
	
			if($form = $this->upload_model->insert_image($table_data))
			{
				if(list($width, $height) = getimagesize($file_info['full_path']))
				{
					$this->load->library('image_lib'); 
	
					$this->image_thumbnail($file_info['full_path'], $file_info['raw_name'], $file_info['file_ext'], 150);
					$this->create_thumb($file_info['full_path'], $file_info['raw_name'], $file_info['file_ext']);
	
					if($width > 300)
					{
						$this->image_thumbnail($file_info['full_path'], $file_info['raw_name'], $file_info['file_ext'], 300);
					}
					
					if($width > 620)
					{
						$this->image_thumbnail($file_info['full_path'], $file_info['raw_name'], $file_info['file_ext'], 620);
					}
				}
				
				// success array
				$array = array(
				    'filelink' => base_url().'media/'.$file_info['file_name'],
				    'success' => TRUE
				);
	 
				// Succes array encoded and sent back to js script
				echo stripslashes(json_encode($array));

			}						
		}
	}
	
}
/* End of File /application/controllers/upload.php */