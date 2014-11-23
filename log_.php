<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pwd = '';

$database = 'ircbot';
$table = 'ChatLog';

if (!mysql_connect($db_host, $db_user, $db_pwd))
    die("Can't connect to database");

if (!mysql_select_db($database))
    die("Can't select database");

// sending query
$result = mysql_query("SELECT * FROM {$table}");
if (!$result) {
    die("Query to show fields from table failed");
}

//$fields_num = mysql_num_fields($result);

//echo "<h1>Table: {$table}</h1>";
echo "<table border='0'><tr>";
// printing table headers
//for($i=0; $i<$fields_num; $i++)
//{
//	
//    $field = mysql_fetch_field($result);
//	if ($field->name !== "id")
//	{
//   	echo "<td>{$field->name}</td>";
//	}
//	
//}
//echo "</tr>\n";
// printing table rows
while($row = mysql_fetch_row($result))
{
	$i = 1;
    echo "<tr>";

    // $row is array... foreach( .. ) puts every element
    // of $row to $cell variable
    foreach($row as $cell)
	{
		switch($i) {
			case 3:
				echo "<td>[$cell]</td>";
				break;
			case 4:
				echo "<td>$cell</td>";
				break;
		}
		$i++;
	}

    echo "</tr>\n";
}
mysql_free_result($result);
?>