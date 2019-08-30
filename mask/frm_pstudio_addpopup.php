<?php
  $pagever = "1.0";
  $pagemod = "18/11/2010 22.59.15";
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
  define("FILE_CORRENTE", "frm_pstudio_addpopup.php");

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  $selobj = $_REQUEST['selobj'];
  
  
  if ($selobj!=-1) {
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
  }
  
  //insegna
  $query = "SELECT insegna.iddocente as iddocente, docente.nickname as nickname 
            FROM insegna INNER JOIN docente ON insegna.iddocente=docente.id AND docente.trashed<>1 
            WHERE idpstudi=".$selobj;
  $result = mysql_query($query) or die("Error_1.2$query");
  while ($line = mysql_fetch_assoc($result)) {
    $editval['insegna'][$line['iddocente']]['esiste']=1;
    $editval['insegna'][$line['iddocente']]['nickname']=$line['nickname'];
  }
  
  print "<BR><H1>"._CLASSIINSEGNAMENTO_."</H1>";
  
  
  print "<BR>";
  
  if ($cmd['comando']==FASEMOD) {
      $titolofinestra = _MODCLASSEINSEGNAMENTO_; $classtitolo = "headermod";
      //echo "XXX";
  } else {
      $cmd['comando']=FASEINS;
      $titolofinestra = _INSCLASSEINSEGNAMENTO_; $classtitolo = "headerins";
      //echo "<BR> xxcmd ".$cmd;
  }
  print "<FORM ACTION='". FILE_DBQUERY ."' METHOD='POST' NAME='caricamento' TARGET=inserimento><INPUT TYPE='hidden' NAME='cmd' VALUE='". $cmd['comando'] ."'><INPUT TYPE='hidden' NAME='selobj' VALUE='". $selobj ."'><INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'>";
	print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='50%'>";
  print "<tr><td class='headermod' colspan='3'><div class='header-text'>$titolofinestra</div></td></tr>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._CLASSE_."</TD>";
  print "<TD>";
  $query = "SELECT classi.id as classi_id, classi.descr as classi_descr, classi.idcustomer as classi_idcustomer,
              customer.nome as customer_nome
            FROM classi LEFT JOIN customer ON classi.idcustomer = customer.id AND customer.trashed<>1
            WHERE classi.trashed<>1 AND classi.attivo=1
            ORDER BY customer.nome, classi.descr";
  $result = mysql_query ($query) or die ("Error_1.2$query");
  print "<SELECT SIZE=1 NAME='idclasse'>";
  while ($line=mysql_fetch_assoc($result)) {
    print "<OPTION VALUE='".$line['classi_id']."' ".($editval['pstudi_idclasse']==$line['classi_id']? " SELECTED CLASS=option_selected ":"").">".($line['customer_nome']=="" ? _MSGNOCUSTOMER_ : $line['customer_nome'])."-".$line['classi_descr']."</OPTION>";
  }
  print "</SELECT></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._CODLIVCORSO_."</TD>";
  print "<TD>";
  $query = "SELECT id, descr, codlivello FROM corso WHERE trashed<>1 ORDER BY ordine";
  $result = mysql_query ($query) or die ("Error_1.3");
  print "<SELECT SIZE=1 NAME='idcorso'>";
  while ($line=mysql_fetch_assoc($result)) {
    print "<OPTION VALUE='".$line['id']."' ".($editval['pstudi_idcorso']==$line['id']? " SELECTED  CLASS=option_selected ":"").">".$line['descr']."</OPTION>";
  }
  print "</SELECT></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._DURATATOTALE_."</TD>";
  print "<TD><INPUT TYPE='text' size='20' NAME='durata' VALUE='". $editval['pstudi_durata'] ."'>
    <INPUT TYPE='button' NAME='durata20' VALUE=' 20 ' onClick=document.caricamento.durata.value=20;>&nbsp;
    <INPUT TYPE='button' NAME='durata30' VALUE=' 30 ' onClick=document.caricamento.durata.value=30;>&nbsp;
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
    if (count($editval['insegna'])<0) {
      print _LBLNODOCENTEASS_;
    }
    /*
    tolto 19/11
    $docass="-1, ";    
    if (count($editval['insegna'])>0) {
      foreach ($editval['insegna'] as $keyinsegna=>$curinsegna) {
        print "<TR><TD COLSPAN=3><A HREF='".FILE_DBQUERY."?cmd=".FASEREMDOCENTE."&codicepagina=".codiceform(FILE_CORRENTE)."&idpstudi=$selobj&iddocente=$keyinsegna'><IMG SRC='../img/canc.png' BORDER=0></A>".$curinsegna['nickname']."</TD></TR>";
        $docass .= "$keyinsegna, ";
      }      
    } else {
      print _LBLNODOCENTEASS_;
    }
    $docass = substr($docass, 0, -2);
    //$query = "SELECT id, nickname FROM docente WHERE trashed<>1 AND attivo=1 AND id NOT IN (".$docass.") ORDER BY nickname";
    */
    $query = "SELECT id, nickname FROM docente WHERE trashed<>1 AND attivo=1 ORDER BY nickname";
    $result = mysql_query ($query) or die ("Error_1.3");
    //print "<TR><TD><SELECT SIZE=1 NAME='iddocente'>";
    
    $i_doc=0;
    while ($line = mysql_fetch_assoc($result)) {
      if ($i_doc==0) {
        print "<TR>";
      }
      if (isset($editval['insegna'][$line['id']])) {
        //selezionato
        $sel_check=" CHECKED ";
        $sel_classe="txt_evidente";
      } else {
        $sel_check="";
        $sel_classe="";
      }
      
      print "<TD><INPUT NAME='iddocente[]' TYPE='checkbox' $sel_check VALUE='".$line['id']."'>"."<SPAN CLASS=$sel_classe>".$line['nickname']."</SPAN></TD>";
      $i_doc++;
      if ($i_doc==3) {
        print "</TR>";
        $i_doc=0;
      }
    }
    /*if ($cmd['comando']==FASEINS) {print "<OPTION VALUE=''>- - - - -</OPTION>";}
    while ($line=mysql_fetch_assoc($result)) {
      print "<OPTION VALUE='".$line['id']."'>".$line['nickname']."</OPTION>";
    }
    print "</SELECT></TD><TD>".($cmd['comando']==FASEINS ? "": "<IMG SRC='../img/piu.png' BORDER=0 onClick=insegna();>")."</TD>";
    */
  print "</TABLE></TD></TR>";
  
  print "<TR><TD COLSPAN=3 align='center' height='70'><INPUT TYPE='button' VALUE='"._BTNSALVA_."' onClick=btnsalva();>&nbsp;&nbsp;&nbsp;<INPUT TYPE='reset' VALUE='"._BTNRESET_."'></TD></TR>";
  print "</TABLE></FORM>";

  include ("finepagina.php");
  
  print "<FORM ACTION='". FILE_DBQUERY ."' METHOD='POST' NAME='modificaclasse'><INPUT TYPE='hidden' NAME='cmd' VALUE='". $cmd['comando'] ."'><INPUT TYPE='hidden' NAME='selobj' VALUE='". $selobj ."'><INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'>";
  print "<INPUT TYPE='hidden' NAME='idstudente' VALUE=''><INPUT TYPE='hidden' NAME='idpstudi' VALUE=''><INPUT TYPE='hidden' NAME='datai' VALUE=''><INPUT TYPE='hidden' NAME='dataf' VALUE=''></FORM>";
  
?>
<SCRIPT>

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
function btnsalva() {
  document.caricamento.submit();
  self.close();
}

</SCRIPT>
<BR>
<BR>
</body>
</html>
