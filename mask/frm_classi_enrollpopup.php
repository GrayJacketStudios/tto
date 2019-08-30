<?php
  $pagever = "1.0";
  $pagemod = "18/11/2010 9.31.18";
  require_once("form_cfg.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <link rel="stylesheet" type="text/css" href="../css/main.css" />
  <STYLE>
   .opt_individuale {
      background-color:#5FE4FF;
      text-align:center;
   }
   .opt_impresa {
      background-color:#C1FF5F;
      text-align:center;
   }
  </STYLE>
</head>
<body>
<?php
  include ("iniziopagina.php");

  //CARICAMENTO VALORI PER EDIT
  define("FILE_CORRENTE", "frm_classi_enrollpopup.php");

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  
  if ($cmd['comando']=="") {
    $cmd['comando']=FASECLASSE;
  }
  
  $selobj = $_REQUEST['selobj'];
  
  
  if ($selobj!=0) {
    $query = "SELECT enroll.idclasse as enroll_idclasse, enroll.idstudente as enroll_idstudente, enroll.dtini as dtini, enroll.dtfine as dtfine,
          enroll.frefer as frefer, studente.nome as studente_nome, customer.nome as customer_nome
          FROM enroll INNER JOIN studente ON enroll.idstudente=studente.id
          LEFT JOIN customer ON studente.idcustomer=customer.id AND customer.trashed<>1
          WHERE enroll.idclasse = ".$selobj." AND studente.trashed<>1 AND customer.stato=1
          ORDER BY customer.ragsoc, customer.impresa DESC, customer.nome, customer.apellido,  studente.nome";

    $result = mysql_query($query) or die ("Error_2.1");
    $studenti = array();
    $idstud = "-1, ";
    while ($line = mysql_fetch_assoc($result)) {
      $chiave = $line['enroll_idstudente'];
      $studenti[$chiave]['enroll_idclasse']=$line['enroll_idclasse'];
      $studenti[$chiave]['dtini']=$line['dtini'];
      $studenti[$chiave]['dtfine']=$line['dtfine'];
      $studenti[$chiave]['frefer']=$line['frefer'];
      $studenti[$chiave]['studente_nome']=$line['studente_nome'];
      $studenti[$chiave]['customer_nome']=$line['customer_nome'];
      
      $idstud .= $chiave.", ";      
    }
    
    //lista degli studenti non associati, raggruppati per customer e divisi in DIV
    $query = "SELECT studente.id as studente_id, studente.nome as studente_nome, customer.id as customer_id, 
              customer.nome as customer_nome, customer.apellido as customer_apellido, customer.ragsoc as customer_ragsoc,
              customer.fantasia as customer_fantasia, customer.impresa as customer_impresa
              FROM studente LEFT JOIN customer ON studente.idcustomer=customer.id AND customer.trashed<>1
              WHERE studente.id NOT IN (".substr($idstud,0,-2).") AND studente.trashed<>1  AND customer.stato=1
              ORDER BY customer.impresa, customer.ragsoc, customer.nome, customer.apellido,  studente.nome";
    $result = mysql_query($query) or die ("Error_2.2");
    $studentiNA = array();
    while ($line = mysql_fetch_assoc($result)) {
      if ($line['customer_impresa']==1) {
        //impresa
        $studentiNA[$line['customer_id']]['customer_nome']=$line['customer_ragsoc']." (".$line['customer_fantasia'].")";
      } else {
        //indiv
        $studentiNA[$line['customer_id']]['customer_nome']=$line['customer_nome']." ".$line['customer_apellido']." (".$line['customer_fantasia'].")";
      }
      $studentiNA[$line['customer_id']]['customer_impresa'] = $line['customer_impresa'];
      
      $studentiNA[$line['customer_id']]['studenti'][$line['studente_id']]['studente_nome']=$line['studente_nome'];
      if ($line['customer_nome']=="") {
        $studentiNA[$line['customer_id']]['customer_nome']=_MSGNOCUSTOMER_;
      }
    }

    
    print "<BR><H1>"._CLASSISTUDENTI_."</H1>";
    print "<BR>";
    //visualizza
    //iscritti
    print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='50%'>";
    print "<tr><td class='headermod' colspan='3'><div class='header-text'>"._ENROLLEDSTUD_."</div></td></tr>";
    $curstyle = RIGA_DISPARI;
    foreach ($studenti as $key =>$cur) {
      if ($cur['dtfine']!=0) {$delstyle = 'text-decoration: line-through;font-style: italic;';} else {$delstyle="";}
      print "<TR STYLE='background-color:$curstyle;'>";
      print "<TD STYLE='$delstyle'>".$cur['customer_nome']."</TD>";
      print "<TD STYLE='$delstyle'>".$cur['studente_nome']."</TD>";
      print "<TD STYLE='width:15%;'>";
        print "<TABLE width=100%><TR>";
        print "<TD ALIGN=CENTER><A HREF='#'  onClick=urldelete(\"". FILE_DBQUERY ."?cmd=". FASEDELSTUDENTE ."&codicepagina=". codiceform(FILE_CORRENTE) ."&selobj=". $key ."&idc=".$selobj."\")><IMG SRC='../img/canc.png' border=0 ALT='"._LBLCANCELLA_."'></A></TD>";
        print "<TD ALIGN=CENTER>";
        if ($cur['dtfine']==0) {print "<A HREF='". FILE_DBQUERY ."?cmd=". FASEREFERSTUDENTE ."&codicepagina=". codiceform(FILE_CORRENTE)  ."&selobj=". $key ."&idc=$selobj'>";}
        if ($cur['frefer']==1) {
          print "<IMG SRC='../img/stella.png' border=0 ALT='"._MSGREFERENTE_."' TITLE='"._MSGREFERENTE_."'>";
        } else {
          print "<IMG SRC='../img/stellagray.png' border=0>";
        }
        print "</A></TD>";
        //$askformhtml = "<FORM ACTION='".FILE_DBQUERY."' METHOD='POST'><INPUT TYPE='text' NAME='cmd' VALUE='".FASEFINESTUDENTE."'><INPUT TYPE='text' NAME='codicepagina' VALUE='".codiceform(FILE_CORRENTE)."'><INPUT TYPE='text' NAME='selobj' VALUE='".$key."'>2<INPUT TYPE='text' NAME='idc' VALUE='$selobj'><INPUT TYPE='text' NAME='datafine' VALUE='".date("d/m/Y",time())."'><INPUT TYPE='text' NAME='note' VALUE=''><INPUT TYPE='submit' NAME='invia' VALUE='invia'>";
        if ($cur['dtfine']==0) {
          print "<TD ALIGN=CENTER><A HREF='#' onClick=finestudente(\"". FILE_DBQUERY ."?cmd=". FASEFINESTUDENTE ."&codicepagina=". codiceform(FILE_CORRENTE) ."&selobj=". $key ."&idc=$selobj\")><IMG SRC='../img/studentedel.png' border=0 ALT='"._LBLTERMSTUDENTE_."' TITLE='"._LBLTERMSTUDENTE_."'></A></TD>";
        } else {
          print "<TD ALIGN=CENTER><A HREF='". FILE_DBQUERY ."?cmd=". FASERESTORESTUDENTE ."&codicepagina=". codiceform(FILE_CORRENTE) ."&selobj=". $key ."&idc=$selobj'><IMG SRC='../img/restorestudente.png' border=0 ALT='"._LBLRESTSTUDENTE_."' TITLE='"._LBLRESTSTUDENTE_."'></A></TD>";
        }
        
			print "</TR></TABLE>";
      print "</TD>";
      print "</TR>";
      if ($curstyle==RIGA_DISPARI) {$curstyle=RIGA_PARI;} else {$curstyle=RIGA_DISPARI;}
    }
    print "</TABLE><BR>";
    
    //non iscritti
    $out = "";
    $styleprimo="display: none;";
    
    $out .= "<FORM NAME='studentiNA' METHOD='POST' ACTION='".FILE_DBQUERY."' TARGET=inserimento>";
    $out .= "<INPUT TYPE='hidden' NAME='cmd' VALUE='". $cmd['comando'] ."'><INPUT TYPE='hidden' NAME='selobj' VALUE='". $selobj ."'><INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'>";
    
    
    foreach ($studentiNA as $key =>$cur) {
      $selcustomer[$key]['nome'] = $cur['customer_nome'];
      $selcustomer[$key]['tipo'] = $cur['customer_impresa'];
      
      
      //print $cur['customer_impresa']."<BR>";
      $out .= "<DIV NAME='stdNA_$key' ID='stdNA_$key' STYLE='$styleprimo'>";
      $out .= "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='30%'>";
      $out .= "<TR><TD COLSPAN=2 STYLE='text-align:center;'>".$cur['customer_nome']."</TD></TR>";
      $curstyle = RIGA_DISPARI;
      foreach ($cur['studenti'] as $key2 =>$cur2) {
        $out .= "<TR STYLE='background-color:$curstyle;'>";
        $out .= "<TD>".$cur2['studente_nome']."</TD>";
        $out .= "<TD STYLE='width:20%;'><INPUT TYPE='checkbox' NAME='enrollstudenti[]' value='$key2'></TD>";
        $out .= "</TR>";
        if ($curstyle==RIGA_DISPARI) {$curstyle=RIGA_PARI;} else {$curstyle=RIGA_DISPARI;}
      }
      $out .= "</TABLE>";
      $out .= "<BR></DIV>";
    }
    $out .= "</FORM>";
  
    print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='50%'>";
    print "<tr><td class='headermod' colspan='3'><div class='header-text'>"._ENROLLSTUD_."</div></td></tr>";
    print "<TR><TD CLASS='form_lbl'>"._LBLAZIENDA_.":</TD>";
    print "<TD><SELECT NAME='selcustomer' SIZE='1' onChange=mostrastudenti(this.value);>";
    $lasttipo = -999;
    foreach ($selcustomer as $keycustomer =>$curcustomer) {
      if ($lasttipo!=$curcustomer['tipo']) {
        switch ($curcustomer['tipo']) {
          case 0:
              print "<OPTION VALUE='NULL' CLASS='opt_individuale'>"._LBLOPTPERSONA_."</OPTION>";break;
          case 1:
              print "<OPTION VALUE='NULL' CLASS='opt_impresa'>"._LBLOPTIMPRESA_."</OPTION>";break;
        }
        $lasttipo = $curcustomer['tipo'];
      }
      print "<OPTION VALUE='$keycustomer' ".($keycustomer==$editval['classi_idcustomer'] ? " SELECTED CLASS=option_selected ":"").">".$curcustomer['nome']."</OPTION>";
    }
    print "</SELECT>";
    print "<INPUT TYPE='hidden' NAME='olddiv' id='olddiv' VALUE='".$editval['classi_idcustomer']."'></TD>";
    print "<TD STYLE='width:30%;text-align:center;'><INPUT TYPE='button' NAME='invia' VALUE='"._BTNENTROLLSTUD_."' onClick=studentisubmit();></TD>";
    print "</TR></TABLE>";
    print "<BR>";
    print $out;
    ?>
    <SCRIPT>
      var olddiv = document.getElementById("olddiv");
      if (olddiv.value!="") {
        bloccoShow = "stdNA_"+olddiv.value;
        var elem = document.getElementById(bloccoShow);
        elem.style.display="block";
      }
    </SCRIPT>
    
    <?
    print "<FORM NAME='dbq_direct' METHOD=POST></FORM>";
  }


  

  include ("finepagina.php");
?>
<SCRIPT>
function studentisubmit() {
  document.studentiNA.submit();
  self.close();
}

function mostrastudenti(curdiv) {
  var olddiv = document.getElementById("olddiv");
  
  bloccoHide = "stdNA_"+olddiv.value;
  bloccoShow = "stdNA_"+curdiv;
  
  var elem = document.getElementById(bloccoHide);
  if (elem!=null) {
    elem.style.display="none";
  }
  
  var elem = document.getElementById(bloccoShow);
  if (elem!=null) {
    elem.style.display="block";
  }
  
  olddiv.value=curdiv;
}

function askform1(selobj, idc) {
  alert(selobj);
  alert(idc);
  var datafine = prompt("Data termine iscrizione studente","<?=date("d/m/Y",time())?>");
  alert(datafine); 
}

function finestudente(url) {
  dt = prompt("<?=_MSGDATADELSTUDCLASSE_?>","<?=date("d/m/Y",time())?>");
  risp = confirm("<?=_MSGCONFFINESTUDCLASSE_?>");
  url = url + "&dtf=" + dt;
  if (risp) {
    document.dbq_direct.action=url;
    document.dbq_direct.submit();
  }
}


function btnsalva() {
  document.caricamento.submit();
  self.close();
}

</SCRIPT>
<BR>
<BR>
</body>
</html>
