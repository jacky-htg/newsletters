<?php

defined('LETTER') || exit('NewsLetter: access denied.');

if (empty($_GET['id'])) Pnl::error(core::getLanguage('error', 'activate_subscription'));
if (empty($_GET['token'])) Pnl::error(core::getLanguage('error', 'activate_subscription'));

$token = $data->getToken(Core_Array::getGet('id'));

if ($token == $_GET['token']) {
    if ($data->makeActivateSub(Core_Array::getGet('id'))) {
        echo '<!DOCTYPE html>';
        echo "<html>\n";
        echo "<head>\n";
        echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\">\n";
        echo "<title>" . core::getLanguage('str', 'title_activate_sub') . "</title>\n";
        echo "</head>\n";
        echo "<body>\n";
        echo "<p style=\"text-align: center\">" . core::getLanguage('msg', 'successful_activation') . "</p>\n";
        echo "</body>\n";
        echo "</html>";
    } else Pnl::error(core::getLanguage('error', 'activate_subscription'));
} else Pnl::error(core::getLanguage('error', 'activate_subscription'));