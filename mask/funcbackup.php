<?php
  require_once ("../cgi/dbcfg.php");
  
  //TEST BACKUP  
  $nomefile = $db_dbname."_".date("Ymd_Hi");
  
  exec ("mysqldump -u ".$db_user." -p".$db_password." ".$db_dbname." > ../backup/".$nomefile.".sql");
  exec ("gzip ../backup/".$nomefile.".sql");
  
  
?>
