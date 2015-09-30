<?php

class Promos_model extends CI_Model {

    function __construct(){
        parent::__construct();
    }
	
	function retrieve($limit = NULL, $start = NULL, $id = NULL)
	{
		$this->db->select('*');
		$this->db->from('promos_2');
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
		$q = $this->db->insert('promos_2', $data);
		
		if($q)
		{
			return $this->db->insert_id();
		}
	}	

	function update($data, $id)
	{
		$this->db->where('id', $id);
		$q = $this->db->update('promos_2', $data);
		
		if($q)
		{
			return true;
		}			
	}	

	function delete_promos($data)
	{
		$this->db->where_in('id', $data);
		$q = $this->db->delete('promos_2');

		if($q)
		{
			return true;
		}		
	}

	function delete($id)
	{
		$this->db->where('id', $id);
		$q = $this->db->delete('promos_2');
				
		if($q)
		{
			return true;
		}		
	}
	
	function display_promos()
	{
		// SELECT promo, promo_title, promo_link, filename, ext FROM promos_2 LEFT JOIN media ON promos_2.promo = media.id;
		$this->db->select('promos_2.id AS promoid, promo, promo_title, promo_link, filename, ext');
		$this->db->from('promos_2');
		$this->db->join('media', 'promos_2.promo = media.id', 'left');

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
	
}