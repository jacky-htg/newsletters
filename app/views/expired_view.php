<?php

defined('LETTER') || exit('NewsLetter: access denied.');

// authorization
Auth::authorization();

$autInfo = Auth::getAutInfo(Auth::getAutId());

//include template
core::requireEx('libs', "html_template/SeparateTemplate.php");
$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");

$errors = array();

if (Core_Array::getRequest('action')){
    $license_key = trim(Core_Array::getPost('license_key'));

    if (empty($license_key)) $errors[] = core::getLanguage('error', 'enter_licensekey');
    if (!empty($license_key)) {
        $arr = Pnl::checkLicensekey($license_key);
        if (isset($arr['error'])) {
            $arr['error'] = str_replace('LICENSE_IS_USED', core::getLanguage('error', 'license_is_used'), $arr['error']);
            $arr['error'] = str_replace('LICENSE_NOT_FOUND', core::getLanguage('error', 'license_not_found'), $arr['error']);
            $arr['error'] = str_replace('ERROR_CHECKING_LICENSE', core::getLanguage('error', 'error_checking_license'), $arr['error']);
            $errors[] = $arr['error'];
        }
    }

    if (empty($errors)) {
        if ($data->updateLicenseKey($license_key)){
            core::updateLicensekey($license_key);
            $success_msg = core::getLanguage('msg', 'changes_added');
        } else {
            $errors[] = core::getLanguage('error', 'web_apps_error');
        }
    }
}

//alert
if (isset($success_msg)){
    $tpl->assign('MSG_ALERT', $success_msg);
}

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

$tpl->assign('TITLE_PAGE',core::getLanguage('title_page', 'expired'));
$tpl->assign('TITLE',core::getLanguage('title', 'expired'));

include_once core::pathTo('extra', 'top.php');

//menu
include_once core::pathTo('extra', 'menu.php');

$licenseinfo = core::getLicenseInfo();

if ($licenseinfo['license_type'] == 'demo')
    $tpl->assign('STR_MSG', core::getLanguage('str', 'demo_version_has_expired'));
elseif ($licenseinfo['license_type'] == 'single' || $licenseinfo['license_type'] == 'multi')
    $tpl->assign('STR_MSG', core::getLanguage('str', 'licensekey_has_expired'));

//form
$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
$tpl->assign('STR_LICENSE_KEY', core::getLanguage('str', 'license_key'));
$tpl->assign('BUTTON_SAVE', core::getLanguage('button', 'save'));
$tpl->assign('STR_START_UPDATE', core::getLanguage('str', 'start_update'));
$tpl->assign('MSG_UPDATE_COMPLETED', core::getLanguage('msg', 'update_completed'));

//value
$tpl->assign('LICENSE_KEY', Core_Array::getPost('license_key') ? $_POST['license_key'] : $data->getLicenseKey());

//footer
include_once core::pathTo('extra', 'footer.php');

// display content
$tpl->display();