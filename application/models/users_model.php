<?php

class users_model Extends CI_Model{

	function get_all_users()
	{
		$this->db->select('userid, firstname, lastname, username, email, active');
		$this->db->from('users');
		$this->db->where('group', '2');


		$q = $this->db->get();

		if($q->num_rows() > 0)
		{
			foreach($q->result() as $row)
			{
				$data[] = $row;
			}
			return $data;
		}
	}

	function get_user($user_id)
	{
		$this->db->select('userid, firstname, lastname, username, email, active');
		$this->db->from('users');
		$this->db->where('userid', $user_id);

		$q = $this->db->get();

		if($q->num_rows() > 0)
		{
			return $q->row();
		}
	}


	function insert_user($data)
	{
		$q = $this->db->insert('users', $data);

		if($q)
		{
			return $this->db->insert_id();
		}
	}


	function update_user($data, $id)
	{
		$this->db->where('userid', $id);
		$q = $this->db->update('users', $data);

		if($q)
		{
			return true;
		}
	}

	function delete_users($data)
	{

		foreach ($data as $id)
		{
			$this->db->where('userid', $id);
			$this->db->delete('users');
		}

		return true;
	}

}
