<?php
	function stampa_actselect($tipo, $selopt=-99999) {
	 $str="";
    //TIPO: 1 NEW   2 DB NO LEZIONE    3 LEZIONE
    $str.= "<OPTION VALUE='1' ".($selopt==1 ? " SELECTED ":"").">"._NOCHANGE_."</OPTION>";
    if ($tipo==-1) {
      $str.= "<OPTION VALUE='2' ".($selopt==2 ? " SELECTED ":"").">"._NOINS_."</OPTION>";
    } else {
      if ($tipo==1) {
        $str.= "<OPTION VALUE='3' ".($selopt==3 ? " SELECTED ":"").">"._SOST_."</OPTION>";
        $str.= "<OPTION VALUE='4' ".($selopt==4 ? " SELECTED ":"").">"._DELSAMED_."</OPTION>";
      }
      $str.= "<OPTION VALUE='5' ".($selopt==5 ? " SELECTED ":"").">"._DELNOTIF_."</OPTION>";
    }
    return $str;
  }
  
  $pagever = "1.0";
  $pagemod = "23/06/2010 16.51.48";
  define("APP_DEBUG",0);
  
  
  require_once ("form_cfg.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <link rel="stylesheet" type="text/css" href="../css/main.css" />
  <link rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></link>
</head>
<body onLoad=aldbquery();>
<script type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<?
  
  $cmd = $_REQUEST['cmd'];
  $conflittocmd = $_REQUEST['conflittocmd'];
	$codicepagina = $_REQUEST['codicepagina'];
	$valori['selobj'] = $_REQUEST['selobj'];
	$valori['nuovadtini'] = $_REQUEST['nuovadtini'];
	$valori['idnuovadtini'] = $_REQUEST['idnuovadtini'];
	
	//in comune
	$valori['compenso']=$_REQUEST['compenso'];
  if ($valori['compenso']=="") {$valori['compenso']=0;}
  $valori['note'] = $_REQUEST['note'];
  $valori['tipo']=$_REQUEST['idtipo'];
  $valori['idsostituzione']=$_REQUEST['idsupplente'];
  $valori['newdt'] = $_REQUEST['newdt'];
  $valori['idpstudi'] = $_REQUEST['idpstudi'];
  $valori['prefne'] = $_REQUEST['prefne'];
  for ($i=1;$i<=$valori['prefne'];$i++) {
    $valori['wkday_'.$i] = $_REQUEST['wkday_'.$i];
    $valori['orai_'.$i] = $_REQUEST['orai_'.$i];
    $valori['oraf_'.$i] = $_REQUEST['oraf_'.$i];
    $valori['iddocente_'.$i] = $_REQUEST['iddocente_'.$i];
  }
  $valori['dataini'] = $_REQUEST['dataini'];
  $valori['datafine'] = $_REQUEST['datafine'];
  $valori['insandcanc'] = $_REQUEST['insandcanc'];
  $valori['idtocanc'] = $_REQUEST['idtocanc'];
  $valori['lesdone'] = $_REQUEST['lesdone'];
  $valori['les_delid'] = $_REQUEST['les_delid'];
    if ($valori['les_delid']=="") {$valori['les_delid']="-1";}
	
	if ($conflittocmd==2) {
    //2° giro analizzo gli eventi, tolgo e normalizzo
    $evtne = $_REQUEST['evtne'];
    $evtdbne = $_REQUEST['evtdbne'];
    $valori['backurl'] = $_REQUEST['backurl'];
    $j=0;
    
    //newevt
    for ($i=1;$i<=$evtne;$i++) {
      if ($_REQUEST['act'.$i]==1) {
        $j++;
        if ($valori['idnuovadtini']==$i) {
          $apporai=$_REQUEST['evtdataini_'.$i]-day_mezzanotte($_REQUEST['evtdataini_'.$i]);
          $apporaf=$_REQUEST['evtdatafine_'.$i]-day_mezzanotte($_REQUEST['evtdatafine_'.$i]);
          
          $valori['evtdataini_'.$j] = eT_dt2times($valori['nuovadtini'])+$apporai;
          $valori['evtdatafine_'.$j] = eT_dt2times($valori['nuovadtini'])+$apporaf;
        } else {
          $valori['evtdataini_'.$j] = $_REQUEST['evtdataini_'.$i];
          $valori['evtdatafine_'.$j] = $_REQUEST['evtdatafine_'.$i];
        }
        $valori['evtiddocente_'.$j] = $_REQUEST['evtiddocente_'.$i];
      }
    }
    $valori['evtne'] = $j;
    
    //dbevt
    for ($i=1;$i<=$evtdbne;$i++) {
      $id = $_REQUEST['evtdbid'.$i];
      $valori['dbid'.$id] = $_REQUEST['dbact'.$i];
    }
    
    
  } else {
    switch ($codicepagina) {
      case "TIMETAB":
        $valori['iddocente'] = $_REQUEST['idprof'];
        $valori['dt']=$_REQUEST['dt'];
        $valori['oraini']= $_REQUEST['oraini'];
        $valori['durata'] = $_REQUEST['dlezione'];
        $valori['backurl'] = "timetable.php";
        break;
      
      case "PSTUDIO_EVT":
        $valori['evtne'] = $_REQUEST['evtne'];
        for ($i=1;$i<=$valori['evtne'];$i++) {
          $valori['evtdataini_'.$i] = $_REQUEST['evtdataini_'.$i];
          $valori['evtdatafine_'.$i] = $_REQUEST['evtdatafine_'.$i];
          $valori['evtiddocente_'.$i] = $_REQUEST['evtiddocente_'.$i];
        }
        $valori['tipo']=1;
        $valori['backurl'] = "frm_classi.php";
        break;
    }
     
    //normalizzazione, caso del timetab è un multiins con 1 solo elemento
    if ($codicepagina=="TIMETAB") {
      $valori['evtne'] = 1;
      $valori['evtdataini_1'] = eT_dt2times($valori['dt']) + eT_ora2times($valori['oraini'],0);
      $valori['evtdatafine_1'] = $valori['evtdataini_1'] + ($valori['durata']+1) * 30 *60;
      $valori['evtiddocente_1'] = $valori['iddocente'];
    }
  
  }
	
	$query = "SELECT * FROM tipo";
	$result = mysql_query($query) or die ("Error5.1");
	while ($line = mysql_fetch_assoc($result)) {
    $vettipo[$line['id']]=$line['descr'];
  }
  
  $query = "SELECT id, nome FROM docente";
	$result = mysql_query($query) or die ("Error5.2");
	while ($line = mysql_fetch_assoc($result)) {
    $vetdocente[$line['id']]=$line['nome'];
  }
  
  $query = "SELECT id, descr FROM pstudi";
	$result = mysql_query($query) or die ("Error5.2");
	while ($line = mysql_fetch_assoc($result)) {
    $vetpstudi[$line['id']]=$line['descr'];
  }
	
  //myprint_r($_REQUEST);
  //myprint_r($valori);

  print "<BR><H1>"._TITCONFLITTO_."</H1>";
  
  $j=0; 
  $evtne = $valori['evtne'];
  $inconflitto=false;
  print "<FORM NAME='caricamento' ACTION='conflitto.php' METHOD=POST>
      <INPUT TYPE='hidden' NAME='cmd' VALUE='".$cmd."'><INPUT TYPE='hidden' NAME='codicepagina' VALUE='".$codicepagina."'><INPUT TYPE='hidden' NAME='selobj' VALUE='".$valori['selobj']."'>"; 
  for ($i=1;$i<=$evtne;$i++) {
    //print "<HR>".date ("d/m/Y H:i",$valori['evtdataini_'.$i])."<HR>".$valori['evtdataini_'.$i]."<HR>".date ("d/m/Y H:i",$valori['evtdatafine_'.$i])."<HR>".$valori['evtdatafine_'.$i]."<HR>";
    $dtini_midnight = day_mezzanotte($valori['evtdataini_'.$i]);
    $query = "SELECT appuntamento.dtini as appuntamento_dtini, appuntamento.dtfine as appuntamento_dtfine, 
      appuntamento.id as appuntamento_id, appuntamento.tipo as appuntamento_tipo, appuntamento.note as appuntamento_note,
      pstudi.descr as pstudi_descr
    FROM appuntamento LEFT JOIN pstudi ON appuntamento.idpstudi=pstudi.id
    WHERE appuntamento.iddocente=".$valori['evtiddocente_'.$i]." AND appuntamento.trashed<>1 AND appuntamento.fsost<>1 AND appuntamento.fdel<>1 
      AND appuntamento.id NOT IN (".$valori['les_delid'].") ";
    
    if ($valori['selobj']!="") {$query .=" AND appuntamento.id<>".$valori['selobj']." ";}  
    
    $query .= " AND appuntamento.dtini>=".$dtini_midnight." AND appuntamento.dtini<".strtotime("1 day", $dtini_midnight)."
      AND appuntamento.dtfine>=".$dtini_midnight." AND appuntamento.dtfine<".strtotime("1 day", $dtini_midnight)."
      AND (
        (appuntamento.dtini > ".$valori['evtdataini_'.$i]." AND ".$valori['evtdatafine_'.$i].">appuntamento.dtini) OR
        (appuntamento.dtfine > ".$valori['evtdataini_'.$i]." AND ".$valori['evtdatafine_'.$i].">appuntamento.dtfine) OR
        (".$valori['evtdataini_'.$i]." >= appuntamento.dtini AND ".$valori['evtdatafine_'.$i]." <= appuntamento.dtfine)
      )";
    
    //print "<HR>".$query."<HR>";
    
    $result = mysql_query($query) or die("Error_1.1");
    print "<INPUT TYPE='hidden' NAME='evtdataini_".$i."' VALUE='".$valori['evtdataini_'.$i]."'>
    <INPUT TYPE='hidden' NAME='evtdatafine_".$i."' VALUE='".$valori['evtdatafine_'.$i]."'>
    <INPUT TYPE='hidden' NAME='evtiddocente_".$i."' VALUE='".$valori['evtiddocente_'.$i]."'>
    <INPUT TYPE='hidden' NAME='evttipo".$i."' VALUE='1'>";
    if (mysql_num_rows($result)>0) {
      print "<TABLE CLASS=lista_table ALIGN=CENTER STYLE='width:60%;'>";
      print "<TR CLASS=lista_riga>";
      print "<TD CLASS=lista_tittab>"._DOCENTE_."</TD><TD CLASS=lista_tittab>"._TIPOEVENTO_."</TD><TD CLASS=lista_tittab>"._DATAEVENTO_."</TD><TD CLASS=lista_tittab>"._LBLAZIONE_."</TD></TR>";
      
      print "<TR CLASS=lista_riga>";
      print "<TD CLASS=lista_col ".STYLE_RIGAORANGE.">".$vetdocente[$valori['evtiddocente_'.$i]]."</TD>";
      print "<TD CLASS=lista_col ".STYLE_RIGAORANGE.">".convlang($vettipo[$valori['tipo']])." ".$vetpstudi[$valori['idpstudi']]."<BR>".$valori['note']."</TD>";
      print "<TD CLASS=lista_col ".STYLE_RIGAORANGE.">"._LBLINIZIO_." ".date("d/m/Y H:i",$valori['evtdataini_'.$i])."<BR>"._LBLFINE_." ".date("d/m/Y H:i",$valori['evtdatafine_'.$i])."</TD>";
      print "<TD CLASS=lista_col ".STYLE_RIGAORANGE.">";
        print "<SELECT NAME='act".$i."' SIZE=1>";
        print stampa_actselect(-1);
      print "</SELECT>&nbsp;<input type=\"button\" value=\""._BRNCALENDARIO_."\" onclick=\"displayCalendar(document.caricamento.nuovadtini_$i,'dd/mm/yyyy',this)\" STYLE=\"margin-top:2px;\">&nbsp;New date:<INPUT TYPE='text' NAME='nuovadtini_$i' VALUE='".date("d/m/Y",$valori['evtdataini_'.$i])."' onChange=caricanuovadtini(this.value,$i);></TD>";
      print "</TR>";
      print "<TR CLASS=lista_riga><TD CLASS=lista_col COLSPAN=4 STYLE='background-color:#FFFFFF;font-size:14px;font-variant:small-caps;'>"._TITINCONFLITTOCON_."</TD></TR>";
      
      
      /*print "CONFLITTO<BR>";
      print "Evento da inserire: ".date("d/m/Y H:i",$valori['evtdataini_'.$i])." - ".date("d/m/Y H:i",$valori['evtdatafine_'.$i])." TIPO:".$valori['tipo']." DOCENTE:".$valori['evtiddocente_'.$i]."<BR>";
      print "<SELECT NAME='act".$i."' SIZE=1>";
      print stampa_actselect(-1);
      print "</SELECT>";
      print "<BR>in conflitto con<BR>";*/ 
      while ($line = mysql_fetch_assoc($result)) {
        $j++;
        
        $curid = $line['appuntamento_id'];
        if (!isset($valori['dbid'.$curid]) || $valori['dbid'.$curid]==1) {$inconflitto=true;}
        print "<TR CLASS=lista_riga>";
        print "<TD CLASS=lista_col>".$vetdocente[$valori['evtiddocente_'.$i]]."</TD>";
        print "<TD CLASS=lista_col>".convlang($vettipo[$line['appuntamento_tipo']])." ".$line['pstudi_descr']."<BR>".$line['appuntamento_note']."</TD>";
        print "<TD CLASS=lista_col>"._LBLINIZIO_." ".date("d/m/Y H:i",$line['appuntamento_dtini'])."<BR>"._LBLINIZIO_." ".date("d/m/Y H:i",$line['appuntamento_dtfine'])."</TD>";
        print "<TD CLASS=lista_col>";
        print "<INPUT TYPE='hidden' NAME='evtdbid".$j."' VALUE='".$line['appuntamento_id']."'>
          <SELECT NAME='dbact".$j."' SIZE=1>";
          print stampa_actselect($line['appuntamento_tipo'],$valori['dbid'.$curid]);
        print "</SELECT></TD>";
        print "</TR>";
        
        /*
        print date("d/m/Y H:i", $line['appuntamento_dtini'])." - ".date("d/m/Y H:i", $line['appuntamento_dtfine'])." - ".$line['appuntamento_id'];
        print "<INPUT TYPE='text' NAME='evtdbid".$j."' VALUE='".$line['appuntamento_id']."'>
          <SELECT NAME='dbact".$j."' SIZE=1>";
        print stampa_actselect($line['appuntamento_tipo'],$valori['dbid'.$curid]);
        print "</SELECT>";
        print "<HR>";
        */
      } 
    } else {
      print "<INPUT TYPE='hidden' NAME='act".$i."' VALUE='1'>";
    }
  }
  print "</TABLE><BR>";
  $appdlezione = ((($valori['evtdatafine_1']-$valori['evtdataini_1']) / 60) / 30) -1;
    
  print "<INPUT TYPE='hidden' NAME='compenso' VALUE='".$valori['compenso']."'>
  <INPUT TYPE='hidden' NAME='idpstudi' VALUE='".$valori['idpstudi']."'>
  <INPUT TYPE='hidden' NAME='idtipo' VALUE='".$valori['tipo']."'>
  <INPUT TYPE='hidden' NAME='note' VALUE='".$valori['note']."'>
  <INPUT TYPE='hidden' NAME='lesdone' VALUE='".$valori['lesdone']."'>
  <INPUT TYPE='hidden' NAME='les_delid' VALUE='".$valori['les_delid']."'>
  <INPUT TYPE='hidden' NAME='nuovadtini' VALUE=''>
  <INPUT TYPE='hidden' NAME='idnuovadtini' VALUE=''>";
  
  if ($codicepagina=="TIMETAB") {
    print "<INPUT TYPE='hidden' NAME='dt' VALUE='".date("d/m/Y",$valori['evtdataini_1'])."'>
    <INPUT TYPE='hidden' NAME='oraini' VALUE='".date("H:i",$valori['evtdataini_1'])."'>
    <INPUT TYPE='hidden' NAME='dlezione' VALUE='".$appdlezione."'>
    <INPUT TYPE='hidden' NAME='insandcanc' VALUE='".$valori['insandcanc']."'>
    <INPUT TYPE='hidden' NAME='idtocanc' VALUE='".$valori['idtocanc']."'>
    <INPUT TYPE='hidden' NAME='idprof' VALUE='".$valori['evtiddocente_1']."'>";
  }
  if ($codicepagina=="PSTUDIO_EVT") {
    print "<INPUT TYPE='hidden' NAME='prefne' VALUE='".$valori['prefne']."'>";
    print "<INPUT TYPE='hidden' NAME='dataini' VALUE='".$valori['dataini']."'>";
    print "<INPUT TYPE='hidden' NAME='datafine' VALUE='".$valori['datafine']."'>";
    for ($i=1;$i<=$valori['prefne'];$i++) {
      print "<INPUT TYPE='hidden' NAME='wkday_".$i."' VALUE='".$valori['wkday_'.$i]."'>";
      print "<INPUT TYPE='hidden' NAME='orai_".$i."' VALUE='".$valori['orai_'.$i]."'>";
      print "<INPUT TYPE='hidden' NAME='oraf_".$i."' VALUE='".$valori['oraf_'.$i]."'>";
      print "<INPUT TYPE='hidden' NAME='iddocente_".$i."' VALUE='".$valori['iddocente_'.$i]."'>";
    }
  }
  
  print "<INPUT TYPE='hidden' NAME='evtne' VALUE='".$valori['evtne']."'>
  <INPUT TYPE='hidden' NAME='evtdbne' VALUE='".$j."'>
  <INPUT TYPE='hidden' NAME='backurl' VALUE='".$valori['backurl']."'>
  <INPUT TYPE='hidden' NAME='conflittocmd' VALUE='2'>";
  if ($inconflitto) {
    print "<BR><DIV STYLE='text-align:center;font-size:14px;font-weight:bolder;color:#FF0000;'>RILEVATI CONFLITTI</DIV><INPUT TYPE='hidden' NAME='inconflitto' VALUE='1'><BR>";
  } else {
    print "<BR><DIV STYLE='text-align:center;font-size:14px;font-weight:bolder;color:#00FF00;'>NESSUN CONFLITTO RILEVATO<INPUT TYPE='hidden' NAME='inconflitto' VALUE='0'></DIV><BR>";
  }
  
  print "<CENTER><INPUT TYPE='submit' NAME='btnelabora' id='btnelabora' VALUE='"._BTNELABCONFL_."' STYLE='height:50px;width:200px;font-size:12px;'></FORM></CENTER>";
  ?>
  <FORM NAME='saltodiretto' ACTION='<?=$valori['backurl']?>' METHOD='POST'></FORM>
  <SCRIPT>
  function aldbquery() {
    if (document.caricamento.inconflitto.value=="0") {
      if (parseInt(document.caricamento.evtne.value, 10)>0) {
        document.caricamento.action="dbquery.php";
        document.caricamento.submit();
      } else {
        document.saltodiretto.submit();
      }
    }
  }
  function caricanuovadtini(valore,id){
    document.caricamento.nuovadtini.value=valore;
    document.caricamento.idnuovadtini.value=id;
  }
  </SCRIPT>
  <?
  /*
  
  
  $dataini = mktime(10, 00, 0, 7, 6, 2009);
  $datafine = mktime(11, 00, 0, 7, 6, 2009);
  
  $dtini_midnight = day_mezzanotte($dataini);
  
  $query = "SELECT * 
    FROM appuntamento 
    WHERE appuntamento.dtini>=".$dtini_midnight." AND appuntamento.dtini<".strtotime("1 day", $dtini_midnight)."
      AND appuntamento.dtfine>=".$dtini_midnight." AND appuntamento.dtfine<".strtotime("1 day", $dtini_midnight)."
      AND (
        (appuntamento.dtini > ".$dataini." AND ".$datafine.">appuntamento.dtini) OR
        (appuntamento.dtfine > ".$dataini." AND ".$datafine.">appuntamento.dtfine) OR
        (".$dataini." > appuntamento.dtini AND ".$datafine." < appuntamento.dtfine)
      )";
  print "<PRE>";
  print $query;
  */
  
  
?>
