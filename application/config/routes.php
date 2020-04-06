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
| example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
| https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
| $route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
| $route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
| $route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples: my-controller/index -> my_controller/index
|   my-controller/my-method -> my_controller/my_method
*/
$route['default_controller'] = 'Auth/login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

/* Auth */
$route['login'] = 'Auth/login';
$route['login_process'] = 'Auth/login_process';
$route['registrasi'] = 'Auth/registration';
$route['register_process'] = 'Auth/register_process';

/* Admin */
$route['admin_side/launcher'] = 'admin/App/launcher';
$route['admin_side/beranda'] = 'admin/App/home';
$route['admin_side/menu'] = 'admin/App/menu';
$route['admin_side/log_activity'] = 'admin/App/log_activity';
$route['admin_side/cleaning_log'] = 'admin/App/cleaning_log';
$route['admin_side/tentang_aplikasi'] = 'admin/App/about';
$route['admin_side/bantuan'] = 'admin/App/helper';

$route['admin_side/admin'] = 'admin/Master/administrator_data';
$route['admin_side/tambah_data_admin'] = 'admin/Master/add_administrator_data';
$route['admin_side/simpan_data_admin'] = 'admin/Master/save_administrator_data';
$route['admin_side/ubah_data_admin/(:any)'] = 'admin/Master/edit_administrator_data/$1';
$route['admin_side/perbarui_data_admin'] = 'admin/Master/update_administrator_data';
$route['admin_side/atur_ulang_kata_sandi_admin/(:any)'] = 'admin/Master/reset_password_administrator_account/$1';
$route['admin_side/hapus_data_admin/(:any)'] = 'admin/Master/delete_administrator_data/$1';

$route['admin_side/departemen'] = 'admin/Master/departemen_data';
$route['admin_side/tambah_data_departemen'] = 'admin/Master/add_departemen_data';
$route['admin_side/simpan_data_departemen'] = 'admin/Master/save_departemen_data';
$route['admin_side/ubah_data_departemen/(:any)'] = 'admin/Master/edit_departemen_data/$1';
$route['admin_side/perbarui_data_departemen'] = 'admin/Master/update_departemen_data';
$route['admin_side/hapus_data_departemen/(:any)'] = 'admin/Master/delete_departemen_data/$1';

$route['admin_side/anggaran'] = 'admin/Master/anggaran_data';
$route['admin_side/tambah_data_anggaran'] = 'admin/Master/add_anggaran_data';
$route['admin_side/simpan_data_anggaran'] = 'admin/Master/save_anggaran_data';
$route['admin_side/detail_data_anggaran/(:any)'] = 'admin/Master/detail_anggaran_data/$1';
$route['admin_side/ubah_data_anggaran/(:any)'] = 'admin/Master/edit_anggaran_data/$1';
$route['admin_side/perbarui_data_anggaran'] = 'admin/Master/update_anggaran_data';
$route['admin_side/hapus_data_anggaran/(:any)'] = 'admin/Master/delete_anggaran_data/$1';

$route['admin_side/kegiatan'] = 'admin/Master/kegiatan_data';
$route['admin_side/tambah_data_kegiatan'] = 'admin/Master/add_kegiatan_data';
$route['admin_side/simpan_data_kegiatan'] = 'admin/Master/save_kegiatan_data';
$route['admin_side/detail_data_kegiatan/(:any)'] = 'admin/Master/detail_kegiatan_data/$1';
$route['admin_side/ubah_data_kegiatan/(:any)'] = 'admin/Master/edit_kegiatan_data/$1';
$route['admin_side/perbarui_data_kegiatan'] = 'admin/Master/update_kegiatan_data';
$route['admin_side/hapus_data_kegiatan/(:any)'] = 'admin/Master/delete_kegiatan_data/$1';

$route['admin_side/output'] = 'admin/Master/output_data';
$route['admin_side/tambah_data_output'] = 'admin/Master/add_output_data';
$route['admin_side/simpan_data_output'] = 'admin/Master/save_output_data';
$route['admin_side/detail_data_output/(:any)'] = 'admin/Master/detail_output_data/$1';
$route['admin_side/ubah_data_output/(:any)'] = 'admin/Master/edit_output_data/$1';
$route['admin_side/perbarui_data_output'] = 'admin/Master/update_output_data';
$route['admin_side/hapus_data_output/(:any)'] = 'admin/Master/delete_output_data/$1';

$route['admin_side/sub_output'] = 'admin/Master/sub_output_data';
$route['admin_side/tambah_data_sub_output'] = 'admin/Master/add_sub_output_data';
$route['admin_side/simpan_data_sub_output'] = 'admin/Master/save_sub_output_data';
$route['admin_side/detail_data_sub_output/(:any)'] = 'admin/Master/detail_sub_output_data/$1';
$route['admin_side/ubah_data_sub_output/(:any)'] = 'admin/Master/edit_sub_output_data/$1';
$route['admin_side/perbarui_data_sub_output'] = 'admin/Master/update_sub_output_data';
$route['admin_side/hapus_data_sub_output/(:any)'] = 'admin/Master/delete_sub_output_data/$1';

$route['admin_side/komponen'] = 'admin/Master/komponen_data';
$route['admin_side/tambah_data_komponen'] = 'admin/Master/add_komponen_data';
$route['admin_side/simpan_data_komponen'] = 'admin/Master/save_komponen_data';
$route['admin_side/detail_data_komponen/(:any)'] = 'admin/Master/detail_komponen_data/$1';
$route['admin_side/ubah_data_komponen/(:any)'] = 'admin/Master/edit_komponen_data/$1';
$route['admin_side/perbarui_data_komponen'] = 'admin/Master/update_komponen_data';
$route['admin_side/hapus_data_komponen/(:any)'] = 'admin/Master/delete_komponen_data/$1';

$route['admin_side/sub_komponen'] = 'admin/Master/sub_komponen_data';
$route['admin_side/tambah_data_sub_komponen'] = 'admin/Master/add_sub_komponen_data';
$route['admin_side/simpan_data_sub_komponen'] = 'admin/Master/save_sub_komponen_data';
$route['admin_side/detail_data_sub_komponen/(:any)'] = 'admin/Master/detail_sub_komponen_data/$1';
$route['admin_side/ubah_data_sub_komponen/(:any)'] = 'admin/Master/edit_sub_komponen_data/$1';
$route['admin_side/perbarui_data_sub_komponen'] = 'admin/Master/update_sub_komponen_data';
$route['admin_side/hapus_data_sub_komponen/(:any)'] = 'admin/Master/delete_sub_komponen_data/$1';

$route['admin_side/rkakl'] = 'admin/Master/rkakl_data';
$route['admin_side/perbarui_data_rkakl'] = 'admin/Master/update_rkakl_data';

$route['admin_side/pendapatan'] = 'admin/Report/pendapatan';

$route['admin_side/penggunaan_anggaran'] = 'admin/Report/penggunaan_anggaran';
$route['admin_side/detail_belanja/(:any)'] = 'admin/Report/detail_belanja/$1';
$route['admin_side/hapus_data_belanja/(:any)'] = 'admin/Report/hapus_data_belanja/$1';

/* REST API */
$route['api'] = 'Rest_server/documentation';

/*
| -------------------------------------------------------------------------
| Sample REST API Routes
| -------------------------------------------------------------------------
*/
$route['api/example/users/(:num)'] = 'api/example/users/id/$1'; // Example 4
$route['api/example/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/example/users/id/$1/format/$3$4'; // Example 8