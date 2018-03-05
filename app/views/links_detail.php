<?php

defined('LETTER') || exit('NewsLetter: access denied.');

// authorization
Auth::authorization();

$autInfo = Auth::getAutInfo(Auth::getAutId());

if (Pnl::CheckAccess($autInfo['role'], 'admin,moderator')) throw new Exception403(core::getLanguage('str', 'dont_have_permission_to_access'));

//horizontal menu
$tpl->assign('STR_EXPORT_LINKS', core::getLanguage('str', 'export_links'));
$tpl->assign('PROMPT_EXPORT_LINKS',   core::getLanguage('prompt', 'export_links'));

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
$order['url'] = "url";
$order['ip'] = "ip";
$order['country'] = "country";
$order['city'] = "city";

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

$arr = $data->getLinksArr($strtmp, $search, Core_Array::getRequest('page'), $pnumber);
//echo "<pre>";print_r($arr);echo "</pre>";
if ($arr){
	$number = $data->getTotal();
	$page = $data->getPageNumber();
        
	if (empty($search)){
		if ($page != 1) {
			$pervpage = '<a href="./?t=links&page=1' . $sort . '">&lt;&lt;</a>';
			$perv = '<a href="./?t=links&page=' . ($page - 1) . '' . $sort . '">&lt;</a>';
		}

		if ($page != $number) {
			$nextpage = '<a href="./?t=links&page=' . ($page + 1) . '' . $sort . '">&gt;</a>';
			$next = '<a href="./?t=links&page=' . $number . '' . $sort . '">&gt;&gt;</a>';
		}									

		if ($page - 2 > 0) $page2left = '<a href="./?t=links&page=' . ($page - 2) .'' . $sort . '">...'.($page - 2) . '</a>';
		if ($page - 1 > 0) $page1left = '<a href="./?t=links&page=' . ($page - 1) . '' . $sort . '">'.($page - 1) . '</a>';
		if ($page + 2 <= $number) $page2right = '<a href="./?t=links&page=' . ($page + 2) . '' . $sort . '">' . ($page + 2) . '...</a>';
		if ($page + 1 <= $number) $page1right = '<a href="./?t=links&page=' . ($page + 1) . '' . $sort . '">' . ($page + 1) . '</a>';
	} else {
		if ($page != 1) {
			$pervpage = '<a href="./?t=links&search=' . urlencode($search) . '&page=1' . $sort . '">&lt;&lt;</a>';
			$perv = '<a href="./?t=links&search=' . urlencode($search) . '&page=' . ($page - 1) . '' . $sort . '">&lt;</a>';
		}								

		if ($page != $number) {
			$nextpage = '<a href="./?t=links&search=' . urlencode($search).'&page=' . ($page + 1) . '' . $sort . '">&gt;</a>';
			$next = '<a href="./?t=links&search=' . urlencode($search) . '&page=' . $number . '' . $sort . '">&gt;&gt;</a>';
		}									

		if ($page - 2 > 0) $page2left = '<a href="./?t=links&search=' . urlencode($search) . '&page=' . ($page - 2) . '' . $sort . '">...' . ($page - 2) . '</a>';
		if ($page - 1 > 0) $page1left = '<a href="./?t=links&search=' . urlencode($search) . '&page=' . ($page - 1) . '' . $sort . '">' . ($page - 1).'</a>';
		if ($page + 2 <= $number) $page2right = '<a href=".?t=links&search=' . urlencode($search) . '&page=' . ($page + 2) . '' . $sort . '">' . ($page + 2) . '...</a>';
		if ($page + 1 <= $number) $page1right = '<a href="./?t=links&search=' . urlencode($search) . '&page=' . ($page + 1) . '' . $sort . '">' . ($page + 1) . '</a>';
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
	$rowBlock->assign('GET_URL', $_GET['url']);
	$rowBlock->assign('GET_IP', $_GET['ip_links']);
        $rowBlock->assign('GET_COUNTRY', $_GET['country_links']);
        $rowBlock->assign('GET_CITY', $_GET['city_links']);
        $rowBlock->assign('GET_CREATED_AT', $_GET['created_at']);
	$rowBlock->assign('TH_CLASS_NAME', $thclass["name"]);	
	$rowBlock->assign('TH_CLASS_EMAIL', $thclass["email"]);	
	$rowBlock->assign('TH_CLASS_URL', $thclass["url"]);	
	$rowBlock->assign('TH_CLASS_IP', $thclass["ip_links"]);
        $rowBlock->assign('TH_CLASS_COUNTRY', $thclass["country_links"]);
        $rowBlock->assign('TH_CLASS_CITY', $thclass["city_links"]);
        $rowBlock->assign('TH_CLASS_CREATED_AT', $thclass["created_at"]);
	
	$rowBlock->assign('ALERT_CONFIRM_REMOVE', core::getLanguage('alert', 'confirm_remove'));
	$rowBlock->assign('ALERT_SELECT_ACTION',  core::getLanguage('alert', 'select_action'));
	$rowBlock->assign('TABLE_NAME', core::getLanguage('str', 'name'));
	$rowBlock->assign('TABLE_EMAIL', core::getLanguage('str', 'email'));
	$rowBlock->assign('TABLE_URL', core::getLanguage('str', 'url'));
	$rowBlock->assign('TABLE_IP', core::getLanguage('str', 'ip'));
        $rowBlock->assign('TABLE_COUNTRY', core::getLanguage('str', 'country'));
        $rowBlock->assign('TABLE_CITY', core::getLanguage('str', 'city'));
        $rowBlock->assign('TABLE_CREATED_AT', core::getLanguage('str', 'created_at'));
	$rowBlock->assign('TABLE_ACTION', core::getLanguage('str', 'action'));
        
	foreach ($arr as $row) {
		$columnBlock = $rowBlock->fetch('column');
		/*$columnBlock->assign('STATUS_CLASS', $row['status'] == 'noactive' ? 'noactive' : '');*/
		//$columnBlock->assign('STR_CHECK_BOX', core::getLanguage('str', 'check_box'));
		$columnBlock->assign('ID_LINKS', $row['id']);
		$columnBlock->assign('NAME', $row['name']);
		$columnBlock->assign('EMAIL', $row['email']);
		$columnBlock->assign('URL', $row['url']);
                $columnBlock->assign('IP', $row['ip_links']);
                $columnBlock->assign('COUNTRY', $row['country_links']);
                $columnBlock->assign('CITY', $row['city_links']);
                $columnBlock->assign('CREATED_AT', $row['created_at']);
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
	
	$rowBlock->assign('STR_NUMBER_OF_LINKS', core::getLanguage('str', 'number_of_links'));
	$rowBlock->assign('NUMBER_OF_LINKS', $data->countLinks());
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