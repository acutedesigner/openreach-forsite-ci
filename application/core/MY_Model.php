<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Model extends CI_Model
{
	
	var $current_content_table;
	var $current_lightbox_table;
	var $edition_title;

	function __construct()
	{
		parent::__construct();
		$this->get_edition_label();		
	}

	function get_edition_label()
	{
		$this->load->model('archive_model');
		
		if($result = $this->archive_model->get_active_label())
		{
			$this->edition_title = $result->edition_title;
			$this->current_content_table = "ed_".$result->edition_label."_content";
			$this->current_lightbox_table = "ed_".$result->edition_label."_lightbox";
		}
		else
		{
			return 'An edition of the Newsletter is not active!';
		}
	}
	
}