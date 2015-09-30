<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| JLoad
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/

// Javascript path
$config['javascript_path'] = base_url().'js/';
// Files to autoload when library loaded
//$config['javascript_autoload'] = array('');
// Groups of files for quick loading
$config['jload_img_zoom'] = array('jquery.fancybox-1.0.0', 'image_zoom'); 


/* End of file jload.php */
/* Location: ./application/config/jload.php */