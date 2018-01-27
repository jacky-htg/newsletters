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
	$password = trim(Core_Array::getPost('password'));
	$password_again = trim(Core_Array::getPost('password_again'));
	$role = Core_Array::getPost('user_role');
	$id = (int)Core_Array::getPost('id');

	if (empty($password)) $errors[] = core::getLanguage('error', 'password_isnt_entered');
	if (empty($password_again)) $errors[] = core::getLanguage('error', 're_enter_password');

	if (!empty($password) && !empty($password_again)){
		if ($password != $password_again) $errors[] = core::getLanguage('error', 'passwords_dont_match');
	}
	
	if (empty($errors)){
		$fields = array();
		$fields['password'] = md5($password);
		$fields['role'] = $role;

		if ($result = $data->editAccount($fields, $id)){
			header("Location: ./?t=accounts");
			exit();
		} else {
			$errors[] = core::getLanguage('error', 'web_apps_error');
		}		
	}
}

$tpl->assign('TITLE_PAGE', core::getLanguage('title_page', 'edit_account'));
$tpl->assign('TITLE', core::getLanguage('title', 'edit_account'));

//error alert
if (!empty($errors)){
	$errorBlock = $tpl->fetch('show_errors');
	$errorBlock->assign('STR_IDENTIFIED_FOLLOWING_ERRORS',  core::getLanguage('str', 'identified_following_errors'));
			
	foreach($errors as $row){
		$rowBlock = $errorBlock->fetch('row');
		$rowBlock->assign('ERROR', $row);
		$errorBlock->assign('row', $rowBlock);
	}
		
	$tpl->assign('show_errors', $errorBlock);
}

include_once core::pathTo('extra', 'top.php');

// menu
include_once core::pathTo('extra', 'menu.php');

//form
$tpl->assign('STR_REQUIRED_FIELDS', core::getLanguage('str', 'required_fields'));
$tpl->assign('STR_LOGIN', core::getLanguage('str', 'login'));
$tpl->assign('STR_PASSWORD', core::getLanguage('str', 'password'));
$tpl->assign('STR_PASSWORD_AGAIN', core::getLanguage('str', 'again_password'));
$tpl->assign('STR_ROLE', core::getLanguage('str', 'role'));
$tpl->assign('STR_ADMIN', core::getLanguage('str', 'admin'));
$tpl->assign('STR_MODERATOR', core::getLanguage('str', 'moderator'));
$tpl->assign('STR_EDITOR', core::getLanguage('str', 'editor'));

$tpl->assign('RETURN_BACK', core::getLanguage('str', 'return_back'));
$tpl->assign('RETURN_BACK_LINK', './?t=accounts');
$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);

$row = $data->getAccountInfo((int)Core_Array::getGet('id'));

$tpl->assign('USER_ROLE', $row['role']);
$tpl->assign('ID', Core_Array::getRequest('id'));
$tpl->assign('BUTTON', core::getLanguage('button', 'edit'));

// footer
include_once core::pathTo('extra', 'footer.php');

// display content
$tpl->display();