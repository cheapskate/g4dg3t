<?php
$html = str_replace(array(chr(10), chr(13), ':', ' '),'', $_POST['chan']) . ' ' . str_replace(array(chr(10), chr(13), ':', ' '),'',$_POST['name']) . ' ' . $_POST['msg'];
$file = "post.txt"; 
$handle = fopen($file,"a");
fwrite($handle,$html);
fclose($handle);
header("Location:" . $_POST['link']);
exit();
?>