<?php

class content_model Extends CI_Model{

	private $content_table = 'newsletters';

	function __construct()
	{
		parent::__construct();
		$this->load->library('nested_set');
		$this->nested_set->setControlParams($this->content_table);		
	}


	public function get_page($page_id)
	{
		$this->db->select($this->content_table.'.id, title, content, tag_id, status, '.$this->content_table.'.date_created, type, userid, nested, users.firstname, users.lastname, name, gallery, parent_id, header_image, lft');
		$this->db->from($this->content_table);
		$this->db->where($this->content_table.'.id', $page_id);
		$this->db->join('users', 'userid = '.$this->content_table.'.author', 'left');
		$this->db->join('galleries', 'galleries.id = '.$this->content_table.'.gallery', 'left');

		$q = $this->db->get();	

		if($q->num_rows() > 0)
		{
			return $q->row();
		}
	}

	public function insert_page($data)
	{		
		$parent = $this->nested_set->getNodeWhere('id = '.$data['parent_id']);
		$child = $this->nested_set->appendNewChild($parent,$data);	
		if($child)
		{
			return $this->db->insert_id();
		}
	}
	
	public function update_page($page_id, $data)
	{
		$this->db->where('id', $page_id);
		$q = $this->db->update($this->content_table, $data);
		
		if($q)
		{
			return true;
		}
	}

	public function delete_page($page_id)
	{
		$node = $this->nested_set->getNodeWhere('id = '.$page_id);

		$deleted_node = $this->nested_set->deleteNode($node);

		if($deleted_node)
		{
			return true;
		}

	}

/**--------------------------**/	

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

	// function get_page($page_id, $current_content_table)
	// {
	// 	$this->db->select($current_content_table.'.id, title, content, tag_id, status, '.$current_content_table.'.date_created, type, userid, nested, users.firstname, users.lastname, name, gallery, header_image, lft');
	// 	$this->db->from($current_content_table);
	// 	$this->db->where($current_content_table.'.id', $page_id);
	// 	$this->db->join('users', 'userid = '.$current_content_table.'.author', 'left');
	// 	$this->db->join('galleries', 'galleries.id = '.$current_content_table.'.gallery', 'left');

	// 	$q = $this->db->get();	

	// 	if($q->num_rows() > 0)
	// 	{
	// 		return $q->row();
	// 	}
	// }

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
	function get_page_title($page_title)
	{
		// if($new_table != NULL)
		// {
		// 	$current_content_table = $new_table;	
		// }
		
		$this->db->select($this->content_table.'.id, title, friendly_title, content, status, '.$this->content_table.'.date_created, type, userid, users.firstname, users.lastname, gallery, header_image, lft, rgt, filename, ext, tag_name');
		$this->db->from($this->content_table);
		$this->db->where('friendly_title', $page_title);
		$this->db->join('users', 'userid = '.$this->content_table.'.author', 'left');
		//LEFT JOIN media ON content.header_image = media.id
		$this->db->join('media', $this->content_table.'.header_image = media.id', 'left');
		$this->db->join('tags', $this->content_table.'.tag_id = tags.id', 'left');

		$q = $this->db->get();	

		if($q->num_rows() > 0)
		{
			return $q->row();
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

	// function delete_page($id)
	// {
	// 	$this->MPTtree->delete_node($id);
	// 	return true;
 // 	}	
	
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