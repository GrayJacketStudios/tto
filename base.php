<?php
  $pagever = "1.0";
  $pagemod = "25/06/2010 10.17.07";
  require_once("form_cfg.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <link rel="stylesheet" type="text/css" href="<?=$PATH?>css/main.css" />
</head>
<body>
<?php
  include ("iniziopagina.php");

  //CARICAMENTO VALORI PER EDIT
  define("FILE_CORRENTE", "frm_registri.php");

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  
  if ($cmd['comando']==FASEMOD) {
      $titolofinestra = "Modificar Registro"; $classtitolo = "headermod";
      //echo "XXX";
  } else {
      $cmd['comando']=FASEINS;
      $titolofinestra = "Insertar nuevo registro"; $classtitolo = "headerins";
      //echo "<BR> xxcmd ".$cmd;
  }
  echo "<FORM ACTION='". FILE_DBQUERY ."' METHOD='POST' NAME='caricamento'><INPUT TYPE='hidden' NAME='cmd' VALUE='". $cmd['comando'] ."'><INPUT TYPE='hidden' NAME='selobj' VALUE='". $editval['id'] ."'><INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'>";
	print "<INPUT TYPE='hidden' NAME='defeventlist' VALUE='528,529,530,531,532,533,534,535,536,537,538,539,540,627,644'>";
	print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='80%'>";
  echo "<tr><td class='headermod' colspan='3'><div class='header-text'>$titolofinestra</div></td></tr>";
    echo "<TR>";

  echo "<TD><INPUT TYPE='hidden' size='50' NAME='id' VALUE='". $editval['id'] ."'></TD>";

  echo "</TR>";
  echo "<TR>";
  echo "<TD CLASS='form_lbl'>Nome Registro</TD>";
  echo "<TD><INPUT TYPE='text' size='50' NAME='descr' VALUE='". $editval['descr'] ."'></TD>";
  echo "<TD CLASS=form_help></TD>";
  echo "</TR>";
  echo "<TR>";
  echo "<TD CLASS='form_lbl'>Prefisso</TD>";
  echo "<TD><INPUT TYPE='text' size='50' NAME='prefisso' VALUE='". $editval['prefisso'] ."'></TD>";
  echo "<TD CLASS=form_help></TD>";
  echo "</TR>";
  echo "<TR>";

  echo "<TR><TD COLSPAN=3 align='center' height='70'><INPUT TYPE='submit' VALUE='Salva'>&nbsp;&nbsp;&nbsp;<INPUT TYPE='reset' VALUE='Ripristiva i valori precedenti'></TD></TR>";
  echo "</TABLE></FORM>";

  include ("finepagina.php");
  
  //<input type='button' name='aggiorna' onClick=aggparent();>
  
?>




<SCRIPT>
function aggparent() {
  opener.document.carica.idprof.value=1;
  opener.ricdati();
  self.close();
  return false;
}
</SCRIPT>
</body>
</html>
