<?php
  print "NO TIME ZONE".mktime(0, 0, 0, 1, 31, 2011)."<BR>";
  print "GM:".gmmktime(0, 0, 0, 1, 31, 2011)."<BR>";
  print date_default_timezone_get()."<BR>";
  
  date_default_timezone_set('System/Localtime');
  print "System/Localtime".mktime(0, 0, 0, 1, 31, 2011)."<BR>";
  print "GM:".gmmktime(0, 0, 0, 1, 31, 2011)."<BR>";
  print date_default_timezone_get()."<BR>";
  
  
  date_default_timezone_set('Europe/Rome');
  print "ROMA".mktime(0, 0, 0, 1, 31, 2011)."<BR>";
  print "GM:".gmmktime(0, 0, 0, 1, 31, 2011)."<BR>";
  print date_default_timezone_get()."<BR>";
  
  date_default_timezone_set('America/Santiago');
  print "SANTIAGO".mktime(0, 0, 0, 1, 31, 2011)."<BR>";
  print "GM:".gmmktime(0, 0, 0, 1, 31, 2011)."<BR>";
  print date_default_timezone_get()."<BR>";
  
  print "<HR><HR>";
  
  
  date_default_timezone_set('Europe/Rome');
  print "DATA:".date("d/m/Y H:i:s",1296471600)."<BR>";
  
  print "PRIMA:".date("d/m/Y H:i:s",time())."<BR>";
  
  print "DOPO:".date("d/m/Y H:i:s",time())."<BR>";
  phpinfo();
?>