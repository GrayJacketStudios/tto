<?php
  $pagever = "1.0";
  $pagemod = "28/01/2011 22.04.34";
  require_once("form_cfg.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <link rel="stylesheet" type="text/css" href="../css/main.css" />
  <link rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></link>
  <STYLE>
   .opt_individuale {
      background-color:#5FE4FF;
      text-align:center;
   }
   .opt_impresa {
      background-color:#C1FF5F;
      text-align:center;
   }
   .tr_evid:hover {
    background-color:red;
   }
  </STYLE>
</head>
<body>
<script type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<?php
  include ("iniziopagina.php");

  //CARICAMENTO VALORI PER EDIT
  define("FILE_CORRENTE", "frm_contab_dtpagopopup.php");

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  
  $cmd['dtpagoreale'] = $_REQUEST['dt'];
  $cmd['selobj'] = $_REQUEST['selobj'];
  
  if ($cmd['dtpagoreale']==0 || $cmd['dtpagoreale']=="") {
    $cmd['dtpagoreale'] = time();
  } 
  
  
  print "<BR><H1>"._CONFERMAPAGO_."</H1>";
  print "<BR>";
  
  //form
    print "<FORM NAME='caricamento' METHOD=POST ACTION='".FILE_DBQUERY."' TARGET=inserimento>";
    print "<INPUT TYPE='hidden' NAME='cmd' VALUE='". FASEUPDPAGOREALE ."'><INPUT TYPE='hidden' NAME='selobj' VALUE='". $cmd['selobj'] ."'><INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'>";
    print "<TABLE STYLE='width:90%;'>";
    print "<TR><TD STYLE='text-align:center;vertical-align:top;'>";
      print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='80%'>";
            
      print "<TR>";
      print "<TD STYLE='width:36px;'><IMG SRC='../img/okverde32.png' CLASS='manina' onClick=btn_salva(1)></TD>";
      print "<TD STYLE='text-align:left;'><INPUT TYPE='text' SIZE=10 NAME='dtpagoreale' VALUE='".date("d/m/Y",$cmd['dtpagoreale'])."'>&nbsp;<IMG SRC='../img/calendario.png' ALIGN=MIDDLE BORDER=0 ALT='"._BRNCALENDARIO_."' TITLE='"._BRNCALENDARIO_."' onclick=\"displayCalendar(document.caricamento.dtpagoreale,'dd/mm/yyyy',this)\"></TD>";
      print "<TR>";
      print "</TABLE>";
    print "</TD><TD STYLE='width:50%; text-align:center;vertical-align: top;'>";
      
      print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='80%'>";
      print "<TR>";
      print "<TD STYLE='width:36px;'><IMG SRC='../img/xrossa32.png' CLASS='manina' onClick=btn_salva(-1)></TD>";
      print "<TD>&nbsp;"._MSGANNULLACONFERMAPAGO_."</TD>";
      print "<TR>";
    print "</TD></TR>";
    print "</TABLE>";
    print "</FORM>";
    

  include ("finepagina.php");
?>
<SCRIPT>
function toggle_modifica() {
  //var elem = document.getElementById("trmodificato");  
  //elem.style.display = "block";
  document.caricamento.modificato.value="1";  
  document.caricamento.invia.value="<?=_LBLRICALCOLA_ ?>";
}

function btn_salva(cmd) {
  if (cmd=="1") {
    document.caricamento.cmd.value="<?= FASESETUPDPAGOREALE ?>";
  } else {
    document.caricamento.cmd.value="<?= FASERESETUPDPAGOREALE ?>";
  }
  document.caricamento.submit();
  self.close();
}

</SCRIPT>
<BR>
<BR>
</body>
</html>
