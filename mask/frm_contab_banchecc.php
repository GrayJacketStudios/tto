<?php
  $pagever = "0.5";
  $pagemod = "31/01/2011 21.54.33";
  require_once("form_cfg.php");
  require_once("func_cdcosto.php");
  include ("contab_menu.php"); 
  
  function labelsoggettounico($tiposoggetto, $vetsoggetto, $fimg=1,$fragsoc=1,$ffantasia=1) {
    
    //print $tiposoggetto;
    /*
    $loadcli[$tipocliente][$chiave]['nome'] = $line['nome'];
    $loadcli[$tipocliente][$chiave]['apellido'] = $line['apellido'];
    $loadcli[$tipocliente][$chiave]['ragsoc'] = $line['ragsoc'];
    $loadcli[$tipocliente][$chiave]['fantasia'] = $line['fantasia'];
    $loadcli[$tipocliente][$chiave]['impresa'] = $line['impresa'];
    */
    
    
    
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
              
              $buf .= $vetsoggetto['nome']." ".$vetsoggetto['apellido'];
              if ($vetsoggetto['ragsoc']!="" && $fragsoc==1) {
                $buf .=" - ".$vetsoggetto['ragsoc'];
              }
              if ($vetsoggetto['fantasia']!="" && $ffantasia==1) {
                $buf .= " (".$vetsoggetto['fantasia'].")";
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
  
  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <?echo $ribbon->style_link();?>
  <link rel="stylesheet" type="text/css" href="../css/main.css" />
  <link rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></link>
  <STYLE>
    .confine-sx {border-left:2px solid black !important;}
    .confinedx {border-right:2px solid black !important;} 
    
    .tr_dtoggi {background-color:#DBFFCF !important;}
    .tr_dtoggi .td_data {color:#1B00FF;font-weight:bolder;font-style:italic;}
    
    .griglia {border-collapse:collapse;}
    .griglia td {border:1px solid #9F9F9F;}
    .nogriglia td {border:0px none black;}
    
    .riga-arancio1{
      background-color: #FFB87F;font-weight: bolder;
    }
    
    .riga-arancio2{
      background-color: #FFE59F;font-weight: bolder;
    }
    
    .riga-giallo1{
      background-color: #FEFFBF;font-weight: bolder;
    }
    
    .riga-giallo1{
      background-color: #FEFF7F;font-weight: bolder;
    }
    
    .riga-header {
      font-size:16px;
      font-variant:small-caps;
      background-color: #B2FBB3;
      border-bottom: 2px solid black;
      height:40px;
    }
    
  </STYLE>
</head>
<body>
<script type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<?php
  $ribbon->build(false);
  include ("iniziopagina.php");
  
  $cifredecimali = 0;

  //CARICAMENTO VALORI PER EDIT
  define("FILE_CORRENTE", "frm_contab_banchecc.php");

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  if ($_REQUEST['selobj']!="") {$_SESSION["SELOBJ_".FILE_CORRENTE]=$_REQUEST['selobj'];}
  $selobj = $_SESSION["SELOBJ_".FILE_CORRENTE];
  $cmd['openblock']=$_REQUEST['op'];
  $cmd['ne_search']=1;
  $cmd['filtra_stato'] = $_REQUEST['filtra_stato'];
  if ($cmd['filtra_stato']=="") {$cmd['filtra_stato']=$_SESSION['_'.FILE_CORRENTE.'.filtra_stato'];}
  if ($cmd['filtra_stato']=="") {$cmd['filtra_stato'] = "1";}
  $_SESSION['_'.FILE_CORRENTE.'.filtra_stato']=$cmd['filtra_stato'];
  
  include ("form_filter.php");
  
  $cmd['form_rowcount'] = $setup['form_rowcount'];
  $cmd['form_rowcount'] = 1000;
  
  $cmd['filtro_dal'] = $_REQUEST['filtro_dal'];
  $cmd['filtro_al'] = $_REQUEST['filtro_al'];
  
  //sviluppo date
  /*
  se $_REQ =="" -> $_SES, se =="" -> oggi
  */

  $query = "SELECT ccbanca.id as ccbanca_id, idbanca, tiposoggetto, idsoggetto, ccbanca.codice as ccbanca_codice, 
            ccbanca.note as ccbanca_note, nomebanca, tipocc, stati.label as tipocc_descr
            FROM ccbanca INNER JOIN banca ON ccbanca.idbanca=banca.id
            INNER JOIN stati ON ccbanca.tipocc=stati.valore AND stati.idgruppo=4
            WHERE tiposoggetto=9999";
  $result = mysql_query($query) or die ("Error 5.1");
  while ($line = mysql_fetch_assoc($result)) {
    $chiave = $line['ccbanca_id'];
    
    $vetccbanche[$chiave]['idbanca']=$line['idbanca'];
    $vetccbanche[$chiave]['tiposoggetto']=$line['tiposoggetto'];
    $vetccbanche[$chiave]['idsoggetto']=$line['idsoggetto'];
    $vetccbanche[$chiave]['ccbanca_codice']=$line['ccbanca_codice'];
    $vetccbanche[$chiave]['ccbanca_note']=$line['ccbanca_note'];
    $vetccbanche[$chiave]['nomebanca']=$line['nomebanca'];
    $vetccbanche[$chiave]['tipocc']=$line['tipocc'];
    $vetccbanche[$chiave]['tipocc_descr']=$line['tipocc_descr'];
  }
  
  print "<BR><H1>"._ANAGRAFICABANCA_."</H1>";
  print "<TABLE CLASS=lista_table ALIGN=CENTER STYLE='width:70%;'>";
  print "<TR><TD CLASS=lista_tittab>&nbsp;</TD><TD CLASS=lista_tittab>"._LBLBANCA_."</TD><TD CLASS=lista_tittab>"._LBLTIPOCONTO_."</TD><TD CLASS=lista_tittab>"._LBLCCBANCA_."</TD><TD CLASS=lista_tittab>"._LBLNOTE_."</TD><TD CLASS=lista_tittab>&nbsp;</TD></TR>";
  foreach ($vetccbanche as $key=>$cur) {
    if ($cur_rowstyle=="form2_lista_riga_pari") {
      $cur_rowstyle="form2_lista_riga_dispari";
      $stile = "background-color:#EDEDED;";//EDEDED   //F0FFEF
    } else {
      $cur_rowstyle="form2_lista_riga_pari";
      $stile = "background-color:#F7F7F7;";//F7F7F7   //FEFFEF
    }
    if ($key==$selobj) {
      $cur_rowstyle = "form2_lista_riga_evidente_selid";//F7F7F7   //FEFFEF
    }
    
    print "<TR CLASS='$cur_rowstyle'>";
    print "<TD CLASS=lista_col_form2></TD>";
    print "<TD CLASS=lista_col_form2>".$cur['nomebanca']."</TD>";
    print "<TD CLASS=lista_col_form2>".$cur['tipocc_descr']."</TD>";
    print "<TD CLASS=lista_col_form2>".$cur['ccbanca_codice']."</TD>";
    print "<TD CLASS=lista_col_form2>".$cur['ccbanca_note']."</TD>";
    
    //COMANDI
    print "<TD CLASS=lista_col_form2><TABLE width=100%><TR>
    <TD ALIGN=CENTER><IMG CLASS='manina' SRC='../img/mod.png' border=0 ALT='"._LBLMATITA_."' TITLE='"._LBLMATITA_."' onClick=chiamapopup(". FASEMOD .",". $key .",".$cur['tiposoggetto'].",".$cur['idsoggetto'].")></A></TD>
    <TD ALIGN=CENTER><A HREF='#' onClick=urldelete(\"". FILE_DBQUERY ."?cmd=". FASEDEL ."&codicepagina=". codiceform(FILE_CORRENTE) ."&selobj=". $key ."\")><IMG SRC='../img/canc.png' border=0 ALT='"._LBLCANCELLA_."' TITLE='"._LBLCANCELLA_."'></A></TD>";
		print "</TR></TABLE>";
    print "</TD>";
    print "</TR>";
    
  }
  
  print "<BR>";
  

  print "<BR><BR>"; 

?>
<SCRIPT>
  function chiamapopup(cmd,selobj,tiposoggetto,idsoggetto) {
    var width  = 550;
    var height = 300;
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
  
    fltipo = "&selobj="+selobj+"&cmd="+cmd+"&ts="+tiposoggetto+"&ids="+idsoggetto;
    
    figlio6 = window.open("frm_banchecc_addpopup.php?p=0"+fltipo,"banchecc",params);
    figlio6.opener=self;
  }

  function dataodierna() {
    var datai, dataf;
    var mese;
    
    datai = new Date();
    
    //versione 1 gg
    mese = datai.getMonth();
    mese++;
    if (mese<10) {mesestr = "0";}
    mesestr = mesestr + mese;
    
    document.caricamento.filtro_dal.value = datai.getDate()+"/"+mesestr+"/"+datai.getFullYear();
    document.caricamento.filtro_al.value = document.caricamento.filtro_dal.value;
    aggiorna();
     
  }
  
  function aggiorna(){
    document.caricamento.submit();
  }
  
  function filtrodate() {
    var datai,dataf;
    var anno,mese;
    var app;
    
    
    anno = parseInt(document.caricamento.filtro_anno.value,10);
    mese = parseInt(document.caricamento.filtro_mese.value,10);
    
    mese--;
    
    datai = new Date(anno,mese,1,0,0,0);
    
    mese++;
    
    mesestr="";
    if (mese<10) {mesestr = "0";}
    
    mesestr = mesestr + mese;
    
    document.caricamento.filtro_dal.value = "01/"+mesestr+"/"+datai.getFullYear();
    
    if (mese>11) {anno++;mese=0;}
    
    dataf = new Date(anno,mese,1,0,0,0);
    
    app = dataf.getTime()
    
    app = app-86400000;
    
    dataf = new Date(app);
    
    
    
    mese = (dataf.getMonth() + 1);
    
    mesestr="";
    if (mese<10) {mesestr = "0";}
    
    mesestr = mesestr + mese;
      
    document.caricamento.filtro_al.value = dataf.getDate()+"/"+mesestr+"/"+dataf.getFullYear();
    aggiorna();
  }
  
  function cambiavalore(valore,campo) {
    var nuovo;
    
    nuovo = parseInt(campo.value,10);
    nuovo = nuovo + valore;
    campo.value=nuovo;
    
    filtrodate();
  }
  
  function opensoggetti() {
    var width  = 600;
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
    
    figlio3 = window.open("frm_contab_soggpopup.php?p=0"+fltipo,"soggetti",params);
    figlio3.opener=self;
  }
  
  function openaggiungi(selobj) {
  var width  = 700;
  var height = 550;
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
  
  var pselobj;
  pselobj="";

  if (selobj!=0) {
    pselobj = '&selobj='+selobj;
  }
  
  
  
  figlio5 = window.open("frm_contab_addpopup.php?p=0"+pselobj,"aggiungi",params);
  figlio5.opener=self;
}

function gommasoggetti(){
  document.caricamento.tipocliente.value="";
  document.caricamento.idcliente.value="";
  document.caricamento.cliente_labsogunico.value="";
  
  aggiorna();
}

function gommacdcosto(){
  document.caricamento.idcdcosto.value="";
  document.caricamento.cdcosto_cod.value="";
  document.caricamento.cdcosto_descr.value="";
  
  aggiorna();
}
  
function opencdcosto() {
  var width  = 400;
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
  
</SCRIPT>
<?php
  include ("finepagina.php");

?>
</body>
</html>
