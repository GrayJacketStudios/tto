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
  define("FILE_CORRENTE", "frm_docenti_statopopup.php");

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  $selobj = $_REQUEST['selobj'];
  
  //STATI
  $query = "SELECT id, valore, label, img FROM stati WHERE idgruppo=8 ORDER BY ordine";
  $result = mysql_query($query) or die ("Error_1.2");
  while ($line = mysql_fetch_assoc($result)) {
    $vetstati[$line['id']]['stato'] = $line['valore'];
    $vetstati[$line['id']]['img'] = $line['img'];
    $vetstati[$line['id']]['txt'] = $line['label'];    
  }
  
  $query = "SELECT id, attivo
            FROM docente
            WHERE id=".$selobj;
  
  $result = mysql_query($query) or die ("Error_1.1$query");
  $line = mysql_fetch_assoc($result);
  $editval['id'] = $line['id'];
  $editval['stato'] = $line['attivo'];
  
  
  $query = "SELECT docente_log.id as id, iddocente, times, mezzo, msg, user.nome as user_nome, user.username as user_username
            FROM docente_log INNER JOIN user ON docente_log.user=user.id
            WHERE docente_log.iddocente=".$selobj."
            ORDER BY times DESC";
  $result = mysql_query($query) or die ("Error_1.3");
  while ($line = mysql_fetch_assoc($result)) {
    $chiave = $line['id'];
    $log[$chiave]['times']=$line['times'];
    $log[$chiave]['mezzo']=$line['mezzo'];
    $log[$chiave]['msg']=$line['msg'];
    $log[$chiave]['user_nome']=$line['user_nome'];
    $log[$chiave]['user_username']=$line['user_username'];
  }
  

  print "<BR><H1>"._DOCENTESTATO_."</H1>";
  
  
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
