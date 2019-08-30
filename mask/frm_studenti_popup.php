<?php
  $pagever = "1.0";
  $pagemod = "24/10/2010 18.30.22";
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
  define("FILE_CORRENTE", "frm_studenti_popup.php");

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  $selobj = $_REQUEST['selobj'];
  
  
  if ($selobj!=0) {
    $query = "SELECT studente.id as id, idcustomer, studente.iduser as iduser, studente.nome as nome, 
            studente.nome2 as nome2, studente.cognome as cognome, studente.cognome2 as cognome2,
            studente.foto as foto, studente.rut as rut, studente.giro as giro, studente.indirizzo as indirizzo,
            studente.num as num, studente.numappartamento as numappartamento, studente.comune as comune,
            studente.tel as tel, studente.mobile as mobile, studente.fax as fax, studente.email as email, studente.email2 as email2, 
            studente.attivo as attivo, customer.nome as customer_nome, user.username as user_username, user.attivo as user_attivo
            FROM studente LEFT JOIN customer ON studente.idcustomer=customer.id AND customer.trashed<>1
            LEFT JOIN user ON studente.iduser=user.id
            WHERE studente.id=".$selobj;

    $result = mysql_query($query) or die ("Error_1.1");
    $line = mysql_fetch_assoc($result);
    
    $editval['id'] = $line['id'];
    $editval['idcustomer'] = $line['idcustomer'];
    $editval['nome'] = $line['nome'];
    $editval['rut'] = $line['rut'];
    $editval['indirizzo'] = $line['indirizzo'];
    $editval['num'] = $line['num'];
    $editval['comune'] = $line['comune'];
    $editval['tel'] = $line['tel'];
    $editval['mobile'] = $line['mobile'];
    $editval['fax'] = $line['fax'];
    $editval['email'] = $line['email'];
    $editval['email2'] = $line['email2'];
  }
  

  print "<BR><H1>"._TITSTUDENTE_."</H1>";
  
  
  print "<BR>";
  
  if ($cmd['comando']==FASEMOD) {
      $titolofinestra = _MODSTUDENTE_; $classtitolo = "headermod";
      //echo "XXX";
  } else {
      $cmd['comando']=FASEINS;
      $titolofinestra = _INSSTUDENTE_; $classtitolo = "headerins";
      //echo "<BR> xxcmd ".$cmd;
  }
  print "<FORM ACTION='". FILE_DBQUERY ."' METHOD='POST' NAME='caricamento' TARGET=inserimento><INPUT TYPE='hidden' NAME='cmd' VALUE='". $cmd['comando'] ."'><INPUT TYPE='hidden' NAME='selobj' VALUE='". $selobj ."'><INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'>";
	print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='50%'>";
  print "<tr><td class='headermod' colspan='3'><div class='header-text'>$titolofinestra</div></td></tr>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLNOME_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='nome' VALUE='". $editval['nome'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLRUT_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='rut' VALUE='". $editval['rut'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLAZIENDA_."</TD>";
  print "<TD>";
  $query = "SELECT id, nome, apellido, ragsoc, addr_work, impresa FROM customer WHERE trashed<>1 AND stato=1 ORDER BY customer.ragsoc, customer.impresa DESC, customer.nome, customer.apellido ";
  $result = mysql_query ($query) or die ("Error_1.2");
  print "<SELECT SIZE=1 NAME='idcustomer' onChange=popola_dacustomer(this.value);>";
  while ($line=mysql_fetch_assoc($result)) {
    print "<OPTION VALUE='".$line['id']."' ".($editval['idcustomer']==$line['id']? " SELECTED ":"").">".$line['nome']." ".$line['apellido'].($line['impresa']==0 ? " [".$line['ragsoc']."]" : "")."</OPTION>";
    $vetjs  .= "vet_direccion[".$line['id']."]=\"".addslashes($line['addr_work'])."\";\nvet_comuna[".$line['id']."]="."\"\"".";\n\n"; 
  }
  print "</SELECT></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  /*print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLGIRO_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='giro' VALUE='". $editval['giro'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";*/
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._INDIRIZZO_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='indirizzo' VALUE='". $editval['indirizzo'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLCOMUNE_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='comune' VALUE='". $editval['comune'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLNUM_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='num' VALUE='". $editval['num'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  /*print "<TR>";
  print "<TD CLASS='form_lbl'>"._APPARTAMENTO_."</TD>";
  print "<TD><TEXTAREA NAME='numappartamento' COLS='50' ROWS='3'>". $editval['numappartamento'] ."</TEXTAREA></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";*/
  
  
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLTEL_."</TD>";
  print "<TD><TEXTAREA NAME='tel' COLS='50' ROWS='3'>". $editval['tel'] ."</TEXTAREA></TD>";
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
  print "<TD CLASS='form_lbl'>"._LBLEMAIL1_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='email' VALUE='". $editval['email'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLEMAIL2_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='email2' VALUE='". $editval['email2'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  
  
  /*
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLUSERNAME_."</TD>";
  print "<TD>";
  $query = "SELECT user.id as user_id, user.username as user_username, user.attivo as user_attivo, user.fboss as user_fboss,
            user.fviceboss as user_fviceboss, docente.iduser as docente_iduser, studente.iduser as studente_iduser
            FROM user LEFT JOIN docente ON user.id=docente.iduser AND docente.trashed <>1
            LEFT JOIN studente ON user.id=studente.iduser AND studente.trashed <>1
            WHERE user.trashed<>1 AND ((docente.iduser IS NULL AND studente.iduser IS NULL)";
  if ($cmd['comando']==FASEMOD && $editval['iduser']!="") {            
    $query .= " OR user.id=".$editval['iduser'].")";
  } else {
    $query .= ")";
  }
  $result = mysql_query ($query) or die ("Error_1.3");
  print "<SELECT SIZE=1 NAME='iduser'>";
  print "<OPTION VALUE='NULL'>"._MSGNOUSER_."</OPTION>";
  while ($line=mysql_fetch_assoc($result)) {
    print "<OPTION VALUE='".$line['user_id']."' ".($editval['iduser']==$line['user_id']? " SELECTED ":"").">".$line['user_username']."</OPTION>";
  }
  print "</SELECT></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  */
  
  
  print "<TR><TD COLSPAN=3 align='center' height='70'><INPUT TYPE='button' VALUE='"._BTNSALVA_."' onClick=btnsalva();>&nbsp;&nbsp;&nbsp;<INPUT TYPE='reset' VALUE='"._BTNRESET_."'></TD></TR>";
  print "</TABLE></FORM>";

  include ("finepagina.php");
?>
<SCRIPT>
function btnsalva() {
  document.caricamento.submit();
  self.close();
}
function popola_dacustomer(valore) {
  vet_direccion = new Array();
  vet_comuna = new Array();
  <?=$vetjs?>
  document.caricamento.indirizzo.value=vet_direccion[valore];
  document.caricamento.comune.value=vet_comuna[valore];
}
//set iniziale
popola_dacustomer(document.caricamento.idcustomer.value);
</SCRIPT>
<BR>
<BR>
</body>
</html>
