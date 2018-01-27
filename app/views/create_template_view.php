<?php

defined('LETTER') || exit('NewsLetter: access denied.');

// authorization
Auth::authorization();

$autInfo = Auth::getAutInfo(Auth::getAutId());

if (Pnl::CheckAccess($autInfo['role'], 'admin,moderator,editor')) throw new Exception403(core::getLanguage('str', 'dont_have_permission_to_access'));

// require temlate class
core::requireEx('libs', "html_template/SeparateTemplate.php");
$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");

$errors = array();

if (Core_Array::getRequest('action')) {
	$name = trim(Core_Array::getPost('name'));
	$body = trim(Core_Array::getPost('body'));
	
	if (empty($name)) $errors[] = core::getLanguage('error', 'empty_subject');
	if (empty($body)) $errors[] = core::getLanguage('error', 'empty_content');
	
	if (empty($errors)) {
		$fields = array();
		$fields['id_template'] = 0;
		$fields['name'] = $name;
		$fields['body'] = $body;
		$fields['prior'] = (int)Core_Array::getPost('prior');
		$fields['id_cat'] = (int)Core_Array::getPost('id_cat');
		$fields['active'] = 'yes';

		if ($data->addNewTemplate($fields)){
			header("Location: ./");
			exit();
		} else {
			$errors[] =  core::getLanguage('error', 'web_apps_error');
		}	
	}
}

$tpl->assign('TITLE_PAGE',  core::getLanguage('title_page', 'create_new_template'));
$tpl->assign('TITLE',  core::getLanguage('title', 'create_new_template'));
$tpl->assign('INFO_ALERT',  core::getLanguage('info', 'create_new_template'));

include_once core::pathTo('extra', 'top.php');

// menu
include_once core::pathTo('extra', 'menu.php');


// alert
if (!empty($errors)) {
	$errorBlock = $tpl->fetch('show_errors');
	$errorBlock->assign('STR_IDENTIFIED_FOLLOWING_ERRORS', core::getLanguage('str', 'identified_following_errors'));
			
	foreach($errors as $row) {
		$rowBlock = $errorBlock->fetch('row');
		$rowBlock->assign('ERROR', $row);
		$errorBlock->assign('row', $rowBlock);
	}
		
	$tpl->assign('show_errors', $errorBlock);
}	

// form
$tpl->assign('STR_FORM_SUBJECT', core::getLanguage('str', 'form_subject'));
$tpl->assign('STR_FORM_CONTENT',  core::getLanguage('str', 'form_content'));
$tpl->assign('STR_FORM_NOTE',  core::getLanguage('str', 'form_supported_tags'));
$tpl->assign('STR_REMOVE', core::getLanguage('str', 'remove'));
$tpl->assign('STR_SUPPORTED_TAGS_LIST',  core::getLanguage('str', 'supported_tags_list'));
$tpl->assign('STR_FORM_ATTACH_FILE',  core::getLanguage('str', 'form_attach_file'));
$tpl->assign('STR_FORM_CATEGORY_SUBSCRIBERS',  core::getLanguage('str', 'form_category_subscribers'));
$tpl->assign('STR_FORM_PRIORITY_NORMAL',  core::getLanguage('str', 'form_priority_normal'));
$tpl->assign('STR_FORM_PRIORITY_LOW', core::getLanguage('str', 'form_priority_low'));
$tpl->assign('STR_FORM_PRIORITY_HIGH',  core::getLanguage('str', 'form_priority_high'));
$tpl->assign('STR_FORM_PRIORITY',  core::getLanguage('str', 'form_priority'));
$tpl->assign('BUTTON',  core::getLanguage('button', 'add'));

// value
$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
$tpl->assign('NAME', Core_Array::getPost('name'));
$tpl->assign('CONTENT', Core_Array::getPost('body'));
$tpl->assign('ID_TEMPLATE', Core_Array::getPost('id_template'));

if (Core_Array::getRequest('prior') == 1)
	$tpl->assign('PRIOR', 1);
elseif (Core_Array::getRequest('prior') == 2)
	$tpl->assign('PRIOR', 2);
else 
	$tpl->assign('PRIOR', 3);

$arr = $data->getCategoryOptionList();

if ($arr) {
	$tpl->assign('POST_ID_CAT', Core_Array::getPost('id_cat'));
	$tpl->assign('STR_SEND_TO_ALL', core::getLanguage('str', 'send_to_all'));

	foreach($arr as $row){
		$rowBlock = $tpl->fetch('categories_row');
		$rowBlock->assign('ID_CAT', $row['id_cat']);
		$rowBlock->assign('NAME', $row['name']);
		$rowBlock->assign('POST_ID_CAT', Core_Array::getPost('id_cat'));
		$tpl->assign('categories_row', $rowBlock);
	}
}		

$tpl->assign('STR_SEND_TEST_EMAIL', core::getLanguage('str', 'send_test_email'));
$tpl->assign('BUTTON_SEND', core::getLanguage('button', 'send'));
$tpl->assign('STR_IDENTIFIED_FOLLOWING_ERRORS', core::getLanguage('str', 'identified_following_errors'));

// footer
include_once core::pathTo('extra', 'footer.php');

// display content
$tpl->display();