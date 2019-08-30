<?php
  $pagever = "1.0";
  $pagemod = "27/10/2010 0.54.13";
  require_once("form_cfg.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <link rel="stylesheet" type="text/css" href="../css/main.css" />
  <STYLE>
   .option_selected {
     background-color:#FFAA00;
     font-style:italic;
     font-weight:bolder;
   }
  </STYLE>
</head>
<body>
<?php
  include ("iniziopagina.php");

  //CARICAMENTO VALORI PER EDIT
  define("FILE_CORRENTE", "frm_contattiforn_popup.php");

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  $selobj = $_REQUEST['selobj'];
  
  if ($cmd['comando']==FASEINS) {
    //idc
    $editval['idfornitore']=$_REQUEST['idc'];
  }
  
  if ($selobj!=0) {
    $query = "SELECT id, idfornitore, nome, posizione, tel_lavoro, tel_privato, fax, mobile, skype, mail1, note
            FROM fornitore_contatto
            WHERE id=".$selobj;

    $result = mysql_query($query) or die ("Error_1.1");
    $line = mysql_fetch_assoc($result);
    $editval['id'] = $line['id'];
    $editval['idfornitore'] = $line['idfornitore'];
    $editval['nome'] = $line['nome'];
    $editval['posizione'] = $line['posizione'];
    $editval['tel_lavoro'] = $line['tel_lavoro'];
    $editval['tel_privato'] = $line['tel_privato'];
    $editval['fax'] = $line['fax'];
    $editval['mobile'] = $line['mobile'];
    $editval['skype'] = $line['skype'];
    $editval['mail1'] = $line['mail1'];
    $editval['note'] = $line['note'];
  }
  

  print "<BR><H1>"._FORNITORECONTATTI_."</H1>";
  
  
  print "<BR>";
  
  if ($cmd['comando']==FASEMOD) {
      $titolofinestra = _MODFORNITORECONTATTI_; $classtitolo = "headermod";
      //echo "XXX";
  } else {
      $cmd['comando']=FASEINS;
      $titolofinestra = _INSFORNITORECONTATTI_; $classtitolo = "headerins";
      //echo "<BR> xxcmd ".$cmd;
  }
  print "<FORM ACTION='". FILE_DBQUERY ."' METHOD='POST' NAME='caricamento' TARGET=inserimento><INPUT TYPE='hidden' NAME='cmd' VALUE='". $cmd['comando'] ."'><INPUT TYPE='hidden' NAME='selobj' VALUE='". $selobj ."'><INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'>";
	print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='50%'>";
  print "<tr><td class='headermod' colspan='3'><div class='header-text'>$titolofinestra</div></td></tr>";
  
  /*print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLCUSTOMER_."</TD>";
  print "<TD>";
  $query = "SELECT id, nome, apellido, ragsoc, fantasia
            FROM customer
            WHERE trashed<>1";
  
  $result = mysql_query ($query) or die ("Error_1.2");
  print "<SELECT SIZE=1 NAME='idcustomer'>";
  while ($line=mysql_fetch_assoc($result)) {
    print "<OPTION VALUE='".$line['id']."' ".($editval['idcustomer']==$line['id']? " SELECTED STYLE='background-color:#FFAA00;font-style:italic;font-weight:bolder;'":"").">".$line['apelido']." ".$line['nome'].($line['ragsoc']!="" ? $line['ragsoc']:"")."</OPTION>";
  }
  print "</SELECT></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";*/
  
  $query = "SELECT id, nome, apellido, ragsoc, fantasia
            FROM fornitore
            WHERE id=".$editval['idfornitore'];
  $result = mysql_query ($query) or die ("Error_1.2");
  $line = mysql_fetch_assoc($result);
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLFORNITORE_."</TD>";
  print "<TD><INPUT TYPE='hidden' size='50' NAME='idfornitore' VALUE='". $editval['idfornitore'] ."'><INPUT TYPE='text' size='50' NAME='lblidfornitore' VALUE='". $line ['apellido']." ".$line['nome']." - ".$line['ragsoc']."' READONLY></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
    
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLNOME_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='nome' VALUE='". $editval['nome'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLPOSIZIONE_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='posizione' VALUE='". $editval['posizione'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLTELWORK_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='tel_lavoro' VALUE='". $editval['tel_lavoro'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLTELPRI_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='tel_privato' VALUE='". $editval['tel_privato'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLMOBILE_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='mobile' VALUE='". $editval['mobile'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLFAX_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='fax' VALUE='". $editval['fax'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLSKYPE_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='skype' VALUE='". $editval['skype'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLEMAIL_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='mail1' VALUE='". $editval['mail1'] ."'></TD>";
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
</body>
</html>
