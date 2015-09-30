<?php

class upload_model Extends CI_Model{

	function insert_image($data)
	{
		$q = $this->db->insert('media', $data);
		
		if($q)
		{
			return $this->db->insert_id();
		}
	}


}