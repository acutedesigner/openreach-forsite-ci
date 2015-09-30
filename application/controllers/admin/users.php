<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class users Extends CI_Controller{

	// Check that the user is always logged in
	function __construct()
	{
		parent::__construct();
		$this->is_logged_in();		
		$this->jload->add('menu.js');
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
		$data['title'] = 'Setup new user';
		$data['javascript'] = $this->jload->generate();
		$data[$this->uri->segment(2)] = 'style="display: block;"';
		$this->load->view('admin/settings/settings', $data);
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

// View all current users	
	function view()
	{
		$this->load->model('users_model');

		if($query = $this->users_model->get_all_users())
		{
			$data['title'] = 'View users';
			$data[$this->uri->segment(2)] = 'style="display: block;"';
			$data['members'] = $query;
			$data['javascript'] = $this->jload->generate();
			$this->load->view('admin/settings/settings', $data);
		}
		else
		{
			$this->index();
		}
	}

//Edit Users
	function edit($id)
	{

		$this->load->model('users_model');
		
		if($form = $this->users_model->get_user($id))
		{
			$data['title'] ='Edit user';
			$data['formdata'] = $form;
			$data[$this->uri->segment(2)] = 'style="display: block;"';
			$data['javascript'] = $this->jload->generate();
			$this->load->view('admin/settings/user-edit', $data);
		}
		else
		{
			$data['title'] ='Edit user';
			$data = array('formdata' => NULL);
			$data[$this->uri->segment(2)] = 'style="display: block;"';
			$data['javascript'] = $this->jload->generate();
			$this->load->view('admin/settings/user-edit', $data);
		}
	
	}

// Create a new user	
	function create()
	{

			// Add them to the view data
			$data = array('formdata' => NULL);
			$data['title'] ='Add a new user';
			$data[$this->uri->segment(2)] = 'style="display: block;"';
			$data['javascript'] = $this->jload->generate();

			// Load the view
			$this->load->view('admin/settings/user-edit', $data);		
	}

	function save()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('firstname', 'first name', 'trim|required');	
		$this->form_validation->set_rules('lastname', 'last name', 'trim|required');
		
		if(!$this->input->post('userid'))
		{
			$this->form_validation->set_rules('username', 'user name', 'trim|required|min_length[6]|alpha_dash|callback_username_check');
			$this->form_validation->set_rules('email', 'email address', 'trim|required|valid_email|callback_email_check');				
			$this->form_validation->set_rules('password', 'password', 'trim|required|min_length[6]|matches[password2]');
			$this->form_validation->set_rules('password2', 'password confirm', 'trim|required');
		}
		else
		{
			$this->form_validation->set_rules('email', 'email address', 'trim|required|valid_email');
			
			if(!$this->input->post('password') == NULL)
			{
				$this->form_validation->set_rules('password', 'password', 'trim|required|min_length[6]|matches[password2]');
				$this->form_validation->set_rules('password2', 'password confirm', 'trim|required');
			}						
		}
		
		$this->form_validation->set_rules('active', 'active', 'trim|integer');
		$this->form_validation->set_rules('userid', 'userid', 'trim|integer');
		
		if($this->form_validation->run() == TRUE )
		{
			// Add post to array
			$post = array(
				'firstname' => ucfirst($this->input->post('firstname')),	
				'lastname' => ucfirst($this->input->post('lastname')),	
				'username' => $this->input->post('username'),	
				'email' => $this->input->post('email'),	
				'active' => $this->input->post('active'),
				'group' => '2'
			);
						
			$this->load->model('users_model');			
			
			// Check if this is a new user or existing
			if($this->input->post('userid'))
			{
				if(!$this->input->post('password') == NULL)
				{
					$post['password'] = md5($this->input->post('password'));
				}
					
				$post['modified'] = date('Y-m-d H:m:s');

				if($form = $this->users_model->update_user($post, $this->input->post('userid')))
				{
					$this->message->set('success','User details have been updated');
					redirect('admin/users/edit/'.$this->input->post('userid'));
				} else { echo "error"; }
			}
			else
			{
				$post['created'] = date('Y-m-d H:m:s');
				$post['activation_key'] = $this->genRandomString();
				$post['password'] = md5($this->input->post('password'));
				
				if($form = $this->users_model->insert_user($post))
				{
					$this->message->set('success','User details have been saved');
					redirect('admin/users/edit/'.$form);
				}
				else
				{
					echo "Error!!!!";
				}
			}			
			
			// Save Data to the database
		}
		else
		{
			$data['title'] ='Add a new user';
			$data['formdata'] = (object)$this->input->post();
			$data['javascript'] = $this->jload->generate();
			$this->load->view('admin/settings/user-edit', $data);
		}
		
	}
	
	function delete()
	{
			if($post = $this->input->post('checkbox'))
			{
				//delete multiple pages
				$this->load->model('users_model');
				
				if($form = $this->users_model->delete_users($post))
				{
					redirect('admin/users/view/'.$this->uri->segment(4));	
				}
			}
			else
			{
				// delete single page
				redirect('admin/users/view/'.$this->uri->segment(4));	
			}
	}
	
	function email_check($email)
	{
		$this->db->select('email')->from('users')->where('email', $email);
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{
			$this->form_validation->set_message('email_check', 'The email address already exists');
			return false;
		}
	}
	
	function username_check($user)
	{
		$this->db->select('username')->from('users')->where('username', $user);
		$query = $this->db->get();
				
		if($query->num_rows() > 0)
		{
			$this->form_validation->set_message('username_check', 'This username is already in use');
			return false;
		}
	}

}