<?php
  $pagever = "1.0";
  $pagemod = "18/11/2010 9.31.18";
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
  define("FILE_CORRENTE", "frm_classi_addpopup.php");

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  $selobj = $_REQUEST['selobj'];
  
  
  if ($selobj!=0) {
    $query = "SELECT classi.id as classi_id, classi.descr as classi_descr, classi.idcustomer as classi_idcustomer
            FROM classi
            WHERE classi.id=".$selobj;

    $result = mysql_query($query) or die ("Error_1.1");
    $line = mysql_fetch_assoc($result);
    $editval['classi_id'] = $line['classi_id'];
    $editval['classi_descr'] = $line['classi_descr'];
    $editval['classi_idcustomer'] = $line['classi_idcustomer'];
  }
  

  print "<BR><H1>"._CLASSISTUDENTI_."</H1>";
  
  
  print "<BR>";
  
  if ($cmd['comando']==FASEMOD) {
      $titolofinestra = _MODCLASSISTUDENTI_; $classtitolo = "headermod";
      //echo "XXX";
  } else {
      $cmd['comando']=FASEINS;
      $titolofinestra = _INSCLASSISTUDENTI_; $classtitolo = "headerins";
      //echo "<BR> xxcmd ".$cmd;
  }
  print "<FORM ACTION='". FILE_DBQUERY ."' METHOD='POST' NAME='caricamento' TARGET=inserimento><INPUT TYPE='hidden' NAME='cmd' VALUE='". $cmd['comando'] ."'><INPUT TYPE='hidden' NAME='selobj' VALUE='". $selobj ."'><INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'>";
	print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='50%'>";
  print "<tr><td class='headermod' colspan='3'><div class='header-text'>$titolofinestra</div></td></tr>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._DESCRCLASSISTUDENTI_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='classi_descr' VALUE='". $editval['classi_descr'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLAZIENDA_."</TD>";
  print "<TD>";
  $query = "SELECT id, nome, apellido, ragsoc, fantasia, impresa FROM customer WHERE trashed<>1 AND stato=1 ORDER BY ragsoc, impresa DESC, nome, apellido";
  $result = mysql_query ($query) or die ("Error_1.2");
  print "<SELECT SIZE=1 NAME='classi_idcustomer'>";
  print "<OPTION VALUE='NULL'>"._MSGNOCUSTOMER_."</OPTION>";
  while ($line=mysql_fetch_assoc($result)) {
    print "<OPTION VALUE='".$line['id']."' ".($editval['classi_idcustomer']==$line['id']? " SELECTED CLASS='option_selected' ":"").">";
      if ($line['impresa']==0) {
        //individuale
        print $line['nome']." ".$line['apellido'];
        if ($line['ragsoc']) {
          print " (".$line['ragsoc'].")";
        }
      } else {
        print $line['ragsoc']." (".$line['fantasia'].")";
      }
    print "</OPTION>";
  }
  print "</SELECT></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
       
  print "<TR><TD COLSPAN=3 align='center' height='70'><INPUT TYPE='button' VALUE='"._BTNSALVA_."' onClick=btnsalva();>&nbsp;&nbsp;&nbsp;<INPUT TYPE='reset' VALUE='"._BTNRESET_."'></TD></TR>";
  print "</TABLE></FORM>";

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
