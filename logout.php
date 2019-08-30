<?php
session_start();

// Unset all of the session variables.
$_SESSION = array();

if (isset($_COOKIE[session_name()])) {
  setcookie(session_name(), '', time()-42000, '/');
}
session_unset();
session_destroy();
?>
<html>
<head>
<title>TimeTableOnline</title>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
  <META HTTP-EQUIV=Refresh CONTENT="3; ./index.html">
</head>


<body style="font-family: Verdana, Tahoma, Arial, sans-serif; font-size: 12px;">
  logoff...
</body>
</html>