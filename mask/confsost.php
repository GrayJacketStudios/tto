<?php
  $pagever = "1.0";
  $pagemod = "25/06/2010 10.17.07";
  require_once("form_cfg.php");
  $selobj = $_REQUEST['selobj'];
  $ids = $_REQUEST['ids'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <link rel="stylesheet" type="text/css" href="../css/main.css" />
</head>
<body>
<?php
  print "<FORM ACTION='dbquery.php' NAME='caricamento' METHOD='POST'>
  <INPUT TYPE='hidden' NAME='cmd' VALUE='".FASECONFSUPPLENZA."'><INPUT TYPE='hidden' NAME='codicepagina' VALUE='TIMETAB'>
  <INPUT TYPE='hidden' NAME='noback' VALUE='1'>
  <INPUT TYPE='hidden' NAME='selobj' VALUE='$selobj'><INPUT TYPE='hidden' NAME='idsostituzione' VALUE='$ids'>
  </FORM>";
?>
<SCRIPT>
document.caricamento.submit();
</SCRIPT>