<?php
  $pagever = "1.0";
  $pagemod = "25/06/2010 10.17.07";
  $path = "../../";
  require_once($path."../mask/form_cfg.php");
  
  $cmd = $_REQUEST['cmd'];
  $selobj = $_REQUEST['selobj'];
  
  $vetlegenda = array("P"=>"Presente",
                    "A"=>"Ausente",
                    "-"=>"No Actividad",
                    "N"=>"No Profesor",
                    "C"=>"Cancelada",
                    "R"=>"Recuperada");
$vetgg = array(1=>"Lu",2=>"Ma",3=>"Me",4=>"Ju",5=>"Vi",6=>"S&#225;");
$vetmm = array(1=>"Gennaio",
  2=>"Febbraio",3=>"Marzo",4=>"Aprile",5=>"Maggio",6=>"Giugno",7=>"Luglio",8=>"Agosto",
  9=>"Settembre",10=>"Ottobre",11=>"Novembre",12=>"Dicembre");
  
  $query = "SELECT customer.nome as customer_nome, customer.contatti as customer_contatti, corso.descr as corso_descr,corso.codlivello as codlivello, 
pstudi.codsense as codsense, pstudi.durata as durata, pstudi.datai as datai, pstudi.dataf as dataf,pstudi.luogo as luogo
FROM pstudi INNER JOIN classe ON pstudi.id=classe.idpstudi
INNER JOIN studente ON classe.idstudente=studente.id
LEFT JOIN customer ON studente.idcustomer=customer.id
INNER JOIN corso ON pstudi.idcorso=corso.id
WHERE pstudi.id=".$selobj;

$result = mysql_query($query) or die ("Error1.1$query");
while ($line = mysql_fetch_assoc($result)) {
  $corso['customer_nome'] = $line['customer_nome'];
  $corso['customer_contatti'] = $line['customer_contatti'];
  $corso['corso_descr'] = $line['corso_descr'];
  $corso['codlivello'] = $line['codlivello'];
  $corso['codsense'] = $line['codsense'];
  $corso['durata'] = $line['durata'];
  $corso['datai'] = $line['datai'];
  $corso['dataf'] = $line['dataf'];
  $corso['luogo'] = $line['luogo'];
}

$app = mktime(0, 0, 0, 1, 1, 2010);
$query = "SELECT prefday.id as id, wkday, orai, oraf, prefday.iddocente as iddocente, docente.nome as nome 
    FROM prefday INNER JOIN docente ON prefday.iddocente=docente.id 
    WHERE idpstudi=".$selobj." 
    ORDER BY prefday.wkday, prefday.orai";
$result = mysql_query($query) or die ("Error1.2$query");
while ($line = mysql_fetch_assoc($result)) {
  $prefday[$line['id']]['giorno'] = $vetgg[$line['wkday']];
  $prefday[$line['id']]['orai'] = date("H:i",$app+$line['orai']);
  $prefday[$line['id']]['oraf'] = date("H:i",$app+$line['oraf']);
  
  $strprefday .= $vetgg[$line['wkday']]." ".$prefday[$line['id']]['orai']." - ".$prefday[$line['id']]['oraf'].", ";
  
  $docenti[$line['iddocente']]=$line['nome'];
  
}

$query = "SELECT studente.id as studente_id, studente.nome as nome 
  FROM studente INNER JOIN classe ON studente.id=classe.idstudente 
  WHERE classe.idpstudi=".$selobj;
$result = mysql_query ($query) or die ("error7.1");
$studenti = mysql_fetch_assoc($result);

$query = "SELECT min(dtini) as inizio, max(dtini) as fine FROM appuntamento WHERE appuntamento.trashed<>1 AND appuntamento.fdel<>1 AND idpstudi=".$selobj;
$result = mysql_query ($query) or die ("error7.2");
$line = mysql_fetch_assoc($result);

$meseini = intval(date("m",$line['inizio']));
$mesefine = intval(date("m",$line['fine']));
$nemesi = $mesefine-$meseini+1;

/*while ($line = mysql_fetch_assoc($result)) {
  $nestudenti++;
  $studenti[$nestudenti] = $line['nome'];
  
}*/

foreach ($docenti as $key=>$cur) {
  $strdocenti .= $cur.", ";
}

$footer = '
<table width="100%" style="border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;"><tr>
<td STYLE="font-family:helvetica, verdana;font-size:10pt;vertical-align: middle;">www.chilenghish.com Tels (56 2) 665 1676 - 665 0965, Ram&oacute;n Carnicer 81, Of. 607</td>
<td><img style="vertical-align: top; opacity: 0.99;" src="logochilenglish.jpg" width="400" /></td>
</tr></table>
';

//==============================================================
//==============================================================
//==============================================================
include("../mpdf.php");

$mpdf=new mPDF('en-GB','A4','','',3,3,3,3,15,15); 

$mpdf->useOnlyCoreFonts = true;

$mpdf->SetDisplayMode('fullpage');

$mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
$mpdf->AddPage('L','','','','',5,5,5,15,18,12);
$mpdf->SetHTMLFooter($footer);

// LOAD a stylesheet
$stylesheet = file_get_contents('mpdfstyletables.css');
$mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text


$tab1 = "
<HTML><HEAD>
<style>
body {
  font-family:helvetica, verdana;
  font-size:10pt;
}
.label1 {
  font-size:12pt;
  font-weight:bold;
}
td {
  height:40pt;
}

</style>
</HEAD><BODY>
<TABLE border=1 WIDTH=100%>
<TR><TD COLSPAN=4 ALIGN=CENTER STYLE='font-size:20pt;font-weight:bolder;'>LIBRO DE CONTROL DE CLASSES</TD></TR>
<TR><TD CLASS=label1>NOMBRE OTEC</TD><TD COLSPAN=2 STYLE='font-size:20pt;font-weight:bold;'>CHILENGLISH LTDA.</TD><TD size=20 ROWSPAN=3 STYLE='font-size:35pt;text-align:center;width:60mm;vertical-align:middle;'>".$corso['codlivello']."</TD></TR>
<TR><TD CLASS=label1>RUT</TD><TD COLSPAN=2 STYLE='font-size:16pt;font-weight:bold;'>76.225.560 - K</TD></TR>
<TR><TD CLASS=label1>DIRECCI&#211;N</TD><TD COLSPAN=2 STYLE='font-size:14pt;font-weight:bold;'>RAMON CARNICER 81, Of. 607 - Providencia, Santiago<BR>Mr. Craig Wilson, Email cwilson@chilenglish.com</TD></TR>
<TR><TD CLASS=label1>NOMBRE CLIENTE</TD><TD STYLE='font-size:14pt;font-weight:bold;'>".$corso['customer_nome']."</TD><TD CLASS=label1>CONTACTO</TD><TD STYLE='font-size:14pt;font-weight:bold;'>".$corso['customer_contatti']."</TD></TR>
<TR><TD CLASS=label1>NOMBRE ACTIVIDAD DE CAPACITACI&#211;N</TD><TD COLSPAN=3 STYLE='font-size:14pt;font-weight:bold;'>".$corso['corso_descr']."</TD></TR>
<TR><TD CLASS=label1>C&#211;DIGO AUTORIZADO POR SENSE</TD><TD STYLE='font-size:14pt;font-weight:bold;'>".$corso['codsense']."</TD><TD CLASS=label1>TOTALE DE HOSAS</TD><TD STYLE='font-size:14pt;font-weight:bold;'>".$corso['durata']."</TD></TR>
<TR><TD CLASS=label1>FECHA DE EJECUCI&#211;N</TD><TD CLASS=label1 STYLE='font-size:14pt;font-weight:bold;'>FECHA INIZIO: ".date("d/m/Y",$corso['datai'])."</TD><TD COLSPAN=2 CLASS=label1  STYLE='font-size:14pt;font-weight:bold;'>FECHIA T&#201;RMINO: ".date("d/m/Y",$corso['dataf'])."</TD>
<TR><TD CLASS=label1>LUGAR DE EJECUZI&#211;N</TD><TD COLSPAN=3 STYLE='font-size:14pt;font-weight:bold;'>".$corso['luogo']."</TD></TR>
<TR><TD CLASS=label1>HORARIO</TD><TD COLSPAN=3 STYLE='font-size:12pt;font-weight:bold;'>".substr($strprefday,0,-2)."</TD></TR>
<TR><TD CLASS=label1>NOMBRE RELATOR</TD><TD COLSPAN=3 STYLE='font-size:14pt;font-weight:bold;'>".substr($strdocenti,0,-2)."</TD></TR>
</TABLE>
</BODY></HTML>";

$mpdf->WriteHTML($tab1);
$mpdf->AddPage('L','','','','',5,5,5,15,18,12);


$tab2="<DIV STYLE='font-size:14pt;width:100%;text-align:center;'>CONTROL DE ASISTENCIA DE PARTICIPANTES</DIV>";
$nrowpag = 7;
$imesi=0;
$volte=0;

while ($imesi<$nemesi) {
  $tab2.="<TABLE WIDTH=100%><TR>";
  foreach ($vetlegenda as $key=>$cur) {
    $tab2.= "<TD STYLE='background-color:#DADADA;height:15pt;text-align:center;border:1px solid #000000;'>$key</TD><TD STYLE='height:15pt;'>".$cur."</TD>";
  }
  $tab2 .="<TD STYLE='height:15pt;width:10pt;'> </TD>";
  $tab2.="<TD ROWSPAN=2 STYLE='width:45pt;background-color:#DADADA;height:15pt;border:1px solid #000000;font-size:8pt;'>Total Horas Mensual</TD>";
  $tab2.="</TR><TR><TD COLSPAN=13 STYLE='height:15pt;'></TD></TR></TABLE>";


  $tab2 .= "<TABLE WIDTH=100%>";
  $tab2 .= "<TR><TD COLSPAN=2 STYLE='height:15pt;'></TD>";
  for ($k=1;$k<=4;$k++) {
    for ($l=1;$l<=6;$l++) {
      if ($l%2==0) {$varyback="background-color:#FFFFFF;";} else {$varyback="background-color:#E3E3E3;";}
      $tab2.= "<TD STYLE='width:15pt;height:15pt;border:1px solid #000000;$varyback'>".substr($vetgg[$l],0,1)."</TD>";
    }
    $tab2.="<TD STYLE='width:65pt;height:15pt;border:1px solid #000000;'>Firma</TD>";
  }
  $tab2 .="<TD STYLE='height:15pt;width:10pt;'></TD>";
  $tab2 .="</TR>";
  
  for ($i=1;$i<=$nrowpag;$i++) {
    for ($j=1;$j<=2;$j++) {
      $tab2 .= "<TR>";
      if ($j==1) {
        $tab2 .= "<TD ROWSPAN=2 STYLE='background-color:#DADADA;font-size:12pt;font-weight:bold;text-align:center;vertical-align:middle;border:1px solid #000000;'>".($i+($volte*$nrowpag))."</TD>";
      }        
      if ($j==1) {
        $tab2 .= "<TD STYLE='height:15pt;border:1px solid #000000;width:70pt;'>".$studenti['nome']."</TD>";      
      } else {
        $appmesi = $meseini+$imesi;
        
        $tab2 .= "<TD STYLE='height:15pt;border:1px solid #000000;width:70pt;'>";
        if ($appmesi <= $mesefine) {
          if ($appmesi>12) {$appmesi-=12;}
          $tab2 .= $vetmm[$appmesi];
        }
        $tab2 .= "</TD>";
        $imesi++;
      }
      
      for ($k=1;$k<=4;$k++) {
        for ($l=1;$l<=6;$l++) {
          if ($l%2==0) {$varyback="background-color:#FFFFFF;";} else {$varyback="background-color:#E3E3E3;";}
          $tab2.= "<TD STYLE='width:15pt;height:15pt;border:1px solid #000000;$varyback'></TD>";
        }
        $tab2.="<TD STYLE='width:65pt;height:15pt;border:1px solid #000000;'></TD>";
      }
      $tab2 .="<TD STYLE='height:15pt;width:10pt;'> </TD>";
      if ($j==1) {
        $tab2 .="<TD ROWSPAN=2 STYLE='width:45pt;background-color:#DADADA;height:15pt;border:1px solid #000000;'></TD>";
      }
      $tab2 .="</TR>";
    }
    $tab2.="<TR><TD COLSPAN=32 STYLE='height:5pt;'></TD></TR>";
  }
  $tab2.="</TABLE>";
  $tab2.="<TABLE WIDTH=100% BORDER=1><TR><TD STYLE='height:15pt;'>APELLIDOSD,NOMBRE</TD><TD STYLE='height:15pt;'>RUT</TD><TD STYLE='height:15pt;'>NIVEL</TD><TD STYLE='height:15pt;'>EMPRESA</TD><TD STYLE='height:15pt;'>CARGO QUE</TD><TD STYLE='height:15pt;width:155pt;'>FIRMA</TD><TD ROWSPAN=2 STYLE='width:10pt;border-top:0px none black;border-bottom:0px none black;'> </TD><TD ROWSPAN=2 STYLE='width:45pt;height:20pt;background-color:#DADADA;border:3px solid #000000;'></TD></TR>";
  $tab2.="<TR><TD STYLE='height:25pt;'>".$studenti['nome']."</TD><TD STYLE='height:25pt;'></TD><TD STYLE='height:25pt;'></TD><TD STYLE='height:25pt;'></TD><TD STYLE='height:25pt;'></TD><TD STYLE='height:25pt;'></TD></TR></TABLE>";
  $mpdf->WriteHTML($tab2);
  $tab2="";
  $mpdf->AddPage('L','','','','',5,5,5,15,18,12);
  $volte++;
}

$tab3 = "<DIV STYLE='font-size:14pt;width:100%;text-align:center;'>CONTENIDOS DE ACTIVIDADES DE CAPACITACI&#211;N</DIV><TABLE BORDER=1 WIDTH=100%><TR><TD STYLE='font-size:12pt;text-align:center;width:3%;'>FECHA</TD><TD STYLE='font-size:12pt;text-align:center;width:40%;'>TEMAS</TD><TD STYLE='font-size:12pt;text-align:center;width:40%;'>ACTIVIDADES</TD><TD STYLE='font-size:12pt;text-align:center;width:3%;'>HORA INICIO</TD><TD STYLE='font-size:12pt;text-align:center;width:3%;'>HORA TERMINO</TD><TD STYLE='font-size:12pt;text-align:center;width:11%;'>FIRMA RELATOR(A)</TD></TR>";
for ($i=1;$i<=10;$i++) {
  $tab3.="<TR><TD></TD><TD></TD><TD></TD><TD></TD><TD></TD><TD></TD></TR>";
}
$tab3.="</TABLE>";

$mpdf->WriteHTML($tab3);
$mpdf->AddPage('L','','','','',5,5,5,15,18,12);



$tab4 = "<DIV STYLE='font-size:14pt;width:100%;text-align:center;'>EVALUACIONES</DIV><TABLE WIDTH=100% BORDER=1><TR><TD STYLE='height:10pt;font-size:12pt;text-align:center;'> </TD><TD STYLE='height:10pt;font-size:12pt;text-align:center;'>APELLIDOS, NOMBRE</TD><TD STYLE='height:10pt;font-size:12pt;text-align:center;'>FECHA</TD><TD STYLE='height:10pt;font-size:12pt;text-align:center;'>FECHA</TD><TD STYLE='height:10pt;font-size:12pt;text-align:center;'>FECHA</TD><TD STYLE='height:10pt;font-size:12pt;text-align:center;'>FECHA</TD><TD STYLE='height:10pt;font-size:12pt;text-align:center;'>NOTA FINAL</TD><TD STYLE='height:10pt;font-size:12pt;text-align:center;'>FIRMA CONFORMIDAD PARTECIPANTES</TD></TR>";
for ($i=1;$i<=13;$i++) {
  $tab4.="<TR><TD STYLE='height:30pt;'>$i</TD><TD STYLE='height:30pt;'></TD><TD STYLE='height:30pt;'></TD><TD STYLE='height:30pt;'></TD><TD STYLE='height:30pt;'></TD><TD STYLE='height:30pt;'></TD><TD STYLE='height:30pt;'></TD><TD STYLE='height:30pt;'></TD></TR>";
}
$tab4.="</TABLE>";

$mpdf->WriteHTML($tab4);

$mpdf->Output();
exit;
//==============================================================
//==============================================================
//==============================================================

?>