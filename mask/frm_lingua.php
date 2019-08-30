<?php
  $pagever = "1.0";
  $pagemod = "10/03/2011 23.42.41";
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
  define("FILE_CORRENTE", "frm_lingua.php");

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  $selobj = $_REQUEST['selobj'];
  
  $query = "SELECT id, codint, descr, img1 FROM lingua ORDER BY codint";
  
  $result = mysql_query($query) or die ("Error_1.1");
  while ($line = mysql_fetch_assoc($result)) {
    $chiave = $line['id'];
    
    $vetlingue[$chiave]['codint']=$line['codint'];
    $vetlingue[$chiave]['descr']=$line['descr'];
    $vetlingue[$chiave]['img1']=$line['img1'];    
  }
  
  
  

  print "<BR><H1>"._TITGESTLINGUA_."</H1>";
  
  
  print "<BR>";
  
  
  print "<FORM ACTION='". FILE_DBQUERY ."' METHOD='POST' NAME='caricamento'  enctype='multipart/form-data'>
     <INPUT TYPE='hidden' NAME='cmd' VALUE='". FASEMOD ."'>
     <INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'>";
	
  //lingue
  print "<TABLE CLASS=lista_table ALIGN=CENTER>";
  print "<TR><TD CLASS=lista_tittab>"._LBLCODINT_."</TD><TD CLASS=lista_tittab>"._LBLDESCR_."</TD><TD CLASS=lista_tittab COLSPAN=2>"._LBLIMMAGINE_."</TD></TR>";
  $i=0;
  foreach ($vetlingue as $key=>$cur) {
    if ($cur_rowstyle=="form2_lista_riga_pari") {
      $cur_rowstyle="form2_lista_riga_dispari";
      $stile = "background-color:#EDEDED;";//EDEDED   //F0FFEF
      $ancora_salto="";
    } else {
      $cur_rowstyle="form2_lista_riga_pari";
      $stile = "background-color:#F7F7F7;";//F7F7F7   //FEFFEF
      $ancora_salto="";
    }
    
    $i++;
    print "<TR CLASS='$cur_rowstyle'>";
    print "<TD><INPUT TYPE='hidden' NAME='id_".$i."' VALUE='".$key."'><INPUT TYPE='text' SIZE=6 NAME='codint_".$i."' VALUE='".$cur['codint']."'></TD>";
    print "<TD><INPUT TYPE='text' SIZE=30 NAME='descr_".$i."' VALUE='".$cur['descr']."'></TD>";
    print "<TD><IMG SRC='../img/canc.png' BORDER=0 CLASS='manina' onClick=cancella(".$key.")>&nbsp;&nbsp;<IMG SRC='../flag/".$cur['img1']."' BORDER=0></TD>
    <TD><INPUT TYPE='hidden' NAME='actimg_".$i."' VALUE='".$cur['img1']."'><INPUT TYPE='file' NAME='imgfile_".$i."'></TD>";
    print "</TR>";
  }
  
  print "<TR CLASS='$cur_rowstyle' STYLE='background-color:#FFF4BF !important;'>";
  print "<TD><INPUT TYPE='text' SIZE=6 NAME='codint_new' VALUE=''></TD>";
  print "<TD><INPUT TYPE='text' SIZE=30 NAME='descr_new' VALUE=''></TD>";
  print "<TD>&nbsp;</TD><TD><INPUT TYPE='file' NAME='imgfile_new'></TD>";
  print "</TR>";
  
  print "<TR><TD COLSPAN=4 ALIGN=CENTER><INPUT TYPE='hidden' NAME='ne' VALUE='".$i."'><INPUT TYPE='button' NAME='salva' VALUE='"._BTNSALVA_."' onClick=btnsalva();></TD></TR>";
  print "</TABLE>";
  print "</FORM>";
  
  print "<FORM NAME='formcancella' METHOD='POST' ACTION='".FILE_DBQUERY."'>
  <INPUT TYPE='hidden' NAME='selobj' VALUE=''><INPUT TYPE='hidden' NAME='cmd' VALUE='". FASEDEL ."'>
     <INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'>
  </FORM>";


	
  
  include ("finepagina.php");
?>
<SCRIPT>
function btnsalva() {
  document.caricamento.submit();
  //self.close();
}

function actlingua(chiave) {
  var app;
  
  app=parseInt(document.caricamento['azione_'+chiave].value, 10);
  app = app * -1;
  document.caricamento['azione_'+chiave].value=app.toString();
}

function cancella(chiave) {
  
  document.formcancella.selobj.value=chiave;
  document.formcancella.submit();
}
</SCRIPT>
</body>
</html>
