<?php
  $pagever = "1.0";
  $pagemod = "25/06/2010 10.17.07";
  require_once("form_cfg.php");
  
  $cmd = $_REQUEST['cmd'];
  $selobj = $_REQUEST['selobj'];
  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <link rel="stylesheet" type="text/css" href="../css/main.css" />
</head>
<body>
<?php
  //update
  if ($cmd == 2) {
    $query = "REPLACE msgbox (id,msg,fdefault) VALUES (".$selobj.", '".conv_textarea($_REQUEST['msg'])."',1)";
    mysql_query($query);
    print "<CENTER>"._MSGMBOXUPDATE_."</CENTER><BR>";
  }
  
  $query = "SELECT * FROM msgbox WHERE id=".$selobj;
  $result = mysql_query($query) or die("Error1.1");
  $msgbox = mysql_fetch_assoc($result);
  print "<BR><H1>"._TITMBOXEDIT_."</H1>";
  print "<FORM NAME='caricamento' ACTION='msgbox.php' METHOD='POST' STYLE='margin:10px;'>";
  print "<CENTER><INPUT TYPE='hidden' NAME='selobj' VALUE='".$selobj."'><INPUT TYPE='hidden' NAME='cmd' VALUE='2'>";
  print "<TEXTAREA NAME='msg' COLS='60' ROWS='15'>".conv_x_ezpdf($msgbox['msg'])."</TEXTAREA><BR><BR>";
  print "<INPUT TYPE='submit' NAME='invia' VALUE='"._BTNSALVA_."'>&nbsp;&nbsp;-&nbsp;&nbsp;<INPUT TYPE='button' NAME='chiudi' VALUE='"._BTNMBOXCHIUDI_."' onClick=window.close();></CENTER>";
?>
</FORM>