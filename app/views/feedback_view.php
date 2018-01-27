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

	switch($_REQUEST['action']) {
		case 3:
			if ($data->deleteFeedback( Core_Array::getRequest('activate'))) {
				$success_alert =  'selected_users_deleted';
			} else {
				$errors[] =  core::getLanguage('error', 'web_apps_error');
			}

			break;
	}
}

if (Core_Array::getRequest('remove') && is_numeric($_REQUEST['remove'])) {
	if ($data->removeFeedback($_REQUEST['remove']))
		$success_alert =   'selected feedback deleted';
	else
		$errors[] =   core::getLanguage('error', 'web_apps_error');
}

$tpl->assign('TITLE_PAGE',  'feedback');
$tpl->assign('TITLE',  'Feedbacks');
$tpl->assign('INFO_ALERT',   'Feedback - a feedback from subscriber. Your feedback from subscribers are shown below.');

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

//horizontal menu
//$tpl->assign('STR_ADD_USER', core::getLanguage('str', 'add_user'));
//$tpl->assign('STR_REMOVE_USER', core::getLanguage('str', 'remove_user'));
//$tpl->assign('STR_IMPORT_USER', core::getLanguage('str', 'import_user'));
//$tpl->assign('STR_EXPORT_USER', core::getLanguage('str', 'export_user'));
//$tpl->assign('PROMPT_ADD_USER', core::getLanguage('prompt', 'add_user'));
//$tpl->assign('PROMPT_REMOVE_FEEDBACK', core::getLanguage('prompt', 'remove_feedback'));
$tpl->assign('PROMPT_EXPORT_FEEDBACK',   core::getLanguage('prompt', 'export_feedback'));

//form
$tpl->assign('FORM_SEARCH_NAME',  core::getLanguage('str', 'search_name'));
$tpl->assign('BUTTON_FIND',  core::getLanguage('button', 'find'));
$tpl->assign('ALERT_CLEAR_ALL', core::getLanguage('alert', 'clear_all'));
$tpl->assign('ALERT_SELECT_ACTION', core::getLanguage('alert', 'select_action'));
$tpl->assign('ALERT_CONFIRM_REMOVE', core::getLanguage('alert', 'confirm_remove'));

$tpl->assign('STR_REMOVE_ALL_FEEDBACK', core::getLanguage('str', 'remove_all_feedback'));
$tpl->assign('STR_CHECK_ALLBOX', core::getLanguage('str', 'check_allbox'));

$order = array();
$order['name'] = "name";
$order['email'] = "email";
$order['time'] = "time";

$strtmp = "name";
$sort = '';

foreach($order as $parametr => $field) {
	if (isset($_GET[$parametr])) {
		if ($_GET[$parametr] == "up"){
			$_GET[$parametr] = "down";
			$strtmp = $field;
			$sort = "&" . $field . "=up";
			$thclass[$parametr] = 'headerSortUp';
		} else {
			$_GET[$parametr] = "up";
			$strtmp = "" . $field . " DESC";
			$sort = "&" . $field . "=down";
			$thclass[$parametr] = 'headerSortDown';
		}
	} else {
		$_GET[$parametr] = "up";
		$thclass[$parametr] = 'headerUnSort';
	}
}

//pagination
if (isset($_COOKIE['pnumber_feedback']))
	$pnumber = (int)$_COOKIE['pnumber_feedback'];
else 
	$pnumber = 20;

$search = Core_Array::getRequest('search');

$arr = $data->getFeedbackArr($strtmp, $search, Core_Array::getRequest('page'), $pnumber);

if ($arr){
	$return_backBlock = $tpl->fetch('show_return_back');	
	$return_backBlock->assign('STR_BACK',  core::getLanguage('str', 'return_back'));

	$number = $data->getTotal();
	$page = $data->getPageNumber();
        
	if (empty($search)){
		if ($page != 1) {
			$pervpage = '<a href="./?t=feedback&page=1' . $sort . '">&lt;&lt;</a>';
			$perv = '<a href="./?t=feedback&page=' . ($page - 1) . '' . $sort . '">&lt;</a>';
		}

		if ($page != $number) {
			$nextpage = '<a href="./?t=feedback&page=' . ($page + 1) . '' . $sort . '">&gt;</a>';
			$next = '<a href="./?t=feedback&page=' . $number . '' . $sort . '">&gt;&gt;</a>';
		}									

		if ($page - 2 > 0) $page2left = '<a href="./?t=feedback&page=' . ($page - 2) .'' . $sort . '">...'.($page - 2) . '</a>';
		if ($page - 1 > 0) $page1left = '<a href="./?t=feedback&page=' . ($page - 1) . '' . $sort . '">'.($page - 1) . '</a>';
		if ($page + 2 <= $number) $page2right = '<a href="./?t=feedback&page=' . ($page + 2) . '' . $sort . '">' . ($page + 2) . '...</a>';
		if ($page + 1 <= $number) $page1right = '<a href="./?t=feedback&page=' . ($page + 1) . '' . $sort . '">' . ($page + 1) . '</a>';
	} else {
		if ($page != 1) {
			$pervpage = '<a href="./?t=feedback&search=' . urlencode($search) . '&page=1' . $sort . '">&lt;&lt;</a>';
			$perv = '<a href="./?t=feedback&search=' . urlencode($search) . '&page=' . ($page - 1) . '' . $sort . '">&lt;</a>';
		}								

		if ($page != $number) {
			$nextpage = '<a href="./?t=feedback&search=' . urlencode($search).'&page=' . ($page + 1) . '' . $sort . '">&gt;</a>';
			$next = '<a href="./?t=feedback&search=' . urlencode($search) . '&page=' . $number . '' . $sort . '">&gt;&gt;</a>';
		}									

		if ($page - 2 > 0) $page2left = '<a href="./?t=feedback&search=' . urlencode($search) . '&page=' . ($page - 2) . '' . $sort . '">...' . ($page - 2) . '</a>';
		if ($page - 1 > 0) $page1left = '<a href="./?t=feedback&search=' . urlencode($search) . '&page=' . ($page - 1) . '' . $sort . '">' . ($page - 1).'</a>';
		if ($page + 2 <= $number) $page2right = '<a href=".?t=feedback&search=' . urlencode($search) . '&page=' . ($page + 2) . '' . $sort . '">' . ($page + 2) . '...</a>';
		if ($page + 1 <= $number) $page1right = '<a href="./?t=feedback&search=' . urlencode($search) . '&page=' . ($page + 1) . '' . $sort . '">' . ($page + 1) . '</a>';
	}	
	
	if ($page > 1)
		$pagenav = "&page=" . $page . "";
	else 
		$pagenav = '';
		
	$rowBlock = $tpl->fetch('row');		
	
	if ($search) $rowBlock->assign('SEARCH', $search);
	
	//show table
	$rowBlock->assign('PAGENAV', $pagenav);
	$rowBlock->assign('GET_NAME', $_GET['name']);
	$rowBlock->assign('GET_EMAIL', $_GET['email']);
	$rowBlock->assign('GET_TIME', $_GET['time']);
	$rowBlock->assign('GET_CONTENT', $_GET['content']);
	$rowBlock->assign('TH_CLASS_NAME', $thclass["name"]);	
	$rowBlock->assign('TH_CLASS_EMAIL', $thclass["email"]);	
	$rowBlock->assign('TH_CLASS_TIME', $thclass["time"]);	
	$rowBlock->assign('TH_CLASS_CONTENT', $thclass["content"]);
	
	$rowBlock->assign('ALERT_CONFIRM_REMOVE', core::getLanguage('alert', 'confirm_remove'));
	$rowBlock->assign('ALERT_SELECT_ACTION',  core::getLanguage('alert', 'select_action'));
	$rowBlock->assign('TABLE_NAME', core::getLanguage('str', 'name'));
	$rowBlock->assign('TABLE_EMAIL', core::getLanguage('str', 'email'));
	$rowBlock->assign('TABLE_ADDED', core::getLanguage('str', 'added'));
	$rowBlock->assign('TABLE_CONTENT', core::getLanguage('str', 'content'));
	$rowBlock->assign('TABLE_ACTION', core::getLanguage('str', 'action'));
        
	foreach ($arr as $row) {
		$columnBlock = $rowBlock->fetch('column');
		/*$columnBlock->assign('STATUS_CLASS', $row['status'] == 'noactive' ? 'noactive' : '');*/
		$columnBlock->assign('STR_CHECK_BOX', core::getLanguage('str', 'check_box'));
		$columnBlock->assign('ID_FEEDBACK', $row['id']);
		$columnBlock->assign('NAME', $row['name']);
		$columnBlock->assign('EMAIL', $row['email']);
		$columnBlock->assign('PUTDATE_FORMAT', $row['putdate_format']);
                $columnBlock->assign('CONTENT', $row['content']);
		/*$columnBlock->assign('IP', $row['ip']);
		$columnBlock->assign('GETHOSTBYADDR', $row['ip']);
		$columnBlock->assign('PROMPT_IP_INFO', core::getLanguage('prompt', 'ip_info'));
		$columnBlock->assign('STR_STAT', $row['status'] == 'active' ?  core::getLanguage('str', 'activeuser'):  core::getLanguage('str', 'noactive'));
		$columnBlock->assign('STR_EDIT',  core::getLanguage('str', 'edit'));		
		$columnBlock->assign('STR_REMOVE',  core::getLanguage('str', 'remove_user'));*/
		$rowBlock->assign('column', $columnBlock);		
	}
	
	if ($number > 1) {
		$paginationBlock = $rowBlock->fetch('pagination');
		$paginationBlock->assign('STR_PNUMBER',  core::getLanguage('str', 'pnumber'));
		$paginationBlock->assign('CURRENT_PAGE', '<a>' . $page . '</a>');
		$paginationBlock->assign('STR_PAGES', core::getLanguage('str', 'pages'));

		$paginationBlock->assign('PAGE1RIGHT', isset($page1right) ? $page1right : '');
		$paginationBlock->assign('PAGE2RIGHT', isset( $page2right) ?  $page2right : '');

		$paginationBlock->assign('PAGE1LEFT', isset($page1left) ? $page1left : '');
		$paginationBlock->assign('PAGE2LEFT', isset($page2left) ? $page2left : '');

		$paginationBlock->assign('PERVPAGE', isset($pervpage) ? $pervpage : '');
		$paginationBlock->assign('NEXTPAGE', isset($nextpage) ? $nextpage : '');
	
		$paginationBlock->assign('PERV', isset($perv) ? $perv : '');
		$paginationBlock->assign('NEXT', isset($next) ? $next : '');
		
		$paginationBlock->assign('PNUMBER', isset($pnumber) ? $pnumber : '');
		$rowBlock->assign('pagination', $paginationBlock);	
	}
	
	$rowBlock->assign('NUMBER_OF_FEEDBACK', $data->countFeedback());
	$rowBlock->assign('STR_ACTION', core::getLanguage('str', 'action'));
	$rowBlock->assign('STR_REMOVE', core::getLanguage('str', 'remove'));
	$rowBlock->assign('STR_APPLY', core::getLanguage('str', 'apply'));
	$tpl->assign('row', $rowBlock);
} else {
	if (!empty($search)) {
		$notfoundBlock = $tpl->fetch('notfound');
		$notfoundBlock->assign('MSG_NOTFOUND',   core::getLanguage('msg', 'notfound'));
		$tpl->assign('notfound', $notfoundBlock);
	} else {
		$tpl->assign('EMPTY_LIST', core::getLanguage('str', 'empty'));
	}
}

//footer
include_once core::pathTo('extra', 'footer.php');

//display content
$tpl->display();
