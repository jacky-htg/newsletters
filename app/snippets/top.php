<?php

defined('LETTER') || exit('NewsLetter: access denied.');

$tpl->assign('SCRIPT_VERSION', VERSION);
$tpl->assign('STR_WARNING', core::getLanguage('str', 'warning'));
$tpl->assign('INFO_ALERT', core::getLanguage('info', 'edit_user'));
$tpl->assign('STR_ERROR', core::getLanguage('str', 'error'));
$tpl->assign('STR_LOGOUT', core::getLanguage('str', 'logout'));
$tpl->assign('STR_LAUNCHEDMAILING', core::getLanguage('str', 'launchedmailing'));
$tpl->assign('STR_STOPMAILING', core::getLanguage('str', 'stopmailing'));
$tpl->assign('MAILING_STATUS', Mailing::getCurrentMailingStatus(Auth::getAutId()));
$tpl->assign('LANGUAGE', core::getSetting("language"));
$tpl->assign('ACCOUNT_LOGIN', $autInfo['login']);
$tpl->assign('ACCOUNT_ROLE', $autInfo['role']);
$tpl->assign('SYS_ERROR_MSG', !empty(core::$system_error) ? Pnl::sys_error_msg(core::$system_error) : '');

if (core::expired_day_count() > 0)
    $alert_expire_license_msg = str_replace('%DAYS%', core::expired_day_count(),  core::getLanguage('msg', 'demo_version_will_expire'));
elseif (core::expired_day_count() == 0)
    $alert_expire_license_msg = core::getLanguage('msg', 'demo_version_will_expire_today');

//if (isset($alert_expire_license_msg)) $tpl->assign('ALERT_EXPIRE_LICENSE_MSG', $alert_expire_license_msg);
