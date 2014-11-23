<div id="send">
	<form action="post.php" method="POST">
        <input type="text" name="chan" value="#g4dg3t" />
        <input type="text" name="name" value="name" />
        <input type="text" name="msg" value="" />
        <input hidden="true" type="hidden" value="<?php echo $_SERVER['REQUEST_URI']; ?>" name="link" />
        <input type="submit" value="Submit" />
    </form>
</div>
<?php
$html = str_replace(array(chr(10), chr(13), ':', ' '),'', $_POST['chan']) . ' ' . str_replace(array(chr(10), chr(13), ':', ' '),'',$_POST['name']) . ' ' . $_POST['msg'];
$file = "post.txt"; 
$handle = fopen($file,"a");
fwrite($handle,$html);
fclose($handle);
header("Location:" . $_POST['link']);
exit();
?>