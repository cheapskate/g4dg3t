<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<style>
#console {/*
	overflow-x:hidden;
	overflow-y:scroll;
	/max-height: 1200px;
*/}
</style>
<script language="javascript">
	setInterval(function() {
		
		var client = new XMLHttpRequest();
		client.open('GET', 'chat.txt');
		client.onreadystatechange = function() {
			document.getElementById("console").innerHTML = client.responseText;
			//document.getElementById("console").scrollTop = document.getElementById("console").scrollHeight;
		}
		client.send();
	},5000);
</script>
</head>

<body>
<div id="console"></div>
<div id="send">
	<form action="post.php" method="POST">
        <input type="text" name="chan" value="#g4dg3t" />
        <input type="text" name="name" value="name" />
        <input type="text" name="msg" value="" />
        <input hidden="true" type="hidden" value="<?php echo $_SERVER['REQUEST_URI']; ?>" name="link" />
        <input type="submit" value="Submit" />
    </form>
</div>
</body>
</html>