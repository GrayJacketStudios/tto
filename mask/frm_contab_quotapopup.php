<?php
  $pagever = "1.0";
  $pagemod = "27/01/2011 0.29.47";
  require_once("form_cfg.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <link rel="stylesheet" type="text/css" href="../css/main.css" />
  <link rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></link>
  <STYLE>
   .opt_individuale {
      background-color:#5FE4FF;
      text-align:center;
   }
   .opt_impresa {
      background-color:#C1FF5F;
      text-align:center;
   }
   .tr_evid:hover {
    background-color:red;
   }
  </STYLE>
</head>
<body>
<script type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<?php
  include ("iniziopagina.php");

  //CARICAMENTO VALORI PER EDIT
  define("FILE_CORRENTE", "frm_contab_quotapopup.php");

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  
  $cmd['monto'] = $_REQUEST['mt'];
  $cmd['dt'] = $_REQUEST['dt'];
  
  $cmd['mod'] = $_REQUEST['modificato'];
  $cmd['nqo'] = $_REQUEST['nqo'];
  
  $cmd['nquote'] = $_REQUEST['nquote'];
  if ($cmd['nquote']=="") {$cmd['nquote']=2;}
  
  
  
  $curtot=0;
  $monto = $cmd['monto'];
  $curdt = $cmd['dt'];
  
  $quote = array();
  for ($i=1;$i<=$cmd['nquote'];$i++) {
    //calcolo generale
    if (($cmd['mod']==1) && ($cmd['nquote']==$cmd['nqo'])) {
      //modifica e numero quote uguale, prendo il valore del campo
      $curmonto = $_REQUEST['qmt_'.$i];
    } else {
      $curmonto = round($monto/$cmd['nquote']);
    }
     
    $quote[$i]['monto'] = $curmonto;
    if ($i==$cmd['nquote']) {
      //ultima quota, aggiunto totale
      $quote[$i]['monto'] = $monto - $curtot;
    }
    $curtot += $curmonto;
    
    //date
    //piazzo e sposto
    $quote[$i]['dt'] = $curdt;
    
    $fine=false;
    //$curdt = strtotime("+1 month", eT_dt2times($curdt));
    $curdt = strtotime("+$i month", eT_dt2times($cmd['dt']));
    while (!$fine) {
      $fine = true;

      //sabato
      if (date("w",$curdt)==6) {
        $curdt = strtotime("+2 day", $curdt);
        $fine=false;
      }
      
      //domenica
      if (date("w",$curdt)==0) {
        $curdt = strtotime("+1 day", $curdt);
        $fine=false;
      }
      
      //feste
      if (isset($vetfeste[$curdt])) {
        $curdt = strtotime("+1 day", $curdt);
        $fine=false;
      } 
    }
    
    $curdt = date("d/m/Y",$curdt);
       
  }
  

   
    print "<BR><H1>"._QUOTE_."</H1>";
    print "<BR>";
    
    //form
    print "<FORM NAME='caricamento' METHOD=POST ACTION='".FILE_CORRENTE."'>";
    
    print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='80%'>";
    
    //somma originale
    
    print "<TR>";
    print "<TD >"._LBLMONTO_."&nbsp;".number_format ($cmd['monto'],$cifredecimali,",",".")."<INPUT TYPE='hidden' NAME='mt' VALUE='".$cmd['monto']."'><INPUT TYPE='hidden' NAME='dt' VALUE='".$cmd['dt']."'><INPUT TYPE='hidden' NAME='nqo' VALUE='".$cmd['nquote']."'></TD>";
    print "</TR>";
    
    print "<TR>";
    print "<TD>"._LBLNUMEROQUOTE_."&nbsp;<INPUT TYPE='text' NAME='nquote' VALUE='".$cmd['nquote']."'  onChange=toggle_modifica()></TD>";
    print "</TR>";
    
    //quote
    $buf_tabella="";
    foreach ($quote as $key =>$cur) {
      if ($cur_rowstyle=="form2_lista_riga_pari") {
        $cur_rowstyle="form2_lista_riga_dispari";
      } else {
        $cur_rowstyle="form2_lista_riga_pari";
      }
      $buf_tabella .= "<TR CLASS=$cur_rowstyle>";
      $buf_tabella .= "<TD CLASS=lista_col_form2>"._QUOTANN_." [".$key."] <INPUT SIZE=10 STYLE='text-align:right;' TYPE='text' NAME='qmt_".$key."' VALUE='".$cur['monto']."' onChange=toggle_modifica()>&nbsp;&nbsp;<INPUT TYPE='text' NAME='qdt_".$key."' VALUE='".$cur['dt']."' SIZE=10 onChange=toggle_modifica()>&nbsp;<IMG SRC='../img/calendario.png' ALIGN=MIDDLE BORDER=0 ALT='"._BRNCALENDARIO_."' TITLE='"._BRNCALENDARIO_."' onclick=\"displayCalendar(document.caricamento.qdt_".$key.",'dd/mm/yyyy',this)\"><INPUT TYPE='hidden' NAME='qdto_".$key."' VALUE='".$cur['dt']."' SIZE=10></TD>";
      $buf_tabella .= "</TR>";
    }
    print $buf_tabella;
    
    print "<TR id='trmodificato' STYLE='display:block;'><TD STYLE='text-align:center;'><INPUT TYPE='hidden' NAME='modificato' VALUE='0'><INPUT TYPE='button' NAME='invia' VALUE='"._LBLCONFERMA_."' onClick=btn_salva()></TD></TR>";
    print "</TABLE>";


  include ("finepagina.php");
?>
<SCRIPT>
function toggle_modifica() {
  //var elem = document.getElementById("trmodificato");  
  //elem.style.display = "block";
  document.caricamento.modificato.value="1"; 
  document.caricamento.dt.value=document.caricamento.qdt_1.value;
  document.caricamento.invia.value="<?=_LBLRICALCOLA_ ?>";
}

function btn_salva() {
  if (document.caricamento.modificato.value==1) {
    document.caricamento.submit();
  } else {
    //  self.close();
    var nquote;
    var str;
    
    nquote = parseInt(document.caricamento.nquote.value, 10);
    str = "<TABLE><TR><TD><?=_LBLNUMEROQUOTE_?> <INPUT TYPE='text' NAME='nquote' VALUE='"+nquote+"' READONLY SIZE=5></TD></TR>";

    for (var i=1;i<=nquote;i++) {
      str = str + "<TR><TD><?=_QUOTANN_?> ["+i+"] <INPUT SIZE=10 TYPE='text' NAME='qmt_"+i+"' VALUE='"+document.caricamento['qmt_'+i].value+"' READONLY> <INPUT SIZE=10 TYPE='text' NAME='qdt_"+i+"' VALUE='"+document.caricamento['qdt_'+i].value+"' READONLY></TD></TR>";
    }
    str = str + "</TABLE>";

    
    //parent.blocco_quota.innerHTML = str;
    var blocco_quota = opener.document.getElementById("blocco_quota");
    blocco_quota.innerHTML = unescape(str);
    self.close();
    
  }
}

</SCRIPT>
<BR>
<BR>
</body>
</html>
