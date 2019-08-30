<?php
session_start();

if ($_SESSION['mgdebug']==1) {
  unset($_SESSION['mgdebug']);
  print "<H1>DEBUG DISATTIVATO</H1>";
} else {
  $_SESSION['mgdebug']=1;
  print "<H1>DEBUG ATTIVATO</H1>";
}
?>