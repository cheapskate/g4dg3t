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
$result = mysql_query("TRUNCATE TABLE {$table}");
if (!$result) {
    die("Query to show fields from table failed");
}


$table = 'ChatLog';
if (!mysql_connect($db_host, $db_user, $db_pwd))
    die("Can't connect to database");
if (!mysql_select_db($database))
    die("Can't select database");
$result = mysql_query("TRUNCATE TABLE {$table}");
if (!$result) {
    die("Query to show fields from table failed");
}
?>