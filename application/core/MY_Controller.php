<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller
{
	
	var $menu;
	var $template_url;

	var $current_content_table;
	var $current_lightbox_table;
	var $edition_title;

	function __construct()
	{
		parent::__construct();
		
		$this->load->helper('text');
		

		$this->template->add_css('template_assets/css/main.css', 'link');
		$this->template->add_js('template_assets/js/vendor/modernizr-2.8.3.min.js', 'import');

		$this->template_url = $this->config->item('template_url');

		$this->load->library('usertracking');
		$this->usertracking->track_this();
	}

	function display_menu($array, $page_title = NULL)
	{
/*
		if(!$array)
		{
			return FALSE;
		}
		
		$i = 0;
		foreach($array as $child)
		{
				$children = (isset($child['children']) && $child['type'] != "blog" ? "has-flyout" : false); // returns true
				$active = ($child['friendly_title'] == $page_title ? "current-menu-parent" : false);
				
				$this->menu .= "<li class=\"$children\">".anchor($child['friendly_title'], $child['title'], array('class' => $active));
				if(isset($child['children']) && $child['type'] != "blog")
				{
					$this->menu .= "<ul>\n";
					$this->display_menu($child['children']);
					$this->menu .= "</ul>\n";
				}
				$this->menu .= "\t</li>\n";
			$i++;
		}
		return $this->menu;
*/
	}
	
	function gallery_widget($number)
	{
		$this->load->model('gallery_model');

		if($query = $this->gallery_model->get_gallery_images($number, 0, 11))
		{
			return $query;
		}
	}
	
	function breadcrumbs($parents)
	{
		$breadcrumbs = array();
			
		foreach($parents as $parent)
		{
			if($parent['lft'] != 1)
			{
				array_push($breadcrumbs, $parent);
			}
		}
						
		if(count($breadcrumbs) != 0)
		{
			return $breadcrumbs;
		}	
	}

	function get_edition_label()
	{
		$this->load->model('archive_model');
		

		// Get the live version which = 2
		if($result = $this->archive_model->get_active_label(1))
		{
			$this->edition_title = $result->edition_title;
			$this->current_content_table = "ed_".$result->edition_label."_content";
			$this->current_lightbox_table = "ed_".$result->edition_label."_lightbox";
		}
		else
		{
			return 'An edition of the Newsletter is not active!';
		}
	}

}

	/*
	 *
	 * MY_Admin_Controller is for the admin area only
	 *
	 */
 
class MY_Admin_Controller extends CI_Controller
{

	var $current_content_table;
	var $current_lightbox_table;
	var $edition_title;

	function __construct()
	{
		parent::__construct();
		$this->is_logged_in();
		$this->get_edition_label();		
	}

	function load_js()
	{
		// Add Javascript files
		$this->jload->add('jquery-1.8.3.min.js');
		$this->jload->add('jquery-ui-1.8.2.custom.min.js');
		$this->jload->add('menu.js');
		$this->jload->add('tinymce/tinymce.min.js');
		$this->jload->add('jquery.ui.nestedSortable.js');
		$this->jload->add('jConfirmAction/jconfirmaction.jquery.js');
		$this->jload->add('cms_tree.js');

		return $this->jload->generate();		
	}

	function get_edition_label()
	{
		$this->load->model('archive_model');
		
		// Get the draft version which = 2
		if($result = $this->archive_model->get_active_label(2))
		{
			$this->edition_title = $result->edition_title;
			$this->current_content_table = "ed_".$result->edition_label."_content";
			$this->current_lightbox_table = "ed_".$result->edition_label."_lightbox";
		}
	}
	
	function is_logged_in()
	{
		$is_logged_in = $this->session->userdata('is_logged_in');

		if(!isset($is_logged_in) || $is_logged_in != TRUE)
		{
			$data['failed'] = "You are not logged in. Please login";
			redirect('admin/login');
		}
	}
}