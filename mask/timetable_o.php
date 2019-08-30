<?php
  define ("_FILECORRENTE_","timetable.php");
  define ("_DBQUERY_","dbquery.php");
  
  require_once("form_cfg.php");
  require_once("functimetable.php");
  
  define("STYLECELLA_NORMALE","background-color:#EEEEEE;");
  define("STYLECELLA_PREFDAY","background-color:#FFA07A;");
  
?>
<html>
<head>
<style type="text/css">

.festa {
  color:#FFFFFF;
  font-style:italic;
  background-color:#DF1B1B !important;
}

#dhtmltooltip{
position: absolute;
width: 250px;
border: 2px solid black;
padding: 2px;
background-color: lightyellow;
visibility: hidden;
font-family:Verdana, Arial;
font-size:10px;
z-index: 100;
/*Remove below line to remove shadow. Below line should always appear last within this CSS*/
filter: progid:DXImageTransform.Microsoft.Shadow(color=gray,direction=135);
}

body {font-family:Verdana,Arial;}
.td_ore {width:15%;}
.td_meeting {width:20%;}
.table_tto {
	width:100%;
}

</STYLE>
<link rel="stylesheet" type="text/css" href="../css/main.css" />
<link rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></link>
</head>

<body>
<script type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<div id="dhtmltooltip"></div>

<?php
//test1 timetable
//print_r($_REQUEST);
//default value

$cmd = $_REQUEST['cmd'];

  $editval['idprof'] = $_REQUEST['idprof'];
  $datai = $_REQUEST['newdt'];
  
  $wks = $_REQUEST['wks'];
  switch ($wks) {
    case 2:
            $daysweek=2;
            $weeks=1;
            break;
    case 4:
            //1 mese
            $daysweek=2;
            $weeks=2;
            break;
    case 1:
    default:
            $daysweek=1;
            $weeks=1;
            break;
  }
  if ($wks=="") {$wks=7;}
  
  /*if ($_SESSION['mgdebug']==1) {
    print $datai."<BR>";
    print date("d/m/Y",$datai)."<BR>";
    print $_SESSION['datai'];
  }*/
  
  
  if ($editval['idprof']=="") {
    $editval['idprof']=$_SESSION['idprof'];
  } else {
    $_SESSION['idprof'] = $editval['idprof'];
  }
  if ($datai=="" && isset($_SESSION['datai'])) {
    $datai=$_SESSION['datai'];
  }

  if ($datai=="") {
    $datai = time();
  } else {
    $_SESSION['datai'] = $datai;
    $datai = eT_dt2times($datai);
  }

  $gg = $_REQUEST['gg'];
  if ($gg!="") {
    $datai = strtotime($gg." day", $datai);
    //$_SESSION['datai'] = date("d/m/Y",$datai);
  } 
  
  
$tt_datai = time();
$tt_dataf = $tt_datai + $wks*24*60*60;

$ora_prec = $tt_orai;

$lezioni=array();

$countspan[0]=0;
$countspan[1]=0;


//ricalcolo datai per lunedi' 1° giorno
$wd = intval(date("w",$datai));
$datai -= ($wd-1)*24*60*60;

//print "DATA:".date("d/m/Y",$datai)."<BR>";

//weekprefday
if ($editval['idpstudi']!="") {
  $query = "SELECT * FROM prefday WHERE idpstudi=".$editval['idpstudi']." ORDER BY wkday,orai,oraf";
  $result = mysql_query($query) or die ("Error_1.1");
  
  while ($line = mysql_fetch_assoc($result)) {
    $chiave = $line['wkday'];
    $passi = ($line['oraf']-$line['orai'])/0.5;
    
    $apph = intval(($line['orai']-1)*60*60);
    //print "<HR>".date("H:i:s",$apph)."<HR>";
    for ($r=0;$r<$passi;$r++) {
      $prefday[$chiave][$apph]=1;
      //print "<HR>[$chiave][".date("H:i:s",$apph)."]$apph<HR>";
      $apph += 30*60;
    }
  }
}
$query = "SELECT * FROM docente WHERE attivo=1 AND trashed<>1";
  if ($_SESSION['user_level']<LEVEL_VICEBOSS) {
   if ($_SESSION['docente_id']!="") {
    $query .= " AND docente.id=".$_SESSION['docente_id'];
   } else {
    $query .= " AND 1=-1";
   }
  }
  $query .= " ORDER BY docente.nickname";
$result = mysql_query($query) or die ("Error_1.5");
while ($line = mysql_fetch_assoc($result)) {
  $vetprof[$line['id']]['iduser']=$line['iduser'];
  $vetprof[$line['id']]['nome']=$line['nome'];
  $vetprof[$line['id']]['nickname']=$line['nickname'];
  $vetprof[$line['id']]['tel']=$line['tel'];
  $vetprof[$line['id']]['mobile']=$line['mobile'];
  $vetprof[$line['id']]['email']=$line['email'];
  $vetprof[$line['id']]['attivo']=$line['attivo'];
  $vetprof[$line['id']]['trashed']=$line['trashed'];
  if ($editval['idprof']==$line['id']) {$editval['prof']=$line['nickname'];}
}



$query = "SELECT * FROM tipo";
$result = mysql_query ($query) or die ("Error_1.2");
$vettipo=array();
while ($line=mysql_fetch_assoc($result)) {
  $vettipo[$line['id']]['descr']=$line['descr'];
  $vettipo[$line['id']]['style']=$line['style'];
}

$query = "SELECT * FROM pstudi WHERE trashed<>1";
$result = mysql_query ($query) or die ("Error_1.4");
while ($line=mysql_fetch_assoc($result)) {
  $vetpstudi[$line['id']]['idcorso'] = $line['idcorso'];
  $vetpstudi[$line['id']]['descr'] = $line['descr'];
  $vetpstudi[$line['id']]['datai'] = $line['datai'];
  $vetpstudi[$line['id']]['dataf'] = $line['dataf'];
  $vetpstudi[$line['id']]['compenso'] = $line['compenso'];
  $vetpstudi[$line['id']]['durata'] = $line['durata'];
  $vetpstudi[$line['id']]['dlezione'] = $line['dlezione'];
  $vetpstudi[$line['id']]['codsense'] = $line['codsense'];
  $vetpstudi[$line['id']]['style'] = $line['style'];
  $vetpstudi[$line['id']]['trashed'] = $line['trashed'];
  
  if ($editval['idpstudi']==$line['id']) {
    $editval['compenso']=$line['compenso'];
    $editval['dlezione']=$line['dlezione'];
  }
}

if ($editval['idprof']!="") {
  $iddocente = "-1, ".$editval['idprof'].", ";
  $iddocente = substr($iddocente,0,-2);
  
  $query = "SELECT appuntamento.id as id, appuntamento.idpstudi as idpstudi, appuntamento.iddocente as iddocente, 
            appuntamento.dtini as dtini, appuntamento.dtfine as dtfine, appuntamento.compenso as compenso, 
            appuntamento.tipo as tipo, appuntamento.note as note, appuntamento.idsostituzione as idsostituzione, 
            appuntamento.fsost as fsost, appuntamento.fnotify as notify, appuntamento.fdel as fdel, 
            appuntamento.frecupera asfrecupera, classi.descr as classi_descr, customer.nome as customer_nome,
            corso.descr as corso_descr, corso.libro as corso_libro, corso.materiale as corsto_materiale,
            corso.codlivello as corso_codlivello  
            FROM appuntamento LEFT JOIN pstudi ON appuntamento.idpstudi = pstudi.id AND pstudi.trashed<>1
            LEFT JOIN classi ON pstudi.idclasse = classi.id AND classi.trashed<>1
            LEFT JOIN customer ON classi.idcustomer = customer.id AND customer.trashed<>1
            LEFT JOIN corso ON pstudi.idcorso = corso.id AND corso.trashed<>1
            WHERE appuntamento.trashed<>1 AND appuntamento.fsost<>1  AND appuntamento.iddocente IN ($iddocente) AND
            dtini>=$datai
            ORDER BY fdel DESC,fsost DESC,dtini";
            
            //print "<HR>$query<HR>";
            //AND appuntamento.fdel<>1
  $result = mysql_query ($query) or die ("Error_1.3 $query");
  //print "<HR>".mysql_num_rows($result)."<HR>";
  while ($line = mysql_fetch_assoc($result)) {
    $line['dtini']-=3600;
    $chiave = day_mezzanotte($line['dtini']);
    $chiave2 = $line['dtini']-$chiave;
    unset($appvetelement);
    /*
    $lezioni[$chiave][$chiave2]['id'] = $line['id'];
    $lezioni[$chiave][$chiave2]['dt'] = $line['dtini'];
    $lezioni[$chiave][$chiave2]['orai'] = $line['dtini']-$chiave;
    $lezioni[$chiave][$chiave2]['durata'] = ($line['dtfine']-$line['dtini']-3600) / 60;
    $lezioni[$chiave][$chiave2]['descr'] = $vetpstudi[$line['idpstudi']]['descr'];
    $lezioni[$chiave][$chiave2]['note'] = $line['note'];
    $lezioni[$chiave][$chiave2]['tipo'] = $line['tipo'];
    $lezioni[$chiave][$chiave2]['fsost'] = $line['fsost'];
    $lezioni[$chiave][$chiave2]['fnotify'] = $line['fnotify'];
    $lezioni[$chiave][$chiave2]['fdel'] = $line['fdel'];
    $lezioni[$chiave][$chiave2]['frecupera'] = $line['frecupera'];
    
    $lezioni[$chiave][$chiave2]['classi_descr'] = $line['classi_descr'];
    $lezioni[$chiave][$chiave2]['customer_nome'] = $line['customer_nome'];
    $lezioni[$chiave][$chiave2]['corso_descr'] = $line['corso_descr'];
    $lezioni[$chiave][$chiave2]['corso_libro'] = $line['corso_libro'];
    $lezioni[$chiave][$chiave2]['corsto_materiale'] = $line['corsto_materiale'];
    $lezioni[$chiave][$chiave2]['corso_codlivello'] = $line['corso_codlivello'];

    
    if ($line['tipo']==1) {
      $lezioni[$chiave][$chiave2]['style'] = $vetpstudi[$line['idpstudi']]['style'];
    } else {
      $lezioni[$chiave][$chiave2]['style'] = $vettipo[$line['tipo']]['style'];
    }
    */
    $appvetelement['id'] = $line['id'];
    $appvetelement['dt'] = $line['dtini'];
    $appvetelement['orai'] = $line['dtini']-$chiave;
    $appvetelement['durata'] = ($line['dtfine']-$line['dtini']-3600) / 60;
    $appvetelement['descr'] = $vetpstudi[$line['idpstudi']]['descr'];
    $appvetelement['note'] = $line['note'];
    $appvetelement['tipo'] = $line['tipo'];
    $appvetelement['fsost'] = $line['fsost'];
    $appvetelement['fnotify'] = $line['fnotify'];
    $appvetelement['fdel'] = $line['fdel'];
    $appvetelement['frecupera'] = $line['frecupera'];
    
    $appvetelement['classi_descr'] = $line['classi_descr'];
    $appvetelement['customer_nome'] = $line['customer_nome'];
    $appvetelement['corso_descr'] = $line['corso_descr'];
    $appvetelement['corso_libro'] = $line['corso_libro'];
    $appvetelement['corsto_materiale'] = $line['corsto_materiale'];
    $appvetelement['corso_codlivello'] = $line['corso_codlivello'];

    
    if ($line['tipo']==1) {
      $appvetelement['style'] = $vetpstudi[$line['idpstudi']]['style'];
    } else {
      $appvetelement['style'] = $vettipo[$line['tipo']]['style'];
    }
    
    
    //INSERIMENTO ELEMENTO IN STRUTTURA
    if ($line['fdel']==1) {
      //elemento cancellato
      $lezioni[$chiave][$chiave2]['ne_fdel_1']++;
      $lezioni[$chiave][$chiave2]['fdel_1'][$lezioni[$chiave][$chiave2]['ne_fdel_1']]=$appvetelement;
    } else {
      if ($line['fsost']==1) {
        //elemento con sostituzione confermata
        $lezioni[$chiave][$chiave2]['ne_fsost_1']++;
        $lezioni[$chiave][$chiave2]['fsost_1'][$lezioni[$chiave][$chiave2]['ne_fsost_1']]=$appvetelement;
      } else {
        //attivo
        
        //check per relocating degli eventi inglobati dall'evento attivo
        if ($_SESSION['mgdebug']==1) {
          /*for ($irelo = $chiave2;$irelo<($chiave2 + ($appvetelement['durata']*60));$irelo+=$tt_step) {
            if (isset($lezioni[$chiave][$irelo])) {
              myprint_r($lezioni[$chiave][$irelo]);
              //reloco i record fdel_1 e fsost_1
              for($jrelo=1;$jrelo<=$lezioni[$chiave][$irelo]['ne_fdel_1'];$jrelo++) {
                $lezioni[$chiave][$chiave2]['ne_fdel_1']++;
                //$lezioni[$chiave][$chiave2]['fdel_1'][$lezioni[$chiave][$chiave2]['ne_fdel_1']] = $lezioni[$chiave][$irelo]['fdel_1'][$jrelo];
              }
              
              for($jrelo=1;$jrelo<=$lezioni[$chiave][$irelo]['ne_fsost_1'];$jrelo++) {
                $lezioni[$chiave][$chiave2]['ne_fsost_1']++;
                $lezioni[$chiave][$chiave2]['fsost_1'][$lezioni[$chiave][$chiave2]['ne_sost_1']] = $lezioni[$chiave][$irelo]['fsost_1'][$jrelo];
              }
              $lezioni[$chiave][$irelo]['ne_fdel_1']=0;
              $lezioni[$chiave][$irelo]['ne_fsost_1']=0;
              
              if ($lezioni[$chiave][$irelo]['ne_attivo']<1) {
                unset($lezioni[$chiave][$irelo]);
              }
              
              
  
            }                 
          }*/
        }
        
        $lezioni[$chiave][$chiave2]['ne_attivo']++;
        $lezioni[$chiave][$chiave2]['attivo'][$lezioni[$chiave][$chiave2]['ne_attivo']]=$appvetelement;
        
        /*
        $app_neattivo = ++$lezioni[$chiave][$chiave2]['ne_attivo'];
        $lezioni[$chiave][$chiave2]=$appvetelement;
        $lezioni[$chiave][$chiave2]['ne_attivo'] = $app_neattivo;
        */
      }
    }
    
    
  }
}

if ($_SESSION['mgdebug']==1) {
  myprint_r($lezioni);
}

$query = "SELECT * from msgbox where fdefault=1";
$result = mysql_query($query);
$msgbox = mysql_fetch_assoc($result);

$query = "SELECT * FROM feste";
$result = mysql_query($query) or die ("ErrLdHd");
$festedays = array();
while ($line = mysql_fetch_assoc($result)) {
  $festedays[$line['times']]['wkday'] = $line['wkday'];
  $festedays[$line['times']]['txt'] = $line['txt'];                                                       
}
/*$lezioni[day_mezzanotte($micapp)]['dt']=$micapp;
$lezioni[day_mezzanotte($micapp)]['orai']=$micapp-day_mezzanotte($micapp);
$lezioni[day_mezzanotte($micapp)]['durata']=90;
$lezioni[day_mezzanotte($micapp)]['descr']="Nestle";
*/


//$lezioni[1277935200]['durata']

//myprint_r($lezioni);

?>
<script type="text/javascript">

/***********************************************
* Cool DHTML tooltip script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
* This notice MUST stay intact for legal use
* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
***********************************************/

var offsetxpoint=-60 //Customize x offset of tooltip
var offsetypoint=20 //Customize y offset of tooltip
var ie=document.all
var ns6=document.getElementById && !document.all
var enabletip=false
if (ie||ns6)
var tipobj=document.all? document.all["dhtmltooltip"] : document.getElementById? document.getElementById("dhtmltooltip") : ""

function ietruebody(){
return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
}

function ddrivetip(thetext, thecolor, thewidth){
if (ns6||ie){
if (typeof thewidth!="undefined") tipobj.style.width=thewidth+"px"
if (typeof thecolor!="undefined" && thecolor!="") tipobj.style.backgroundColor=thecolor
tipobj.innerHTML=thetext
enabletip=true
return false
}
}

function positiontip(e){
if (enabletip){
var curX=(ns6)?e.pageX : event.clientX+ietruebody().scrollLeft;
var curY=(ns6)?e.pageY : event.clientY+ietruebody().scrollTop;
//Find out how close the mouse is to the corner of the window
var rightedge=ie&&!window.opera? ietruebody().clientWidth-event.clientX-offsetxpoint : window.innerWidth-e.clientX-offsetxpoint-20
var bottomedge=ie&&!window.opera? ietruebody().clientHeight-event.clientY-offsetypoint : window.innerHeight-e.clientY-offsetypoint-20

var leftedge=(offsetxpoint<0)? offsetxpoint*(-1) : -1000

//if the horizontal distance isn't enough to accomodate the width of the context menu
if (rightedge<tipobj.offsetWidth)
//move the horizontal position of the menu to the left by it's width
tipobj.style.left=ie? ietruebody().scrollLeft+event.clientX-tipobj.offsetWidth+"px" : window.pageXOffset+e.clientX-tipobj.offsetWidth+"px"
else if (curX<leftedge)
tipobj.style.left="5px"
else
//position the horizontal position of the menu where the mouse is positioned
tipobj.style.left=curX+offsetxpoint+"px"

//same concept with the vertical position
if (bottomedge<tipobj.offsetHeight)
tipobj.style.top=ie? ietruebody().scrollTop+event.clientY-tipobj.offsetHeight-offsetypoint+"px" : window.pageYOffset+e.clientY-tipobj.offsetHeight-offsetypoint+"px"
else
tipobj.style.top=curY+offsetypoint+"px"
tipobj.style.visibility="visible"
}
}

function hideddrivetip(){
if (ns6||ie){
enabletip=false
tipobj.style.visibility="hidden"
tipobj.style.left="-1000px"
tipobj.style.backgroundColor=''
tipobj.style.width=''
}
}

document.onmousemove=positiontip

</script>
<?php

print "<BR>";

//28/06/2010 12.18.34
//print "<TABLE WIDTH=100%><TR>";
/*print "<TD CLASS=td_cmd>";
  print "<H3>Piano di studi</H3><BR><FORM ACTION='dbquery.php' NAME='carica' METHOD='POST'>";
  print "<INPUT TYPE='hidden' NAME='cmd' VALUE='".FASEINS."'><INPUT TYPE='hidden' NAME='codicepagina' VALUE='TABELLA'>";
  print "<TABLE WIDTH=100%>";
  
  print "<TR><TD CLASS=td_lbl>Data evento</TD><TD CLASS=td_cmd>";
  print "<INPUT TYPE='text' NAME='dt' VALUE='".$editval['dt']."' CLASS=form_input>";
  print "</TD></TR>";
  
  print "<TR><TD CLASS=td_lbl>Ora inizio</TD><TD CLASS=td_cmd>";
  print "<INPUT TYPE='text' NAME='oraini' VALUE='".$editval['oraini']."' CLASS=form_input>";
  print "</TD></TR>";
  
  print "<TR><TD CLASS=td_lbl>Tipo evento</TD><TD CLASS=td_cmd>";
  print "<SELECT NAME='idtipo' SIZE=1 CLASS=form_input onChange=ricdati();>";
  foreach ($vettipo as $keytipo =>$curtipo) {
    print "<OPTION VALUE='$keytipo' ".($editval['idtipo']==$keytipo? " SELECTED ":"")." >".$curtipo['descr']."</OPTION>";
  }
  print "</SELECT>";
  print "<IMG ALIGN='middle' SRC='img/apply.png' BORDER=0 STYLE='padding:0px; margin-left:5px;' onClick=ricdati();>";
  print "</TD></TR>";
  
  if ($editval['idtipo']==1) {
    print "<TR><TD CLASS=td_lbl>Classe</TD><TD CLASS=td_cmd onChange=ricdati();>";
    print "<SELECT NAME='idpstudi' SIZE=1 CLASS=form_input>";
    print "<OPTION VALUE='-1' READONLY>SELEZIONA UNA CLASSE</OPTION>";
    foreach ($vetpstudi as $keystudi =>$curstudi) {
      print "<OPTION VALUE='$keystudi' ".($editval['idpstudi']==$keystudi? " SELECTED ":"").">".$curstudi['descr']."</OPTION>";
    } 
    print "</SELECT>";
    print "<IMG ALIGN='middle' SRC='img/apply.png' BORDER=0 STYLE='padding:0px; margin-left:5px;' onClick=ricdati();>";
    print "</TD></TR>";
  } else {
      print "<INPUT TYPE='hidden' NAME='idpstudi' VALUE='-1'>";
  }
  
    print "<TR><TD CLASS=td_lbl>Durata lezione</TD><TD CLASS=td_cmd>";
    print "<SELECT NAME='durata' SIZE=1 CLASS=form_input>";
    for ($i=0;$i<20;$i++) {
      print "<OPTION VALUE='".$i."' ".(($editval['dlezione']/30)-1==$i ? " SELECTED ":"").">".date("H:i",($i-1)*30*60)."[$i]</OPTION>";
    }
    print "</SELECT>";
    print "</TD></TR>";
  
    print "<TR><TD CLASS=td_lbl>Professore</TD><TD CLASS=td_cmd>";
    print "<INPUT TYPE='text' NAME='prof' VALUE='".$editval['prof']."' READONLY CLASS=form_input><INPUT TYPE='hidden' NAME='idprof' VALUE='".$editval['idprof']."'>";
    print "</TD></TR>";
  
    print "<TR><TD CLASS=td_lbl>Compenso</TD><TD CLASS=td_cmd>";
    print "<INPUT TYPE='text' NAME='compenso' VALUE='".$editval['compenso']."' CLASS=form_input>";
    print "</TD></TR>";
  
  print "<TR><TD COLSPAN=2><INPUT TYPE='submit' NAME='invia' VALUE='INVIA'></TD></TR>";
  
  print "</TABLE>";
  print "</FORM>";
print "</TD>";
*/

//28/06/2010 12.18.16
//print "<TD>";
print "<TABLE CLASS=table_tto>";
print "<TR><TD STYLE='width:160px;'>";
print "<FORM NAME='nuovadt' ACTION='#'><CENTER><IMG SRC='../img/prev.png' BORDER=0 ALT='"._MSGBACKWEEK_."' TITLE='"._MSGBACKWEEK_."' onClick=cambiadata(-7);>&nbsp;<INPUT SIZE=12 TYPE='text' NAME='newdt' VALUE='".date("d/m/Y",$datai)."'>&nbsp;<IMG SRC='../img/next.png' BORDER=0  ALT='"._MSGNEXTWEEK_."' TITLE='"._MSGNEXTWEEK_."' onClick=cambiadata(+7);><BR><IMG SRC='../img/calendario.png' ALIGN=MIDDLE BORDER=0 ALT='"._BRNCALENDARIO_."' TITLE='"._BRNCALENDARIO_."' onclick=\"displayCalendar(document.nuovadt.newdt,'dd/mm/yyyy',this)\">&nbsp;<IMG SRC='../img/update22.png' ALIGN=MIDDLE BORDER=0 ALT='"._MSGREFRESH_."' TITLE='"._MSGREFRESH_."' onClick=updtt();>";
print "&nbsp;<IMG SRC='../img/1week.png' ALIGN=MIDDLE BORDER=0 ALT='"._MSGREFRESH_."' TITLE='"._MSGREFRESH_."' onClick=updwks(\"1\");>&nbsp;<IMG SRC='../img/2week.png' ALIGN=MIDDLE BORDER=0 ALT='"._MSGREFRESH_."' TITLE='"._MSGREFRESH_."' onClick=updwks(\"2\");>&nbsp;<IMG SRC='../img/4week.png' ALIGN=MIDDLE BORDER=0 ALT='"._MSGREFRESH_."' TITLE='"._MSGREFRESH_."' onClick=updwks(\"4\");>";
print "</CENTER></FORM>";
print "</TD>";

//docenti
//print "<TR><TD COLSPAN=".($numerogg+1)."><INPUT TYPE='button' NAME='btn_prof' VALUE='JONO'></TD></TR>";
print "<TD>";
  foreach ($vetprof as $keyprof =>$curprof) {
    if ($editval['idprof']==$keyprof) {
      $style_curprof = BTN_PROF_SELECTED;
    } else {
      $style_curprof = "";
    }
    //RIMUOVI SPAZI ??PD lui lo sa
    print "<INPUT TYPE=\"button\" $style_curprof NAME=\"prof_id\" VALUE=\"".$curprof['nickname']."\" onClick=updprof(\"".$keyprof."\",\"".$curprof['nick-name']."\");>&nbsp;";
  }
print "</TD>";
print "<TD CLASS=alertbox onClick=openalertbox();>";
  print "<DIV STYLE='color:#FFFFFF;font-family:verdana;font-size:12px;font-weight:bolder;text-align:center;'>"._TITALERTBOX_."</DIV>";
  print "<DIV STYLE='color:#FFFFFF;font-family:verdana;font-size:10px;'>".stampaAlertBox(1)."</DIV>";
print "</TD>";
print "<TD CLASS=msgbox STYLE='color:#FFFFFF;font-family:verdana;font-size:10px;vertical-align: top;' onClick=openmsgbox(1);>";
  print "<DIV >";
  $msgboxlen = strlen($msgbox['msg']);
  if ($msgboxlen>0) {
    print substr($msgbox['msg'],0,150).($msgboxlen >150 ?"[...]":"");
  } else {
    print "<SPAN STYLE='font-size:12px;font-weight:bolder;text-align: center;'>"._MSGMBOXNOMSG_."</SPAN>";
  }
  print "</DIV>";
print "</TD>";
  
print "</TR>";
print "</TABLE>";

//TIMETABLE


for ($iwks=0;$iwks<$weeks;$iwks++) {
  $ora_prec = $tt_orai;
  
  for ($i=0;$i<$numerogg*$daysweek;$i++) {
  
  }
  
  print "<TABLE CLASS=table_tto>";
  print "<TR><TD>&nbsp;</TD>";
  for ($i=0;$i<$numerogg*$daysweek;$i++) {
    //$appdt = $datai+(($i+($numerogg*$daysweek*$iwks))*24*60*60);
    $appdt = strtotime(($i+($numerogg*$daysweek*$iwks))." day",$datai); 
    $appwkday = date("w",$appdt);
    if ($tt_noday[$appwkday]!=1) {
      if (isset($festedays[$appdt])) {
        $style2 = "festa";
      } else {
        $style2 = "";
      }
      print "<TD CLASS='td_days $style2'>";
        print date("d/m/Y",$appdt)."<BR>".letter_weekday(date("w",$appdt));
        
      print "</TD>";
    }
  }
  
  for ($i=$ora_prec+$tt_step;$i<=$tt_oraf;$i+=$tt_step) {
    print "<TR>";
    print "<TD CLASS=td_hours NOWRAP>".date("H:i",$ora_prec)." - ".date("H:i",$i)."</TD>";
    for ($j=0;$j<$numerogg*$daysweek;$j++) {
      //$appdt = $datai + (($j+($numerogg*$daysweek*$iwks)) * 24*60*60);
      $appdt = strtotime(($j+($numerogg*$daysweek*$iwks))." day",$datai);
      
      $appwkday = date("w",$appdt);
      if ($tt_noday[$appwkday]!=1) {      
        $stylecella = STYLECELLA_NORMALE;
        $label = "&nbsp;";
        $curid="";
        $tooltipcmd="";
        //colore prefwkday
        $wkd = intval(date("w",$appdt));
        if (isset($prefday[$wkd])) {
          $apph = $i;
          if ($prefday[$wkd][$ora_prec]==1) {
            $stylecella=STYLECELLA_PREFDAY;
          }
        }
        
        $chiave = $appdt;
        //if ($lezioni[$appdt]['orai']==$ora_prec) {
        //if ($lezioni[$chiave][$ora_prec]['orai']==$ora_prec) {    //1/11/2010
        
        
        $icone_eventi="";
        
        $stylecella = STYLECELLA_NORMALE;
        if (isset($lezioni[$chiave][$ora_prec])) {
          //l'evento esiste (può essere cancellato, sostituito o attivo)

          //icone per evento cancellato
          if ($lezioni[$chiave][$ora_prec]['ne_fdel_1']>0) {
            //esistono degli eventi cancellati
            foreach ($lezioni[$chiave][$ora_prec]['fdel_1'] as $key_subevent => $cur_subevent) {
              $appnote = conv_textarea(convlang($cur_subevent['note']));
              $tooltipmsg = "<B>".date("H:i",$cur_subevent['orai'])." - ".date("H:i",($cur_subevent['orai'] + ($cur_subevent['durata']* 60)))." (".$cur_subevent['durata']."')";
              $tooltipmsg .= "<BR>".convlang($vettipo[$cur_subevent['tipo']]['descr']);
              $tooltipmsg .= "<BR>".$cur_subevent['customer_nome']." ".$cur_subevent['classi_descr']." [".$cur_subevent['corso_codlivello']."]";
            
              if ($cur_subevent['descr']!="") {
                $tooltipmsg .= "<BR>".convlang($cur_subevent['descr']);
              }
              $tooltipmsg .= "<BR>".$appnote."<BR></B>";
              $icone_eventi .= "<IMG SRC='../img/xrossa.png' onMouseover=\"hideddrivetip();ddrivetip('".addslashes($tooltipmsg)."')\"; onMouseout=\"hideddrivetip()\">&nbsp;&nbsp;";
            }
          }
          /*if ($lezioni[$chiave][$ora_prec]['fdel']==1) {
            //l'evento è stato cancellato
            $countspan[$j]=1;
            $appbordocella = eT_getStyleValue($lezioni[$chiave][$ora_prec]['style'],"background-color");
            $stylecella = STYLECELLA_NORMALE;
            $stylecella .= "border: 5px dashed $appbordocella;";
            $appnote = conv_textarea(convlang($lezioni[$chiave][$ora_prec]['note']));
            $label = "[".$appbordocella."]".$lezioni[$chiave][$ora_prec]['customer_nome']." ".$lezioni[$chiave][$ora_prec]['classi_descr']." [".$lezioni[$chiave][$ora_prec]['corso_codlivello']."]<BR>".$lezioni[$chiave][$ora_prec]['descr']."<BR>".$appnote;
            switch ($lezioni[$chiave][$ora_prec]['fdel']) {
              case 1:
                      $label.= "CANCELLATA";
                      break;
            }
            
          } else {
            if ($lezioni[$chiave][$ora_prec]['fsost']!=0) {
              //l'evento è stato sostituito
            } else {
          */
            
          if ($lezioni[$chiave][$ora_prec]['ne_attivo']>0) {
            $lastfineattivo= ($lezioni[$chiave][$ora_prec]['attivo'][1]['durata']*60) + $ora_prec;
          
            //l'evento è attivo
            $rowspan[$j]=$lezioni[$chiave][$ora_prec]['attivo'][1]['durata']*60/$tt_step;
            $countspan[$j]=$rowspan[$j];
            //$stylecella="background-color:#1E90FF;text-align:center;font-weight:bolder;font-size:10px;";
            $stylecella = $lezioni[$chiave][$ora_prec]['attivo'][1]['style'];
            $appnote = conv_textarea(convlang($lezioni[$chiave][$ora_prec]['attivo'][1]['note']));
            /* LABEL
            cliente - classe [corso]
            */
            $label = $lezioni[$chiave][$ora_prec]['attivo'][1]['customer_nome']." ".$lezioni[$chiave][$ora_prec]['attivo'][1]['classi_descr']." [".$lezioni[$chiave][$ora_prec]['attivo'][1]['corso_codlivello']."]<BR>".$lezioni[$chiave][$ora_prec]['attivo'][1]['descr']."<BR>".$appnote;
            
            //sostituzinoi e altre 
            switch ($lezioni[$chiave][$ora_prec]['attivo'][1]['fsost']) {
              case -3:
                      $label.= _LBLCONFSOST_;
                      break;
              case -2:
                      $label.= _LBLSOSTNOCONF_;
                      break;
              case -1:
                      $label.= _LBLSOSTREQ_;
                      break;
              case 1:
                      $label.=_LBLSOSTCONF_;
                      break;
            }
            
            switch ($lezioni[$chiave][$ora_prec]['attivo'][1]['fdel']) {
              case 1:
                      $label.= "CANCELLATA";
                      break;
            }
            
            $curid=$lezioni[$chiave][$ora_prec]['attivo'][1]['id'];
            
            $tooltipmsg = "<B>".date("H:i",$ora_prec)." - ".date("H:i",($ora_prec + ($lezioni[$chiave][$ora_prec]['attivo'][1]['durata']* 60)))." (".$lezioni[$chiave][$ora_prec]['attivo'][1]['durata']."')";
            $tooltipmsg .= "<BR>".convlang($vettipo[$lezioni[$chiave][$ora_prec]['attivo'][1]['tipo']]['descr']);
            $tooltipmsg .= "<BR>".$lezioni[$chiave][$ora_prec]['attivo'][1]['customer_nome']." ".$lezioni[$chiave][$ora_prec]['attivo'][1]['classi_descr']." [".$lezioni[$chiave][$ora_prec]['attivo'][1]['corso_codlivello']."]";
            
              if ($lezioni[$chiave][$ora_prec]['attivo'][1]['descr']!="") {
                $tooltipmsg .= "<BR>".convlang($lezioni[$chiave][$ora_prec]['attivo'][1]['descr']);
              }
            $tooltipmsg .= "<BR>".$appnote."<BR></B>";
            
            $tooltipcmd = "onMouseover=\"hideddrivetip();ddrivetip('".addslashes($tooltipmsg)."')\"; onMouseout=\"hideddrivetip()\" ";
          }
        } else {
          $rowspan[$j]=1;
        }
        
        if ($countspan[$j]==0 || $countspan[$j]==$rowspan[$j]) {
          $msgstr = "DATA e ORA SELEZIONATA:".date("d-m-Y",$appdt)." - ".date("H.i",$ora_prec);
          // ORIG
          //print "<TD CLASS=td_free ROWSPAN=".$rowspan[$j]." STYLE=\"$stylecella\" onClick=updvalori(\"".date("d/m/Y",$appdt)."\",\"".date("H:i",$ora_prec)."\",\"".$curid."\"); ".$tooltipcmd.">";
          print "<TD CLASS=td_free ROWSPAN=\"".$rowspan[$j]."\" STYLE=\"$stylecella\" onClick=updvalori(\"".date("d/m/Y",$appdt)."\",\"".date("H:i",$ora_prec)."\",\"".$curid."\");>";
            if ($icone_eventi!="") {
              print "$icone_eventi<BR>";
            }
            print "<DIV ".$tooltipcmd." >$label</DIV>";
          print "</TD>";
          
          //MOD
          /*
          print "<TD CLASS=td_free ROWSPAN=".$rowspan[$j]." STYLE=\"$stylecella\">";
          print "<TABLE BORDER=0 CELLPADDING=0 WIDTH=100%>";
            print "<TR>";
              print "<TD onClick=alert(".date("d-m-Y",$appdt).", ".date("H:i",$ora_prec).");>$label</TD>";
            print "</TR>";
          print "</TABLE></TD>";
          */
          
          //print "<TD CLASS=td_free ROWSPAN=".$rowspan[$j]." STYLE=\"$stylecella\" onClick=alert(".date("d-m-Y",$appdt).", ".date("H:i",$ora_prec).");>$label</TD>";
        } else {
          $countspan[$j]--;
        }
      }
    }
    print "</TR>";
    $ora_prec=$i;
  }
  print "</TABLE>";
}

//28/06/2010 12.19.29
//print "</TD></TR></TABLE>";

//form update from prof
print "<FORM NAME='aggdati' ACTION='conflitto.php' METHOD=POST>
<INPUT TYPE='hidden' NAME='cmd' VALUE=''>
<INPUT TYPE='hidden' NAME='codicepagina' VALUE='".codiceform(_FILECORRENTE_)."'>
<INPUT TYPE='hidden' NAME='selobj' VALUE=''>
<INPUT TYPE='hidden' NAME='dt' VALUE=''>
<INPUT TYPE='hidden' NAME='oraini' VALUE=''>
<INPUT TYPE='hidden' NAME='idprof' VALUE=''>
<INPUT TYPE='hidden' NAME='idtipo' VALUE=''>
<INPUT TYPE='hidden' NAME='idpstudi' VALUE=''>
<INPUT TYPE='hidden' NAME='idsostituzione' VALUE=''>
<INPUT TYPE='hidden' NAME='note' VALUE=''>
<INPUT TYPE='hidden' NAME='dlezione' VALUE=''>
<INPUT TYPE='hidden' NAME='compenso' VALUE=''>
<INPUT TYPE='hidden' NAME='insandcanc' VALUE=''>
<INPUT TYPE='hidden' NAME='idtocanc' VALUE=''>
<INPUT TYPE='hidden' NAME='newdt' VALUE='".date("d/m/Y",$datai)."'>
</FORM>";

print "<FORM NAME='aggprof' ACTION='"._FILECORRENTE_."' METHOD=POST>
<INPUT TYPE='hidden' NAME='idprof' VALUE='".$editval['idprof']."'>
<INPUT TYPE='hidden' NAME='newdt' VALUE=''>
<INPUT TYPE='hidden' NAME='gg' VALUE=''>
<INPUT TYPE='hidden' NAME='wks' VALUE='$wks'>
</FORM>";

?>
<SCRIPT LANGUAGE='javascript'>
function updvalori (data, ora,selobj) {
  var width  = 600;
  var height = 400;
  var left   = (screen.width  - width)/2;
  var top    = (screen.height - height)/2;
  var params = 'width='+width+', height='+height;
  params += ', top='+top+', left='+left;
  params += ', directories=no';
  params += ', location=no';
  params += ', menubar=no';
  params += ', resizable=no';
  params += ', scrollbars=no';
  params += ', status=no';
  params += ', toolbar=no';

  document.aggdati.dt.value=data;
  document.aggdati.oraini.value=ora;
  document.aggdati.selobj.value=selobj;
  
  var curprof = document.aggprof.idprof.value;
  
  document.aggdati.cmd.value=100;
  
  figlio = window.open("inputmain.php?selobj="+selobj+"&cmd=3&idprof="+curprof,"inputdati",params);
  if (figlio.opener==null) {
    figlio.opener=self;
    figlio.frames["saveopener"].opener=self;
  }
}

function updprof (idprof, nome) {
  document.aggprof.idprof.value=idprof;
  updtt();
}

function updwks(valore) {
  document.aggprof.wks.value=valore;
  updtt();
}

function updtt () {
  document.aggprof.newdt.value=document.nuovadt.newdt.value;
  document.aggprof.submit();
}
function newdt_today(idprof) {
  document.nuovadt.newdt.value = "<?=date("d/m/Y")?>";
  updtt();
}

function cambiadata(gg) {
  document.aggprof.gg.value=gg;
  updtt();
}
function openalertbox() {
  var width  = 600;
  var height = 400;
  var left   = (screen.width  - width)/2;
  var top    = (screen.height - height)/2;
  var params = 'width='+width+', height='+height;
  params += ', top='+top+', left='+left;
  params += ', directories=no';
  params += ', location=no';
  params += ', menubar=no';
  params += ', resizable=no';
  params += ', scrollbars=yes';
  params += ', status=no';
  params += ', toolbar=no';
  
  
  
  figlio2 = window.open("alertbox.php","alertbox",params);
  figlio2.opener=self;
}

function openmsgbox(selobj) {
  var width  = 600;
  var height = 400;
  var left   = (screen.width  - width)/2;
  var top    = (screen.height - height)/2;
  var params = 'width='+width+', height='+height;
  params += ', top='+top+', left='+left;
  params += ', directories=no';
  params += ', location=no';
  params += ', menubar=no';
  params += ', resizable=no';
  params += ', scrollbars=yes';
  params += ', status=no';
  params += ', toolbar=no';
  
  figlio3 = window.open("msgbox.php?selobj="+selobj,"alertbox",params);
}

var globalupdvalori = this.updvalori;

</SCRIPT>
