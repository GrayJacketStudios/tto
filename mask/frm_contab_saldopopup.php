<?php
  $pagever = "1.0";
  $pagemod = "31/01/2011 22.20.13";
  require_once("form_cfg.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <link rel="stylesheet" type="text/css" href="../css/main.css" />
  <STYLE>
   
  </STYLE>
</head>
<body>
<?php
  include ("iniziopagina.php");

  //CARICAMENTO VALORI PER EDIT
  define("FILE_CORRENTE", "frm_contab_saldopopup.php");

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  $selobj = $_REQUEST['selobj'];
  $tiposoggetto = $_REQUEST['ts'];
  $idsoggetto = $_REQUEST['ids'];
  
  
  if ($selobj!=0) {
    $query = "SELECT id, dt, importo, note FROM saldo WHERE id=".$selobj;
    $result = mysql_query($query) or die ("Error_1.1");
    $line = mysql_fetch_assoc($result);
    $editval['importo']=$line['importo'];
    $editval['dt']=$line['dt'];
    $editval['note']=$line['note'];
  } else {
    $editval['dt'] = date("Ymd");
  }
  
  print "<BR><H1>"._SALDOINIZIALE_."</H1>";
  
  
  print "<BR>";
  
  if ($cmd['comando']==FASEMOD) {
      $titolofinestra = _MODSALDOINIZIALE_; $classtitolo = "headermod";
      //echo "XXX";
  } else {
      $cmd['comando']=FASEINS;
      $titolofinestra = _INSSALDOINIZIALE_; $classtitolo = "headerins";
      //echo "<BR> xxcmd ".$cmd;
  }
  print "<FORM ACTION='". FILE_DBQUERY ."' METHOD='POST' NAME='caricamento' TARGET=inserimento><INPUT TYPE='hidden' NAME='cmd' VALUE='". $cmd['comando'] ."'><INPUT TYPE='hidden' NAME='selobj' VALUE='". $selobj ."'><INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'>";
	print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='80%'>";
  print "<tr><td class='headermod' colspan='3'><div class='header-text'>$titolofinestra</div></td></tr>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLDATA_."</TD>";
  print "<TD>";
  print "<INPUT TYPE='text' NAME='dt' VALUE='".eT_strdt2string($editval['dt'])."'>";
  print "</TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLMONTO_."</TD>";
  print "<TD>";
  print "<INPUT TYPE='text' NAME='importo' VALUE='".$editval['importo']."'>";
  print "</TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._NOTES_."</TD>";
  print "<TD>";
  print "<INPUT TYPE='text' NAME='note' VALUE='".$editval['note']."' SIZE=30>";
  print "</TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  
     
  print "<TR><TD COLSPAN=3 align='center' height='70'><INPUT TYPE='button' VALUE='"._BTNSALVA_."' onClick=btnsalva();>&nbsp;&nbsp;&nbsp;<INPUT TYPE='reset' VALUE='"._BTNRESET_."'></TD></TR>";
  print "</TABLE></FORM>";
  print "<BR><BR><DIV STYLE='margin-left:20px;'><A HREF='frm_contab_saldo.php'>"._MSGTORNAINDIETRO_."</A></DIV>";
  include ("finepagina.php");
?>
<SCRIPT>

function btnsalva() {
  document.caricamento.submit();
  self.close();
}

</SCRIPT>
<BR>
<BR>
</body>
</html>
