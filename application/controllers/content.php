<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('content_model');
		$this->load->model('newsletters_model');
	}


	/**
	 * index   Generates the content and menus to be displayed on the page
	 * @param  string $issue         The current issue being requested
	 * @param  string $content_type  The type of content being requested
	 * @param  string $article_title The title of article being 
	 * @return Content returned to the template generator
	 */
	function index($issue = NULL, $content_type = NULL, $article_title = NULL)
	{

		$latest_issue_data = $this->newsletters_model->get_latest_nl();

		// We need to validate $issue
		if($issue != NULL && preg_match('/^issue\-[0-9]+$/', $issue))
		{

			$issue = explode("-", $issue);
			$issue_number = $issue[1];	
			$issue_data = $this->newsletters_model->get_issue($issue_number);

			if($issue_data && $article_title != NULL)
			{
				$article_data = $this->content_model->get_page_title($article_title);
			}
			elseif($issue_data)
			{
				$section_data = $this->newsletters_model->get_first_child((array)$issue_data);
				$article_data = $this->newsletters_model->get_first_child((array)$section_data);
			}
			else
			{
				redirect('/');
			}
		}
		else
		{
			$issue_data = $latest_issue_data;
			$section_data = $this->newsletters_model->get_first_child((array)$issue_data);
			$article_data = $this->newsletters_model->get_first_child((array)$section_data);
		}

		$get_sidebar_menu = $this->get_sidebar_menu($issue_data->id);

		foreach ($get_sidebar_menu as $key => $value) {
			$data[$key] = (object)$value;
		}

		$get_latest_issue_menu = $this->get_latest_issue_menu($latest_issue_data->id);

		foreach ($get_latest_issue_menu as $key => $value) {
			$data[$key] = (object)$value;
		}

		//	Return Data
		//	==================================

		$data['edition_title'] = $issue_data->title;
		$data['issue'] = $issue_data->issue;
		$data['previous_issues_menu'] = $this->previous_issues_menu();

		// Article elements
		$data['page'] = $article_data;		
		$data['title'] = $article_data->title;
		$data['current_article'] = $article_data->id;				

		// Render the page
		$this->template->write_view('header', 'template/header', $data);
		$this->template->write_view('content', 'template/index', $data);
		$this->template->write_view('sidebar', 'template/sidebar', $data);
		$this->template->write_view('footer', 'template/footer', $data);
		$this->template->render();

	}


	/**
	 * get_latest_issue_menu	Gets a list of articles for most recent issue
	 * @param  int $issue_id    The id of the current issue being displayed
	 * @return object           Array of menu items
	 */
	public function get_latest_issue_menu($issue_id)
	{
		// Get the children articles of the issue being called		
		$issue_children = $this->newsletters_model->get_children($issue_id);

		// The varable that will be returned
		$newsletter_sections = NULL;

		// Extract the sections Articles | Current Offers | Previous issues
		foreach ($issue_children as $child) {

			$clean_title = str_replace('-', '_', $child['friendly_title']);

			// Full array
			$newsletter_sections[$clean_title.'_array'] = $child;

			// Build the menu array for latest issue
			$section = $this->newsletters_model->get_sub_tree($child);

			//$this->printme($child);						
			if(!empty($section['result_array']))
			{
				// Issue data merged with section
				$issue = $this->newsletters_model->get_ancestor($child);
				$newsletter_sections['articles_array'] = $section['result_array'];
				$newsletter_sections['latest_issue'] = $issue['issue'];
			}
		}		
		return $newsletter_sections;
	}

	/**
	 * get_sidebar_menu			Gets a list of the displayed issues articles
	 * @param  int $issue_id    The id of the current issue being displayed
	 * @return object           Array of menu items
	 */
	public function get_sidebar_menu($issue_id)
	{
		// Get the children articles of the issue being called		
		$issue_children = $this->newsletters_model->get_children($issue_id);

		// The varable that will be returned
		$newsletter_sections = NULL;

		// Extract the sections Articles | Current Offers | Previous issues
		foreach ($issue_children as $child) {

			$clean_title = str_replace('-', '_', $child['friendly_title']);

			// For sidebar menus
			$newsletter_sections['sidebar_'.$clean_title] = $this->newsletters_model->get_children($child['id']);

		}
		return $newsletter_sections;
	}

	/**
	 * previous_issues_menu get a list of all previous newsletter articles
	 * @return object returns object tree
	 */
	public function previous_issues_menu()
	{
		if($results = $this->newsletters_model->previous_issues_menu())
		{
			$article = NULL;

			foreach($results as $result)
			{
				$children = $this->newsletters_model->get_sub_tree($result);
				$article[] = array(
					'ancestor' => $this->newsletters_model->get_ancestor($result),
					'articles' => $children['result_array']
				);
			}
			// $this->printme($article);
			return $article;	
		}
	}

}