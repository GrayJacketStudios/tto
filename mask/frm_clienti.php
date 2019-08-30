<?php
  $pagever = "1.2";
  $pagemod = "24/10/2010 10.34.20";
  require_once("form_cfg.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <link rel="stylesheet" type="text/css" href="../css/main.css" />
</head>
<body>
<?php
  include ("iniziopagina.php");
  
  //CARICAMENTO VALORI PER EDIT
  define("FILE_CORRENTE", "frm_clienti.php");

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

  echo $cmd['filtra_stato'];

  
  

  //filtra_stato2
  $cmd['filtra_stato2'] = $_REQUEST['filtra_stato2'];
  if ($cmd['filtra_stato2']=="") {$cmd['filtra_stato2']=$_SESSION['_'.FILE_CORRENTE.'.filtra_stato2'];}
  if ($cmd['filtra_stato2']=="-1") {
    unset($_SESSION['_'.FILE_CORRENTE.'.filtra_stato2']);
    $cmd['filtra_stato2']="";
  }
  $_SESSION['_'.FILE_CORRENTE.'.filtra_stato2'] = $cmd['filtra_stato2'];

  
  $cmd['csv'] = $_REQUEST['csv'];
  
  include ("form_filter.php");
  
  $cmd['form_rowcount'] = $setup['form_rowcount'];
  $cmd['form_rowcount'] = 10000;
  
  
  if ($cmd['filtra_stato']==2) {   //potenziali
    //presort per potenziali
    $query = "SELECT customer.id as idcustomer, 0 as ultimo
              FROM customer LEFT JOIN customer_log ON customer.id=customer_log.idcustomer
              WHERE customer.stato=".$cmd['filtra_stato']." AND times is null AND customer.trashed<>1 AND (
              customer_log.trashed<>1 OR customer_log.trashed IS NULL
              ) ";
    $query .= " AND (customer.apellido like '%".$cmd['src1']."%' OR customer.nome like '%".$cmd['src1']."%' OR customer.posizione like '%".$cmd['src1']."%'
            OR customer.ragsoc like '%".$cmd['src1']."%' OR customer.rut_ditta like '%".$cmd['src1']."%' OR customer.rut_persona like '%".$cmd['src1']."%' 
            OR customer.giro like '%".$cmd['src1']."%' OR customer.legale_nome like '%".$cmd['src1']."%' OR customer.legale_rut like '%".$cmd['src1']."%' 
            OR customer.addr_pri like '%".$cmd['src1']."%' OR customer.addr_work like '%".$cmd['src1']."%' OR customer.tel_pri like '%".$cmd['src1']."%' 
            OR customer.tel_lavoro like '%".$cmd['src1']."%' OR customer.tel_mobile like '%".$cmd['src1']."%' OR customer.mail1 like '%".$cmd['src1']."%' 
            OR customer.mail2 like '%".$cmd['src1']."%' OR customer.codsence like '%".$cmd['src1']."%' OR customer.rubro like '%".$cmd['src1']."%' )";
    
    if ($cmd['filtra_stato']==2 && $cmd['filtra_stato2']!="" && $cmd['filtra_stato2']!="NULL") {
      $query .= " AND customer.stato2=".$cmd['filtra_stato2'];
    }
    
    if ($cmd['filtra_stato']==2 && $cmd['filtra_stato2']=="NULL") {
      $query .= " AND customer.stato2 is ".$cmd['filtra_stato2'];
    }
    
    $query .= " GROUP by customer.id
              ORDER by ultimo DESC";
    
    $result = mysql_query($query) or die ("Error 10.1 $query");
    while ($line = mysql_fetch_assoc($result)) {
      $chiave = $line['idcustomer'];
      
      $vetcustomer[$chiave]['pre_ultimo1']=$line['ultimo'];
    }          
    $query = "SELECT idcustomer, max(times) as ultimo
              FROM customer_log INNER JOIN customer ON customer_log.idcustomer=customer.id
              WHERE customer_log.trashed<>1 AND customer.trashed<>1 AND customer.stato=".$cmd['filtra_stato']." AND NOT (times is null) ";
    $query .= " AND (customer.apellido like '%".$cmd['src1']."%' OR customer.nome like '%".$cmd['src1']."%' OR customer.posizione like '%".$cmd['src1']."%'
            OR customer.ragsoc like '%".$cmd['src1']."%' OR customer.rut_ditta like '%".$cmd['src1']."%' OR customer.rut_persona like '%".$cmd['src1']."%' 
            OR customer.giro like '%".$cmd['src1']."%' OR customer.legale_nome like '%".$cmd['src1']."%' OR customer.legale_rut like '%".$cmd['src1']."%' 
            OR customer.addr_pri like '%".$cmd['src1']."%' OR customer.addr_work like '%".$cmd['src1']."%' OR customer.tel_pri like '%".$cmd['src1']."%' 
            OR customer.tel_lavoro like '%".$cmd['src1']."%' OR customer.tel_mobile like '%".$cmd['src1']."%' OR customer.mail1 like '%".$cmd['src1']."%' 
            OR customer.mail2 like '%".$cmd['src1']."%' OR customer.codsence like '%".$cmd['src1']."%' OR customer.rubro like '%".$cmd['src1']."%' )";
    
    if ($cmd['filtra_stato']==2 && $cmd['filtra_stato2']!="" && $cmd['filtra_stato2']!="NULL") {
      $query .= " AND customer.stato2=".$cmd['filtra_stato2'];
    }
    
    if ($cmd['filtra_stato']==2 && $cmd['filtra_stato2']=="NULL") {
      $query .= " AND customer.stato2 is ".$cmd['filtra_stato2'];
    }
    
    $query .= " GROUP by customer.id
              ORDER by ultimo DESC";
                                     
    $result = mysql_query($query) or die ("Error 10.1B");
    while ($line = mysql_fetch_assoc($result)) {
      $chiave = $line['idcustomer'];
      
      $vetcustomer[$chiave]['pre_ultimo2']=$line['ultimo'];
    }
  }
  
  
  
  $query_select = "SELECT customer.id as customer_id, customer.nome as customer_nome, customer.apellido as customer_apellido,
        customer.posizione as posizione, customer.ragsoc as ragsoc, customer.rut_ditta as rut_ditta, customer.rut_persona as rut_persona,
        customer.giro as customer_giro, customer.legale_nome as legale_nome, customer.legale_rut as legale_rut, customer.addr_pri as addr_pri,
        customer.addr_work as addr_work, customer.tel_pri as tel_pri, customer.tel_lavoro as tel_lavoro, customer.tel_mobile as tel_mobile,
        customer.mail1 as customer_mail1, customer.mail2 as customer_mail2, customer.mail3 as customer_mail3,customer.fatturaz as fatturaz,
        customer.seg_nome as seg_nome, customer.seg_foto as seg_foto, customer.seg_mail as seg_mail, customer.fantasia as fantasia, customer.web as customer_web,
        customer.skype as customer_skype, customer.fb as customer_fb, customer.codsence as customer_codsence, customer.note as customer_note, customer.impresa as customer_impresa, 
        customer.fatturaz_rut as fatturaz_rut, customer.fatturaz_giro as fatturaz_giro, customer.fatturaz_ragsoc as fatturaz_ragsoc,
        customer.stato as customer_stato, customer.stato2 as customer_stato2, customer.rubro as rubro  ";
        
  $query = "FROM customer
        WHERE customer.trashed<>1";
  if ($cmd['filtra_stato']!="-1") {
    $query .= " AND stato=".$cmd['filtra_stato']." ";
  }
      
  if ($cmd['src1']!="") {
    $query .= " AND (customer.apellido like '%".$cmd['src1']."%' OR customer.nome like '%".$cmd['src1']."%' OR customer.posizione like '%".$cmd['src1']."%'
            OR customer.ragsoc like '%".$cmd['src1']."%' OR customer.rut_ditta like '%".$cmd['src1']."%' OR customer.rut_persona like '%".$cmd['src1']."%' 
            OR customer.giro like '%".$cmd['src1']."%' OR customer.legale_nome like '%".$cmd['src1']."%' OR customer.legale_rut like '%".$cmd['src1']."%' 
            OR customer.addr_pri like '%".$cmd['src1']."%' OR customer.addr_work like '%".$cmd['src1']."%' OR customer.tel_pri like '%".$cmd['src1']."%' 
            OR customer.tel_lavoro like '%".$cmd['src1']."%' OR customer.tel_mobile like '%".$cmd['src1']."%' OR customer.mail1 like '%".$cmd['src1']."%' 
            OR customer.mail2 like '%".$cmd['src1']."%' OR customer.codsence like '%".$cmd['src1']."%' OR customer.rubro like '%".$cmd['src1']."%' )";
  } else {
    resetFormSessionFilter();
  }
  
  if ($cmd['filtra_stato']==2 && $cmd['filtra_stato2']!="" && $cmd['filtra_stato2']!="NULL") {
    $query .= " AND customer.stato2=".$cmd['filtra_stato2'];
  }
  
  if ($cmd['filtra_stato']==2 && $cmd['filtra_stato2']=="NULL") {
    $query .= " AND customer.stato2 is ".$cmd['filtra_stato2'];
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
  
  
  
  
/*
  $query = "SELECT id, nome, rut, indirizzo, num, comune, tel, mobile, fax, email, piva, 
            contabili, contatti
            FROM customer
            WHERE trashed<>1";
  if ($cmd['comando']==FASESEARCH) {
    $query .= " AND nome like '%".$_REQUEST['nome']."%' AND rut like '%".$_REQUEST['rut']."%'";
  }*/
 
  $result = mysql_query($query) or die ("Error_1.1");
  $ids = "-1, ";  
  while ($line = mysql_fetch_assoc($result)) {
    $chiave = $line['customer_id'];
    $vetcustomer[$chiave]['dati']['customer_nome']=$line['customer_nome'];
    $vetcustomer[$chiave]['dati']['customer_apellido']=$line['customer_apellido'];
    $vetcustomer[$chiave]['dati']['posizione']=$line['posizione'];
    $vetcustomer[$chiave]['dati']['ragsoc']=$line['ragsoc'];
    $vetcustomer[$chiave]['dati']['rut_ditta']=$line['rut_ditta'];
    $vetcustomer[$chiave]['dati']['rut_persona']=$line['rut_persona'];
    $vetcustomer[$chiave]['dati']['customer_giro']=$line['customer_giro'];
    $vetcustomer[$chiave]['dati']['legale_nome']=$line['legale_nome'];
    $vetcustomer[$chiave]['dati']['legale_rut']=$line['legale_rut'];
    $vetcustomer[$chiave]['dati']['addr_pri']=$line['addr_pri'];
    $vetcustomer[$chiave]['dati']['addr_work']=$line['addr_work'];
    $vetcustomer[$chiave]['dati']['tel_pri']=$line['tel_pri'];
    $vetcustomer[$chiave]['dati']['tel_lavoro']=$line['tel_lavoro'];
    $vetcustomer[$chiave]['dati']['tel_mobile']=$line['tel_mobile'];
    $vetcustomer[$chiave]['dati']['customer_mail1']=$line['customer_mail1'];
    $vetcustomer[$chiave]['dati']['customer_mail2']=$line['customer_mail2'];
    $vetcustomer[$chiave]['dati']['customer_mail3']=$line['customer_mail3'];
    $vetcustomer[$chiave]['dati']['fatturaz']=$line['fatturaz'];
    $vetcustomer[$chiave]['dati']['seg_nome']=$line['seg_nome'];
    $vetcustomer[$chiave]['dati']['seg_foto']=$line['seg_foto'];
    $vetcustomer[$chiave]['dati']['seg_mail']=$line['seg_mail'];
    $vetcustomer[$chiave]['dati']['fantasia']=$line['fantasia'];
    $vetcustomer[$chiave]['dati']['customer_web']=$line['customer_web'];
    $vetcustomer[$chiave]['dati']['customer_skype']=$line['customer_skype'];
    $vetcustomer[$chiave]['dati']['customer_fb']=$line['customer_fb'];
    $vetcustomer[$chiave]['dati']['customer_codsence']=$line['customer_codsence'];
    $vetcustomer[$chiave]['dati']['customer_note']=$line['customer_note'];
    $vetcustomer[$chiave]['dati']['customer_impresa']=$line['customer_impresa'];
    $vetcustomer[$chiave]['dati']['customer_fatturazrut']=$line['fatturaz_rut'];
    $vetcustomer[$chiave]['dati']['customer_fatturazgiro']=$line['fatturaz_giro'];
    $vetcustomer[$chiave]['dati']['customer_fatturazragsoc']=$line['fatturaz_ragsoc'];
    $vetcustomer[$chiave]['dati']['customer_stato']=$line['customer_stato'];
    $vetcustomer[$chiave]['dati']['customer_stato2']=$line['customer_stato2'];
    $vetcustomer[$chiave]['dati']['rubro']=$line['rubro'];
    
    
    //LABEL ORDINAMENTO
    if ($line['customer_impresa']==1) {
      //impresa
      $vetcustomer[$chiave]['dati']['label']=$line['ragsoc'];
    } else {
      //$vetcustomer[$chiave]['dati']['label']=$line['customer_nome'];
      $vetcustomer[$chiave]['dati']['label']=$line['ragsoc'];
    }
    
    $ids .= $chiave.", ";
  }
  $ids = substr($ids,0,-2);
  
  // ###############
  // CONTATTI REFERENTI
  
  $query = "SELECT customer_contatto.id as customer_contatto_id, customer_contatto.idcustomer as idcustomer, customer_contatto.nome as nome,
        customer_contatto.posizione as posizione, customer_contatto.tel_lavoro as tel_lavoro, customer_contatto.tel_privato as tel_privato,
        customer_contatto.fax as fax, customer_contatto.mobile as mobile, customer_contatto.skype as skype, customer_contatto.note as note,
        customer_contatto.predef as predef, customer_contatto.mail1 as mail1
        
        FROM customer_contatto
        WHERE customer_contatto.trashed<>1 AND customer_contatto.idcustomer IN (".$ids.") ORDER BY customer_contatto.predef desc, customer_contatto.nome";
  unset ($result);
  $result = mysql_query ($query) or die ("Error_1.2");
  while ($line=mysql_fetch_assoc($result)) {
    $chiave2 = $line['customer_contatto_id'];
    $chiave1 = $line['idcustomer'];
    
    //predef
    if ($line['predef']==1) {
      $vetcustomer[$chiave1]['contatto_predef'] = $chiave2;
    }
    
    
    $vetcustomer[$chiave1]['contatti'][$chiave2]['nome'] = $line['nome'];
    $vetcustomer[$chiave1]['contatti'][$chiave2]['posizione'] = $line['posizione'];
    $vetcustomer[$chiave1]['contatti'][$chiave2]['tel_lavoro'] = $line['tel_lavoro'];
    $vetcustomer[$chiave1]['contatti'][$chiave2]['tel_privato'] = $line['tel_privato'];
    $vetcustomer[$chiave1]['contatti'][$chiave2]['fax'] = $line['fax'];
    $vetcustomer[$chiave1]['contatti'][$chiave2]['mobile'] = $line['mobile'];
    $vetcustomer[$chiave1]['contatti'][$chiave2]['skype'] = $line['skype'];
    $vetcustomer[$chiave1]['contatti'][$chiave2]['mail1'] = $line['mail1'];
    $vetcustomer[$chiave1]['contatti'][$chiave2]['note'] = $line['note'];
    $vetcustomer[$chiave1]['contatti'][$chiave2]['predef'] = $line['predef'];
  }
  
  //###################
  // ULTIMA REGISTRAZIONE LOG
  
  $query = "SELECT valore, label FROM stati WHERE idgruppo=2";
  $result = mysql_query($query) or die ("Errore 11.1 $query");
  while ($line = mysql_fetch_assoc($result)) {
    $lblstati[$line['valore']] = convlang($line['label']);
  }
  
  $query = "SELECT idcustomer, max(times) as ultimo
          FROM customer_log
          WHERE customer_log.trashed<>1 AND customer_log.idcustomer IN (".$ids.")
          GROUP BY idcustomer";
  $result = mysql_query($query) or die ("Error 1.3 ");
  
  
  
  $idlog = "";
  while ($line = mysql_fetch_assoc($result)) {
    $idlog .= " OR (customer_log.idcustomer = ".$line['idcustomer']." AND customer_log.times = ".$line['ultimo'].") ";
  }
  
  $query = "SELECT customer_log.id as id, customer_log.idcustomer as idcustomer, customer_log.times as times, customer_log.mezzo as mezzo, 
            customer_log.msg as msg, customer_log.user as user, user.nome as user_nome
            FROM customer_log INNER JOIN user ON customer_log.user=user.id
            WHERE customer_log.trashed<>1 AND (1=0 ".$idlog.")";

  $result = mysql_query($query) or die ("Error 1.4");
  while ($line = mysql_fetch_assoc($result)) {
    $chiave = $line['idcustomer'];
    
    $vetcustomer[$chiave]['log']['id'] = $line['id'];
    $vetcustomer[$chiave]['log']['idcustomer'] = $line['idcustomer'];
    $vetcustomer[$chiave]['log']['times'] = $line['times'];
    $vetcustomer[$chiave]['log']['mezzo'] = $line['mezzo'];
    $vetcustomer[$chiave]['log']['msg'] = $line['msg'];
    $vetcustomer[$chiave]['log']['user'] = $line['user'];
    $vetcustomer[$chiave]['log']['user_nome'] = $line['user_nome'];
  }

  
  if ($_SESSION['mgdebug']==1) {
   myprint_r($vetcustomer);
  }
  
  if ($cmd['csv']==1) {
    //salva file csv
$tmpfname = tempnam("/tto/upload", "TTO").".txt";
    $name = basename($tmpfname);


    $strriga2 = _LBLEMAIL_."\n";

   
  foreach ($vetcustomer as $keycsv => $curcsv) {
      if($curcsv['dati']['customer_mail1'] != ""){
          $strriga2 .= $curcsv['dati']['customer_mail1'].($curcsv['dati']['customer_mail1']==""?"":"")."\n";
          $strriga2 .= $curcsv['dati']['customer_mail2'].($curcsv['dati']['customer_mail2']==""?"":"\n");
      }
      else
        $strriga2 .= "\n";
    }
	file_put_contents("upload/".$name,$strriga2);


$ch = "upload/download.php?nom=".$name;
header('Location: '.$ch);
/*
if (file_exists("upload/".$name)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="upload/'.$name.'";');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    readfile("upload/".$name);

}
*/

 //   print "<FORM NAME='getfilecsv' ACTION='$filelink' TARGET=_blank></FORM>";
 //   print "<SCRIPT>document.getfilecsv.submit();</SCRIPT>";
  }
  
  //#################à
  //FORM
  
  print "<BR><H1>"._TITCLIENTE_."</H1>";
  print "<DIV STYLE='text-align:right;' NOWRAP>";
     //CSV
    print "<INPUT TYPE='button' VALUE=' Correos ' NAME='gocsv' onClick=gocsv();>&nbsp;&nbsp;";
    
    print "<FORM NAME='fstato' METHOD='POST' ACTION='".FILE_CORRENTE."'>";
    print "<INPUT TYPE='hidden' NAME='csv' VALUE=''><INPUT TYPE='hidden' NAME='cmd' VALUE='".FASESEARCH."'>";
    
    
    
    print _LBLFILTRA_.": <SELECT NAME='filtra_stato' SIZE=1 onChange=document.fstato.submit();><OPTION VALUE='-1'>"._LBLOPTTUTTI_."</OPTION>";
    
    $query = "SELECT valore, label FROM stati WHERE idgruppo=1 ORDER BY ordine";
    $result = mysql_query($query) or die("Error_qs1");
    while ($line=mysql_fetch_assoc($result)) {
      print "<OPTION VALUE='".$line['valore']."' ".($cmd['filtra_stato']==$line['valore']?" SELECTED ":"").">".convlang($line['label'])."</OPTION>";
    }
    print "</SELECT><BR>";
    
    if ($cmd['filtra_stato']==2) {
      print "<SELECT NAME='filtra_stato2' SIZE=1 onChange=document.fstato.submit();>";
      print "<OPTION VALUE='-1'>"._LBLOPTTUTTI_."</OPTION>";
      print "<OPTION VALUE='NULL' ".($cmd['filtra_stato2']=="NULL" ? " SELECTED ":"").">"._LBLNESSUNAAZIONE_."</OPTION>";
      
      $query = "SELECT id, valore, label FROM stati WHERE idgruppo=2 ORDER BY ordine";
      $result = mysql_query($query) or die ("Error_10.3");
      while ($line=mysql_fetch_assoc($result)) {
        print "<OPTION VALUE='".$line['valore']."'".($cmd['filtra_stato2']==$line['valore']? " SELECTED  CLASS='OPTION_SELEZIONATA'":"").">".convlang($line['label'])."</OPTION>";
      }
      print "</SELECT>";
    }
    
    
    print "</FORM><BR><BR>";
    
    print "</DIV>";
  $lastchar="AA";
  if (count($vetcustomer)>0 )  {
    print "<TABLE CLASS=lista_table ALIGN=CENTER>";
    //print "<TR><TD COLSPAN=5><TABLE STYLE='width:100%'>";   
    $k=9; 
    foreach ($vetcustomer as $key =>$cur) {
      $ancora_salto="";
      $k++;
      if ((substr($cur['dati']['label'],0,1)!=$lastchar && $cmd['filtra_stato']!=2)|| $k==10) {
        $k=0;
        $m++;
        if ($cmd['filtra_stato']!=2) {
          $lastchar=substr($cur['dati']['label'],0,1);
          print "<TR><TD STYLE='width:160px;'><SPAN STYLE='font-size:24px;'>[".$lastchar."]</SPAN></TD>";
        } else {
          print "<TR><TD>&nbsp;</TD>";
        }
          //SEARCH
          print "<TD STYLE='text-align:right;'><DIV id='areasearch'>";
          print "<FORM NAME='ricerca$m' METHOD='POST' ACTION='".FILE_CORRENTE."'><INPUT TYPE='hidden' NAME='cmd' VALUE='".FASESEARCH."'>";
          print "<TABLE align='center' class='searchform' cellpadding='2' cellspacing='0' width='40%'>";
          print "<TR>";
          print "<TD CLASS='form_lbl'><INPUT TYPE='text' NAME='src1' VALUE='".$cmd['src1']."'><INPUT TYPE='hidden' NAME='daform' VALUE='1'></TD><TD CLASS='form_lbl'><BUTTON>Search</BUTTON></TD>";

          if ($cmd['filtra_stato']==2) {
              print "<TD>";
              $query = "SELECT id, valore, label FROM stati WHERE idgruppo=2 ORDER BY ordine";
              $result = mysql_query($query) or die ("Error_10.3");
              
              print "<SELECT NAME='filtra_stato2' SIZE=1 onChange=document.ricerca$m.submit();>";
              print "<OPTION VALUE='-1'>"._LBLOPTTUTTI_."</OPTION>";
              print "<OPTION VALUE='NULL' ".($cmd['filtra_stato2']=="NULL" ? " SELECTED ":"").">"._LBLNESSUNAAZIONE_."</OPTION>";
              
              while ($line=mysql_fetch_assoc($result)) {
                print "<OPTION VALUE='".$line['valore']."'".($cmd['filtra_stato2']==$line['valore']? " SELECTED  CLASS='OPTION_SELEZIONATA'":"").">".convlang($line['label'])."</OPTION>";
              }
              print "</SELECT>";
          }

          print "</TR>";
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
          print "<IMG CLASS='manina' BORDER=0 SRC='../img/add_user.png' ALT='"._TIPADDUSERCONTATTO_."' TITLE='"._TIPADDUSERCONTATTO_."' onClick='popcliente(2,0);'></TD>";
        print "</TD></TR>";
        //print "</TABLE></TD></TR>";
        print "<TR><TD CLASS=lista_tittab>"._CLIENTE_."</TD><TD CLASS=lista_tittab>"._CONTATTO_."</TD><TD CLASS=lista_tittab STYLE='width:25%;'>"._LBLULTIMOCONTATTO_."</TD><TD CLASS=lista_tittab>"._STATO_."</TD><TD CLASS=lista_tittab width='70'>&nbsp;</TD></TR>";
        $cur_rowstyle = "form2_lista_riga_pari";
      }
      
      if ($cur_rowstyle=="form2_lista_riga_pari") {
        $cur_rowstyle="form2_lista_riga_dispari";
        $stile = "background-color:#EDEDED;";//EDEDED   //F0FFEF
      } else {
        $cur_rowstyle="form2_lista_riga_pari";
        $stile = "background-color:#F7F7F7;";//F7F7F7   //FEFFEF
      }
      if ($key==$selobj) {
        $cur_rowstyle = "form2_lista_riga_evidente_selid";//F7F7F7   //FEFFEF
        
        $ancora_salto="<A NAME='SELOBJ'>&nbsp;</A>";
      }
      
      //print "<TR CLASS=lista_riga>";
      print "<TR CLASS='$cur_rowstyle'>";
    
      //tipo
      
      switch ($cur['dati']['customer_impresa']) {
        case 1://azienda
          $img = "industria2.png";$lbl=_LBLAZIENDALE_;break;
        case 0://individuale
        default:
          $img = "persone.png";$lbl=_LBLINDIVIDUALE_;break;
      }
      print "<TD CLASS='lista_col_form2 risalto'>";
        
        print $ancora_salto;
      
        print "<A NAME='blocco".$key."'></A><IMG SRC='../img/".$img."' ALT='$lbl' TITLE='$lbl' ALIGN=MIDDLE>&nbsp;";
      
        print "[". $key ."] ".$cur['dati']['customer_nome']." ".$cur['dati']['customer_apellido'];
        if ($cur['dati']['fantasia']!="") {
          print "<BR>[".$cur['dati']['fantasia']."]";
        }
            
      print "</TD>";
      //print "<TD CLASS=lista_col_form2>".$cur['dati']['fantasia']."</TD>";
      
      //contatti
      //mostra email del contatto customer + contatto predefinito
      print "<TD CLASS=lista_col_form2>";
        //customer
        if ($cur['dati']['tel_pri']!="") {
          print "<IMG SRC='../img/phone_red16.png' BORDER=0 ALT='"._LBLTELPRI_."' TITLE='"._LBLTELPRI_."'> ".$cur['dati']['tel_pri']."&nbsp;&nbsp;";
        }
        if ($cur['dati']['tel_lavoro']!="") {
          print "<IMG SRC='../img/phone_blue16.png' BORDER=0 ALT='"._LBLTELWORK_."' TITLE='"._LBLTELWORK_."'> ".$cur['dati']['tel_lavoro']."&nbsp;&nbsp;";
        }
        if ($cur['dati']['tel_mobile']!="") {
          print "<IMG SRC='../img/mobile16.png' BORDER=0 ALT='"._LBLTELMOBILE_."' TITLE='"._LBLTELMOBILE_."'> ".$cur['dati']['tel_mobile']."&nbsp;&nbsp;";
        }
        print "<BR STYLE='margin-top:10px;margin-bottom:10px;'>";
        if ($cur['dati']['customer_mail1']!="") {
          print "<A HREF='mailto:".$cur['dati']['customer_mail1']."'><IMG SRC='../img/email16.png' BORDER=0> ".$cur['dati']['customer_mail1']."</A>&nbsp;&nbsp;";
        }
        if ($cur['dati']['customer_mail2']!="") {
          print "<A HREF='mailto:".$cur['dati']['customer_mail2']."'><IMG SRC='../img/email16.png' BORDER=0> ".$cur['dati']['customer_mail2']."</A>&nbsp;&nbsp;";
        }
        if ($cur['dati']['customer_mail3']!="") {
          print "<A HREF='mailto:".$cur['dati']['customer_mail3']."'><IMG SRC='../img/email16.png' BORDER=0> ".$cur['dati']['customer_mail3']."</A>&nbsp;&nbsp;";
        }
        
        
        
        print "<HR STYLE='width:80%;margin-top:10px;margin-bottom:10px;'>";
      
      $appkey = $cur['contatto_predef'];
      if ($appkey!="") {
        print $cur['contatti'][$appkey]['nome']." (".$cur['contatti'][$appkey]['posizione'].")<BR>"._LBLSHORTTELWORK_.$cur['contatti'][$appkey]['tel_lavoro']." "._LBLSHORTTELPRI_.$cur['contatti'][$appkey]['tel_privato']." "._LBLSHORTFAX_.$cur['contatti'][$appkey]['fax']."<BR>"._LBLSHORTTELMOB_.$cur['contatti'][$appkey]['mobile']." "._LBLSHORTSKYPE_.$cur['contatti'][$appkey]['skype'];
      } else {
        print _MSGNOPREDEF_;
      }
      print "</TD>";
                                
      //ULTIMO CONTATTO
      
      print "<TD CLASS=lista_col_form2>";
        if ($cur['log']['times']>0) {
          print "<SPAN STYLE='font-size:13px !important;font-weight:bolder !important;'>".date("d/m/Y",$cur['log']['times'])." (".eT_diffts($cur['log']['times'], time(), $labels).")</SPAN><BR><SPAN STYLE='font-size:bolder;text-alignement:center;'>"._LBLULTIMAAZIONE_.": ".$lblstati[$cur['dati']['customer_stato2']]."<HR ALIGN=CENTER STYLE='width:40%;margin-top:10px;margin-bottom:10px;'>[".$cur['log']['user_nome']."]: ".conv_textarea($cur['log']['msg']);
        } else {
          print _LBLNESSUNULTIMOCONTATTO_;
        }
      print "</TD>";
      
      //stato
      switch ($cur['dati']['customer_stato']) {
        case CORRENTE:
          $imgledstato="ledgreen.png";
          $txtstato = _CORRENTE_;
          break;
        case POTENZIALE:
          $imgledstato="ledyellow.png";
          $txtstato = _POTENZIALE_;
          break;
        case VECCHIO:
          $imgledstato="ledred.png";
          $txtstato = _VECCHIO_;
          break;
        default:
          $imgledstato="ledgray.png";
          $txtstato = _NONDEF_;
      }
      print "<TD CLASS=lista_col_form2 ALIGN='CENTER'>"."<IMG SRC='../img/".$imgledstato."' ALT='$txtstato' TITLE='$txtstato' CLASS='manina' onClick=popstato($key)>"."</TD>";
      
      //COMANDI
      print "<TD CLASS=lista_col_form2>
        <TABLE width=100%><TR>
          <TD ALIGN=CENTER><IMG CLASS='manina' SRC='../img/mod.png' border=0 ALT='"._LBLMATITA_."' TITLE='"._LBLMATITA_."' onClick=popcliente(". FASEMOD .",". $key .")></TD>
          <TD ALIGN=CENTER><A HREF='#' onClick=urldelete(\"". FILE_DBQUERY ."?cmd=". FASEDEL ."&codicepagina=". codiceform(FILE_CORRENTE) ."&selobj=". $key ."\")><IMG SRC='../img/canc.png' border=0 ALT='"._LBLCANCELLA_."' TITLE='"._LBLCANCELLA_."'></A></TD>
          <TD ALIGN=CENTER><A HREF='#blocco$key' onClick=mostradett(\"dettrow_$key\")><IMG SRC='../img/lente.png' border=0 ALT='"._LBLLENTE_."' TITLE='"._LBLLENTE_."'></A></TD>";
          if ($cur['dati']['customer_impresa']==0 && $cur['dati']['customer_stato']==1) {
            print "<TD ALIGN=CENTER><A HREF='". FILE_DBQUERY ."?cmd=". FASECREASTUDENTE ."&codicepagina=". codiceform(FILE_CORRENTE) ."&selobj=". $key."'><IMG SRC='../img/clonaclialunno.png' border=0 ALT='"._LBLLENTE_."' TITLE='"._LBLLENTE_."'></A></TD>";
          } else {
            print "<TD STYLE='width:28px;'>&nbsp;</TD>";
          }
			  print "</TR></TABLE></TD>";
      print "</TR>";
      //print "<TR><TD COLSPAN=5><CENTER><TABLE WIDTH=100%><TR><TD STYLE='border:1px solid red;'>&nbsp;</TD></TR></TABLE></CENTER></TD></TR>";
      print "<TR>
           <TD COLSPAN=5 STYLE='width:100%;'>";
      print "<TABLE id='dettrow_".$key."' STYLE='width:100%;display: ".($cmd['openblock']==$key ? " block ":" none ").";'>
              <TR><TD STYLE='width:100%;'>";
        //############################à
        //print "<TR id='dettrow_$key' STYLE='display: ".($cmd['openblock']==$key ? " block ":" none ").";border:1px solid #FFAF02;'>
        //DETTROW
        
        print "<DIV STYLE='border:2px solid #0000FF;width:100%;background-color:#FFFACD;'>";
        print "<TABLE WIDTH=100%>";
          print "<TR><TD><IMG SRC='../img/".$img."' ALT='$lbl' TITLE='$lbl'></TD><TD CLASS=cust_nome>".$cur['dati']['customer_nome']." ".$cur['dati']['customer_apellido']."</TD>";      
          print "<TD>";
          //print "<SPAN STYLE='font-size:20px;'>".$cur['dati']['customer_apellido']." ".$cur['dati']['customer_nome']."</SPAN>";
          if ($cur['dati']['fantasia']!="") {
            print "(".$cur['dati']['fantasia'].")";
          } else {
            if ($cur['dati']['ragsoc']!="") {
              print "(".$cur['dati']['ragsoc'].")";
            } else {
              print "&nbsp;";
            }
          }
          print "</TD></TR>";
        print "</TABLE>";
        print "<TABLE WIDTH=100%>";
          /*print "<BR>";
          print $cur['dati']['ragsoc']." Posizione ".$cur['dati']['posizione'];
          print "<BR>";
          print "Indirizzo Azienda:".$cur['dati']['addr_work']."<BR>";
          print "Indirizzo Privato:".$cur['dati']['addr_pri']."<BR>";
          print "RUT Azienda ".$cur['dati']['rut_ditta']." "."RUT Persona ".$cur['dati']['rut_persona']."<BR>";
          print "Legale rappresentante: ".$cur['dati']['legale_nome']." RUT ".$cur['dati']['legale_rut']."<BR>";
          print "Segreteria: ".$cur['dati']['seg_nome']." ".$cur['dati']['seg_mail']."<BR>";
          print "Codice Sence: ".$cur['dati']['customer_codsence']."<BR>";
          print "Tel.Privato: ".$cur['dati']['tel_pri']." "."Tel.Lavoro: ".$cur['dati']['tel_lavoro']." "."Tel.Privato: ".$cur['dati']['tel_mobile']."<BR>";
          */
          
          print "<TR><TD CLASS=cust_lbl>"._AZIENDA_."</TD><TD CLASS=cust_lbl>"._LBLPOSIZIONE_."</TD><TD CLASS=cust_lbl>"._LBLCODSENCE_."</TD></TR>";
          print "<TR><TD>".$cur['dati']['ragsoc']."</TD><TD>".$cur['dati']['posizione']."</TD><TD>".$cur['dati']['customer_codsence']."</TD></TR>";
          print "<TR><TD CLASS=cust_lbl>"._LBLADDRESSWORK_."</TD><TD COLSPAN=2>".$cur['dati']['addr_work']."</TD></TR>";
          print "<TR><TD CLASS=cust_lbl>"._LBLADDRESSPRI_."</TD><TD COLSPAN=2>".$cur['dati']['addr_pri']."</TD></TR>";
          print "<TR><TD CLASS=cust_lbl>"._LBLRUTDITTA_."</TD><TD CLASS=cust_lbl>"._LBLRUTPERSONA_."</TD><TD CLASS=cust_lbl>"._LBLFATTURAZIONE_."</TD></TR>";
          print "<TR><TD>".$cur['dati']['rut_ditta']."</TD><TD>".$cur['dati']['rut_persona']."</TD><TD>".($cur['dati']['fatturaz']==0 ? _LBLOPZFATTPRI_ : _LBLOPZFATTDITTA_)."</TD></TR>";
          if ($cur['dati']['customer_impresa']==0 && $cur['dati']['fatturaz']==1) {
            print "<TR><TD CLASS=cust_lbl>"._AZIENDA_."</TD><TD CLASS=cust_lbl>"._LBLRUT_."</TD><TD CLASS=cust_lbl>"._LBLGIRO_."</TD></TR>";
            print "<TR><TD>".$cur['dati']['customer_fatturazragsoc']."</TD><TD>".$cur['dati']['customer_fatturazrut']."</TD><TD>".$cur['dati']['customer_fatturazgiro']."</TD></TR>";
          }
          
          print "<TR><TD CLASS=cust_lbl>"._LBLLEGALENOME_."</TD><TD COLSPAN=2>".$cur['dati']['legale_nome']." ("._LBLRUT_.": ".$cur['dati']['legale_rut'].")</TD></TR>";
          //print "<TR><TD CLASS=cust_lbl>Segreteria</TD><TD COLSPAN=2>".$cur['dati']['seg_nome']." ".$cur['dati']['seg_mail']."</TD></TR>";
          print "<TR><TD COLSPAN=3 CLASS=cust_lbl>"._CONTATTI_."</TD></TR>";
          print "<TR><TD CLASS=cust_lbl>"._LBLTELPRI_."</TD><TD CLASS=cust_lbl>"._LBLTELWORK_."</TD><TD CLASS=cust_lbl>"._LBLTELMOBILE_."</TD></TR>";
          print "<TR><TD>".$cur['dati']['tel_pri']."</TD><TD>".$cur['dati']['tel_lavoro']."</TD><TD>".$cur['dati']['tel_mobile']."</TD></TR>";
          print "<TR><TD CLASS=cust_lbl COLSPAN=3>"._LBLEMAIL_."</TD></TR>";
          print "<TR><TD><A HREF='mailto:".$cur['dati']['customer_mail1']."'>".$cur['dati']['customer_mail1']."</A></TD><TD><A HREF='mailto:".$cur['dati']['customer_mail2']."'>".$cur['dati']['customer_mail2']."</A></TD><TD>&nbsp;</TD></TR>";
          print "<TR><TD CLASS=cust_lbl>"._LBLWEB_."</TD><TD CLASS=cust_lbl>"._LBLSKYPE_."</TD><TD CLASS=cust_lbl>&nbsp;</TD></TR>";
          print "<TR><TD><A HREF='http://".$cur['dati']['customer_web']."'>".$cur['dati']['customer_web']."</A></TD><TD>".$cur['dati']['customer_skype']."</TD><TD>".$cur['dati']['customer_fb']."</TD></TR>";
          print "<TR><TD COLSPAN=3 CLASS=cust_lbl>"._NOTES_."</TD></TR>";
          print "<TR><TD COLSPAN=3>".conv_textarea($cur['dati']['customer_note'])."</TD></TR>";
          print "</TABLE>";
        print "</DIV>";
        print "<BR>";
        print "<DIV STYLE='border:2px solid #0000FF;width:100%;background-color:#D4FED4;'>";
        print "<TABLE WIDTH=100%>";
          //dett contatti
          print "<TR><TD COLSPAN=2 STYLE='font-size:16px;font-style:italic;font-weight:bolder;'>"._CONTATTI_."</TD><TD STYLE='text-align:right;'><IMG CLASS='manina' BORDER=0 SRC='../img/add_user.png' ALT='"._TIPADDUSERCONTATTO_."' TITLE='"._TIPADDUSERCONTATTO_."' onClick='popcontatto(2,0,$key);'></TD></TR>";
          foreach ($cur['contatti'] as $key2=>$cur2) {
            print "<TR><TD COLSPAN=2  STYLE='font-size:14px;font-weight:bolder;'>";
              if ($cur2['predef']==1) {
                print "<IMG SRC='../img/stella.png' ALT='PREFERITO' TITLE='PREFERITO'>";  
              } else {
                print "<A HREF='".FILE_DBQUERY."?selobj=$key2&idcustomer=$key&cmd=".FASEPREFERITO."&codicepagina=CONTATTICUST'><IMG BORDER=0 SRC='../img/stellagray.png' ALT='NOPREFERITO' TITLE='NOPREFERITO'></A>";
              }
              print "&nbsp;";
              print "<IMG CLASS='manina' SRC='../img/mod.png' ALT='Modifica riga' TITLE='Modifica riga' onClick='popcontatto(3,$key2);'>";
              print "&nbsp;";
              print "<A HREF='#' onClick=urldelete(\"". FILE_DBQUERY ."?cmd=". FASEDEL ."&codicepagina=CONTATTICUST&selobj=". $key2 ."&idcustomer=$key\")><IMG SRC='../img/canc.png' border=0 ALT='"._LBLCANCELLA_."' TITLE='"._LBLCANCELLA_."'></A>";
              print "&nbsp;";
              print $cur2['nome']."</TD>";
            print "<TD>".$cur2['posizione']."</TD></TR>";
            print "<TR><TD CLASS=cust_lbl>"._LBLTELWORK_."</TD><TD CLASS=cust_lbl>"._LBLTELPRI_."</TD><TD CLASS=cust_lbl>"._LBLTELMOBILE_."</TD></TR>";
            print "<TR><TD>".$cur2['tel_lavoro']."</TD><TD>".$cur2['tel_privato']."</TD><TD>".$cur2['mobile']."</TD></TR>";
            print "<TR><TD CLASS=cust_lbl>"._LBLFAX_."</TD><TD CLASS=cust_lbl>"._LBLSKYPE_."</TD><TD CLASS=cust_lbl>"._LBLEMAIL_."</TD></TR>";
            print "<TR><TD>".$cur2['fax']."</TD><TD>".$cur2['skype']."</TD><TD><A HREF='mailto:".$cur2['mail1']."'>".$cur2['mail1']."</A></TD></TR>";
            print "<TR><TD COLSPAN=3 CLASS=cust_lbl>"._NOTES_."</TD></TR>";
            print "<TR><TD COLSPAN=3>".$cur2['note']."</TD></TR>";
            print "<TR><TD COLSPAN=3><HR STYLE='width:80%;height:2px;color:#000000;'></TD></TR>";
          }
          
        print "</TABLE>";
        print "</DIV>";
      print "</TD></TR></TABLE>";
      print "</TD></TR>";
    }
    print "<TABLE>";
?>

<?php
  } else {
      print "<SPAN STYLE='text-align:right;width:160px;'>";  
      print "<IMG CLASS='manina' BORDER=0 SRC='../img/add_user.png' ALT='"._TIPADDUSERCONTATTO_."' TITLE='"._TIPADDUSERCONTATTO_."' onClick='popcliente(2,0);'>";
      
      if ($_SESSION[FILE_CORRENTE.'FILTERED']==1) {
        //print "<TD ALIGN=RIGHT STYLE='text-align:right;width:160px;'>";
        print "&nbsp;&nbsp;&nbsp;";
        print "<A HREF='". FILE_CORRENTE ."?rstflt=1'><img src='../img/edit_small.gif' align='center' border='0' width='16' height='16'>"._RESETFILTER_."</A>";
        //print "</div></TD>";
      } else {
        //print "<TD>&nbsp;</TD>";
      }
      print "</SPAN>";   
  }
?>
<SCRIPT>
  function gocsv() {
    document.fstato.csv.value="1";
    document.fstato.submit();
  }
  
  function popcontatto(fase,id,idc) {
    var figlio;
    var width  = 600;
    var height = 550;
    var left   = (screen.width  - width)/2;
    var top    = (screen.height - height)/2;
    var params = 'width='+width+', height='+height;
    params += ', top='+top+', left='+left;
    params += ', directories=no';
    params += ', location=no';
    params += ', menubar=no';
    params += ', resizable=no';
    params += ', scrollbars=no';
    params += ', status=no';
    params += ', toolbar=no';
    
    figlio = window.open("frm_contatticust_popup.php?selobj="+id+"&cmd="+fase+"&idc="+idc,"customer_contatti",params);
    if (figlio.opener==null) {
      figlio.opener=self;
      figlio.frames["saveopener"].opener=self;
    }
  }

  function popcliente(fase,id) {
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
    
    figlio = window.open("frm_clienti_popup.php?selobj="+id+"&cmd="+fase,"customer_contatti",params);
    if (figlio.opener==null) {
      figlio.opener=self;
      figlio.frames["saveopener"].opener=self;
    }
  }
  
  function mostradett(chiave) {
    var elem = document.getElementById(chiave);  
    if (elem.style.display == "block") {
      elem.style.display = "none";
    } else {
      elem.style.display = "block";
    }
  }

  function popstato(id) {
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
    
    figlio = window.open("frm_clientistato_popup.php?selobj="+id,"clienti_stato",params);
    if (figlio.opener==null) {
      figlio.opener=self;
      figlio.frames["saveopener"].opener=self;
    }
  }  
  
</SCRIPT>
<?php
  include ("finepagina.php");
    die("");
  // Torna all'inserimento
  if ($cmd['comando']==FASEMOD) {
    print "<div align='right' class='backinsert'>";
    print "\t<A HREF='". FILE_CORRENTE ."'><img src='../img/edit_small.gif' align='center' border='0' width='16' height='16'>"._BACKTOINS_."</A>";
    print "</div>";
  }
  print "<BR>";
  
  if ($cmd['comando']==FASEMOD) {
      $titolofinestra = _MODCLIENTE_; $classtitolo = "headermod";
      //echo "XXX";
  } else {
      $cmd['comando']=FASEINS;
      $titolofinestra = _INSCLIENTE_; $classtitolo = "headerins";
      //echo "<BR> xxcmd ".$cmd;
  }
  print "<FORM ACTION='". FILE_DBQUERY ."' METHOD='POST' NAME='caricamento'><INPUT TYPE='hidden' NAME='cmd' VALUE='". $cmd['comando'] ."'><INPUT TYPE='hidden' NAME='selobj' VALUE='". $selobj ."'><INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'>";
	print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='50%'>";
  print "<tr><td class='headermod' colspan='3'><div class='header-text'>$titolofinestra</div></td></tr>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLNOME_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='nome' VALUE='". $editval['nome'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLRUT_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='rut' VALUE='". $editval['rut'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._INDIRIZZO_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='indirizzo' VALUE='". $editval['indirizzo'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLNUM_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='num' VALUE='". $editval['num'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLCOMUNE_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='comune' VALUE='". $editval['comune'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLTEL_."</TD>";
  print "<TD><TEXTAREA NAME='tel' COLS='50' ROWS='3'>". $editval['tel'] ."</TEXTAREA></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLMOBILE_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='mobile' VALUE='". $editval['mobile'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLFAX_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='fax' VALUE='". $editval['fax'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLEMAIL_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='email' VALUE='". $editval['email'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._CONTATTI_."</TD>";
  print "<TD><TEXTAREA NAME='contatti' COLS='50' ROWS='3'>". $editval['contatti'] ."</TEXTAREA></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLVATNO_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='piva' VALUE='". $editval['piva'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLCONTABILI_."</TD>";
  print "<TD><TEXTAREA NAME='contabili' COLS='50' ROWS='3'>". $editval['contabili'] ."</TEXTAREA></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR><TD COLSPAN=3 align='center' height='70'><INPUT TYPE='submit' VALUE='"._BTNSALVA_."'>&nbsp;&nbsp;&nbsp;<INPUT TYPE='reset' VALUE='"._BTNRESET_."'></TD></TR>";
  if ($cmd['comando']==FASEINS) {
    print "<TR><TD COLSPAN=3 align='center' height='70'><INPUT TYPE='button' STYLE='width:200px;' VALUE='"._BTNRICERCA_."' onClick=fasesearch(document.caricamento);></TD></TR>";
  }
  print "</TABLE></FORM>";

  include ("finepagina.php");
  
  
?>
</body>
</html>
