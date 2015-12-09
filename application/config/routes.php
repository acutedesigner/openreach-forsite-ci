<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "home";
$route['404_override'] = '';

$route['admin'] = 'admin/login';

$route['archive'] = 'archive/archive';
$route['archive/login'] = 'archive/login';
$route['archive/current-offers/(:any)'] = 'archive/archive/current_offers/$1';
$route['archive/(:any)'] = 'archive/archive/$1';
$route['archive/logout'] = 'archive/login/logout';

$route['media/(:any)'] = 'media/$1';

/*
$route['news/(:any)'] = '/news/index/$1';
$route['gallery/(:any)'] = '/gallery/index/$1';
$route['contact/(:any)'] = '/contact/$1';
*/

$route['current-offers'] = 'current_offers/index';

$route['newtree'] = 'newtree/index';


$route['^(?!admin|archive|media|news|gallery|blog).*'] = "content/index/$0";
/* End of file routes.php */
/* Location: ./application/config/routes.php */