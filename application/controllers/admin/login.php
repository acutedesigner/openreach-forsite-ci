<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login Extends CI_Controller{

	// this will load the login form
	function index()
	{
		echo "here";
		$data['login_link'] = 'admin/login/login_user';
		$data['title'] = 'Log in to the admin';
		$this->load->view('admin/login/login_form', $data);	
	}
	
	function login_user()
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
					'firstname' => $query->firstname,
					'lastname' => $query->lastname,
					'userid' => $query->userid,
					'username' => $this->input->post('username'),
					'is_logged_in' => true
				);

				$this->session->set_userdata($data);
				redirect('admin/dashboard/index');
			}
			else
			{
				// If the login fails
				$data['failed'] = "Your login details are not correct";
				$data['login_link'] = 'admin/login/login_user';
				$data['title'] = 'Log in to the admin';
				$this->load->view('admin/login/login_form', $data);	
			}
			
		}
		else //if the validation fails here
		{
			$this->index();
		}
	
	}
	
	function logout_user()
	{
		$this->session->sess_destroy();
		$data['login_link'] = 'admin/login/login_user';
		$data['title'] = 'Log in to the admin';
		$data['failed'] = "You have been sucessfully logged out";
		$this->load->view('admin/login/login_form', $data);	
	}
	
	function retrieve_password()
	{
		$this->load->view('admin/retrieve_password');
	}
	
	function check_user()
	{
		$this->load->library('form_validation');

		// Set validation requirements		
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

		// Run validation		
		if($this->form_validation->run() == TRUE )
		{
			$this->load->model('login_model');
			$query = $this->login_model->check_user();
			
			if($query)
			{
				$data['success'] = "A email " . $this->input->post('email') . " does not exist";
				$this->load->view('admin/login/retrieve_password', $data);				
			}
			else
			{
				// if the db check fails
				$data['failed'] = "The email " . $this->input->post('email') . " does not exist";
				$this->load->view('admin/login/retrieve_password', $data);				
			}
		}
		else
		{
			// if the validation fails
			$this->retrieve_password();
		}
		
	}

	function reset_password()
	{
		if($this->input->post())
		{
			$this->load->library('form_validation');

			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

			if($this->form_validation->run() == TRUE )
			{
				$email = $this->input->post('email');

				$this->load->model('login_model');
				$query = $this->login_model->check_user();

				if($query)
				{
					if($get_key = $this->login_model->reset_password($this->input->post('email')))
					{
						$activation_key = $get_key->activation_key;
						$userid = $get_key->userid;
					}

					//If the query is successful then get a new validation link
					
					// Create and send email password
					$this->load->library('email');
					$this->email->set_newline("\r\n");

					$this->email->from('nutyga@googlemail.com', 'NM Peters');
					$this->email->to($email); 
					$this->email->subject('Reset your password');

					$this->email->message('To reset your password. Click the link below\n '.site_url().'/login/resetprocess/'.$userid.'/'.$activation_key);	

					$this->email->send();

					$data['email'] = $this->email->print_debugger();
					$data['javascript'] = $this->jload->generate();
					$data['success'] = "A email has been sent";
					$this->load->view('admin/login/reset_password', $data);				
				}
				else
				{

					$data['javascript'] = $this->jload->generate();
					// if the db check fails
					$data['failed'] = "The email " . $this->input->post('email') . " does not exist";
					$this->load->view('admin/login/reset_password', $data);				
				}
			}
			else
			{
				$data['javascript'] = $this->jload->generate();
				$this->load->view('admin/login/reset_password', $data);				
			}
		}
		else
		{
			$data['javascript'] = $this->jload->generate();
			$this->load->view('admin/login/reset_password', $data);	
		}		
	}

	function resetprocess($userid)
	{
		$this->load->model('login_model');
		
		$key = $this->login_model->check_key($this->uri->segment(4));
		
		if($key)
		{
			$data['form'] = $userid;
			$this->load->view('admin/login/update_password', $data);
		}
		else
		{
			$data['failed'] = 'Your validation link is not valid!';
			$this->load->view('admin/login/reset_password', $data);
		}
	}

	function set_new_password()
	{
		$this->load->library('form_validation');

		$this->form_validation->set_rules('password', 'password', 'trim|required|min_length[6]|matches[password2]');
		$this->form_validation->set_rules('password2', 'password confirm', 'trim|required');

		if($this->form_validation->run() == TRUE )
		{
			// Add post to array
			$post = array(
				'password' => md5($this->input->post('password')),	
				'activation_key' => $this->genRandomString()
			);
			
			$this->load->model('login_model');
			
			if($this->login_model->update_pass($post, $this->input->post('userid')))
			{
				$data['success'] = "Your password has been reset! Please login.";		
				$this->load->view('admin/login/login_form', $data);		
			}
			
		}
		else
		{
			$data['form'] = $this->input->post('userid');
			$this->load->view('admin/login/update_password', $data);		
		}
		// Load view for new password fields
				
		//if validation is correct update the new password
		
		//if post is on check the validation key is valid
		
		// generate a new validation key		
	}
		
	function genRandomString($length = 32)
	{

    // start with a blank password
    $password = "";

    // define possible characters - any character in this string can be
    // picked for use in the password, so if you want to put vowels back in
    // or add special characters such as exclamation marks, this is where
    // you should do it
    $possible = "2346789bcdfghjkmnpqrtvwxyzBCDFGHJKLMNPQRTVWXYZ";

    // we refer to the length of $possible a few times, so let's grab it now
    $maxlength = strlen($possible);
  
    // check for length overflow and truncate if necessary
    if ($length > $maxlength) {
      $length = $maxlength;
    }
	
    // set up a counter for how many characters are in the password so far
    $i = 0; 
    
    // add random characters to $password until $length is reached
    while ($i < $length) { 

      // pick a random character from the possible ones
      $char = substr($possible, mt_rand(0, $maxlength-1), 1);
        
      // have we already used this character in $password?
      if (!strstr($password, $char)) { 
        // no, so it's OK to add it onto the end of whatever we've already got...
        $password .= $char;
        // ... and increase the counter by one
        $i++;
      }

    }

    // done!
    return $password;
	}
			
}