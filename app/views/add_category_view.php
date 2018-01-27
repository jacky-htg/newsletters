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
	$name = trim(htmlspecialchars(Core_Array::getRequest('name')));

	if (empty($name)) $errors[] = core::getLanguage('error', 'empty_category_name');
	if (!empty($name) && $data->checkExistCatName($name)) $errors[] = core::getLanguage('error', 'cat_name_exist');
	
	if (empty($errors)){
		$fields = array();
		$fields['id_cat'] = 0;
		$fields['name'] = $name;
	
		if ($data->addNewCategory($fields)){
			header("Location: ./?t=category");
			exit();
		} else {
			$errors[] = core::getLanguage('error', 'no_category_added');
		}		
	}
}

$tpl->assign('TITLE_PAGE', core::getLanguage('title_page', 'add_category'));
$tpl->assign('TITLE', core::getLanguage('title', 'add_category'));
$tpl->assign('INFO_ALERT', core::getLanguage('info', 'add_category'));

//error alert
if (!empty($errors)) {
	$errorBlock = $tpl->fetch('show_errors');
	$errorBlock->assign('STR_IDENTIFIED_FOLLOWING_ERRORS', core::getLanguage('str', 'identified_following_errors'));

	foreach($error as $row) {
		$rowBlock = $errorBlock->fetch('row');
		$rowBlock->assign('ERROR', $row);
		$errorBlock->assign('row', $rowBlock);
	}

	$tpl->assign('show_errors', $errorBlock);
}

include_once core::pathTo('extra', 'top.php');

//menu
include_once core::pathTo('extra', 'menu.php');

$tpl->assign('RETURN_BACK', core::getLanguage('str', 'return_back'));
$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
$tpl->assign('RETURN_BACK_LINK', './?t=category');
$tpl->assign('STR_NAME', core::getLanguage('str', 'name'));
$tpl->assign('NAME', Core_Array::getRequest('name'));
$tpl->assign('BUTTON', core::getLanguage('button', 'add'));

//footer
include_once core::pathTo('extra', 'footer.php');

// display content
$tpl->display();