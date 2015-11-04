<?php

class content_model Extends CI_Model{

	function __construct()
	{
		parent::__construct();
/*
		$this->load->model('MPTtree');
		$this->MPTtree->set_table($current_content_table);
*/
	}
	
	function get_content($num, $offset, $page_type, $current_content_table)
	{
		$this->db->select('*');
        $this->db->from($current_content_table);
		$this->db->where('type', $page_type);
		$this->db->join('users', 'userid = '.$current_content_table.'.author', 'left');
		$this->db->order_by($current_content_table.'.date_created', 'desc');
		$this->db->limit($num, $offset);
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

	function get_page($page_id, $current_content_table)
	{
		$this->db->select($current_content_table.'.id, title, content, tag_id, status, '.$current_content_table.'.date_created, type, userid, nested, users.firstname, users.lastname, name, gallery, header_image, lft');
		$this->db->from($current_content_table);
		$this->db->where($current_content_table.'.id', $page_id);
		$this->db->join('users', 'userid = '.$current_content_table.'.author', 'left');
		$this->db->join('galleries', 'galleries.id = '.$current_content_table.'.gallery', 'left');

		$q = $this->db->get();	

		if($q->num_rows() > 0)
		{
			return $q->row();
		}
	}

	function get_home($current_content_table)
	{
		$this->db->select($current_content_table.'.id, title, content, status, '.$current_content_table.'.date_created, type, userid, nested, users.firstname, users.lastname, lft');
		$this->db->from($current_content_table);
		$this->db->where($current_content_table.'.lft', '1');
		$this->db->join('users', 'userid = '.$current_content_table.'.author', 'left');

		$q = $this->db->get();	

		if($q->num_rows() > 0)
		{
			return $q->row();
		}
	}

	function get_sidebar($lft,$rgt,$current_content_table)
	{

		$this->db->select($current_content_table.'.id, title, friendly_title, content, status, '.$current_content_table.'.date_created, type, userid, users.firstname, users.lastname, gallery, header_image, lft, rgt, filename, ext, tag_name');
		$this->db->where('lft >',$lft);
		$this->db->where('rgt <',$rgt);

		$this->db->join('users', 'userid = '.$current_content_table.'.author', 'left');
		//LEFT JOIN media ON content.header_image = media.id
		$this->db->join('media', $current_content_table.'.header_image = media.id', 'left');
		$this->db->join('tags', $current_content_table.'.tag_id = tags.id', 'left');

		$this->db->order_by('lft','asc');
		$query = $this->db->get($current_content_table);
		return $query->num_rows() ? $query->result_array() : array();		
	}

	function get_page_positions($page_type, $current_content_table)
	{
		$this->db->select('title, lft');
        $this->db->from($current_content_table);
		$this->db->where('type', $page_type);
		$this->db->join('users', 'userid = '.$current_content_table.'.author', 'left');
		$this->db->order_by("lft", "asc");
		$this->db->limit($num, $offset);
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

	//function get_page_title($page_title, $new_table = NULL, $current_content_table)
	function get_page_title($page_title, $current_content_table)
	{
		// if($new_table != NULL)
		// {
		// 	$current_content_table = $new_table;	
		// }
		
		$this->db->select($current_content_table.'.id, title, friendly_title, content, status, '.$current_content_table.'.date_created, type, userid, users.firstname, users.lastname, gallery, header_image, lft, rgt, filename, ext, tag_name');
		$this->db->from($current_content_table);
		$this->db->where('friendly_title', $page_title);
		$this->db->join('users', 'userid = '.$current_content_table.'.author', 'left');
		//LEFT JOIN media ON content.header_image = media.id
		$this->db->join('media', $current_content_table.'.header_image = media.id', 'left');
		$this->db->join('tags', $current_content_table.'.tag_id = tags.id', 'left');

		$q = $this->db->get();	

		if($q->num_rows() > 0)
		{
			return $q->row();
		}
	}
	
	function insert_page($data, $current_content_table)
	{
		$q = $this->db->insert($current_content_table, $data);
		
		if($q)
		{
			return $this->db->insert_id();
		}
	}
	
	function update_page($data, $id, $current_content_table)
	{
		$this->db->where('id', $id);
		$q = $this->db->update($current_content_table, $data);
		
		if($q)
		{
			return true;
		}
	}
	
	function delete_pages($data)
	{
		foreach ($data as $id)
		{
			$this->MPTtree->delete_node($id);
		}		
		return true;
	}

	function delete_page($id)
	{
		$this->MPTtree->delete_node($id);
		return true;
 	}	
	
	function status_update($data, $id, $current_content_table)
	{
		$this->db->where('id', $id);
		$q = $this->db->update($current_content_table , $data);
		
		if($q)
		{
			return true;
		}		
	}
	
	function get_menu($page, $current_content_table)
	{
		// if($new_table != NULL)
		// {
		// 	$current_content_table = $new_table;	
		// }
		$this->db->select('id, title, friendly_title');
        $this->db->from($current_content_table);
        $this->db->where('lft >', '(SELECT lft FROM '.$current_content_table.' WHERE friendly_title ="'.$page.'")', false);
        $this->db->where('rgt <', '(SELECT rgt FROM '.$current_content_table.' WHERE friendly_title ="'.$page.'")', false);
		$this->db->order_by('lft','asc');
        
         
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