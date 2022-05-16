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
|	https://codeigniter.com/userguide3/general/routing.html
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
$route['default_controller'] = 'Consultas';

$route['translate_uri_dashes'] = FALSE;

$route['contactus'] = 'ContactUs';

$route['register'] = 'User/register';
$route['login']    = 'User/login';
$route['logout']   = 'User/logout';

$route['doctors']               = 'Medicos';
$route['doctors/add']           = 'Medicos/add';
$route['doctors/edit/(:num)']   = 'Medicos/edit/$1';
$route['doctors/delete/(:num)'] = 'Medicos/delete/$1';
$route['doctors/(:num)']        = "Medicos/details/$1";

$route['nurses']               = 'Enfermeiros';
$route['nurses/add']           = 'Enfermeiros/add';
$route['nurses/edit/(:num)']   = 'Enfermeiros/edit/$1';
$route['nurses/delete/(:num)'] = 'Enfermeiros/delete/$1';
$route['nurses/(:num)']        = "Enfermeiros/details/$1";

$route['patients']               = 'Utentes';
$route['patients/add']           = 'Utentes/add';
$route['patients/edit/(:num)']   = 'Utentes/edit/$1';
$route['patients/delete/(:num)'] = 'Utentes/delete/$1';
$route['patients/(:num)']        = "Utentes/details/$1";

$route['consultations']               = "Consultas/all";
$route['consultations/add']           = 'Consultas/add';
$route['consultations/edit/(:num)']   = 'Consultas/edit/$1';
$route['consultations/delete/(:num)'] = 'Consultas/delete/$1';
$route['consultations/(:num)']        = "Consultas/details/$1";
