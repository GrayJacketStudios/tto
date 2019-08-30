<?php
  $pagever = "1.0";
  $pagemod = "23/01/2011 11.27.14";
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
   .tr_evid:hover {
    background-color:red;
   }
  </STYLE>
</head>
<body>
<?php
  include ("iniziopagina.php");

  //CARICAMENTO VALORI PER EDIT
  define("FILE_CORRENTE", "frm_contab_corsipopup.php");

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  

  $selobj = $_REQUEST['selobj'];
  $cmd['src1'] = $_REQUEST['src1'];
  $cmd['tipocorso'] = $_REQUEST['tipocorso'];
  $cmd['filtro_cliente'] = trim($_REQUEST['flc']);
  
  
  //preload cliente
  if ($cmd['filtro_cliente']!="") {
    $query = "SELECT customer.id as customer_id, customer.nome as customer_nome, customer.apellido as customer_apellido,
               customer.ragsoc as ragsoc
              FROM customer
              WHERE customer.id=".$cmd['filtro_cliente'];
    $result = mysql_query($query) or die("Errore 3.1");
    $filtroflc = mysql_fetch_assoc($result);
  }
  
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


  $query = "SELECT pstudi.id as pstudi_id, pstudi.idcorso as pstudi_idcorso, pstudi.descr as pstudi_descr, pstudi.datai as pstudi_datai,
            pstudi.dataf as pstudi_dataf, pstudi.compenso as pstudi_compenso, pstudi.durata as pstudi_durata, pstudi.dlezione as pstudi_dlezione,
            pstudi.codsense as pstudi_codsense, pstudi.codotec as pstudi_codotec, pstudi.attivita as pstudi_attivita, pstudi.luogo as pstudi_luogo,
            corso.descr as corso_descr, corso.codlivello as corso_codlivello, pstudi.attivo as pstudi_attivo, pstudi.style as pstudi_style,
            pstudi.idclasse as pstudi_idclasse, classi.descr as classi_descr, classi.idcustomer as classi_idcustomer,
            customer.nome as customer_nome, pstudi.trasporto as pstudi_trasporto, customer.ragsoc as customer_ragsoc,
            customer.fantasia as customer_fantasia, customer.impresa as customer_impresa, customer.apellido as customer_apellido";
            
  $query .= " FROM pstudi INNER JOIN corso ON pstudi.idcorso=corso.id
              LEFT JOIN classi ON pstudi.idclasse=classi.id AND classi.trashed<>1
              LEFT JOIN customer ON classi.idcustomer=customer.id
              
              WHERE pstudi.trashed<>1";
  
  if ($cmd['filtro_cliente']!="") {
    $query .= " AND customer.id=".$cmd['filtro_cliente'];
  }
  
  if ($cmd['tipocorso']!="") {
    $query .= " AND pstudi.idcorso=".$cmd['tipocorso'];
  }
  
  
  $query .= " AND (pstudi.descr like '%".$cmd['src1']."%' OR pstudi.codsense like '%".$cmd['src1']."%' OR customer.nome like '%".$cmd['src1']."%' OR customer.apellido like '%".$cmd['src1']."%' OR customer.ragsoc like '%".$cmd['src1']."%' OR customer.fantasia like '%".$cmd['src1']."%')";
  
  $query .= " ORDER BY customer.ragsoc, customer.impresa DESC, customer.nome, customer.apellido";
  
  $result = mysql_query($query) or die ("Error_1.1");
  
  //Porchetta per far visualizzare il filtro per cliente. SRC1 vienta il nome del cliente cercato
  $cmd['src1'] = $filtroflc['customer_nome']." ".$filtroflc['customer_apellido']." ".$filtroflc['ragsoc'];
  
  $str_idpst="";
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
  
      
    print "<BR><H1>"._CORSI_."</H1>";
    print "<BR>";
    
    //form x search
    print "<FORM NAME='ricerca' METHOD=POST ACTION='".FILE_CORRENTE."'><INPUT TYPE='hidden' NAME='selobj' VALUE='".$selobj."'>";
    print "<INPUT TYPE='hidden' NAME='cmd' VALUE='".FASESEARCH."'>";
    
    print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='80%'>";
    
    //select tutti, cli, forn
    //src1
    //lista
    
    print "<TR>";
    print "<TD>_LBLTIPOCORSO_</TD>";
    print "<TD>";
    print "<SELECT SIZE=1 NAME='tipocorso' onChange=updlista()>";
    print "<OPTION VALUE=''>- - - - -</OPTION>";
  
      $query = "SELECT id, codlivello FROM corso WHERE trashed<>1 AND attivo=1 ORDER BY ordine";
      $result = mysql_query ($query) or die ("Error_2.1");
      while ($line = mysql_fetch_assoc($result)) {
        print "<OPTION VALUE='".$line['id']."' ".($line['id']==$cmd['tipocorso'] ? " SELECTED ":"").">".$line['codlivello']."</OPTION>";
      }
      print "</SELECT>";
    print "</TD>";
    print "</TR>";
    
    print "<TR>";
    print "<TD>_LBLRICERCA_</TD>";
    print "<TD>";
    print "<INPUT TYPE='text' SIZE='30' NAME='src1' VALUE='".$cmd['src1']."'>";
    print "<IMG SRC='../img/ricerca.png' BORDER=0 onClick=updlista();>&nbsp;<IMG SRC='../img/gomma.png' onClick=gommaricerca();>";
    print "</TD>";
    print "</TR>";
    
    print "<TR><TD COLSPAN=2>";
    print "<TABLE CELLSPACING=0 CELLPADDING=0 WIDTH=100%>";
    
    
    foreach ($pstudio as $key =>$cur) {
      
      //normalizzazione label
      $applabel = "[".$key."] ".$cur['customer_nome']." ".$cur['customer_apellido']." ".$cur['customer_ragsoc']."<BR>".$cur['classi_descr']." (".$cur['corso_codlivello'].")";
    
      $retlabel = str_replace("<BR>", "", $applabel);
    
      print "<TR CLASS=tr_evid onClick=selelemento(".$key.",\"".fullescape($retlabel)."\")>";
      print "<TD><IMG SRC='../img/next.png'></TD>";
      print "<TD>";
        if ($cur['customer_impresa']==1) {
          //impresa
          $img = "industria20.png";
        } else {
          $img = "persone20.png";
        }
        print "<IMG SRC='../img/$img' BORDER=0>";
      print "</TD>";
      print "<TD>".$applabel."</TD>";
      print "</TR>";
    }
    
    print "</TABLE></TD></TR>";
    print "</TABLE>";
    print "</FORM>";


/*
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
  
  }

*/
  

  include ("finepagina.php");
?>
<SCRIPT>
function updlista() {
  document.ricerca.submit();
}

function gommaricerca() {
  document.ricerca.src1.value="";
  updlista();
}

function selelemento(idext, prodotto_label)  {
      opener.document.caricamento.idext.value=idext;
      opener.document.caricamento.prodotto_label.value=unescape(prodotto_label);
      
      this.close();
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
