<?php
  $pagever = "1.0";
  $pagemod = "29/06/2010 16.43.31";
  require_once("form_cfg.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <link rel="stylesheet" type="text/css" href="../css/main.css" />
  <STYLE>
   
  </STYLE>
</head>
<body>
<?php
  include ("iniziopagina.php");

  //CARICAMENTO VALORI PER EDIT
  define("FILE_CORRENTE", "frm_studenti.php");

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
  
  $cmd['csv'] = $_REQUEST['csv'];
  
  include ("form_filter.php");
  
  $cmd['form_rowcount'] = $setup['form_rowcount'];
  $cmd['form_rowcount'] = 1000;
  

  $query_select = "SELECT studente.id as id, idcustomer, studente.iduser as iduser, studente.nome as nome, 
            studente.nome2 as nome2, studente.cognome as cognome, studente.cognome2 as cognome2,
            studente.foto as foto, studente.rut as rut, studente.giro as giro, studente.indirizzo as indirizzo,
            studente.num as num, studente.numappartamento as numappartamento, studente.comune as comune,
            studente.tel as tel, studente.mobile as mobile, studente.fax as fax, studente.email as email, studente.email2 as email2, 
            studente.attivo as attivo, customer.nome as customer_nome, user.username as user_username, user.attivo as user_attivo,
            customer.apellido as customer_apellido ";
            
  $query =  "FROM studente LEFT JOIN customer ON studente.idcustomer=customer.id AND customer.trashed<>1
            LEFT JOIN user ON studente.iduser=user.id
            WHERE studente.trashed<>1";
  
  if ($cmd['filtra_stato']!="-1") {
    $query .= " AND studente.attivo=".$cmd['filtra_stato']." ";
  }
  
  if ($cmd['src1']!="") {
    $query .= " AND (studente.nome like '%".$cmd['src1']."%' OR studente.rut like '%".$cmd['src1']."%' OR studente.giro like '%".$cmd['src1']."%' OR 
                     studente.indirizzo like '%".$cmd['src1']."%' OR studente.comune like '%".$cmd['src1']."%' OR studente.tel like '%".$cmd['src1']."%' OR 
                     studente.mobile like '%".$cmd['src1']."%' OR studente.fax like '%".$cmd['src1']."%' OR studente.email like '%".$cmd['src1']."%' OR 
                     studente.email2 like '%".$cmd['src1']."%' )";
  } else {
    resetFormSessionFilter();
  }
  
  switch ($cmd['form_sort']) {
    case 1:
    default:
            //$query .= " ORDER BY studente.nome, customer.apellido, customer.ragsoc, customer.impresa DESC ";
            $query .= " ORDER BY customer.ragsoc, customer.impresa DESC, customer.nome, customer.apellido, studente.nome";
            break;
  }
  
  $result = mysql_query("SELECT count(*) as conto ".$query);
  $line=mysql_fetch_assoc($result);
  $query_tot_row=$line['conto'];
  mysql_free_result($result);
  $query = $query_select . $query;

  $query .= " LIMIT ".$cmd['form_limit'].", ".$cmd['form_rowcount'];
  $result = mysql_query($query) or die ("Error_1.1 $query");
  
  $vetdati=array();
  while ($line = mysql_fetch_assoc($result)) {
    $chiave = $line['id'];
    $vetdati[$chiave]['idcustomer']=$line['idcustomer'];
    $vetdati[$chiave]['iduser']=$line['iduser'];
    $vetdati[$chiave]['nome']=$line['nome'];
    $vetdati[$chiave]['nome2']=$line['nome2'];
    $vetdati[$chiave]['cognome']=$line['cognome'];
    $vetdati[$chiave]['cognome2']=$line['cognome2'];
    $vetdati[$chiave]['rut']=$line['rut'];
    $vetdati[$chiave]['giro']=$line['giro'];
    $vetdati[$chiave]['indirizzo']=$line['indirizzo'];
    $vetdati[$chiave]['num']=$line['num'];
    $vetdati[$chiave]['numappartamento']=$line['numappartamento'];
    $vetdati[$chiave]['comune']=$line['comune'];
    $vetdati[$chiave]['tel']=$line['tel'];
    $vetdati[$chiave]['mobile']=$line['mobile'];
    $vetdati[$chiave]['fax']=$line['fax'];
    $vetdati[$chiave]['email']=$line['email'];
    $vetdati[$chiave]['email2']=$line['email2'];
    $vetdati[$chiave]['customer_nome']=$line['customer_nome'];
    $vetdati[$chiave]['customer_apellido']=$line['customer_apellido'];
    $vetdati[$chiave]['attivo']=$line['attivo'];

}

  if ($cmd['csv']==1) {
    //salva file csv
  $tmpfname = tempnam("/tto/upload", "TTO").".txt";
    $name = basename($tmpfname);
    $strriga2 = _LBLEMAIL1_."\n";
    foreach ($vetdati as $keycsv => $curcsv) {
      $strriga2 .= $curcsv['email'].($curcsv['email']==""?"":"\n");
      $strriga2 .= $curcsv['email2'].($curcsv['email2']==""?"":","); 
    }
 file_put_contents("upload/".$name,$strriga2);
$ch = "upload/download.php?nom=".$name;
header('Location: '.$ch);
  }
   
  print "<BR><H1>"._TITSTUDENTE_."</H1>";
  
  print "<DIV STYLE='text-align:right;'>";
  
  //CSV
    print "<INPUT TYPE='button' VALUE=' CSV ' NAME='gocsv' onClick=gocsv();>&nbsp;&nbsp;";
  
  print "<FORM NAME='fstato' METHOD='POST' ACTION='".FILE_CORRENTE."'><INPUT TYPE='hidden' NAME='csv' VALUE=''><INPUT TYPE='hidden' NAME='cmd' VALUE='".FASESEARCH."'>";
  print _LBLFILTRA_.": <SELECT NAME='filtra_stato' SIZE=1 onChange=document.fstato.submit();><OPTION VALUE='-1'>"._LBLOPTTUTTI_."</OPTION>";
  
  $query = "SELECT valore, label FROM stati WHERE idgruppo=6 ORDER BY ordine";
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
      print "<IMG CLASS='manina' BORDER=0 SRC='../img/add_user.png' ALT='"._TIPADDUSERCONTATTO_."' TITLE='"._TIPADDUSERCONTATTO_."' onClick='chiamapopup(2,0);'></TD>";
    print "</TD>";
  print "</TR>";
  print "</TABLE>";
  
  if (mysql_num_rows($result)!=0 )  {
    echo "<TABLE CLASS=lista_table ALIGN=CENTER>";
    echo "<TR><TD CLASS=lista_tittab>"._STUDENTE_."</TD><TD CLASS=lista_tittab>"._INDIRIZZO_."</TD><TD CLASS=lista_tittab>"._CONTATTI_."</TD><TD CLASS=lista_tittab>"._EMAIL_."</TD><TD CLASS=lista_tittab>"._STATO_."</TD><TD CLASS=lista_tittab width='70'>&nbsp;</TD></TR>";
    //while ($line=mysql_fetch_assoc($result))
    foreach ($vetdati as $key=>$cur) {
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
      print "<TD CLASS=lista_col_form2>[". $key ."] ".$cur['nome'].($cur['idcustomer']!="" ? "<BR>(".$cur['customer_nome']." ".$cur['customer_apellido'].")" : "(- - - - -)")."</TD>";
      print "<TD CLASS=lista_col_form2>".$cur['indirizzo']." ".$line['comune']."<BR>".$cur['num']."</TD>";
      print "<TD CLASS=lista_col_form2>"._LBLTEL_." ".conv_textarea($cur['tel'],0)."<BR>"._LBLMOBILE_." ".$cur['mobile']."<BR>"._LBLFAX_." ".$cur['fax']."</TD>";
      print "<TD CLASS=lista_col_form2><A HREF='mailto:".$cur['email']."'>".$cur['email']."</A><BR><A HREF='mailto:".$cur['email2']."'>".$cur['email2']."</A></TD>";
      print "<TD CLASS=lista_col_form2 ALIGN=CENTER>".($cur['attivo']==1 ? "<A HREF='".FILE_DBQUERY."?cmd=". FASEDENY ."&codicepagina=". codiceform(FILE_CORRENTE) ."&selobj=". $key."'><IMG SRC='../img/ledgreen.png' BORDER=0 ALIGN=MIDDLE ALT='"._TXTDISABILITA_."' TITLE='"._TXTDISABILITA_."'></A>" : "<A HREF='".FILE_DBQUERY."?cmd=". FASEALLOW ."&codicepagina=". codiceform(FILE_CORRENTE) ."&selobj=". $key."'><IMG SRC='../img/ledred.png' BORDER=0 ALIGN=MIDDLE ALT='"._TXTABILITA_."' TITLE='"._TXTABILITA_."'></A>")."</TD>";
      //COMANDI
      print "<TD CLASS=lista_col_form2><TABLE width=100%><TR>
      <TD ALIGN=CENTER><IMG CLASS='manina' SRC='../img/mod.png' border=0 ALT='"._LBLMATITA_."' TITLE='"._LBLMATITA_."' onClick=chiamapopup(". FASEMOD .",". $key .")></TD>
      <TD ALIGN=CENTER><A HREF='#' onClick=urldelete(\"". FILE_DBQUERY ."?cmd=". FASEDEL ."&codicepagina=". codiceform(FILE_CORRENTE) ."&selobj=". $key ."\")><IMG SRC='../img/canc.png' border=0 ALT='"._LBLCANCELLA_."' TITLE='"._LBLCANCELLA_."'></A></TD>";
			print "</TR></TABLE></TD>";
      print "</TR>";
        
      
    }
       
    echo "</TABLE><BR><BR>";
  }
  
  include ("finepagina.php");
?>
<SCRIPT>
function gocsv() {
    document.fstato.csv.value="1";
    document.fstato.submit();
  }
  
function popola_dacustomer(valore) {
  vet_direccion = new Array();
  vet_comuna = new Array();
  <?=$vetjs?>
  document.caricamento.indirizzo.value=vet_direccion[valore];
  document.caricamento.comune.value=vet_comuna[valore];
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
    
    figlio = window.open("frm_studenti_popup.php?selobj="+id+"&cmd="+fase,"studenti",params);
    if (figlio.opener==null) {
      figlio.opener=self;
      figlio.frames["saveopener"].opener=self;
    }
  }


//set iniziale
popola_dacustomer(document.caricamento.idcustomer.value);
</SCRIPT>
</body>
</html>
