<?php

defined('LETTER') || exit('NewsLetter: access denied.');

// authorization
Auth::authorization();

Auth::logOut();

$redirect = $_SERVER['HTTP_REFERER'] ? $_SERVER['HTTP_REFERER'] : '/';
	
header("Location: " . $redirect . "");

exit();