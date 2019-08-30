<?php
  $pagever = "1.0";
  $pagemod = "22/11/2010 20.56.12";
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
  define("FILE_CORRENTE", "frm_docenti_filepopup.php");

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  $selobj = $_REQUEST['selobj'];
  
  
  if ($selobj!=0) {            
    $query = "SELECT docente.id as docente_id, docente.iduser as docente_iduser, docente.nome as docente_nome,
            docente.nickname as docente_nickname, docente.tel as docente_tel, docente.mobile as docente_mobile,
            docente.email as docente_email, docente.attivo as docente_attivo, user.nome as user_nome, user.username as user_username,
            user.attivo as user_attivo, user.fboss as user_fboss, user.fviceboss as user_fviceboss,
            docente.banca as docente_banca, docente.conto as docente_conto, docente.rut as docente_rut, docente.tipoconto as docente_tipoconto,
            docente.contatto as docente_contatto, docente.note as docente_note
            FROM docente LEFT JOIN user ON docente.iduser=user.id AND user.trashed<>1 
            WHERE docente.trashed<>1 AND docente.id=".$selobj;

    $result = mysql_query($query) or die ("Error_1.1");
    $line = mysql_fetch_assoc($result);
    $editval['docente_nome']=$line['docente_nome'];
    $editval['docente_nickname']=$line['docente_nickname'];
    $editval['docente_tel']=$line['docente_tel'];
    $editval['docente_mobile']=$line['docente_mobile'];
    $editval['docente_email']=$line['docente_email'];
    $editval['docente_attivo']=$line['docente_attivo'];
    $editval['docente_iduser']=$line['docente_iduser'];
    $editval['docente_banca']=$line['docente_banca'];
    $editval['docente_conto']=$line['docente_conto'];
    $editval['docente_rut']=$line['docente_rut'];
    $editval['docente_tipoconto']=$line['docente_tipoconto'];
    $editval['docente_contatto']=$line['docente_contatto'];
    $editval['docente_note']=$line['docente_note'];
  }
  
  $query = "SELECT id, idgruppo, idext, nomefile, txt, mime, times
            FROM archiviofile
            WHERE idgruppo=1 AND idext=".$selobj;
  $result = mysql_query($query) or die ("Error_1.2");
  while ($line=mysql_fetch_assoc($result)) {
    $chiave = $line['id'];
    $vetarchiviofile[$chiave]['nomefile']=$line['nomefile'];
    $vetarchiviofile[$chiave]['txt']=$line['txt'];
    $vetarchiviofile[$chiave]['mime']=$line['mime'];
    $vetarchiviofile[$chiave]['times']=$line['times'];
  }
  

  print "<BR><H1>"._TITDOCENTI_FILE_."</H1>";

  print "<BR><TABLE CLASS=lista_table ALIGN=CENTER>";
  print "<TR><TD CLASS=lista_tittab>"._LBLTIPOFILE_."</TD><TD CLASS=lista_tittab>"._LBLDATA_."</TD><TD CLASS=lista_tittab>"._LBLDESCR_."</TD><TD CLASS=lista_tittab width='70'>&nbsp;</TD></TR>";  
  
  foreach ($vetarchiviofile as $key=>$cur) {
    if ($cur_rowstyle=="form2_lista_riga_pari") {
      $cur_rowstyle="form2_lista_riga_dispari";
      $stile = "background-color:#EDEDED;";//EDEDED   //F0FFEF
    } else {
      $cur_rowstyle="form2_lista_riga_pari";
      $stile = "background-color:#F7F7F7;";//F7F7F7   //FEFFEF
    }
    if ($key==$selobj) {
      $stile = "background-color:#FFAA00;";//F7F7F7   //FEFFEF
    }
    
    print "<TR CLASS='$cur_rowstyle'>";
    
    $img = tipomime($cur['mime'],IMMAGINE);
    print "<TD CLASS=lista_col_form2 ALIGN=CENTER><A HREF='../upload/".$cur['nomefile']."' TARGET=_blank><IMG SRC='../img/".$img."' BORDER=0></A></TD>";
    print "<TD CLASS=lista_col_form2>".date("d/m/Y H:i",$cur['times'])."</TD>";
    print "<TD CLASS=lista_col_form2>".$cur['txt']."</TD>";
    print "<TD CLASS=lista_col_form2>
      <A HREF='../upload/".$cur['nomefile']."' TARGET=_blank><IMG SRC='../img/lente.png' BORDER=0></A>";
      //auth
      if (checkauth('TTO702','D')) {
        print "&nbsp;&nbsp;
        <A HREF='#' onClick=urldelete(\"". FILE_DBQUERY ."?cmd=". FASEDELFILE ."&codicepagina=". codiceform(FILE_CORRENTE) ."&selobj=". $key ."&nomefile=".$cur['nomefile']."&idd=$selobj\")><IMG SRC='../img/canc.png' border=0 ALT='"._LBLCANCELLA_."' TITLE='"._LBLCANCELLA_."'>";
      }
    print "</TD>";
    print "</TR>";
    
  }
  
  print "</TABLE>";
  print "<BR><BR>";
  
  if ($cmd['comando']==FASEMOD) {
      $titolofinestra = _MODDOCENTE_FILE_; $classtitolo = "headermod";
      //echo "XXX";
  } else {
      $cmd['comando']=FASEINS;
      $titolofinestra = _INSDOCENTE_FILE_; $classtitolo = "headerins";
      //echo "<BR> xxcmd ".$cmd;
  }
  print "<FORM ACTION='". FILE_DBQUERY ."' METHOD='POST' NAME='caricamento' enctype='multipart/form-data'><INPUT TYPE='hidden' NAME='cmd' VALUE='". $cmd['comando'] ."'><INPUT TYPE='hidden' NAME='selobj' VALUE='". $selobj ."'><INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'>";
	print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='50%'>";
  print "<tr><td class='headermod' colspan='3'><div class='header-text'>$titolofinestra</div></td></tr>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLNOMEFILE_."</TD>";
  print "<TD><INPUT TYPE='file' size='50' NAME='nomefile' VALUE=''></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLDESCR_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='descr' VALUE=''></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
   
  print "<TR><TD COLSPAN=3 align='center' height='70'><INPUT TYPE='button' VALUE='"._BTNSALVA_."' onClick=btnsalva();>&nbsp;&nbsp;&nbsp;<INPUT TYPE='reset' VALUE='"._BTNRESET_."'></TD></TR>";
  print "</TABLE></FORM>";

  include ("finepagina.php");
?>
<SCRIPT>
function btnsalva() {
  document.caricamento.submit();

}

</SCRIPT>
<BR>
<BR>
</body>
</html>
