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
  define("FILE_CORRENTE", "frm_docenti_addpopup.php");

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  $selobj = $_REQUEST['selobj'];
  
  
  if ($selobj!=0) {
    $query = "SELECT docente.id as docente_id, docente.iduser as docente_iduser, docente.nome as docente_nome,
            docente.nickname as docente_nickname, docente.tel as docente_tel, docente.mobile as docente_mobile,
            docente.email as docente_email, docente.attivo as docente_attivo, user.nome as user_nome, user.username as user_username,
            user.attivo as user_attivo, user.fboss as user_fboss, user.fviceboss as user_fviceboss,
            docente.banca as docente_banca, docente.conto as docente_conto, docente.rut as docente_rut, docente.tipoconto as docente_tipoconto,
            docente.contatto as docente_contatto, docente.note as docente_note, ccbanca.id as ccbanca_id, ccbanca.idbanca as idbanca, 
            ccbanca.codice as ccbanca_codice, ccbanca.tipocc as ccbanca_tipocc
            FROM docente LEFT JOIN user ON docente.iduser=user.id AND user.trashed<>1
            LEFT JOIN ccbanca ON docente.id = ccbanca.idsoggetto AND ccbanca.tiposoggetto=3
            LEFT JOIN banca ON ccbanca.idbanca = banca.id 
            WHERE docente.trashed<>1 AND docente.id=".$selobj;

    $result = mysql_query($query) or die ("Error_1.1");
    $line = mysql_fetch_assoc($result);
    $editval['docente_nome']=$line['docente_nome'];
    $editval['docente_nickname']=$line['docente_nickname'];
    $editval['docente_tel']=$line['docente_tel'];
    $editval['docente_mobile']=$line['docente_mobile'];
    $editval['docente_email']=$line['docente_email'];
    $editval['docente_attivo']=$line['docente_attivo'];
    $editval['docente_iduser']=$line['docente_iduser'];
    $editval['docente_banca']=$line['docente_banca'];
    $editval['docente_conto']=$line['docente_conto'];
    $editval['docente_rut']=$line['docente_rut'];
    $editval['docente_tipoconto']=$line['docente_tipoconto'];
    $editval['docente_contatto']=$line['docente_contatto'];
    $editval['docente_note']=$line['docente_note'];
    
    $editval['ccbanca_id']=$line['ccbanca_id'];
    $editval['idbanca']=$line['idbanca'];
    $editval['ccbanca_codice']=$line['ccbanca_codice'];
    $editval['ccbanca_tipocc']=$line['ccbanca_tipocc'];
  }
  

  print "<BR><H1>"._TITDOCENTI_."</H1>";
  
  
  print "<BR>";
  
  if ($cmd['comando']==FASEMOD) {
      $titolofinestra = _MODDOCENTE_; $classtitolo = "headermod";
      //echo "XXX";
  } else {
      $cmd['comando']=FASEINS;
      $titolofinestra = _INSDOCENTE_; $classtitolo = "headerins";
      //echo "<BR> xxcmd ".$cmd;
  }
  print "<FORM ACTION='". FILE_DBQUERY ."' METHOD='POST' NAME='caricamento' TARGET=inserimento><INPUT TYPE='hidden' NAME='cmd' VALUE='". $cmd['comando'] ."'><INPUT TYPE='hidden' NAME='selobj' VALUE='". $selobj ."'><INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'>";
	print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='50%'>";
  print "<tr><td class='headermod' colspan='3'><div class='header-text'>$titolofinestra</div></td></tr>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLNOME_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='nome' VALUE='". $editval['docente_nome'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLNICK_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='nickname' VALUE='". $editval['docente_nickname'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLTEL_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='tel' VALUE='". $editval['docente_tel'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLMOBILE_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='mobile' VALUE='". $editval['docente_mobile'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLEMAIL_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='email' VALUE='". $editval['docente_email'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  /*
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLBANCA_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='banca' VALUE='". $editval['docente_banca'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";

  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLCONTO_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='conto' VALUE='". $editval['docente_conto'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
   */
   
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLBANCA_."</TD>";
  print "<TD>";
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
  print "<SELECT SIZE=1 NAME='ccbanca_tipocc'>";
  
  $query = "SELECT id, label, valore FROM stati WHERE idgruppo=4 ORDER BY ordine";
  $result = mysql_query ($query) or die ("Error_1.3");
  while ($line = mysql_fetch_assoc($result)) {
    print "<OPTION VALUE='".$line['valore']."' ".($line['valore']==$editval['ccbanca_tipocc'] ? " SELECTED ":"").">".$line['label']."</OPTION>";
  }
  print "</SELECT>";
  print "</TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLCONTO_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='ccbanca_codice' VALUE='". $editval['ccbanca_codice'] ."'><INPUT TYPE='hidden' NAME='ccbanca_id' VALUE='".$editval['ccbanca_id']."'><INPUT TYPE='hidden' NAME='ccbanca_idold' VALUE='".$editval['ccbanca_id']."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLRUT_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='rut' VALUE='". $editval['docente_rut'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLCONTATTO_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='contatto' VALUE='". $editval['docente_contatto'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLNOTE_."</TD>";
  print "<TD><TEXTAREA NAME='note' ROWS=3 COLS=50>".conv_textarea($editval['docente_note'],1)."</TEXTAREA></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  //TOLTA GESTIONE DELL'USUARIO DEL DOCENTE. RIPRISTINARE SUCCESSIVAMENTE
  print "<TR><TD></TD><TD><INPUT TYPE='hidden' VALUE='NULL' NAME='iduser'></TD><TD></TD></TR>";
  /*
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLUSERNAME_."</TD>";
  print "<TD>";
  $query = "SELECT user.id as user_id, user.username as user_username, user.attivo as user_attivo, user.fboss as user_fboss,
            user.fviceboss as user_fviceboss, docente.iduser as docente_iduser, studente.iduser as studente_iduser
            FROM user LEFT JOIN docente ON user.id=docente.iduser AND docente.trashed <>1
            LEFT JOIN studente ON user.id=studente.iduser AND studente.trashed <>1
            WHERE user.trashed<>1 AND ((docente.iduser IS NULL AND studente.iduser IS NULL)";
  if ($cmd['comando']==FASEMOD && $editval['docente_iduser']!="") {            
    $query .= " OR user.id=".$editval['docente_iduser'].")";
  } else {
    $query .= ")";
  }
  $result = mysql_query ($query) or die ("Error_1.2");
  print "<SELECT SIZE=1 NAME='iduser'>";
  print "<OPTION VALUE='NULL'>"._MSGNOUSER_."</OPTION>";
  while ($line=mysql_fetch_assoc($result)) {
    print "<OPTION VALUE='".$line['user_id']."' ".($editval['docente_iduser']==$line['user_id']? " SELECTED ":"").">".$line['user_username']."</OPTION>";
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
