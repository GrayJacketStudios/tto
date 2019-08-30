<?php
  $pagever = "1.0";
  $pagemod = "18/11/2010 22.59.15";
  require_once("form_cfg.php");
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
<script type="text/javascript" src="../jscolor/jscolor.js"></script>
<script type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<?php
  include ("iniziopagina.php");

  //CARICAMENTO VALORI PER EDIT
  define("FILE_CORRENTE", "frm_pstudio_evtpopup.php");

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  $selobj = $_REQUEST['selobj'];
  $cmd['comando2'] = $_REQUEST['cmd2'];
  $cmd['prefdeltype'] = $_REQUEST['prefdeltype'];
  $cmd['prefdelid'] = $_REQUEST['prefdelid'];
  $neprefday=$_REQUEST['prefne'];
  $editval['dataini'] = $_REQUEST['dataini'];
  $cmd['dbdeleted'] = "-1, ".$_REQUEST['dbdeleted'];
  if ($editval['dataini']=="") {
    $editval['dataini'] = date("d/m/Y");
  }
  
  $query = "SELECT pstudi.id as pstudi_id, pstudi.idcorso as pstudi_idcorso, pstudi.descr as pstudi_descr, pstudi.datai as pstudi_datai,
          pstudi.dataf as pstudi_dataf, pstudi.compenso as pstudi_compenso, pstudi.durata as pstudi_durata, pstudi.dlezione as pstudi_dlezione,
          pstudi.codsense as pstudi_codsense, pstudi.codotec as pstudi_codotec, pstudi.attivita as pstudi_attivita, pstudi.luogo as pstudi_luogo,
          pstudi.attivo as pstudi_attivo, pstudi.style as pstudi_style,
          pstudi.idclasse as pstudi_idclasse, pstudi.trasporto as pstudi_trasporto
          FROM pstudi
          WHERE pstudi.id=$selobj";

  $result = mysql_query($query) or die ("Error_1.1");
  $line = mysql_fetch_assoc($result);
  $editval['pstudi_idcorso'] = $line['pstudi_idcorso'];
  $editval['pstudi_idclasse'] = $line['pstudi_idclasse'];
  $editval['pstudi_descr'] = $line['pstudi_descr'];
  $editval['pstudi_datai'] = date("d/m/Y",$line['pstudi_datai']);
  $editval['pstudi_dataf'] = date("d/m/Y",$line['pstudi_dataf']);
  $editval['pstudi_compenso'] = $line['pstudi_compenso'];
  $editval['pstudi_trasporto'] = $line['pstudi_trasporto'];
  $editval['pstudi_durata'] = $line['pstudi_durata'];
  $editval['pstudi_dlezione'] = $line['pstudi_dlezione'];
  $editval['pstudi_codsense'] = $line['pstudi_codsense'];
  $editval['pstudi_codotec'] = $line['pstudi_codotec'];
  $editval['pstudi_attivita']=$line['pstudi_attivita'];
  $editval['pstudi_luogo']=$line['pstudi_luogo'];
  
  $editval['pstudi_style'] = $line['pstudi_style'];
  
  //insegna
  $query = "SELECT insegna.iddocente as iddocente, docente.nickname as nickname 
            FROM insegna INNER JOIN docente ON insegna.iddocente=docente.id AND docente.trashed<>1 
            WHERE idpstudi=".$selobj;
  $result = mysql_query($query) or die("Error_1.2");
  while ($line = mysql_fetch_assoc($result)) {
    $editval['insegna'][$line['iddocente']]['esiste']=1;
    $editval['insegna'][$line['iddocente']]['nickname']=$line['nickname'];
  }
  
  //FESTE
  $query = "SELECT times, txt FROM feste";
  $result = mysql_query($query) or die ("Error_1.3");
  while ($line = mysql_fetch_assoc($result)) {
    $vetfeste[$line['times']]['txt']=$line['txt'];
  }
  //myprint_r($vetfeste);
  
//  myprint_r($editval);
  
  for ($g=1;$g<=$neprefday;$g++) {
    if (!($cmd['prefdelid']==$g && $cmd['prefdeltype']==1)) {
      $chiave = $_REQUEST["wkday_$g"]."-".$_REQUEST["orai_$g"];
      $vetprefday[$chiave]['wkday']=$_REQUEST["wkday_$g"];
      $vetprefday[$chiave]['orai']=$_REQUEST["orai_$g"];
      $vetprefday[$chiave]['oraf']=$_REQUEST["oraf_$g"];
      $vetprefday[$chiave]['iddocente']=$_REQUEST["iddocente_$g"];
      $vetprefday[$chiave]['nickname']=$editval['insegna'][$_REQUEST["iddocente_$g"]]['nickname'];
      $vetprefday[$chiave]['prefday_id']=-1;
      $vetprefday[$chiave]['prefday_type']=1;
    } 
  }
  
  if ($cmd['comando2']==2) { //ins item da form
    $chiave = $_REQUEST['wkday']."-".$_REQUEST['orai'];
    $vetprefday[$chiave]['wkday']=$_REQUEST['wkday'];
    $vetprefday[$chiave]['orai']=$_REQUEST['orai'];
    $vetprefday[$chiave]['oraf']=$_REQUEST['oraf'];
    $vetprefday[$chiave]['iddocente']=$_REQUEST['iddocente'];
    $vetprefday[$chiave]['nickname']=$editval['insegna'][$_REQUEST["iddocente"]]['nickname'];
    $vetprefday[$chiave]['prefday_id']=-1;
    $vetprefday[$chiave]['prefday_type']=1;
    
    $_SESSION['orai'] = $vetprefday[$chiave]['orai'];
    $_SESSION['oraf'] = $vetprefday[$chiave]['oraf'];
  }
  
  $query = "SELECT prefday.id as prefday_id, prefday.idpstudi as prefday_idpstudi, prefday.wkday as wkday, prefday.orai as orai, prefday.oraf as oraf, prefday.iddocente as prefday_iddocente,
            docente.nickname as nickname
            FROM prefday LEFT JOIN docente ON prefday.iddocente=docente.id AND docente.trashed<>1
            WHERE prefday.idpstudi=".$selobj."
            AND prefday.id NOT IN (".substr($cmd['dbdeleted'],0,-2).")
            ORDER BY prefday.wkday, prefday.orai";
  
//  print $query;          
  
  $result = mysql_query($query) or die ("Error_3.3");
  while ($line = mysql_fetch_assoc($result)) {
    $chiave = $line['wkday']."-".$line['orai'];    
    if (!($cmd['prefdelid']==$line['prefday_id'] && $cmd['prefdeltype']==2)) {
      $vetprefday[$chiave]['wkday'] = $line['wkday'];
      $vetprefday[$chiave]['orai']=$line['orai'];
      $vetprefday[$chiave]['oraf']=$line['oraf'];
      $vetprefday[$chiave]['iddocente']=$line['prefday_iddocente'];
      $vetprefday[$chiave]['nickname']=$line['nickname'];
      $vetprefday[$chiave]['prefday_id']=$line['prefday_id'];
      $vetprefday[$chiave]['prefday_type']=2;
    } else {
      unset ($vetprefday[$chiave]);
      $cmd['dbdeleted'] .= $line['prefday_id'].", ";
    }
  }

  //myprint_r($vetprefday);
  
  print "<BR><H1>"._CLASSIINSEGNAMENTO_."</H1>";
  print "<BR><BR>";
  print "<FORM NAME='prefday' id='prefdayform' ACTION='".FILE_CORRENTE."' METHOD='POST'>";   
  print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='50%'>";
  print "<tr><td class='headermod' colspan='5'><div class='header-text'>"._TITOLOPREFDAY_."</div></td></tr>";
  print "<tr><td class='headermod' colspan='5'><div class='header-msg'>"._MSGTITOLOPREFDAY_."</div></td></tr>";
  $neprefday=0;
  
  ksort($vetprefday);
  
  $preset['orai'] = $_REQUEST['orai'];
  $preset['oraf'] = $_REQUEST['oraf'];
  
  foreach ($vetprefday as $keypref =>$curpref) {
    $neprefday++;
    if ($cur_rowstyle=="form2_lista_riga_pari") {
      $cur_rowstyle="form2_lista_riga_dispari";
      $stile = "background-color:#EDEDED;";//EDEDED   //F0FFEF
    } else {
      $cur_rowstyle="form2_lista_riga_pari";
      $stile = "background-color:#F7F7F7;";//F7F7F7   //FEFFEF
    }
    if ($key==$selobj) {
      $cur_rowstyle="form2_lista_riga_select";
      $stile = "background-color:#FFAA00;";//F7F7F7   //FEFFEF
    }
    print "<TR STYLE='$stile'>";
    print "<TD CLASS=lista_col_form2><INPUT TYPE='hidden' NAME='wkday_".$neprefday."' VALUE='".$curpref['wkday']."'><INPUT TYPE='hidden' NAME='orai_$neprefday' VALUE='".$curpref['orai']."'><INPUT TYPE='hidden' NAME='oraf_".$neprefday."' VALUE='".$curpref['oraf']."'><INPUT TYPE='hidden' NAME='iddocente_$neprefday' VALUE='".$curpref['iddocente']."'>".letter_weekday($curpref['wkday'])."</TD>";
    print "<TD CLASS=lista_col_form2>".date("H:i",$curpref['orai'])."</TD>";
    print "<TD CLASS=lista_col_form2>".date("H:i",$curpref['oraf'])."</TD>";
    print "<TD CLASS=lista_col_form2>".$curpref['nickname']."</TD>";
    
    switch ($curpref['prefday_type']) {
      case 1: //runtime
        $idtodel = $neprefday;break;
      case 2: //db
        $idtodel = $curpref['prefday_id'];break;
    }
    
    print "<TD CLASS=lista_col_form2><IMG CLASS=manina SRC='../img/canc.png' BORDER=0 onClick=prefdaydelclick(".$idtodel.",".$curpref['prefday_type'].");></TD>";
    print "</TR>";
  }
  
  //RIGA PER AGGIUNGI                    
  $cur_rowstyle="form2_lista_riga_select";
  $stile = "background-color:#FFAA00;";
  print "<TR STYLE='background-color:#FFAA00;'>";
  print "<TR CLASS=lista_col_form2>";
  print "<TD CLASS=lista_col_form2><SELECT SIZE=1 NAME='wkday'>";
    for ($i=0;$i<7;$i++) {
      if ($tt_noday[$i]!=1) { 
        print "<OPTION VALUE='$i'>".letter_weekday($i)."</OPTION>";
      }
    }
  print "</SELECT></TD>";
  print "<TD CLASS=lista_col_form2><SELECT SIZE=1 NAME='orai' onChange=impostaorafine();>";
    for ($i=$tt_orai;$i<=$tt_oraf;$i+=$tt_step) {
      print "<OPTION VALUE='$i' ".($i == $preset['orai']? " SELECTED ":"").">".date("H:i",$i)."</OPTION>";
    }
  print "</SELECT></TD>";
  print "<TD CLASS=lista_col_form2><SELECT SIZE=1 NAME='oraf'>";
    for ($i=$tt_orai;$i<=$tt_oraf;$i+=$tt_step) {
      print "<OPTION VALUE='$i' ".($i == $preset['oraf']? " SELECTED ":"").">".date("H:i",$i)."</OPTION>";
    }
  print "</SELECT></TD>";
  print "<TD CLASS=lista_col_form2>";
    if (count($pstudi[$selobj]['insegna']>0)) {
      print "<SELECT SIZE=1 NAME='iddocente'>";
      foreach ($editval['insegna'] as $keydocente=>$curdocente) {
        print "<OPTION VALUE='$keydocente'>".$curdocente['nickname']."</OPTION>";
      }
      print "</SELECT>";
    } else {
      print _LBLNODOCENTEASS_."<INPUT TYPE='hidden' NAME='iddocente' VALUE='NULL'>";
    }
  print "</TD>";
  
  print "<TD CLASS=lista_col_form2><IMG SRC='../img/piu.png' BORDER=0 onClick=prefdayclick();></TD>";
  print "</TR>";
  
  print "</TABLE><BR><BR>";
  
  //DATA INIZIO
  print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='50%'>";
  print "<tr><td class='headermod' colspan='5'><div class='header-text'>"._TITOLOPREFDAYDATA_."</div></td></tr>";
  print "<tr><td class='headermod' colspan='5'><div class='header-msg'>"._MSGTITOLOPREFDAYDATA_."</div></td></tr>";

  
  //data
  print "<TR CLASS=lista_riga><TD CLASS='form_lbl'>"._DATAINIZIO_."</TD><TD CLASS=lista_col_form2 COLSPAN=4><INPUT TYPE='text' NAME='dataini' VALUE='".$editval['dataini']."'>&nbsp;<IMG SRC='../img/calendario.png' ALIGN=MIDDLE  BORDER=0 ALT='"._BRNCALENDARIO_."' TITLE='"._BRNCALENDARIO_."' onclick=\"displayCalendar(document.prefday.dataini,'dd/mm/yyyy',this)\">&nbsp;<IMG SRC='../img/update16.png' ALIGN=MIDDLE BORDER=0 onClick=dtiniclick();></TD></TR>";
  print "<TR CLASS=lista_riga><TD  CLASS='form_lbl'>"._DATAFINE_."</TD><TD CLASS=lista_col_form2 COLSPAN=4><INPUT TYPE='text' NAME='datafine' VALUE='".$editval['datafine']."'></TD></TR>";
  
  print "</TABLE><BR><BR>";
  
  
  
  
  //lezioni caricate
  $query = "SELECT appuntamento.id as id, appuntamento.dtini as dtini, appuntamento.dtfine as dtfine, 
            appuntamento.tipo as tipo, appuntamento.note as note
            FROM appuntamento
            WHERE appuntamento.trashed<>1 AND appuntamento.fdel=0 AND appuntamento.idpstudi=".$selobj;
  $result = mysql_query ($query) or die ("Error 0809 01");
  
  $adesso = time();
  $oredone=0;
  $lesdone=0;
  $les_delid="-1, ";
  $les = array();
  
  while ($line=mysql_fetch_assoc($result)) {
    if ($line['dtini']>$adesso) {
      $chiave = $line['id'];
      $les['todo'][$chiave]['dtini'] = $line['dtini'];
      $les['todo'][$chiave]['dtfine'] = $line['dtfine'];
      $les['todo'][$chiave]['tipo'] = $line['tipo'];
      $les['todo'][$chiave]['note'] = $line['note'];
      
      $les['todo']['str'] .= "<TR><TD STYLE='font-weight:bolder;color:#FF0000;text-decoration: line-through;'>".date("d/m/Y",$line['dtini'])." ".date("H:i",$line['dtini'])." - ".date("H:i",$line['dtfine'])."</TD></TR>";
      $les_delid .= $chiave.", ";
    } else {
      $stiletiga="";
      
      $chiave = $line['id'];
      $les['done'][$chiave]['dtini'] = $line['dtini'];
      $les['done'][$chiave]['dtfine'] = $line['dtfine'];
      $les['done'][$chiave]['tipo'] = $line['tipo'];
      $les['done'][$chiave]['note'] = $line['note'];
      
      $les['done']['str'] .= "<TR><TD>".date("d/m/Y",$line['dtini'])." ".date("H:i",$line['dtini'])." - ".date("H:i",$line['dtfine'])."</TD></TR>";
      
      $oredone = ($line['dtfine']-$line['dtini']) + $oredone;
      $lesdone ++;
    }
  }
  
  $les_delid = substr($les_delid,0,-2);
  
  if ($lesdone>0) {
    print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='50%'>";
    print "<tr><td class='headermod' colspan='5'><div class='header-text'>"._TITOLOSTATUSLEZIONI_."</div></td></tr>";
    print "<TR><TD>"._LBLLEZIONIDONE_."</TD><TD>"._LBLORELEZIONEDONE_."</TD><TD ROWSPAN=2><INPUT TYPE='button' NAME='btnshwles' VALUE='"._LBLLESSHWDET_."' onClick=shwhid_lesdett()></TD></TR>";
    print "<TR><TD CLASS='lista_col_form2 col_dati'>".$lesdone."</TD><TD CLASS='lista_col_form2 col_dati'>".($oredone/3600)."</TD></TR>";
    print "</TABLE><BR><BR>";
    
    //div dettaglio lezioni
    print "<DIV NAME='les_dett' ID='les_dett' STYLE='display:none;'>";
    print "<TABLE  class='inputform' cellpadding='2'  ALIGN=CENTER STYLE='width:40%;'>";
    print "<tr><td class='headermod' colspan='5'><div class='header-text'>"._TITOLODETTLEZIONI_."</div></td></tr>";
    print $les['done']['str'];
    print $les['todo']['str'];
    print "</TABLE><BR><BR></DIV>";
  }
  
  $dt_dataini = eT_dt2times($editval['dataini']);
  $totore = $editval['pstudi_durata'] * 3600;
  
  if ($_SESSION['mgdebug']==1) {
    print "<HR>";
    print "ORE TOTALI PSTUDI:[".$totore."]";
    print "<HR>";
  }
  
  /* ########################################################
                         C A L C O L O  
  ########################################################*/

  
  if (count($vetprefday)>0 && $editval['dataini']!="") {
    
    $wkdini = date("w",$dt_dataini);
    /*
    print "data inserita:".date("d/m/Y",$dt_dataini)."<BR>";
    print "wkd inserito:$wkdini<BR>";
    */
    
    //trova la partenza
    reset($vetprefday);
    $fine=false;
    $trov=false;
    while ($fine==false) {
      $cur = current($vetprefday);
      //print "[$wkdini]-".$cur['wkday']."<BR>";
      if ($wkdini==$cur['wkday']) {
        //print "TROVATO<BR>";
        $fine=true;
        $trov=true;
      } else {
        //print "FALSE<BR>";
        if (next($vetprefday) === false) {
          //print "RESET<BR>";
          reset($vetprefday);
          $fine=true;
        }
      }      
    }
    
    //if ($trov==false) {
    //no trov, sposto data inizio
    $fine=false;
    $salto=0;
    while (!$fine) {
      $cur = current($vetprefday);
      //print "curwk:".$cur['wkday']."<BR>salto:".$salto."WKDINI:$wkdini<BR>";
      if (($cur['wkday']+$salto)>=$wkdini) {
        //print "VERO";
        $fine=true;
      } else {
        //print "FALSO";
        if (next($vetprefday) === false) {
          //print "RESET";
          reset($vetprefday);
          $salto=7;
        }
      }
    }
          
    $app_wk = $cur['wkday'];
    if ($app_wk < $wkdini) {$app_wk += 7;}
    //$curdt = $dt_dataini + (($app_wk-$wkdini) * 86400);
    //corretto calcolo data inizio
    $curdt =  strtotime("+ ".($app_wk-$wkdini)." day",$dt_dataini);

    $fine=false;
    unset($datalezione);
    $ne=0;
      
      //Normalizzazione totore con ore già svolte
      $totore -= $oredone; 
      if ($_SESSION['mgdebug']==1) {
        print "<HR>";
        print "ORE TOTALI PSTUDI TOLTE QUELLE GIA' SVOLTE:[".$totore."]";
        print "<HR>";
      }       
      while (!$fine) {
        if (isset($vetfeste[$curdt])) {
          $ne++;
          $datalezione[$ne]['tipo']=2; //tipo: 1 dataevento   2 data festa
          $datalezione[$ne]['dataini'] = $curdt;
          if ($_SESSION['mgdebug']==1) {
            print "<HR>Ramo true<HR>";
          }
        } else {
          if ($_SESSION['mgdebug']==1) {
            print "<HR>Ramo false<HR>";
          }
          
          $cur = current($vetprefday);
          
          $appdur = $cur['oraf']-$cur['orai'];
          if ($_SESSION['mgdebug']==1) {
            print "<HR>";
            print "DURATA CALCOLATA DELLA SINGOLA LEZIONE:[".$appdur."]";
            print "<HR>";
          }
          
          if ($totore<$appdur) {
            $appdur=$totore;
            if ($_SESSION['mgdebug']==1) {
              print "ORE rESIDUE INFERIORI ALLE RICHIESTE";
            }
          }
                  
          $ne++;
          $datalezione[$ne]['tipo']=1;
          $datalezione[$ne]['dataini']=($curdt+$cur['orai']+3600);
          $datalezione[$ne]['datafine']=$datalezione[$ne]['dataini']+$appdur;
          $datalezione[$ne]['wkday'] = date("w",$datalezione[$ne]['dataini']);
          $datalezione[$ne]['iddocente'] = $cur['iddocente'];
          $datalezione[$ne]['nickname'] = $cur['nickname'];
          
          if ($_SESSION['mgdebug']==1) {
            print "ELEMENTO APPENA INSERITO";
            myprint_r($datalezione[$ne]);
            print "<HR>NUMERO LEZIONI: [".$ne."]<HR>";
          }
          
          
          if ($_SESSION['mgdebug']==1) {
            print "<HR>TOTORE PRE MOD:[".$totore."]<HR>";
            print "<HR>TOTORE DURATA DA SOTTRARRE:[".$appdur."]<HR>";
          }
          
          $totore-=$appdur;
          if ($_SESSION['mgdebug']==1) {
            print "<HR>NUOVO TOTORE :[".$totore."]<HR>";
          }
          
          if ($totore==0){$fine=true;}
        }
        
        if (next($vetprefday) === false) {
            reset($vetprefday);
        }
        $cur = current($vetprefday);
        $app_wk = $cur['wkday'];
        $cur_wk = date("w",$curdt);
        
        
        /*print "data pre salto:".date("d/m/Y H:i:s",$curdt)."<BR>";
        print "app_wk:".$app_wk."<BR>";
        print "curwk:".$cur_wk."<BR>";*/
        
        //if ($app_wk<=$cur_wk || $cur_wk==0) {$app_wk += 7;}
        if ($app_wk<=$cur_wk) {$app_wk += 7;}
        
        //print "app_wk:".$app_wk."<BR>";
        
        $curdt = eT_dt2times(date("d/m/Y",$curdt));
        $appdtmic = $curdt;
        
        //PROBLEMA IN ALCUNI CASI SI PERDE UN ORA E SBALLA IL CALCOLO
        //SOSTITUTITO CON STRTOTIME
        //$curdt += (($app_wk-$cur_wk) * 86400);
        $curdt = strtotime("+ ".($app_wk-$cur_wk)." day",$curdt);
                  
        /*
        print "<B>data post salto:".date("d/m/Y H:i:s",$curdt)."<BR>";
        
        print "data con strtime".date("d/m/Y H:i:s",strtotime("+ ".($app_wk-$cur_wk)." day",$appdtmic))."<BR></B>";
        */
        

      }
      
      /*print "<PRE>";
      print_r($datalezione);
      print "</PRE>";*/
      
      print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='60%'>";
      print "<tr><td class='headermod' colspan='5'><div class='header-text'>"._DATECALCOLATE_."</div></td></tr>";
      //$g=1;
      $g = 1; 
      //il numero della lezione viene normalizzata solo sulla label. i campi evt restano sequenziali da 1
      foreach ($datalezione as $keylezione =>$curlezione) {
        if ($cur_rowstyle=="form2_lista_riga_pari") {
          $cur_rowstyle="form2_lista_riga_dispari";
          $stile = "background-color:#EDEDED;";//EDEDED   //F0FFEF
        } else {
          $cur_rowstyle="form2_lista_riga_pari";
          $stile = "background-color:#F7F7F7;";//F7F7F7   //FEFFEF
        }
        if ($key==$selobj) {
          $stile = "background-color:#FFAA00;";//F7F7F7   //FEFFEF
        }
        
        //SELECT RIGHE
        switch ($curlezione['tipo']) {
          case 2:
            $cur_rowstyle = "form2_lista_riga_evidente1";
            break;
        }
        
        print "<TR CLASS='$cur_rowstyle'>";
        
        switch ($curlezione['tipo']) {
          case 1:
            print "<TD CLASS=lista_col_form2>";
            print "<INPUT TYPE='hidden' NAME='evtdataini_$g' VALUE='".$curlezione['dataini']."'>
              <INPUT TYPE='hidden' NAME='evtdatafine_$g' VALUE='".$curlezione['datafine']."'>
              <INPUT TYPE='hidden' NAME='evtiddocente_$g' VALUE='".$curlezione['iddocente']."'>
              ".letter_weekday($curlezione['wkday'])." ".date("d/m/Y",$curlezione['dataini'])."</TD>";
            print "<TD CLASS=lista_col_form2>"._LEZIONENN_.($g + $lesdone)."</TD>";
            print "<TD CLASS=lista_col_form2>".date("H:i",$curlezione['dataini'])."</TD>";
            print "<TD CLASS=lista_col_form2>".date("H:i",$curlezione['datafine'])."</TD>";
            print "<TD CLASS=lista_col_form2>".$curlezione['nickname']."</TD>";
            //print "<TD CLASS=lista_col_form2>".$curlezione['prefday_id']."</TD>";
            $ultimogg = $curlezione['dataini'];
            $g++;
            break;
          case 2:
            print "<TD CLASS=lista_col_form2>".letter_weekday(date("w",$curlezione['dataini']))." ".date("d/m/Y",$curlezione['dataini'])."</TD>";
            print "<TD COLSPAN=4 CLASS=lista_col_form2>"._LBLFESTA_." ".$vetfeste[$curlezione['dataini']]['txt']."</TD>";
            break;
        }
 
        print "</TR>";
        
      }
    print "<TR CLASS=lista_riga><TD CLASS=lista_col COlSPAN=5>
    <INPUT TYPE='button' NAME='btnsubmit' VALUE='"._BTNCONFEVTESALVA_."' onClick=btnsubclick();></TD></TR>";
    print "</TABLE>";
    print "<SCRIPT>document.prefday.datafine.value=\"".date("d/m/Y",$ultimogg)."\";</SCRIPT>";
  
    //}
    
  }
  
  print "<BR><INPUT TYPE='hidden' NAME='cmd' VALUE='".FASEPREFDAY."'>
    <INPUT TYPE='hidden' NAME='evtne' VALUE='".($g-1)."'>
    <INPUT TYPE='hidden' NAME='compenso' VALUE='".$editval['pstudi_compenso']."'>
    <INPUT TYPE='hidden' NAME='idpstudi' VALUE='".$selobj."'>
    <INPUT TYPE='hidden' NAME='codicepagina' VALUE='".codiceform(FILE_CORRENTE)."_EVT'>
    <INPUT TYPE='hidden' NAME='prefne' VALUE='".$neprefday."'>
    <INPUT TYPE='hidden' NAME='selobj' VALUE='".$selobj."'>
    <INPUT TYPE='hidden' NAME='cmd2' VALUE=''>
    <INPUT TYPE='hidden' NAME='prefdeltype' VALUE=''>
    <INPUT TYPE='hidden' NAME='prefdelid' VALUE=''>
    <INPUT TYPE='hidden' NAME='lesdone' VALUE='$lesdone'>
    <INPUT TYPE='hidden' NAME='les_delid' VALUE='$les_delid'>
    <INPUT TYPE='hidden' NAME='dbdeleted' VALUE='".$cmd['dbdeleted']."'>";
  print "</FORM>";
  
  print "<FORM ACTION='". FILE_DBQUERY ."' METHOD='POST' NAME='modificaclasse'><INPUT TYPE='hidden' NAME='cmd' VALUE='". $cmd['comando'] ."'><INPUT TYPE='hidden' NAME='selobj' VALUE='". $selobj ."'><INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'>";
  print "<INPUT TYPE='hidden' NAME='idstudente' VALUE=''><INPUT TYPE='hidden' NAME='idpstudi' VALUE=''><INPUT TYPE='hidden' NAME='datai' VALUE=''><INPUT TYPE='hidden' NAME='dataf' VALUE=''></FORM>";
  
  include ("finepagina.php");
?>
<SCRIPT>
function insegna() {
  document.caricamento.cmd.value=<?=FASEASSDOCENTE?>;
  document.caricamento.submit();
}

function modclasse(idstudente, idpstudi, datai, op) {
  document.modificaclasse.idstudente.value=idstudente;
  document.modificaclasse.idpstudi.value=idpstudi;
  if (op==1) {
    document.modificaclasse.cmd.value=<?=FASEADDSTUDENTE?>;
  } else {
    document.modificaclasse.datai.value=datai;
    risp = confirm("<?=_MSGDELSTUDCLASSE_?>");
    if (risp) {
      //ok
      dt = prompt("<?=_MSGDATADELSTUDCLASSE_?>");
      document.modificaclasse.dataf.value=dt;
      document.modificaclasse.cmd.value=<?=FASEFINESTUDENTE?>;
    } else {
      document.modificaclasse.cmd.value=<?=FASEDELSTUDENTE?>;
    }
  }
  document.modificaclasse.submit();
}

function prefdayclick() {
  document.prefday.cmd2.value="2";
  document.prefday.submit();
}
function prefdaydelclick(id,tipo) {
  document.prefday.prefdeltype.value=tipo;
  document.prefday.prefdelid.value=id;
  document.prefday.submit();
}
function dtiniclick() {
  document.prefday.submit();
}

function btnsubclick() {
  document.prefday.cmd.value=<?=FASEPREFDAYINS?>;
  document.prefday.action="conflitto.php";
  document.getElementById("prefdayform").target="inserimento";
  document.prefday.submit();
  self.close();
}

function impostaorafine() {
  dlezione = <?=$editval['pstudi_dlezione']/30;?>;
  newpos = document.prefday.orai.options.selectedIndex + dlezione;
  if (newpos > document.prefday.oraf.options.length-1) {
    newpos = document.prefday.oraf.options.length-1;
  }
  document.prefday.oraf.options.selectedIndex=newpos;
}

function settacolore(valore) {
  var elem = document.getElementById("divcolore");

  elem.innerHTML = "<INPUT TYPE='text' NAME='mostracolore' VALUE='ABCDE' STYLE='" + valore + "'>"; 
}

function shwhid_lesdett() {
  var elem = document.getElementById("les_dett");
  
  if (elem.style.display=="block") {
    elem.style.display="none";
  } else {
    elem.style.display="block";
  }
}
 function chiamapopup(fase,id) {
    var figlio;
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
    
    figlio = window.open("frm_pstudio_addpopup.php?selobj="+id+"&cmd="+fase,"pstudio",params);
    if (figlio.opener==null) {
      figlio.opener=self;
      figlio.frames["saveopener"].opener=self;
    }
  }
</SCRIPT>

<SCRIPT>
  impostaorafine();
</SCRIPT>
</body>
</html>
