<?php

defined('LETTER') || exit('NewsLetter: access denied.');

// authorization
Auth::authorization();

$autInfo = Auth::getAutInfo(Auth::getAutId());

if (Pnl::CheckAccess($autInfo['role'], 'admin,moderator,editor')) throw new Exception403(core::getLanguage('str', 'dont_have_permission_to_access'));

//include template
core::requireEx('libs', "html_template/SeparateTemplate.php");
$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");

$tpl->assign('TITLE_PAGE', core::getLanguage('title_page', 'whois'));
$tpl->assign('TITLE', core::getLanguage('title', 'whois'));
$tpl->assign('INFO_ALERT', core::getLanguage('info', 'whois'));

include_once core::pathTo('extra', 'top.php');

//menu
include_once core::pathTo('extra', 'menu.php');

$tpl->assign('RETURN_BACK', core::getLanguage('str', 'return_back'));

$errors = array();

if (Core_Array::getGet('ip')){
    $sock = @fsockopen("whois.ripe.net", 43, $errno, $errstr);

    if (!$sock) {
        $errors[] = $errno($errstr);
    } else {
        $whoisBlock = $tpl->fetch('whois');
        $whoisBlock->assign('TH_TABLE_IP_INFO', core::getLanguage('str', 'ip_info'));

        fputs ($sock, $_GET['ip']."\r\n");

        while (!feof($sock)){
            $rowBlock = $whoisBlock->fetch('row');
            $rowBlock->assign('SOCK', str_replace(":",":&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" ,fgets ($sock,128)));
            $whoisBlock->assign('row', $rowBlock);
        }

        $tpl->assign('whois', $whoisBlock);
    }
} else {
    $errors[] = core::getLanguage('error', 'service_unavailable');
}

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

//footer
include_once core::pathTo('extra', 'footer.php');

// display content
$tpl->display();