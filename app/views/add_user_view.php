<?php

defined('LETTER') || exit('NewsLetter: access denied.');

// authorization
Auth::authorization();

$autInfo = Auth::getAutInfo(Auth::getAutId());

if (Pnl::CheckAccess($autInfo['role'], 'admin,moderator')) throw new Exception403(core::getLanguage('str', 'dont_have_permission_to_access'));

// require temlate class
core::requireEx('libs', "html_template/SeparateTemplate.php");
$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");

$errors = array();

if (Core_Array::getRequest('action')) {

	$name = trim(Core_Array::getRequest('name'));
	$email = trim(Core_Array::getRequest('email'));
	
	if (empty($email)) $errors[] = core::getLanguage('error', 'empty_email');
	
	if (!empty($email) && Pnl::check_email($email)){
		$errors[] = core::getLanguage('error', 'wrong_email');
	}
	
	if (!empty($email) && $data->checkExistEmail($email)){
		$errors[] = core::getLanguage('error', 'subscribe_is_already_done');
	}
	
	if (empty($errors)) {
		$fields = array();
		$fields['id_user']   = 0;
		$fields['name']      = $name;
		$fields['email']     = $email;
		$fields['token']     = Pnl::getRandomCode();
		$fields['time']      = date("Y-m-d H:i:s");	
		$fields['status']    = 'active';
		
		if ($data->addUser($fields, Core_Array::getRequest('id_cat'))) {
			header("Location: ./?t=subscribers");
			exit;
		} else {
			$errors[] =  core::getLanguage('error', 'web_apps_error');
		}
	}
}

$tpl->assign('TITLE_PAGE', core::getLanguage('title_page', 'add_user'));
$tpl->assign('TITLE', core::getLanguage('title', 'add_user'));
$tpl->assign('INFO_ALERT', core::getLanguage('info', 'add_user'));

include_once core::pathTo('extra', 'top.php');

// menu
include_once core::pathTo('extra', 'menu.php');

//alert
if (isset($success)) {
	$tpl->assign('MSG_ALERT', $success);
}

if (!empty($errors)){
	$errorBlock = $tpl->fetch('show_errors');
	$errorBlock->assign('STR_IDENTIFIED_FOLLOWING_ERRORS', core::getLanguage('str', 'identified_following_errors'));
			
	foreach($errors as $row) {
		$rowBlock = $errorBlock->fetch('row');
		$rowBlock->assign('ERROR', $row);
		$errorBlock->assign('row', $rowBlock);
	}
		
	$tpl->assign('show_errors', $errorBlock);
}

//form
$tpl->assign('RETURN_BACK', core::getLanguage('str', 'return_back'));
$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
$tpl->assign('FORM_NAME', core::getLanguage('str', 'name'));
$tpl->assign('FORM_EMAIL', core::getLanguage('str', 'email'));
$tpl->assign('FORM_CATEGORY', core::getLanguage('str', 'category'));
$tpl->assign('BUTTON', core::getLanguage('button', 'add'));

//value
$tpl->assign('NAME', Core_Array::getRequest('name'));
$tpl->assign('EMAIL', Core_Array::getRequest('email'));

foreach($data->getCategoryList() as $row){
	$rowBlock = $tpl->fetch('categories_list');
	$rowBlock->assign('ID_CAT', $row['id_cat']);
	$rowBlock->assign('CATEGORY_NAME', $row['name']);
		
	if($data->checkSub(Core_Array::getRequest('id_cat'), $row['id_cat'])) $rowBlock->assign('CHECKED','checked');
	
	$tpl->assign('categories_list', $rowBlock);
}

//footer
include_once core::pathTo('extra', 'footer.php');

// display content
$tpl->display();