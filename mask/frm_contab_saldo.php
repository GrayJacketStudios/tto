<?php
  $pagever = "0.5";
  $pagemod = "31/01/2011 21.54.33";
  require_once("form_cfg.php");
  
  
  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
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
  include ("iniziopagina.php");
  
  $cifredecimali = 0;

  //CARICAMENTO VALORI PER EDIT
  define("FILE_CORRENTE", "frm_contab_saldo.php");

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

  $query = "SELECT id, dt, importo, note FROM saldo ORDER BY dt DESC";
  
  $result = mysql_query($query) or die ("Error 1.1$query");
  while ($line = mysql_fetch_assoc($result)) {
    $chiave = $line['id'];
    
    $saldi[$chiave]['importo'] = $line['importo'];
    $saldi[$chiave]['dt'] = $line['dt'];
    $saldi[$chiave]['note'] = $line['note'];
    
  }
  
  print "<BR><H1>"._SALDOINIZIALE_."</H1>";
  print "<DIV STYLE='margin-right:30px;' ALIGN=RIGHT><IMG SRC='../img/contab_add.png' BORDER=0 CLASS='manina' onClick=chiamapopup(". FASEINS .",0)></DIV>";
  print "<TABLE CLASS=lista_table ALIGN=CENTER STYLE='width:70%;'>";
  print "<TR><TD CLASS=lista_tittab>"._LBLDATA_."</TD><TD CLASS=lista_tittab>"._LBLMONTO_."</TD><TD CLASS=lista_tittab>"._NOTES_."</TD><TD CLASS=lista_tittab>&nbsp;</TD></TR>";
  foreach ($saldi as $key=>$cur) {
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
    print "<TD CLASS=lista_col_form2>".eT_strdt2string($cur['dt'])."</TD>";
    print "<TD CLASS=lista_col_form2 ALIGN=RIGHT>".number_format ($cur['importo'],$cifredecimali,",",".")."</TD>";
    print "<TD CLASS=lista_col_form2>".$cur['note']."</TD>";
    
    //COMANDI
    print "<TD CLASS=lista_col_form2><TABLE width=100%><TR>
    <TD ALIGN=CENTER><IMG CLASS='manina' SRC='../img/mod.png' border=0 ALT='"._LBLMATITA_."' TITLE='"._LBLMATITA_."' onClick=chiamapopup(". FASEMOD .",". $key .")></A></TD>
    <TD ALIGN=CENTER><FORM ACTION='". FILE_DBQUERY ."?cmd=". FASEDEL ."&codicepagina=". codiceform(FILE_CORRENTE) ."&selobj=". $key ."' METHOD='POST' TARGET=inserimento NAME='cancella".$key."'></FORM><IMG SRC='../img/canc.png' border=0 ALT='"._LBLCANCELLA_."' TITLE='"._LBLCANCELLA_."' CLASS='manina' onClick=cancella(".$key.")></TD>";
		print "</TR></TABLE>";
    print "</TD>";
    print "</TR>";
    
  }
  
  print "<BR>";
  

  print "<BR><BR>"; 

?>
<SCRIPT>
  function cancella(chiave) {
    var avanti = confirm ("<?=_MSGSEISICURO_?>");
		if (avanti==true) {
				document['cancella'+chiave].submit();
        self.close();
		}
		
  }
  
  function chiamapopup(cmd,selobj) {
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
  
    fltipo = "&selobj="+selobj+"&cmd="+cmd;
    
    figlio6 = window.open("frm_contab_saldopopup.php?p=0"+fltipo,"saldo",params);
    figlio6.opener=self;
  }

 
  
</SCRIPT>
<?php
  include ("finepagina.php");

?>
</body>
</html>
