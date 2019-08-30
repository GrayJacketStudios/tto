<?php
require_once ("cfg.php");

$link = mysql_connect($db_host,$db_user,$db_password);
$db_selected = mysql_select_db($db_dbname, $link);
?>
