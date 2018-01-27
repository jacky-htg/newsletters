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

if (isset($_REQUEST['clear_log'])){
	if ($data->clearLog())
		$alert_success = core::getLanguage('msg', 'clear_log');
	else
		$errors[] = core::getLanguage('error', 'clear_log');
}

$order = array();
$order['name'] = "s.name";
$order['email'] = "email";
$order['time'] = "a.time";
$order['success'] = "success";
$order['readmail'] = "readmail";
$order['catname'] = "c.name";
	
$strtmp = "id_log";

foreach($order as $parametr => $field){
	if (isset($_GET[$parametr])){
		if ($_GET[$parametr] == "up"){
			$_GET[$parametr] = "down";
			$strtmp = $field;
			$thclass[$parametr] = 'headerSortDown';
		} else{
			$_GET[$parametr] = "up";
			$strtmp = "" . $field . " DESC";
			$thclass[$parametr] = 'headerSortUp';
		}
	} else {
		$_GET[$parametr] = "up";
		$thclass[$parametr] = 'headerUnSort';
	}
}

$tpl->assign('TITLE_PAGE', core::getLanguage('title_page', 'log'));
$tpl->assign('TITLE', core::getLanguage('title', 'log'));
$tpl->assign('INFO_ALERT', core::getLanguage('info', 'log'));

include_once core::pathTo('extra', 'top.php');

//menu
include_once core::pathTo('extra', 'menu.php');

//pagination
if (isset($_COOKIE['pnumber_log']))
	$pnumber = (int)$_COOKIE['pnumber_log'];
else
	$pnumber = 20;

if (Core_Array::getRequest('id_log')){
	$blockDetailLog = $tpl->fetch('DetailLog');
	$blockDetailLog->assign('STR_BACK', core::getLanguage('str', 'return_back'));
	$blockDetailLog->assign('TH_TABLE_MAILER', core::getLanguage('str', 'mailer'));
	$blockDetailLog->assign('TH_TABLE_CATNAME', core::getLanguage('str', 'category'));
	$blockDetailLog->assign('TH_TABLE_TIME', core::getLanguage('str', 'time'));
	$blockDetailLog->assign('TH_TABLE_STATUS', core::getLanguage('str', 'status'));
	$blockDetailLog->assign('TH_TABLE_READ', core::getLanguage('str', 'read'));
	$blockDetailLog->assign('TH_TABLE_ERROR', core::getLanguage('str', 'error'));
	
	$blockDetailLog->assign('THCLASS_NAME', $thclass["name"]);
	$blockDetailLog->assign('THCLASS_EMAIL', $thclass["email"]);
	$blockDetailLog->assign('THCLASS_CATNAME',$thclass["catname"]);
	$blockDetailLog->assign('THCLASS_TIME', $thclass["time"]);
	$blockDetailLog->assign('THCLASS_SUCCESS', $thclass["success"]);
	$blockDetailLog->assign('THCLASS_READMAIL', $thclass["readmail"]);

	$arr = $data->getDetaillog($strtmp, Core_Array::getRequest('id_log'), 50);

	if (is_array($arr)){
		$blockDetailLog->assign('ID_LOG', $_GET['id_log']);
		$blockDetailLog->assign('GET_NAME', $_GET['name']);
		$blockDetailLog->assign('GET_EMAIL', $_GET['email']);
		$blockDetailLog->assign('GET_CATNAME', $_GET['catname'] );
		$blockDetailLog->assign('GET_TIME', $_GET['time']);
		$blockDetailLog->assign('GET_SUCCESS', $_GET['success']);
		$blockDetailLog->assign('GET_READMAIL', $_GET['readmail']);

		foreach($arr as $row){
			$status = $row['success'] == 'yes' ? core::getLanguage('str', 'send_status_yes') : core::getLanguage('str', 'send_status_no');  
			$read = $row['readmail'] == 'yes' ? core::getLanguage('str', 'yes') : core::getLanguage('str', 'no'); 
			$catname = $row['id_cat'] == 0 ? core::getLanguage('str', 'general') : $row['catname'];		
		
			$rowBlock = $blockDetailLog->fetch('row');
			$rowBlock->assign('NAME', $row['name']);
			$rowBlock->assign('EMAIL', $row['email']);
			$rowBlock->assign('CATNAME', $catname);
			$rowBlock->assign('TIME', $row['time']);
			$rowBlock->assign('STATUS', $status);
			$rowBlock->assign('READ', $read);
			$rowBlock->assign('ERRORMSG', $row['errormsg']);
			
			$blockDetailLog->assign('row', $rowBlock);
		}	
	}
	
	$tpl->assign('DetailLog', $blockDetailLog);
} else {
	$blockLogList = $tpl->fetch('LogList');	
	
	//alert error
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

	//alert success
	if (isset($alert_success)){
		$blockLogList->assign('MSG_ALERT', $alert_success);
	}
	
	$blockLogList->assign('STR_CLEAR_LOG', core::getLanguage('str', 'clear_log'));
	$blockLogList->assign('TH_TABLE_TIME', core::getLanguage('str', 'time'));
	$blockLogList->assign('TH_TABLE_TOTAL', core::getLanguage('str', 'total'));
	$blockLogList->assign('TH_TABLE_SENT', core::getLanguage('str', 'sent'));
	$blockLogList->assign('TH_TABLE_NOSENT', core::getLanguage('str', 'nosent'));
	$blockLogList->assign('TH_TABLE_READ', core::getLanguage('str', 'read'));
	$blockLogList->assign('TH_TABLE_DOWNLOAD_REPORT', core::getLanguage('str', 'download_report'));

	$arr = $data->getLogArr($pnumber, Core_Array::getRequest('page'));

	if (is_array($arr)) {
		foreach ($arr as $row) {
			$rowBlock = $blockLogList->fetch('row');
			$rowBlock->assign('TIME', $row['time']);
			$rowBlock->assign('ID_LOG', $row['id_log']);
			$total = $data->countLetters($row['id_log']);
			$total_sent = $data->countSent($row['id_log']);
			$total_nosent = $total - $total_sent;
			$rowBlock->assign('TOTAL', $total);
			$rowBlock->assign('TOTAL_SENT', $total_sent);
			$rowBlock->assign('TOTAL_NOSENT', $total_nosent);
			$total_read = $data->countRead($row['id_log']);
			$rowBlock->assign('TOTAL_READ', $total_read);

			if (Pnl::CheckAccess($autInfo['role'], 'admin,moderator') === false) $rowBlock->assign('ALLOW_DOWNLOAD', 'yes');

			$rowBlock->assign('STR_DOWNLOAD', core::getLanguage('str', 'download'));
			$blockLogList->assign('row', $rowBlock);
		}
	}

	$number = $data->getTotal();
	$page = $data->getPageNumber();

	if ($page != 1) {
		$pervpage = '<a href="./?t=log&page=1">&lt;&lt;</a>';
		$perv = '<a href="./?t=log&' . ($page - 1) . '">&lt;</a>';
	}							

	if ($page != $number) {
		$nextpage = '<a href="./?t=log&' . ($page + 1) . '">&gt;</a>';
		$next = '<a href="./?t=log&page=' . $number . '">&gt;&gt;</a>';
	}									

	if ($page - 2 > 0) $page2left = '<a href="./?t=log&page=' . ($page - 2) . '">...' . ($page - 2) . '</a>';
	if ($page - 1 > 0) $page1left = '<a href="./?t=log&page=' . ($page - 1) . '">' . ($page - 1) . '</a>';
	if ($page + 2 <= $number) $page2right = '<a href="./?t=log&page=' . ($page + 2) . '">' . ($page + 2) . '...</a>';
	if ($page + 1 <= $number) $page1right = '<a href="./?t=log&page=' . ($page + 1) . '">' . ($page + 1) . '</a>';

	if ($number > 1) {
		$paginationBlock = $blockLogList->fetch('pagination');
		$paginationBlock->assign('STR_PNUMBER', core::getLanguage('str', 'pnumber'));
		$paginationBlock->assign('CURRENT_PAGE','<a>' . $page . '</a>');
		$paginationBlock->assign('PAGE1RIGHT', isset($page1right) ? $page1right : '');
		$paginationBlock->assign('PAGE2RIGHT', isset($page2right) ? $page2right : '');
		$paginationBlock->assign('PAGE1LEFT', isset($page1left) ? $page1left : '');
		$paginationBlock->assign('PAGE2LEFT', isset($page2left) ? $page2left : '');
		$paginationBlock->assign('PERVPAGE', isset($pervpage) ? $pervpage : '');
		$paginationBlock->assign('NEXTPAGE', isset($nextpage) ? $nextpage : '');
		$paginationBlock->assign('PERV', isset($perv) ? $perv : '');
		$paginationBlock->assign('NEXT', isset($next) ? $next : '');
		$blockLogList->assign('pagination', $paginationBlock);			
	}
	
	$tpl->assign('LogList', $blockLogList);
}

$tpl->assign('SHOW_MORE', core::getLanguage('str', 'show_more'));
$tpl->assign('STR_CLICK', core::getLanguage('str', 'click'));
$tpl->assign('STR_THERE_ARE_NO_MORE_ENTRIES', core::getLanguage('str', 'there_are_no_more_entries'));

//footer
include_once core::pathTo('extra', 'footer.php');

// display content
$tpl->display();