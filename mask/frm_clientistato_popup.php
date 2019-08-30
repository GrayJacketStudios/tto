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
   .option_selected {
     background-color:#FFAA00;
     font-style:italic;
     font-weight:bolder;
   }
   .infocollegate{
    width:90%;
    background-color:#E8FFAF;
    -moz-border-radius: 5px;
    border-radius: 5px;
   }
  </STYLE>
</head>
<body>
<?php
  include ("iniziopagina.php");

  //CARICAMENTO VALORI PER EDIT
  define("FILE_CORRENTE", "frm_clientistato_popup.php");

  date_default_timezone_set('America/Santiago');

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  $selobj = $_REQUEST['selobj'];
  
  //STATI
  $query = "SELECT id, valore, label, img FROM stati WHERE idgruppo=1 ORDER BY ordine";
  $result = mysql_query($query) or die (mysql_error());
  while ($line = mysql_fetch_assoc($result)) {
    $vetstati[$line['id']]['stato'] = $line['valore'];
    $vetstati[$line['id']]['img'] = $line['img'];
    $vetstati[$line['id']]['txt'] = $line['label'];    
  }
  
  $query = "SELECT id, stato, stato2,note
            FROM customer
            WHERE id=".$selobj;
  
  $result = mysql_query($query) or die (mysql_error());
  $line = mysql_fetch_assoc($result);
  $editval['id'] = $line['id'];
  $editval['stato'] = $line['stato'];
  $editval['stato2'] = $line['stato2'];
  $editval['note'] = $line['note'];
  
  $query = "SELECT customer_log.id as id, idcustomer, times, mezzo, msg, user.nome as user_nome, user.username as user_username,
            customer_log.user as customer_log_user
            FROM customer_log LEFT JOIN user ON customer_log.user=user.id
            WHERE customer_log.idcustomer=".$selobj." AND customer_log.trashed<>1
            ORDER BY times DESC";
  $result = mysql_query($query) or die (mysql_error());
  while ($line = mysql_fetch_assoc($result)) {
    $chiave = $line['id'];
    $log[$chiave]['times']=$line['times'];
    $log[$chiave]['mezzo']=$line['mezzo'];
    $log[$chiave]['msg']=$line['msg'];
    $log[$chiave]['user_nome']=$line['user_nome'];
    $log[$chiave]['user_username']=$line['user_username'];
    $log[$chiave]['customer_log_user']=$line['customer_log_user'];
  }
  
  $query = "SELECT customer.id as customer_id, customer.nome as customer_nome, customer.apellido as customer_apellido,
        customer.posizione as posizione, customer.ragsoc as ragsoc, customer.rut_ditta as rut_ditta, customer.rut_persona as rut_persona,
        customer.giro as customer_giro, customer.legale_nome as legale_nome, customer.legale_rut as legale_rut, customer.addr_pri as addr_pri,
        customer.addr_work as addr_work, customer.tel_pri as tel_pri, customer.tel_lavoro as tel_lavoro, customer.tel_mobile as tel_mobile,
        customer.mail1 as customer_mail1, customer.mail2 as customer_mail2, customer.mail3 as customer_mail3,customer.fatturaz as fatturaz,
        customer.seg_nome as seg_nome, customer.seg_foto as seg_foto, customer.seg_mail as seg_mail, customer.fantasia as fantasia, customer.web as customer_web,
        customer.skype as customer_skype, customer.fb as customer_fb, customer.codsence as customer_codsence, customer.note as customer_note, customer.impresa as customer_impresa, 
        customer.fatturaz_rut as fatturaz_rut, customer.fatturaz_giro as fatturaz_giro, customer.fatturaz_ragsoc as fatturaz_ragsoc,
        customer.stato as customer_stato, customer.stato2 as customer_stato2, customer.rubro as rubro  ";
        
  $query .= "FROM customer 
            WHERE customer.id=".$selobj;
  $result = mysql_query($query) or die(mysql_error());
  $line = mysql_fetch_assoc($result);
  
  $vetcustomer['nome'] = $line['customer_nome'];
  $vetcustomer['apellido'] = $line['customer_apellido'];
  $vetcustomer['ragsoc'] = $line['ragsoc'];
  $vetcustomer['fantasia'] = $line['fantasia'];
  $vetcustomer['impresa'] = $line['customer_impresa'];
  $vetcustomer['tel_pri'] = $line['tel_pri'];
  $vetcustomer['tel_lavoro'] = $line['tel_lavoro'];
  $vetcustomer['tel_mobile'] = $line['tel_mobile'];
  $vetcustomer['customer_mail1'] = $line['customer_mail1'];
  $vetcustomer['customer_mail2'] = $line['customer_mail2'];
  $vetcustomer['customer_mail3'] = $line['customer_mail3'];


  print "<BR><H1>"._CUSTOMERSTATO_."</H1>";
  
  
  print "<BR>";
  
  print "<CENTER><DIV CLASS='infocollegate'>";
    print "<TABLE STYLE='width:98%;'>";
      print "<TR><TD>".labelsoggettounico(1,$vetcustomer,0,1,1)."</TD><TD>";
        print "<IMG SRC='../img/phone_red16.png' BORDER=0 ALT='"._LBLTELPRI_."' TITLE='"._LBLTELPRI_."'> ".$vetcustomer['tel_pri']."<BR>";
        print "<IMG SRC='../img/phone_blue16.png' BORDER=0 ALT='"._LBLTELWORK_."' TITLE='"._LBLTELWORK_."'> ".$vetcustomer['tel_lavoro']."<BR>";
        print "<IMG SRC='../img/mobile16.png' BORDER=0 ALT='"._LBLTELMOBILE_."' TITLE='"._LBLTELMOBILE_."'> ".$vetcustomer['tel_mobile']."<BR>";
      print "</TD><TD>";
        print "<A HREF='mailto:".$vetcustomer['customer_mail1']."'>".$vetcustomer['customer_mail1']."</A><BR>";
        print "<A HREF='mailto:".$vetcustomer['customer_mail2']."'>".$vetcustomer['customer_mail2']."</A><BR>";
        print "<A HREF='mailto:".$vetcustomer['customer_mail3']."'>".$vetcustomer['customer_mail3']."</A><BR>";
      print "</TD></TR>";
    print "</TABLE>";
  print "</DIV></CENTER><BR><BR>";
  
  print "<FORM ACTION='". FILE_DBQUERY ."' METHOD='POST' NAME='caricamento' TARGET=inserimento><INPUT TYPE='hidden' NAME='cmd' VALUE='". FASESETTASTATO ."'><INPUT TYPE='hidden' NAME='selobj' VALUE='". $selobj ."'><INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'>";
	print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='50%'>";
	
	foreach ($vetstati as $key =>$cur) {
    print "<TR>";
    print "<TD CLASS='form_lbl'>".convlang($cur['txt'])."</TD>";
    print "<TD>";
      print "<INPUT TYPE='radio' NAME='stato' VALUE='".$cur['stato']."#".convlang($cur['txt'])."' ".($editval['stato']==$cur['stato']? " CHECKED ":"")."><IMG SRC='../img/".$cur['img']."'>&nbsp;"; 
      if ($cur['stato']==2) {
        //se potenziali
        
        $query = "SELECT id, valore, label FROM stati WHERE idgruppo=2 ORDER BY ordine";
        $result = mysql_query($query) or die ("Error_1.3");
        
        print "<SELECT NAME='stato2' SIZE='1'>";
        print "<OPTION VALUE='NULL'>"._LBLNESSUNAAZIONE_."</OPTION>";
        while ($line=mysql_fetch_assoc($result)) {
          print "<OPTION VALUE='".$line['valore']."#".convlang($line['label'])."'".($editval['stato2']==$line['valore']? " SELECTED  CLASS='OPTION_SELEZIONATA'":"").">".convlang($line['label'])."</OPTION>";
        }
      }  
    print "</TD>";
    print "<TD CLASS=form_help></TD>";
    print "</TR>";    
  }
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLMEZZO_."</TD>";
  print "<TD><IMG SRC='../img/email.png'>&nbsp;<input type='radio' name='mezzo' value='1' checked>&nbsp;&nbsp;<IMG SRC='../img/tel.png'>&nbsp;<input type='radio' name='mezzo' value='2'></TD>";
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
  print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='85%' STYLE='background-color: #FFE4AF;color:#000000;'>";
  
  
  
  foreach ($log as $key=>$cur) {
    print "<TR>";
    print "<TD>";
      
      //AUTH CLI07
      if ($_SESSION['auth_CLI07']==1) {
        print "<A HREF='#' onClick=urldelete(\"".FILE_DBQUERY."?cmd=".FASEDELLOG."&selobj=".$key."&idc=".$selobj."&codicepagina=".codiceform(FILE_CORRENTE)."\")><IMG SRC='../img/canc.png' BORDER=0 ALIGN=LEFT></A>&nbsp;";
      }
      
      if ($cur['times']>=mktime(0, 0, 0, 3, 27, 2011)) {
        $cur['times']+=3600;
      } 
      
      print date("d/m/Y H:i",$cur['times']);
      print "<BR>";
      if ($cur['customer_log_user']==0) {
        switch ($cur['mezzo']) {
          case 20:
            $temp_user = "WEB FORM";break;
          default:
            $temp_user = "TTO";break;
        }
        print $temp_user;
      } else {
        print $cur['user_nome']; 
      }

    print "</TD>";
    
    switch ($cur['mezzo']) {
      case 1:
            $img = "email.png";
            break;
      case 2:
            $img = "tel.png";
            break;
      case 20:
            $img = "webform_32.png";
            break;
    }
    print "<TD>";
      print "<IMG SRC='../img/$img'>";
      
      
      
    print "</TD>";
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
