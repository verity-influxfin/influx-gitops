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
$route['api/v2/combination/info/(:num)'] 		= 'api/v2/combination/info/$1';
$route['api/v2/judicialperson/agent/(:num)']= 'api/v2/judicialperson/agent/$1';
$route['api/v2/certifications/(:any)'] 		= 'api/v2/certifications/$1';

$route['api/v2/version/(:any)'] 		    = 'api/v2/version/$1';

/*-----------
 *  sidebar
 -----------*/
// [借款管理]
$route['admin/(Target|target)/repayment_delayed'] = 'admin/target/index';
$route['admin/(Target|target)/target_repayment_export'] = 'admin/target/target_export';
$route['admin/(Target|target)/target_finished_export'] = 'admin/target/target_export';
$route['admin/(Target|target)/target_waiting_signing_export'] = 'admin/target/target_export';
$route['admin/(Target|target)/detail'] = 'admin/target/edit';
$route['admin/(Target|target)/target_waiting_signing_detail'] = 'admin/target/edit';
$route['admin/(Target|target)/target_waiting_verify_detail'] = 'admin/target/edit';
$route['admin/(Target|target)/target_finished_detail'] = 'admin/target/edit';
$route['admin/(Target|target)/target_repayment_detail'] = 'admin/target/edit';
$route['admin/(Target|target)/target_prepayment_detail'] = 'admin/target/edit';
$route['admin/(Target|target)/target_loan_detail'] = 'admin/target/edit';
$route['admin/(Target|target)/legal_doc_detail'] = 'admin/target/edit';
$route['admin/(Target|target)/transfer_detail'] = 'admin/target/edit';
$route['admin/(Target|target)/obligations_detail'] = 'admin/target/edit';
$route['admin/(Target|target)/waiting_transfer_detail'] = 'admin/target/edit';
$route['admin/(Target|target)/waiting_transfer_success_detail'] = 'admin/target/edit';
$route['admin/(Target|target)/order_target_detail'] = 'admin/target/edit';
$route['admin/(Target|target)/waiting_approve_order_transfer_detail'] = 'admin/target/edit';
$route['admin/(Target|target)/final_validations_detail'] = 'admin/target/final_validations';
$route['admin/creditmanagementtable/waiting_reinspection_report'] = 'admin/creditmanagementtable/report';
$route['admin/creditmanagement/waiting_bidding_report'] = 'admin/creditmanagement/report';
// [債權管理]
$route['admin/(Transfer|transfer)/transfer_assets_export'] = 'admin/transfer/assets_export';
$route['admin/(Transfer|transfer)/obligation_assets_export'] = 'admin/transfer/assets_export';
$route['admin/(Brookesia|brookesia)/final_valid_user_rule_hit'] = 'admin/brookesia/user_rule_hit';
$route['admin/(Brookesia|brookesia)/final_valid_user_related_user'] = 'admin/brookesia/user_related_user';
// [風控專區]
$route['admin/(Risk|risk)/juridical_person'] = 'admin/Risk/index';
$route['admin/(Risk|risk)/investor'] = 'admin/Risk/index';
$route['admin/creditmanagementtable/juridical_person_report'] = 'admin/creditmanagementtable/report';
$route['admin/(Creditmanagement|creditmanagement)/report_natural_person'] = 'admin/creditmanagement/report';
$route['admin/(Creditmanagement|creditmanagement)/report_final_validations'] = 'admin/creditmanagement/report';
$route['admin/(Creditmanagement|creditmanagement)/report_targets_edit'] = 'admin/creditmanagement/report';
$route['admin/(Creditmanagement|creditmanagement)/report_final_validations'] = 'admin/creditmanagement/report';
$route['admin/(Creditmanagement|creditmanagement)/final_validations_get_structural_data'] = 'admin/Creditmanagement/get_structural_data';
$route['admin/(Creditmanagement|creditmanagement)/natural_person_get_structural_data'] = 'admin/Creditmanagement/get_structural_data';
$route['admin/bankdata/juridical_person_report'] = 'admin/bankdata/report';
// [虛擬帳號管理]
$route['admin/(Passbook|passbook)/user_bankaccount_list'] = 'admin/Certification/user_bankaccount_list';
$route['admin/(Passbook|passbook)/user_bankaccount_success'] = 'admin/Certification/user_bankaccount_success';
$route['admin/(Passbook|passbook)/user_bankaccount_failed'] = 'admin/Certification/user_bankaccount_failed';
$route['admin/(Passbook|passbook)/user_bankaccount_resend'] = 'admin/Certification/user_bankaccount_resend';
$route['admin/(Passbook|passbook)/user_bankaccount_verify'] = 'admin/Certification/user_bankaccount_verify';
$route['admin/(Passbook|passbook)/user_bankaccount_edit'] = 'admin/Certification/user_bankaccount_edit';
$route['admin/(Passbook|passbook)/user_bankaccount_detail'] = 'admin/Certification/user_bankaccount_edit';
$route['admin/(Passbook|passbook)/detail'] = 'admin/Passbook/edit';
// [法人管理]
$route['admin/(Judicialperson|judicialperson)/juridical_(apply|management)'] = 'admin/Judicialperson/index';
$route['admin/(Judicialperson|judicialperson)/juridical_(apply|management)_edit'] = 'admin/Judicialperson/edit';
$route['admin/(Judicialperson|judicialperson)/juridical_(apply|management)_success'] = 'admin/Judicialperson/apply_success';
$route['admin/(Judicialperson|judicialperson)/juridical_(apply|management)_failed'] = 'admin/Judicialperson/apply_failed';
$route['admin/(Judicialperson|judicialperson)/cooperation_(apply|management)'] = 'admin/Judicialperson/cooperation';
$route['admin/(Judicialperson|judicialperson)/cooperation_(apply|management)_edit'] = 'admin/Judicialperson/cooperation_edit';
$route['admin/(Judicialperson|judicialperson)/cooperation_(apply|management)_success'] = 'admin/Judicialperson/cooperation_success';
$route['admin/(Judicialperson|judicialperson)/cooperation_(apply|management)_failed'] = 'admin/Judicialperson/cooperation_failed';
// [活動及最新消息]
$route['admin/(Article|article)/news'] = 'admin/Article/index';
$route['admin/(Article|article)/(article|news)_add'] = 'admin/Article/add';
$route['admin/(Article|article)/(article|news)_edit'] = 'admin/Article/edit';
$route['admin/(Article|article)/(article|news)_success'] = 'admin/Article/success';
$route['admin/(Article|article)/(article|news)_failed'] = 'admin/Article/failed';
$route['admin/(Article|article)/(article|news)_del'] = 'admin/Article/del';
// [業務報表]
$route['admin/(Sales|sales)/promote_detail'] = 'admin/sales/promote_edit';
$route['admin/postloan'] = 'admin/PostLoan';
$route['admin/(User|user)/detail'] = 'admin/user/edit';
$route['admin/(Certification|certification)/user_certification_detail'] = 'admin/Certification/user_certification_edit';
$route['admin/(Certification|certification)/user_bankaccount_detail'] = 'admin/Certification/user_bankaccount_edit';