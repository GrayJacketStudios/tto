<?php
  //PSTUDIO GESTISCE:
  //pstudi in quanto istanza di un corso
  
  
  /*
  HISTORY
  1.6  Trasformazione in FORM2-popup
  
  
  
  */
  
  $pagever = "1.6";
  $pagemod = "18/11/2010 22.32.11";
  require_once("form_cfg.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <link rel="stylesheet" type="text/css" href="../css/main.css" />
  <link rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></link>
  <STYLE>
    .barraseparazione1 {
      height:1px;
      width:60%;
    }
  </STYLE>
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
  if ($_REQUEST['selobj']!="") {$_SESSION["SELOBJ_".FILE_CORRENTE]=$_REQUEST['selobj'];}
  $selobj = $_SESSION["SELOBJ_".FILE_CORRENTE];
  $cmd['openblock']=$_REQUEST['op'];
  $cmd['fs']=$_REQUEST['fs'];
  $cmd['ne_search']=1;
  
  $cmd['filtra_stato'] = $_REQUEST['filtra_stato'];
  if ($cmd['filtra_stato']=="") {$cmd['filtra_stato']=$_SESSION['_'.FILE_CORRENTE.'.filtra_stato'];}
  if ($cmd['filtra_stato']=="") {$cmd['filtra_stato'] = "1";}
  $_SESSION['_'.FILE_CORRENTE.'.filtra_stato']=$cmd['filtra_stato'];
  
      
  include ("form_filter.php");
  $cmd['form_rowcount'] = $setup['form_rowcount'];
  $cmd['form_rowcount'] = 1000;
  
  //myprint_r($_SESSION);
  
  //PRELOAD INSEGNA
  $pstudi=array();

  $query_select = "SELECT pstudi.id as pstudi_id, pstudi.idcorso as pstudi_idcorso, pstudi.descr as pstudi_descr, pstudi.datai as pstudi_datai,
            pstudi.dataf as pstudi_dataf, pstudi.compenso as pstudi_compenso, pstudi.durata as pstudi_durata, pstudi.dlezione as pstudi_dlezione,
            pstudi.codsense as pstudi_codsense, pstudi.codotec as pstudi_codotec, pstudi.attivita as pstudi_attivita, pstudi.luogo as pstudi_luogo,
            corso.descr as corso_descr, corso.codlivello as corso_codlivello, pstudi.attivo as pstudi_attivo, pstudi.style as pstudi_style,
            pstudi.idclasse as pstudi_idclasse, classi.descr as classi_descr, classi.idcustomer as classi_idcustomer,
            customer.nome as customer_nome, pstudi.trasporto as pstudi_trasporto, customer.ragsoc as customer_ragsoc,
            customer.fantasia as customer_fantasia, customer.impresa as customer_impresa, customer.apellido as customer_apellido";
            
  $query = " FROM pstudi INNER JOIN corso ON pstudi.idcorso=corso.id
              LEFT JOIN classi ON pstudi.idclasse=classi.id AND classi.trashed<>1
              LEFT JOIN customer ON classi.idcustomer=customer.id
              
              WHERE pstudi.trashed<>1";
  
  if ($cmd['filtra_stato']!="-1") {
    $query .= " AND pstudi.attivo=".$cmd['filtra_stato']." ";
  }
                
  //FSINGOLO [fs] select solo uno in caso di ritorno da dbquery. setta filterreset
  if ($cmd['fs']==1) {
    setFlagFormSessionFilter();
    $cmd['form_limit']=0;
    $query .= " AND pstudi.id=".$selobj;
  }

  //if ($cmd['comando']==FASESEARCH) {
  if ($cmd['src1']!="") {
    $query .= " AND (pstudi.descr like '%".$cmd['src1']."%' OR pstudi.codsense like '%".$cmd['src1']."%' OR
                classi.descr like '%".$cmd['src1']."%' OR customer.nome like '%".$cmd['src1']."%' OR
                customer.apellido like '%".$cmd['src1']."%' OR customer.ragsoc like '%".$cmd['src1']."%' OR
                customer.fantasia like '%".$cmd['src1']."%' OR
                pstudi.luogo like '%".$cmd['src1']."%' OR pstudi.attivita like '%".$cmd['src1']."%') ";
  } else {
    resetFormSessionFilter();
  }
  
  switch ($cmd['form_sort']) {
    case 1:
    default:
            $query .= " ORDER BY customer.ragsoc, customer.impresa DESC, customer.nome, customer.apellido ";
            break;
  }

    
  $result = mysql_query("SELECT count(*) as conto ".$query);
  $line=mysql_fetch_assoc($result);
  $query_tot_row=$line['conto'];
  mysql_free_result($result);
  
  $query = $query_select . $query;
  $query .= " LIMIT ".$cmd['form_limit'].", ".$cmd['form_rowcount'];

  $result = mysql_query($query) or die ("Error_1.1");
  
  $str_idpst="-2102, ";
  while ($line=mysql_fetch_assoc($result)) {
    $chiave = $line['pstudi_id'];
    $pstudio[$chiave]['pstudi_id'] = $line['pstudi_id'];
    $pstudio[$chiave]['pstudi_idcorso'] = $line['pstudi_idcorso'];
    $pstudio[$chiave]['pstudi_descr'] = $line['pstudi_descr'];
    $pstudio[$chiave]['pstudi_idclasse'] = $line['pstudi_idclasse'];
    $pstudio[$chiave]['classi_descr'] = $line['classi_descr'];
    $pstudio[$chiave]['classi_idcustomer'] = $line['classi_idcustomer'];
    $pstudio[$chiave]['customer_nome'] = $line['customer_nome'];
    $pstudio[$chiave]['customer_ragsoc'] = $line['customer_ragsoc'];
    $pstudio[$chiave]['customer_fantasia'] = $line['customer_fantasia'];
    $pstudio[$chiave]['customer_impresa'] = $line['customer_impresa'];
    $pstudio[$chiave]['customer_apellido'] = $line['customer_apellido'];
    $pstudio[$chiave]['corso_codlivello'] = $line['corso_codlivello'];
    $pstudio[$chiave]['corso_descr'] = $line['corso_descr'];
    $pstudio[$chiave]['pstudi_datai'] = $line['pstudi_datai'];
    $pstudio[$chiave]['pstudi_dataf'] = $line['pstudi_dataf'];
    $pstudio[$chiave]['pstudi_durata'] = $line['pstudi_durata'];
    $pstudio[$chiave]['pstudi_dlezione'] = $line['pstudi_dlezione'];
    $pstudio[$chiave]['pstudi_codsense'] = $line['pstudi_codsense'];
    $pstudio[$chiave]['pstudi_codotec'] = $line['pstudi_codotec'];
    $pstudio[$chiave]['pstudi_luogo'] = $line['pstudi_luogo'];
    $pstudio[$chiave]['pstudi_attivita'] = $line['pstudi_attivita'];
    $pstudio[$chiave]['pstudi_attivo'] = $line['pstudi_attivo'];
    $pstudio[$chiave]['pstudi_compenso'] = $line['pstudi_compenso'];
    $pstudio[$chiave]['pstudi_trasporto'] = $line['pstudi_trasporto'];
    $pstudio[$chiave]['pstudi_style'] = $line['pstudi_style'];
    $pstudio[$chiave]['alunniclasse'] = 0;
     
    $str_idpst .= $chiave.", ";
  }
  $str_idpst = substr($str_idpst,0,-2);
  
  
  $query = "SELECT docente.nickname as nickname, docente.id as docente_id, docente.attivo as docente_attivo,
            insegna.idpstudi as idpstudi
            FROM docente INNER JOIN insegna ON docente.id = insegna.iddocente
            INNER JOIN pstudi ON insegna.idpstudi=pstudi.id AND pstudi.trashed <>1 AND pstudi.attivo<>-1
            WHERE docente.trashed<>1 AND pstudi.id IN (".$str_idpst.")";
  $result = mysql_query($query) or die ("Error_2.1 $query");
  while ($line = mysql_fetch_assoc($result)) {
    $pstudi[$line['idpstudi']]['insegna'][$line['docente_id']]['nick']=$line['nickname'];
    $pstudi[$line['idpstudi']]['insegna'][$line['docente_id']]['attivo']=$line['docente_attivo'];
    $pstudi[$line['idpstudi']]['insegna'][$line['docente_id']]['id']=$line['docente_id'];
  }
  
  
  
  $query = "SELECT id, idpstudi, dtini, dtfine
            FROM appuntamento
            WHERE appuntamento.trashed<>1 AND fdel=0
            AND idpstudi IN (".$str_idpst.")
            ORDER BY idpstudi, dtfine";
  $result = mysql_query($query) or ("Error_1.2");
  $adesso = time();
  while ($line = mysql_fetch_assoc($result)) {
    $chiave = $line['idpstudi'];
    $pstudio[$chiave]['totlezioni']++;
    $pstudio[$chiave]['totdurata']+=$line['dtfine']-$line['dtini'];
    $pstudio[$chiave]['ultimalez']=$line['dtfine'];

    if ($line['dtfine']<=$adesso) {
      //passata
      $pstudio[$chiave]['lezionifatte']++;
      $pstudio[$chiave]['orefatte']+=$line['dtfine']-$line['dtini'];
    }
  }
  
  $query = "SELECT pstudi.id as pstudi_id, pstudi.idclasse as pstudi_idclasse, count(enroll.idstudente) as conto
            FROM pstudi INNER JOIN enroll ON pstudi.idclasse=enroll.idclasse
            WHERE (dtfine=0 OR dtfine is NULL) AND pstudi.trashed<>1 AND pstudi.id IN (".$str_idpst.")";
  
  if ($cmd['filtra_stato']!="-1") {
    $query .= " AND pstudi.attivo=".$cmd['filtra_stato']." ";
  }
  
  $query .= " GROUP BY pstudi.id, pstudi.idclasse";
  $result = mysql_query($query) or die("Error_1.4 $query");
  while ($line = mysql_fetch_assoc($result)) {
    $chiave = $line['pstudi_id'];
    $pstudio[$chiave]['alunniclasse']=$line['conto'];
  }
      
  print "<BR><H1>"._CLASSIINSEGNAMENTO_."</H1>";
  
  print "<DIV STYLE='text-align:right;'>";
  print "<FORM NAME='fstato' METHOD='POST' ACTION='".FILE_CORRENTE."'><INPUT TYPE='hidden' NAME='cmd' VALUE='".FASESEARCH."'>";
  print _LBLFILTRA_.": <SELECT NAME='filtra_stato' SIZE=1 onChange=document.fstato.submit();><OPTION VALUE='-1'>"._LBLOPTTUTTI_."</OPTION>";
  
  $query = "SELECT valore, label FROM stati WHERE idgruppo=9 ORDER BY ordine";
  $result = mysql_query($query) or die("Error_qs1");
  while ($line=mysql_fetch_assoc($result)) {
    print "<OPTION VALUE='".$line['valore']."' ".($cmd['filtra_stato']==$line['valore']?" SELECTED ":"").">".convlang($line['label'])."</OPTION>";
  }
  print "</SELECT></FORM>";
  
  print "</DIV>";
  
  print "<TABLE CLASS=support_table>";
  print "<TR>";
    //SEARCH
    print "<TD STYLE='text-align:right;'><DIV id='areasearch'>";
    print "<FORM NAME='ricerca' METHOD='POST' ACTION='".FILE_CORRENTE."'><INPUT TYPE='hidden' NAME='cmd' VALUE='".FASESEARCH."'>";
    print "<TABLE align='center' class='searchform' cellpadding='2' cellspacing='0' width='40%'>";
    print "<TR><TD CLASS='form_lbl'><INPUT TYPE='text' NAME='src1' VALUE='".$cmd['src1']."'><INPUT TYPE='hidden' NAME='daform' VALUE='1'></TD><TD CLASS='form_lbl'><BUTTON>Search</BUTTON></TD></TR>";
    print "</TABLE>";
    print "</FORM>";
    print "</DIV></TD>";
    
    if ($_SESSION[FILE_CORRENTE.'FILTERED']==1) {
      print "<TD ALIGN=RIGHT STYLE='text-align:right;width:160px;'>";
      print "<div align='right' class='resetfilter'>";
      print "\t<A HREF='". FILE_CORRENTE ."?rstflt=1'><img src='../img/edit_small.gif' align='center' border='0' width='16' height='16'>"._RESETFILTER_."</A>";
      print "</div></TD>";
    } else {
      print "<TD>&nbsp;</TD>";
    }
    
    print "<TD COLSPAN=2 STYLE='text-align:right;width:160px;'>";  
      print "<IMG CLASS='manina' BORDER=0 SRC='../img/add_user.png' ALT='"._TIPADDUSERCONTATTO_."' TITLE='"._TIPADDUSERCONTATTO_."' onClick='chiamapopup(2,-1);'></TD>";
    print "</TD>";
  print "</TR>";
  print "</TABLE>";
  
  
  
  if (count($pstudio)!=0 )  {
    echo "<TABLE CLASS=lista_table ALIGN=CENTER>";
    print "<TR><TD CLASS=lista_tittab>#</TD><TD CLASS=lista_tittab>"._CLIENTE_."</TD><TD CLASS=lista_tittab>"._CLASSE_."</TD><TD CLASS=lista_tittab>"._LIVELLOCORSO_."</TD><TD CLASS=lista_tittab WIDTH=200>"._PLANNING_."</TD><TD CLASS=lista_tittab>"._STATOATTIVO_."</TD><TD CLASS=lista_tittab width='70'>&nbsp;</TD></TR>";
    //echo "<TR><TD CLASS=lista_tittab>"._DESCRIZIONE_."</TD><TD CLASS=lista_tittab>"._LIVELLOCORSO_."</TD><TD CLASS=lista_tittab>"._CODICI_."</TD><TD CLASS=lista_tittab>"._PLANNING_."</TD><TD CLASS=lista_tittab>"._LUOGO_."</TD><TD CLASS=lista_tittab>"._STATOATTIVO_."</TD><TD CLASS=lista_tittab width='70'>&nbsp;</TD></TR>";
    foreach ($pstudio as $key=>$cur) {
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
      print "<TD CLASS=lista_col_form2>[". $cur['pstudi_id'] ."] ".$cur['pstudi_descr']."</TD>";
      print "<TD CLASS=lista_col_form2 STYLE='width:250px;'>";
        if ($cur['customer_impresa']==1) {
          //impresa
          print $cur['customer_ragsoc']." (".$cur['customer_fantasia'].")";
        } else {
          //indiv
          print $cur['customer_nome']." ".$cur['customer_apellido']." (".$cur['customer_fantasia'].")";
        }
      print "</TD>";
      
      print "<TD CLASS=lista_col_form2 STYLE='width:250px;'>[". $cur['pstudi_idclasse'] ."] ";
        print "<BR>".$cur['classi_descr'];
      print "</TD>";
      print "<TD CLASS=lista_col_form2>[". $cur['corso_codlivello'] ."] ".$cur['corso_descr']."</TD>";
      //print "<TD CLASS=lista_col $styleslect>"._LBLSENSE_. $line['pstudi_codsense'] ."<BR>"._LBLOTEC_.$line['pstudi_codotec']."</TD>";
      print "<TD CLASS=lista_col_form2>";
        print _DATAINIZIO_ . ($cur['pstudi_datai']=="" ? _LBLNODATA_:date("d/m/Y",$cur['pstudi_datai']))."<BR>";
        print _DATAFINE_. ($cur['pstudi_dataf']=="" ? _LBLNODATA_:date("d/m/Y",$cur['pstudi_dataf'])) ."<BR>";
        print _DATAULTIMALEZIONE_. ($cur['ultimalez']=="" ? _LBLNODATA_:date("d/m/Y",$cur['ultimalez'])) ."<BR>";
        print "<BR>";
        print _DURATATOTALE_.($cur['totdurata']/3600)."<BR>";
        print _TOTALELEZIONI_. $cur['totlezioni'] ."<BR>";
        print "<BR>";
        print _ORESVOLTE_.($cur['orefatte']/3600)."<BR>";
        print _LEZIONISVOLTE_.$cur['lezionifatte']."<BR>";
        
        $percdone = ($cur['orefatte']/3600) / $cur['pstudi_durata'];
        $percdone = ($percdone + 0.001)*100;
        $percdone = ceil($percdone);
//        $percdone=1;
        define ("PROGBARYELLOW","60");
        define ("PROGBARRED","80");
        
        if ($percdone>=PROGBARRED) {
          $styleprogbar = "#FF0000";
        } else {
            if ($percdone>=PROGBARYELLOW) {
              $styleprogbar = "#FFF700";
          } else {
              $styleprogbar = "#3AC100";
          }
        }
        
        print "<BR><TABLE WIDTH=100% CELLPADDING=0 CELLSPACING=0 STYLE='padding: 0px 0px 0px 0px;	margin: 0px 0px 0px 0px;'>
                <TR>
                  <TD STYLE='width:$percdone%;height:15px;background-color:$styleprogbar;border:1px solid #000000;'></TD>
                  <TD STYLE='height:15px;border-top:1px solid #000000;border-bottom:1px solid #000000;border-right:1px solid #000000;'></TD>
                </TR>
              </TABLE>";
      print "</TD>";
      //print "<TD CLASS=lista_col $styleslect>". $line['pstudi_luogo']."</TD>";
      print "<TD CLASS=lista_col_form2 ALIGN=CENTER>".($cur['pstudi_attivo']==1 ? "<A HREF='".FILE_DBQUERY."?cmd=". FASEDENY ."&codicepagina=". codiceform(FILE_CORRENTE) ."&selobj=". $cur['pstudi_id']."'><IMG SRC='../img/ledgreen.png' BORDER=0 ALIGN=MIDDLE></A>" : "<A HREF='".FILE_DBQUERY."?cmd=". FASEALLOW ."&codicepagina=". codiceform(FILE_CORRENTE) ."&selobj=". $cur['pstudi_id']."'><IMG SRC='../img/ledred.png' BORDER=0 ALIGN=MIDDLE></A>")."<BR><BR>";
      // gruppo o individuale
      if ($cur['alunniclasse']==1) {
        //individuale
        print "<IMG SRC='../img/pstudio_individuale.png'>";
      } else {
        if ($cur['alunniclasse']>1) {
          //gruppo
        print "<IMG SRC='../img/pstudio_gruppo.png'>";
        }  
      }
      print "</TD>";
      //COMANDI
      //."&form_limit=".$cmd['form_limit']."&form_sort=".$cmd['form_sort']."&src1=".$cmd['src1']."&src2=".$cmd['src1']
      print "<TD CLASS=lista_col_form2>
      <TABLE width=100%>
      <TR>                                      
        <TD ALIGN=CENTER><IMG CLASS='manina' SRC='../img/mod.png' border=0 ALT='"._LBLMATITA_."' TITLE='"._LBLMATITA_."' onClick=chiamapopup(". FASEMOD .",". $cur['pstudi_id'] .")></TD>
        <TD ALIGN=CENTER><A HREF='#' onClick=urldelete(\"". FILE_DBQUERY ."?cmd=". FASEDEL ."&codicepagina=". codiceform(FILE_CORRENTE) ."&selobj=". $cur['pstudi_id'] ."\")><IMG SRC='../img/canc.png' border=0 ALT='"._LBLCANCELLA_."'></A></TD>
      </TR>";
      //print "<TR><TD COLSPAN=2><HR CLASS='barraseparazione1'></TD></TR>";
      //pianificazione
      print "<TR>";
        print "<TD ALIGN=CENTER><IMG CLASS='manina' SRC='../img/calendario_fulmine.png' border=0 ALT='"._LBLPREFDAY_."' TITLE='"._LBLPREFDAY_."' onClick=chiamaevtpopup(". FASEPREFDAY .",". $cur['pstudi_id'] .")></TD>
               <TD ALIGN=CENTER>&nbsp;</TD>";
      print "</TR>";
      //print "<TR><TD COLSPAN=2><HR CLASS='barraseparazione1'></TD></TR>";
      //report
      //libro di classe LCI
      unset($lci);
      if ($cur['alunniclasse']==0) {
        //nessun alunno iscritto - anomalia
        $lci['msg']=_MSGDISABLED_;
        $lci['icona'] = "lci_disabled.png";
        $lci['icona2'] = "allerta_giallo_16.png";
        $lci['msg2']=_MSGANOMALIA_ALUNNI_;
        $lci['link']="";
      } else {
        if ($cur['alunniclasse']==1) {
          //individuale
          $lci['msg'] = _MSGINDIVIDUALE_;
          $lci['icona'] = "individuale.png";
          $lci['link']="";
        } else {
          //classe
          $lci['msg'] = _MSGGRUPPO_;
          $lci['icona'] = "gruppo.png";
          $lci['link']=""; //<A HREF='/stampe/mpdf/examples/LCI1.php?selobj=".$cur['pstudi_id']."' TARGET=_blank>
        }
      }
      print "<TR>";
        print "<TD ALIGN=CENTER>".($lci['link']=="" ? "" : "<A HREF='/stampe/".$lci['link']."' TARGET=_blank>")."
                <IMG SRC='../img/".$lci['icona']."' border=0 ALT='".$lci['msg']."' TITLE='".$lci['msg']."'></A>
                ".($lci['icona2']=="" ? "" : "<IMG SRC='../img/".$lci['icona2']."' border=0 ALT='".$lci['msg2']."' TITLE='".$lci['msg2']."'>")."
              </TD>";
        print "<TD ALIGN=CENTER>&nbsp;</TD>";
			/*print "</TR><TR>";
			print "<TD ALIGN=CENTER><IMG CLASS='manina' SRC='../img/calendario_fulmine.png' border=0 ALT='"._LBLPREFDAY_."' TITLE='"._LBLPREFDAY_."' onClick=chiamaevtpopup(". FASEPREFDAY .",". $cur['pstudi_id'] .")></TD>
             <TD>&nbsp;</TD>
             <TD>&nbsp;</TD>";*/
      print "</TR>
             </TABLE></TD>";
      print "</TR>";
        
      //CARICAMENTO IN LINEA PER EDIT
      /*
######## ########  #### ######## ##     ##    ###    ##       
##       ##     ##  ##     ##    ##     ##   ## ##   ##       
##       ##     ##  ##     ##    ##     ##  ##   ##  ##       
######   ##     ##  ##     ##    ##     ## ##     ## ##       
##       ##     ##  ##     ##     ##   ##  ######### ##       
##       ##     ##  ##     ##      ## ##   ##     ## ##       
######## ########  ####    ##       ###    ##     ## ######## 
      */
      if ($selobj==$cur['pstudi_id']) {
        $editval['pstudi_idcorso'] = $cur['pstudi_idcorso'];
        $editval['pstudi_idclasse'] = $cur['pstudi_idclasse'];
        $editval['pstudi_descr'] = $cur['pstudi_descr'];
        $editval['pstudi_datai'] = date("d/m/Y",$cur['pstudi_datai']);
        $editval['pstudi_dataf'] = date("d/m/Y",$cur['pstudi_dataf']);
        $editval['pstudi_compenso'] = $cur['pstudi_compenso'];
        $editval['pstudi_trasporto'] = $cur['pstudi_trasporto'];
        $editval['pstudi_durata'] = $cur['pstudi_durata'];
        $editval['pstudi_dlezione'] = $cur['pstudi_dlezione'];
        $editval['pstudi_codsense'] = $cur['pstudi_codsense'];
        $editval['pstudi_codotec'] = $cur['pstudi_codotec'];
        $editval['pstudi_attivita']=$cur['pstudi_attivita'];
        $editval['pstudi_luogo']=$cur['pstudi_luogo'];
        
        $editval['pstudi_style'] = $cur['pstudi_style'];
      } 
    }
       
    echo "</TABLE><BR>";

/*        
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
        print "<A HREF='".FILE_CORRENTE."?form_limit=$curlimit'>";
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
*/

/*  
  //SEARCH
  print "<DIV id='areasearch'>";
  print "<FORM NAME='ricerca' METHOD='POST' ACTION='".FILE_CORRENTE."'><INPUT TYPE='hidden' NAME='cmd' VALUE='".FASESEARCH."'>";
  print "<TABLE align='center' class='searchform' cellpadding='2' cellspacing='0' width='70%'>";
  //print "<TR><TD CLASS='form_lbl'>Descrizione: <INPUT TYPE='text' NAME='src1' VALUE='".($cmd['comando']==FASESEARCH ? $cmd['src1']:"")."'></TD><TD CLASS='form_lbl'>SENSE: <INPUT TYPE='text' NAME='src2' VALUE='".($cmd['comando']==FASESEARCH ? $cmd['src2']:"")."'></TD><TD CLASS='form_lbl'><BUTTON>Search</BUTTON></TD></TR>";
  print "<TR><TD CLASS='form_lbl'>Descrizione: <INPUT TYPE='text' NAME='src1' VALUE='". $cmd['src1'] ."'></TD><TD CLASS='form_lbl'>SENCE: <INPUT TYPE='text' NAME='src2' VALUE='". $cmd['src2'] ."'></TD><TD CLASS='form_lbl'><BUTTON>Search</BUTTON></TD></TR>";
  print "</TABLE>";
  print "</FORM>";
  print "</DIV>";
  
  if ($_SESSION[FILE_CORRENTE.'FILTERED']==1) {
    print "<div align='right' class='resetfilter'>";
    print "\t<A HREF='". FILE_CORRENTE ."?rstflt=1'><img src='../img/edit_small.gif' align='center' border='0' width='16' height='16'>"._RESETFILTER_."</A>";
    print "</div>";
  }
*/
  //myprint_r($editval);
  
  //$cmd['comando']=FASEPREFDAY;
/*
          ########  ########  ######## ######## ########     ###    ##    ## 
          ##     ## ##     ## ##       ##       ##     ##   ## ##    ##  ##  
          ##     ## ##     ## ##       ##       ##     ##  ##   ##    ####   
          ########  ########  ######   ######   ##     ## ##     ##    ##    
          ##        ##   ##   ##       ##       ##     ## #########    ##    
          ##        ##    ##  ##       ##       ##     ## ##     ##    ##    
          ##        ##     ## ######## ##       ########  ##     ##    ##    

PREFDAY_TYPE
1 valore aggiunto in runtime e non inserito in db. cancellazione con scarico dell'elemento in base all'ne di post dal form
2 valore caricato da db. cancellazione su load db con id

*/
/*
  if ($cmd['comando']==FASEPREFDAY) {
    $cmd['comando2'] = $_REQUEST['cmd2'];
    $cmd['prefdeltype'] = $_REQUEST['prefdeltype'];
    $cmd['prefdelid'] = $_REQUEST['prefdelid'];
    $neprefday=$_REQUEST['prefne'];
    $editval['dataini'] = $_REQUEST['dataini'];
    $cmd['dbdeleted'] = "-1, ".$_REQUEST['dbdeleted'];
    if ($editval['dataini']=="") {
      $editval['dataini'] = date("d/m/Y");
    }
    
    //myprint_r($cmd);
    
    for ($g=1;$g<=$neprefday;$g++) {
      if (!($cmd['prefdelid']==$g && $cmd['prefdeltype']==1)) {
        $chiave = $_REQUEST["wkday_$g"]."-".$_REQUEST["orai_$g"];
        $vetprefday[$chiave]['wkday']=$_REQUEST["wkday_$g"];
        $vetprefday[$chiave]['orai']=$_REQUEST["orai_$g"];
        $vetprefday[$chiave]['oraf']=$_REQUEST["oraf_$g"];
        $vetprefday[$chiave]['iddocente']=$_REQUEST["iddocente_$g"];
        $vetprefday[$chiave]['nickname']=$pstudi[$selobj]['insegna'][$_REQUEST["iddocente_$g"]]['nick'];
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
      $vetprefday[$chiave]['nickname']=$pstudi[$selobj]['insegna'][$_REQUEST["iddocente"]]['nick'];
      $vetprefday[$chiave]['prefday_id']=-1;
      $vetprefday[$chiave]['prefday_type']=1;
      
      $_SESSION['orai'] = $vetprefday[$chiave]['orai'];
      $_SESSION['oraf'] = $vetprefday[$chiave]['oraf'];
    }
    
    //myprint_r($vetprefday);
    $query = "SELECT prefday.id as prefday_id, prefday.idpstudi as prefday_idpstudi, prefday.wkday as wkday, prefday.orai as orai, prefday.oraf as oraf, prefday.iddocente as prefday_iddocente,
              docente.nickname as nickname
              FROM prefday LEFT JOIN docente ON prefday.iddocente=docente.id AND docente.trashed<>1
              WHERE prefday.idpstudi=".$selobj."
              AND prefday.id NOT IN (".substr($cmd['dbdeleted'],0,-2).")
              ORDER BY prefday.wkday, prefday.orai";
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
      
      switch ($curpref['prefday_type']) {
        case 1: //runtime
          $idtodel = $neprefday;break;
        case 2: //db
          $idtodel = $curpref['prefday_id'];break;
      }
      
      print "<TD CLASS=lista_col><IMG CLASS=manina SRC='../img/canc.png' BORDER=0 onClick=prefdaydelclick(".$idtodel.",".$curpref['prefday_type'].");></TD>";
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
      print "<TABLE  class='inputform' cellpadding='2'  ALIGN=CENTER STYLE='width:40%;'>";
      print "<tr><td class='headermod' colspan='5'><div class='header-text'>"._TITOLOSTATUSLEZIONI_."</div></td></tr>";
      print "<TR CLASS=lista_riga><TD CLASS=lista_col>"._LBLLEZIONIDONE_."</TD><TD CLASS=lista_col>"._LBLORELEZIONEDONE_."</TD><TD CLASS=lista_col></TD></TR>";
      print "<TR CLASS=lista_riga><TD CLASS=lista_col>".$lesdone."</TD><TD CLASS=lista_col>".($oredone/3600)."</TD><TD CLASS=lista_col STYLE='text-align:center;'><INPUT TYPE='button' NAME='btnshwles' VALUE='"._LBLLESSHWDET_."' onClick=shwhid_lesdett()></TD></TR>";
      print "</TABLE><BR><BR>";
      
      //div dettaglio lezioni
      print "<DIV NAME='les_dett' ID='les_dett' STYLE='display:none;'>";
      print "<TABLE  class='inputform' cellpadding='2'  ALIGN=CENTER STYLE='width:40%;'>";
      print "<tr><td class='headermod' colspan='5'><div class='header-text'>"._TITOLODETTLEZIONI_."</div></td></tr>";
      print $les['done']['str'];
      print $les['todo']['str'];
      print "</TABLE><BR><BR></DIV>";
    }
*/        
    /*print "<PRE>";
    print_r($vetprefday);
    print "</PRE>";*/
/*    $dt_dataini = eT_dt2times($editval['dataini']);
    $totore = $editval['pstudi_durata'] * 3600;
    //calcolo
    if (count($vetprefday)>0 && $editval['dataini']!="") {
      
      $wkdini = date("w",$dt_dataini);
      /*
      print "data inserita:".date("d/m/Y",$dt_dataini)."<BR>";
      print "wkd inserito:$wkdini<BR>";
      */
      
      //trova la partenza
/*      reset($vetprefday);
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
        
        //Normalizzazione totore con ore già svolte
        $totore -= $oredone;        
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
          
/*          if ($app_wk<=$cur_wk || $cur_wk==0) {$app_wk += 7;}
          
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
/*        
        print "<TABLE CLASS=lista_table ALIGN=CENTER STYLE='width:40%;'>";
        print "<tr><td class='headermod' colspan='5'><div class='header-text'>"._DATECALCOLATE_."</div></td></tr>";
        //$g=1;
        $g = 1; 
        //il numero della lezione viene normalizzata solo sulla label. i campi evt restano sequenziali da 1
        foreach ($datalezione as $keylezione =>$curlezione) {
          print "<TR CLASS=lista_riga>";
          print "<TD CLASS=lista_col>
          <INPUT TYPE='hidden' NAME='evtdataini_$g' VALUE='".$curlezione['dataini']."'>
          <INPUT TYPE='hidden' NAME='evtdatafine_$g' VALUE='".$curlezione['datafine']."'>
          <INPUT TYPE='hidden' NAME='evtiddocente_$g' VALUE='".$curlezione['iddocente']."'>
          "._LEZIONENN_.($g + $lesdone)." ".letter_weekday($curlezione['wkday'])." ".date("d/m/Y",$curlezione['dataini'])."</TD>";
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
      <INPUT TYPE='hidden' NAME='cmd2' VALUE=''>
      <INPUT TYPE='hidden' NAME='prefdeltype' VALUE=''>
      <INPUT TYPE='hidden' NAME='prefdelid' VALUE=''>
      <INPUT TYPE='hidden' NAME='lesdone' VALUE='$lesdone'>
      <INPUT TYPE='hidden' NAME='les_delid' VALUE='$les_delid'>
      <INPUT TYPE='hidden' NAME='dbdeleted' VALUE='".$cmd['dbdeleted']."'>";
    print "</FORM>";
  } else {
  /*
          #### ##    ##  ######        ##     ##  #######  ########  
           ##  ###   ## ##    ##       ###   ### ##     ## ##     ## 
           ##  ####  ## ##             #### #### ##     ## ##     ## 
           ##  ## ## ##  ######        ## ### ## ##     ## ##     ## 
           ##  ##  ####       ##       ##     ## ##     ## ##     ## 
           ##  ##   ### ##    ##       ##     ## ##     ## ##     ## 
          #### ##    ##  ######        ##     ##  #######  ########    
  */
/*
    if ($cmd['comando']==FASEMOD) {
        $titolofinestra = _MODCLASSEINSEGNAMENTO_; $classtitolo = "headermod";
        //echo "XXX";
    } else {
        $cmd['comando']=FASEINS;
        $titolofinestra = _INSCLASSEINSEGNAMENTO_; $classtitolo = "headerins";
        //echo "<BR> xxcmd ".$cmd;
    }
    // Torna all'inserimento
    if ($cmd['comando']!=FASEINS) {
      print "<div align='right' class='backinsert'>";
      print "\t<A HREF='". FILE_CORRENTE ."'><img src='../img/edit_small.gif' align='center' border='0' width='16' height='16'>"._BACKTOINS_."</A>";
      print "</div>";
    }
    print "<BR>";

    //if ($cmd['comando']==FASEMOD || $cmd['comando']==FASEINS) {    
      print "<FORM ACTION='". FILE_DBQUERY ."' METHOD='POST' NAME='caricamento'><INPUT TYPE='hidden' NAME='cmd' VALUE='". $cmd['comando'] ."'><INPUT TYPE='hidden' NAME='selobj' VALUE='". $selobj ."'><INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'>";
    	print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='50%'>";
      print "<tr><td class='headermod' colspan='3'><div class='header-text'>$titolofinestra</div></td></tr>";
      
      /*print "<TR>";
      print "<TD CLASS='form_lbl'>"._DESCRIZIONE_."</TD>";
      print "<TD><INPUT TYPE='text' size='50' NAME='descr' VALUE='". $editval['pstudi_descr'] ."'></TD>";
      print "<TD CLASS=form_help></TD>";
      print "</TR>";*/
      
/*      print "<TR>";
      print "<TD CLASS='form_lbl'>"._CLASSE_."</TD>";
      print "<TD>";
      $query = "SELECT classi.id as classi_id, classi.descr as classi_descr, classi.idcustomer as classi_idcustomer,
                  customer.nome as customer_nome
                FROM classi LEFT JOIN customer ON classi.idcustomer = customer.id AND customer.trashed<>1
                WHERE classi.trashed<>1 AND classi.attivo=1
                ORDER BY customer.nome, classi.descr";
      $result = mysql_query ($query) or die ("Error_1.2");
      print "<SELECT SIZE=1 NAME='idclasse'>";
      while ($line=mysql_fetch_assoc($result)) {
        print "<OPTION VALUE='".$line['classi_id']."' ".($editval['pstudi_idclasse']==$line['classi_id']? " SELECTED ":"").">".($line['customer_nome']=="" ? _MSGNOCUSTOMER_ : $line['customer_nome'])."-".$line['classi_descr']."</OPTION>";
      }
      print "</SELECT></TD>";
      print "<TD CLASS=form_help></TD>";
      print "</TR>";
      
      print "<TR>";
      print "<TD CLASS='form_lbl'>"._CODLIVCORSO_."</TD>";
      print "<TD>";
      $query = "SELECT id, descr, codlivello FROM corso WHERE trashed<>1 ORDER BY ordine";
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
      
/*      print "<TR>";
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
      print "<TD CLASS='form_lbl'>"._LBLSPESETRASPORTO_."</TD>";
      print "<TD><INPUT TYPE='text' size='50' NAME='trasporto' VALUE='". $editval['pstudi_trasporto'] ."'></TD>";
      print "<TD CLASS=form_help></TD>";
      print "</TR>";
      
      print "<TR>";
      print "<TD CLASS='form_lbl'>"._LBLSENSE_."</TD>";
      print "<TD><INPUT TYPE='text' size='50' NAME='codsense' VALUE='". $editval['pstudi_codsense'] ."'></TD>";
      print "<TD CLASS=form_help></TD>";
      print "</TR>";
      
      /*print "<TR>";
      print "<TD CLASS='form_lbl'>"._LBLOTEC_."</TD>";
      print "<TD><INPUT TYPE='text' size='50' NAME='codotec' VALUE='". $editval['pstudi_codotec'] ."'></TD>";
      print "<TD CLASS=form_help></TD>";
      print "</TR>";*/
      
/*      print "<TR>";
      print "<TD CLASS='form_lbl'>"._ATTIVITA_."</TD>";
      print "<TD><TEXTAREA NAME='attivita' COLS='50' ROWS='3'>". $editval['pstudi_attivita'] ."</TEXTAREA></TD>";
      print "<TD CLASS=form_help></TD>";
      print "</TR>";
      
      print "<TR>";
      print "<TD CLASS='form_lbl'>"._LUOGO_."</TD>";
      print "<TD><TEXTAREA NAME='luogo' COLS='50' ROWS='3'>". $editval['pstudi_luogo'] ."</TEXTAREA></TD>";
      print "<TD CLASS=form_help></TD>";
      print "</TR>";
      
      /*print "<TR>";
      print "<TD CLASS='form_lbl'>"._LBLCOLORE_."</TD>";
      print "<TD><INPUT class=\"color {valueElement:'styleValue'}\" size=\"50\" NAME=\"style\" id=\"style\"><INPUT TYPE=\"hidden\" id=\"styleValue\" name=\"styleValue\" VALUE=\"".($cmd['comando']==FASEMOD ? $editval['pstudi_style'] : "FFFFFF")."\"></TD>";
      print "<TD CLASS=form_help></TD>";
      print "</TR>";*/
      
/*      print "<TR>";
      print "<TD CLASS='form_lbl'>"._LBLCOLORE_."</TD>";
      print "<TD>";
      $query = "SELECT id, style FROM colori";
      $result = mysql_query ($query) or die ("Error_1.2");
      print "<SELECT SIZE=1 NAME='styleValue' onChange=settacolore(this.value);>";
      while ($line=mysql_fetch_assoc($result)) {
        $vetcolori[]=$line['style'];
        print "<OPTION VALUE='".$line['style']."' ".($editval['pstudi_style']==$line['style']? " SELECTED ":"")." STYLE='".$line['style']."'>"._TXTOPTCOLORE_."</OPTION>";
        
      }
      print "</SELECT>";
      print "<DIV ID='divcolore'><INPUT TYPE='text' NAME='mostracolore' ID='mostracolore' VALUE='"._TXTOPTCOLORE_."' STYLE='".$editval['pstudi_style']."'></DIV>";
      print "</TD>";
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
//      print "</TABLE></FORM>";
 //   }
//  }  
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
 function chiamaevtpopup(fase,id) {
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
    
    figlio = window.open("frm_pstudio_evtpopup.php?selobj="+id+"&cmd="+fase,"pstudioevt",params);
    if (figlio.opener==null) {
      figlio.opener=self;
      figlio.frames["saveopener"].opener=self;
    }
  }
</SCRIPT>

</body>
</html>

<?
  /*if ($cmd['comando']==FASECLASSE) {
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
    
  }*/ 
?>