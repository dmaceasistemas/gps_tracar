<?php
/*
$username="gps";
$password="gps@m0n1t0r";
$database="traccar_new";
$server="172.16.0.180";
*/

$username="root";
$password="123456";
$database="traccar";
$server="localhost";

// Opens a connection to a MySQL server
$connection=mysql_connect ($server, $username, $password);
if (!$connection) {
  die('Not connected : ' . mysql_error());
}

// Set the active MySQL database
$db_selected = mysql_select_db($database, $connection);
if (!$db_selected) {
  die ('Can\'t use db : ' . mysql_error());
}
?>
