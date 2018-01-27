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

if (Core_Array::getRequest('action')) {
	$name = htmlspecialchars(trim(Core_Array::getPost('name')));
	$email = strtolower(trim(Core_Array::getPost('email')));
	
	if (empty($name)) $errors[] = core::getLanguage('error', 'empty_email');
	if (!empty($email) && Pnl::check_email($email)) $errors[] = core::getLanguage('error', 'wrong_email');
	
	if (empty($errors)) {
		$fields = array();
		$fields['name'] = $name;
		$fields['email'] = $email;
	
		if ($data->editUser($fields, Core_Array::getPost('id_user'), Core_Array::getPost('id_cat'))){
			header("Location: ./?t=subscribers");
			exit;	
		} else $errors[] = core::getLanguage('error', 'edit_user');
	}
}

$tpl->assign('TITLE_PAGE', core::getLanguage('title_page', 'edit_user'));
$tpl->assign('TITLE', core::getLanguage('title', 'edit_user'));
$tpl->assign('INFO_ALERT', core::getLanguage('info', 'edit_user'));

//error alert
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

include_once core::pathTo('extra', 'top.php');

//menu
include_once core::pathTo('extra', 'menu.php');

$tpl->assign('RETURN_BACK',core::getLanguage('str', 'return_back'));
$tpl->assign('PHP_SELF', $_SERVER['REQUEST_URI']);
$tpl->assign('FORM_NAME', core::getLanguage('str', 'name'));
$tpl->assign('FORM_EMAIL', core::getLanguage('str', 'email'));
$tpl->assign('FORM_CATEGORY', core::getLanguage('str', 'category'));
$tpl->assign('BUTTON', core::getLanguage('button', 'edit'));

$user = $data->getUserEdit(Core_Array::getGet('id_user'));

$tpl->assign('NAME', Core_Array::getPost('name') ? $_POST['name'] : $user['name']);
$tpl->assign('EMAIL', Core_Array::getPost('email') ? $_POST['email'] : $user['email']);

$arr = $data->getGategoryList();

if (is_array($arr)){
	for($i=0; $i<count($arr); $i++){
		$rowBlock = $tpl->fetch('categories_list');
		$rowBlock->assign('ID_CAT',$arr[$i]['id_cat']);
		$rowBlock->assign('CATEGORY_NAME', $arr[$i]['name']);
		
		if($data->checkUserSub($arr[$i]['id_cat'],$_GET['id_user'])>0){
			$rowBlock->assign('CHECKED', 'checked');
		}
		
		$tpl->assign('categories_list', $rowBlock);
	}
}

$tpl->assign('ID_USER', $user['id_user']);

//footer
include_once core::pathTo('extra', 'footer.php');

// display content
$tpl->display();