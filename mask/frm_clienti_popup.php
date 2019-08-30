<?php
  $pagever = "1.1";
  $pagemod = "16/02/2011 23.01.46";
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
  </STYLE>
</head>
<body>
<?php
  include ("iniziopagina.php");

  //CARICAMENTO VALORI PER EDIT
  define("FILE_CORRENTE", "frm_clienti_popup.php");

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  $selobj = $_REQUEST['selobj'];
  
  //preload rut x controllo
  $tr = array("."=>""," "=>"","-"=>"","/"=>"",","=>"","&#8722;"=>"");
  $query = "SELECT DISTINCT rut_ditta, rut_persona, nome, apellido, ragsoc, impresa FROM customer where trashed<>1";
  $result = mysql_query($query) or die ("Error 10.1");
  while ($line = mysql_fetch_assoc($result)) {
    if ($line['rut_ditta']<>"") {
      $chiave =strtr(html_entity_decode($line['rut_ditta'],ENT_COMPAT,'ISO8859-15'),$tr);
      $vetsoggetto['nome']=$line['nome'];
      $vetsoggetto['apellido']=$line['apellido'];
      $vetsoggetto['ragsoc']=$line['ragsoc'];
      $vetsoggetto['impresa']=$line['impresa'];
      
      $vetrut[$chiave] = labelsoggettounico(1,$vetsoggetto,0,1,0);
    }
    if ($line['rut_persona']<>"") {
      $chiave =strtr(html_entity_decode($line['rut_persona'],ENT_COMPAT,'ISO8859-15'),$tr);
      $vetsoggetto['nome']=$line['nome'];
      $vetsoggetto['apellido']=$line['apellido'];
      $vetsoggetto['ragsoc']=$line['ragsoc'];
      $vetsoggetto['impresa']=$line['impresa'];
      
      $vetrut[$chiave] = labelsoggettounico(1,$vetsoggetto,0,1,0);
    }
  }
  
  
  if ($selobj!=0) {
    $query = "SELECT id, nome, apellido, posizione, ragsoc, rut_ditta, rut_persona, giro, legale_nome, legale_rut,
            addr_pri, addr_work, tel_pri, tel_lavoro, tel_mobile, mail1, mail2, mail3, fatturaz, seg_nome, seg_foto, seg_mail,
            fantasia, web, skype, fb, codsence, note, impresa, fatturaz_rut, fatturaz_giro, fatturaz_ragsoc, rubro
            FROM customer
            WHERE id=".$selobj;

    $result = mysql_query($query) or die ("Error_1.1");
    $line = mysql_fetch_assoc($result);
    $editval['id'] = $line['id'];
    $editval['nome'] = $line['nome'];
    $editval['apellido'] = $line['apellido'];
    $editval['posizione'] = $line['posizione'];
    $editval['ragsoc'] = $line['ragsoc'];
    $editval['rut_ditta'] = $line['rut_ditta'];
    $editval['rut_persona'] = $line['rut_persona'];
    $editval['giro'] = $line['giro'];
    $editval['legale_nome'] = $line['legale_nome'];
    $editval['legale_rut'] = $line['legale_rut'];
    $editval['addr_pri'] = $line['addr_pri'];
    $editval['addr_work'] = $line['addr_work'];
    $editval['tel_pri'] = $line['tel_pri'];
    $editval['tel_lavoro'] = $line['tel_lavoro'];
    $editval['tel_mobile'] = $line['tel_mobile'];
    $editval['mail1'] = $line['mail1'];
    $editval['mail2'] = $line['mail2'];
    $editval['mail3'] = $line['mail3'];
    $editval['fatturaz'] = $line['fatturaz'];
    $editval['seg_nome'] = $line['seg_nome'];
    $editval['seg_foto'] = $line['seg_foto'];
    $editval['seg_mail'] = $line['seg_mail'];
    $editval['fantasia'] = $line['fantasia'];
    $editval['web'] = $line['web'];
    $editval['skype'] = $line['skype'];
    $editval['codsence'] = $line['codsence'];
    $editval['note'] = $line['note'];
    $editval['impresa'] = $line['impresa'];
    $editval['fatturazrut'] = $line['fatturaz_rut'];
    $editval['fatturazgiro'] = $line['fatturaz_giro'];
    $editval['fatturazragsoc'] = $line['fatturaz_ragsoc'];
    $editval['rubro'] = $line['rubro'];
    
    $editval['original_rut_persona'] = $line['rut_persona'];
    $editval['original_rut_ditta'] = $line['rut_ditta'];
  }
  

  print "<BR><H1>"._TITCLIENTE_."</H1>";
  
  
  print "<BR>";
  
  if ($cmd['comando']==FASEMOD) {
      $titolofinestra = _MODCUSTOMER_; $classtitolo = "headermod";
      //echo "XXX";
  } else {
      $cmd['comando']=FASEINS;
      $titolofinestra = _INSCUSTOMER_; $classtitolo = "headerins";
      //echo "<BR> xxcmd ".$cmd;
  }
  print "<FORM ACTION='". FILE_DBQUERY ."' METHOD='POST' NAME='caricamento' TARGET=inserimento><INPUT TYPE='hidden' NAME='cmd' VALUE='". $cmd['comando'] ."'><INPUT TYPE='hidden' NAME='selobj' VALUE='". $selobj ."'><INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'>";
	print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='50%'>";
  print "<tr><td class='headermod' colspan='3'><div class='header-text'>$titolofinestra</div></td></tr>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLTIPOCLI_."</TD>";
  print "<TD><SELECT SIZE=1 NAME='impresa' onChange=display_campi(this.value);><OPTION VALUE=0>"._LBLOPTPERSONA_."</OPTION><OPTION VALUE=1 ".($editval['impresa']==1 ? " SELECTED ":"").">"._LBLOPTIMPRESA_."</OPTION></SELECT></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR id='nome'>";
  print "<TD CLASS='form_lbl'>"._LBLNOME_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='nome' VALUE='". $editval['nome'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
   
  print "<TR id='apellido'>";
  print "<TD CLASS='form_lbl'>"._LBLCOGNOME_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='apellido' VALUE='". $editval['apellido'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR id='posizione'>";
  print "<TD CLASS='form_lbl'>"._LBLPOSIZIONE_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='posizione' VALUE='". $editval['posizione'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR id='rubro_individuale'>";
  print "<TD CLASS='form_lbl'>"._LBLRUBRO_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='rubro' VALUE='". $editval['rubro'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR id='ragsoc_impresa'>";
  print "<TD CLASS='form_lbl'>"._LBLRAGSOC_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='ragsoc_impresa' VALUE='". $editval['ragsoc'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR id='ragsoc'>";
  print "<TD CLASS='form_lbl'>"._LBLRAGSOC_."</TD>";
  print "<TD>";
    $query = "SELECT ragsoc FROM customer WHERE trashed<>1 AND impresa=1 ORDER BY ragsoc";
    $result = mysql_query ($query) or die ("Error_1.2");
    print "<SELECT SIZE=1 NAME='ragsoc'>";
    print "<OPTION VALUE=''> - - - - - - - </OPTION>";
    while ($line = mysql_fetch_assoc($result)) {
      print "<OPTION VALUE='".$line['ragsoc']."' ".($editval['ragsoc']==$line['ragsoc'] ? " SELECTED CLASS='OPTION_SELEZIONATA'":"").">".$line['ragsoc']."</OPTION>";
    }
    print "</SELECT>";
  print "</TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR id='fantasia'>";
  print "<TD CLASS='form_lbl'>"._LBLFANTASIA_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='fantasia' VALUE='". $editval['fantasia'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR id='rut_ditta'>";
  print "<TD CLASS='form_lbl'>"._LBLRUTDITTA_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='rut_ditta' VALUE='". $editval['rut_ditta'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR id='rut_persona'>";
  print "<TD CLASS='form_lbl'>"._LBLRUTPERSONA_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='rut_persona' VALUE='". $editval['rut_persona'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLGIRO_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='giro' VALUE='". $editval['giro'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR id='rubro'>";
  print "<TD CLASS='form_lbl'>"._LBLRUBRO_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='rubro' VALUE='". $editval['rubro'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR id='legale_nome'>";
  print "<TD CLASS='form_lbl'>"._LBLLEGALENOME_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='legale_nome' VALUE='". $editval['legale_nome'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR id='legale_rut'>";
  print "<TD CLASS='form_lbl'>"._LBLLEGALERUT_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='legale_rut' VALUE='". $editval['legale_rut'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLEMAIL_." 1</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='mail1' VALUE='". $editval['mail1'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLEMAIL_." 2</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='mail2' VALUE='". $editval['mail2'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR id='tel_lavoro'>";
  print "<TD CLASS='form_lbl'>"._LBLTELWORK_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='tel_lavoro' VALUE='". $editval['tel_lavoro'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR id='tel_pri'>";
  print "<TD CLASS='form_lbl'>"._LBLTELPRI_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='tel_pri' VALUE='". $editval['tel_pri'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLTELMOBILE_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='tel_mobile' VALUE='". $editval['tel_mobile'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
    
  print "<TR id='addr_pri'>";
  print "<TD CLASS='form_lbl'>"._LBLADDRESSPRI_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='addr_pri' VALUE='". $editval['addr_pri'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR id='addr_work'>";
  print "<TD CLASS='form_lbl'>"._LBLADDRESSWORK_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='addr_work' VALUE='". $editval['addr_work'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
    
  print "<TR id='fatturaz'>";
  print "<TD CLASS='form_lbl'>"._LBLFATTURAZIONE_."</TD>";
  print "<TD><SELECT SIZE=1 NAME='fatturaz' onChange=show_datifatturaz();><OPTION VALUE=0>"._LBLOPZFATTPRI_."</OPTION>
         <OPTION VALUE=1 ".($editval['fatturaz']==1 ? " SELECTED ": "").">"._LBLOPZFATTDITTA_."</OPTION></SELECT></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";

  print "<TR id='fatturazragsoc'>";
  print "<TD CLASS='form_lbl'>"._LBLFATTURAZRAGSOC_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='fatturazragsoc' VALUE='". $editval['fatturazragsoc'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";

  print "<TR id='fatturazrut'>";
  print "<TD CLASS='form_lbl'>"._LBLFATTURAZRUT_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='fatturazrut' VALUE='". $editval['fatturazrut'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR id='fatturazgiro'>";
  print "<TD CLASS='form_lbl'>"._LBLFATTURAZGIRO_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='fatturazgiro' VALUE='". $editval['fatturazgiro'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR id='fatturaz_impresa'>";
  print "<TD CLASS='form_lbl'>"._LBLFATTURAZIONE_."</TD>";
  print "<TD><SELECT SIZE=1 NAME='fatturaz_impresa'><OPTION VALUE=1>"._LBLOPZFATTDITTA_."</OPTION></SELECT></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  /*
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLSEGNOME_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='seg_nome' VALUE='". $editval['seg_nome'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  */
  
  /*print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLSEGFOTO_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='seg_foto' VALUE='". $editval['seg_foto'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  */
  
  /*
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLSEGMAIL_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='seg_mail' VALUE='". $editval['seg_mail'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  */
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLWEB_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='web' VALUE='". $editval['web'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLSKYPE_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='skype' VALUE='". $editval['skype'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLCODSENCE_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='codsence' VALUE='". $editval['codsence'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  print "<TR>";
  print "<TD CLASS='form_lbl'>"._LBLNOTE_."</TD>";
  print "<TD><INPUT TYPE='text' size='50' NAME='note' VALUE='". $editval['note'] ."'></TD>";
  print "<TD CLASS=form_help></TD>";
  print "</TR>";
  
  
  print "<TR><TD COLSPAN=3 align='center' height='70'><INPUT TYPE='button' VALUE='"._BTNSALVA_."' onClick=btnsalva();>&nbsp;&nbsp;&nbsp;<INPUT TYPE='reset' VALUE='"._BTNRESET_."'></TD></TR>";
  print "</TABLE></FORM>";

  include ("finepagina.php");        
?>
<SCRIPT>
function btnsalva() {
  var vetrut = new Array();
  
  
  <?php
  foreach ($vetrut as $key => $cur) {
    print "vetrut['".$key."'] = \"". addslashes($cur) ."\";";
  }
  ?>
  corretto=1;
  var app;
  app = document.caricamento.rut_ditta.value;
  
  if (app!="" && app!="<?=$editval['original_rut_ditta']?>") {
    app = app.replace(/\./g,"");
    app = app.replace(/ /g,"");
    app = app.replace(/,/g,"");
    app = app.replace(/-/g,"");
  
    if(app!="" && vetrut[app]!=undefined) {
        
      alert("<?=_LBLALERTRUTDOPPIO_?>: "+vetrut[app]);
      corretto=0;
    }
  }

  app = document.caricamento.rut_persona.value;
  
  if (app!="" && app!="<?=$editval['original_rut_persona']?>") {
    app = app.replace(/\./g,"");
    app = app.replace(/ /g,"");
    app = app.replace(/,/g,"");
    app = app.replace(/-/g,"");
  
    if(corretto==1 && app!="" && vetrut[app]!=undefined) {
        
      alert("<?=_LBLALERTRUTDOPPIO_?>: "+vetrut[app]);
      corretto=0;
    }
  }

  if (corretto==1) {
    document.caricamento.submit();
    //alert("salto");
    self.close();
  }
}

function display_campi(valore) {
  if (parseInt(valore,10)==1) {
    //impresa
    var elem = document.getElementById("nome");
    elem.style.display="none";

    var elem = document.getElementById("apellido");
    elem.style.display="none";

    var elem = document.getElementById("ragsoc");
    elem.style.display="none";
    
    var elem = document.getElementById("posizione");
    elem.style.display="none";

    var elem = document.getElementById("rut_persona");
    elem.style.display="none";

    var elem = document.getElementById("addr_pri");
    elem.style.display="none";
    
    var elem = document.getElementById("tel_pri");
    elem.style.display="none";

    var elem = document.getElementById("fatturaz");
    elem.style.display="none";
    
    var elem = document.getElementById("fatturazrut");
    elem.style.display="none";
    
    var elem = document.getElementById("fatturazgiro");
    elem.style.display="none";
    
    var elem = document.getElementById("fatturazragsoc");
    elem.style.display="none";      
    
    var elem = document.getElementById("rubro_individuale");
    elem.style.display="none";      
    
//--------------------------------
    var elem = document.getElementById("ragsoc_impresa");     
    elem.style.display="";

    var elem = document.getElementById("fantasia");
    elem.style.display="";

    var elem = document.getElementById("rut_ditta");
    elem.style.display="";

    var elem = document.getElementById("addr_work");
    elem.style.display="";

    var elem = document.getElementById("fatturaz_impresa");
    elem.style.display="";
    
    var elem = document.getElementById("legale_nome");
    elem.style.display="";
    
    var elem = document.getElementById("legale_rut");
    elem.style.display="";
    
    var elem = document.getElementById("rubro");
    elem.style.display="";

  } else {
    //persona
    var elem = document.getElementById("ragsoc_impresa");
    elem.style.display="none";

    var elem = document.getElementById("fantasia");
    elem.style.display="none";

    var elem = document.getElementById("rut_ditta");
    elem.style.display="none";

    var elem = document.getElementById("addr_work");
    elem.style.display="none";

    var elem = document.getElementById("fatturaz_impresa");
    elem.style.display="none";
    
    var elem = document.getElementById("fatturazrut");
    elem.style.display="none";
    
    var elem = document.getElementById("fatturazgiro");
    elem.style.display="none";
    
    var elem = document.getElementById("fatturazragsoc");
    elem.style.display="none";
    
    var elem = document.getElementById("legale_nome");
    elem.style.display="none";
    
    var elem = document.getElementById("legale_rut");
    elem.style.display="none";
    
    var elem = document.getElementById("rubro");
    elem.style.display="none";

//-----------------------------------
    var elem = document.getElementById("nome");
    elem.style.display="";

    var elem = document.getElementById("apellido");
    elem.style.display="";

    var elem = document.getElementById("ragsoc");
    elem.style.display="";
    
    var elem = document.getElementById("posizione");
    elem.style.display="";

    var elem = document.getElementById("rut_persona");
    elem.style.display="";

    var elem = document.getElementById("addr_pri");
    elem.style.display="";
    
    var elem = document.getElementById("tel_pri");
    elem.style.display="";

    var elem = document.getElementById("fatturaz");
    elem.style.display="";
    
    var elem = document.getElementById("rubro_individuale");
    elem.style.display="";  
  }
}

function show_datifatturaz() {
  if (document.caricamento.fatturaz.value==1 && document.caricamento.impresa.value==0) {
    var elem = document.getElementById("fatturazrut");
    elem.style.display="";
    
    var elem = document.getElementById("fatturazgiro");
    elem.style.display="";
    
    var elem = document.getElementById("fatturazragsoc");
    elem.style.display="";
        
  } else {
    var elem = document.getElementById("fatturazrut");
    elem.style.display="none";
    
    var elem = document.getElementById("fatturazgiro");
    elem.style.display="none";
    
    var elem = document.getElementById("fatturazragsoc");
    elem.style.display="none";
  }
}

//impostazione iniziale per visualizzazione campi
display_campi(document.caricamento.impresa.value);

//impostazione iniziale per dati fatturazione
show_datifatturaz();


</SCRIPT>
<BR>
<BR>
</body>
</html>
