<?php

defined('LETTER') || exit('NewsLetter: access denied.');

// authorization
Auth::authorization();

$autInfo = Auth::getAutInfo(Auth::getAutId());

if (Pnl::CheckAccess($autInfo['role'], 'admin')) throw new Exception403(core::getLanguage('str', 'dont_have_permission_to_access'));

// require temlate class
core::requireEx('libs', "html_template/SeparateTemplate.php");
$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");

$errors = array();

if (Core_Array::getRequest('action')){
	$fields = Array();

	$fields['language'] = trim(Core_Array::getRequest('language'));
	$fields['email'] = trim(Core_Array::getRequest('email'));
	$fields['list_owner'] = trim(Core_Array::getRequest('list_owner'));
	$fields['email_name'] = trim(Core_Array::getRequest('email_name'));
	$fields['return_path'] = trim(Core_Array::getRequest('return_path'));
	$fields['path'] = trim(Core_Array::getRequest('path'));
	$fields['show_email'] = Core_Array::getRequest('show_email') == 'on' ? "yes" : "no";
	$fields['organization'] = trim(Core_Array::getRequest('organization'));
	$fields['smtp_host'] = trim(Core_Array::getRequest('smtp_host'));
	$fields['smtp_username'] = trim(Core_Array::getRequest('smtp_username'));
	$fields['smtp_password'] = trim(Core_Array::getRequest('smtp_password'));
	$fields['smtp_port'] = trim((int)Core_Array::getRequest('smtp_port'));
	$fields['smtp_aut'] = Core_Array::getRequest('smtp_aut');
	$fields['smtp_secure'] = Core_Array::getRequest('smtp_secure');
	$fields['smtp_timeout'] = trim((int)Core_Array::getRequest('smtp_timeout'));
	$fields['how_to_send'] = (int)Core_Array::getRequest('how_to_send');
	$fields['id_charset'] = (int)Core_Array::getRequest('id_charset');
	$fields['content_type'] = (int)Core_Array::getRequest('content_type');
	$fields['number_days'] = (int)Core_Array::getRequest('number_days');
	$fields['make_limit_send'] = Core_Array::getRequest('make_limit_send')  == 'on' ? "yes" : "no";
	$fields['re_send'] = Core_Array::getRequest('re_send')  == 'on' ? "yes" : "no";
	$fields['random'] = Core_Array::getRequest('random') == 'on' ? "yes" : "no";
	$fields['delete_subs'] = Core_Array::getRequest('delete_subs') == 'on' ? "yes" : "no";
	$fields['newsubscribernotify'] = Core_Array::getRequest('newsubscribernotify') == 'on' ? "yes" : "no";
	$fields['request_reply'] = Core_Array::getRequest('request_reply') == 'on' ? "yes" : "no";
	$fields['show_unsubscribe_link'] = Core_Array::getRequest('show_unsubscribe_link') == 'on' ? "yes" : "no";
	$fields['subjecttextconfirm'] = trim(Core_Array::getRequest('subjecttextconfirm'));
	$fields['textconfirmation'] = trim(Core_Array::getRequest('textconfirmation'));
	$fields['require_confirmation'] = Core_Array::getRequest('require_confirmation') == 'on' ? "yes" : "no";
	$fields['unsublink'] = trim(Core_Array::getRequest('unsublink'));
	$fields['limit_number'] = trim((int)Core_Array::getRequest('limit_number'));

	if (Core_Array::getRequest('interval_type') == '1') {
		$fields['interval_type'] = 'm';
	} elseif (Core_Array::getRequest('interval_type') == '2') {
		$fields['interval_type'] = 'h';
	} elseif (Core_Array::getRequest('interval_type') == '3') {
		$fields['interval_type'] = 'd';
	} else {
		$fields['interval_type'] = 'no';
	}

	$fields['interval_number'] = trim((int)Core_Array::getRequest('interval_number'));
	$fields['limit_number'] = Core_Array::getRequest('limit_number');
	$fields['precedence'] = Core_Array::getRequest('precedence');
	$fields['sendmail'] = trim(Core_Array::getRequest('sendmail'));
	$fields["add_dkim"] = Core_Array::getRequest('add_dkim') == 'on' ? "yes" : "no";
	$fields["dkim_domain"] = trim(Core_Array::getRequest('dkim_domain'));
	$fields["dkim_private"] = trim(Core_Array::getRequest('dkim_private'));
	$fields["dkim_selector"] = trim(Core_Array::getRequest('dkim_selector'));
	$fields["dkim_passphrase"] = trim(Core_Array::getRequest('dkim_passphrase'));
	$fields["dkim_identity"] = trim(Core_Array::getRequest('dkim_identity'));
	$fields["sleep"] = trim((int)Core_Array::getRequest('sleep'));

	if ($data->updateSettings($fields))
		$success = core::getLanguage('msg', 'changes_added');
	else
		$errors[] = core::getLanguage('error', 'web_apps_error');
	
	header('Location: ./?t=settings');
	exit;
}

$tpl->assign('TITLE_PAGE', core::getLanguage('title_page', 'settings'));
$tpl->assign('TITLE', core::getLanguage('title', 'settings'));
$tpl->assign('INFO_ALERT', core::getLanguage('info', 'settings'));

include_once core::pathTo('extra', 'top.php');

//menu
include_once core::pathTo('extra', 'menu.php');

//alert
if (!empty($errors)) {
	$errorBlock = $tpl->fetch('show_errors');
	$errorBlock->assign('STR_IDENTIFIED_FOLLOWING_ERRORS', core::getLanguage('str', 'identified_following_errors'));

	foreach($errors as $row){
		$rowBlock = $errorBlock->fetch('row');
		$rowBlock->assign('ERROR', $row);
		$errorBlock->assign('row', $rowBlock);
	}

	$tpl->assign('show_errors', $errorBlock);
}
	
if (isset($success)) {
	$tpl->assign('MSG_ALERT', $success);
}

//value
$tpl->assign('OPTION_LANG', core::getSetting('language'));
$tpl->assign('EMAIL', core::getSetting('email'));
$tpl->assign('PATH', core::getSetting('path') == NULL ? "http://" . $_SERVER["SERVER_NAME"] . Pnl::root() : core::getSetting('path'));
$tpl->assign('LIST_OWNER', core::getSetting('list_owner'));
$tpl->assign('SHOW_EMAIL', core::getSetting('show_email'));
$tpl->assign('SUBSCRIBER_NOTIFY', core::getSetting('newsubscribernotify'));
$tpl->assign('SUBJECTTEXTCONFIRM', core::getSetting('subjecttextconfirm'));
$tpl->assign('TEXTCONFIRMATION', core::getSetting('textconfirmation'));
$tpl->assign('REQUIRE_CONFIRMATION', core::getSetting('require_confirmation'));
$tpl->assign('UNSUBLINK', core::getSetting('unsublink'));
$tpl->assign('SMTP_USERNAME', core::getSetting('smtp_username'));
$tpl->assign('SMTP_PASSWORD', core::getSetting('smtp_password'));
$tpl->assign('SMTP_PORT', core::getSetting('smtp_port'));
$tpl->assign('REQUEST_REPLY', core::getSetting('request_reply'));
$tpl->assign('INTERVAL_NUMBER', core::getSetting('interval_number'));
$tpl->assign('INTERVAL_TYPE', core::getSetting('interval_type'));
$tpl->assign('DELETE_SUBS', core::getSetting('delete_subs'));
$tpl->assign('HOW_TO_SEND', core::getSetting('how_to_send'));
$tpl->assign('SMTP_TIMEOUT', core::getSetting('smtp_timeout'));
$tpl->assign('SMTP_SECURE', core::getSetting('smtp_secure'));
$tpl->assign('SMTP_AUT', core::getSetting('smtp_aut'));
$tpl->assign('SHOW_UNSUBSCRIBE_LINK', core::getSetting('show_unsubscribe_link'));
$tpl->assign('RE_SEND', core::getSetting('re_send'));
$tpl->assign('LIMIT_NUMBER', core::getSetting('limit_number'));
$tpl->assign('MAKE_LIMIT_SEND', core::getSetting('make_limit_send'));
$tpl->assign('NUMBER_DAYS', core::getSetting('number_days'));
$tpl->assign('PRECEDENCE', core::getSetting('precedence'));
$tpl->assign('SENDMAIL', core::getSetting('sendmail'));
$tpl->assign('SLEEP', core::getSetting('sleep'));
$tpl->assign('RANDOM', core::getSetting('random'));
$tpl->assign('RETURN_PATH', core::getSetting('return_path'));
$tpl->assign('ADD_DKIM', core::getSetting('add_dkim'));
$tpl->assign('DKIM_DOMEN', core::getSetting('dkim_domain'));
$tpl->assign('DKIM_PRIVATE', core::getSetting('dkim_private'));
$tpl->assign('DKIM_SELECTOR', core::getSetting('dkim_selector'));
$tpl->assign('DKIM_PASSPHRASE', core::getSetting('dkim_passphrase'));
$tpl->assign('DKIM_IDENTITY', core::getSetting('dkim_identity'));

//form
$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
$tpl->assign('SET_LANGUAGE', core::getLanguage('str', 'set_language'));
$tpl->assign('SET_OPTION_RU', core::getLanguage('str', 'set_option_ru'));
$tpl->assign('SET_OPTION_EN', core::getLanguage('str', 'set_option_en'));
$tpl->assign('SET_INTERFACE_SETTINGS', core::getLanguage('str', 'set_interface_settings'));
$tpl->assign('SET_EMAIL', core::getLanguage('str', 'set_email'));
$tpl->assign('SET_LIST_OWNER', core::getLanguage('str', 'list_owner'));
$tpl->assign('SET_SHOW_EMAIL', core::getLanguage('str', 'set_show_email'));
$tpl->assign('SET_SUBSCRIBER_NOTIFY', core::getLanguage('str', 'set_subscriber_notify'));
$tpl->assign('SET_EMAIL_NAME', core::getLanguage('str', 'set_email_name'));

$email_name = core::getSetting('email_name');

if (empty($email_name))
    $tpl->assign('EMAIL_NAME', $_SERVER['SERVER_NAME']);
else
    $tpl->assign('EMAIL_NAME', htmlspecialchars(core::getSetting('email_name')));

$tpl->assign('SET_ORGANIZATION', core::getLanguage('str', 'set_organization'));
$tpl->assign('ORGANIZATION', htmlspecialchars(core::getSetting('organization')));
$tpl->assign('SET_SUBJECT_TEXTCONFIRM', core::getLanguage('str', 'set_subject_textconfirm'));
$tpl->assign('SET_TEXT_CONFIRMATION', core::getLanguage('str', 'set_text_confirmation'));
$tpl->assign('SET_REQUIRE_CONFIRMATION', core::getLanguage('str', 'set_require_confirmation'));
$tpl->assign('SET_UNSUBLINK', core::getLanguage('str', 'set_unsublink'));
$tpl->assign('SET_HINT', core::getLanguage('str', 'set_hint'));
$tpl->assign('SET_SMTP_SETTINGS', core::getLanguage('str', 'set_smtp_settings'));
$tpl->assign('SET_SMTP_HOST', core::getLanguage('str', 'set_smtp_host'));
$tpl->assign('SMTP_HOST', core::getSetting('smtp_host'));
$tpl->assign('SET_SMTP_USERNAME', core::getLanguage('str', 'set_username'));
$tpl->assign('SET_SMTP_PASSWORD', core::getLanguage('str', 'set_password'));
$tpl->assign('SET_SMTP_PORT', core::getLanguage('str', 'set_port'));
$tpl->assign('SET_SMTP_TIMEOUT', core::getLanguage('str', 'set_timeout'));
$tpl->assign('SET_SMTP_SSL', core::getLanguage('str', 'set_smtp_secure'));
$tpl->assign('SET_RETURN_PATH', core::getLanguage('str', 'set_return_path'));
$tpl->assign('STR_NO', core::getLanguage('str', 'no'));
$tpl->assign('SMTP_SECURE_SSL', core::getLanguage('str', 'smtp_secure_ssl'));
$tpl->assign('SMTP_SECURE_TLS', core::getLanguage('str', 'smtp_secure_tls'));
$tpl->assign('SET_SMTP_AUT', core::getLanguage('str', 'set_smtp_aut'));
$tpl->assign('SET_SMTP_AUT_LOGIN', core::getLanguage('str', 'set_smtp_aut_login'));
$tpl->assign('SET_SMTP_AUT_PLAIN', core::getLanguage('str', 'set_smtp_aut_plain'));
$tpl->assign('SET_SMTP_AUT_CRAM', core::getLanguage('str', 'set_smtp_aut_cram'));
$tpl->assign('SET_SEND_PARAMETERS', core::getLanguage('str', 'set_send_parameters'));
$tpl->assign('SET_SHOW_UNSUBSCRIBE_LINK', core::getLanguage('str', 'set_show_unsubscribe_link'));
$tpl->assign('SET_REPLY', core::getLanguage('str', 'set_request_reply'));
$tpl->assign('SET_INTERVAL_TYPE', core::getLanguage('str', 'set_interval_type'));
$tpl->assign('SET_INTERVAL_TYPE_NO', core::getLanguage('str', 'set_interval_type_no'));
$tpl->assign('SET_INTERVAL_TYPE_M', core::getLanguage('str', 'set_interval_type_m'));
$tpl->assign('SET_INTERVAL_TYPE_H', core::getLanguage('str', 'set_interval_type_h'));
$tpl->assign('SET_INTERVAL_TYPE_D', core::getLanguage('str', 'set_interval_type_d'));
$tpl->assign('SET_RE_SEND', core::getLanguage('str', 'set_re_send'));
$tpl->assign('SET_NUMBER_LIMIT', core::getLanguage('str', 'set_number_limit'));
$tpl->assign('SET_NUMBER_DAYS', core::getLanguage('str', 'set_number_days'));
$tpl->assign('SET_IPRECEDENCE_NO', core::getLanguage('str', 'no'));
$tpl->assign('SET_CHARSET', core::getLanguage('str', 'set_charset'));
$tpl->assign('SET_HOW_TO_SEND', core::getLanguage('str', 'set_how_to_send'));
$tpl->assign('SET_HOW_TO_SEND_OPTION_1', core::getLanguage('str', 'set_how_to_send_option_1'));
$tpl->assign('SET_HOW_TO_SEND_OPTION_2', core::getLanguage('str', 'set_how_to_send_option_2'));
$tpl->assign('SET_HOW_TO_SEND_OPTION_3', core::getLanguage('str', 'set_how_to_send_option_3'));
$tpl->assign('SET_SENDMAIL_PATH', core::getLanguage('str', 'set_sendmail'));
$tpl->assign('SET_PATH', core::getLanguage('str', 'set_path'));
$tpl->assign('SET_SLEEP', core::getLanguage('str', 'set_sleep'));
$tpl->assign('SET_RANDOM', core::getLanguage('str', 'set_random'));
$tpl->assign('SET_ADD_DKIM', core::getLanguage('str', 'set_add_dkim'));
$tpl->assign('SET_DKIM_DOMEN', core::getLanguage('str', 'set_dkim_domen'));
$tpl->assign('SET_DKIM_PRIVATE', core::getLanguage('str', 'set_dkim_private'));
$tpl->assign('SET_DKIM_SELECTOR', core::getLanguage('str', 'set_dkim_selector'));
$tpl->assign('SET_DKIM_PASSPHRASE', core::getLanguage('str', 'set_dkim_passphrase'));
$tpl->assign('SET_DKIM_IDENTITY', core::getLanguage('str', 'set_dkim_identity'));
$tpl->assign('BUTTON_APPLY', core::getLanguage('button', 'apply'));
$tpl->assign('BUTTON_BY_DEFAULT', core::getLanguage('button', 'by_default'));

$temp = $data->getCharsetList();

asort($temp);

foreach($temp as $key => $value){
	$rowBlock = $tpl->fetch('charsetlist_row');
	$rowBlock->assign('KEY', $key);
	$rowBlock->assign('ID_CHARSET', core::getSetting('id_charset'));
	$rowBlock->assign('VALUE', $value);
	$tpl->assign('charsetlist_row', $rowBlock);
}

$tpl->assign('SET_CONTENT_TYPE', core::getLanguage('str', 'set_content_type'));
$tpl->assign('CONTENT_TYPE', core::getSetting('content_type'));

// footer
include_once core::pathTo('extra', 'footer.php');

// display content
$tpl->display();