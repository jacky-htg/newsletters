<?php

defined('LETTER') || exit('NewsLetter: access denied.');

$tpl->assign('ACTIVE_MENU', Core_Array::getGet('t'));

$tpl->assign('MENU_TEMPLATES_TITLE', core::getLanguage('menu', 'templates_title'));
$tpl->assign('MENU_TEMPLATES', core::getLanguage('menu', 'templates_name'));
$tpl->assign('MENU_CREATE_NEW_TEMPLATE_TITLE', core::getLanguage('menu', 'create_new_template_title'));
$tpl->assign('MENU_CREATE_NEW_TEMPLATE', core::getLanguage('menu', 'create_new_template_name'));
$tpl->assign('MENU_SUBSCRIBERS_TITLE', core::getLanguage('menu', 'subscribers_title'));
$tpl->assign('MENU_SUBSCRIBERS', core::getLanguage('menu', 'subscribers_name'));
$tpl->assign('MENU_CATEGORY_TITLE', core::getLanguage('menu', 'category_title'));
$tpl->assign('MENU_CATEGORY', core::getLanguage('menu', 'category_name'));
$tpl->assign('MENU_FEEDBAK_TITLE', core::getLanguage('menu', 'feedback_title'));
$tpl->assign('MENU_FEEDBACK', core::getLanguage('menu', 'feedback_name'));
$tpl->assign('MENU_LINKS_TITLE', core::getLanguage('menu', 'links_title'));
$tpl->assign('MENU_LINKS', core::getLanguage('menu', 'links_name'));
$tpl->assign('MENU_SETTINGS_TITLE', core::getLanguage('menu', 'settings_title'));
$tpl->assign('MENU_SETTINGS', core::getLanguage('menu', 'settings_name'));
$tpl->assign('MENU_INTERFACE_SETTINGS_TITLE', core::getLanguage('menu', 'interface_settings_title'));
$tpl->assign('MENU_INTERFACE_SETTINGS', core::getLanguage('menu', 'interface_settings'));
$tpl->assign('MENU_SMTP_TITLE', core::getLanguage('menu', 'SMTP_TITLE'));
$tpl->assign('MENU_SMTP', core::getLanguage('menu', 'smtp'));
$tpl->assign('MENU_ACCOUNTS_TITLE', core::getLanguage('menu', 'accounts_title'));
$tpl->assign('MENU_ACCOUNTS', core::getLanguage('menu', 'accounts'));
$tpl->assign('MENU_LICENSE_KEY_TITLE', core::getLanguage('menu', 'license_key_title'));
$tpl->assign('MENU_LICENSE_KEY_TITLE', core::getLanguage('menu', 'license_key'));
$tpl->assign('MENU_IMPORT_TITLE', core::getLanguage('menu', 'import_title'));
$tpl->assign('MENU_IMPORT', core::getLanguage('menu', 'import_name'));
$tpl->assign('MENU_EXPORT_TITLE', core::getLanguage('menu', 'export_title'));
$tpl->assign('MENU_EXPORT', core::getLanguage('menu', 'export_name'));
$tpl->assign('MENU_LOG_TITLE', core::getLanguage('menu', 'log_title'));
$tpl->assign('MENU_LOG', core::getLanguage('menu', 'log_name'));
$tpl->assign('MENU_MAILING_OPTIONS_TITLE', core::getLanguage('menu', 'mailing_options_title'));
$tpl->assign('MENU_MAILING_OPTIONS', core::getLanguage('menu', 'mailing_options'));
$tpl->assign('MENU_UPDATE_TITLE', core::getLanguage('menu', 'update_title'));
$tpl->assign('MENU_UPDATE', core::getLanguage('menu', 'update'));