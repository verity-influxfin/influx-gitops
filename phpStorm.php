<?php
die('This file is used for development purposes only.');
/**
 * PhpStorm Code Completion to CodeIgniter + HMVC
 *
 * @package       CodeIgniter
 * @subpackage    PhpStorm
 * @category      Code Completion
 * @author        Natan Felles
 * @link          http://github.com/natanfelles/codeigniter-phpstorm
 */

/*
 * To enable code completion to your own libraries add a line above each class as follows:
 *
 * @property Library_name       $library_name                        Library description
 *
 */

/**
 * @property CI_Benchmark        $benchmark                           This class enables you to mark points and calculate the time difference between them. Memory consumption can also be displayed.
 * @property CI_Calendar         $calendar                            This class enables the creation of calendars
 * @property CI_Cache            $cache                               Caching Class
 * @property CI_Cart             $cart                                Shopping Cart Class
 * @property CI_Config           $config                              This class contains functions that enable config files to be managed
 * @property CI_Controller       $controller                          This class object is the super class that every library in CodeIgniter will be assigned to
 * @property CI_DB_forge         $dbforge                             Database Forge Class
 * @property CI_DB_mysql_driver|CI_DB_query_builder $db                                  This is the platform-independent base Query Builder implementation class
 * @property CI_DB_utility       $dbutil                              Database Utility Class
 * @property CI_Driver_Library   $driver                              Driver Library Class
 * @property CI_Email            $email                               Permits email to be sent using Mail, Sendmail, or SMTP
 * @property CI_Encrypt          $encrypt                             Provides two-way keyed encoding using Mcrypt
 * @property CI_Encryption       $encryption                          Provides two-way keyed encryption via PHP's MCrypt and/or OpenSSL extensions
 * @property CI_Exceptions       $exceptions                          Exceptions Class
 * @property CI_Form_validation  $form_validation                     Form Validation Class
 * @property CI_FTP              $ftp                                 FTP Class
 * @property CI_Hooks            $hooks                               Provides a mechanism to extend the base system without hacking
 * @property CI_Image_lib        $image_lib                           Image Manipulation class
 * @property CI_Input            $input                               Pre-processes global input data for security
 * @property CI_Javascript       $javascript                          Javascript Class
 * @property CI_Jquery           $jquery                              Jquery Class
 * @property CI_Lang             $lang                                Language Class
 * @property CI_Loader           $load                                Loads framework components
 * @property CI_Log              $log                                 Logging Class
 * @property CI_Migration        $migration                           All migrations should implement this, forces up() and down() and gives access to the CI super-global
 * @property CI_Model            $model                               CodeIgniter Model Class
 * @property CI_Output           $output                              Responsible for sending final output to the browser
 * @property CI_Pagination       $pagination                          Pagination Class
 * @property CI_Parser           $parser                              Parser Class
 * @property CI_Profiler         $profiler                            This class enables you to display benchmark, query, and other data in order to help with debugging and optimization.
 * @property CI_Router           $router                              Parses URIs and determines routing
 * @property CI_Security         $security                            Security Class
 * @property CI_Session          $session                             Session Class
 * @property CI_Table            $table                               Lets you create tables manually or from database result objects, or arrays
 * @property CI_Trackback        $trackback                           Trackback Sending/Receiving Class
 * @property CI_Typography       $typography                          Typography Class
 * @property CI_Unit_test        $unit                                Simple testing class
 * @property CI_Upload           $upload                              File Uploading Class
 * @property CI_URI              $uri                                 Parses URIs and determines routing
 * @property CI_User_agent       $agent                               Identifies the platform, browser, robot, or mobile device of the browsing agent
 * @property CI_Xmlrpc           $xmlrpc                              XML-RPC request handler class
 * @property CI_Xmlrpcs          $xmlrpcs                             XML-RPC server class
 * @property CI_Zip              $zip                                 Zip Compression Class
 * @property CI_Utf8             $utf8                                Provides support for UTF-8 environments
 *
 * library
 * @property Black_list_lib $black_list_lib
 * @property Brookesia_lib $brookesia_lib
 * @property Brookesia_react_lib $brookesia_react_lib
 * @property Sqltoci $sqltoci
 * @property CertificationFactory $certificationFactory
 * @property CreditFactory $creditFactory
 * @property TargetFactory $targetFactory
 * @property UserFactory $userFactory
 * @property Gen_company_from_lib $gen_company_from_lib
 * @property Gen_personal_from_lib $gen_personal_from_lib
 * @property Auth_lib $auth_lib
 * @property Loantarget_lib $loantarget_lib
 * @property Product_lib $product_lib
 * @property Role_lib $role_lib
 * @property Security_lib $security_lib
 * @property Log_request_lib $log_request_lib
 * @property Address $address
 * @property Time $time
 * @property Report_check_lib $report_check_lib
 * @property Report_scan_lib $report_scan_lib
 * @property Json_output $json_output
 * @property Business_registration_lib $business_registration_lib
 * @property Findbiz_lib $findbiz_lib
 * @property Google_lib $google_lib
 * @property Instagram_lib $instagram_lib
 * @property Judicial_yuan_lib $judicial_yuan_lib
 * @property Sip_lib $sip_lib
 * @property Assets_report_lib $assets_report_lib
 * @property Http_utility $http_utility
 * @property Joint_credit_regex $joint_credit_regex
 * @property Labor_insurance_regex $labor_insurance_regex
 * @property Payment_time_utility $payment_time_utility
 * @property Regular_expression $regular_expression
 * @property Data_legalize_lib $data_legalize_lib
 * @property Data_verify_lib $data_verify_lib
 * @property Anti_fraud_lib $anti_fraud_lib
 * @property Azure_lib $azure_lib
 * @property Booking_lib $booking_lib
 * @property Certification_lib $certification_lib
 * @property Charge_lib $charge_lib
 * @property Compare_lib $compare_lib
 * @property Contract_lib $contract_lib
 * @property Coop_lib $coop_lib
 * @property Credit_lib $credit_lib
 * @property Estatement_lib $estatement_lib
 * @property Ezpay_lib $ezpay_lib
 * @property Facebook_lib $facebook_lib
 * @property Faceplusplus_lib $faceplusplus_lib
 * @property Financial_lib $financial_lib
 * @property Foreign_exchange_car_lib $foreign_exchange_car_lib
 * @property Format $format
 * @property Game_lib $game_lib
 * @property Gcis_lib $gcis_lib
 * @property Googlevision_lib $googlevision_lib
 * @property Id_card_lib $id_card_lib
 * @property Image_recognition_lib $image_recognition_lib
 * @property Instagram_lib $instagram_lib
 * @property JWT $jWT
 * @property Joint_credit_lib $joint_credit_lib
 * @property Judicialperson_lib $judicialperson_lib
 * @property Labor_insurance_lib $labor_insurance_lib
 * @property Line_lib $line_lib
 * @property Lunaryear_lib $lunaryear_lib
 * @property MY_Admin_Controller $mY_Admin_Controller
 * @property Mongodb_lib $mongodb_lib
 * @property Notification_lib $notification_lib
 * @property Ocr2_lib $ocr2_lib
 * @property Ocr_lib $ocr_lib
 * @property Order_lib $order_lib
 * @property Papago_lib $papago_lib
 * @property Passbook_lib $passbook_lib
 * @property Payment_lib $payment_lib
 * @property Permission_lib $permission_lib
 * @property Phpspreadsheet_lib $phpspreadsheet_lib
 * @property Predis_lib $predis_lib
 * @property Prepayment_lib $prepayment_lib
 * @property Qrcode_lib $qrcode_lib
 * @property REST_Controller $rEST_Controller
 * @property Risk_report_lib $risk_report_lib
 * @property S3_lib $s3_lib
 * @property S3_upload $s3_upload
 * @property Sales_lib $sales_lib
 * @property Scan_lib $scan_lib
 * @property Sendemail $sendemail
 * @property Sms_lib $sms_lib
 * @property Spreadsheet_lib $spreadsheet_lib
 * @property Student_card_recognition_lib $student_card_recognition_lib
 * @property Subloan_lib $subloan_lib
 * @property Target_lib $target_lib
 * @property Transaction_lib $transaction_lib
 * @property Transfer_lib $transfer_lib
 * @property User_bankaccount_lib $user_bankaccount_lib
 * @property User_lib $user_lib
 * @property Withdraw_lib $withdraw_lib
 *
 * model
 * @property Admin_model $admin_model
 * @property Agreement_model $agreement_model
 * @property Article_model $article_model
 * @property Contract_format_model $contract_format_model
 * @property Debt_audit_model $debt_audit_model
 * @property debt_processing_model $debt_processing_model
 * @property Difficult_word_model $difficult_word_model
 * @property Group_model $group_model
 * @property Partner_model $partner_model
 * @property Partner_type_model $partner_type_model
 * @property Role_model $role_model
 * @property Beha_user_model $beha_user_model
 * @property User_behavior_model $user_behavior_model
 * @property Batch_model $batch_model
 * @property Contract_model $contract_model
 * @property Credit_model $credit_model
 * @property Credit_sheet_model $credit_sheet_model
 * @property Credit_sheet_review_model $credit_sheet_review_model
 * @property Investment_model $investment_model
 * @property Prepayment_model $prepayment_model
 * @property Product_model $product_model
 * @property Risk_model $risk_model
 * @property Subloan_model $subloan_model
 * @property Target_associate_model $target_associate_model
 * @property Target_meta_model $target_meta_model
 * @property Target_model $target_model
 * @property Transfer_combination_model $transfer_combination_model
 * @property Transfer_investment_model $transfer_investment_model
 * @property Transfer_model $transfer_model
 * @property Loan_manager_contact_model $loan_manager_contact_model
 * @property Loan_manager_debtprocessing_model $loan_manager_debtprocessing_model
 * @property Loan_manager_pushdata_model $loan_manager_pushdata_model
 * @property Loan_manager_role_model $loan_manager_role_model
 * @property Loan_manager_target_model $loan_manager_target_model
 * @property Loan_manager_user_model $loan_manager_user_model
 * @property Log_loanmanager_user_model $log_loanmanager_user_model
 * @property Log_loanmanager_userlogin_model $log_loanmanager_userlogin_model
 * @property Log_admin_model $log_admin_model
 * @property Log_adminlogin_model $log_adminlogin_model
 * @property Log_azure_model $log_azure_model
 * @property Log_blockedlist_model $log_blockedlist_model
 * @property Log_certification_ocr_model $log_certification_ocr_model
 * @property Log_coop_model $log_coop_model
 * @property Log_ezpay_model $log_ezpay_model
 * @property Log_faceplusplus_model $log_faceplusplus_model
 * @property Log_game_model $log_game_model
 * @property Log_googlevision_model $log_googlevision_model
 * @property Log_image_model $log_image_model
 * @property Log_integration_model $log_integration_model
 * @property Log_investmentschange_model $log_investmentschange_model
 * @property Log_legaldoc_export_model $log_legaldoc_export_model
 * @property Log_legaldoc_status_model $log_legaldoc_status_model
 * @property Log_line_model $log_line_model
 * @property Log_mailbox_model $log_mailbox_model
 * @property Log_ocr2_model $log_ocr2_model
 * @property Log_orderchange_model $log_orderchange_model
 * @property Log_papago_model $log_papago_model
 * @property Log_paymentexport_model $log_paymentexport_model
 * @property Log_qrcode_apply_review_model $log_qrcode_apply_review_model
 * @property Log_qrcode_import_collaboration_model $log_qrcode_import_collaboration_model
 * @property Log_qrcode_modify_model $log_qrcode_modify_model
 * @property Log_qrcode_reward_model $log_qrcode_reward_model
 * @property Log_request_model $log_request_model
 * @property Log_script_model $log_script_model
 * @property Log_send_email_model $log_send_email_model
 * @property Log_sns_model $log_sns_model
 * @property Log_targetschange_model $log_targetschange_model
 * @property Log_user_qrcode_model $log_user_qrcode_model
 * @property Log_userbankaccount_model $log_userbankaccount_model
 * @property Log_usercertification_model $log_usercertification_model
 * @property Log_userlogin_model $log_userlogin_model
 * @property Log_version_model $log_version_model
 * @property Ocr_model $ocr_model
 * @property Ml_log_model $ml_log_model
 * @property User_login_log_model $user_login_log_model
 * @property LoanReceiveResponseLog_model $loanReceiveResponseLog_model
 * @property LoanResult_model $loanResult_model
 * @property LoanSendRequestImageLog_model $loanSendRequestImageLog_model
 * @property LoanSendRequestLog_model $loanSendRequestLog_model
 * @property LoanTargetMappingMsgNo_model $loanTargetMappingMsgNo_model
 * @property Anonymous_donate_model $anonymous_donate_model
 * @property Charity_model $charity_model
 * @property Frozen_amount_model $frozen_amount_model
 * @property Order_model $order_model
 * @property Payment_model $payment_model
 * @property Qrcode_reward_model $qrcode_reward_model
 * @property Receipt_model $receipt_model
 * @property Receipts_leasing_model $receipts_leasing_model
 * @property Transaction_model $transaction_model
 * @property Virtual_passbook_model $virtual_passbook_model
 * @property Withdraw_model $withdraw_model
 * @property Charity_anonymous_model $charity_anonymous_model
 * @property Charity_institution_model $charity_institution_model
 * @property Cooperation_model $cooperation_model
 * @property Deduct_model $deduct_model
 * @property Edm_event_log_model $edm_event_log_model
 * @property Edm_event_model $edm_event_model
 * @property Email_verify_model $email_verify_model
 * @property Judicial_agent_model $judicial_agent_model
 * @property Judicial_person_model $judicial_person_model
 * @property Ntu_model $ntu_model
 * @property Qrcode_collaborator_model $qrcode_collaborator_model
 * @property Qrcode_setting_model $qrcode_setting_model
 * @property Sale_dashboard_model $sale_dashboard_model
 * @property Sale_goals_model $sale_goals_model
 * @property Sms_verify_model $sms_verify_model
 * @property Sound_record_model $sound_record_model
 * @property User_bankaccount_model $user_bankaccount_model
 * @property User_bio_model $user_bio_model
 * @property User_certification_model $user_certification_model
 * @property User_certification_ocr_task_model $user_certification_ocr_task_model
 * @property User_certification_report_model $user_certification_report_model
 * @property User_contact_model $user_contact_model
 * @property User_estatement_model $user_estatement_model
 * @property User_meta_model $user_meta_model
 * @property User_model $user_model
 * @property User_notification_model $user_notification_model
 * @property user_qrcode_apply_model $user_qrcode_apply_model
 * @property user_qrcode_collaboration_model $user_qrcode_collaboration_model
 * @property user_qrcode_model $user_qrcode_model
 * @property user_subcode_model $user_subcode_model
 * @property user_version_update_model $user_version_update_model
 * @property Virtual_account_model $virtual_account_model
 *
 */

class CI_Controller
{
	public $anti_fraud_lib;

	public function __construct()
	{
	}
}

class CI_Model extends CI_Controller
{
}
