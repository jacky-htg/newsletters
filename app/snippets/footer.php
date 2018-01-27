<?php

defined('LETTER') || exit('NewsLetter: access denied.');

$tpl->assign('STR_LOGO',core::getLanguage('str', 'logo'));
$tpl->assign('STR_AUTHOR',core::getLanguage('str', 'author'));