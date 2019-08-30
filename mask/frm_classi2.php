<?php
  //PSTUDIO GESTISCE:
  //pstudi in quanto istanza di un corso
  
  $pagever = "1.5";
  $pagemod = "30/08/2010 18.03.03";
  require_once("form_cfg.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <link rel="stylesheet" type="text/css" href="../css/main.css" />
  <link rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></link>
</head>
<body>
<script type="text/javascript" src="../jscolor/jscolor.js"></script>
<script type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<?php
  include ("iniziopagina.php");

  //CARICAMENTO VALORI PER EDIT
  define("FILE_CORRENTE", "frm_pstudio.php");

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  $selobj = $_REQUEST['selobj'];
  $cmd['form_limit'] = $_REQUEST['form_limit'];
  $cmd['form_sort'] = $_REQUEST['form_sort'];
  
  if ($cmd['form_limit']=="") {$cmd['form_limit']=0;}
  
  $cmd['form_rowcount'] = $setup['form_rowcount'];
  
  //myprint_r($_SESSION);
  
  //PRELOAD INSEGNA
  $pstudi=array();
  
  $query = "SELECT docente.nickname as nickname, docente.id as docente_id, docente.attivo as docente_attivo,
            insegna.idpstudi as idpstudi
            FROM docente INNER JOIN insegna ON docente.id = insegna.iddocente
            INNER JOIN pstudi ON insegna.idpstudi=pstudi.id AND pstudi.trashed <>1 AND pstudi.attivo<>-1
            WHERE docente.trashed<>1";
  $result = mysql_query($query) or die ("Error_2.1");
  while ($line = mysql_fetch_assoc($result)) {
    $pstudi[$line['idpstudi']]['insegna'][$line['docente_id']]['nick']=$line['nickname'];
    $pstudi[$line['idpstudi']]['insegna'][$line['docente_id']]['attivo']=$line['docente_attivo'];
    $pstudi[$line['idpstudi']]['insegna'][$line['docente_id']]['id']=$line['docente_id'];
  }


  $query_select = "SELECT pstudi.id as pstudi_id, pstudi.idcorso as pstudi_idcorso, pstudi.descr as pstudi_descr, pstudi.datai as pstudi_datai,
            pstudi.dataf as pstudi_dataf, pstudi.compenso as pstudi_compenso, pstudi.durata as pstudi_durata, pstudi.dlezione as pstudi_dlezione,
            pstudi.codsense as pstudi_codsense, pstudi.codotec as pstudi_codotec, pstudi.attivita as pstudi_attivita, pstudi.luogo as pstudi_luogo,
            corso.descr as corso_descr, corso.codlivello as corso_codlivello, pstudi.attivo as pstudi_attivo, pstudi.style as pstudi_style";
            
  $query = " FROM pstudi INNER JOIN corso ON pstudi.idcorso=corso.id
            WHERE pstudi.trashed<>1";

  if ($cmd['comando']==FASESEARCH) {
    $query .= " AND pstudi.descr like '%".$_REQUEST['descr']."%' AND pstudi.codsense like '%".$_REQUEST['codsense']."%'";
  }
  
  switch ($cmd['form_sort']) {
    case 1:
    default:
            $query .= " ORDER BY pstudi.descr ";
            break;
  }
  
    
  $result = mysql_query("SELECT count(*) as conto ".$query);
  $line=mysql_fetch_assoc($result);
  $query_tot_row=$line['conto'];
  mysql_free_result($result);
  
  $query = $query_select . $query;
  $query .= " LIMIT ".$cmd['form_limit'].", ".$cmd['form_rowcount'];
  
  $result = mysql_query($query) or die ("Error_1.1");
  
  print "<BR><H1>"._CLASSIINSEGNAMENTO_."</H1>";
  
  if (mysql_num_rows($result)!=0 )  {
    echo "<TABLE CLASS=lista_table ALIGN=CENTER>";
    echo "<TR><TD CLASS=lista_tittab>"._DESCRIZIONE_."</TD><TD CLASS=lista_tittab>"._LIVELLOCORSO_."</TD><TD CLASS=lista_tittab>"._CODICI_."</TD><TD CLASS=lista_tittab>"._PLANNING_."</TD><TD CLASS=lista_tittab>"._LUOGO_."</TD><TD CLASS=lista_tittab>"._STATOATTIVO_."</TD><TD CLASS=lista_tittab width='70'>&nbsp;</TD></TR>";
    while ($line=mysql_fetch_assoc($result)) {
      $styleslect="";
      if ($line['pstudi_id']==$selobj) {$styleslect=STYLE_RIGAORANGE;}
      print "<TR CLASS=lista_riga>";
      print "<TD CLASS=lista_col $styleslect>[". $line['pstudi_id'] ."] ".$line['pstudi_descr']."</TD>";
      print "<TD CLASS=lista_col $styleslect>[". $line['corso_codlivello'] ."] ".$line['corso_descr']."</TD>";
      print "<TD CLASS=lista_col $styleslect>"._LBLSENSE_. $line['pstudi_codsense'] ."<BR>"._LBLOTEC_.$line['pstudi_codotec']."</TD>";
      print "<TD CLASS=lista_col $styleslect>";
        print _DATAINIZIO_ . ($line['pstudi_datai']=="" ? _LBLNODATA_:date("d/m/Y",$line['pstudi_datai']))."<BR>";
        print _DATAFINE_. ($line['pstudi_dataf']=="" ? _LBLNODATA_:date("d/m/Y",$line['pstudi_dataf'])) ."<BR>";
        print _DURATATOTALE_.$line['pstudi_durata']."<BR>";
        print _OREPIANIFICATE_. "00-00";
      print "</TD>";
      print "<TD CLASS=lista_col $styleslect>". $line['pstudi_luogo']."</TD>";
      print "<TD CLASS=lista_col ALIGN=CENTER $styleslect>".($line['pstudi_attivo']==1 ? "<A HREF='".FILE_DBQUERY."?cmd=". FASEDENY ."&codicepagina=". codiceform(FILE_CORRENTE) ."&selobj=". $line['pstudi_id']."'><IMG SRC='../img/ledgreen.png' BORDER=0 ALIGN=MIDDLE></A>" : "<A HREF='".FILE_DBQUERY."?cmd=". FASEALLOW ."&codicepagina=". codiceform(FILE_CORRENTE) ."&selobj=". $line['pstudi_id']."'><IMG SRC='../img/ledred.png' BORDER=0 ALIGN=MIDDLE></A>")."</TD>";
      //COMANDI
      print "<TD CLASS=lista_col $styleslect><TABLE width=100%><TR>
      <TD ALIGN=CENTER><A HREF='". FILE_CORRENTE ."?cmd=". FASEMOD ."&selobj=". $line['pstudi_id'] ."#maskera'><IMG SRC='../img/mod.png' border=0 ALT='"._LBLMATITA_."' TITLE='"._LBLMATITA_."'></A></TD>
      <TD ALIGN=CENTER><A HREF='#' onClick=urldelete(\"". FILE_DBQUERY ."?cmd=". FASEDEL ."&codicepagina=". codiceform(FILE_CORRENTE) ."&selobj=". $line['pstudi_id'] ."\")><IMG SRC='../img/canc.png' border=0 ALT='"._LBLCANCELLA_."'></A></TD>
      <TD ALIGN=CENTER><A HREF='". FILE_CORRENTE ."?cmd=". FASECLASSE ."&selobj=". $line['pstudi_id'] ."#maskera'><IMG SRC='../img/registri.png' border=0 ALT='"._REGISTRODICLASSE_."' TITLE='"._REGISTRODICLASSE_."'></A></TD>";
			print "</TR><TR>";
			print "<TD ALIGN=CENTER><A HREF='". FILE_CORRENTE ."?cmd=". FASEPREFDAY ."&selobj=". $line['pstudi_id'] ."#maskera'><IMG SRC='../img/calendario_fulmine.png' border=0 ALT='"._LBLPREFDAY_."' TITLE='"._LBLPREFDAY_."'></A></TD>
             <TD><A HREF='/stampe/mpdf/examples/LCI1.php?selobj=".$line['pstudi_id']."' TARGET=_blank><IMG SRC='../img/reportsmall.png' border=0 ALT='"._LBLLCI_."' TITLE='"._LBLLCI_."'></A></TD>
             <TD>&nbsp;</TD>";
      print "</TR></TABLE></TD>";
      print "</TR>";
        
              //CARICAMENTO IN LINEA PER EDIT
      if ($selobj==$line['pstudi_id']) {
        $editval['pstudi_idcorso'] = $line['pstudi_idcorso'];
        $editval['pstudi_descr'] = $line['pstudi_descr'];
        $editval['pstudi_datai'] = date("d/m/Y",$line['pstudi_datai']);
        $editval['pstudi_dataf'] = date("d/m/Y",$line['pstudi_dataf']);
        $editval['pstudi_compenso'] = $line['pstudi_compenso'];
        $editval['pstudi_durata'] = $line['pstudi_durata'];
        $editval['pstudi_dlezione'] = $line['pstudi_dlezione'];
        $editval['pstudi_codsense'] = $line['pstudi_codsense'];
        $editval['pstudi_codotec'] = $line['pstudi_codotec'];
        $editval['pstudi_attivita']=$line['pstudi_attivita'];
        $editval['pstudi_luogo']=$line['pstudi_luogo'];
        
        $editval['pstudi_style'] = eT_getStyleValue($line['pstudi_style'],"background-color:");
      } 
    }
       
    echo "</TABLE><BR>";
        
    $i=1;
    $ne=$setup['form_btnpage'];
    $ne--;
    $fine=0;
    unset($vetlimit);
    $vetlimit=array();
    $vetlimit[0] = 0;
    while ($fine==0) {
      $fine=1;
      $curlimit = $cmd['form_limit'];
      //if ($curlimit>0 && $curlimit<($query_tot_row-1)) {
        $newlimit = ceil($curlimit/$cmd['form_rowcount'])*$cmd['form_rowcount'];
        $vetlimit[$newlimit] = $newlimit;

      //}
      
      $newlimit = $curlimit - ($i * $cmd['form_rowcount']);
      if ($newlimit>0 && $newlimit<($query_tot_row) && $ne>0) {  //tolto -1 in totrow
        $fine=0;
        $vetlimit[$newlimit] = $newlimit;
        $ne--;
      }
      
      $newlimit = $curlimit + ($i * $cmd['form_rowcount']);
      if ($newlimit>0 && $newlimit<($query_tot_row) && $ne>0) {  //tolto -1 in totrow
        $fine=0;
        $vetlimit[$newlimit] = $newlimit;
        $ne--;
      }
      $i++;
    }
    $newlimit = ceil(($query_tot_row-1)/$cmd['form_rowcount'])*$cmd['form_rowcount'];
    if ($newlimit>0 && $newlimit<($query_tot_row) && $ne>0) {  //tolto -1 in totrow
      $vetlimit[$newlimit] = $newlimit;
    }
    ksort($vetlimit);
    $lastlimit = end($vetlimit);
    
    $limitfinale = $cmd['form_limit']+$cmd['form_rowcount'];
    $limitinizio = $cmd['form_limit']+1;
    if ($limitfinale > $query_tot_row) {$limitfinale=$query_tot_row;}
    if ($limitinizio==0) {$limitinizio=1;}
    
    print "<DIV id='pagebar' CLASS=pagebar STYLE='text-align:right;font-size:12px;'>";
    print _SHOW_." "._SHOWFROM_." ".$limitinizio." "._SHOWTO_." ".$limitfinale." "._SHOWOF_." ".$query_tot_row." ";
    foreach ($vetlimit as $keylimit=>$curlimit) {
      if ($keylimit!=$cmd['form_limit']) {
        print "<A HREF='frm_classi2.php?form_limit=$curlimit'>";
      } else {
        print "<SPAN CLASS=selectedpage STYLE='color:#0000FF;font-weight:bolder;'>";
      }
      print "[";
      if ($curlimit==0) {
        print _FIRST_;
      } else {
        if ($curlimit==$lastlimit) {
          print _LAST_;
        } else {
          print ceil($curlimit/$cmd['form_rowcount'])+1;
        }
      }
      print "]";
      if ($keylimit!=$cmd['form_limit']) {
        print "</A> ";
      } else {
        print "</SPAN> ";
      }
    }
    print "</DIV><BR>";
  }
  
  //SEARCH
  print "<DIV id='areasearch'>";
  print "<FORM NAME='ricerca' METHOD='POST' ACTION='".FILE_CORRENTE."'><INPUT TYPE='hidden' NAME='cmd' VALUE='".FASESEARCH."'>";
  print "<TABLE align='center' class='searchform' cellpadding='2' cellspacing='0' width='70%'>";
  print "<TR><TD CLASS='form_lbl'>Descrizione: <INPUT TYPE='text' NAME='descr' VALUE='".($cmd['comando']==FASESEARCH ? $_REQUEST['descr']:"")."'></TD><TD CLASS='form_lbl'>SENSE: <INPUT TYPE='text' NAME='codsense' VALUE='".($cmd['comando']==FASESEARCH ? $_REQUEST['codsense']:"")."'></TD><TD CLASS='form_lbl'><BUTTON>Search</BUTTON></TD></TR>";
  print "</TABLE>";
  print "</FORM>";
  print "</DIV>";
  
  
  //myprint_r($editval);
  
  //$cmd['comando']=FASEPREFDAY;
  if ($cmd['comando']==FASEPREFDAY) {
  
    $cmd['comando2'] = $_REQUEST['cmd2'];
    $neprefday=$_REQUEST['prefne'];
    $editval['dataini'] = $_REQUEST['dataini'];
    if ($editval['dataini']=="") {
      $editval['dataini'] = date("d/m/Y");
    }
    
    for ($g=1;$g<=$neprefday;$g++) {
      $chiave = $_REQUEST["wkday_$g"]."-".$_REQUEST["orai_$g"];
      $vetprefday[$chiave]['wkday']=$_REQUEST["wkday_$g"];
      $vetprefday[$chiave]['orai']=$_REQUEST["orai_$g"];
      $vetprefday[$chiave]['oraf']=$_REQUEST["oraf_$g"];
      $vetprefday[$chiave]['iddocente']=$_REQUEST["iddocente_$g"];
      $vetprefday[$chiave]['nickname']=$_REQUEST["iddocente_$g"];
      $vetprefday[$chiave]['prefday_id']=-1;
    }
    
    if ($cmd['comando2']==2) { //ins item da form
      $chiave = $_REQUEST['wkday']."-".$_REQUEST['orai'];
      $vetprefday[$chiave]['wkday']=$_REQUEST['wkday'];
      $vetprefday[$chiave]['orai']=$_REQUEST['orai'];
      $vetprefday[$chiave]['oraf']=$_REQUEST['oraf'];
      $vetprefday[$chiave]['iddocente']=$_REQUEST['iddocente'];
      $vetprefday[$chiave]['nickname']=$_REQUEST['iddocente'];
      $vetprefday[$chiave]['prefday_id']=-1;
      
      $_SESSION['orai'] = $vetprefday[$chiave]['orai'];
      $_SESSION['oraf'] = $vetprefday[$chiave]['oraf'];
    }
    $query = "SELECT prefday.id as prefday_id, prefday.idpstudi as prefday_idpstudi, prefday.wkday as wkday, prefday.orai as orai, prefday.oraf as oraf, prefday.iddocente as prefday_iddocente,
              docente.nickname as nickname
              FROM prefday LEFT JOIN docente ON prefday.iddocente=docente.id AND docente.trashed<>1
              WHERE prefday.idpstudi=".$selobj."
              ORDER BY prefday.wkday, prefday.orai";
    $result = mysql_query($query) or die ("Error_3.3");     
    while ($line = mysql_fetch_assoc($result)) {
      $chiave = $line['wkday']."-".$line['orai'];
      $vetprefday[$chiave]['wkday'] = $line['wkday'];
      $vetprefday[$chiave]['orai']=$line['orai'];
      $vetprefday[$chiave]['oraf']=$line['oraf'];
      $vetprefday[$chiave]['iddocente']=$line['prefday_iddocente'];
      $vetprefday[$chiave]['nickname']=$line['nickname'];
      $vetprefday[$chiave]['prefday_id']=$line['prefday_id'];
    }   
    print "<BR><BR>";
    print "<FORM NAME='prefday' ACTION='".FILE_CORRENTE."' METHOD='POST'>";   
    print "<TABLE CLASS=lista_table ALIGN=CENTER STYLE='width:40%;'>";
    print "<tr><td class='headermod' colspan='5'><div class='header-text'>"._TITOLOPREFDAY_."</div></td></tr>";
    $neprefday=0;
    
    ksort($vetprefday);
    
    $preset['orai'] = $_REQUEST['orai'];
    $preset['oraf'] = $_REQUEST['oraf'];
    
    foreach ($vetprefday as $keypref =>$curpref) {
      $neprefday++;
      
      print "<TR CLASS=lista_riga>";
      print "<TD CLASS=lista_col><INPUT TYPE='hidden' NAME='wkday_".$neprefday."' VALUE='".$curpref['wkday']."'><INPUT TYPE='hidden' NAME='orai_$neprefday' VALUE='".$curpref['orai']."'><INPUT TYPE='hidden' NAME='oraf_".$neprefday."' VALUE='".$curpref['oraf']."'><INPUT TYPE='hidden' NAME='iddocente_$neprefday' VALUE='".$curpref['iddocente']."'>".letter_weekday($curpref['wkday'])."</TD>";
      print "<TD CLASS=lista_col>".date("H:i",$curpref['orai'])."</TD>";
      print "<TD CLASS=lista_col>".date("H:i",$curpref['oraf'])."</TD>";
      print "<TD CLASS=lista_col>".$curpref['nickname']."</TD>";
      print "<TD CLASS=lista_col>".$curpref['prefday_id']."</TD>";
      print "</TR>";
    }
    
    print "<TR CLASS=lista_riga STYLE='background-color:#FFD37A;'>";
    print "<TD CLASS=lista_col STYLE='background-color:#FFD37A;'><SELECT SIZE=1 NAME='wkday'>";
      for ($i=1;$i<7;$i++) {
        print "<OPTION VALUE='$i'>".letter_weekday($i)."</OPTION>";
      }
    print "</SELECT></TD>";
    print "<TD CLASS=lista_col STYLE='background-color:#FFD37A;'><SELECT SIZE=1 NAME='orai' onChange=impostaorafine();>";
      for ($i=$tt_orai;$i<=$tt_oraf;$i+=$tt_step) {
        print "<OPTION VALUE='$i' ".($i == $preset['orai']? " SELECTED ":"").">".date("H:i",$i)."</OPTION>";
      }
    print "</SELECT></TD>";
    print "<TD CLASS=lista_col STYLE='background-color:#FFD37A;'><SELECT SIZE=1 NAME='oraf'>";
      for ($i=$tt_orai;$i<=$tt_oraf;$i+=$tt_step) {
        print "<OPTION VALUE='$i' ".($i == $preset['oraf']? " SELECTED ":"").">".date("H:i",$i)."</OPTION>";
      }
    print "</SELECT></TD>";
    print "<TD CLASS=lista_col STYLE='background-color:#FFD37A;'>";
      if (count($pstudi[$selobj]['insegna']>0)) {
        print "<SELECT SIZE=1 NAME='iddocente'>";
        foreach ($pstudi[$selobj]['insegna'] as $keydocente=>$curdocente) {
          print "<OPTION VALUE='$keydocente'>".$curdocente['nick']."</OPTION>";
        }
        print "</SELECT>";
      } else {
        print _LBLNODOCENTEASS_."<INPUT TYPE='hidden' NAME='iddocente' VALUE='NULL'>";
      }
    print "</TD>";
    
    print "<TD CLASS=lista_col STYLE='background-color:#FFD37A;'><IMG SRC='../img/piu.png' BORDER=0 onClick=prefdayclick();></TD>";
    print "</TR>";
    
    print "</TABLE><BR><BR>";
    print "<TABLE CLASS=lista_table ALIGN=CENTER STYLE='width:40%;'>";
    print "<tr><td class='headermod' colspan='5'><div class='header-text'>"._TITOLOPREFDAY_."</div></td></tr>";
    
    //data
    print "<TR CLASS=lista_riga><TD CLASS=lista_col COLSPAN=5>"._DATAINIZIO_."<INPUT TYPE='text' NAME='dataini' VALUE='".$editval['dataini']."'>&nbsp;<input type=\"button\" value=\""._BRNCALENDARIO_."\" onclick=\"displayCalendar(document.prefday.dataini,'dd/mm/yyyy',this)\">&nbsp;<IMG SRC='../img/update16.png' BORDER=0 onClick=dtiniclick();></TD></TR>";
    print "<TR CLASS=lista_riga><TD CLASS=lista_col COLSPAN=5>"._DATAFINE_."<INPUT TYPE='text' NAME='datafine' VALUE='".$editval['datafine']."'></TD></TR>";
    
    print "</TABLE><BR><BR>";
    
    /*print "<PRE>";
    print_r($vetprefday);
    print "</PRE>";*/
    $dt_dataini = eT_dt2times($editval['dataini']);
    $totore = $editval['pstudi_durata'] * 3600;
    //calcolo
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
        if ($app_wk<$wkdini) {$app_wk += 7;}
        $curdt = $dt_dataini + (($app_wk-$wkdini) * 86400);
        
        
        $fine=false;
        unset($datalezione);
        $ne=0;
        while (!$fine) {
          $cur = current($vetprefday);
          
          $appdur = $cur['oraf']-$cur['orai'];
          if ($totore<$appdur) {$appdur=$totore;}
                  
          $ne++;
          $datalezione[$ne]['dataini']=($curdt+$cur['orai']+3600);
          $datalezione[$ne]['datafine']=$datalezione[$ne]['dataini']+$appdur;
          $datalezione[$ne]['wkday'] = date("w",$datalezione[$ne]['dataini']);
          $datalezione[$ne]['iddocente'] = $cur['iddocente'];
          $datalezione[$ne]['nickname'] = $cur['nickname'];
          
          $totore-=$appdur;
          if ($totore==0){$fine=true;}
          
          if (next($vetprefday) === false) {
              reset($vetprefday);
          }
          $cur = current($vetprefday);
          $app_wk = $cur['wkday'];
          $cur_wk = date("w",$curdt);
          
          /*print "data pre salto:".date("d/m/Y H:i:s",$curdt)."<BR>";
          print "app_wk:".$app_wk."<BR>";
          print "curwk:".$cur_wk."<BR>";*/
          
          if ($app_wk<=$cur_wk || $cur_wk==0) {$app_wk += 7;}
          
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
        
        print "<TABLE CLASS=lista_table ALIGN=CENTER STYLE='width:40%;'>";
        print "<tr><td class='headermod' colspan='5'><div class='header-text'>"._DATECALCOLATE_."</div></td></tr>";
        $g=1;
        foreach ($datalezione as $keylezione =>$curlezione) {
          print "<TR CLASS=lista_riga>";
          print "<TD CLASS=lista_col>
          <INPUT TYPE='hidden' NAME='evtdataini_$g' VALUE='".$curlezione['dataini']."'>
          <INPUT TYPE='hidden' NAME='evtdatafine_$g' VALUE='".$curlezione['datafine']."'>
          <INPUT TYPE='hidden' NAME='evtiddocente_$g' VALUE='".$curlezione['iddocente']."'>
          "._LEZIONENN_.$g." ".letter_weekday($curlezione['wkday'])." ".date("d/m/Y",$curlezione['dataini'])."</TD>";
          print "<TD CLASS=lista_col>".date("H:i",$curlezione['dataini'])."</TD>";
          print "<TD CLASS=lista_col>".date("H:i",$curlezione['datafine'])."</TD>";
          print "<TD CLASS=lista_col>".$curlezione['nickname']."</TD>";
          print "<TD CLASS=lista_col>".$curlezione['prefday_id']."</TD>";
          print "</TR>";
          $ultimogg = $curlezione['dataini'];
          $g++;
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
      <INPUT TYPE='hidden' NAME='cmd2' VALUE=''>";
    print "</FORM>";
  }
  
  if ($cmd['comando']==FASECLASSE) {
    print "<TABLE CLASS=lista_table ALIGN=CENTER STYLE='width:60%;'>";
    print "<TR CLASS=lista_riga>";
    
    $tabella = "";
    //studenti in classe
    $query = "SELECT studente.id as studente_id, studente.nome as studente_nome, customer.id as customer_id, customer.nome as customer_nome,
              classe.datai as classe_datai
              FROM classe INNER JOIN studente ON classe.idstudente=studente.id
              LEFT JOIN customer ON studente.idcustomer=customer.id
              WHERE studente.trashed<>1 AND (customer.trashed<>1 OR customer.trashed IS NULL) AND classe.dataf=1 AND classe.idpstudi=".$selobj."
              ORDER BY customer.nome, studente.nome";
    $result = mysql_query($query) or die ("Error_3.1");
    $idstud = "-1, ";
    while ($line = mysql_fetch_assoc($result)) {
      $tabella.= "<TR><TD CLASS=lista_col><IMG SRC='../img/prev.png' BORDER=0 onClick=modclasse(".$line['studente_id'].",".$selobj.",".$line['classe_datai'].",2);></TD><TD CLASS=lista_col>".$line['studente_nome']." (".$line['customer_nome'].")</TD></TR>";
      $idstud .= $line['studente_id'].", ";
    }
    
    $idstud = substr($idstud,0,-2);
    //studenti non in classe
    $query = "SELECT studente.id as studente_id, studente.nome as studente_nome, customer.id as customer_id, customer.nome as customer_nome
              FROM studente LEFT JOIN customer ON studente.idcustomer=customer.id
              WHERE studente.trashed<>1 AND (customer.trashed<>1 OR customer.trashed IS NULL) AND studente.id NOT IN (".$idstud.")
              ORDER BY customer.nome, studente.nome";
    $result = mysql_query($query) or die ("Error_3.2");
    
    print "<TD WIDTH=50% STYLE='vertical-align: top;'><TABLE WIDTH=100%>";
    print "<TR><TD COLSPAN=2 CLASS=lista_tittab>"._NOISCRITTI_."</TD></TR>";
    while ($line = mysql_fetch_assoc($result)) {
      print "<TR><TD CLASS=lista_col >".$line['studente_nome']." (".$line['customer_nome'].")</TD><TD CLASS=lista_col ><IMG SRC='../img/next.png' BORDER=0 onClick=modclasse(".$line['studente_id'].",".$selobj.",0,1);></TD></TR>";
    }
    print "</TABLE></TD>";
    print "<TD WIDTH=50% STYLE='vertical-align: top;'><TABLE WIDTH=100%><TR><TD COLSPAN=2 CLASS=lista_tittab>"._BTNISCRITTI_."</TD></TR>".$tabella."</TABLE></TD>";
    print "</TR></TABLE>";
    
  } else {
  
    if ($cmd['comando']!=FASECLASSE && $cmd['comando']!=FASEPREFDAY) {
      if ($cmd['comando']==FASEMOD) {
          $titolofinestra = _MODCLASSEINSEGNAMENTO_; $classtitolo = "headermod";
          //echo "XXX";
      } else {
          $cmd['comando']=FASEINS;
          $titolofinestra = _INSCLASSEINSEGNAMENTO_; $classtitolo = "headerins";
          //echo "<BR> xxcmd ".$cmd;
      }
    }
    
    // Torna all'inserimento
    if ($cmd['comando']!=FASEINS) {
      print "<div align='right' class='backinsert'>";
      print "\t<A HREF='". FILE_CORRENTE ."'><img src='../img/edit_small.gif' align='center' border='0' width='16' height='16'>"._BACKTOINS_."</A>";
      print "</div>";
    }
    print "<BR>";
    
    
    
    if ($cmd['comando']==FASEMOD || $cmd['comando']==FASEINS) {    
      print "<FORM ACTION='". FILE_DBQUERY ."' METHOD='POST' NAME='caricamento'><INPUT TYPE='hidden' NAME='cmd' VALUE='". $cmd['comando'] ."'><INPUT TYPE='hidden' NAME='selobj' VALUE='". $selobj ."'><INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'>";
    	print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='50%'>";
      print "<tr><td class='headermod' colspan='3'><div class='header-text'>$titolofinestra</div></td></tr>";
      
      print "<TR>";
      print "<TD CLASS='form_lbl'>"._DESCRIZIONE_."</TD>";
      print "<TD><INPUT TYPE='text' size='50' NAME='descr' VALUE='". $editval['pstudi_descr'] ."'></TD>";
      print "<TD CLASS=form_help></TD>";
      print "</TR>";
      
      print "<TR>";
      print "<TD CLASS='form_lbl'>"._CODLIVCORSO_."</TD>";
      print "<TD>";
      $query = "SELECT id, descr, codlivello FROM corso WHERE trashed<>1 ORDER BY codlivello";
      $result = mysql_query ($query) or die ("Error_1.2");
      print "<SELECT SIZE=1 NAME='idcorso'>";
      while ($line=mysql_fetch_assoc($result)) {
        print "<OPTION VALUE='".$line['id']."' ".($editval['pstudi_idcorso']==$line['id']? " SELECTED ":"").">".$line['descr']."</OPTION>";
      }
      print "</SELECT></TD>";
      print "<TD CLASS=form_help></TD>";
      print "</TR>";
      
      /*
      print "<TR>";
      print "<TD CLASS='form_lbl'>"._DATAINIZIO_."</TD>";
      print "<TD><INPUT TYPE='text' size='50' NAME='datai' VALUE='". $editval['pstudi_datai'] ."'></TD>";
      print "<TD CLASS=form_help></TD>";
      print "</TR>";
      
      print "<TR>";
      print "<TD CLASS='form_lbl'>"._DATAFINE_."</TD>";
      print "<TD><INPUT TYPE='text' size='50' NAME='dataf' VALUE='". $editval['pstudi_dataf'] ."'></TD>";
      print "<TD CLASS=form_help></TD>";
      print "</TR>";
      */
      
      print "<TR>";
      print "<TD CLASS='form_lbl'>"._DURATATOTALE_."</TD>";
      print "<TD><INPUT TYPE='text' size='20' NAME='durata' VALUE='". $editval['pstudi_durata'] ."'>
        <INPUT TYPE='button' NAME='durata20' VALUE=' 20 ' onClick=document.caricamento.durata.value=20;>&nbsp;
        <INPUT TYPE='button' NAME='durata40' VALUE=' 40 ' onClick=document.caricamento.durata.value=40;>&nbsp;
        <INPUT TYPE='button' NAME='durata60' VALUE=' 60 ' onClick=document.caricamento.durata.value=60;>&nbsp;
        <INPUT TYPE='button' NAME='durata120' VALUE=' 120 ' onClick=document.caricamento.durata.value=120;>&nbsp;
      </TD>";
      print "<TD CLASS=form_help></TD>";
      print "</TR>";
      
      print "<TR>";
      print "<TD CLASS='form_lbl'>"._DURATALEZIONE_."</TD>";
      print "<TD>";
        $appdlezione = mktime(0, 0, 0, 1, 1, 2010);
        print "<SELECT NAME='dlezione' SIZE='1'>";
        for ($n=1;$n<=10;$n++) {
          $curdlezione = $n * 30;
          print "<OPTION VALUE='".$curdlezione."' ".($curdlezione == $editval['pstudi_dlezione']? " SELECTED ":"").">".date("H:i",$appdlezione + ($curdlezione * 60))."</OPTION>";
        }
        print "</SELECT>";
      //<INPUT TYPE='text' size='50' NAME='dlezione' VALUE='". $editval['pstudi_dlezione'] ."'>
      print "</TD>";
      print "<TD CLASS=form_help></TD>";
      print "</TR>";
      
      print "<TR>";
      print "<TD CLASS='form_lbl'>"._COMPENSO_."</TD>";
      print "<TD><INPUT TYPE='text' size='50' NAME='compenso' VALUE='". $editval['pstudi_compenso'] ."'></TD>";
      print "<TD CLASS=form_help></TD>";
      print "</TR>";
      
      print "<TR>";
      print "<TD CLASS='form_lbl'>"._LBLSENSE_."</TD>";
      print "<TD><INPUT TYPE='text' size='50' NAME='codsense' VALUE='". $editval['pstudi_codsense'] ."'></TD>";
      print "<TD CLASS=form_help></TD>";
      print "</TR>";
      
      print "<TR>";
      print "<TD CLASS='form_lbl'>"._LBLOTEC_."</TD>";
      print "<TD><INPUT TYPE='text' size='50' NAME='codotec' VALUE='". $editval['pstudi_codotec'] ."'></TD>";
      print "<TD CLASS=form_help></TD>";
      print "</TR>";
      
      print "<TR>";
      print "<TD CLASS='form_lbl'>"._ATTIVITA_."</TD>";
      print "<TD><TEXTAREA NAME='attivita' COLS='50' ROWS='3'>". $editval['pstudi_attivita'] ."</TEXTAREA></TD>";
      print "<TD CLASS=form_help></TD>";
      print "</TR>";
      
      print "<TR>";
      print "<TD CLASS='form_lbl'>"._LUOGO_."</TD>";
      print "<TD><TEXTAREA NAME='luogo' COLS='50' ROWS='3'>". $editval['pstudi_luogo'] ."</TEXTAREA></TD>";
      print "<TD CLASS=form_help></TD>";
      print "</TR>";
      
      print "<TR>";
      print "<TD CLASS='form_lbl'>"._LBLCOLORE_."</TD>";
      print "<TD><INPUT class=\"color {valueElement:'styleValue'}\" size=\"50\" NAME=\"style\" id=\"style\"><INPUT TYPE=\"hidden\" id=\"styleValue\" name=\"styleValue\" VALUE=\"".($cmd['comando']==FASEMOD ? $editval['pstudi_style'] : "FFFFFF")."\"></TD>";
      print "<TD CLASS=form_help></TD>";
      print "</TR>";
      
      print "<TR>";
      print "<TD CLASS='form_lbl'>"._LBLDOCENTI_."</TD>";
      print "<TD>";
        print "<TABLE>";
        $docass="-1, ";
        if (count($pstudi[$selobj]['insegna'])>0) {
          foreach ($pstudi[$selobj]['insegna'] as $keyinsegna=>$curinsegna) {
            print "<TR><TD>".$curinsegna['nick']."</TD><TD><A HREF='".FILE_DBQUERY."?cmd=".FASEREMDOCENTE."&codicepagina=".codiceform(FILE_CORRENTE)."&idpstudi=$selobj&iddocente=$keyinsegna'><IMG SRC='../img/canc.png' BORDER=0></A></TD></TR>";
            $docass .= "$keyinsegna, ";
          }      
        } else {
          print _LBLNODOCENTEASS_;
        }
        $docass = substr($docass, 0, -2);
        $query = "SELECT id, nickname FROM docente WHERE trashed<>1 AND attivo=1 AND id NOT IN (".$docass.") ORDER BY nickname";
        $result = mysql_query ($query) or die ("Error_1.3");
        print "<TR><TD><SELECT SIZE=1 NAME='iddocente'>";
        if ($cmd['comando']==FASEINS) {print "<OPTION VALUE=''>- - - - -</OPTION>";}
        while ($line=mysql_fetch_assoc($result)) {
          print "<OPTION VALUE='".$line['id']."'>".$line['nickname']."</OPTION>";
        }
        print "</SELECT></TD><TD>".($cmd['comando']==FASEINS ? "": "<IMG SRC='../img/piu.png' BORDER=0 onClick=insegna();>")."</TD>";
        print "</TABLE></TD></TR>";
      
      
      
      print "<TR><TD COLSPAN=3 align='center' height='70'><INPUT TYPE='submit' VALUE='"._BTNSALVA_."'>&nbsp;&nbsp;&nbsp;<INPUT TYPE='reset' VALUE='"._BTNRESET_."'></TD></TR>";
      /*if ($cmd['comando']==FASEINS) {
        print "<TR><TD COLSPAN=3 align='center' height='70'><INPUT TYPE='button' STYLE='width:200px;' VALUE='Ricerca' onClick=fasesearch(document.caricamento);></TD></TR>";
      }*/
      print "</TABLE></FORM>";
    }
  }  
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
function dtiniclick() {
  document.prefday.submit();
}

function btnsubclick() {
  document.prefday.cmd.value=<?=FASEPREFDAYINS?>;
  document.prefday.action="conflitto.php";
  document.prefday.submit();
}

function impostaorafine() {
  dlezione = <?=$editval['pstudi_dlezione']/30;?>;
  newpos = document.prefday.orai.options.selectedIndex + dlezione;
  if (newpos > document.prefday.oraf.options.length-1) {
    newpos = document.prefday.oraf.options.length-1;
  }
  document.prefday.oraf.options.selectedIndex=newpos;
}
</SCRIPT>

</body>
</html>
