<?php
  $pagever = "1.0";
  $pagemod = "31/01/2011 10.22.44";
  require_once("form_cfg.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <link rel="stylesheet" type="text/css" href="../css/main.css" />
  <link rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></link>
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
<script type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<?php
  include ("iniziopagina.php");

  //CARICAMENTO VALORI PER EDIT
  define("FILE_CORRENTE", "frm_contab_anagbanchepopup.php");

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  
  
  $query = "SELECT id, nomebanca FROM banca WHERE trashed<>1 ORDER BY nomebanca";
  $result = mysql_query($query) or die ("Error 1.1");
  while ($line = mysql_fetch_assoc($result)) {
    $chiave = $line['id'];
    $vetbanca[$chiave]['nomebanca'] = $line['nomebanca'];
    
    if ($cmd['comando']==FASEREQUERY) {
      $selectbanche .= "<OPTION VALUE='".$chiave."'>".$line['nomebanca']."</OPTION>";
    }
  }
  
  print "<BR><H1>"._ANAGRAFICABANCA_."</H1>";
  print "<BR>";
  
  //form
    print "<FORM NAME='caricamento' METHOD=POST ACTION='".FILE_DBQUERY."'>";
    print "<INPUT TYPE='hidden' NAME='modificato' VALUE='0'><INPUT TYPE='hidden' NAME='ne' VALUE='".count($vetbanca)."'><INPUT TYPE='hidden' NAME='cmd' VALUE='". FASEMOD ."'><INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'>";
    print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='80%'>";
    print "<TR><TD ALIGN=CENTER STYLE='text-aling:center !important;'>"._LBL_MODIFICABANCHE_."</TD></TR>";
    $i=1;
    foreach ($vetbanca as $key =>$cur) {
      print "<TR>";
      print "<TD>";
        print "<DIV id='sh_$i' STYLE='display:block;'><IMG SRC='../img/mod.png' onClick=edita($i)>&nbsp;<IMG SRC='../img/canc.png' onClick=cancella($i)>&nbsp;<SPAN id='label_$i'>".$cur['nomebanca']."</SPAN><INPUT TYPE='hidden' NAME='banca_delete_$i' VALUE=''></DIV>";
        print "<DIV id='edt_$i' STYLE='display:none;'><INPUT TYPE='hidden' NAME='mod_$i' VALUE='0'><INPUT TYPE='hidden' NAME='id_$i' VALUE='$key'><INPUT TYPE='text' NAME='nomebanca_$i' VALUE='".$cur['nomebanca']."'></DIV>";
      print "</TD>";
      print "</TR>";
      $i++;
    }
    print "</TABLE><BR>";
    
    print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='80%'>";
    print "<TR><TD ALIGN=CENTER STYLE='text-aling:center !important;'>"._LBL_NUOVABANCA_."</TD></TR>";
    print "<TR><TD><INPUT TYPE='text' NAME='banca_new' VALUE=''></TD></TR>";
    print "</TABLE><BR>";
    
    print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='80%'>";
    print "<TR><TD STYLE='text-align:center;'><INPUT TYPE='button' NAME='invia' VALUE='"._LBLCONFERMA_."' onClick=btn_salva()></TD></TR>";
    print "</TABLE>";
    print "</FORM>";
    
    

  include ("finepagina.php");
?>
<SCRIPT>
function requery() {
  var blocco_banca = opener.document.getElementById("idbanca");
  blocco_banca.innerHTML = unescape("<?=fullescape($selectbanche)?>");
  self.close()
}

function btn_salva() {
  if (document.caricamento.modificato.value=="0" && document.caricamento.banca_new.value=="") {
    //no modfiicato
    self.close();
  } else {
    //modificato
    document.caricamento.submit();
  }
  
  //self.close();
}

function edita(chiave) {
  document.caricamento.modificato.value="1";
  
  var elem = document.getElementById("edt_"+chiave);  
  elem.style.display = "block";
  
  var elem2 = document.getElementById("sh_"+chiave);
  elem2.style.display = "none";
  
  document.caricamento["mod_"+chiave].value="1";  
}


function cancella(chiave){
  document.caricamento.modificato.value="1";
  var elem = document.getElementById("label_"+chiave);  
  elem.style.textDecoration = "line-through";
  elem.style.color = "#DEDEDE";
  document.caricamento["banca_delete_"+chiave].value="1";
  
}

<?
  if ($cmd['comando']==FASEREQUERY) {
    print "requery();";
  }
?>

</SCRIPT>
<BR>
<BR>
</body>
</html>
