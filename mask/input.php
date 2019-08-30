<?php
  $pagever = "1.0";
  $pagemod = "25/06/2010 10.17.07";
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
  define("FILE_CORRENTE", "input.php");

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  $cmd['letto'] = $_REQUEST['letto'];
  $selobj = $_REQUEST['selobj'];
  
  
  $editval['idtipo'] = $_REQUEST['idtipo'];
  $editval['iddocente'] = $_REQUEST['idprof'];
  $editval['idpstudi'] = $_REQUEST['idpstudi'];
  $editval['note'] = $_REQUEST['note'];
  
  $editval['dtini'] = $_REQUEST['dtini'];
  $editval['dtfine'] = $_REQUEST['dtfine'];
  $editval['compenso'] = $_REQUEST['compenso'];  
  $editval['idsostituzione'] = $_REQUEST['idsostituzione'];
  $editval['dlezione'] = $_REQUEST['dlezione'];
  $editval['dlezione'] = ($editval['dlezione']+1) * 30;
  
  if ($cmd['letto']==1 && $cmd['comando']!=FASESUPPLENZA) {
    $cmd['inedit'] = 1;
    $cmd['comando'] = 102;
  }
  if ($cmd['comando']==FASESUPPLENZA) {
    $cmd['inedit']=2;
  }
  
  if ($_SESSION['mgdebug']==1) {
    myprint_r($_REQUEST);
  }
  
  
  if ($selobj!="") {
      $titolofinestra = _MODIFICAEVENTO_; $classtitolo = "headermod";
      //echo "XXX";
  } else {
      //$cmd['comando']=FASEINS;
      $titolofinestra = _INSERISCIEVENTO_; $classtitolo = "headerins";
      //echo "<BR> xxcmd ".$cmd;
  }
  
  if ($cmd['comando']==FASESUPPLENZA) {
    $titolofinestra = _SUPPLENZAEVENTO_; $classtitolo = "headermod";
    $styletitolo = "STYLE='background-color:green;'";
    $varysupplenza = " READONLY STYLE='color:#9A9A9A;font-style:italic;!important;'";
  }
  
  if ($selobj!="" && $cmd['letto']!=1) {
    $query = "SELECT idpstudi, iddocente, dtini, dtfine, compenso, tipo, note, idsostituzione, trashed, 
      fsost, fdel, fnotify, frecupera FROM appuntamento WHERE id=".$selobj;
      //print "<HR>".$query."<HR>";
    $result = mysql_query ($query) or die ("Error_2.0");
    $line = mysql_fetch_assoc($result);
    
    $editval['oldprof'] = $line['iddocente'];
    $editval['idpstudi'] = $line['idpstudi'];
    $editval['iddocente'] = $line['iddocente'];
    $editval['dtini'] = $line['dtini'];
    $editval['dtfine'] = $line['dtfine'];
    $editval['compenso'] = $line['compenso'];
    $editval['idtipo'] = $line['tipo'];
    $editval['note'] = $line['note'];
    $editval['idsostituzione'] = $line['idsostituzione'];
    $editval['fsost'] = $line['fsost'];
    $editval['fdel'] = $line['fdel'];
    $editval['fnotify'] = $line['fnotify'];
    $editval['frecupera'] = $line['frecupera'];
    $editval['trashed'] = $line['trashed'];
    
    $editval['dt'] = date("d/m/Y", $line['dtini']);
    $editval['oraini'] = date("H:i",$line['dtini']);
    $editval['dlezione'] = ($line['dtfine']-$line['dtini'])/60;
    
    $cmd['inedit'] = 1;
    $cmd['comando'] = 102;
    $cmd['letto']=1;
    
  }
  //myprint_r($editval);
  if ($editval['idpstudi']!="") {
    $query = "SELECT id, wkday, orai, oraf FROM prefday WHERE idpstudi=".$editval['idpstudi']." ORDER BY wkday, orai";
    //print $query;
    $result = mysql_query($query) or die ("Error.5.1");
    while ($line = mysql_fetch_assoc($result)) {
      $chiave=$line['wkday']."-".$line['orai'];
      $prefday[$chiave]['wkday']=$line['wkday'];
      $prefday[$chiave]['orai']=$line['orai'];
      $prefday[$chiave]['oraf']=$line['oraf'];
    }
    $query = "SELECT max(dtini) as lastlesson FROM appuntamento where idpstudi = ".$editval['idpstudi'];
    $result = mysql_query($query) or die("Error5.2");
    $lastlesson = mysql_fetch_assoc($result);
    //print "<HR>".$lastlesson['lastlesson']."<HR>";
    
    $curwk = date("w",$lastlesson['lastlesson']);
    //print "<HR>".$curwk."<HR>";
    if (count($prefday)==0) {
      $addgg['gg']=7;
      $addgg['oraini'] = date("H:i",$line['dtini']);
      $addgg['orafine'] = date("H:i",$line['dtfine']);
    } else {
      $addgg['gg']=100;
      foreach ($prefday as $prefkey=>$prefcur) {
        if ($prefcur['wkday']<=$curwk) {$prefcur['wkday']+=7;}
        if ($prefcur['wkday']-$curwk <$addgg['gg']) {
          $addgg['gg'] = $prefcur['wkday']-$curwk;
          $addgg['oraini'] = $prefcur['orai'];
          $addgg['orafine'] = $prefcur['oraf'];
        }
      }
    }
    $addgg['nextlesson'] = strtotime($addgg['gg']." day",$lastlesson['lastlesson']);
    //print "next lesson:".date("d/m/Y",$addgg['nextlesson']);
    //myprint_r($prefday);
    //myprint_r($addgg);
  }
  
  
  //myprint_r($cmd);
  
  echo "<FORM ACTION='input.php' METHOD='POST' NAME='caricamento'><INPUT TYPE='hidden' NAME='idsostituzione' VALUE='".$editval['idsostituzione']."'><INPUT TYPE='hidden' NAME='oldprof' VALUE='".$editval['oldprof']."'><INPUT TYPE='hidden' NAME='letto' VALUE='".$cmd['letto']."'><INPUT TYPE='hidden' NAME='cmd' VALUE='". $cmd['comando'] ."'><INPUT TYPE='hidden' NAME='selobj' VALUE='". $selobj."'><INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'><INPUT TYPE='hidden' NAME='inedit' VALUE='".$cmd['inedit']."'>";
	print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='80%'>";
  echo "<tr><td class='headermod' colspan='3'><div class='header-text'>$titolofinestra</div></td></tr>";

  echo "<TR>";
  echo "<TD CLASS='form_lbl'>"._DATAEVENTO_."</TD>";
  echo "<TD><INPUT TYPE='text' $varysupplenza size='12' NAME='dt' VALUE='". $editval['dt'] ."' STYLE='width:150px;'></TD>";
  echo "</TR>";
  
  echo "<TR>";
  echo "<TD CLASS='form_lbl'>"._ORAINIZIO_."</TD>";
  echo "<TD>";
    if ($cmd['comando']==FASESUPPLENZA) {
      print "<INPUT TYPE='text' NAME='oraini' VALUE='".$editval['oraini']."' $varysupplenza>";
    } else {
      //$apporai = mktime(0, 0, 0, 1, 1, 2010)+3600;
      print "<SELECT NAME='oraini' SIZE=1 STYLE='width:150px;' >";
      for ($n=$tt_orai;$n<=$tt_oraf;$n+=$tt_step) {
        print "<OPTION VALUE='".date("H:i",$apporai+$n)."' ".($editval['oraini']==($apporai+$n)? " SELECTED ":"").">".date("H:i",$apporai+$n)."</OPTION>";
      }
      print "</SELECT>";
    }
  print "</TD>";
  echo "</TR>";
  
  echo "<TR>";
  echo "<TD CLASS='form_lbl'>"._DOCENTE_."</TD>";
  echo "<TD><SELECT SIZE=1 NAME='idprof' STYLE='width:150px;' ".($cmd['comando']!=FASESUPPLENZA ? " onChange=stepbystep(100); ":"").">";
  
  $query = "SELECT * FROM docente WHERE attivo=1 AND trashed<>1 ORDER BY docente.nickname";
  $result = mysql_query($query) or die ("Error_1.5");
  while ($line = mysql_fetch_assoc($result)) {
    print "<OPTION VALUE='".$line['id']."' ".($editval['iddocente']==$line['id'] ? " SELECTED STYLE='font-weight:bolder;color:#0000FF;background-color:#FFFF89;' " : "").">".$line['nickname']."</OPTION>";
  }
  print "</SELECT>";
  if ($cmd['comando']!=FASESUPPLENZA) {print "&nbsp;<IMG SRC='../img/next.png' ALIGN='absmiddle' BORDER='0' onClick=stepbystep(100);>";}
  print "</TD>";
  echo "</TR>";
  
  if ($cmd['comando']>=100) {
    echo "<TR>";
    echo "<TD CLASS='form_lbl'>"._TIPOEVENTO_."</TD>";
    echo "<TD>";
      if ($cmd['comando']==FASESUPPLENZA) {
        $query = "SELECT * FROM tipo WHERE id=".$editval['idtipo'];
        $result = mysql_query ($query) or die ("Error_1.2");
        $line = mysql_fetch_assoc($result);
        print "<INPUT TYPE='hidden' NAME='idtipo' VALUE='".$editval['idtipo']."'><INPUT TYPE='text' NAME='lbltipo' VALUE='".convlang($line['descr'])."' $varysupplenza>";
      } else {
        print "<SELECT SIZE=1 NAME='idtipo' STYLE='width:150px;' onChange=stepbystep(101);>";
    
        $query = "SELECT * FROM tipo";
        $result = mysql_query ($query) or die ("Error_1.2");
        while ($line = mysql_fetch_assoc($result)) {
          print "<OPTION VALUE='".$line['id']."' ".($editval['idtipo']==$line['id'] ? " SELECTED STYLE='font-weight:bolder;color:#0000FF;background-color:#FFFF89;' " : "").">".convlang($line['descr'])."</OPTION>";
        }
        print "</SELECT>";
        print "&nbsp;<IMG SRC='../img/next.png' ALIGN='absmiddle' BORDER='0' onClick=stepbystep(101);>";
      }
    //print "<INPUT TYPE='button' NAME='upd1' VALUE='update' onClick=stepbystep(101);>";
    print "</TD>";
    echo "</TR>";
  }
  
  if ($cmd['comando']>=101 && $editval['idtipo']==1) {
    echo "<TR>";
    echo "<TD CLASS='form_lbl'>"._CLASSE_."</TD>";
    echo "<TD>";
      if ($cmd['comando']==FASESUPPLENZA) {
        $query = "SELECT pstudi.id as id, classi.descr as descr,pstudi.compenso as compenso, pstudi.dlezione as dlezione FROM pstudi LEFT JOIN classi ON pstudi.idclasse = classi.id AND classi.trashed<>1 WHERE pstudi.id=".$editval['idpstudi'];
        $result = mysql_query ($query) or die ("Error_1.2");
        $line = mysql_fetch_assoc($result);
        print "<INPUT TYPE='hidden' NAME='idpstudi' VALUE='".$editval['idpstudi']."'><INPUT TYPE='text' NAME='lblpstudi' VALUE='".$line['descr']."' $varysupplenza>";        
      } else {
        print "<SELECT SIZE=1 NAME='idpstudi' STYLE='width:150px;' onChange=stepbystep(102);>";
        $query = "SELECT pstudi.id as id, classi.descr as descr,pstudi.compenso as compenso, pstudi.dlezione as dlezione FROM pstudi LEFT JOIN classi ON pstudi.idclasse = classi.id AND classi.trashed<>1 WHERE pstudi.trashed<>1";
        $result = mysql_query ($query) or die ("Error_1.4");
        while ($line = mysql_fetch_assoc($result)) {
          print "<OPTION VALUE='".$line['id']."' ".($editval['idpstudi']==$line['id'] ? " SELECTED  CLASS='OPTION_SELEZIONATA' " : "").">".$line['descr']."</OPTION>";
          if ($editval['idpstudi']==$line['id'] && $selobj=="") {
            $editval['compenso']=$line['compenso'];
            $editval['dlezione']=$line['dlezione'];
          }
        }
        print "</SELECT>";
        print "&nbsp;<IMG SRC='../img/next.png' ALIGN='absmiddle' BORDER='0' onClick=stepbystep(102);>";      
      }
    //print "<INPUT TYPE='button' NAME='upd1' VALUE='update' onClick=stepbystep(102);>";
    print "</TD>";
    echo "</TR>";
  } else {
    print "<INPUT TYPE='hidden' NAME='idpstudi' VALUE='-1'>";
  }
  
  if (($editval['idtipo']!=1 && $cmd['comando']>=101) || $cmd['comando']>=102) {
    echo "<TR>";
    echo "<TD CLASS='form_lbl'>"._NOTES_."</TD>";
    echo "<TD>";
      print "<TEXTAREA NAME='note' COLS='30' ROWS='5'>".$editval['note']."</TEXTAREA>";
    print "</TD>";
    echo "</TR>";
    
    echo "<TR>";
    echo "<TD CLASS='form_lbl'>"._DURATA_."</TD>";
    print "<TD>";
      $app = mktime(0, 0, 0, 1, 1, 2010);
      if ($cmd['comando']==FASESUPPLENZA) {
        print "<INPUT TYPE='hidden' NAME='dlezione' VALUE='".(($editval['dlezione']/30)-1)."'><INPUT TYPE='text' NAME='lbldlezione' VALUE='".date("H:i",$app+($editval['dlezione']*60))."' $varysupplenza>";
      } else {
        print "<SELECT $isreadonly NAME='dlezione' SIZE=1 STYLE='width:150px;'>";
        for ($i=1;$i<=20;$i++) {
          print "<OPTION VALUE='".$i."' ".(($editval['dlezione']/30)-1==$i ? " SELECTED STYLE='font-weight:bolder;color:#0000FF;background-color:#FFFF89;' ":"").">".date("H:i",($i*30*60)+$app)."</OPTION>";
        }
        print "</SELECT></TD>";      
      }
    echo "</TR>";
    
    echo "<TR>";
    echo "<TD CLASS='form_lbl'>"._COMPENSO_."</TD>";
    echo "<TD><INPUT TYPE='text' size='12' NAME='compenso' VALUE='". $editval['compenso'] ."' STYLE='width:150px;'></TD>";
    echo "</TR>";
    
    print "<TR><TD COLSPAN=3 ALIGN=CENTER><DIV id='metodocanc' STYLE='display:none;'><SELECT NAME='tipocanc' SIZE='1'>";
    print "<OPTION VALUE=1>"._OPZCANCNONOTIFY_."</OPTION>";
    if ($editval['idtipo']==1) {
      print "<OPTION VALUE=2>"._OPZCANCNORECUPERO_."</OPTION>";
      print "<OPTION VALUE=3>"._OPZCANCSIRECUPERO_."</OPTION>";
      
    } else {
      print "<OPTION VALUE=2>"._OPZCANCSINOTIFY_."</OPTION>";
    }
    print "</SELECT><INPUT TYPE='hidden' NAME='sceltacanc' VALUE='0'><INPUT TYPE='hidden' NAME='insandcanc' VALUE='0'><INPUT TYPE='hidden' NAME='idtocanc' VALUE=''><BR>"._LBLDATAPREVRECUPERO_." <INPUT TYPE='text' NAME='lblnextdata' VALUE='".date("d/m/Y",$addgg['nextlesson'])."' READONLY></TD></TR>";
    
    echo "<TR><TD COLSPAN=3 align='center' height='70'>";
    if ($editval['fsost']==-3) {
      print "<INPUT TYPE='button' VALUE='"._BTNCONFSUPPLENZA_."' onClick=supp_conf();>";
    }
    if ($editval['fsost']!=-2) {
      print "<INPUT TYPE='button' VALUE='"._BTNSALVA_."' onClick=inserisci();>";
      if ($selobj!="") {
        print "&nbsp;-&nbsp;<INPUT TYPE='button' NAME='cancella' VALUE='"._BTNCANCELLA_."' onClick=canc();>";
      }
      print "&nbsp;-&nbsp;";
    } else {
      print _LBLSUBSTREQUEST_."<BR><BR>";
    }
    print "<INPUT TYPE='button' NAME='chiudi' VALUE='"._BTNCHIUDI_."' onClick=\"window.parent.opener.focus();window.parent.close();\">";
    
    if ($editval['fsost']!=-2) { 
      print "<BR><BR><INPUT TYPE='button' NAME='btnsupplenza' VALUE='"._BTNSUPPLENZA_."' onClick=\"stepbystep(".FASESUPPLENZA.");\">";
    }
    print "</TD></TR>";
  
  }
  
  echo "</TABLE></FORM>";

  include ("finepagina.php");
  
?>

<SCRIPT>
  //script load data/ora
  if (window.parent.opener.document.aggdati.cmd.value==100) {
    document.caricamento.dt.value = window.parent.opener.document.aggdati.dt.value;
    document.caricamento.oraini.value = window.parent.opener.document.aggdati.oraini.value; 
  }
  
function stepbystep(cmd) {
  window.parent.opener.document.aggdati.dt.value = document.caricamento.dt.value;
  window.parent.opener.document.aggdati.oraini.value = document.caricamento.oraini.value;
  
  document.caricamento.cmd.value=cmd;
  document.caricamento.submit();
}

function aggparent() {
  alert(window.parent.opener.document.carica.compenso.value);
}

function inserisci() {
  if (document.caricamento.inedit.value==1) {
    window.parent.opener.document.aggdati.cmd.value=<?=FASEMOD?>;
  } else {
    window.parent.opener.document.aggdati.cmd.value=<?=FASEINS?>;
  }
  if (document.caricamento.inedit.value==2) {
    window.parent.opener.document.aggdati.cmd.value=<?=FASESUPPLENZA?>;
  }
  window.parent.opener.document.aggdati.dt.value=document.caricamento.dt.value;
  window.parent.opener.document.aggdati.oraini.value=document.caricamento.oraini.value;
  window.parent.opener.document.aggdati.idprof.value=document.caricamento.idprof.value;
  window.parent.opener.document.aggdati.idtipo.value=document.caricamento.idtipo.value;
  window.parent.opener.document.aggdati.idpstudi.value=document.caricamento.idpstudi.value;
  window.parent.opener.document.aggdati.note.value=document.caricamento.note.value;
  window.parent.opener.document.aggdati.dlezione.value=document.caricamento.dlezione.value;
  window.parent.opener.document.aggdati.compenso.value=document.caricamento.compenso.value;
  window.parent.opener.document.aggdati.insandcanc.value=document.caricamento.insandcanc.value;
  window.parent.opener.document.aggdati.idtocanc.value=document.caricamento.idtocanc.value;
  
  window.parent.opener.document.aggdati.submit();
  window.parent.opener.focus();
  window.parent.close();
  return false;
}

function canc() {
  if (document.caricamento.sceltacanc.value=="1") {
    var avanti = confirm ("<?=_MSGSEISICURO_?>");
  	if (avanti==true) {
  	  if (document.caricamento.tipocanc.selectedIndex==0) {
        window.parent.opener.document.aggdati.cmd.value=<?=FASEDEL?>;
        window.parent.opener.document.aggdati.selobj.value=document.caricamento.selobj.value;
        window.parent.opener.document.aggdati.idprof.value=document.caricamento.idprof.value;
        
        window.parent.opener.document.aggdati.submit();
        window.parent.opener.focus();
        window.parent.close();
      }
      if (document.caricamento.tipocanc.selectedIndex==2) {
        document.caricamento.dt.value="<?php print date("d/m/Y",$addgg['nextlesson']);?>";
        document.caricamento.inedit.value=0;
        document.caricamento.insandcanc.value=1;
        document.caricamento.idtocanc.value="<?=$selobj?>";
        inserisci();
      }
      if (document.caricamento.tipocanc.selectedIndex==1) {
        window.parent.opener.document.aggdati.cmd.value=<?=FASEDELSAMEDAY?>;
        window.parent.opener.document.aggdati.selobj.value=document.caricamento.selobj.value;
        window.parent.opener.document.aggdati.idprof.value=document.caricamento.idprof.value;
        
        window.parent.opener.document.aggdati.submit();
        window.parent.opener.focus();
        window.parent.close();
      }
      return false;
  	}
  } else {
    var elem = document.getElementById("metodocanc");  
    elem.style.display = "block";
    document.caricamento.sceltacanc.value="1";
  }
}

function supp_conf() {
  var avanti = confirm ("<?=_MSGSEISICURO_?>");
	if (avanti==true) {
		window.parent.opener.document.aggdati.action="dbquery.php";
    window.parent.opener.document.aggdati.cmd.value=<?=FASECONFSUPPLENZA?>;
    window.parent.opener.document.aggdati.selobj.value=document.caricamento.selobj.value;
    window.parent.opener.document.aggdati.idprof.value=document.caricamento.idprof.value;
    window.parent.opener.document.aggdati.idsostituzione.value=document.caricamento.idsostituzione.value;
    
    window.parent.opener.document.aggdati.submit();
    window.parent.opener.focus();
    window.parent.close();
    return false;
	}
}

</SCRIPT>
<?php
  if ($editval['fsost']==-1) {
    print "<SCRIPT>stepbystep(".FASESUPPLENZA.");</SCRIPT>";
  }
?>
</body>
</html>
