<?php
  $pagever = "1.0";
  $pagemod = "25/06/2010 10.17.07";
  $path = "../../";
  require_once($path."../mask/form_cfg.php");
  
  $cmd = $_REQUEST['cmd'];
  $selobj = $_REQUEST['selobj'];
  
  $dataini = eT_dt2times($_REQUEST['dataini']);
  $datafine = eT_dt2times($_REQUEST['datafine']);
  
  
  $vetgg = array(1=>"Lu",2=>"Ma",3=>"Me",4=>"Ju",5=>"Vi",6=>"S&#225;");
  foreach ($vetgg as $key=>$cur) {
    $cedolino['stat']['wkday'][$key]['freq']=0;
  }
  
  $query = "select appuntamento.iddocente as iddocente, appuntamento.idpstudi as idpstudi, appuntamento.dtini as dtini, appuntamento.dtfine as dtfine, 
    appuntamento.compenso as compenso, tipo, fdel, frecupera, docente.nome as docente_nome, pstudi.descr as pstudi_descr,
    fsost, idsostituzione, tipo.descr as tipo_descr
    FROM appuntamento INNER JOIN docente ON appuntamento.iddocente=docente.id
    LEFT JOIN pstudi ON appuntamento.idpstudi=pstudi.id
    INNER JOIN tipo ON appuntamento.tipo=tipo.id 
    WHERE appuntamento.trashed<>1 AND fsost<>1 AND iddocente=".$selobj;
    
    if ($dataini>0) {
      $query .= " AND appuntamento.dtini>=$dataini AND appuntamento.dtfine<=$datafine";
    }
  
  $result = mysql_query($query) or die ("Error1.1$query");
  while ($line = mysql_fetch_assoc($result)) {
    //condizioni di calcolo
    if (
      ($line['fdel']==0 && $line['frecupera']==0) || //normale
      ($line['tipo']==1 && $line['fdel']==1 && $line['frecupera']==0) //sameday
    ) {
        $appt = $line['dtfine']-$line['dtini'];
        $cedolino['tempotot'] += $appt;
        $cedolino['eurotot'] += ($appt / 3600) * $line['compenso'];
        $cedolino['toteventi'] ++;
        
        $cedolino['stat']['compenso'][$line['compenso']] = $appdt;
        $cedolino['stat']['pstudi'][$line['idpstudi']]['time'] = $appdt;
        $cedolino['stat']['pstudi'][$line['idpstudi']]['freq'] ++;
        $cedolino['stat']['wkday'][date("w",$line['dtini'])]['freq'] ++;
        $cedolino['stat']['tipo'][$line['tipo']]['freq'] ++;
        
        $pstudi[$line['idpstudi']]=($line['pstudi_descr']==""? " Altre attivita' ":$line['pstudi_descr']);
        $tipo[$line['tipo']]=$line['tipo_descr'];
        
      } 
  }
  
  $query = "SELECT id, nome FROM docente WHERE attivo=1 AND trashed<>1";
  $result = mysql_query($query) or die ("Error5.1");
  while ($line = mysql_fetch_assoc($result)) {
    $docente[$line['id']]=$line['nome'];
  }
  
  //elab stat
  foreach ($cedolino['stat']['pstudi'] as $key=>$cur) {
    $cedolino['stat']['pstudi'][$key]['perc'] = $cur['freq']/$cedolino['toteventi'];
  }
  foreach ($cedolino['stat']['wkday'] as $key=>$cur) {
    $cedolino['stat']['wkday'][$key]['perc'] = $cur['freq']/$cedolino['toteventi'];
  }
  foreach ($cedolino['stat']['tipo'] as $key=>$cur) {
    $cedolino['stat']['tipo'][$key]['perc'] = $cur['freq']/$cedolino['toteventi'];
  }
  
  $tab1 ="<HTML><HEAD><STYLE>
    body {
      font-family:verdana, helvetica;
      font-size:12pt;
    }

    .label1 {
      font-size:14pt;
      background-color:#ECECEC;
      border-bottom:1px solid #000000;
      width:250px;
    }
    .dati1 {
      border-bottom:1px solid #000000;
    }
    .nomereport {
      font-size:16pt;
      font-weight:bold;
    }
    .lblnomereport {
      font-size:12pt;
      font-variant:small-caps;
    }
    .stat_titolo {
      border-bottom:3px solid #000000;
      font-variant:small-caps;
      font-size:15pt;
      margin-top:20pt;
      height:35pt;
    }
    .stat_th {
      bo-rder-bottom:1px solid #000000;
      background-color:#CDCDCD;
      font-weight:bolder;
      text-align:center;
    }
    .stat_td {
      border-bottom:1px solid #000000;
    }
    </STYLE>
    </HEAD>
    <BODY>";
  
  $tab1 .= "<TABLE WIDTH=100%><TR><TD ALIGN=CENTER><IMG SRC='logochilenglish.jpg'></TD></TR><TR><TD ALIGN=CENTER><SPAN CLASS=lblnomereport>Report docente: </SPAN><SPAN CLASS=nomereport>".$docente[$selobj]."</SPAN></TD></TR></TABLE><BR><TABLE WIDTH=60% ALIGN=CENTER>";
  $tab1 .= "<TR><TD CLASS=label1>Periodo</TD><TD CLASS=dati1>inizio: ".date("d/m/Y",$dataini)." - fine: ".date("d/m/Y",$datafine)."</TD></TR>";
  $tab1 .= "<TR><TD CLASS=label1>Ore lavorate</TD><TD CLASS=dati1>".($cedolino['tempotot']/3600)."</TD></TR>";
  $tab1 .= "<TR><TD CLASS=label1>Compenso totale</TD><TD CLASS=dati1>".$cedolino['eurotot']."</TD></TR>";
  $tab1 .= "<TR><TD CLASS=label1>N. totale eventi</TD><TD CLASS=dati1>".$cedolino['toteventi']."</TD></TR>";
  $tab1 .= "</TABLE><BR><BR><TABLE WIDTH=50% ALIGN=CENTER><TR><TD COLSPAN=3><SPAN CLASS=nomereport>Statistics</SPAN><BR><BR></TD></TR>";
  $tab1 .= "<TR><TD COLSPAN=3 CLASS=stat_titolo>Corsi insegnati</TD></TR><TR><TD CLASS=stat_th>Corso</TD><TD CLASS=stat_th>N. eventi</TD><TD CLASS=stat_th>%</TD></TR>";
  foreach ($cedolino['stat']['pstudi'] as $key=>$cur) {
    $tab1.="<TR><TD CLASS=dati1>".$pstudi[$key]."</TD><TD CLASS=dati1 ALIGN=CENTER>".$cur['freq']."</TD><TD CLASS=dati1 ALIGN=CENTER>".number_format($cur['perc']*100,0)." %</TD></TR>";
  }
  $tab1 .= "<TR><TD COLSPAN=3 CLASS=stat_titolo>Suddivisione settimanale</TD></TR><TR><TD CLASS=stat_th>Weekday</TD><TD CLASS=stat_th>N. eventi</TD><TD CLASS=stat_th>%</TD></TR>";
  foreach ($cedolino['stat']['wkday'] as $key=>$cur) {
    $tab1.="<TR><TD CLASS=dati1>".$vetgg[$key]."</TD><TD CLASS=dati1 ALIGN=CENTER>".$cur['freq']."</TD><TD CLASS=dati1 ALIGN=CENTER>".number_format($cur['perc']*100,0)." %</TD></TR>";
  }
  $tab1 .= "<TR><TD COLSPAN=3 CLASS=stat_titolo>Tipologia eventi</TD></TR><TR><TD CLASS=stat_th>Tipologia</TD><TD CLASS=stat_th>N. eventi</TD><TD CLASS=stat_th>%</TD></TR>";
  foreach ($cedolino['stat']['tipo'] as $key=>$cur) {
    $tab1.="<TR><TD CLASS=dati1>".convlang($tipo[$key])."</TD><TD CLASS=dati1 ALIGN=CENTER>".$cur['freq']."</TD><TD CLASS=dati1 ALIGN=CENTER>".number_format($cur['perc']*100,0)." %</TD></TR>";
  }
  $tab1.="</TABLE></CENTER><BR><BR><BR><BR><TABLE WIDTH=100%><TR><TD>www.chilenghish.com Tels (56 2) 665 1676 - 665 0965, Ram&oacute;n Carnicer 81, Of. 607</TD><TD><img src=\"logochilenglishfooter.jpg\"/></TD></TR></TABLE>";
  
  //==============================================================
  //==============================================================
  //==============================================================
  include("../mpdf.php");
  $mpdf=new mPDF('en-GB','A4','','',3,3,3,3,15,15); 

  $mpdf->useOnlyCoreFonts = true;
  
  $mpdf->SetDisplayMode('fullpage');
  
  $mpdf->list_indent_first_level = 0;	// 1 or 0 - whether to indent the first level of a list
  $mpdf->AddPage();
  // LOAD a stylesheet
  $stylesheet = file_get_contents('mpdfstyletables.css');
  $mpdf->WriteHTML($stylesheet,1);	// The parameter 1 tells that this is css/style only and no body/html/text
  
  $mpdf->WriteHTML($tab1);
  $mpdf->Output();
?>
  