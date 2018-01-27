<?php

defined('LETTER') || exit('NewsLetter: access denied.');

// authorization
Auth::authorization();

$autInfo = Auth::getAutInfo(Auth::getAutId());

if (Pnl::CheckAccess($autInfo['role'], 'admin')) throw new Exception403(core::getLanguage('str', 'dont_have_permission_to_access'));

//include template
core::requireEx('libs', "html_template/SeparateTemplate.php");
$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");

$errors = array();

if (Core_Array::getRequest('action')) {
	if ($_FILES['file']['tmp_name']) {
		$ext = pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
	
		if ($ext == 'xls' || $ext == 'xlsx') {
			$result = $data->importFromExcel(Core_Array::getPost('id_cat'));
		} else {
			$result = $data->importFromText(Core_Array::getPost('id_cat'));
		}
		
		if (!$result) $errors[] = core::getLanguage('error', 'no_import');
	} else $errors[] = core::getLanguage('error', 'no_import_file');
}

$tpl->assign('TITLE_PAGE', core::getLanguage('title_page', 'import'));
$tpl->assign('TITLE', core::getLanguage('title', 'import'));
$tpl->assign('INFO_ALERT', core::getLanguage('info', 'import'));

include_once core::pathTo('extra', 'top.php');

//menu
include_once core::pathTo('extra', 'menu.php');

$temp = array();
$temp[] = 'iso-8859-1';
$temp[] = 'iso-8859-2';
$temp[] = 'iso-8859-3';
$temp[] = 'iso-8859-4';
$temp[] = 'iso-8859-5';
$temp[] = 'iso-8859-6';
$temp[] = 'iso-8859-7';
$temp[] = 'iso-8859-8';
$temp[] = 'iso-8859-9';
$temp[] = 'iso-8859-10';
$temp[] = 'iso-8859-13';
$temp[] = 'iso-8859-14';
$temp[] = 'iso-8859-15';
$temp[] = 'iso-8859-16';
$temp[] = 'koi8-r';
$temp[] = 'koi8-u';
$temp[] = 'windows-1250';
$temp[] = 'windows-1251';
$temp[] = 'windows-1252';
$temp[] = 'windows-1253';
$temp[] = 'windows-1254';
$temp[] = 'windows-1255';
$temp[] = 'windows-1256';
$temp[] = 'windows-1257';
$temp[] = 'windows-1258';
$temp[] = 'utf-8';

$charset = array();

foreach ($temp as $row) {
	$charset[$row] = Pnl::charsetlist($row);
}

asort($charset);

$option = '';

foreach ($charset as $key => $value) {
	$option .= '<option value="'.$key.'">'.$value.'</option>';
}

$tpl->assign('STR_BACK', core::getLanguage('str', 'return_back'));

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

if (isset($result)){
	$tpl->assign('MSG_ALERT', str_replace('%COUNT%', $result, core::getLanguage('msg', 'imported_emails')));
}

//form
$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
$tpl->assign('STR_DATABASE_FILE', core::getLanguage('str', 'database_file'));
$tpl->assign('BUTTON_ADD', core::getLanguage('button', 'import'));
$tpl->assign('STR_CATEGORY', core::getLanguage('str', 'category'));
$tpl->assign('OPTION', $option);
$tpl->assign('STR_CHARSET', core::getLanguage('str', 'charset'));
$tpl->assign('STR_NO', core::getLanguage('str', 'no'));

foreach ($data->getCategoryList() as $row) {
	$rowBlock = $tpl->fetch('row');
	$rowBlock->assign('ID_CAT', $row['id']);
	$rowBlock->assign('NAME', $row['name']);
	$tpl->assign('row', $rowBlock);
}

//footer
include_once core::pathTo('extra', 'footer.php');

// display content
$tpl->display();