 <?php

//MODIFY TO YOUR OWN SETTINGS

//CONNECTS TO YOUR DATABASE
$c = mysql_connect("server", "username", "password");
$db = mysql_select_db("your_database", $c);

//TABLES FOR THE CONTENT AND THE RATINGS (MODIFY IF TABLE NAMES ARE DIFFERENT)
$content = 'content';
$ratings = 'ratings';

$ip = $_SERVER["REMOTE_ADDR"]; //IP ADDRESS

?> 