<!DOCTYPE html>
<html>
<head>
<title>Subscript</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<body>
<?php

$url = "http://".$_SERVER["SERVER_NAME"].root()."index.php?t=subform";

$get_content = file($url);
$get_content = implode($get_content, "\r\n");

preg_match("/<div class=\"subform\">(.*)<\/div>/isU", $get_content, $out);

echo $out[1];

function root()
{
	if(dirname($_SERVER['SCRIPT_NAME']) == '/' | dirname($_SERVER['SCRIPT_NAME']) == '\\')
		return '/';
	else
		return dirname($_SERVER['SCRIPT_NAME']) . '/';
}

?>
</body>
</html>