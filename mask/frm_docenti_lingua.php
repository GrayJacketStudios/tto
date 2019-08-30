<?php
  $pagever = "1.0";
  $pagemod = "08/10/2010 20.18.07";
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
  define("FILE_CORRENTE", "frm_docenti_lingua.php");

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  $selobj = $_REQUEST['selobj'];
  
  $query = "SELECT id, nome, nickname FROM docente WHERE id=".$selobj;
  
  $result = mysql_query($query) or die ("Error_1.1");
  while ($line = mysql_fetch_assoc($result)) {
    $editval['id']=$line['id'];
    $editval['nome']=$line['nome'];
    $editval['nick']=$line['nick'];    
  }
  
  //lingue insegna
  $query = "SELECT docente.id as docente_id, lingua.codint as codint, lingua.descr as descr, img1,
            docente_insegna.data as data 
            FROM lingua INNER JOIN docente_insegna ON lingua.id=docente_insegna.idlingua
            INNER JOIN docente ON docente_insegna.iddocente=docente.id
            WHERE docente.id IN (".$selobj.")";
  
  $result = mysql_query($query) or die ("Error PRE 1.3");
  while ($line = mysql_fetch_assoc($result)) {
    $chiave = $line['docente_id'];
    $chiave2 = $line['codint'];
    
    $vet_docenti[$chiave]['insegna'][$chiave2]['codint']=$line['codint'];
    $vet_docenti[$chiave]['insegna'][$chiave2]['descr']=$line['descr'];
    $vet_docenti[$chiave]['insegna'][$chiave2]['img1']=$line['img1'];
    $vet_docenti[$chiave]['insegna'][$chiave2]['data']=$line['data'];
  }
  
  //lingue interpreta
  $query = "SELECT docente.id as docente_id, lingua_da.codint as da_codint, lingua_da.descr as da_descr, 
            lingua_da.img1 as da_img1, 
            lingua_a.codint as a_codint, lingua_a.descr as a_descr, lingua_a.img1 as a_img1,
            docente_interpreta.idlingua_da as idlingua_da, docente_interpreta.idlingua_a as idlingua_a 
            FROM lingua as lingua_da 
            INNER JOIN docente_interpreta ON lingua_da.id=docente_interpreta.idlingua_da
            INNER JOIN lingua as lingua_a ON docente_interpreta.idlingua_a=lingua_a.id
            INNER JOIN docente ON docente_interpreta.iddocente=docente.id
            WHERE docente.id IN (".$selobj.")";

  $result = mysql_query($query) or die ("Error PRE 1.4");
  while ($line = mysql_fetch_assoc($result)) {
    $chiave = $line['docente_id'];
    $chiave2 = $line['da_codint']."-".$line['a_codint'];
    
    $vet_docenti[$chiave]['interpreta'][$chiave2]['da_codint']=$line['da_codint'];
    $vet_docenti[$chiave]['interpreta'][$chiave2]['a_codint']=$line['a_codint'];
    $vet_docenti[$chiave]['interpreta'][$chiave2]['da_descr']=$line['da_descr'];
    $vet_docenti[$chiave]['interpreta'][$chiave2]['a_descr']=$line['a_descr'];
    $vet_docenti[$chiave]['interpreta'][$chiave2]['da_img1']=$line['da_img1'];
    $vet_docenti[$chiave]['interpreta'][$chiave2]['a_img1']=$line['a_img1'];
    $vet_docenti[$chiave]['interpreta'][$chiave2]['idlingua_da']=$line['idlingua_da'];
    $vet_docenti[$chiave]['interpreta'][$chiave2]['idlingua_a']=$line['idlingua_a'];
    
  }
  
  //lingue traduce
  $query = "SELECT docente.id as docente_id, lingua_da.codint as da_codint, lingua_da.descr as da_descr, lingua_da.img1 as da_img1, 
            lingua_a.codint as a_codint, lingua_a.descr as a_descr, lingua_a.img1 as a_img1,
            docente_traduce.idlingua_da as idlingua_da, docente_traduce.idlingua_a as idlingua_a 
            FROM lingua as lingua_da 
            INNER JOIN docente_traduce ON lingua_da.id=docente_traduce.idlingua_da
            INNER JOIN lingua as lingua_a ON docente_traduce.idlingua_a=lingua_a.id
            INNER JOIN docente ON docente_traduce.iddocente=docente.id
            WHERE docente.id IN (".$selobj.")";
  
  $result = mysql_query($query) or die ("Error PRE 1.5");
  while ($line = mysql_fetch_assoc($result)) {
    $chiave = $line['docente_id'];
    $chiave2 = $line['da_codint']."-".$line['a_codint'];
    
    $vet_docenti[$chiave]['traduce'][$chiave2]['da_codint']=$line['da_codint'];
    $vet_docenti[$chiave]['traduce'][$chiave2]['a_codint']=$line['a_codint'];
    $vet_docenti[$chiave]['traduce'][$chiave2]['da_descr']=$line['da_descr'];
    $vet_docenti[$chiave]['traduce'][$chiave2]['a_descr']=$line['a_descr'];
    $vet_docenti[$chiave]['traduce'][$chiave2]['da_img1']=$line['da_img1'];
    $vet_docenti[$chiave]['traduce'][$chiave2]['a_img1']=$line['a_img1'];
    $vet_docenti[$chiave]['traduce'][$chiave2]['idlingua_da']=$line['idlingua_da'];
    $vet_docenti[$chiave]['traduce'][$chiave2]['idlingua_a']=$line['idlingua_a'];
  }
  
  $query = "SELECT id, codint, descr, img1 FROM lingua ORDER BY descr";
  $result = mysql_query($query)or die ("Error PRE 1.6");
  while ($line = mysql_fetch_assoc($result)) {
    $chiave = $line['id'];
    
    $vetlingua[$chiave]['id']=$line['id'];
    $vetlingua[$chiave]['codint']=$line['codint'];
    $vetlingua[$chiave]['descr']=$line['descr'];
    $vetlingua[$chiave]['img1']=$line['img1'];
  }
  define ("_LBLAGGIORNAPAGINA_","Aggiornare la pagina per visualizzare le modifiche");

  print "<BR><H1>"._DOCENTELINGUA_."</H1>";
  
  print "<BR><CENTER><DIV id='boxupdate' CLASS='boxaggiornamento' STYLE='display:none;'><IMG SRC='../img/attenzione_32.png' ALIGN=MIDDLE><SPAN STYLE='vertical-align:middle;'>"._LBLAGGIORNAPAGINA_."</SPAN><IMG SRC='../img/attenzione_32.png' ALIGN=MIDDLE></DIV></CENTER>";
  
  print "<BR>";
  
  print "<DIV STYLE='text-align:right;margin-right:40px;'><INPUT TYPE='button' NAME='editlingua' VALUE='"._BTNEDITLINGUA_."' onClick=chiamalingue();></DIV><BR><BR>";
  
  
  print "<FORM ACTION='". FILE_DBQUERY ."' METHOD='POST' NAME='caricamento' TARGET=inserimento>
     <INPUT TYPE='hidden' NAME='cmd' VALUE='". FASESETTALINGUA ."'>
     <INPUT TYPE='hidden' NAME='selobj' VALUE='". $selobj ."'>
     <INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'>";
	
  //lingue
  print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='70%'>";
	print "<TR><TD COLSPAN=4>"._LBLLINGUEINSEGNATE_."</TD></TR>";
	
  $i=0;
  foreach ($vetlingua as $keylingua => $curlingua) {
    if ($i==0) {
      print "<TR>";
    }
    print "<TD>";
      print "<INPUT TYPE='checkbox' NAME='linguainsegna[]' VALUE='".$keylingua."' ".($vet_docenti[$selobj]['insegna'][$curlingua['codint']]['codint']!="" ? " CHECKED ":"")." >";
      print "<INPUT TYPE='hidden' NAME='data_".$keylingua."' VALUE='".($vet_docenti[$selobj]['insegna'][$curlingua['codint']]['codint']!="" ? $vet_docenti[$selobj]['insegna'][$curlingua['codint']]['data'] : "-2")."'>";
      print "<IMG SRC='../flag/".$curlingua['img1']."'> ".$curlingua['descr'];
    print "</TD>";
    $i++;
    if ($i==3) {
      print "</TR>";
      $i=0;
    }
  }
	print "<TR><TD COLSPAN=3 ALIGN=CENTER><INPUT TYPE='hidden' NAME='insegna_ne' VALUE='".count($vetlingua)."'>
  <INPUT TYPE='button' NAME='btn_salva2' VALUE='"._BTNSALVATUTTO_."' onClick=btnsalva()></TD></TR>";
	print "</TABLE>";
	
	print "<BR><BR><BR>";
	
  
  
  //traduzioni
  print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='70%'>";
	print "<TR><TD COLSPAN=4>"._LBLTRADUZIONI_."</TD></TR>";
	
	$i=0;
  foreach ($vet_docenti[$selobj]['traduce'] as $keylingua => $curlingua) {
    $i++;
    
    if ($cur_rowstyle=="form2_lista_riga_pari") {
      $cur_rowstyle="form2_lista_riga_dispari";
      $ancora_salto="";
    } else {
      $cur_rowstyle="form2_lista_riga_pari";
      $ancora_salto="";
    }
    
    $idriga = $curlingua['idlingua_da']."_".$curlingua['idlingua_a'];
    
    print "<TR CLASS='$cur_rowstyle' id='tr_tra_$i'>";
    print "<TD id='td1_tra_$i' CLASS=lista_col_form2><IMG SRC='../flag/".$curlingua['da_img1']."'> ".$curlingua['da_descr']."</TD>";
    
    print "<TD CLASS=lista_col_form2><IMG SRC='../img/next.png'></TD>";
    
    print "<TD id='td2_tra_$i' CLASS=lista_col_form2><IMG SRC='../flag/".$curlingua['a_img1']."'> ".$curlingua['a_descr']."</TD>";
    
    print "<TD CLASS=lista_col_form2><IMG SRC='../img/canc.png' BORDER=0 onClick=cancella(\"$i\",\"tra\")>
    <INPUT TYPE='hidden' NAME='canc_tra_$i' VALUE='0'><INPUT TYPE='hidden' NAME='canc_tra_".$i."_da' VALUE='".$curlingua['idlingua_da']."'><INPUT TYPE='hidden' NAME='canc_tra_".$i."_a' VALUE='".$curlingua['idlingua_a']."'></TD>";
    print "</TR>";
    
  }
  
  if ($cur_rowstyle=="form2_lista_riga_pari") {
    $cur_rowstyle="form2_lista_riga_dispari";
    $ancora_salto="";
  } else {
    $cur_rowstyle="form2_lista_riga_pari";
    $ancora_salto="";
  }
  print "<TR CLASS='$cur_rowstyle'>";
  print "<TD CLASS=lista_col_form2><INPUT TYPE='hidden' NAME='canc_tra_ne' VALUE='$i'>
  <SELECT NAME='new_traduce_da' SIZE=1><OPTION VALUE=''>- - - - - - - </OPTION>";
    foreach ($vetlingua as $keylingua => $curlingua) {
      print "<OPTION VALUE='$keylingua'>".$curlingua['descr']."</OPTION>";
    }
  print "</SELECT></TD>";
  
  print "<TD CLASS=lista_col_form2><IMG SRC='../img/next.png'></TD>";
  
  print "<TD CLASS=lista_col_form2><SELECT NAME='new_traduce_a' SIZE=1><OPTION VALUE=''>- - - - - - - </OPTION>";
    foreach ($vetlingua as $keylingua => $curlingua) {
      print "<OPTION VALUE='$keylingua'>".$curlingua['descr']."</OPTION>";
    }
  print "</SELECT></TD>";
  print "<TD CLASS=lista_col_form2>"._LBLNUOVO_."</TD>";
  print "</TR>";
  
  print "<TR><TD COLSPAN=4 ALIGN=CENTER><INPUT TYPE='button' NAME='btn_salva2' VALUE='"._BTNSALVATUTTO_."' onClick=btnsalva()></TD></TR>";
	print "</TABLE>";
	
	print "<BR><BR><BR>";
	
  
  //interprete
  print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='70%'>";
	
	print "<TR><TD COLSPAN=4>"._LBLINTERPRETE_."</TD></TR>";
	$i=0;
  foreach ($vet_docenti[$selobj]['interpreta'] as $keylingua => $curlingua) {
    $i++;
    
    if ($cur_rowstyle=="form2_lista_riga_pari") {
      $cur_rowstyle="form2_lista_riga_dispari";
      $ancora_salto="";
    } else {
      $cur_rowstyle="form2_lista_riga_pari";
      $ancora_salto="";
    }
    
    $idriga = $curlingua['idlingua_da']."_".$curlingua['idlingua_a'];
    
    print "<TR CLASS='$cur_rowstyle' id='tr_int_$i'>";
    print "<TD id='td1_int_$i' CLASS=lista_col_form2><IMG SRC='../flag/".$curlingua['da_img1']."'> ".$curlingua['da_descr']."</TD>";
    
    print "<TD CLASS=lista_col_form2><IMG SRC='../img/next.png'></TD>";
    
    print "<TD id='td2_int_$i' CLASS=lista_col_form2><IMG SRC='../flag/".$curlingua['a_img1']."'> ".$curlingua['a_descr']."</TD>";
    
    print "<TD CLASS=lista_col_form2><IMG SRC='../img/canc.png' BORDER=0 onClick=cancella(\"$i\",\"int\")>
    <INPUT TYPE='hidden' NAME='canc_int_$i' VALUE='0'><INPUT TYPE='hidden' NAME='canc_int_".$i."_da' VALUE='".$curlingua['idlingua_da']."'><INPUT TYPE='hidden' NAME='canc_int_".$i."_a' VALUE='".$curlingua['idlingua_a']."'></TD>";
    print "</TR>";
    
  }
  if ($cur_rowstyle=="form2_lista_riga_pari") {
    $cur_rowstyle="form2_lista_riga_dispari";
    $ancora_salto="";
  } else {
    $cur_rowstyle="form2_lista_riga_pari";
    $ancora_salto="";
  }
  
  print "<TR CLASS='$cur_rowstyle'>";
  print "<TD CLASS=lista_col_form2><INPUT TYPE='hidden' NAME='canc_int_ne' VALUE='$i'>
  <SELECT NAME='new_interpreta_da' SIZE=1><OPTION VALUE=''>- - - - - - - </OPTION>";
    foreach ($vetlingua as $keylingua => $curlingua) {
      print "<OPTION VALUE='$keylingua'>".$curlingua['descr']."</OPTION>";
    }
  print "</SELECT></TD>";
  
  print "<TD CLASS=lista_col_form2><IMG SRC='../img/next.png'></TD>";
  
  print "<TD CLASS=lista_col_form2><SELECT NAME='new_interpreta_a' SIZE=1><OPTION VALUE=''>- - - - - - - </OPTION>";
    foreach ($vetlingua as $keylingua => $curlingua) {
      print "<OPTION VALUE='$keylingua'>".$curlingua['descr']."</OPTION>";
    }
  print "</SELECT></TD>";
  
  print "<TD CLASS=lista_col_form2>"._LBLNUOVO_."</TD>";
  print "</TR>";
  
  print "<TR><TD COLSPAN=4 ALIGN=CENTER><INPUT TYPE='button' NAME='btn_salva3' VALUE='"._BTNSALVATUTTO_."' onClick=btnsalva()></TD></TR>";
  
	print "</TABLE>";
	
	/*
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLMEZZO_."</TD>";
  print "<TD><IMG SRC='../img/email.png'>&nbsp;<input type='radio' name='mezzo' value='1' checked>&nbsp;&nbsp;<IMG SRC='../img/tel.png'>&nbsp;<input type='radio' name='mezzo' value='2'>&nbsp;&nbsp;<IMG SRC='../img/colloquio.png'>&nbsp;<input type='radio' name='mezzo' value='3'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLNOTE_."</TD>";
  print "<TD><TEXTAREA NAME='msg' ROWS=6 COLS=50></TEXTAREA></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR><TD COLSPAN=3 align='center' height='70'><INPUT TYPE='button' VALUE='"._BTNSALVA_."' onClick=btnsalva();>&nbsp;&nbsp;&nbsp;<INPUT TYPE='reset' VALUE='"._BTNRESET_."'></TD></TR>";
  print "</TABLE></FORM>";
  
  print "<BR>";
  print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='70%' STYLE='background-color: #FFD179;color:#000000;'>";
  
  foreach ($log as $key=>$cur) {
    print "<TR>";
    print "<TD>".date("d/m/Y H:i",$cur['times'])."<BR>".$cur['user_nome']."</TD>";
    
    switch ($cur['mezzo']) {
      case 1:
            $img = "email.png";
            break;
      case 2:
            $img = "tel.png";
            break;
      case 3:
            $img = "colloquio.png";
            break;
    }
    print "<TD><IMG SRC='../img/$img'></TD>";
    print "<TD WIDTH=60%>".conv_textarea($cur['msg'])."</TD>";
    print "</TR>";
    print "<TR><TD COLSPAN=3><HR SIZE=1></TD></TR>";
  }
  print "</TABLE>";
  */
  include ("finepagina.php");
?>
<SCRIPT>
function btnsalva() {
  document.caricamento.submit();
  self.close();
}

function actlingua(chiave) {
  var app;
  
  app=parseInt(document.caricamento['azione_'+chiave].value, 10);
  app = app * -1;
  document.caricamento['azione_'+chiave].value=app.toString();
}

function cancella(chiave,sezione) {
  
  if (document.caricamento['canc_'+sezione+'_'+chiave].value==0) {
    document.caricamento['canc_'+sezione+'_'+chiave].value=1;
  } else {
    document.caricamento['canc_'+sezione+'_'+chiave].value=0;
  }
  
  var elem = document.getElementById("td1_"+sezione+'_'+chiave);  
    if (document.caricamento['canc_'+sezione+'_'+chiave].value==1) {
      elem.style.textDecoration = "line-through";
    } else {
      elem.style.textDecoration = "none";
    }
  
  var elem = document.getElementById("td2_"+sezione+'_'+chiave);  
    if (document.caricamento['canc_'+sezione+'_'+chiave].value==1) {
      elem.style.textDecoration = "line-through";
    } else {
      elem.style.textDecoration = "none";
    }
}

function chiamalingue() {
    var figlio;
    var width  = 750;
    var height = 350;
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
    
    var elem = document.getElementById("boxupdate");
    elem.style.display="";
    
    figlio = window.open("frm_lingua.php","lingua",params);
    if (figlio.opener==null) {
      figlio.opener=self;
      figlio.frames["saveopener"].opener=self;
    }
  }
</SCRIPT>
</body>
</html>
