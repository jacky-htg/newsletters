<?php

defined('LETTER') || exit('NewsLetter: access denied.');

if (Core_Array::getRequest('action')){
	$name = trim(Core_Array::getPost('name'));
	$email = trim(Core_Array::getPost('email'));
	if (empty($name)) Pnl::error(core::getLanguage('error', 'empty_your_name'));
	if (empty($email)) Pnl::error(core::getLanguage('error', 'empty_email'));
	if (Pnl::check_email($email)) Pnl::error(core::getLanguage('error', 'wrong_email'));
	if ($data->checkExistEmail($email)) Pnl::error(core::getLanguage('error', 'subscribe_is_already_done'));

	$fields = array();
	$fields['id_user']   = 0;
	$fields['name']      = $name;
	$fields['email']     = $email;
	$fields['ip']        = Pnl::getIP();
	$fields['token']     = Pnl::getRandomCode();
	$fields['time']      = date("Y-m-d H:i:s");
 	$fields['status']    = core::getSetting("require_confirmation") == 'yes' ? 'noactive' : 'active';

	$insert_id = $data->makeSubscribe($fields);

	if ($insert_id){
		$isert = $data->insertSubs($insert_id, Core_Array::getRequest('id_cat'));
		$result = $data->sendNotification($insert_id);

		echo '<!DOCTYPE html>';
		echo "<html>\n";
		echo "<head>\n";
		echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
		echo "<title>" . core::getLanguage('subject', 'subscription') . "</title>\n";
		echo "</head>\n";
		echo "<body>\n";
		echo '<p style="text-align: center">';

		if (core::getSetting("require_confirmation") == "yes")
			echo core::getLanguage('msg', 'add_subscribe1');
		else
			echo core::getLanguage('msg', 'add_subscribe2');

		echo "<br><br><a href=http://" . $_SERVER['SERVER_NAME'] . ">" . core::getLanguage('str', 'go_to_homepage') . "</a>\n";
		echo "</p>\n";
		echo "</body>\n";
		echo "</html>";
		exit;
	} else {
		Pnl::error(core::getLanguage('error', 'subscribe'));
	}
}

//require temlate class
core::requireEx('libs', "html_template/SeparateTemplate.php");
$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");

//form
$tpl->assign('TITLE_SUBSCRIBE', core::getLanguage('title', 'subscribe'));
$tpl->assign('ACTION', "http://" . $_SERVER["SERVER_NAME"] . Pnl::root() . "?t=subform");
$tpl->assign('STR_NAME', core::getLanguage('table', 'name'));
$tpl->assign('STR_EMAIL', core::getLanguage('table', 'email'));
$tpl->assign('BUTTON_SUBSCRIBE', core::getLanguage('button', 'subscribe'));

$arr = $data->getCategoryList();

foreach ($arr as $row) {
	$rowBlock = $tpl->fetch('row');
	$rowBlock->assign('ID_CAT', $row['id_cat']);
	$rowBlock->assign('NAME', $row['name']);
	$tpl->assign('row', $rowBlock);
}

// display content
$tpl->display();