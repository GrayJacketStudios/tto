<?php
  $pagever = "1.0";
  $pagemod = "18/11/2010 9.31.18";
  require_once("form_cfg.php");
  require_once("func_cdcosto.php");
  
  /*  function labelsoggettounico($tiposoggetto, $vetsoggetto, $fimg=1,$fragsoc=1,$ffantasia=1) {
    
    
    
    switch ($tiposoggetto) {
      case 1://CLIENTI
      case 2://FORNITORI
              if ($vetsoggetto['impresa']==1) {
                //impresa
                $img = "industria20.png";
              } else {
                //persona
                $img = "persone20.png";
              }
              
              if ($vetsoggetto['nome']!="" || $vetsoggetto['apellido']!="") {
                $buf .= $vetsoggetto['nome']." ".$vetsoggetto['apellido']." -";
              }
              
              if ($vetsoggetto['ragsoc']!="" && $fragsoc==1) {
                $buf .=" ".$vetsoggetto['ragsoc'];
              } else {
                $ffantasia=1;
              }
              if ($vetsoggetto['fantasia']!="" && $ffantasia==1) {
                $buf .= " (".$vetsoggetto['fantasia'].")";
              } else {
                $buf .= " ".$vetsoggetto['ragsoc'];
              }
              //print "<HR>".$buf."<HR>";
              break; 
      case 3://PROFESSORI
              $img = "professori_20.png";
              $buf .= $vetsoggetto['nome'];
              if ($vetsoggetto['nickname']!="") {
                $buf .= " (".$vetsoggetto['nickname'].")";
              }
              break;
    }
    
    if ($fimg==1) {
      $buf = "<IMG SRC='../img/$img' BORDER='0'>&nbsp;".$buf;
    }
    
    return $buf;
  }
  */
  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <link rel="stylesheet" type="text/css" href="../css/main.css" />
  <link rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></link>
  <STYLE>
   
  </STYLE>
</head>
<body>
<script type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<?php
  include ("iniziopagina.php");

  //CARICAMENTO VALORI PER EDIT
  define("FILE_CORRENTE", "frm_contab_addpopup.php");

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  $selobj = $_REQUEST['selobj'];
  
  //reset date, se select saranno settate successivamente
  $editval['dtpagoprev']="";
  
  //popola clienti/beneficiari
  $tipocliente = 1;
  //$query = "SELECT id, nome, apellido, ragsoc, fantasia, impresa FROM customer WHERE id IN (".substr($ids[$tipocliente],0,-2).")";
  $query = "SELECT id, nome, apellido, ragsoc, fantasia, impresa FROM customer WHERE trashed<>1 AND stato=1";
 
  $result = mysql_query($query) or die("Post1.1$query");
  while ($line = mysql_fetch_assoc($result)) {
    $chiave = $line['id'];
    $loadcli[$tipocliente][$chiave]['nome'] = $line['nome'];
    $loadcli[$tipocliente][$chiave]['apellido'] = $line['apellido'];
    $loadcli[$tipocliente][$chiave]['ragsoc'] = $line['ragsoc'];
    $loadcli[$tipocliente][$chiave]['fantasia'] = $line['fantasia'];
    $loadcli[$tipocliente][$chiave]['impresa'] = $line['impresa'];
    
    $appvet = $loadcli[$tipocliente][$chiave];
    
    $autovettore_soggetti .= "autovettore_soggetti[\"".$tipocliente."-".$chiave."\"] = \"".labelsoggettounico($tipocliente, $appvet, 0,1,1)."\";";
    
  }
  $tipocliente = 2;
  //$query = "SELECT id, nome, apellido, ragsoc, fantasia FROM fornitore WHERE id IN (".substr($ids[$tipocliente],0,-2).")";
  $query = "SELECT id, nome, apellido, ragsoc, fantasia FROM fornitore WHERE trashed<>1 AND stato=1";
  $result = mysql_query($query) or die("Post1.2");
  while ($line = mysql_fetch_assoc($result)) {
    $chiave = $line['id'];
    $loadcli[$tipocliente][$chiave]['nome'] = $line['nome'];
    $loadcli[$tipocliente][$chiave]['apellido'] = $line['apellido'];
    $loadcli[$tipocliente][$chiave]['ragsoc'] = $line['ragsoc'];
    $loadcli[$tipocliente][$chiave]['fantasia'] = $line['fantasia'];
    $loadcli[$tipocliente][$chiave]['impresa'] = $line['impresa'];
    
    $appvet = $loadcli[$tipocliente][$chiave];
    $autovettore_soggetti .= "autovettore_soggetti[\"".$tipocliente."-".$chiave."\"] = \"".labelsoggettounico($tipocliente, $appvet, 0,1,1)."\";";
  }
  $tipocliente = 3;
  //$query = "SELECT id, nome, nickname FROM docente WHERE id IN (".substr($ids[$tipocliente],0,-2).")";
  $query = "SELECT id, nome, nickname FROM docente WHERE trashed<>1 AND attivo=1";
  $result = mysql_query($query) or die("Post1.3");
  while ($line = mysql_fetch_assoc($result)) {
    $chiave = $line['id'];
    $loadcli[$tipocliente][$chiave]['nome'] = $line['nome'];
    $loadcli[$tipocliente][$chiave]['nickname'] = $line['nickname'];
    
    $appvet = $loadcli[$tipocliente][$chiave];
    $autovettore_soggetti .= "autovettore_soggetti[\"".$tipocliente."-".$chiave."\"] = \"".labelsoggettounico($tipocliente, $appvet, 0,1,1)."\";";
  }
  
  
  if ($selobj!=0) {
    $cmd['comando'] = FASEMOD;
  
    $query = "SELECT id, descr, importo, idtipo, idext, tipocliente, idcliente, dtcreazione, dtpagoprev, dtpagoreale, doc, 
              tipopago, refpago, idcc, idcdcosto, fncredito, ncidannullo, note, fpromemoria, dtpromemoria, nquota, thisquota, 
              quotaroot, cast(importosinquota as decimal) as importosinquota_dec
              FROM entrate 
              WHERE id=$selobj AND trashed<>1";

    $result = mysql_query($query) or die ("Error_1.1");
    $line = mysql_fetch_assoc($result);
    
    //myprint_r($line);
    
    $editval['id']=$line['id'];
    $editval['descr']=$line['descr'];
    $editval['importo']=$line['importo'];
    $editval['idtipo']=$line['idtipo'];
    $editval['idext']=$line['idext'];
    $editval['tipocliente']=$line['tipocliente'];
    $editval['idcliente']=$line['idcliente'];
    $editval['dtcreazione']=$line['dtcreazione'];
    $editval['dtpagoprev']=$line['dtpagoprev'];
    $editval['dtpagoreale']=$line['dtpagoreale'];
    $editval['docente_tipoconto']=$line['docente_tipoconto'];
    $editval['doc']=$line['doc'];
    $editval['tipopago']=$line['tipopago'];
    $editval['refpago']=$line['refpago'];
    $editval['idcc']=$line['idcc'];
    $editval['idcdcosto']=$line['idcdcosto'];
    $editval['fncredito']=$line['fncredito'];
    $editval['ncidannullo']=$line['ncidannullo'];
    $editval['note']=$line['note'];
    $editval['fpromemoria']=$line['fpromemoria'];
    $editval['dtpromemoria']=$line['dtpromemoria'];
    $editval['nquota']=$line['nquota'];
    $editval['thisquota']=$line['thisquota'];
    $editval['quotaroot']=$line['quotaroot'];
    $editval['importosinquota']=$line['importosinquota_dec'];
  
  
  //Cdcosto
  $query = "SELECT id, codice, descr, fext 
            FROM cdicosto
            WHERE id=".$editval['idcdcosto'];
  $result = mysql_query($query) or die ("Error1.2");
  $line = mysql_fetch_assoc($result);
  $editval['cdcosto_cod']=$line['codice'];
  $editval['cdcosto_descr']=$line['descr'];
  $editval['cdcosto_fext']=$line['fext'];
  
  
  
  
  //sogunico
  //myprint_r($editval);
  switch($editval['tipocliente']) {
    case 1:
            $query = "SELECT impresa, nome, apellido, ragsoc, fantasia
            FROM customer ";
            break;
    case 2:
            $query = "SELECT impresa, nome, apellido, ragsoc, fantasia
            FROM fornitore ";
            break;
    case 3:
            $query = "SELECT nome FROM docente ";
            break;
  }
  $query .= "WHERE id=".$editval['idcliente'];
  $result = mysql_query($query) or die ("Error1.3$query");
  $line = mysql_fetch_assoc($result);
  $editval['cliente_labsogunico']=$line['nome']." ".$line['apellido']." ".$line['ragsoc']." ".$line['fantasia'];
  
  //label del corso
  if ($editval['idext']!="") {
    $query = "SELECT pstudi.id as pstudi_id
              FROM pstudi
              WHERE pstudi.id=".$editval['idext'];
    $result = mysql_query($query) or die ("Error1.4$query");
    $line = mysql_fetch_assoc($result);
    $editval['prodotto_label']=$line['pstudi_id'];
  }
  
  //myprint_r($editval);
  
  //altrequote
  if ($editval['nquota']>1) {
    $query = "SELECT id, importo FROM entrate WHERE quotaroot=".$editval['quotaroot']." AND id<>".$selobj;
    $result = mysql_query($query) or die ("Error1.5$query");
    while ($line = mysql_fetch_assoc($result)) {
      $editval['altrequote']['ids'].= $line['id'].", ";
      $editval['altrequote']['totimporto']+= $line['importo'];
    }
  }
  
  }
  
  
  //carica tutto cdcosto
  
  carica_dati();
    
  ins_struttura(0, $vetcdcosto,0, "");
  
  vettore_struttura(0, $vetcdcosto, 0, $vetcdcostolineare);  

  print "<BR><H1>"._ENTRATASPESA_."</H1>";
  
  
  print "<BR>";
  
  if ($cmd['comando']==FASEMOD) {
      $titolofinestra = _MODITEMBILANCIO_; $classtitolo = "headermod";
      //echo "XXX";
  } else {
      $cmd['comando']=FASEINS;
      $titolofinestra = _INSITEMBILANCIO_; $classtitolo = "headerins";
      //echo "<BR> xxcmd ".$cmd;
  }
  
  //
  
  print "<FORM ACTION='". FILE_DBQUERY ."' METHOD='POST' NAME='caricamento' TARGET=inserimento><INPUT TYPE='hidden' NAME='cmd' VALUE='". $cmd['comando'] ."'><INPUT TYPE='hidden' NAME='selobj' VALUE='". $selobj ."'><INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'>";
	print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='80%'>";
  print "<tr><td class='headermod' colspan='3'><div class='header-text'>$titolofinestra</div></td></tr>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLTIPOVOCE_."</TD>";
  print "<TD>";
  print "<SELECT SIZE=1 NAME='idtipo'>";
  
  $query = "SELECT id, label, valore FROM stati WHERE idgruppo=10 ORDER BY ordine";
  $result = mysql_query ($query) or die ("Error_2.1");
  while ($line = mysql_fetch_assoc($result)) {
    print "<OPTION VALUE='".$line['valore']."' ".($line['valore']==$editval['idtipo'] ? " SELECTED ":"").">".convlang($line['label'])."</OPTION>";
  }
  print "</SELECT>";
  print "</TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLCDICOSTO_."</TD>";
  print "<TD>
    <INPUT TYPE='hidden' NAME='idcdcosto' VALUE='".$editval['idcdcosto']."'>
    <INPUT TYPE='text' SIZE=8 NAME='cdcosto_cod' VALUE='".$editval['cdcosto_cod']."' onBlur=associa_cdcosto(document.caricamento.cdcosto_cod.value)>&nbsp;
    <INPUT TYPE='text' size='55' NAME='cdcosto_descr' VALUE='". $editval['cdcosto_descr'] ."' READONLY>&nbsp;&nbsp;
    <IMG SRC='../img/binocolo2.png' onClick=opencdcosto()>
    </TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLBENEFICIARIO_."</TD>";
  print "<TD>
    <INPUT TYPE='hidden' NAME='tipocliente' VALUE='".$editval['tipocliente']."'>
    <INPUT TYPE='text' SIZE=8 NAME='idcliente' VALUE='".$editval['idcliente']."' onChange=caricasoggetto(this.value)>&nbsp;
    <INPUT TYPE='text' SIZE=55 NAME='cliente_labsogunico' VALUE='".$editval['cliente_labsogunico']."' READONLY>&nbsp;&nbsp;
    <IMG SRC='../img/binocolo2.png' onClick=opensoggetti()>
    </TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLCORSO_."</TD>";
  print "<TD>
    <INPUT TYPE='hidden' NAME='idext' VALUE='".$editval['idext']."'>
    <INPUT TYPE='text' SIZE=70 NAME='prodotto_label' VALUE='".$editval['prodotto_label']."' READONLY>&nbsp;&nbsp;
    <IMG SRC='../img/binocolo2.png' onClick=opencorsi()>
    </TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLMONTO_."</TD>";
$balance_totale1 = floatval($editval['importo']);
  print "<TD><INPUT TYPE='text' ID='suma' size='30' NAME='importo' VALUE='".$balance_totale1."'>"; 
?>
<script  type="text/javascript">
function valor()
{
	var s = document.getElementById('suma').value;
	var res = s.split("+");
	var i;
	var resultado = 0;
	for (i = 0; i < res.length; i++){
		resultado = (parseInt(res[i])+resultado);
	}
	return resultado;

}
</script>
<button type="button"
onclick="document.getElementById('suma').value = valor()">
Sumar</button>

</TD>
<?php
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLDATAPREV_."</TD>";
  print "<TD><IMG SRC='../img/calendario.png' ALIGN=MIDDLE BORDER=0 ALT='"._BRNCALENDARIO_."' TITLE='"._BRNCALENDARIO_."' onclick=\"displayCalendar(document.caricamento.dtpagoprev,'dd/mm/yyyy',this)\">&nbsp;<INPUT TYPE='text' size='10' NAME='dtpagoprev' VALUE='". ($editval['dtpagoprev']=="" ? "": date("d/m/Y",$editval['dtpagoprev'])) ."'>&nbsp;<IMG SRC='../img/promemoria1.png' BORDER=0 onClick=toggle_promemoria();></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  $style_promemoria = "display:none;";
  if ($editval['dtpromemoria']>0) {
    $style_promemoria = "";
  }
  
  print "<TR id='blocco_promemoria' STYLE='".$style_promemoria."'>";
  print "<TD CLASS='form_lbl'>"._LBLDTPROMEMORIA_."</TD>";
  print "<TD><IMG SRC='../img/calendario.png' ALIGN=MIDDLE BORDER=0 ALT='"._BRNCALENDARIO_."' TITLE='"._BRNCALENDARIO_."' onclick=\"displayCalendar(document.caricamento.dtpromemoria,'dd/mm/yyyy',this)\">&nbsp;<INPUT TYPE='hidden' NAME='fpromemoria' VALUE='".($editval['dtpromemoria']>0 ? $editval['dtpromemoria']:"0")."'><INPUT TYPE='text' size='10' NAME='dtpromemoria' VALUE='". ($editval['dtpromemoria']==0 ? "":date("d/m/Y",$editval['dtpromemoria'])) ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLQUOTA_."</TD>";
  print "<TD><INPUT TYPE='hidden' NAME='quotaroot' VALUE='".$editval['quotaroot']."'><INPUT TYPE='hidden' NAME='quotaids' VALUE='".$editval['altrequote']['ids']."'><INPUT TYPE='hidden' NAME='quotatotimporto' VALUE='".$editval['altrequote']['totimporto']."'>";
  print "<INPUT TYPE='hidden' NAME='modquote' VALUE='0'><INPUT TYPE='hidden' NAME='importosinquota' VALUE='".$editval['importosinquota']."'><IMG SRC='../img/quota.png' BORDER=0 onClick=openquote();><BR><DIV id='blocco_quota' STYLE='display:block;'>";
    
    //quota
    //print "<INPUT TYPE='text' NAME='numeroquote' VALUE='1'><IMG SRC='../img/update16.png' BORDER='0' onClick=aggiorna_quota()><BR>";
    //print "<TABLE id='tabella_quota'><TR><TD>data1</TD><TD>monto1</TD></TR><TR><TD>data2</TD><TD>monto2</TD></TR><TR><TD>data3</TD><TD>monto3</TD></TR></TABLE>";
    
  print "</DIV></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLUPDQUOTADESCR_."</TD>";
  print "<TD><SELECT SIZE=1 NAME='updquotadescr'><OPTION VALUE='0'>"._OPZNOUPDATEDESCRQUOTE_."</OPTION><OPTION VALUE='1'>"._OPZSIUPDATEDESCRQUOTE_."</OPTION></SELECT></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLUPDQUOTATOTALE_."</TD>";
  print "<TD><SELECT SIZE=1 NAME='updquotatotale'><OPTION VALUE='0'>"._OPZNOUPDATETOTALEQUOTE_."</OPTION><OPTION VALUE='1' SELECTED>"._OPZSIUPDATEQUOTEQUOTE_."</OPTION></SELECT></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLDESCR_."</TD>";
  print "<TD><INPUT TYPE='text' size='70' NAME='descr' VALUE='". $editval['descr'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLDOC_."</TD>";
  print "<TD><INPUT TYPE='text' size='70' NAME='doc' VALUE='". $editval['doc'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLTIPOPAGO_."</TD>";
  print "<TD>";
  print "<SELECT SIZE=1 NAME='tipopago'>";
  
  $query = "SELECT id, label,valore FROM stati WHERE idgruppo=11 ORDER BY ordine";
  $result = mysql_query ($query) or die ("Error_2.2");
  while ($line = mysql_fetch_assoc($result)) {
    print "<OPTION VALUE='".$line['valore']."' ".($line['valore']==$editval['tipopago'] ? " SELECTED ":"").">".convlang($line['label'])."</OPTION>";
  }
  print "</SELECT>";
  
  print "&nbsp;&nbsp;<INPUT TYPE='text' size='40' NAME='refpago' VALUE='". $editval['refpago'] ."'>";
  
  print "</TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLCCBANCA_."</TD>";
  print "<TD>";
  print "<SELECT SIZE=1 NAME='idcc' id='idcc'>";
  print "<OPTION VALUE='0'>"._OPZNESSUNCONTOBANCA_."</OPTION>";
  
  $query = "SELECT banca.nomebanca as nomebanca, ccbanca.id as ccbanca_id, tiposoggetto, idsoggetto, ccbanca.codice as ccbanca_codice
            FROM ccbanca INNER JOIN banca ON ccbanca.idbanca=banca.id";
  if ($editval['idcc']>0) {
    $query .= " WHERE ccbanca.id=".$editval['idcc'];
  } else {
    $query .= " WHERE ccbanca.tiposoggetto=9999";
  }
            
  $result = mysql_query ($query) or die ("Error_3.1");
  while ($line = mysql_fetch_assoc($result)) {
    print "<OPTION VALUE='".$line['ccbanca_id']."' ".($line['ccbanca_id']==$editval['idcc'] ? " SELECTED ":"").">".$line['nomebanca']."-".$line['ccbanca_codice']."</OPTION>";
  }
  print "</SELECT>";
  print "</TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLNOTE_."</TD>";
  print "<TD><TEXTAREA NAME='note' ROWS=3 COLS=50>".conv_textarea($editval['note'],1)."</TEXTAREA></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
       
  print "<TR><TD COLSPAN=3 align='center' height='70'><INPUT TYPE='button' VALUE='"._BTNSALVA_."' onClick=btnsalva();>&nbsp;&nbsp;&nbsp;<INPUT TYPE='reset' VALUE='"._BTNRESET_."'></TD></TR>";
  print "</TABLE></FORM>";
  include ("finepagina.php");
?>
<SCRIPT>
function caricasoggetto(codice) {
    var autovettore_soggetti = new Array();
    
    if (codice!="") {
      
      <?=$autovettore_soggetti?>
      
      vettore = codice.split(".");
      idsoggetto = vettore[0];
      tiposoggetto = vettore[1];
      
      //descr = autovettore_soggetti[tiposoggetto+"-"+idsoggetto].toString();
      
      descr = autovettore_soggetti[tiposoggetto+"-"+idsoggetto];
      document.caricamento.cliente_labsogunico.value=descr;
      
      if (document.caricamento.cliente_labsogunico.value != "undefined") {        
        document.caricamento.tipocliente.value = tiposoggetto;
        document.caricamento.idcliente.value=idsoggetto;
      } else {
        document.caricamento.tipocliente.value = "";
        document.caricamento.idcliente.value="";
        document.caricamento.cliente_labsogunico.value="";  
      }
    }
  }


function updconti() {
  var elem = document.getElementById("idcc"); 
  alert (elem.innerHTML);
}
function btnsalva() {
  var msg;

  msg = check_valori(); 
  if (msg=="") {
    document.caricamento.submit();
    self.close();
  } else {
    alert(msg);
  }
}

function check_valori() {
  var messaggio;
  messaggio="";
  
  //cdicosto corretto
  if (document.caricamento.idcdcosto.value=="") {
    messaggio = messaggio + "\n"+"<?=_MSGCDCOSTONOCORRETTO_?>";
  }
  
  return messaggio;
}



function opencdcosto() {
  var width  = 500;
  var height = 500;
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
  
  
  
  figlio2 = window.open("frm_contab_cdcostopopup.php","cdcosto",params);
  figlio2.opener=self;
}

function opensoggetti() {
  var width  = 700;
  var height = 400;
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
  
  var fltipo = '';

  if (document.caricamento.tipocliente.value!="") {
    fltipo = '&idtipo='+document.caricamento.tipocliente.value;
  }
  
  figlio3 = window.open("frm_contab_soggpopup.php?jslb=1&p=0"+fltipo,"soggetti",params);
  figlio3.opener=self;
}

function opencorsi() {
  var width  = 700;
  var height = 400;
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
  
  var flc = '';

  if (document.caricamento.idcliente.value!="" && document.caricamento.tipocliente.value=="1") {
    flc = '&flc='+document.caricamento.idcliente.value;
  }
  
  
  
  figlio4 = window.open("frm_contab_corsipopup.php?p=0"+flc,"soggetti",params);
  figlio4.opener=self;
}

function openquote() {
  var width  = 700;
  var height = 400;
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
  
  var risp;
  risp=true;
  if (document.caricamento.cmd.value=="<?=FASEMOD?>") {
    risp = confirm ("<?=_MSGCONFERMAMODIFICAQUOTE_?>");
    if (risp) {
      document.caricamento.modquote.value="1";
      document.caricamento.importo.value=document.caricamento.importosinquota.value;
    }
  }
  
  var flc = '';

  //if (document.caricamento.idcliente.value!="" && document.caricamento.tipocliente.value=="1") {
  //  flc = '&flc='+document.caricamento.idcliente.value;
  //}
  
  var monto,dtinizio;
  monto = document.caricamento.importo.value;
  dtinizio = document.caricamento.dtpagoprev.value;
  
  
  
  
  if (risp) {
    figlio5 = window.open("frm_contab_quotapopup.php?p=0&mt="+monto+"&dt="+dtinizio,"quote",params);
    figlio5.opener=self;
    
    var elem = document.getElementById("blocco_quota");
    elem.style.display = "block";
  }  
}

//ASSOCIA VETCDCOSTO
vettore = new Array();

  <?
    foreach($vetcdcostolineare as $key => $cur) {
      print "vettore[\"".$key."\"]= new Array();vettore[\"".$key."\"][0]=\"".$cur['descr']."\";vettore[\"".$key."\"][1]=\"".$cur['id']."\";\n";
    }
  ?>

function associa_cdcosto(codice) {
  document.caricamento.idcdcosto.value="";
  document.caricamento.cdcosto_descr.value="";
  
  document.caricamento.idcdcosto.value=vettore[codice][1];
  document.caricamento.cdcosto_descr.value=vettore[codice][0];
}

function toggle_promemoria() {
  var elem = document.getElementById("blocco_promemoria");  
    if (elem.style.display == "none") {
      elem.style.display = "";
      document.caricamento.fpromemoria.value="1";
    } else {
      elem.style.display = "none";
      document.caricamento.fpromemoria.value="0";
    }
}

function toggle_quota() {
  var elem = document.getElementById("blocco_quota");  
    if (elem.style.display == "block") {
      elem.style.display = "none";
    } else {
      elem.style.display = "block";
    }
}

function aggiorna_quota() {
  var elem = document.getElementById("tabella_quote");
}

</SCRIPT>
<BR>
<BR>
</body>
</html>
