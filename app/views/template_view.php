<?php

defined('LETTER') || exit('NewsLetter: access denied.');

// authorization
Auth::authorization();

$autInfo = Auth::getAutInfo(Auth::getAutId());

if (Pnl::CheckAccess($autInfo['role'], 'admin,moderator,editor')) throw new Exception403(core::getLanguage('str', 'dont_have_permission_to_access'));

//include template
core::requireEx('libs', "html_template/SeparateTemplate.php");
$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");

$errors = array();

if (Core_Array::getRequest('action')){
	switch($_REQUEST['action']){
		case 2:
		
			$fields['active'] = 'yes';
			
			if(!$data->changeStatusNewsLetter($fields, Core_Array::getRequest('activate'))) $alert_error = core::getLanguage('error', 'web_apps_error');
			
			break;
		
		case 3:
		
			$fields['active'] = 'no';
			
			if(!$data->changeStatusNewsLetter($fields, Core_Array::getRequest('activate'))) $alert_error = core::getLanguage('error', 'web_apps_error');

			break;
		
		case 4:
		
			if(!$data->removeTemplate(Core_Array::getRequest('activate'))) $alert_error = core::getLanguage('error', 'web_apps_error');
			
			break;
		
		default:
		
			header("Location: ./");
			exit;		
	}
}

if (Core_Array::getRequest('pos') == 'up' && is_numeric($_GET['id_template'])){
	if ($data->upPosition(Core_Array::getRequest('id_template'))){
		header("Location: ./");
		exit;
	} else {
		$errors[] = core::getLanguage('error', 'web_apps_error');
	}
}

if (Core_Array::getRequest('pos') == 'down' && is_numeric($_GET['id_template'])){
	if ($data->downPosition(Core_Array::getRequest('id_template'))){
		header("Location: ./");
		exit;
	} else {
		$errors[] = core::getLanguage('error', 'web_apps_error');
	}	
}

$tpl->assign('TITLE_PAGE', core::getLanguage('title_page', 'template'));
$tpl->assign('TITLE', core::getLanguage('title', 'template'));
$tpl->assign('INFO_ALERT', core::getLanguage('info', 'template'));

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

$tpl->assign('TH_TABLE_ACTIVITY', core::getLanguage('str', 'activity'));
$tpl->assign('TH_TABLE_CATEGORY', core::getLanguage('str', 'category'));
$tpl->assign('TH_TABLE_MAILER', core::getLanguage('str', 'mailer'));
$tpl->assign('TH_TABLE_POSITION', core::getLanguage('str', 'position'));
$tpl->assign('TH_TABLE_EDIT', core::getLanguage('str', 'edit'));
$tpl->assign('TH_TABLE_SEND', core::getLanguage('str', 'send'));

if (isset($_COOKIE['pnumber']))
	$pnumber = (int)$_COOKIE['pnumber'];
else 
	$pnumber = 5;

$arr = $data->getListArr($pnumber, Core_Array::getRequest('page'));

if ($arr){
	//fetch row block from root template
	$rowBlock = $tpl->fetch('row');
	
	foreach($arr as $row){
		//fetch column block from row block
		$columnBlock = $rowBlock->fetch('column');
		
		if($row['id_cat'] == 0) { $row['catname'] = core::getLanguage('str', 'general'); }

		$row['body'] = preg_replace('/<br(\s\/)?>/siU', "", $row['body']);
		$row['body'] = Pnl::remove_html_tags($row['body']);
		$row['body'] = preg_replace('/\n/sU', "", $row['body']);
		$pos = strpos(substr($row['body'], 500), " ");
		$srttmpend = strlen($row['body']) > 500 ? '...' : '';
		$columnBlock->assign('CLASS_NOACTIVE', $row['active']);
		$columnBlock->assign('ROW_ID_TEMPLATE', $row['id_template']);
		$columnBlock->assign('ROW_CONTENT', substr($row['body'], 0, 500 + $pos) . (isset($srttmpend) ?  $srttmpend : ''));
		$columnBlock->assign('STR_SEND', core::getLanguage('str', 'send'));	
		$columnBlock->assign('STR_UP', core::getLanguage('str', 'up'));	
		$columnBlock->assign('STR_DOWN', core::getLanguage('str', 'down'));
		$columnBlock->assign('ROW_POS', $row['pos']);
		$columnBlock->assign('ROW_CATNAME', $row['catname']);	
		$columnBlock->assign('ROW_TMPLNAME', $row['tmplname']);		
		$columnBlock->assign('ROW_ACTIVE', $row['active'] == 'yes' ? core::getLanguage('str', 'yes') : core::getLanguage('str', 'no'));
		
		//assign modified column block back to row block
        $rowBlock->assign('column', $columnBlock);
	}	
	
	//assign modified row block back to root template
    $tpl->assign('row', $rowBlock);	
}

$tpl->assign('STR_CATEGORY', core::getLanguage('str', 'category'));	
$tpl->assign('ALERT_SELECT_ACTION', core::getLanguage('alert', 'select_action'));
$tpl->assign('ALERT_CONFIRM_REMOVE', core::getLanguage('alert', 'confirm_remove'));
$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
$tpl->assign('STR_PAUSE_SENDING', core::getLanguage('str', 'pause_sending'));
$tpl->assign('STR_STOP_SENDING', core::getLanguage('str', 'stop_sending'));
$tpl->assign('STR_REFRESH_SENDING', core::getLanguage('str', 'refresh_sending'));
$tpl->assign('ALERT_MALING_NOT_SELECTED', core::getLanguage('alert', 'maling_not_selected'));

$tpl->assign('STR_SENT', core::getLanguage('str', 'sent'));
$tpl->assign('STR_WASNT_SENT', core::getLanguage('str', 'send_status_no'));

//modal window
$tpl->assign('STR_TIME', core::getLanguage('str', 'time'));
$tpl->assign('STR_TIME_LEFT', core::getLanguage('str', 'time_left'));
$tpl->assign('STR_TIME_PASSED', core::getLanguage('str', 'time_passed'));
$tpl->assign('STR_SEND_TEST_EMAIL', core::getLanguage('str', 'send_test_email'));
$tpl->assign('STR_TOTAL', core::getLanguage('str', 'total'));
$tpl->assign('STR_GOOD', core::getLanguage('str', 'good'));
$tpl->assign('STR_BAD', core::getLanguage('str', 'bad'));
$tpl->assign('STR_SENDOUT_TO_SUBSCRIBERS', core::getLanguage('str', 'sendout_to_subscribers'));
$tpl->assign('STR_ONLINE_MAILINGLOG', core::getLanguage('str', 'online_mailinglog'));  
$tpl->assign('ALERT_ERROR_SERVER', core::getLanguage('alert', 'error_server'));

$tpl->assign('STR_ACTION', core::getLanguage('str', 'action'));
$tpl->assign('STR_ACTIVATE', core::getLanguage('str', 'activate'));
$tpl->assign('STR_SENDOUT', core::getLanguage('str', 'sendout'));
$tpl->assign('STR_DEACTIVATE', core::getLanguage('str', 'deactivate'));
$tpl->assign('STR_REMOVE', core::getLanguage('str', 'remove'));
$tpl->assign('STR_APPLY', core::getLanguage('str', 'apply'));

//pagination
$number = $data->getTotal();
$page = $data->getPageNumber();

if ($page != 1) {
	$pervpage = '<a href="./?page=1">&lt;&lt;</a>';
	$perv = '<a href="./?page=' . ($page - 1) . '">&lt;</a>';
}

if ($page != $number) {
	$nextpage = '<a href="./?page=' . ($page + 1) . '">&gt;</a>';
	$next = '<a href="./?page=' . $number . '">&gt;&gt;</a>';
}

if ($page - 2 > 0) $page2left = '<a href="./?page=' . ($page - 2) . '">...' . ($page - 2) . '</a>';
if ($page - 1 > 0) $page1left = '<a href="./?page=' . ($page - 1) . '">' .($page - 1) . '</a>';
if ($page + 2 <= $number) $page2right = '<a href="./?page=' . ($page + 2) . '">' . ($page + 2) . '...</a>';
if ($page + 1 <= $number) $page1right = '<a href="./?page=' . ($page + 1) . '">' . ($page + 1) . '</a>';

if ($number > 1){
	$paginationBlock = $tpl->fetch('pagination');
	$paginationBlock->assign('STR_PNUMBER', core::getLanguage('str', 'pnumber'));
	$paginationBlock->assign('CURRENT_PAGE', '<a>' . $page . '</a>');
	$paginationBlock->assign('PAGE1RIGHT', isset($page1right) ? $page1right : '');
	$paginationBlock->assign('PAGE2RIGHT', isset($page2right) ? $page2right : '');
	$paginationBlock->assign('PAGE1LEFT', isset($page1left) ?  $page1left : '');
	$paginationBlock->assign('PAGE2LEFT', isset($page2left) ? $page2left : '');
	$paginationBlock->assign('PERVPAGE', isset($pervpage) ? $pervpage : '');
	$paginationBlock->assign('PERV', isset($perv) ? $perv : '');
	$paginationBlock->assign('NEXTPAGE', isset($nextpage) ? $nextpage : '');
	$paginationBlock->assign('NEXT', isset($next) ? $next : '');
	$paginationBlock->assign('PNUMBER', $pnumber);
	$tpl->assign('pagination', $paginationBlock);
}

//footer
include_once core::pathTo('extra', 'footer.php');
		
//display content
$tpl->display();