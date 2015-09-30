<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Demo Extends CI_Controller{
	
	// Check that the user is always logged in
	function __construct()
	{
		parent::__construct();
		//$this->is_logged_in();		
		$this->jload->add('menu.js');
		$this->jload->add('jquery-ui-1.8.2.custom.min.js');
		$this->jload->add('jquery.ui.nestedSortable.js');
		$this->load->model('MPTtree');
		$this->MPTtree->set_table('content');
	}

	function index()
	{
		$tree = $this->MPTtree->tree2array();
				
		$data['children'] = $tree;
		$data['javascript'] = $this->jload->generate();
		$this->load->view('admin/demo', $data);		
	}
	
	function move_node(){
		$node = $this->MPTtree->get_node($_POST['selitems']);
		$target = $this->MPTtree->get_node($_POST['new_dir']);
		switch($_POST['point']){
			case 'append':
				// add as child
				$this->MPTtree->move_node_append($node['lft'],$target['lft']);
			break;
			case 'above':
				// add above new_dir
				$this->MPTtree->move_node_before($node['lft'],$target['lft']);
			break;
			case 'below':
				// add below
				$this->MPTtree->move_node_after($node['lft'],$target['lft']);
			break;
		}
		echo '{success: \'true\'}';
	}

}