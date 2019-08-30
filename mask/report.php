<?php
  //CLASSI GESTISCE:
  //pstudi in quanto istanza di un corso
  //classi in quanto relazione per studente <-> pstudi
  
  $pagever = "1.0";
  $pagemod = "30/06/2010 13.39.21";
  require_once("form_cfg.php");
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../css/main.css" />
<link rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></link>
</head>

<body>
<script type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<?php
  print "<BR><H1>"._TITREPORT_."</H1><BR>";
  print "<TABLE><TR><TD ROWSPAN=2 STYLE='font-size:14px;font-weight:bolder;'>1</TD><TD>"._REPORT1TIT_."</TD></TR>";
  print "<TR><TD><FORM NAME='reportdocente' METHOD=POST ACTION='../stampe/mpdf/examples/cedolino.php' TARGET=_blank>";
  
  print _DOCENTE_." <SELECT NAME='selobj' SIZE=1>";
  $query = "SELECT id, nickname FROM docente WHERE trashed<>1 AND attivo=1 ORDER BY nickname";
  $result = mysql_query($query);
  while ($line = mysql_fetch_assoc($result)) {
    print "<OPTION VALUE=".$line['id'].">".$line['nickname']."</OPTION>";
  }
  print "</SELECT>";
  
  print "&nbsp;&nbsp;&nbsp;";
  
  $iniziomese = mktime(0, 0, 0, date("m"), 1, date("Y"));
  $finemese = strtotime("1 month", $iniziomese);
  $finemese = strtotime("-1 day", $finemese);
  
  print _PERIODO_.": "._DATAINIZIO_." <INPUT TYPE='text' NAME='dataini' VALUE='".date("d/m/Y",$iniziomese)."'>&nbsp;<input type=\"button\" value=\""._BRNCALENDARIO_."\" onclick=\"displayCalendar(document.reportdocente.dataini,'dd/mm/yyyy',this)\" STYLE=\"margin-top:2px;\">&nbsp;&nbsp;"._DATAFINE_." <INPUT TYPE='text' NAME='datafine' VALUE='".date("d/m/Y",$finemese)."'> <input type=\"button\" value=\""._BRNCALENDARIO_."\" onclick=\"displayCalendar(document.reportdocente.datafine,'dd/mm/yyyy',this)\" STYLE=\"margin-top:2px;\">";
  print "&nbsp; <INPUT TYPE='submit' NAME='sub' VALUE='"._BTNELABORA_."'></FORM>";
  print "</TD></TR>";
  print "</TABLE>";
?>  