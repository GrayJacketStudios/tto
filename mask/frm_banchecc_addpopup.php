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
  define("FILE_CORRENTE", "frm_banchecc_addpopup.php");

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  $selobj = $_REQUEST['selobj'];
  $tiposoggetto = $_REQUEST['ts'];
  $idsoggetto = $_REQUEST['ids'];
  
  
  if ($selobj!=0) {
    $query = "SELECT ccbanca.id as ccbanca_id, idbanca, tiposoggetto, idsoggetto, ccbanca.codice as ccbanca_codice, 
            ccbanca.note as ccbanca_note, nomebanca, tipocc
            FROM ccbanca INNER JOIN banca ON ccbanca.idbanca=banca.id
            WHERE tiposoggetto=".$tiposoggetto." AND ccbanca.id=".$selobj;
    $result = mysql_query($query) or die ("Error_1.1");
    $line = mysql_fetch_assoc($result);
    $editval['ccbanca_id']=$line['ccbanca_id'];
    $editval['idbanca']=$line['idbanca'];
    $editval['tiposoggetto']=$line['tiposoggetto'];
    $editval['idsoggetto']=$line['idsoggetto'];
    $editval['ccbanca_note']=$line['ccbanca_note'];
    $editval['nomebanca']=$line['nomebanca'];
    $editval['ccbanca_codice']=$line['ccbanca_codice'];
    $editval['tipocc']=$line['tipocc'];
    
  }
  
  print "<BR><H1>"._ANAGRAFICABANCA_."</H1>";
  
  
  print "<BR>";
  
  if ($cmd['comando']==FASEMOD) {
      $titolofinestra = _MODBANCACC_; $classtitolo = "headermod";
      //echo "XXX";
  } else {
      $cmd['comando']=FASEINS;
      $titolofinestra = _INSBANCACC_; $classtitolo = "headerins";
      //echo "<BR> xxcmd ".$cmd;
  }
  print "<FORM ACTION='". FILE_DBQUERY ."' METHOD='POST' NAME='caricamento' TARGET=inserimento><INPUT TYPE='hidden' NAME='cmd' VALUE='". $cmd['comando'] ."'><INPUT TYPE='hidden' NAME='selobj' VALUE='". $selobj ."'><INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'>";
  print "<INPUT TYPE='hidden' NAME='ts' VALUE='$tiposoggetto'><INPUT TYPE='hidden' NAME='ids' VALUE='$idsoggetto'>";
	print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='80%'>";
  print "<tr><td class='headermod' colspan='3'><div class='header-text'>$titolofinestra</div></td></tr>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLBANCA_."</TD>";
  print "<TD>";
  print "<INPUT TYPE='hidden' NAME='idbanca_old' VALUE='".$editval['idbanca']."'>";
  print "<SELECT SIZE=1 NAME='idbanca' id='idbanca'>";
  
  $query = "SELECT id, nomebanca FROM banca WHERE trashed<>1 ORDER BY nomebanca";
  $result = mysql_query ($query) or die ("Error_4.1");
  while ($line = mysql_fetch_assoc($result)) {
    print "<OPTION VALUE='".$line['id']."' ".($line['id']==$editval['idbanca'] ? " SELECTED ":"").">".$line['nomebanca']."</OPTION>";
  }
  print "</SELECT>";
  
  print "&nbsp;&nbsp;<IMG SRC='../img/mod.png' onClick=openbanche()>";
  
  print "</TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLTIPOCONTO_."</TD>";
  print "<TD>";
  print "<SELECT SIZE=1 NAME='tipoconto'>";
  
  $query = "SELECT id, label, valore FROM stati WHERE idgruppo=4 ORDER BY ordine";
  $result = mysql_query ($query) or die ("Error_1.3");
  while ($line = mysql_fetch_assoc($result)) {
    print "<OPTION VALUE='".$line['valore']."' ".($line['valore']==$editval['tipocc'] ? " SELECTED ":"").">".$line['label']."</OPTION>";
  }
  print "</SELECT>";
  print "</TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLCONTO_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='ccbanca_codice' VALUE='". $editval['ccbanca_codice'] ."'><INPUT TYPE='hidden' NAME='ccbanca_id' VALUE='".$editval['ccbanca_id']."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
     
  print "<TR><TD COLSPAN=3 align='center' height='70'><INPUT TYPE='button' VALUE='"._BTNSALVA_."' onClick=btnsalva();>&nbsp;&nbsp;&nbsp;<INPUT TYPE='reset' VALUE='"._BTNRESET_."'></TD></TR>";
  print "</TABLE></FORM>";

  include ("finepagina.php");
?>
<SCRIPT>
function openbanche() {
  var width  = 400;
  var height = 380;
  var left   = (screen.width  - width)/2;
  var top    = (screen.height - height)/2;
  var params = 'width='+width+', height='+height;
  params += ', top='+top+', left='+left;
  params += ', directories=no';
  params += ', location=no';
  params += ', menubar=no';
  params += ', resizable=no';
  params += ', scrollbars=yes';
  params += ', status=no';
  params += ', toolbar=no';
  
  
  
  
  figlio7 = window.open("frm_contab_anagbanchepopup.php?p=0","banche",params);
  figlio7.opener=self;
}
function btnsalva() {
  document.caricamento.submit();
  self.close();
}

</SCRIPT>
<BR>
<BR>
</body>
</html>
