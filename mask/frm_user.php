<?php
  $pagever = "1.0";
  $pagemod = "29/06/2010 16.43.31";
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
  define("FILE_CORRENTE", "frm_user.php");

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  $selobj = $_REQUEST['selobj'];
  

  $query = "SELECT user.id as user_id, user.nome as user_nome, user.username as username, user.password as password,
    user.dtcreazione as dtcreazione, user.attivo as user_attivo, user.fboss as fboss, user.fviceboss as fviceboss, user.trashed as trashed,
    docente.id as docente_id, docente.nome as docente_nome, docente.nickname as docente_nickname, user.email as user_email,  
    studente.id as studente_id, studente.nome as studente_nome
    FROM user LEFT JOIN docente ON user.id=docente.iduser LEFT JOIN studente ON user.id=studente.iduser
    WHERE user.trashed<>1";
  
  if ($cmd['comando']==FASESEARCH) {
    $query .= " AND studente.nome like '%".$_REQUEST['nome']."%' AND studente.rut like '%".$_REQUEST['rut']."%'";
  }
  $query .= " ORDER BY user.nome";
  $result = mysql_query($query) or die ("Error_1.1");
  
  print "<BR><H1>"._USERNAME_."</H1>";
  
  if (mysql_num_rows($result)!=0 )  {
    echo "<TABLE CLASS=lista_table ALIGN=CENTER>";
    echo "<TR><TD CLASS=lista_tittab>"._LBLUSERNAME_."</TD><TD CLASS=lista_tittab>"._LBLNOME_."</TD><TD CLASS=lista_tittab>"._LBLEMAIL_."</TD><TD CLASS=lista_tittab>"._LBLPERMESSI_."</TD><TD CLASS=lista_tittab>"._LBLDATACREAZIONE_."</TD><TD CLASS=lista_tittab>"._LBLASSOCIATO_."</TD><TD CLASS=lista_tittab>"._LBLSTATO_."</TD><TD CLASS=lista_tittab>&nbsp;</TD></TR>";
    while ($line=mysql_fetch_assoc($result)) {
      //salva lista user per confronto
      $tuttiuser .= "[".$line['username']."]";
    
      print "<TR CLASS=lista_riga>";
      print "<TD CLASS=lista_col>[".$line['user_id']."] ".$line['username']."</TD>";
      print "<TD CLASS=lista_col>".$line['user_nome']."</TD>";
      print "<TD CLASS=lista_col>".$line['user_email']."</TD>";
      print "<TD CLASS=lista_col>";
        if ($line['fboss']==1) {
          //admin
          print "<IMG SRC='../img/admin.png' BORDER=0 ALIGN=MIDDLE ALT='"._LBLGRANTBOSS_."' TITLE='"._LBLGRANTBOSS_."'>";
        } else {
          if ($line['fviceboss']==1) {
            //vice
            print "<IMG SRC='../img/viceadmin.png' BORDER=0 ALIGN=MIDDLE ALT='"._LBLGRANTVICEBOSS_."' TITLE='"._LBLGRANTVICEBOSS_."'>";
          } else {
            //niente
            print "<IMG SRC='../img/admindisabled.png' BORDER=0 ALIGN=MIDDLE ALT='"._LBLGRANTUSER_."' TITLE='"._LBLGRANTUSER_."'>";
          }
        }
      print "</TD>";
      print "<TD CLASS=lista_col>".date("d/m/Y",$line['dtcreazione'])."</TD>";
      print "<TD CLASS=lista_col>";
        if ($line['docente_id']!="") {
          print _DOCENTE_.": [".$line['docente_id']."] ".$line['docente_nickname'];
        } else {
          if ($line['studente_id']!="") {
            print _STUDENTE_.": [".$line['studente_id']."] ".$line['studente_nome'];
          } else {
            print _NOASSOCIAZIONE_;
          }
        }
      print "</TD>";
      print "<TD CLASS=lista_col>";
        if ($line['user_attivo']==1) {
          //Attivo
          print "<A HREF='".FILE_DBQUERY."?cmd=".FASEDENY."&codicepagina=".codiceform(FILE_CORRENTE)."&selobj=".$line['user_id']."'><IMG SRC='../img/ledgreen.png' BORDER=0 ALIGN=MIDDLE ALT='"._TXTDISABILITA_."' TITLE='"._TXTDISABILITA_."'></A>";
        } else {
          print "<A HREF='".FILE_DBQUERY."?cmd=".FASEALLOW."&codicepagina=".codiceform(FILE_CORRENTE)."&selobj=".$line['user_id']."'><IMG SRC='../img/ledred.png' BORDER=0 ALIGN=MIDDLE ALT='"._TXTABILITA_."' TITLE='"._TXTABILITA_."'></A>";
        }
      print "</TD>";
      //COMANDI
      print "<TD CLASS=lista_col><TABLE width=100%><TR>
      <TD ALIGN=CENTER><A HREF='". FILE_CORRENTE ."?cmd=". FASEMOD ."&selobj=". $line['user_id'] ."#maskera'><IMG SRC='../img/mod.png' border=0 ALT='"._LBLMATITA_."' TITLE='"._LBLMATITA_."'></A></TD>
      <TD ALIGN=CENTER><A HREF='#' onClick=urldelete(\"". FILE_DBQUERY ."?cmd=". FASEDEL ."&codicepagina=". codiceform(FILE_CORRENTE) ."&selobj=". $line['user_id'] ."\")><IMG SRC='../img/canc.png' border=0 ALT='"._LBLCANCELLA_."'></A></TD>";
			print "</TR></TABLE></TD>";
      print "</TR>";
        
              //CARICAMENTO IN LINEA PER EDIT
      if ($selobj==$line['user_id']) {
        $editval['user_nome'] = $line['user_nome'];
        $editval['username'] = $line['username'];
        $editval['dtcreazione'] = $line['dtcreazione'];
        $editval['fboss'] = $line['fboss'];
        $editval['fviceboss'] = $line['fviceboss'];
        $editval['password'] = FIXPASSWORD;
        $editval['user_email'] = $line['user_email'];
      } 
    }
       
    echo "</TABLE><BR>";
  }
  // Torna all'inserimento
  if ($cmd['comando']==FASEMOD) {
    print "<div align='right' class='backinsert'>";
    print "\t<A HREF='". FILE_CORRENTE ."'><img src='../img/edit_small.gif' align='center' border='0' width='16' height='16'> "._BACKTOINS_."</A>";
    print "</div>";
  }
  print "<BR>";
  
  if ($cmd['comando']==FASEMOD) {
      $titolofinestra = _MODUSER_; $classtitolo = "headermod";
      //echo "XXX";
  } else {
      $cmd['comando']=FASEINS;
      $titolofinestra = _INSUSER_; $classtitolo = "headerins";
      //echo "<BR> xxcmd ".$cmd;
  }
  print "<FORM ACTION='". FILE_DBQUERY ."' METHOD='POST' NAME='caricamento'><INPUT TYPE='hidden' NAME='cmd' VALUE='". $cmd['comando'] ."'><INPUT TYPE='hidden' NAME='selobj' VALUE='". $selobj ."'><INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'>";
	print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='50%'>";
  print "<tr><td class='headermod' colspan='3'><div class='header-text'>$titolofinestra</div></td></tr>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLUSERNAME_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='username' VALUE='". $editval['username'] ."' ".($cmd['comando']==FASEMOD ? " READONLY ":"")."></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLPASSWORD_."</TD>";
  print "<TD><INPUT TYPE='password' size='50' NAME='password' VALUE='". ($cmd['comando']==FASEINS ? "":$editval['password']) ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLNOME_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='user_nome' VALUE='". $editval['user_nome'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLEMAIL_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='user_email' VALUE='". $editval['user_email'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
    
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLPERMESSI_."</TD>";
  print "<TD>";
    print "<SELECT NAME='permessi' SIZE=1>";
    print "<OPTION VALUE='10'>"._OPZUTENTENORMALE_."</OPTION>";
    print "<OPTION VALUE='20' ".($editval['fviceboss']==1 ? " SELECTED ":"").">"._OPZVICEBOSS_."</OPTION>";
    print "<OPTION VALUE='30' ".($editval['fboss']==1 ? " SELECTED ":"").">"._OPZBOSS_."</OPTION>";
    print "</SELECT>";
  print "</TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR><TD COLSPAN=3 align='center' height='70'><INPUT TYPE='submit' VALUE='"._BTNSALVA_."'>&nbsp;&nbsp;&nbsp;<INPUT TYPE='reset' VALUE='"._BTNRESET_."'></TD></TR>";
  /*if ($cmd['comando']==FASEINS) {
    print "<TR><TD COLSPAN=3 align='center' height='70'><INPUT TYPE='button' STYLE='width:200px;' VALUE='Ricerca' onClick=fasesearch(document.caricamento);></TD></TR>";
  }*/
  print "</TABLE></FORM>";

  include ("finepagina.php");
?>
</body>
</html>
