<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Archive Login
class Login extends MY_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
	
		if($this->input->post('submit'))
		{
			$this->load->library('form_validation');
	
			// Set validation requirements		
			$this->form_validation->set_rules('username', 'Username', 'trim|required');
			$this->form_validation->set_rules('password', 'Password', 'trim|required');
	
			// Run validation		
			if($this->form_validation->run() == TRUE )
			{
				$this->load->model('login_model');
				$query = $this->login_model->validate_user();
				
				if($query)
				{							
					$data = array(
						'firstname' 	=> $query->firstname,
						'lastname' 		=> $query->lastname,
						'userid' 		=> $query->userid,
						'username' 		=> $this->input->post('username'),
						'is_logged_in' 	=> true
					);
	
					$this->session->set_userdata($data);
					redirect('archive');
				}
				else
				{
					// If the login fails
					$data['login_link'] = 'archive/login';
					$data['title'] = 'Log in to the archive';
					$data['failed'] = "Your login details are not correct";
					$this->load->view('admin/login/login_form', $data);	
				}
				
			}
			else //if the validation fails here
			{
				$data['login_link'] = 'archive/login';
				$data['title'] = 'Log in to the archive';
				$this->load->view('admin/login/login_form', $data);				
			}
		}
		else
		{
			$data['login_link'] = 'archive/login';
			$data['title'] = 'Log in to the archive';
			$this->load->view('admin/login/login_form', $data);				
		}
	
	}
	
	function logout()
	{
		$this->session->sess_destroy();
		$data['login_link'] = 'archive/login';
		$data['title'] = 'Log in to the archive';
		$data['failed'] = "You have been sucessfully logged out";
		$this->load->view('admin/login/login_form', $data);	
	}
	
}