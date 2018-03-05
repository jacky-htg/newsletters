<?php

defined('LETTER') || exit('NewsLetter: access denied.');

// authorization
Auth::authorization();

$autInfo = Auth::getAutInfo(Auth::getAutId());

if (Pnl::CheckAccess($autInfo['role'], 'admin,moderator')) throw new Exception403(core::getLanguage('str', 'dont_have_permission_to_access'));

// require temlate class
core::requireEx('libs', "html_template/SeparateTemplate.php");
if (isset($_GET['type']) && 'detail' === $_GET['type']) {
    $tpl_file_name = 'links';
}
else {
    $tpl_file_name = 'links_graphic';
}
$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . $tpl_file_name . ".tpl");

$errors = array();

$tpl->assign('TITLE_PAGE',  core::getLanguage('title_page', 'links'));
$tpl->assign('TITLE',  core::getLanguage('title', 'links'));
$tpl->assign('INFO_ALERT',   core::getLanguage('info', 'links'));

include_once core::pathTo('extra', 'top.php');

//menu
include_once core::pathTo('extra', 'menu.php');

$search = urldecode(Core_Array::getRequest('search'));
$tpl->assign('SEARCH', $search);
$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);

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

if (isset($success_alert)){ 
	$tpl->assign('MSG_ALERT', $success_alert);
}

if (isset($_GET['type']) && 'detail' === $_GET['type']) {
    include_once __DIR__.('/links_detail.php');
}
else {
    include_once __DIR__.('/links_graphic.php');
}
//footer
include_once core::pathTo('extra', 'footer.php');

//display content
$tpl->display();
