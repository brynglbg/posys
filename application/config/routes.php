<?php
$route['default_controller'] = 'app/view';
// ===== NEW BELOW HERE =====
// auth
$route['auth/logout'] = 'auth/logout';
$route['auth/login'] = 'auth/login';
$route['auth'] = 'auth';
// ajax
$route['ajax/(:any)'] = 'ajax/$1';
// 
$route['pdf_print/(:any)/(:any)'] = 'app/pdf_print/$1/$2';
// ===== MUST BE LAST =====
$route['(:any)/(:any)'] = 'app/view/$1/$2';
$route['(:any)'] = 'app/view/$1';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
