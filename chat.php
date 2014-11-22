<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pwd = '';
$database = 'ircbot';
$table = 'Chat';
if (!mysql_connect($db_host, $db_user, $db_pwd))
    die("Can't connect to database");
if (!mysql_select_db($database))
    die("Can't select database");
$result = mysql_query("SELECT * FROM {$table}");
if (!$result) {
    die("Query to show fields from table failed");
}
echo "<table border='0' width='100%'>";
while($row = mysql_fetch_row($result))
{
	$i = 1;
    echo "<tr>";
    foreach($row as $cell)
	{
		switch($i) {
			//case 3:
			//	echo "<td>[$cell]</td>";
			//	break;
			case 4:
				echo "<td class=\"ircline\">$cell</td>";
				break;
		}
		$i++;
	}
    echo "</tr>\n";
}
mysql_free_result($result);
?>