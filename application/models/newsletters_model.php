<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Newsletters_model Extends CI_Model{

	private $newsletter_table = 'newsletters';

	function __construct()
	{
		parent::__construct();
		$this->load->library('nested_set');
		$this->nested_set->setControlParams($this->newsletter_table);		
	}

	/**
	 * Function to retrieve multiple rows from DB
	 * @param  int $id     id of specific newsletter
	 * @param  int $num    number of items for page view
	 * @param  int $offset amount to offset. Or page number of 
	 * @return array         [description]
	 */
	public function get_all_nl($id = NULL, $num = NULL, $offset = NULL)
	{
		$this->db->select('id, title, status');
        $this->db->from($this->newsletter_table);
		$this->db->where('type', 'newsletters');
		$this->db->order_by('id', 'desc');
		// $this->db->limit($num, $offset);
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

	/**
	 * Retrieve single newsletter content from db
	 * @param  int $id id of the requested newsletter
	 * @return object     [description]
	 */
	public function get_nl($id)
	{
		$this->db->select('id, title, status, issue');
        $this->db->from($this->newsletter_table);
		$this->db->where('id', $id);
		$q = $this->db->get();		

		if($q->num_rows() == 1)
		{
			return $q->row_object();
		}

	}

	public function get_latest_nl()
	{
		$this->db->select('id, title, status, issue, lft, rgt');
        $this->db->from($this->newsletter_table);
		$this->db->where('type', 'newsletters');
		$this->db->where('status', 1);
		$this->db->order_by('id', 'desc');
		$this->db->limit(1, 0);
		$q = $this->db->get();		
 
		if($q->num_rows() == 1)
		{
			return $q->row_object();
		}

	}

	/**
	 * Creates a new issue of the newsletter
	 * @param  array $post_data data from the form
	 * @return int   id of the new newsletter
	 */
	public function create_new_nl($post_data)
	{
		$parentNode = $this->nested_set->insertNewTree($post_data);

		$parent_id = $this->db->insert_id();

		$sections_array = array(

			array(
				'author' => $this->session->userdata('userid'),
				'title' => 'Current Offers',
				'friendly_title' => url_title('Current Offers', 'dash', TRUE),
				'type' => 'offers',
				'status' => $post_data['status'],
				'date_created' => date('Y-m-d H:m:s'),
				'last_edited' => date('Y-m-d H:m:s')
			),
			array(
				'author' => $this->session->userdata('userid'),
				'title' => 'Articles',
				'friendly_title' => url_title('articles', 'dash', TRUE),
				'type' => 'articles',
				'status' => $post_data['status'],
				'date_created' => date('Y-m-d H:m:s'),
				'last_edited' => date('Y-m-d H:m:s')
			)

		);

		foreach ($sections_array as $section ) {
			$this->nested_set->appendNewChild($parentNode, $section);
		}
		
		return $parent_id;		
	}

	/**
	 * Update an existing newsletter issue
	 * @param  array $data array of updated content
	 * @param  int $id   id of the the issue to be updated
	 * @return boolean
	 */
	public function update_nl($data, $id)
	{
		$this->db->where('id', $id);
		$q = $this->db->update($this->newsletter_table, $data);
		
		if($q)
		{
			// Update the statuses
			$this->update_status($id, array( 'status' => $data['status']));
			return true;
		}
	}

	public function update_status($parent_id, $status)
	{
		$this->db->where('parent_id', $parent_id);
		$q = $this->db->update($this->newsletter_table, $status);

		if($q)
		{
			return true;
		}
	}

	/**
	 * Helper function to count the number of newsletters issued
	 * @return int number of issues + 1
	 */
	public function count_nl()
	{
		$this->db->where('type', 'newsletters');
		$this->db->from($this->newsletter_table);
		$count = (int)$this->db->count_all_results();
		return  $count + 1;
	}

	public function get_issue($issue)
	{
		if(count($this->nested_set->getNodeWhere('issue = '.$issue)) > 0)
		{
			return (object)$this->nested_set->getNodeWhere('issue = '.$issue);
		}				
	}

	public function get_first_child($parent_node)
	{
		// Also get the image!!
		$first_child = $this->nested_set->getFirstChild($parent_node);

		$this->db->select('filename, ext');
		$this->db->from('media');
		$this->db->where('id', $first_child['header_image']);
		$q = $this->db->get();
		if($q->num_rows() > 0)
		{
			return (object)array_merge($first_child, $q->row_array());
		}
		else
		{
			// No header image
			return (object)$first_child;
		}
	}

	public function get_children($parent_id)
	{
		//return $this->nested_set->getNodesWhere('parent_id = '.$parent_id);		

		$this->db->select($this->newsletter_table.'.id, title, friendly_title, parent_id, nested, content, status, '.$this->newsletter_table.'.date_created, type, userid, users.firstname, users.lastname, gallery, header_image, lft, rgt, filename, ext, tag_name');
		$this->db->from($this->newsletter_table);
		$this->db->where('parent_id', $parent_id);
		$this->db->join('users', 'userid = '.$this->newsletter_table.'.author', 'left');
		$this->db->join('media', $this->newsletter_table.'.header_image = media.id', 'left');
		$this->db->join('tags', $this->newsletter_table.'.tag_id = tags.id', 'left');
		$this->db->order_by('lft', 'asc');

		$q = $this->db->get();    	
 
		if($q->num_rows() > 0)
		{
			return $q->result_array();
		}

	}

	public function get_section_children($parent_id, $section)
	{
		$this->db->select('id, title, status, issue, parent_id');
        $this->db->from($this->newsletter_table);
		$this->db->where('id', $parent_id);
		$this->db->where('id', $id);
		$q = $this->db->get();		

		if($q->num_rows() == 1)
		{
			return $q->row();
		}

	}

	public function previous_issues_menu()
	{

		$this->db->select('*');
        $this->db->from($this->newsletter_table);
		$this->db->where('type', 'articles');
		$this->db->where('status', 1);
		$this->db->order_by('id', 'desc');
		$q = $this->db->get();		
 
		if($q->num_rows() > 1)
		{
			// To remove the latest active newsletter
			$input = $q->result_array();
			$results = array_slice($input, 1);
			return $results;
		}

	}

	public function get_sub_tree($node)
	{
		return $this->nested_set->getTreePreorder($node, true);
	}

	public function get_ancestor($currnode)
	{
		return $this->nested_set->getAncestor($currnode);
	}

	public function delete_nl($newsletter_id)
	{
		// Get node
		$node = $this->nested_set->getNodeWhere('id = "'.$newsletter_id.'"');
		// delete tree
		return $this->nested_set->deleteNode($node);
	}

	public function move_node($data)
	{
	
		// First we get the node and target node objects
		$node = $this->nested_set->getNodeWhere('id = "'.$data['node'].'"'); 
		$target = $this->nested_set->getNodeWhere('id = "'.$data['node_target'].'"');

		// then based on the method param in the data array run that method
		if('next' == $data['method'])
		{
			return $this->nested_set->setNodeAsNextSibling($node, $target);
		}

		if('prev' == $data['method'])
		{
			return $this->nested_set->setNodeAsPrevSibling($node, $target);
		}
	
	}

	public function show_tree()
	{
		echo $this->nested_set->getTreeAsHTML(array('title', 'lft', 'rgt'));
	}
}