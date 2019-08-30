<?php
  $pagever = "1.0";
  $pagemod = "22/01/2011 10.33.52";
  require_once("form_cfg.php");
  require_once("func_cdcosto.php");   
  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <link rel="stylesheet" type="text/css" href="../css/main.css" />
  <STYLE>
    a:hover {color:red;}
  </STYLE>
</head>
<body>
<?php

//CENTRODICOSTO

  include ("iniziopagina.php");

  //CARICAMENTO VALORI PER EDIT
  define("FILE_CORRENTE", "frm_contab_cdcostopopup.php");

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  $selobj = $_REQUEST['selobj'];
  
  carica_dati();
    
  ins_struttura(0, $vetcdcosto,0, "");
  
  stampa_struttura(0, $vetcdcosto, 0);
  
  ?>

  <SCRIPT>
    function selelemento(idcdcosto, codcdcosto, descrcdcosto)  {
      opener.document.caricamento.idcdcosto.value=idcdcosto;
      opener.document.caricamento.cdcosto_cod.value=codcdcosto;
      opener.document.caricamento.cdcosto_descr.value=unescape(descrcdcosto);
      
      this.close();
    }
    

  </SCRIPT>
  <?
  
  die("s");
  print "<BR><H1>"._CENTRIDICOSTO_."</H1>";

  print "<BR>";

  print "<FORM ACTION='". FILE_DBQUERY ."' METHOD='POST' NAME='caricamento' TARGET=inserimento><INPUT TYPE='hidden' NAME='cmd' VALUE='". FASESETTASTATO ."'><INPUT TYPE='hidden' NAME='selobj' VALUE='". $selobj ."'><INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'>";
	print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='50%'>";
	
	
	
	
	foreach ($vetstati as $key =>$cur) {
    print "<TR>";
    print "<TD CLASS='form_lbl'>".convlang($cur['txt'])."</TD>";
    print "<TD>";
      print "<INPUT TYPE='radio' NAME='stato' VALUE='".$cur['stato']."§".convlang($cur['txt'])."' ".($editval['stato']==$cur['stato']? " CHECKED ":"")."><IMG SRC='../img/".$cur['img']."'>&nbsp;";   
    print "</TD>";
    print "<TD CLASS=form_help></TD>";
    print "</TR>";    
  }
  
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
  include ("finepagina.php");
?>
<SCRIPT>

function btnsalva() {
  document.caricamento.submit();
  self.close();
}
</SCRIPT>
</body>
</html>
