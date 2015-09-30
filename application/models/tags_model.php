<?php

class Tags_model extends CI_Model {

    function __construct(){
        parent::__construct();
    }
	
	function retrieve($limit = NULL, $start = NULL, $id = NULL)
	{
		$this->db->select('*');
		$this->db->from('tags');
		if($id != NULL)
		{
			$this->db->where("id",$id);
		}		
		if($limit != NULL)
		{
			$this->db->limit($limit, $start);
		}		

		$q = $this->db->get();

		// Return single row		
		if($id != NULL)
		{
			return $q->row();
		}
		// Return multiple rows
		else if($q->num_rows() > 0)
		{
			foreach($q->result() as $row)
			{
				$data[] = $row;
			}
			return $data;
		}			
	}

	function create($data)
	{
		$q = $this->db->insert('tags', $data);
		
		if($q)
		{
			return $this->db->insert_id();
		}
	}	

	function update($data, $id)
	{
		$this->db->where('id', $id);
		$q = $this->db->update('tags', $data);
		
		if($q)
		{
			return true;
		}			
	}	

	function delete_tags($data)
	{
		$this->db->where_in('id', $data);
		$q = $this->db->delete('tags');

		if($q)
		{
			return true;
		}		
	}

	function delete($id)
	{
		$this->db->where('id', $id);
		$q = $this->db->delete('tags');
				
		if($q)
		{
			return true;
		}		
	}
}