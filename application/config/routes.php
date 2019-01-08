<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['admin'] = 'admin/admin/login';
$route['api/target/info/(:num)'] 			= 'api/target/info/$1';
$route['api/target/batch/(:num)'] 			= 'api/target/batch/$1';
$route['api/product/info/(:num)'] 			= 'api/product/info/$1';
$route['api/product/applyinfo/(:num)'] 		= 'api/product/applyinfo/$1';
$route['api/product/cancel/(:num)'] 		= 'api/product/cancel/$1';
$route['api/agreement/info/(:any)'] 		= 'api/agreement/info/$1';
$route['api/notitication/info/(:num)'] 		= 'api/notitication/info/$1';

$route['api/repayment/info/(:num)'] 		= 'api/repayment/info/$1';
$route['api/repayment/prepayment/(:num)'] 	= 'api/repayment/prepayment/$1';
$route['api/repayment/contract/(:num)'] 	= 'api/repayment/contract/$1';

$route['api/subloan/applyinfo/(:num)'] 		= 'api/subloan/applyinfo/$1';
$route['api/subloan/cancel/(:num)'] 		= 'api/subloan/cancel/$1';
$route['api/subloan/preapply/(:num)'] 		= 'api/subloan/preapply/$1';

$route['api/recoveries/info/(:num)'] 		= 'api/recoveries/info/$1';
$route['api/transfer/info/(:num)'] 			= 'api/transfer/info/$1';


$route['api/v2/target/info/(:num)'] 		= 'api/v2/target/info/$1';
$route['api/v2/target/batch/(:num)'] 		= 'api/v2/target/batch/$1';
$route['api/v2/product/info/(:num)'] 		= 'api/v2/product/info/$1';
$route['api/v2/product/applyinfo/(:num)'] 	= 'api/v2/product/applyinfo/$1';
$route['api/v2/product/cancel/(:num)'] 		= 'api/v2/product/cancel/$1';
$route['api/v2/agreement/info/(:any)'] 		= 'api/v2/agreement/info/$1';
$route['api/v2/notitication/info/(:num)'] 	= 'api/v2/notitication/info/$1';

$route['api/v2/repayment/info/(:num)'] 		= 'api/v2/repayment/info/$1';
$route['api/v2/repayment/prepayment/(:num)']= 'api/v2/repayment/prepayment/$1';
$route['api/v2/repayment/contract/(:num)'] 	= 'api/v2/repayment/contract/$1';

$route['api/v2/subloan/applyinfo/(:num)'] 	= 'api/v2/subloan/applyinfo/$1';
$route['api/v2/subloan/cancel/(:num)'] 		= 'api/v2/subloan/cancel/$1';
$route['api/v2/subloan/preapply/(:num)'] 	= 'api/v2/subloan/preapply/$1';

$route['api/v2/recoveries/info/(:num)'] 	= 'api/v2/recoveries/info/$1';
$route['api/v2/transfer/info/(:num)'] 		= 'api/v2/transfer/info/$1';
$route['api/v2/judicialperson/agent/(:num)']= 'api/v2/judicialperson/agent/$1';
$route['api/v2/certifications/(:any)'] 		= 'api/v2/certifications/$1';