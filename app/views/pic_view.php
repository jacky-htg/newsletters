<?php

defined('LETTER') || exit('NewsLetter: access denied.');

if ( Core_Array::getRequest('id_template') && Core_Array::getRequest('id_user')){
	$result = $data->countUser(Core_Array::getRequest('id_template'), Core_Array::getRequest('id_user'));
}

$img = ImageCreateTrueColor(1,1);
header ("Content-type: image/gif");
imagegif($img);
imagedestroy($img);