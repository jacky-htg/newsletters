<?php

defined('LETTER') || exit('NewsLetter: access denied.');

// authorization
Auth::authorization();

$autInfo = Auth::getAutInfo(Auth::getAutId());

if (Pnl::CheckAccess($autInfo['role'], 'admin,moderator')) throw new Exception403(core::getLanguage('str', 'dont_have_permission_to_access'));

//include template
core::requireEx('libs', "html_template/SeparateTemplate.php");
$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");

$errors = array();

if (Core_Array::getRequest('action')){
	$name = htmlspecialchars(trim(Core_Array::getRequest('name')));

	if (empty($name)) $alert_error = core::getLanguage('error', 'empty_category_name');
	if (!isset($alert_error)){
		$fields = array();
		$fields['name'] = $name;
	
		if ($data->editCategoryRow($fields, Core_Array::getPost('id_cat'))){
			header("Location: ./?t=category");
			exit;
		} else  $errors[] = core::getLanguage('error', 'edit_cat_name');
	}
}

//title
$tpl->assign('TITLE_PAGE', core::getLanguage('title_page', 'edit_category'));
$tpl->assign('TITLE', core::getLanguage('title', 'edit_category'));
$tpl->assign('INFO_ALERT', core::getLanguage('info', 'edit_category'));

include_once core::pathTo('extra', 'top.php');

//menu
include_once core::pathTo('extra', 'menu.php');

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

//form
$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
$tpl->assign('STR_NAME', core::getLanguage('str', 'name'));
$tpl->assign('BUTTON', core::getLanguage('button', 'edit'));
$tpl->assign('STR_RETURN_BACK', core::getLanguage('str', 'return_back'));

$row = $data->getCategoryRow(Core_Array::getRequest('id_cat'));

//value
$tpl->assign('NAME', Core_Array::getPost('name') ?  $_POST['name'] : $row['name']);
$tpl->assign('ID_CAT', $row['id_cat']);

//footer
include_once core::pathTo('extra', 'footer.php');

// display content
$tpl->display();