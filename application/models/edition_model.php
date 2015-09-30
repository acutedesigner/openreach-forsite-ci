<?php

class Edition_model extends CI_Model {

    function __construct(){
        parent::__construct();
		$this->load->dbforge();		
    }


	function retrieve($limit = NULL, $start = NULL, $id = NULL)
	{
		$this->db->select('*');
		$this->db->from('table');
		if($id != NULL)
		{
			$this->db->where("column",$id);
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

		// So here we must create three tables

		// Edition Lightbox link

  		// Create the fields. Primary key automatically created
  		$this->dbforge->add_field('id');
  		$this->dbforge->add_field("gallery_id int(10) NOT NULL");
  		$this->dbforge->add_field("image_id int(10) NOT NULL");

		// Create the table		
		$lightbox_table_name = 'ed_'.$data.'_lightbox';
  		$this->dbforge->create_table($lightbox_table_name, TRUE);
		
		// Edition content table
		
  		// Create the fields. Primary key automatically created
  		$this->dbforge->add_field('id');
  		$this->dbforge->add_field("author int(50) DEFAULT NULL");
  		$this->dbforge->add_field("title text");
  		$this->dbforge->add_field("friendly_title varchar(255) NOT NULL");
  		$this->dbforge->add_field("content longtext");
  		$this->dbforge->add_field("tag_id int(4) DEFAULT NULL");
  		$this->dbforge->add_field("date_created date DEFAULT NULL");
  		$this->dbforge->add_field("last_edited datetime DEFAULT NULL");
  		$this->dbforge->add_field("type varchar(20) NOT NULL");
  		$this->dbforge->add_field("status int(11) DEFAULT NULL");
  		$this->dbforge->add_field("gallery int(3) NOT NULL");
  		$this->dbforge->add_field("header_image int(4) DEFAULT NULL");
  		$this->dbforge->add_field("nested int(1) NOT NULL");
		$this->dbforge->add_field("locked int(1) DEFAULT NULL");
		$this->dbforge->add_field("parent int(5) DEFAULT NULL");
		$this->dbforge->add_field("keypath varchar(200) DEFAULT NULL");
		$this->dbforge->add_field("lft int(10) NOT NULL");
		$this->dbforge->add_field("rgt int(10) NOT NULL");
		  		
		// Create the table		
		$content_table_name = 'ed_'.$data.'_content';

		if($this->dbforge->create_table($content_table_name, TRUE))
		{
			// populate the content table
			$sql = "INSERT INTO ".$content_table_name." VALUES(NULL,1,'Home Page','home-page','<h1>Placeholder for the homepage</h1>',NULL,'2011-12-26','2015-02-24 15:02:56','page',1,0,NULL,0,1,NULL,'',1,10),
(NULL,1,'Current Offers','current-offers','<p>Currents Offers link</p>',NULL,'2015-06-22','2015-06-22 17:06:12','page',1,0,NULL,1,NULL,NULL,NULL,4,5),
(NULL,1,'Newsletter Articles','newsletter-articles','<p>Newsletter Articles Link</p>',NULL,'2015-06-22','2015-06-22 17:06:31','page',1,0,NULL,1,NULL,NULL,NULL,2,3),
(NULL,1,'Footer Links','footer-links','<p>Footer Links</p>',NULL,'2015-06-23','2015-06-23 08:06:00','page',0,0,NULL,1,NULL,NULL,NULL,6,9),
(NULL,1,'Cookie Policy','cookie-policy','<h2>Cookies and how they benefit you</h2>\r\n<p>Our website uses cookies, as almost all websites do, to help provide you with the best experience we can.\r\nCookies are small text files that are placed on your computer or mobile phone when you browse websites.\r\n</p>\r\n<p>Our cookies help us:\r\n</p>\r\n<ul>\r\n	<li>Improve the speed/security of the site</li>\r\n</ul>\r\n<p>We do not use cookies to:\r\n</p>\r\n<ul>\r\n	<li>Collect any personally identifiable information (without your express permission)</li>\r\n	<li>Collect any sensitive information (without your express permission)</li>\r\n	<li>Pass personally identifiable data to third parties</li>\r\n	<li>Pay sales commissions</li>\r\n</ul>\r\n<p>You can learn more about all the cookies we use below.\r\n</p>\r\n<h2>Granting us permission to use cookies</h2>\r\n<p>If the settings on your software that you are using to view this website (your browser) are adjusted to accept cookies we take this, and your continued use of our website, to mean that you are fine with this.  Should you wish to remove or not use cookies from our site you can learn how to do this below, however doing so will likely mean that our site will not work as you would expect.\r\n</p>\r\n<h2>More about our cookies</h2>\r\n<p><strong>Google Analytics</strong><br>\r\n	We use Google Analytics to see how people use our website. This helps us improve it. The data we have is anonymised.<br>\r\n	<a href=\"http://www.google.com/policies/privacy/\" target=\"_blank\">Google\'s Privacy Policy</a>\r\n</p>\r\n<p><strong>ci_session</strong> is simply an array containing the following information:\r\n</p>\r\n<ul>\r\n	<li>The user\'s unique Session ID (this is a statistically random string with very strong entropy, hashed with MD5 for portability, and regenerated (by default) every five minutes)</li>\r\n	<li>The user\'s IP Address</li>\r\n	<li>The user\'s User Agent data (the first 120 characters of the browser data string)</li>\r\n	<li>The \"last activity\" time stamp.</li>\r\n</ul>\r\n<p>This cookie is generated by the security features of this website to protect users against Cross-site request forgery (CSRF). You can learn more about this threat on <a href=\"https://en.wikipedia.org/wiki/Cross-site_request_forgery\" target=\"_blank\">Wikipedia.</a>\r\n</p>\r\n<h2>Turning cookies off</h2>\r\n<p>You can usually switch cookies off by adjusting your browser settings to stop it from accepting cookies (Learn how <a href=\" http://www.attacat.co.uk/resources/cookies/how-to-ban\" target=\"_blank\">here</a>).  Doing so however will likely limit the functionality of ours and a large proportion of the world\'s websites as cookies are a standard part of most modern websites.\r\n</p>\r\n<p>It may be that your concerns around cookies relate to so called \"spyware\".  Rather than switching off cookies in your browser you may find that anti-spyware software achieves the same objective by automatically deleting cookies considered to be invasive.  Learn more about <a href=\"http://www.attacat.co.uk/resources/cookies/how-to-control-your-online-privacy\" target=\"_blank\">managing cookies with antispyware software</a>.\r\n</p>',0,'2015-06-23','2015-08-28 10:08:54','page',1,0,0,1,NULL,NULL,NULL,7,8)";
			
			$this->db->query($sql);
			
			return true;
		}
				
	}	

	function update($old_label, $new_label)
	{
		// This function alters the tables for the edition
		
		$old_lightbox_table_name = 'ed_'.$old_label.'_lightbox';
		$new_lightbox_table_name = 'ed_'.$new_label.'_lightbox';		
		$this->dbforge->rename_table($old_lightbox_table_name, $new_lightbox_table_name);

		$old_content_table_name = 'ed_'.$old_label.'_content';
		$new_content_table_name = 'ed_'.$new_label.'_content';
				
		$this->dbforge->rename_table($old_content_table_name, $new_content_table_name);
		
		return true;
				
	}	

	function delete($id)
	{
		$this->db->where('id', $id);
		$q = $this->db->delete('content');
				
		if($q)
		{
			return true;
		}		
	}
}