<?php
  $pagever = "1.25";
  $pagemod = "15/11/2010 21.44.18";
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
  define("FILE_CORRENTE", "frm_fornitori.php");

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
  
  
  
  $query_select = "SELECT fornitore.id as fornitore_id, fornitore.nome as fornitore_nome, fornitore.apellido as fornitore_apellido,
        fornitore.posizione as posizione, fornitore.ragsoc as ragsoc, fornitore.rut_ditta as rut_ditta, fornitore.rut_persona as rut_persona,
        fornitore.giro as fornitore_giro, fornitore.legale_nome as legale_nome, fornitore.legale_rut as legale_rut, fornitore.addr_pri as addr_pri,
        fornitore.addr_work as addr_work, fornitore.tel_pri as tel_pri, fornitore.tel_lavoro as tel_lavoro, fornitore.tel_mobile as tel_mobile,
        fornitore.mail1 as fornitore_mail1, fornitore.mail2 as fornitore_mail2, fornitore.mail3 as fornitore_mail3,fornitore.fatturaz as fatturaz,
        fornitore.seg_nome as seg_nome, fornitore.seg_foto as seg_foto, fornitore.seg_mail as seg_mail, fornitore.fantasia as fantasia, fornitore.web as fornitore_web,
        fornitore.skype as fornitore_skype, fornitore.fb as fornitore_fb, fornitore.codsence as fornitore_codsence, fornitore.note as fornitore_note, fornitore.impresa as fornitore_impresa, 
        fornitore.fatturaz_rut as fatturaz_rut, fornitore.fatturaz_giro as fatturaz_giro, fornitore.fatturaz_ragsoc as fatturaz_ragsoc, fornitore.idcategoria as idcategoria,
        fornitore.categ2 as categ2, fornitore.stato as fornitore_stato, ccbanca.id as ccbanca_id, ccbanca.idbanca as idbanca, 
        ccbanca.codice as ccbanca_codice, ccbanca.tipocc as ccbanca_tipocc,banca.nomebanca as banca_nomebanca, 
        stati_banca.label as ccbanca_label  ";
        
  $query = "FROM fornitore LEFT JOIN stati ON fornitore.idcategoria=stati.valore
            LEFT JOIN ccbanca ON fornitore.id = ccbanca.idsoggetto AND ccbanca.tiposoggetto=2
            LEFT JOIN banca ON ccbanca.idbanca = banca.id
            LEFT JOIN stati as stati_banca ON ccbanca.tipocc=stati_banca.valore AND stati_banca.idgruppo=4
        WHERE fornitore.trashed<>1";
  
  if ($cmd['filtra_stato']!="-1") {
    $query .= " AND fornitore.stato=".$cmd['filtra_stato']." ";
  }
      
  if ($cmd['src1']!="") {
    $query .= " AND (fornitore.apellido like '%".$cmd['src1']."%' OR fornitore.nome like '%".$cmd['src1']."%' OR fornitore.ragsoc like '%".$cmd['src1']."%' OR fornitore.fantasia like '%".$cmd['src1']."%')";
  } else {
    resetFormSessionFilter();
  }
  
  switch ($cmd['form_sort']) {
    case 1:
    default:
            $query .= " ORDER BY stati.ordine, categ2, fornitore.ragsoc, fornitore.impresa DESC, fornitore.nome, fornitore.apellido ";
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
 
  $result = mysql_query($query) or die ("Error_1.1$query");
  $ids = "-1, ";  
  while ($line = mysql_fetch_assoc($result)) {
    $chiave = $line['fornitore_id'];
    $vetfornitore[$chiave]['dati']['idcategoria']=$line['idcategoria'];
    $vetfornitore[$chiave]['dati']['categ2']=$line['categ2'];
    $vetfornitore[$chiave]['dati']['fornitore_nome']=$line['fornitore_nome'];
    $vetfornitore[$chiave]['dati']['fornitore_apellido']=$line['fornitore_apellido'];
    $vetfornitore[$chiave]['dati']['posizione']=$line['posizione'];
    $vetfornitore[$chiave]['dati']['ragsoc']=$line['ragsoc'];
    $vetfornitore[$chiave]['dati']['rut_ditta']=$line['rut_ditta'];
    $vetfornitore[$chiave]['dati']['rut_persona']=$line['rut_persona'];
    $vetfornitore[$chiave]['dati']['fornitore_giro']=$line['fornitore_giro'];
    $vetfornitore[$chiave]['dati']['legale_nome']=$line['legale_nome'];
    $vetfornitore[$chiave]['dati']['legale_rut']=$line['legale_rut'];
    $vetfornitore[$chiave]['dati']['addr_pri']=$line['addr_pri'];
    $vetfornitore[$chiave]['dati']['addr_work']=$line['addr_work'];
    $vetfornitore[$chiave]['dati']['tel_pri']=$line['tel_pri'];
    $vetfornitore[$chiave]['dati']['tel_lavoro']=$line['tel_lavoro'];
    $vetfornitore[$chiave]['dati']['tel_mobile']=$line['tel_mobile'];
    $vetfornitore[$chiave]['dati']['fornitore_mail1']=$line['fornitore_mail1'];
    $vetfornitore[$chiave]['dati']['fornitore_mail2']=$line['fornitore_mail2'];
    $vetfornitore[$chiave]['dati']['fornitore_mail3']=$line['fornitore_mail3'];
    $vetfornitore[$chiave]['dati']['fatturaz']=$line['fatturaz'];
    $vetfornitore[$chiave]['dati']['seg_nome']=$line['seg_nome'];
    $vetfornitore[$chiave]['dati']['seg_foto']=$line['seg_foto'];
    $vetfornitore[$chiave]['dati']['seg_mail']=$line['seg_mail'];
    $vetfornitore[$chiave]['dati']['fantasia']=$line['fantasia'];
    $vetfornitore[$chiave]['dati']['fornitore_web']=$line['fornitore_web'];
    $vetfornitore[$chiave]['dati']['fornitore_skype']=$line['fornitore_skype'];
    $vetfornitore[$chiave]['dati']['fornitore_fb']=$line['fornitore_fb'];
    $vetfornitore[$chiave]['dati']['fornitore_codsence']=$line['fornitore_codsence'];
    $vetfornitore[$chiave]['dati']['fornitore_note']=$line['fornitore_note'];
    $vetfornitore[$chiave]['dati']['fornitore_impresa']=$line['fornitore_impresa'];
    $vetfornitore[$chiave]['dati']['fornitore_fatturazrut']=$line['fatturaz_rut'];
    $vetfornitore[$chiave]['dati']['fornitore_fatturazgiro']=$line['fatturaz_giro'];
    $vetfornitore[$chiave]['dati']['fornitore_fatturazragsoc']=$line['fatturaz_ragsoc'];
    $vetfornitore[$chiave]['dati']['fornitore_stato']=$line['fornitore_stato'];
    
    $vetfornitore[$chiave]['dati']['ccbanca_id']=$line['ccbanca_id'];
    $vetfornitore[$chiave]['dati']['idbanca']=$line['idbanca'];
    $vetfornitore[$chiave]['dati']['ccbanca_codice']=$line['ccbanca_codice'];
    $vetfornitore[$chiave]['dati']['ccbanca_tipocc']=$line['ccbanca_tipocc'];
    $vetfornitore[$chiave]['dati']['banca_nomebanca']=$line['banca_nomebanca'];
    $vetfornitore[$chiave]['dati']['ccbanca_label']=$line['ccbanca_label'];
    
    //LABEL ORDINAMENTO
    if ($line['fornitore_impresa']==1) {
      //impresa
      $vetfornitore[$chiave]['dati']['label']=$line['ragsoc'];
    } else {
      //$vetfornitore[$chiave]['dati']['label']=$line['fornitore_nome'];
      $vetfornitore[$chiave]['dati']['label']=$line['ragsoc'];
    }
    
    $ids .= $chiave.", ";
  }
  $ids = substr($ids,0,-2);
  
  // ###############
  // CONTATTI REFERENTI
  
  $query = "SELECT fornitore_contatto.id as fornitore_contatto_id, fornitore_contatto.idfornitore as idfornitore, fornitore_contatto.nome as nome,
        fornitore_contatto.posizione as posizione, fornitore_contatto.tel_lavoro as tel_lavoro, fornitore_contatto.tel_privato as tel_privato,
        fornitore_contatto.fax as fax, fornitore_contatto.mobile as mobile, fornitore_contatto.skype as skype, fornitore_contatto.note as note,
        fornitore_contatto.predef as predef, fornitore_contatto.mail1 as mail1
        
        FROM fornitore_contatto
        WHERE fornitore_contatto.trashed<>1 AND fornitore_contatto.idfornitore IN (".$ids.") ORDER BY fornitore_contatto.predef desc, fornitore_contatto.nome";
  unset ($result);
  $result = mysql_query ($query) or die ("Error_1.2");
  while ($line=mysql_fetch_assoc($result)) {
    $chiave2 = $line['fornitore_contatto_id'];
    $chiave1 = $line['idfornitore'];
    
    //predef
    if ($line['predef']==1) {
      $vetfornitore[$chiave1]['contatto_predef'] = $chiave2;
    }
    
    
    $vetfornitore[$chiave1]['contatti'][$chiave2]['nome'] = $line['nome'];
    $vetfornitore[$chiave1]['contatti'][$chiave2]['posizione'] = $line['posizione'];
    $vetfornitore[$chiave1]['contatti'][$chiave2]['tel_lavoro'] = $line['tel_lavoro'];
    $vetfornitore[$chiave1]['contatti'][$chiave2]['tel_privato'] = $line['tel_privato'];
    $vetfornitore[$chiave1]['contatti'][$chiave2]['fax'] = $line['fax'];
    $vetfornitore[$chiave1]['contatti'][$chiave2]['mobile'] = $line['mobile'];
    $vetfornitore[$chiave1]['contatti'][$chiave2]['skype'] = $line['skype'];
    $vetfornitore[$chiave1]['contatti'][$chiave2]['mail1'] = $line['mail1'];
    $vetfornitore[$chiave1]['contatti'][$chiave2]['note'] = $line['note'];
    $vetfornitore[$chiave1]['contatti'][$chiave2]['predef'] = $line['predef'];
  }
  
  //categorie
  $query = "SELECT id,valore, label, img FROM stati WHERE idgruppo=3 ORDER BY ordine";
  $result = mysql_query($query) or die ("Error_loadcatg");
  while ($line = mysql_fetch_assoc($result)) {
    $vetcategorie[$line['valore']]['img']=$line['img'];
    $vetcategorie[$line['valore']]['txt']=$line['label'];
  }
  //###################
  // ALUNNI E CORSI
  
  if ($_SESSION['mgdebug']==1) {
   myprint_r($vetfornitore);
  }
  
  //#################à
  //FORM
  
  print "<BR><H1>"._TITFORNITORE_."</H1>";
  print "<DIV STYLE='text-align:right;'>";
  print "<FORM NAME='fstato' METHOD='POST' ACTION='".FILE_CORRENTE."'><INPUT TYPE='hidden' NAME='cmd' VALUE='".FASESEARCH."'>";
  print _LBLFILTRA_.": <SELECT NAME='filtra_stato' SIZE=1 onChange=document.fstato.submit();><OPTION VALUE='-1'>"._LBLOPTTUTTI_."</OPTION>";
  
  $query = "SELECT valore, label FROM stati WHERE idgruppo=5 ORDER BY ordine";
  $result = mysql_query($query) or die("Error_qs1");
  while ($line=mysql_fetch_assoc($result)) {
    print "<OPTION VALUE='".$line['valore']."' ".($cmd['filtra_stato']==$line['valore']?" SELECTED ":"").">".convlang($line['label'])."</OPTION>";
  }
  print "</SELECT></FORM>";
  
  print "</DIV>";
  //$lastcategoria=-1;
  $lastcategoria="AA";
  if (count($vetfornitore)>0 )  {
    print "<TABLE CLASS=lista_table ALIGN=CENTER>";
    //print "<TR><TD COLSPAN=5><TABLE STYLE='width:100%'>";    
    foreach ($vetfornitore as $key =>$cur) {
      $curcateg = $vetcategorie[$cur['dati']['idcategoria']]['txt'].($cur['dati']['categ2']!="" ? "-".$cur['dati']['categ2']:"");
      if ($lastcategoria!=$curcateg) {  //$cur['dati']['idcategoria']
        //$lastcategoria=$cur['dati']['idcategoria'];
        $lastcategoria = $curcateg;
        print "<TR><TD STYLE='border-bottom:2px solid #0000FF;' COLSPAN=3><BR><BR><BR><IMG SRC='../img/".$vetcategorie[$cur['dati']['idcategoria']]['img']."' ALT='".$curcateg."' TITLE='".$curcateg."'>&nbsp;<SPAN STYLE='font-size:24px;'>".$curcateg."</SPAN></TD></TR>";
        $lastchar="AA";                                      
      }
      $curprimocar = strtoupper(substr($cur['dati']['ragsoc'],0,1));
      if ($curprimocar!=$lastchar) {
        $lastchar=$curprimocar;
        print "<TR><TD STYLE='width:160px;'><SPAN STYLE='font-size:24px;'>[".$lastchar."]</SPAN></TD>";
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
          print "<IMG CLASS='manina' BORDER=0 SRC='../img/add_user.png' ALT='"._TIPADDUSERCONTATTO_."' TITLE='"._TIPADDUSERCONTATTO_."' onClick='popcliente(2,0);'></TD>";
        print "</TD></TR>";
        //print "</TABLE></TD></TR>";
        print "<TR><TD CLASS=lista_tittab>"._FORNITORE_."</TD><TD CLASS=lista_tittab>"._LBLNOMECONTATTO_."</TD><TD CLASS=lista_tittab>"._CONTATTO_."</TD><TD CLASS=lista_tittab>"._STATO_."</TD><TD CLASS=lista_tittab width='70'>&nbsp;</TD></TR>";
        $cur_rowstyle = "form2_lista_riga_pari";
      }
      
      if ($cur_rowstyle=="form2_lista_riga_pari") {
        $cur_rowstyle="form2_lista_riga_dispari";
        $stile = "background-color:#EDEDED;";//EDEDED   //F0FFEF
        $ancora_salto="";
      } else {
        $cur_rowstyle="form2_lista_riga_pari";
        $stile = "background-color:#F7F7F7;";//F7F7F7   //FEFFEF
        $ancora_salto="";
      }
      if ($key==$selobj) {
        $cur_rowstyle = "form2_lista_riga_evidente_selid";//F7F7F7   //FEFFEF
        $ancora_salto="<A NAME='SELOBJ'>&nbsp;</A>";
      }
      
      //print "<TR CLASS=lista_riga>";
      print "<TR CLASS='$cur_rowstyle'>";
    
      //tipo
      
      /*
      switch ($cur['dati']['fornitore_impresa']) {
        case 1://azienda
          $img = "industria2.png";$lbl=_LBLAZIENDALE_;break;
        case 0://individuale
        default:
          $img = "persone.png";$lbl=_LBLINDIVIDUALE_;break;
      }
      */
      print "<TD CLASS='lista_col_form2 risalto'>".$ancora_salto;
        print "<A NAME='blocco".$key."'></A><IMG SRC='../img/".$vetcategorie[$cur['dati']['idcategoria']]['img']."' ALIGN=MIDDLE>&nbsp;";
      
        print "[". $key ."] ".$cur['dati']['ragsoc'];    
      print "</TD>";
      print "<TD CLASS=lista_col_form2>".$cur['dati']['fornitore_nome']."</TD>";
      $appkey = $cur['contatto_predef'];
      if ($appkey!="") {
        print "<TD CLASS=lista_col_form2>".$cur['contatti'][$appkey]['nome']." (".$cur['contatti'][$appkey]['posizione'].")<BR>"._LBLSHORTTELWORK_.$cur['contatti'][$appkey]['tel_lavoro']." "._LBLSHORTTELPRI_.$cur['contatti'][$appkey]['tel_privato']." "._LBLSHORTFAX_.$cur['contatti'][$appkey]['fax']."<BR>"._LBLSHORTTELMOB_.$cur['contatti'][$appkey]['mobile']." "._LBLSHORTSKYPE_.$cur['contatti'][$appkey]['skype']."</TD>";
      } else {
        print "<TD CLASS=lista_col_form2>"._MSGNOPREDEF_."</TD>";
      }
      print "<TD CLASS=lista_col_form2>";
        if ($cur['dati']['fornitore_stato']==1) {
          $img = "ledgreen.png";
        } else {
          $img = "ledred.png";
        }
        
        //print "<TD CLASS=lista_col_form2 ALIGN=CENTER>".($line['docente_attivo']==1 ? "<A HREF='".FILE_DBQUERY."?cmd=". FASEDENY ."&codicepagina=". codiceform(FILE_CORRENTE) ."&selobj=". $line['docente_id']."'><IMG SRC='../img/ledgreen.png' BORDER=0 ALIGN=MIDDLE ALT='"._TXTDISABILITA_."' TITLE='"._TXTDISABILITA_."'></A>" : "<A HREF='".FILE_DBQUERY."?cmd=". FASEALLOW ."&codicepagina=". codiceform(FILE_CORRENTE) ."&selobj=". $line['docente_id']."'><IMG SRC='../img/ledred.png' BORDER=0 ALIGN=MIDDLE ALT='"._TXTABILITA_."' TITLE='"._TXTABILITA_."'></A>")."</TD>";
        print "<CENTER><A HREF='".FILE_DBQUERY."?cmd=".($cur['dati']['fornitore_stato']==1 ? FASEDENY : FASEALLOW)."&codicepagina=".codiceform(FILE_CORRENTE)."&selobj=".$key."'>";
        print "<IMG SRC='../img/$img' BORDER=0 CLASS=manina></A></CENTER>";
      print "</TD>";
      //COMANDI
      print "<TD CLASS=lista_col_form2>
        <TABLE width=100%><TR>
          <TD ALIGN=CENTER><IMG CLASS='manina' SRC='../img/mod.png' border=0 ALT='"._LBLMATITA_."' TITLE='"._LBLMATITA_."' onClick=popcliente(". FASEMOD .",". $key .")></TD>
          <TD ALIGN=CENTER><A HREF='#' onClick=urldelete(\"". FILE_DBQUERY ."?cmd=". FASEDEL ."&codicepagina=". codiceform(FILE_CORRENTE) ."&selobj=". $key ."\")><IMG SRC='../img/canc.png' border=0 ALT='"._LBLCANCELLA_."' TITLE='"._LBLCANCELLA_."'></A></TD>
          <TD ALIGN=CENTER><A HREF='#blocco$key' onClick=mostradett(\"dettrow_$key\")><IMG SRC='../img/lente.png' border=0 ALT='"._LBLLENTE_."' TITLE='"._LBLLENTE_."'></A></TD>";
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
          print "<TR><TD><IMG SRC='../img/".$img."' ALT='$lbl' TITLE='$lbl'></TD><TD CLASS=cust_nome>".$cur['dati']['ragsoc']."</TD>";              
          print "<TD>";
          //print "<SPAN STYLE='font-size:20px;'>".$cur['dati']['fornitore_apellido']." ".$cur['dati']['fornitore_nome']."</SPAN>";
          if ($cur['dati']['fornitore_nome']!="") {
            print "(".$cur['dati']['fornitore_nome'].")";
          } else {
            print "&nbsp;";
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
          print "Codice Sence: ".$cur['dati']['fornitore_codsence']."<BR>";
          print "Tel.Privato: ".$cur['dati']['tel_pri']." "."Tel.Lavoro: ".$cur['dati']['tel_lavoro']." "."Tel.Privato: ".$cur['dati']['tel_mobile']."<BR>";
          */
          
          print "<TR><TD CLASS=cust_lbl>"._AZIENDA_."</TD><TD CLASS=cust_lbl COLSPAN=2>"._LBLPOSIZIONE_."</TD></TR>";
          print "<TR><TD>".$cur['dati']['ragsoc']."</TD><TD>".$cur['dati']['posizione']."</TD></TR>";
          print "<TR><TD CLASS=cust_lbl>"._LBLADDRESSWORK_."</TD><TD COLSPAN=2>".$cur['dati']['addr_work']."</TD></TR>";
          /*
          print "<TR><TD CLASS=cust_lbl>RUT Azienda</TD><TD CLASS=cust_lbl>RUT Persona</TD><TD CLASS=cust_lbl>Fatturazione</TD></TR>";
          print "<TR><TD>".$cur['dati']['rut_ditta']."</TD><TD>".$cur['dati']['rut_persona']."</TD><TD>".($cur['dati']['fatturaz']==0 ? _LBLOPZFATTPRI_ : _LBLOPZFATTDITTA_)."</TD></TR>";
          if ($cur['dati']['fornitore_impresa']==0 && $cur['dati']['fatturaz']==1) {
            print "<TR><TD CLASS=cust_lbl>Azienda</TD><TD CLASS=cust_lbl>RUT Fatturazione</TD><TD CLASS=cust_lbl>GIRO Fatturazione</TD></TR>";
            print "<TR><TD>".$cur['dati']['fornitore_fatturazragsoc']."</TD><TD>".$cur['dati']['fornitore_fatturazrut']."</TD><TD>".$cur['dati']['fornitore_fatturazgiro']."</TD></TR>";
          }
          
          print "<TR><TD CLASS=cust_lbl>Legale rappresentante</TD><TD COLSPAN=2>".$cur['dati']['legale_nome']." (RUT: ".$cur['dati']['legale_rut'].")</TD></TR>";
          print "<TR><TD CLASS=cust_lbl>Segreteria</TD><TD COLSPAN=2>".$cur['dati']['seg_nome']." ".$cur['dati']['seg_mail']."</TD></TR>";
          */
          print "<TR><TD COLSPAN=3 CLASS=cust_lbl>"._CONTATTI_."</TD></TR>";
          print "<TR><TD CLASS=cust_lbl>"._LBLTELPRI_."</TD><TD CLASS=cust_lbl>"._LBLTELWORK_."</TD><TD CLASS=cust_lbl>"._LBLTELMOBILE_."</TD></TR>";
          print "<TR><TD>".$cur['dati']['tel_pri']."</TD><TD>".$cur['dati']['tel_lavoro']."</TD><TD>".$cur['dati']['tel_mobile']."</TD></TR>";
          
          print "<TR><TD CLASS=cust_lbl>"._LBLBANCA_."</TD><TD CLASS=cust_lbl>"._LBLTIPOCONTO_."</TD><TD CLASS=cust_lbl>"._LBLCONTO_."</TD></TR>";
          print "<TR><TD>".$cur['dati']['banca_nomebanca']."</TD><TD>".$cur['dati']['ccbanca_label']."</TD><TD>".$cur['dati']['ccbanca_codice']."</TD></TR>";

          print "<TR><TD CLASS=cust_lbl COLSPAN=2>"._LBLEMAIL_."</TD><TD CLASS=cust_lbl>"._LBLWEB_."</TD></TR>";
          print "<TR><TD><A HREF='mailto:".$cur['dati']['fornitore_mail1']."'>".$cur['dati']['fornitore_mail1']."</A></TD><TD><A HREF='mailto:".$cur['dati']['fornitore_mail2']."'>".$cur['dati']['fornitore_mail2']."</A></TD><TD><A HREF='http://".$cur['dati']['fornitore_web']."'>".$cur['dati']['fornitore_web']."</A></TD></TR>";
          print "<TR><TD COLSPAN=3 CLASS=cust_lbl>"._NOTES_."</TD></TR>";
          print "<TR><TD COLSPAN=3>".$cur['dati']['fornitore_note']."</TD></TR>";
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
                print "<A HREF='".FILE_DBQUERY."?selobj=$key2&idfornitore=$key&cmd=".FASEPREFERITO."&codicepagina=CONTATTIFORN'><IMG BORDER=0 SRC='../img/stellagray.png' ALT='NOPREFERITO' TITLE='NOPREFERITO'></A>";
              }
              print "&nbsp;";
              print "<IMG CLASS='manina' SRC='../img/mod.png' ALT='Modifica riga' TITLE='Modifica riga' onClick='popcontatto(3,$key2);'>";
              print "&nbsp;";
              print "<A HREF='#' onClick=urldelete(\"". FILE_DBQUERY ."?cmd=". FASEDEL ."&codicepagina=CONTATTIFORN&selobj=". $key2 ."&idfornitore=$key\")><IMG SRC='../img/canc.png' border=0 ALT='"._LBLCANCELLA_."' TITLE='"._LBLCANCELLA_."'></A>";
              print "&nbsp;";
              print $cur2['nome']."</TD>";
            print "<TD>".$cur2['posizione']."</TD></TR>";
            print "<TR><TD CLASS=cust_lbl>"._LBLTELWORK_."</TD><TD CLASS=cust_lbl>"._LBLTELMOBILE_."</TD><TD CLASS=cust_lbl>"._LBLTELPRI_."</TD></TR>";
            print "<TR><TD>".$cur2['tel_lavoro']."</TD><TD>".$cur2['mobile']."</TD><TD>".$cur2['tel_privato']."</TD></TR>";
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
    
    figlio = window.open("frm_contattiforn_popup.php?selobj="+id+"&cmd="+fase+"&idc="+idc,"fornitore_contatti",params);
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
    
    figlio = window.open("frm_fornitori_popup.php?selobj="+id+"&cmd="+fase,"fornitore_contatti",params);
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
