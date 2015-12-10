
<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Migration extends MY_Controller {

	private $content_table = 'newsletters';

	public function __construct()
	{
		parent::__construct();
		$this->load->library('nested_set');
		$this->nested_set->setControlParams($this->content_table);		

	}

	public function index()
	{
		// $this->db->select('*');
		// $this->db->from('ed_october2015_content');

		// $q = $this->db->get();	

		// if($q->num_rows() > 0)
		// {
		// 	$results = $q->result_array();

		// 	foreach($results as $data)
		// 	{
		// 		$parent = $this->nested_set->getNodeWhere('id = "3"');

		// 		$post = array(
		// 			'author' => 1,
		// 			'title' => $data['title'],
		// 			'friendly_title' => url_title($data['title'], 'dash', TRUE),
		// 			'content' => $data['content'],
		// 			'header_image' => $data['header_image'],
		// 			'status' => 2,
		// 			'parent_id' => 3,
		// 			'type' => 'article',
		// 			'date_created' => $data['date_created']
		// 		);


		// 		$child = $this->nested_set->appendNewChild($parent, $post);	
		// 		if($child)
		// 		{
		// 			echo $this->db->insert_id();
		// 		}

		// 	}
		// }

	}
}