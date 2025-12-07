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
$route['default_controller'] = 'backend';
$route['404_override'] = '';
$route['translate_uri_dashes'] = false;
$route['verifyCode'] = 'backend/verifyCode';
$route['verifyCodeGet/(:any)'] = 'backend/verifyCode/$1';
$route['api.backend/(:any)'] = 'backend/$1';
$route['avisosprueba/(:any)']['get'] = 'AvisoDeDeuda/prueba/$1'; // avisosprueba/1234
$route['avisosprueba2/(:any)']['get'] = 'AvisoDeDeuda/prueba2/$1'; // avisosprueba2/1234
$route['avisos/(:num)'] = 'AvisoDeDeuda/getDeudaByPagador/$1';
$route['avisodeuda2/(:any)/(:num)/(:num)/(:num)'] = 'AvisoDeDeuda/getDeudaByKey/$1/$2/$3/$4'; // avisodeuda/B/83/2022/8
$route['avisodeuda/(:any)/(:num)/(:num)/(:num)'] = 'AvisoDeDeuda/getDeudaByKey/$1/$2/$3/$4'; // avisodeuda/B/83/2022/8
$route['avisodeuda/(:any)'] = 'AvisoDeDeuda/getDeudaByHash/$1'; // avisodeuda/MzF8MjAyMnw2
$route['avisodeudapdf/(:any)'] = 'AvisoDeDeuda/getFile/$1';
$route['barcode/(:any)'] = 'AvisoDeDeuda/barcode/$1'; // barcode/123456779
$route['testmail/(:any)'] = 'TestMail/test/$1';
$route['testmail'] = 'TestMail/test/';
$route['testmail2/(:any)'] = 'TestMail/test2/$1';
$route['testmail3'] = 'TestMail/test3/';

log_message("error", "routes.php");
//log_message("error", print_r($_SERVER));