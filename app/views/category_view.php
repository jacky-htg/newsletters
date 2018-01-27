<?php

defined('LETTER') || exit('NewsLetter: access denied.');

// authorization
Auth::authorization();

$autInfo = Auth::getAutInfo(core::session()->get('id'));

if (Pnl::CheckAccess($autInfo['role'], 'admin,moderator')) throw new Exception403(core::getLanguage('str', 'dont_have_permission_to_access'));

//include template
core::requireEx('libs', "html_template/SeparateTemplate.php");
$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");

$errors = array();

if (Core_Array::getRequest('remove')){

	if ($data->removeCategory(Core_Array::getRequest('remove')))
		$success = core::getLanguage('msg', 'category_removed');
	else
		$errors[] = core::getLanguage('error', 'web_apps_error');
}

$tpl->assign('TITLE_PAGE', core::getLanguage('title_page', 'category'));
$tpl->assign('TITLE', core::getLanguage('title', 'category'));
$tpl->assign('INFO_ALERT', core::getLanguage('info', 'category'));

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

if (isset($success)){
	$tpl->assign('MSG_ALERT', $success);
}

include_once core::pathTo('extra', 'top.php');

// menu
include_once core::pathTo('extra', 'menu.php');

$tpl->assign('TH_TABLE_POSITION', core::getLanguage('str', 'position'));
$tpl->assign('TH_TABLE_NAME', core::getLanguage('str', 'name'));
$tpl->assign('TH_TABLE_NUMBER_SUBSCRIBERS', core::getLanguage('str', 'number_subscribers'));
$tpl->assign('TH_TABLE_ACTION', core::getLanguage('str', 'action'));

foreach ($data->getCategoryArr() as $row) {
	$count = $data->getCountSubscription($row['id_cat']);
	$rowBlock = $tpl->fetch('row');
	$rowBlock->assign('NAME', $row['name']);
	$rowBlock->assign('COUNT', $count);
	$rowBlock->assign('STR_EDIT', core::getLanguage('str', 'edit'));
	$rowBlock->assign('ID_CAT', $row['id_cat']);
	
	$rowBlock->assign('STR_REMOVE', core::getLanguage('str', 'remove'));
	if ($count > 0) $rowBlock->assign('ALERT_REMOVE_SUNBERS', core::getLanguage('alert', 'remove_subers'));
	
	$tpl->assign('row', $rowBlock);
}

$tpl->assign('BUTTON_ADD_CATEGORY', core::getLanguage('button', 'add_category'));

// footer
include_once core::pathTo('extra', 'footer.php');

// display content
$tpl->display();