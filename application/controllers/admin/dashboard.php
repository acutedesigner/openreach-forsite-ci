<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class dashboard Extends MY_Admin_Controller{
	
	// Check that the user is always logged in
	function __construct()
	{
		parent::__construct();
		$this->is_logged_in();		
	}
	
	function is_logged_in()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');
		
		if(!isset($is_logged_in) || $is_logged_in != TRUE)
		{
		// Needs to be added to the messages
			$data['failed'] = "You are not logged in. Please login";
			redirect('admin/login');
		}
	}

	function index()
	{
		$data['javascript'] = $this->load_js();
		$this->load->view('admin/dashboard', $data);
	}

}