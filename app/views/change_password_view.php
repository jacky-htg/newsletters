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

if (Core_Array::getPost('action')) {
	$current_password = trim(Core_Array::getPost('current_password'));
	$password = trim(Core_Array::getPost('password'));
	$password_again = trim(Core_Array::getPost('password_again'));

	if (!$current_password) {
		$errors[] = core::getLanguage('error', 'enter_current_passwd');
	}

	if (!$password) {
		$errors[] = core::getLanguage('error', 'password_isnt_entered');
	}

	if (!$password_again) {
		$errors[] = core::getLanguage('error', 're_enter_password');
	}
	
	if ($password && $password_again && $password != $password_again) {
		$errors[] = core::getLanguage('error', 'passwords_dont_match');
	}
	
	if ($current_password){
		$current_password = md5($_POST["current_password"]);
		
		if (Auth::getCurrentHash($autInfo['id']) != $current_password) {
			$errors[] = core::getLanguage('error', 'current_password_incorrect');
		}
	}

	if (empty($errors)) {
		if ($data->changePassword($password, $autInfo['id'])) {
			$success_msg = core::getLanguage('msg', 'password_has_been_changed');
		} else {
			$errors[] = core::getLanguage('error', 'change_password');
		}		
	}
}

$tpl->assign('TITLE_PAGE', core::getLanguage('title_page', 'change_password'));
$tpl->assign('TITLE', core::getLanguage('title', 'change_password'));
$tpl->assign('INFO_ALERT', core::getLanguage('info', 'change_password'));

include_once core::pathTo('extra', 'top.php');

// menu
include_once core::pathTo('extra', 'menu.php');
	
//alert
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

if (isset($success_msg)) {
	$tpl->assign('MSG_ALERT', $success_msg);
}

//form
$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
$tpl->assign('STR_CURRENT_PASSWORD', core::getLanguage('str', 'current_password'));
$tpl->assign('STR_PASSWORD', core::getLanguage('str', 'password'));
$tpl->assign('STR_AGAIN_PASSWORD', core::getLanguage('str', 'again_password'));
$tpl->assign('BUTTON_SAVE', core::getLanguage('button', 'save'));

//footer
include_once core::pathTo('extra', 'footer.php');

$tpl->display();