<?php
  $pagever = "0.5";
  $pagemod = "17/01/2011 23.07.35";
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
      background-color: #EFEFEF;  
      border-bottom: 2px solid black;
      height:40px;
    }
    
    .uscite {
      background-color:#DF4545 !important;
      color:#FFFFFF !important;
      letter-spacing: 4px;
      font-size:18px;
      font-weight:bolder;
    }
    
    .entrate {
      background-color:#58AF57 !important;
      color:#FFFFFF !important;
      letter-spacing: 4px;
      font-size:18px;
      font-weight:bolder;
    }
    
    .filtri {
      background-color:#EFEFEF;
      font-size:13px;
      font-weight:bolder;
      font-variant:small-caps;
    }
    
    #dhtmltooltip{
      position: absolute;
      width: 350px;
      border: 2px solid black;
      padding: 2px;
      background-color: #7CBAFF;
      visibility: hidden;
      font-family:Verdana, Arial;
      font-size:10px;
      z-index: 100;
      /*Remove below line to remove shadow. Below line should always appear last within this CSS*/
      filter: progid:DXImageTransform.Microsoft.Shadow(color=gray,direction=135);
      -webkit-border-radius: 12px;
      -moz-border-radius: 12px;
      -webkit-box-shadow: 2px 2px 6px rgba(0,0,0,0.6);
      }
      
    .blink_1 {
      background-color:red !important;
    }
    
    
  </STYLE>
</head>
<body>
<div id="dhtmltooltip"></div>
<script type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<?php
  $ribbon->build(false);
  include ("iniziopagina.php");
  
  $cifredecimali = 0;

  //CARICAMENTO VALORI PER EDIT
  define("FILE_CORRENTE", "frm_contabshow.php");

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
  //retrive da session
  if ($_REQUEST['filtro_dal']=="") {
    //noset
    if (isset($_SESSION['contab_main_dtdal'])) {
      $cmd['filtro_dal']= $_SESSION['contab_main_dtdal'];
      $cmd['filtro_al']= $_SESSION['contab_main_dtal'];
    } else {
      $cmd['filtro_dal'] = date("01/m/Y",time());
      $app = strtotime("+1 month",eT_dt2times($cmd['filtro_dal']));
      $app = strtotime("-1 day", $app);
      $cmd['filtro_al'] = date("d/m/Y",$app);
    }
  }
  
  //set session
  $_SESSION['contab_main_dtdal'] = $cmd['filtro_dal'];
  $_SESSION['contab_main_dtal'] = $cmd['filtro_al'];

  
  
  
  $cmd['filtro_dalmese'] = date("m",eT_dt2times($cmd['filtro_dal']));
  $cmd['filtro_dalanno'] = date("Y",eT_dt2times($cmd['filtro_dal']));
  
  
  
  $cmd['filtro_idcdcosto'] = $_REQUEST['idcdcosto'];
  $cmd['filtro_cdcosto_cod']=$_REQUEST['cdcosto_cod'];
  
  if ($_REQUEST['cdcosto_cod']=="") {
    $cmd['filtro_idcdcosto'] = $_SESSION['contab_main_filtro_idcdcosto'];
    $cmd['filtro_cdcosto_cod'] = $_SESSION['contab_main_filtro_cdcosto_cod'];
  }
  
  if ($_REQUEST['cdcosto_cod']=="-1") {
    $cmd['filtro_idcdcosto'] = "";
    $cmd['filtro_cdcosto_cod'] = "";
  }
  $_SESSION['contab_main_filtro_idcdcosto'] = $cmd['filtro_idcdcosto'];
  $_SESSION['contab_main_filtro_cdcosto_cod'] = $cmd['filtro_cdcosto_cod'];
  
  
  //---------------------------------
  
  $cmd['filtroragsoc'] = $_REQUEST['filtroragsoc'];
  if ($_REQUEST['filtroragsoc']=="") {
    $cmd['filtroragsoc'] = $_SESSION['filtroragsoc'];
  }
  $_SESSION['filtroragsoc'] = $cmd['filtroragsoc'];
  
  //-----------------------------
  
  $cmd['filtronoconf'] = $_REQUEST['filtronoconf'];
  
  if ($cmd['filtronoconf']=="") {
    $cmd['filtronoconf'] = $_SESSION['contab_main_filtronoconf'];
  }
  
  if ($cmd['filtronoconf']=="") {
    $cmd['filtronoconf'] = 1;
  }
  $_SESSION['contab_main_filtronoconf']= $cmd['filtronoconf'];

  //-----------------------------
  
  $cmd['filtro_tipocliente'] = $_REQUEST['tipocliente'];
  $cmd['filtro_idcliente'] = $_REQUEST['idcliente'];
  
  if ($_REQUEST['idcliente']=="") {
    $cmd['filtro_tipocliente'] = $_SESSION['contab_main_filtro_tipocliente'];
    $cmd['filtro_idcliente'] = $_SESSION['contab_main_filtro_idcliente'];
  }
  
  if ($_REQUEST['idcliente']=="-1") {
    $cmd['filtro_tipocliente']="";
    $cmd['filtro_idcliente']="";
  }
  
  $_SESSION['contab_main_filtro_tipocliente'] = $cmd['filtro_tipocliente'];
  $_SESSION['contab_main_filtro_idcliente'] = $cmd['filtro_idcliente'];
  
  //-----------------------------     
  
  switch ($cmd['filtroragsoc']) {
    case 1://tutto
          $fragsoc=1;
          $ffantasia=1;
          break;
    case 2:
          $fragsoc=1;
          $ffantasia=0;
          break;
    case 3:
          $fragsoc=0;
          $ffantasia=1;
          break;
  }                           
  
  if($_REQUEST['filtrofattura']=="") {
    $cmd['filtrofattura']=$_SESSION['filtrofattura'];
  } else {
    $cmd['filtrofattura']=$_REQUEST['filtrofattura'];
  }
  
  $_SESSION['filtrofattura']=$cmd['filtrofattura'];
  
  switch ($cmd['filtrofattura']) {
    case 1://normale
          $ffattura=0;
          $fpago=0;
          break;
    case 2://fattura
          $ffattura=1;
          $fpago=0;
          break;
    case 3://pago
          $ffattura=0;
          $fpago=1;
          break;
    case 4://tutto
          $ffattura=1;
          $fpago=1;
          break;                    



  }
  
  

  $query = "SELECT entrate.id as entrate_id, entrate.descr as entrate_descr, importo, entrate.idtipo as entrate_idtipo, idext, 
            tipocliente, entrate.idcliente as entrate_idcliente, dtcreazione, dtpagoprev, dtpagoreale, entrate.doc as entrate_doc, 
            tipopago, refpago, entrate.idcc as entrate_idcc, entrate.idcdcosto as entrate_idcdcosto, fncredito, ncidannullo, 
            entrate.note as entrate_note, fpromemoria, dtpromemoria, nquota, thisquota, quotaroot, importosinquota, entrate.trashed as trashed,
            stati_pago.label as pago_label, ccbanca.codice as ccbanca_codice, banca.nomebanca as nomebanca
            FROM entrate INNER JOIN stati as stati_pago ON entrate.tipopago=stati_pago.valore AND stati_pago.idgruppo=11
            LEFT JOIN ccbanca ON entrate.idcc=ccbanca.id 
            LEFT JOIN banca ON ccbanca.idbanca = banca.id 
            WHERE entrate.trashed<>1 AND ((dtpagoprev>=".eT_dt2times($cmd['filtro_dal'])." AND dtpagoprev<=".eT_dt2times($cmd['filtro_al']).") OR
                  (dtpagoreale>=".eT_dt2times($cmd['filtro_dal'])." AND dtpagoreale<=".eT_dt2times($cmd['filtro_al']).") ";
  if ($cmd['filtronoconf']==1) {
    $query .= " OR (dtpagoreale<=0 AND dtpagoprev<=".eT_dt2times($cmd['filtro_dal'])." AND dtpagoprev<=".day_mezzanotte(time()).")";
  }
  
  $query .= " ) ";
            
  //check soggetto
  if ($cmd['filtro_idcliente']!="") {
    $query .= " AND entrate.tipocliente = ".$cmd['filtro_tipocliente']." AND entrate.idcliente=".$cmd['filtro_idcliente'];
  }
  
  //check cdcosto
  if ($cmd['filtro_idcdcosto']!="") {
    $query .= " AND entrate.idcdcosto = ".$cmd['filtro_idcdcosto'];
  }
  
  print "<HR>$query<HR>";
            
  $result = mysql_query ($query) or die("Error 1.1$query");
  
  $appstr="-1, ";
  if ($cmd['filtro_idcliente']!="") {
    $appstr .= $cmd['filtro_idcliente'].", "; 
  }
  $ids[1]= $appstr;
  $ids[2]= $appstr;
  $ids[3]= $appstr;
   
  while ($line = mysql_fetch_assoc($result)) {
    $tipo = $line['entrate_idtipo'];
    
    $fetchashow = $line['dtpagoprev'];
    
    if ($line['dtpagoreale']>0) {
      $fetchashow = $line['dtpagoreale'];
    }
    
    $vetdati[$fetchashow]['ne'][$tipo]++;
    
    $chiave = $vetdati[$fetchashow]['ne'][$tipo];
    
    $vetdati[$fetchashow][$tipo]['dati'][$chiave]['entrate_id'] = $line['entrate_id'];
    $vetdati[$fetchashow][$tipo]['dati'][$chiave]['entrate_descr'] = $line['entrate_descr'];
    $vetdati[$fetchashow][$tipo]['dati'][$chiave]['importo'] = $line['importo'];
    $vetdati[$fetchashow][$tipo]['dati'][$chiave]['idext'] = $line['idext'];
    $vetdati[$fetchashow][$tipo]['dati'][$chiave]['tipocliente'] = $line['tipocliente'];
    $vetdati[$fetchashow][$tipo]['dati'][$chiave]['entrate_idcliente'] = $line['entrate_idcliente'];
    $vetdati[$fetchashow][$tipo]['dati'][$chiave]['dtcreazione'] = $line['dtcreazione'];
    $vetdati[$fetchashow][$tipo]['dati'][$chiave]['dtpagoprev'] = $line['dtpagoprev'];
    $vetdati[$fetchashow][$tipo]['dati'][$chiave]['dtpagoreale'] = $line['dtpagoreale'];
    $vetdati[$fetchashow][$tipo]['dati'][$chiave]['entrate_doc'] = $line['entrate_doc'];
    $vetdati[$fetchashow][$tipo]['dati'][$chiave]['tipopago'] = $line['tipopago'];
    $vetdati[$fetchashow][$tipo]['dati'][$chiave]['refpago'] = $line['refpago'];
    $vetdati[$fetchashow][$tipo]['dati'][$chiave]['entrate_idcc'] = $line['entrate_idcc'];
    $vetdati[$fetchashow][$tipo]['dati'][$chiave]['entrate_idcdcosto'] = $line['entrate_idcdcosto'];
    $vetdati[$fetchashow][$tipo]['dati'][$chiave]['fncredito'] = $line['fncredito'];
    $vetdati[$fetchashow][$tipo]['dati'][$chiave]['ncidannullo'] = $line['ncidannullo'];
    $vetdati[$fetchashow][$tipo]['dati'][$chiave]['entrate_note'] = $line['entrate_note'];
    $vetdati[$fetchashow][$tipo]['dati'][$chiave]['fpromemoria'] = $line['fpromemoria'];
    $vetdati[$fetchashow][$tipo]['dati'][$chiave]['dtpromemoria'] = $line['dtpromemoria'];
    $vetdati[$fetchashow][$tipo]['dati'][$chiave]['nquota'] = $line['nquota'];
    $vetdati[$fetchashow][$tipo]['dati'][$chiave]['thisquota'] = $line['thisquota'];
    $vetdati[$fetchashow][$tipo]['dati'][$chiave]['quotaroot'] = $line['quotaroot'];
    $vetdati[$fetchashow][$tipo]['dati'][$chiave]['importosinquota'] = $line['importosinquota'];
    $vetdati[$fetchashow][$tipo]['dati'][$chiave]['trashed'] = $line['trashed'];
    $vetdati[$fetchashow][$tipo]['dati'][$chiave]['pago_label'] = convlang($line['pago_label']);
    $vetdati[$fetchashow][$tipo]['dati'][$chiave]['ccbanca_codice'] = $line['ccbanca_codice'];
    $vetdati[$fetchashow][$tipo]['dati'][$chiave]['nomebanca'] = $line['nomebanca'];
    
    if ($vetdati[$fetchashow][$tipo]['dati'][$chiave]['dtpagoreale']<>0) {
      $vetdati[$fetchashow]['tot'][$tipo] += $vetdati[$fetchashow][$tipo]['dati'][$chiave]['importo'];
    }
    
    $ids[$vetdati[$fetchashow][$tipo]['dati'][$chiave]['tipocliente']] .= $vetdati[$fetchashow][$tipo]['dati'][$chiave]['entrate_idcliente'].", ";
  }

  //popola clienti/beneficiari
  $tipocliente = 1;
  $query = "SELECT id, nome, apellido, ragsoc, fantasia, impresa FROM customer WHERE id IN (".substr($ids[$tipocliente],0,-2).")";
  $result = mysql_query($query) or die("Post1.1$query");
  while ($line = mysql_fetch_assoc($result)) {
    $chiave = $line['id'];
    $loadcli[$tipocliente][$chiave]['nome'] = $line['nome'];
    $loadcli[$tipocliente][$chiave]['apellido'] = $line['apellido'];
    $loadcli[$tipocliente][$chiave]['ragsoc'] = $line['ragsoc'];
    $loadcli[$tipocliente][$chiave]['fantasia'] = $line['fantasia'];
    $loadcli[$tipocliente][$chiave]['impresa'] = $line['impresa'];
  }

  $tipocliente = 2;
  $query = "SELECT id, nome, apellido, ragsoc, fantasia FROM fornitore WHERE id IN (".substr($ids[$tipocliente],0,-2).")";
  $result = mysql_query($query) or die("Post1.2");
  while ($line = mysql_fetch_assoc($result)) {
    $chiave = $line['id'];
    $loadcli[$tipocliente][$chiave]['nome'] = $line['nome'];
    $loadcli[$tipocliente][$chiave]['apellido'] = $line['apellido'];
    $loadcli[$tipocliente][$chiave]['ragsoc'] = $line['ragsoc'];
    $loadcli[$tipocliente][$chiave]['fantasia'] = $line['fantasia'];
    $loadcli[$tipocliente][$chiave]['impresa'] = $line['impresa'];
  }
  
  $tipocliente = 3;
  $query = "SELECT id, nome, nickname FROM docente WHERE id IN (".substr($ids[$tipocliente],0,-2).")";
  $result = mysql_query($query) or die("Post1.3");
  while ($line = mysql_fetch_assoc($result)) {
    $chiave = $line['id'];
    $loadcli[$tipocliente][$chiave]['nome'] = $line['nome'];
    $loadcli[$tipocliente][$chiave]['nickname'] = $line['nickname'];
  }

  ksort($vetdati);
  
  //carica tutto cdcosto
  
  carica_dati();
    
  ins_struttura(0, $vetcdcosto,0, "");
  
  vettore_struttura(0, $vetcdcosto, 0, $vetcdcostolineare);
  
  //RESET LABEL
  define("_ANNO_","");
  define("_MESE_","");
  
  
  print "<BR>";
  
  //opzioni di ricerca
  print "<FORM NAME='caricamento' METHOD='POST' ACTION='".FILE_CORRENTE."'>";
  print "<TABLE CLASS='lista_table nogriglia' ALIGN=CENTER>";
    print "<TR><TD COLSPAN=3 CLASS='filtri'>"._FILTRODATE_."</TD>
           <TD COLSPAN=3  CLASS='filtri'>"._FILTROALTRI_."</TD>
           <TD COLSPAN=2  CLASS='filtri'> </TD></TR>";//FFE08F
    print "<TR>";
    print "<TD ROWSPAN=2>";
      print _ANNO_."<IMG SRC='../img/prev.png' CLASS='manina' BORDER=0 onClick=cambiavalore(-1,document.caricamento.filtro_anno)>&nbsp;<INPUT TYPE='text' SIZE=5 NAME='filtro_anno' VALUE='".$cmd['filtro_dalanno']."'>&nbsp;<IMG SRC='../img/next.png' CLASS='manina' BORDER=0 onClick=cambiavalore(1,document.caricamento.filtro_anno)><BR>";
      print _MESE_."<SELECT NAME='filtro_mese' SIZE=1 onChange=filtrodate()>".eT_combomesi($cmd['filtro_dalmese'])."</SELECT>";
    print "</TD>";
    
    print "<TD ROWSPAN=2 STYLE='text-align:right;'>";
      print _DTDAL_."&nbsp;<INPUT TYPE='text' NAME='filtro_dal' SIZE=12 VALUE='".$cmd['filtro_dal']."'>&nbsp;<IMG SRC='../img/calendario.png' CLASS='manina' ALIGN=MIDDLE BORDER=0 ALT='"._BRNCALENDARIO_."' TITLE='"._BRNCALENDARIO_."' onclick=\"displayCalendar(document.caricamento.filtro_dal,'dd/mm/yyyy',this)\"><BR>"._DTAL_."&nbsp;<INPUT TYPE='text' NAME='filtro_al' SIZE=12 VALUE='".$cmd['filtro_al']."'>&nbsp;<IMG SRC='../img/calendario.png' CLASS='manina' ALIGN=MIDDLE BORDER=0 ALT='"._BRNCALENDARIO_."' TITLE='"._BRNCALENDARIO_."' onclick=\"displayCalendar(document.caricamento.filtro_al,'dd/mm/yyyy',this)\">";
    print "</TD>";
    
    print "<TD ROWSPAN=2 STYLE='border-right:1px solid black;'>";
      print "<IMG SRC='../img/calendario_oggi.png' CLASS=manina onClick=dataodierna()>"; 
    print "</TD>";

    print "<TD>";
      $app_filtro_cliente['nome'] = $loadcli[$cmd['filtro_tipocliente']][$cmd['filtro_idcliente']]['nome'];
      $app_filtro_cliente['apellido'] = $loadcli[$cmd['filtro_tipocliente']][$cmd['filtro_idcliente']]['apellido'];
      $app_filtro_cliente['ragsoc'] = $loadcli[$cmd['filtro_tipocliente']][$cmd['filtro_idcliente']]['ragsoc'];
      $app_filtro_cliente['fantasia'] = $loadcli[$cmd['filtro_tipocliente']][$cmd['filtro_idcliente']]['fantasia'];
      $app_filtro_cliente['impresa'] = $loadcli[$cmd['filtro_tipocliente']][$cmd['filtro_idcliente']]['impresa'];
      $app_filtro_cliente['nickname'] = $loadcli[$cmd['filtro_tipocliente']][$cmd['filtro_idcliente']]['nickname'];
      

      print _CLIENTE_."&nbsp;<INPUT TYPE='hidden' NAME='tipocliente' VALUE='".$cmd['filtro_tipocliente']."'><INPUT TYPE='hidden' NAME='idcliente' VALUE='".$cmd['filtro_idcliente']."'><INPUT TYPE='text' NAME='cliente_labsogunico' VALUE='".labelsoggettounico($cmd['filtro_tipocliente'],$app_filtro_cliente,0,0,1)."' SIZE=60 READONLY>&nbsp;&nbsp;<IMG SRC='../img/binocolo2.png' onClick=opensoggetti()>&nbsp;<IMG SRC='../img/gomma.png' onClick=gommasoggetti()>";
    print "</TD>";

    print "<TD ROWSPAN=2 STYLE='border-left:1px solid black;width:15%;'>";
       print "<SELECT SIZE=1 NAME='filtroragsoc' onChange=aggiorna()>";
  
      $query = "SELECT id, label, valore FROM stati WHERE idgruppo=13 ORDER BY ordine";
      $result = mysql_query ($query) or die ("Error_7.1");
      while ($line = mysql_fetch_assoc($result)) {
        print "<OPTION VALUE='".$line['valore']."' ".($line['valore']==$cmd['filtroragsoc'] ? " SELECTED ":"").">".convlang($line['label'])."</OPTION>";
      }
      print "</SELECT>";
      
      print "<BR>";
      
      print "<SELECT SIZE=1 NAME='filtronoconf' STYLE='margin-top:5px;'>
      <OPTION VALUE='1'>"._OPZMOSTRANOCONF_."</OPTION><OPTION VALUE='2' ".($cmd['filtronoconf']==2 ? " SELECTED ":"").">"._OPZHIDENOCONF_."</OPTION></SELECT>";
      
      
    print "</TD>";
    
    print "<TD ROWSPAN=2 STYLE='width:15%;'>";
       print "<SELECT SIZE=1 NAME='filtrofattura' onChange=aggiorna()>";
  
      $query = "SELECT id, label, valore FROM stati WHERE idgruppo=14 ORDER BY ordine";
      $result = mysql_query ($query) or die ("Error_7.2");
      while ($line = mysql_fetch_assoc($result)) {
        print "<OPTION VALUE='".$line['valore']."' ".($line['valore']==$cmd['filtrofattura'] ? " SELECTED ":"").">".convlang($line['label'])."</OPTION>";
      }
      print "</SELECT>";
      
      print "<BR>";
      
      print "<SELECT SIZE=1 NAME='filtronoconf' STYLE='margin-top:5px;'>
      <OPTION VALUE='1'>"._OPZMOSTRANOCONF_."</OPTION><OPTION VALUE='2' ".($cmd['filtronoconf']==2 ? " SELECTED ":"").">"._OPZHIDENOCONF_."</OPTION></SELECT>";
      
      
    print "</TD>";

    print "<TD ROWSPAN=2>";
      print "<IMG SRC='../img/updblu.png' BORDER=0 onClick=aggiorna()>";
    print "</TD>";
    
    

    print "<TD ROWSPAN=2>";
      print "<IMG SRC='../img/contab_add.png' BORDER=0 onClick=openaggiungi(0)>";
    print "</TD>";
    
    print "</TR>";
    
    //ricerca centri di costo
    print "<TR>";
    print "<TD>";
      print _LBLCDICOSTO_."&nbsp;<INPUT TYPE='hidden' NAME='idcdcosto' VALUE='".$cmd['filtro_idcdcosto']."'><INPUT TYPE='text' NAME='cdcosto_cod' VALUE='".$cmd['filtro_cdcosto_cod']."' SIZE=8 onBlur=associa_cdcosto(document.caricamento.cdcosto_cod.value)>&nbsp;<INPUT TYPE='text' NAME='cdcosto_descr' VALUE='".$vetcdcostolineare[$cmd['filtro_cdcosto_cod']]['descr']."' SIZE=35 READONLY>&nbsp;&nbsp;<IMG SRC='../img/binocolo2.png' onClick=opencdcosto()>&nbsp;<IMG SRC='../img/gomma.png' onClick=gommacdcosto()>"; 
    print "</TD>";
    print "</TR>";
  
    
  print "</TABLE>";
  print "</FORM>";
  print "<BR><HR SIZE='2' WIDTH=60%><BR>";
  
  print "<TABLE STYLE='width:98%;' CLASS='lista_table griglia' ALIGN=CENTER>";
  
  //testa
    print "<TR STYLE='height:25px !important;'>";
      //print "<TH CLASS='riga-header ' STYLE='border-right: 2px solid black;height:25px !important;'>&nbsp;</TH>";
      print "<TH>&nbsp;</TH>";
      print "<TH COLSPAN=4 CLASS='riga-header entrate' STYLE='width:43%;height:25px !important;'>"._ENTRATA_."</TH>";
      
      print "<TH COLSPAN=4 CLASS='riga-header uscite' STYLE='width:43%;height:25px !important;'>"._USCITA_."</TH>";
      
      //print "<TH CLASS='riga-header ' STYLE='width:10%;border-right: 2px solid black;height:25px !important;'>&nbsp;</TH>";
      print "<TH STYLE='width:10%;'>&nbsp;</TH>";
    print "</TR>";
    
    print "<TR>";
      print "<TH CLASS='riga-header ' STYLE='border-right: 2px solid black;'>"._LBLDATA_."</TH>";
      print "<TH CLASS='riga-header ' STYLE='width:7%;'>"._LBLMONTO_."</TH>";
      print "<TH CLASS='riga-header ' STYLE='width:17%;'>"._LBLCLIENTE_."</TH>";
      print "<TH CLASS='riga-header ' STYLE='width:15%;'>"._LBLDESCRIZIONE_SHORT_."</TH>";
      print "<TH CLASS='riga-header ' STYLE='width:4%;border-right: 2px solid black;'>&nbsp;</TH>";
      print "<TH CLASS='riga-header ' STYLE='width:7%;'>"._LBLMONTO_."</TH>";
      print "<TH CLASS='riga-header ' STYLE='width:17%;'>"._LBLBENEFICIARIO_."</TH>";
      print "<TH CLASS='riga-header ' STYLE='width:15%;'>"._LBLDESCRIZIONE_SHORT_."</TH>";
      print "<TH CLASS='riga-header ' STYLE='width:4%;border-right: 2px solid black;'>&nbsp;</TH>";
      print "<TH CLASS='riga-header ' STYLE='width:10%;border-right: 2px solid black;'>"._LBLTOTALE_."</TH>";
    print "</TR>";
    
  $buf_tabella="";
  
  $totali = array();
  
  $dtoggi = day_mezzanotte(time());
  
  foreach ($vetdati as $key => $cur) {
    //data
    $curdata = $key;

    $curne[1]=1;     //COSTI
    $curne[2]=1;     //ENTRATE
    $done=false;
    
    $fstampa['tot']=1;
    
    while (!$done) {
      $buf_tabella .= "<TR CLASS='##RIGHESTYLE##'>";
      
      $dtpago=0;
      
      if ($curdata!="") {
        $buf_tabella .= "<TD ROWSPAN=##RIGHETOT## CLASS=' confinedx ".($key == day_mezzanotte(time()) ? " td_data ":"")."'>";
          $buf_tabella .= date("d/m/Y",$curdata);
        $buf_tabella .="</TD>";
        $curdata = "";
      }
          //entrate
          if($curne[2]<=$cur['ne'][2]) {
            //c'Š una entrata
            //print "<TD>".$cur[2]['dati'][$curne[2]]['entrate_id']."</TD>";
            
            //if ($dtpago==0) {$dtpago=$cur[2]['dati'][$curne[2]]['dtpagoreale'];}
            $dtpago=$cur[2]['dati'][$curne[2]]['dtpagoreale'];
            
            $app_curclass = "##TDSTYLE##";
            /*if ($cur[2]['dati'][$curne[2]]['importo']==0) {
              $app_curclass .= " blink_1";
            } else {
              $appid="";
            }*/
             
            $buf_tabella .= "<TD ALIGN=RIGHT CLASS='".$app_curclass."'>";
              $buf_tabella .= number_format ($cur[2]['dati'][$curne[2]]['importo'],$cifredecimali,",",".");
              if ($cur[2]['dati'][$curne[2]]['importo']==0) {
                $buf_tabella .="<IMG SRC='../img/blink.png' BORDER=0 ALIGN=LEFT>";
              }
              if ($cur[2]['dati'][$curne[2]]['nquota']>1) {
                $buf_tabella .= "<BR><IMG SRC='../img/monete16.png'> [".$cur[2]['dati'][$curne[2]]['thisquota']."/". $cur[2]['dati'][$curne[2]]['nquota']."]";
              }
            $buf_tabella .= "</TD>";
            $buf_tabella .= "<TD CLASS='##TDSTYLE##'>";
              $cur_tipocliente = $cur[2]['dati'][$curne[2]]['tipocliente'];
              $cur_idcliente = $cur[2]['dati'][$curne[2]]['entrate_idcliente'];
              
              
              //impresa -> 1     persona -> 0
              //$appvet = $loadcli[$cur_tipocliente][$cur_idcliente];
              $appvet = $loadcli[$cur[2]['dati'][$curne[2]]['tipocliente']][$cur[2]['dati'][$curne[2]]['entrate_idcliente']];
              
              $buf_tabella .= labelsoggettounico($cur_tipocliente,$appvet,1,$fragsoc,$ffantasia);
              
              /*if ($loadcli[$cur_tipocliente][$cur_idcliente]['impresa']==1) {
                //impresa
                $buf_tabella .= "<IMG SRC='../img/industria20.png'> ".$loadcli[$cur_tipocliente][$cur_idcliente]['nome']." ".$loadcli[$cur_tipocliente][$cur_idcliente]['apellido']." (".$loadcli[$cur_tipocliente][$cur_idcliente]['fantasia'].")";
              } else {
                $buf_tabella .= "<IMG SRC='../img/persone20.png'> ".$loadcli[$cur_tipocliente][$cur_idcliente]['nome']." ".$loadcli[$cur_tipocliente][$cur_idcliente]['apellido']." (".$loadcli[$cur_tipocliente][$cur_idcliente]['fantasia'].")";
              }*/
              
              
            //  .$cur[2]['dati'][$curne[2]]['entrate_idcliente'].
            $buf_tabella .= "</TD>";
            $buf_tabella .= "<TD CLASS='##TDSTYLE##'>".$cur[2]['dati'][$curne[2]]['entrate_descr']."</TD>";
            $buf_tabella .= "<TD CLASS='##TDSTYLE## confinedx'>";
            //comandi
              $buf_tabella .= "<TABLE CLASS='nogriglia' WIDTH=100%><TR>";
              $buf_tabella .= "<TD><IMG SRC='../img/mod.png' CLASS='manina' BORDER='0' onClick=openaggiungi(".$cur[2]['dati'][$curne[2]]['entrate_id'].")></TD>";
              $buf_tabella .= "<TD><A HREF='#' onClick=urldelete(\"". FILE_DBQUERY ."?cmd=". FASEDEL ."&codicepagina=". codiceform(FILE_CORRENTE) ."&selobj=".$cur[2]['dati'][$curne[2]]['entrate_id']."\")><IMG SRC='../img/canc.png' border=0 ALT='"._LBLCANCELLA_."' TITLE='"._LBLCANCELLA_."'></A></TD>";

              if ($key <= $dtoggi) {
                if ($dtpago>0) {
                  $imgconferma = "okverde16.png";
                } else {
                  $imgconferma = "okrosso16.png";
                }
                $buf_tabella .= "<TD><IMG SRC='../img/".$imgconferma."' CLASS='manina' BORDER='0' onClick=openconferma(".$cur[2]['dati'][$curne[2]]['entrate_id'].",".intval($cur[2]['dati'][$curne[2]]['dtpagoreale']).")>";
                if ($_SESSION['mgdebug']==1) {
                  $buf_tabella .= "VALORE DTPAGO:[".$dtpago."]";
                }
                $buf_tabella .= "</TD>";
              } else {
                $buf_tabella .= "<TD><IMG SRC='../img/okgrigio16.png' BORDER='0'></TD>";
              }
              $buf_tabella .= "</TR>";
              $buf_tabella .="<TR>";
              $buf_tabella .= "<TD><IMG SRC='../img/lente.png' CLASS='manina' BORDER='0' onClick=openaggiungi(".$cur[1]['dati'][$curne[1]]['entrate_id'].")></TD>";
              //$buf_tabella .="<TD></TD>";
              $buf_tabella .="<TD></TD>";
              $buf_tabella .="<TD></TD>";
              $buf_tabella .="<TR>";
              
              $buf_tabella .= "</TABLE>";
            $buf_tabella .= "</TD>";
                      
            if ($dtpago==0) {
              $totali[2]['noconf'] +=$cur[2]['dati'][$curne[2]]['importo'];
            } else {
              $totali[2]['conf'] +=$cur[2]['dati'][$curne[2]]['importo'];
            }
            
            //$totali[2] +=$cur[2]['dati'][$curne[2]]['importo'];
            $curne[2]++;
            
            //colore TD
            $cur_tdstyle="";
            if (($key < $dtoggi) && ($dtpago==0)) {
              //nel passato, non pagata
              $cur_tdstyle = "riga-arancio1";
            }
            $buf_tabella = str_replace("##TDSTYLE##", $cur_tdstyle, $buf_tabella);
            
          } else {
            $buf_tabella .= "<TD>&nbsp;</TD>";
            $buf_tabella .= "<TD>&nbsp;</TD>";
            $buf_tabella .= "<TD>&nbsp;</TD>";
            $buf_tabella .= "<TD CLASS=' confinedx'>&nbsp;</TD>";
          }
          
          //uscite
          if($curne[1]<=$cur['ne'][1]) {
            //c'Š una entrata
            //print "<TD>".$cur[1]['dati'][$curne[1]]['entrate_id']."</TD>";
            
            //if ($dtpago==0) {$dtpago=$cur[1]['dati'][$curne[1]]['dtpagoreale'];}
            $dtpago=$cur[1]['dati'][$curne[1]]['dtpagoreale'];
            
            //$buf_tabella .= "<TD ALIGN=RIGHT>".number_format ($cur[1]['dati'][$curne[1]]['importo'],$cifredecimali,",",".")."</TD>";
            $buf_tabella .= "<TD ALIGN=RIGHT CLASS='##TDSTYLE##'>";
              $buf_tabella .= number_format ($cur[1]['dati'][$curne[1]]['importo'],$cifredecimali,",",".");
              if ($cur[1]['dati'][$curne[1]]['importo']==0) {
                $buf_tabella .="<IMG SRC='../img/blink.png' BORDER=0 ALIGN=LEFT>";
              }
              if ($cur[1]['dati'][$curne[1]]['nquota']>1) {
                $buf_tabella .= "<BR><IMG SRC='../img/monete16.png'> [".$cur[1]['dati'][$curne[1]]['thisquota']."/". $cur[1]['dati'][$curne[1]]['nquota']."]";
              }
            $buf_tabella .= "</TD>";
            $buf_tabella .= "<TD CLASS='##TDSTYLE##'>";
              $cur_tipocliente = $cur[1]['dati'][$curne[1]]['tipocliente'];
              $cur_idcliente = $cur[1]['dati'][$curne[1]]['entrate_idcliente'];
              
              //$appvet = $loadcli[$cur_tipocliente][$cur_idcliente];
              $appvet = $loadcli[$cur[1]['dati'][$curne[1]]['tipocliente']][$cur[1]['dati'][$curne[1]]['entrate_idcliente']];
              
              $buf_tabella .= labelsoggettounico($cur_tipocliente,$appvet,1,$fragsoc,$ffantasia);
              
              
              
              //impresa -> 1     persona -> 0
              /*if ($loadcli[$cur_tipocliente][$cur_idcliente]['impresa']==1) {
                //impresa
                $buf_tabella .= "<IMG SRC='../img/industria20.png'> ".$loadcli[$cur_tipocliente][$cur_idcliente]['nome']." ".$loadcli[$cur_tipocliente][$cur_idcliente]['apellido']." (".$loadcli[$cur_tipocliente][$cur_idcliente]['fantasia'].")";
              } else {
                $buf_tabella .= "<IMG SRC='../img/persone20.png'> ".$loadcli[$cur_tipocliente][$cur_idcliente]['nome']." ".$loadcli[$cur_tipocliente][$cur_idcliente]['apellido']." (".$loadcli[$cur_tipocliente][$cur_idcliente]['fantasia'].")";
              }*/
              
            //  .$cur[2]['dati'][$curne[2]]['entrate_idcliente'].
            $buf_tabella .= "</TD>";
            $buf_tabella .= "<TD CLASS='##TDSTYLE##'>";
              $buf_tabella .= $cur[1]['dati'][$curne[1]]['entrate_descr'];
              if ($ffattura==1) {
                $buf_tabella .="<BR><B>Factura: </B>".$cur[1]['dati'][$curne[1]]['entrate_doc'];
              }
              if ($fpago==1) {
                $buf_tabella .="<BR><B>Pago: </B>".$cur[1]['dati'][$curne[1]]['pago_label']." ".$cur[1]['dati'][$curne[1]]['refpago']."<BR>";
                $buf_tabella .="<B>Cuenta Corriente: </B>".$cur[1]['dati'][$curne[1]]['nomebanca']." ".$cur[1]['dati'][$curne[1]]['ccbanca_codice'];
              }
              
            $buf_tabella .= "</TD>";
            $buf_tabella .= "<TD CLASS='##TDSTYLE## confinedx'>";
              //comandi
              $buf_tabella .= "<TABLE CLASS='nogriglia' WIDTH=100%>";
              $buf_tabella .= "<TR>";
              $buf_tabella .= "<TD><IMG SRC='../img/mod.png' CLASS='manina' BORDER='0' onClick=openaggiungi(".$cur[1]['dati'][$curne[1]]['entrate_id'].")></TD>";
              $buf_tabella .= "<TD><A HREF='#' onClick=urldelete(\"". FILE_DBQUERY ."?cmd=". FASEDEL ."&codicepagina=". codiceform(FILE_CORRENTE) ."&selobj=".$cur[1]['dati'][$curne[1]]['entrate_id']."\")><IMG SRC='../img/canc.png' border=0 ALT='"._LBLCANCELLA_."' TITLE='"._LBLCANCELLA_."'></A></TD>";
              if ($key <= $dtoggi) {
                if ($dtpago>0) {
                  $imgconferma = "okverde16.png";
                } else {
                  $imgconferma = "okrosso16.png";
                }
                $buf_tabella .= "<TD><IMG SRC='../img/".$imgconferma."' CLASS='manina' BORDER='0' onClick=openconferma(".$cur[1]['dati'][$curne[1]]['entrate_id'].",".intval($cur[1]['dati'][$curne[1]]['dtpagoreale']).")>";
                if ($_SESSION['mgdebug']==1) {
                  $buf_tabella .= "VALORE DTPAGO:[".$dtpago."]";
                }
                $buf_tabella .= "</TD>";
              } else {
                $buf_tabella .= "<TD><IMG SRC='../img/okgrigio16.png' BORDER='0'></TD>";
              }
              $buf_tabella .="<TR>";
              
              //tooltip
              //messaggio
              $tooltipmsg = "";
              $tooltipmsg .= "<B>".labelsoggettounico($cur_tipocliente,$appvet,1,1,1)."</B><BR>";
              //$tooltipmsg .= number_format ($cur[1]['dati'][$curne[1]]['importo'],$cifredecimali,",",".");
              //$tooltipmsg .= $cur[1]['dati'][$curne[1]]['thisquota']."/". $cur[1]['dati'][$curne[1]]['nquota'];
              $tooltipmsg .= "<B>Factura: </B>".$cur[1]['dati'][$curne[1]]['entrate_doc']."<BR>";
              $tooltipmsg .= "<B>Pago: </B>".$cur[1]['dati'][$curne[1]]['pago_label']." ".$cur[1]['dati'][$curne[1]]['refpago']."<BR>";
              $tooltipmsg .= "<B>Cuenta Corriente: </B>".$cur[1]['dati'][$curne[1]]['nomebanca']." ".$cur[1]['dati'][$curne[1]]['ccbanca_codice']."<BR>";
              
              $tooltipcmd = "onMouseover=\"hideddrivetip();ddrivetip('".addslashes($tooltipmsg)."')\"; onMouseout=\"hideddrivetip()\" ";
              
              $buf_tabella .= "<TD><IMG SRC='../img/lente.png' CLASS='manina' BORDER='0' $tooltipcmd ></TD>";
              
              //onClick=openaggiungi(".$cur[1]['dati'][$curne[1]]['entrate_id'].")
              //$buf_tabella .="<TD></TD>";
              $buf_tabella .="<TD></TD>";
              $buf_tabella .="<TD></TD>";
              $buf_tabella .="<TR>";
              
              $buf_tabella .= "</TABLE>";
              
              
            $buf_tabella .= "</TD>"; 
            
            if ($dtpago==0) {
              $totali[1]['noconf'] +=$cur[1]['dati'][$curne[1]]['importo'];
            } else {
              $totali[1]['conf'] +=$cur[1]['dati'][$curne[1]]['importo'];
            }
            
            //$totali[1] +=$cur[1]['dati'][$curne[1]]['importo'];
            
            
            $curne[1]++;
            
            //colore TD
            $cur_tdstyle="";
            if (($key < $dtoggi) && ($dtpago==0)) {
              //nel passato, non pagata
              $cur_tdstyle = "riga-arancio1";
            }
            $buf_tabella = str_replace("##TDSTYLE##", $cur_tdstyle, $buf_tabella);
            
          } else {
            $buf_tabella .= "<TD>&nbsp;</TD>";
            $buf_tabella .= "<TD>&nbsp;</TD>";
            $buf_tabella .= "<TD>&nbsp;</TD>";
            $buf_tabella .= "<TD CLASS=' confinedx'>&nbsp;</TD>";
          }
          
          //TOTALI
          if ($fstampa['tot']==1) {
            $fstampa['tot']=0;
            $buf_tabella .= "<TD ROWSPAN=##RIGHETOT##>
              <TABLE CLASS='nogriglia' WIDTH=100%>
                <TR><TD>"._ENTRATA_."</TD><TD ALIGN=RIGHT>".number_format ($cur['tot'][2],$cifredecimali,",",".")."</TD></TR>
                <TR><TD>"._USCITA_."</TD><TD ALIGN=RIGHT>".number_format ($cur['tot'][1],$cifredecimali,",",".")."</TD></TR>";
            
            
            $appbilancio = ($cur['tot'][2] - $cur['tot'][1]);
            
            if ($appbilancio < 0) {
              $bilancio_style = "color:red;";
            } else {
              $bilancio_style = "color:green;";
            }
            
            $buf_tabella .= "<TR><TD STYLE='font-size:12px;font-weight:bolder;'>"._BALANCE_."</TD><TD ALIGN=RIGHT STYLE='".$bilancio_style." font-size:12px;font-weight:bolder;'>".number_format ($appbilancio,$cifredecimali,",",".")."</TD></TR>";
            
            
            $buf_tabella .= "</TABLE></TD>";
          }
   
      $buf_tabella .= "</TR>";
      
      if ($curne[2]>$cur['ne'][2] && $curne[1]>$cur['ne'][1]) {
        $done=true;
        
        //max righe
        $maxrighe = max($cur['ne'][1],$cur['ne'][2]);
        $buf_tabella = str_replace("##RIGHETOT##", $maxrighe, $buf_tabella);
        
        //standard
        if ($cur_rowstyle=="form2_lista_riga_pari") {
          $cur_rowstyle="form2_lista_riga_dispari";
        } else {
          $cur_rowstyle="form2_lista_riga_pari";
        }
        
        //futuro
        if ($key > $dtoggi) {
           $cur_rowstyle = "riga-giallo1";
        }
        
        /*if (($key < $dtoggi) && ($dtpago==0)) {
          //nel passato, non pagata
          $cur_rowstyle = "riga-arancio2";
        }*/
        
        if ($key == day_mezzanotte(time())) {
          $cur_rowstyle .= " tr_dtoggi ";
        }
        
        $buf_tabella = str_replace("##RIGHESTYLE##", $cur_rowstyle, $buf_tabella);
        
        
      }
    }
  }
  
  //TOTALI  
  $buf_tabella .= "<TR STYLE='background-color:#DDDDDD;'><TD STYLE='font-size:14px;font-weight:bolder;border-top:2px solid black;'>"._LBLTOTALECONFERMATO_."</TD>";
  $buf_tabella .= "<TD STYLE='border-top:2px solid black; font-weight:bolder; font-size:14px;' ALIGN=RIGHT>".number_format ($totali[2]['conf'],$cifredecimali,",",".")."</TD>";
  $buf_tabella .= "<TD STYLE='border-top:2px solid black;'></TD><TD STYLE='border-top:2px solid black;'></TD><TD STYLE='border-top:2px solid black;'></TD>";
  
  $buf_tabella .= "<TD STYLE='border-top:2px solid black; font-weight:bolder; font-size:14px;' ALIGN=RIGHT>".number_format ($totali[1]['conf'],$cifredecimali,",",".")."</TD>";
  $buf_tabella .= "<TD STYLE='border-top:2px solid black;'></TD><TD STYLE='border-top:2px solid black;'></TD><TD STYLE='border-top:2px solid black;'></TD>";
  
  $buf_tabella .= "<TD STYLE='border-top:2px solid black;'>";
  $buf_tabella .= "<TABLE CLASS='nogriglia' WIDTH=100%>
                <TR><TD>"._ENTRATA_."</TD><TD ALIGN=RIGHT>".number_format ($totali[2]['conf'],$cifredecimali,",",".")."</TD></TR>
                <TR><TD>"._USCITA_."</TD><TD ALIGN=RIGHT>".number_format ($totali[1]['conf'],$cifredecimali,",",".")."</TD></TR>";
            
            
            $appbilancio = ($totali[2]['conf'] - $totali[1]['conf']);
            
            if ($appbilancio < 0) {
              $bilancio_style = "color:red;";
            } else {
              $bilancio_style = "color:green;";
            }
            
            $buf_tabella .= "<TR><TD STYLE='font-size:12px;font-weight:bolder;'>BALANCE</TD><TD ALIGN=RIGHT STYLE='".$bilancio_style." font-size:12px;font-weight:bolder;'>".number_format ($appbilancio,$cifredecimali,",",".")."</TD></TR>";
            
            
            $buf_tabella .= "</TABLE></TD>";
  $buf_tabella .= "</TR>";
  
    //totale non confermato
  $buf_tabella .= "<TR STYLE='background-color:#DDDDDD;'><TD STYLE='font-size:14px;font-weight:bolder;border-top:2px solid black;'>"._LBLTOTALENONCONFERMATO_."</TD>";
  $buf_tabella .= "<TD STYLE='border-top:2px solid black; font-weight:bolder; font-size:14px;' ALIGN=RIGHT>".number_format ($totali[2]['noconf'],$cifredecimali,",",".")."</TD>";
  $buf_tabella .= "<TD STYLE='border-top:2px solid black;'></TD><TD STYLE='border-top:2px solid black;'></TD><TD STYLE='border-top:2px solid black;'></TD>";
  
  $buf_tabella .= "<TD STYLE='border-top:2px solid black; font-weight:bolder; font-size:14px;' ALIGN=RIGHT>".number_format ($totali[1]['noconf'],$cifredecimali,",",".")."</TD>";
  $buf_tabella .= "<TD STYLE='border-top:2px solid black;'></TD><TD STYLE='border-top:2px solid black;'></TD><TD STYLE='border-top:2px solid black;'></TD>";
  
  $buf_tabella .= "<TD STYLE='border-top:2px solid black;'>";
  $buf_tabella .= "<TABLE CLASS='nogriglia' WIDTH=100%>
                <TR><TD>"._ENTRATA_."</TD><TD ALIGN=RIGHT>".number_format ($totali[2]['noconf'],$cifredecimali,",",".")."</TD></TR>
                <TR><TD>"._USCITA_."</TD><TD ALIGN=RIGHT>".number_format ($totali[1]['noconf'],$cifredecimali,",",".")."</TD></TR>";
            
            
            $appbilancio = ($totali[2]['noconf'] - $totali[1]['noconf']);
            
            if ($appbilancio < 0) {
              $bilancio_style = "color:red;";
            } else {
              $bilancio_style = "color:green;";
            }
            
            $buf_tabella .= "<TR><TD STYLE='font-size:12px;font-weight:bolder;'>BALANCE</TD><TD ALIGN=RIGHT STYLE='".$bilancio_style." font-size:12px;font-weight:bolder;'>".number_format ($appbilancio,$cifredecimali,",",".")."</TD></TR>";
            
            
            $buf_tabella .= "</TABLE></TD>";
  $buf_tabella .= "</TR>";
  
  $buf_tabella .= "</TABLE>";
  
  print $buf_tabella;
  print "<BR><BR>"; 
  print "<INPUT TYPE='button' NAME='aaaaaa' VALUE='cambia classe' onClick=lampeggia()><INPUT TYPE='text' NAME='blink_style' id='blink_style' VALUE='0'>";
  print "<DIV id='fottiti'></DIV>";

?>
<SCRIPT>
  

  function openconferma(selobj,dtpago) {
    var width  = 650;
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
  
    fltipo = '&selobj='+selobj+"&dt="+dtpago;
    
    figlio6 = window.open("frm_contab_dtpagopopup.php?p=0"+fltipo,"conferma",params);
    figlio6.opener=self;
  }

  function dataodierna() {
    var datai, dataf;
    var mese, giorno;
    
    datai = new Date();
    
    //versione 1 gg
    giorno = datai.getDate();
    if (giorno<10) {giornostr = "0";}
    giornostr = giornostr + giorno;
    
    mese = datai.getMonth();
    mese++;
    if (mese<10) {mesestr = "0";}
    mesestr = mesestr + mese;
    
    document.caricamento.filtro_dal.value = giornostr+"/"+mesestr+"/"+datai.getFullYear();
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
    
    figlio3 = window.open("frm_contab_soggpopup.php?p=0"+fltipo,"soggetti",params);
    figlio3.opener=self;
  }
  
  function openaggiungi(selobj) {
  var width  = 800;
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
  document.caricamento.idcliente.value="-1";
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
  
</SCRIPT>
<script type="text/javascript">

/***********************************************
* Cool DHTML tooltip script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

var offsetxpoint=-60 //Customize x offset of tooltip
var offsetypoint=20 //Customize y offset of tooltip
var ie=document.all
var ns6=document.getElementById && !document.all
var enabletip=false
if (ie||ns6)
var tipobj=document.all? document.all["dhtmltooltip"] : document.getElementById? document.getElementById("dhtmltooltip") : ""

function ietruebody(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function ddrivetip(thetext, thecolor, thewidth){
if (ns6||ie){
if (typeof thewidth!="undefined") tipobj.style.width=thewidth+"px"
if (typeof thecolor!="undefined" && thecolor!="") tipobj.style.backgroundColor=thecolor
tipobj.innerHTML=thetext
enabletip=true
return false
}
}

function positiontip(e){
if (enabletip){
var curX=(ns6)?e.pageX : event.clientX+ietruebody().scrollLeft;
var curY=(ns6)?e.pageY : event.clientY+ietruebody().scrollTop;
//Find out how close the mouse is to the corner of the window
var rightedge=ie&&!window.opera? ietruebody().clientWidth-event.clientX-offsetxpoint : window.innerWidth-e.clientX-offsetxpoint-20
var bottomedge=ie&&!window.opera? ietruebody().clientHeight-event.clientY-offsetypoint : window.innerHeight-e.clientY-offsetypoint-20

var leftedge=(offsetxpoint<0)? offsetxpoint*(-1) : -1000

//if the horizontal distance isn't enough to accomodate the width of the context menu
if (rightedge<tipobj.offsetWidth)
//move the horizontal position of the menu to the left by it's width
tipobj.style.left=ie? ietruebody().scrollLeft+event.clientX-tipobj.offsetWidth+"px" : window.pageXOffset+e.clientX-tipobj.offsetWidth+"px"
else if (curX<leftedge)
tipobj.style.left="5px"
else
//position the horizontal position of the menu where the mouse is positioned
tipobj.style.left=curX+offsetxpoint+"px"

//same concept with the vertical position
if (bottomedge<tipobj.offsetHeight)
tipobj.style.top=ie? ietruebody().scrollTop+event.clientY-tipobj.offsetHeight-offsetypoint+"px" : window.pageYOffset+e.clientY-tipobj.offsetHeight-offsetypoint+"px"
else
tipobj.style.top=curY+offsetypoint+"px"
tipobj.style.visibility="visible"
}
}

function hideddrivetip(){
if (ns6||ie){
enabletip=false
tipobj.style.visibility="hidden"
tipobj.style.left="-1000px"
tipobj.style.backgroundColor=''
tipobj.style.width=''
}
}

		function changecss(theClass,element,value) {
	//Last Updated on October 10, 1020
	//documentation for this script at
	//http://www.shawnolson.net/a/503/altering-css-class-attributes-with-javascript.html
	 var cssRules;

	 var added = false;
	 for (var S = 0; S < document.styleSheets.length; S++){

    if (document.styleSheets[S]['rules']) {
	  cssRules = 'rules';
	 } else if (document.styleSheets[S]['cssRules']) {
	  cssRules = 'cssRules';
	 } else {
	  //no rules found... browser unknown
	 }

	  for (var R = 0; R < document.styleSheets[S][cssRules].length; R++) {
	   if (document.styleSheets[S][cssRules][R].selectorText == theClass) {
	    if(document.styleSheets[S][cssRules][R].style[element]){
	    document.styleSheets[S][cssRules][R].style[element] = value;
	    added=true;
		break;
	    }
	   }
	  }
	  if(!added){
	  try{
	  	document.styleSheets[S].insertRule(theClass+' { '+element+': '+value+'; }',document.styleSheets[S][cssRules].length);

	  } catch(err){
	  		try{document.styleSheets[S].addRule(theClass,element+': '+value+';');}catch(err){}

	  }

	  //if(document.styleSheets[S].insertRule){
			  //document.styleSheets[S].insertRule(theClass+' { '+element+': '+value+'; }',document.styleSheets[S][cssRules].length);
			//} else if (document.styleSheets[S].addRule) {
				//document.styleSheets[S].addRule(theClass,element+': '+value+';');
			//}
	  }
	 }
	}

document.onmousemove=positiontip

</script>
<?php
  include ("finepagina.php");

?>
</body>
</html>
