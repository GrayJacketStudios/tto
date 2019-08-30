<?php
  $pagever = "1.0";
  $pagemod = "27/10/2010 0.33.36";
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
  define("FILE_CORRENTE", "frm_fornitori_popup.php");

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  $selobj = $_REQUEST['selobj'];
  
  
  if ($selobj!=0) {
    $query = "SELECT fornitore.id as id, nome, apellido, posizione, ragsoc, rut_ditta, rut_persona, giro, legale_nome, legale_rut,
            addr_pri, addr_work, tel_pri, tel_lavoro, tel_mobile, mail1, mail2, mail3, fatturaz, seg_nome, seg_foto, seg_mail,
            fantasia, web, skype, fb, codsence, fornitore.note as note, impresa, fatturaz_rut, fatturaz_giro, fatturaz_ragsoc, idcategoria, categ2, 
            ccbanca.id as ccbanca_id, ccbanca.idbanca as idbanca, 
            ccbanca.codice as ccbanca_codice, ccbanca.tipocc as ccbanca_tipocc
            FROM fornitore LEFT JOIN ccbanca ON fornitore.id = ccbanca.idsoggetto AND ccbanca.tiposoggetto=2
            LEFT JOIN banca ON ccbanca.idbanca = banca.id 
            WHERE fornitore.id=".$selobj;

    $result = mysql_query($query) or die ("Error_1.1$query");
    $line = mysql_fetch_assoc($result);
    $editval['id'] = $line['id'];
    $editval['idcategoria'] = $line['idcategoria'];
    $editval['categ2'] = $line['categ2'];
    $editval['nome'] = $line['nome'];
    $editval['apellido'] = $line['apellido'];
    $editval['posizione'] = $line['posizione'];
    $editval['ragsoc'] = $line['ragsoc'];
    $editval['rut_ditta'] = $line['rut_ditta'];
    $editval['rut_persona'] = $line['rut_persona'];
    $editval['giro'] = $line['giro'];
    $editval['legale_nome'] = $line['legale_nome'];
    $editval['legale_rut'] = $line['legale_rut'];
    $editval['addr_pri'] = $line['addr_pri'];
    $editval['addr_work'] = $line['addr_work'];
    $editval['tel_pri'] = $line['tel_pri'];
    $editval['tel_lavoro'] = $line['tel_lavoro'];
    $editval['tel_mobile'] = $line['tel_mobile'];
    $editval['mail1'] = $line['mail1'];
    $editval['mail2'] = $line['mail2'];
    $editval['mail3'] = $line['mail3'];
    $editval['fatturaz'] = $line['fatturaz'];
    $editval['seg_nome'] = $line['seg_nome'];
    $editval['seg_foto'] = $line['seg_foto'];
    $editval['seg_mail'] = $line['seg_mail'];
    $editval['fantasia'] = $line['fantasia'];
    $editval['web'] = $line['web'];
    $editval['skype'] = $line['skype'];
    $editval['codsence'] = $line['codsence'];
    $editval['note'] = $line['note'];
    $editval['impresa'] = $line['impresa'];
    $editval['fatturazrut'] = $line['fatturaz_rut'];
    $editval['fatturazgiro'] = $line['fatturaz_giro'];
    $editval['fatturazragsoc'] = $line['fatturaz_ragsoc'];
    
    $editval['ccbanca_id']=$line['ccbanca_id'];
    $editval['idbanca']=$line['idbanca'];
    $editval['ccbanca_codice']=$line['ccbanca_codice'];
    $editval['ccbanca_tipocc']=$line['ccbanca_tipocc'];
    
  }
  

  print "<BR><H1>"._TITFORNITORE_."</H1>";
  
  
  print "<BR>";
  
  if ($cmd['comando']==FASEMOD) {
      $titolofinestra = _MODFORNITORE_; $classtitolo = "headermod";
      //echo "XXX";
  } else {
      $cmd['comando']=FASEINS;
      $titolofinestra = _INSFORNITORE_; $classtitolo = "headerins";
      //echo "<BR> xxcmd ".$cmd;
  }
  print "<FORM ACTION='". FILE_DBQUERY ."' METHOD='POST' NAME='caricamento' TARGET=inserimento><INPUT TYPE='hidden' NAME='cmd' VALUE='". $cmd['comando'] ."'><INPUT TYPE='hidden' NAME='selobj' VALUE='". $selobj ."'><INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'>";
	print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='50%'>";
  print "<tr><td class='headermod' colspan='3'><div class='header-text'>$titolofinestra</div></td></tr>";
  
  print "<TR id='idcategoria'>";
  print "<TD CLASS='form_lbl'>"._LBLCATEGORIA_."</TD>";
  print "<TD>";
    $query = "SELECT valore, label FROM stati WHERE idgruppo=3 ORDER BY ordine";
    $result = mysql_query ($query) or die ("Error_1.3");
    print "<SELECT SIZE=1 NAME='idcategoria'>";
    while ($line = mysql_fetch_assoc($result)) {
      print "<OPTION VALUE='".$line['valore']."' ".($editval['idcategoria']==$line['valore'] ? " SELECTED CLASS='OPTION_SELEZIONATA'":"").">".convlang($line['label'])."</OPTION>";
    }
    print "</SELECT>";
  print "</TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  /*
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLTIPOCLI_."</TD>";
  print "<TD><SELECT SIZE=1 NAME='impresa' onChange=display_campi(this.value);><OPTION VALUE=0>Persona/Individuale</OPTION><OPTION VALUE=1 ".($editval['impresa']==1 ? " SELECTED ":"").">Impresa</OPTION></SELECT></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  */
  
  /*
  
   
  print "<TR id='apellido'>";
  print "<TD CLASS='form_lbl'>"._LBLCOGNOME_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='apellido' VALUE='". $editval['apellido'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  */
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLRAGSOC_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='ragsoc' VALUE='". $editval['ragsoc'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR id='nome'>";
  print "<TD CLASS='form_lbl'>"._LBLNOMECONTATTO_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='nome' VALUE='". $editval['nome'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR id='posizione'>";
  print "<TD CLASS='form_lbl'>"._LBLPOSIZIONE_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='posizione' VALUE='". $editval['posizione'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR id='posizione'>";
  print "<TD CLASS='form_lbl'>"._LBLPRODESERVIZI_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='categ2' VALUE='". $editval['categ2'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";

  /*
  print "<TR id='fantasia'>";
  print "<TD CLASS='form_lbl'>"._LBLFANTASIA_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='fantasia' VALUE='". $editval['fantasia'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  */
  
  /*
  print "<TR id='rut_ditta'>";
  print "<TD CLASS='form_lbl'>"._LBLRUTDITTA_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='rut_ditta' VALUE='". $editval['rut_ditta'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  */
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLEMAIL_." 1</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='mail1' VALUE='". $editval['mail1'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLEMAIL_." 2</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='mail2' VALUE='". $editval['mail2'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR id='tel_lavoro'>";
  print "<TD CLASS='form_lbl'>"._LBLTELWORK_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='tel_lavoro' VALUE='". $editval['tel_lavoro'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR id='tel_pri'>";
  print "<TD CLASS='form_lbl'>"._LBLTELPRI_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='tel_pri' VALUE='". $editval['tel_pri'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLTELMOBILE_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='tel_mobile' VALUE='". $editval['tel_mobile'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR id='addr_work'>";
  print "<TD CLASS='form_lbl'>"._LBLADDRESSWORK_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='addr_work' VALUE='". $editval['addr_work'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  //BANCA
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
  print "<TD CLASS='form_lbl'>"._LBLWEB_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='web' VALUE='". $editval['web'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  /*
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLSKYPE_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='skype' VALUE='". $editval['skype'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  */
    
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLNOTE_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='note' VALUE='". $editval['note'] ."'></TD>";
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

function display_campi(valore) {
  if (parseInt(valore,10)==1) {
    //impresa
    var elem = document.getElementById("nome");
    elem.style.display="none";

    var elem = document.getElementById("apellido");
    elem.style.display="none";

    var elem = document.getElementById("ragsoc");
    elem.style.display="none";
    
    var elem = document.getElementById("posizione");
    elem.style.display="none";

    var elem = document.getElementById("rut_persona");
    elem.style.display="none";

    var elem = document.getElementById("addr_pri");
    elem.style.display="none";
    
    var elem = document.getElementById("tel_pri");
    elem.style.display="none";

    var elem = document.getElementById("fatturaz");
    elem.style.display="none";
    
    var elem = document.getElementById("fatturazrut");
    elem.style.display="none";
    
    var elem = document.getElementById("fatturazgiro");
    elem.style.display="none";
    
    var elem = document.getElementById("fatturazragsoc");
    elem.style.display="none";
        
//--------------------------------
    var elem = document.getElementById("ragsoc_impresa");     
    elem.style.display="";

    var elem = document.getElementById("fantasia");
    elem.style.display="";

    var elem = document.getElementById("rut_ditta");
    elem.style.display="";

    var elem = document.getElementById("addr_work");
    elem.style.display="";

    var elem = document.getElementById("fatturaz_impresa");
    elem.style.display="";

  } else {
    //persona
    var elem = document.getElementById("ragsoc_impresa");
    elem.style.display="none";

    var elem = document.getElementById("fantasia");
    elem.style.display="none";

    var elem = document.getElementById("rut_ditta");
    elem.style.display="none";

    var elem = document.getElementById("addr_work");
    elem.style.display="none";

    var elem = document.getElementById("fatturaz_impresa");
    elem.style.display="none";
    
    var elem = document.getElementById("fatturazrut");
    elem.style.display="none";
    
    var elem = document.getElementById("fatturazgiro");
    elem.style.display="none";
    
    var elem = document.getElementById("fatturazragsoc");
    elem.style.display="none";

//-----------------------------------
    var elem = document.getElementById("nome");
    elem.style.display="";

    var elem = document.getElementById("apellido");
    elem.style.display="";

    var elem = document.getElementById("ragsoc");
    elem.style.display="";
    
    var elem = document.getElementById("posizione");
    elem.style.display="";

    var elem = document.getElementById("rut_persona");
    elem.style.display="";

    var elem = document.getElementById("addr_pri");
    elem.style.display="";
    
    var elem = document.getElementById("tel_pri");
    elem.style.display="";

    var elem = document.getElementById("fatturaz");
    elem.style.display="";
  }
}

function show_datifatturaz() {
  if (document.caricamento.fatturaz.value==1 && document.caricamento.impresa.value==0) {
    var elem = document.getElementById("fatturazrut");
    elem.style.display="";
    
    var elem = document.getElementById("fatturazgiro");
    elem.style.display="";
    
    var elem = document.getElementById("fatturazragsoc");
    elem.style.display="";
        
  } else {
    var elem = document.getElementById("fatturazrut");
    elem.style.display="none";
    
    var elem = document.getElementById("fatturazgiro");
    elem.style.display="none";
    
    var elem = document.getElementById("fatturazragsoc");
    elem.style.display="none";
  }
}
</SCRIPT>
<BR>
<BR>
</body>
</html>
