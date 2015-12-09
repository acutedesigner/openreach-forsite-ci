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
		$this->db->where('type', 'newsletter');
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
		$this->db->select('id, title, status');
        $this->db->from($this->newsletter_table);
		$this->db->where('id', $id);
		$q = $this->db->get();		

		if($q->num_rows() == 1)
		{
			return $q->row();
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
				'type' => 'offer',
				'date_created' => date('Y-m-d H:m:s'),
				'last_edited' => date('Y-m-d H:m:s')
			),
			array(
				'author' => $this->session->userdata('userid'),
				'title' => 'Articles',
				'friendly_title' => url_title('articles', 'dash', TRUE),
				'type' => 'article',
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
			return true;
		}
	}

	/**
	 * Helper function to count the number of newsletters issued
	 * @return int number of issues + 1
	 */
	public function count_nl()
	{
		$this->db->where('type', 'newsletter');
		$this->db->from($this->newsletter_table);
		return $this->db->count_all_results() + 1;
	}

}