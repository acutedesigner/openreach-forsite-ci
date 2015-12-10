<?php

class Preview_model extends CI_Model {

	private $newsletter_table = 'newsletters';    
    
    function __construct(){
        parent::__construct();
		$this->load->library('nested_set');
		$this->nested_set->setControlParams($this->newsletter_table);		
    }

    public function get_drafts()
    {
		$this->db->select('*');
		$this->db->from($this->newsletter_table);
		$this->db->where('type', 'newsletters');    	
		$this->db->where('status', 2);
		$this->db->order_by('id', 'desc');
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

	public function get_issue($issue)
	{
		return $this->nested_set->getNodeWhere('issue = '.$issue);				
	}

	public function get_page($title)
	{
		$this->db->select($this->newsletter_table.'.id, title, friendly_title, content, status, '.$this->newsletter_table.'.date_created, type, userid, users.firstname, users.lastname, gallery, header_image, lft, rgt, filename, ext, tag_name');
		$this->db->from($this->newsletter_table);
		$this->db->where('friendly_title', $title);
		$this->db->join('users', 'userid = '.$this->newsletter_table.'.author', 'left');
		$this->db->join('media', $this->newsletter_table.'.header_image = media.id', 'left');
		$this->db->join('tags', $this->newsletter_table.'.tag_id = tags.id', 'left');

		$q = $this->db->get();    	
 
		if($q->num_rows() > 0)
		{
			return $q->row();
		}
	}

	public function get_first_child($issue_number)
	{
		$issue_node = $this->nested_set->getNodeWhere('issue = '.$issue_number);

		$article_node = $this->nested_set->getNodeWhere('type = "articles" AND parent_id = "'.$issue_node['id'].'"');

		$first_child = $this->nested_set->getFirstChild($article_node);
		$this->db->select('filename, ext');
		$this->db->from('media');
		$this->db->where('id', $first_child['header_image']);
		$q = $this->db->get();
		if($q->num_rows() > 0)
		{
			return (object)array_merge($first_child, $q->row_array());
		}

	}

    /**
     * OLD FUNCTIONS ------------------------------------------
     */
	
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