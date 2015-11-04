<?php

class Archive_model extends CI_Model {


/*
	CREATE TABLE archive (
	    edition_id int(11) NOT NULL AUTO_INCREMENT,
	    edition_title varchar(50) DEFAULT NULL,
	    edition_label varchar(50) DEFAULT NULL,
	    status int(1) DEFAULT NULL,
	    date_created DATE DEFAULT NULL,
	    PRIMARY KEY (edition_id)
	    );
*/
    
    
    function __construct(){
        parent::__construct();
    }
	
	function retrieve($limit = NULL, $start = NULL, $id = NULL)
	{
		$this->db->select('*');
		$this->db->from('archive');
		if($id != NULL)
		{
			$this->db->where("edition_id",$id);
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
		$q = $this->db->insert('archive', $data);
		
		if($q)
		{
			return $this->db->insert_id();
		}
	}	

	function update($data, $id)
	{
		$this->db->where('edition_id', $id);
		$q = $this->db->update('archive', $data);
		
		if($q)
		{
			return true;
		}			
	}	

	function delete($id)
	{
		$this->db->where('id', $id);
		$q = $this->db->delete('archive');
				
		if($q)
		{
			return true;
		}		
	}
	
	function deactivate_editions()
	{
		$data = array(
		               'status' => 0
		            );

		$this->db->where('status', 1);		
		$q = $this->db->update('archive', $data);
		
		if($q)
		{
			return true;
		} 
	}
	
	function check_label($label, $edition_id)
	{
		$this->db->select('edition_label');
		$this->db->where('edition_label', $label);
		
		if($edition_id != NULL)
		{
			$this->db->where_not_in('edition_id', $edition_id);			
		}

		$q = $this->db->get('archive');
		
		if($q->num_rows() > 0)
		{
			return true;
		}
	}
	
	function get_active_label($status = 1)
	{
		$this->db->select('edition_label, edition_title');
		$this->db->where('status', $status);
		
		$q = $this->db->get('archive');
		
		if($q)
		{
			return $q->row();
		}
		
	}
}