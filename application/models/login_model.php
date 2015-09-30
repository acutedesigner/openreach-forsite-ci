<?php

class login_model Extends CI_Model{
	
	function validate_user()
	{
		// setup the query
		$this->db->select('firstname, lastname, userid');
		$this->db->where('active', '1');
		$this->db->where('username', $this->input->post('username'));
		$this->db->where('password', md5($this->input->post('password')));
		
		// get the user
		$query = $this->db->get('users');
		
		if($query->num_rows() > 0)
		{
			return $query->row();
		}
	}
	
	function check_user()
	{
		$this->db->where('email', $this->input->post('email'));
		
		$query = $this->db->get('users');
		
		if($query->num_rows == 1)
		{
			return true;
		}

	}	
	
	function reset_password($email)
	{
		$this->db->select('activation_key, userid');
		$this->db->where('email', $email);
		
		$query = $this->db->get('users');
		
		if($query->num_rows() > 0)
		{
			return $query->row();
		}

	}	

	function check_key($key)
	{
		$this->db->where('activation_key', $key);
		
		$query = $this->db->get('users');
		
		if($query->num_rows == 1)
		{
			return true;
		}

	}
	
	function update_pass($data, $id)
	{
		$this->db->where('userid', $id);
		$q = $this->db->update('users', $data);
		
		if($q)
		{
			return true;
		}
	}
	
}