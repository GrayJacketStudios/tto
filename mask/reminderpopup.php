<?php
  $pagever = "1.0";
  $pagemod = "17/03/2011 10.32.25";
  require_once("form_cfg.php");
  
  
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <link rel="stylesheet" type="text/css" href="../css/main.css" />
  <link rel="stylesheet" href="dhtmlgoodies_calendar/dhtmlgoodies_calendar.css" media="screen"></link>
  <STYLE>
    .reminder_data{font-size:8px;font-weight:normal;}
    .reminder_ora{font-size:16px;font-weight:bolder;}
    .reminder_oggetto{font-size:13px;}
    .reminder_descrizione{font-size:10px;font-weight:normal;font-family:Verdana;width:98%;border:none;border-top:1px dashed #0000FF;}
    .bordato{border:2px solid #FF981F;}
  </STYLE>
</head>
<body>
<script type="text/javascript" src="dhtmlgoodies_calendar/dhtmlgoodies_calendar.js"></script>
<?php
  include ("iniziopagina.php");

  //CARICAMENTO VALORI PER EDIT
  define("FILE_CORRENTE", "reminderpopup.php");
?>
<SCRIPT>
  //pads left
  String.prototype.lpad = function(padString, length) {
  	var str = this;
      while (str.length < length)
          str = padString + str;
      return str;
  }
   
  //pads right
  String.prototype.rpad = function(padString, length) {
  	var str = this;
      while (str.length < length)
          str = str + padString;
      return str;
  }
  
  function lpad(sorg, padString, length) {
      var str="";
      str=sorg.toString();
      while (str.length < length)
          str = padString + str;
      return str;
  }
  
  function rpad(sorg, padString, length) {
      var str="";
      str=sorg.toString();
      while (str.length < length)
          str = str + padString;
      return str;
  } 
  
  
  function calcoladata(minuti) {
    var d = new Date();
    var app="";
    d = new Date(d.getTime() + minuti * 60 * 1000);
    //alert(d.getTime());
    
    //alert(lpad(9,"0",2));
    
    app = d.getFullYear();
    app = app.toString() + lpad((d.getMonth()+1),"0",2); 
    app = app.toString() + lpad(d.getDate(),"0",2);
    app = app.toString() + lpad(d.getHours(),"0",2);
    app = app.toString() + lpad(d.getMinutes(),"0",2);
    app = app.toString() + "00";
    return app.toString();
  }
  
  function aggiornadata(minuti) {
    nuovasdt = calcoladata(minuti);
    document.reminder.cmd.value='<?=FASEUPDDATA?>';
    
    document.reminder.nuovasdt.value=nuovasdt;
    document.reminder.submit();
    self.close();
  }
  function PlaySound(soundObj) {
    var sound = document.getElementById(soundObj);
    sound.Play();
  }
  
  function mostracalendario(elecall, eleput) {
    var data = new Date();
    
    var elem = document.getElementById("oralibera");
    elem.style.display = "";
    
    var elem = document.getElementById("btn_salva");
    elem.style.display = "";
    
    if (eleput.value=="") {
      app="";
      app = app.toString() + lpad(data.getDate(),"0",2)+"/";
      app = app.toString() + lpad((data.getMonth()+1),"0",2)+"/";
      app = app.toString() + data.getFullYear()+" ";
      app = app.toString() + lpad(data.getHours(),"0",2)+":";
      app = app.toString() + lpad(data.getMinutes(),"0",2);
      
      eleput.value = app;
    }
    displayCalendar(eleput,'dd/mm/yyyy hh:ii',elecall,true);
    document.reminder.dtdacal.value="1";
    if (document.reminder.cmd.value!="<?=FASEINS?>") {
      document.reminder.cmd.value='<?=FASEUPDDATA?>';
    }
  }
  function abilitaedit() {
    var elem = document.getElementById("show_oggetto");
    elem.style.display = "none";
    var elem = document.getElementById("edit_oggetto");
    elem.style.display = "";
    
    var elem = document.getElementById("reminder_descrizione");
    elem.readOnly  = false;
    
    var elem = document.getElementById("show_link");
    elem.style.display = "none";
    var elem = document.getElementById("edit_link");
    elem.style.display = "";
    
    var elem = document.getElementById("edit_email");
    elem.style.display = "";
    
    var elem = document.getElementById("btn_salva");
    elem.style.display = "";
    
    if (document.reminder.cmd.value!="<?=FASEINS?>") {
      document.reminder.cmd.value='<?=FASEMOD?>';
    }
  }
  function toggleemail() {
    var elem = document.getElementById("notifymail_ok");
    
    if (elem.style.display=="none") {
      elem.style.display="";
      
      var elem = document.getElementById("notifymail_ko");
      elem.style.display="none";
    } else {
      elem.style.display="none";
      
      var elem = document.getElementById("notifymail_ko");
      elem.style.display="";
    }
    var elem = document.getElementById("btn_salva");
    elem.style.display = "";
  }
  function salva() {
    chiudi = false;
    
    if (document.reminder.cmd.value=="<?=FASEUPDDATA?>" || document.reminder.cmd.value=="<?=FASETERMINA?>" ) {
      //chiudi=true;
    }
    document.reminder.submit();
    if (chiudi) {
      self.close();
    }    
  }
  function termina() {
    var avanti = confirm ("<?=_MSGSEISICURO_?>");
			if (avanti==true) {
					document.reminder.cmd.value="<?=FASETERMINA?>";
          salva();
			}
    
  }
</SCRIPT>
<?php
  $selobj = $_REQUEST['selobj'];
  $op = $_REQUEST['op'];
  $query = "SELECT id, dataora, oggetto, descr, link, mail
            FROM reminder
            WHERE id=".($op==FASEINS ? "-1" : $selobj);
  $result = mysql_query($query) or die("Errore1.1");
  $line = mysql_fetch_assoc($result);
  
  
  print "<FORM NAME='reminder' ACTION='". FILE_DBQUERY ."' METHOD='POST'><INPUT TYPE='hidden' NAME='testpopup' VALUE='A'>
        <INPUT TYPE='hidden' NAME='cmd' VALUE='".($op==FASEINS ? FASEINS : FASEUPDDATA)."'>
       <INPUT TYPE='hidden' NAME='selobj' VALUE='". $selobj ."'>
       <INPUT TYPE='hidden' NAME='codicepagina' VALUE='". codiceform(FILE_CORRENTE) ."'>
       <INPUT TYPE='hidden' NAME='nuovasdt' VALUE=''>";
    print "<TABLE STYLE='width:100%;margin:0;'>";
      print "<TR><TD>".($op!=FASEINS ? "<SPAN CLASS='reminder_data'>".eT_sdtprint($line['dataora'],"d/m/Y")."</SPAN><BR><SPAN CLASS='reminder_ora'>".eT_sdtprint($line['dataora'],"H:i")."</SPAN>": "&nbsp;")."</TD>";
      print "<TD CLASS='reminder_oggetto'><SPAN id='show_oggetto'>".$line['oggetto']."</SPAN><SPAN id='edit_oggetto' STYLE='display:none;'><IMG ALIGN=RIGHT SRC='../img/matita5_22.png'><INPUT TYPE='text' SIZE=40 NAME='edit_oggetto' VALUE='".$line['oggetto']."' CLASS=' ".($op==FASEINS ? " bordato ":"")."'></SPAN></TD></TR>";
      print "<TR><TD COLSPAN=2><TEXTAREA NAME='reminder_descr' id='reminder_descrizione' COLS=40 ROWS=8 CLASS='reminder_descrizione ".($op==FASEINS ? " bordato ":"")."' READONLY>";
        print $line['descr'];
      print "</TEXTAREA></TD></TR>";
      print "<TR><TD COLSPAN=2>";
        print "<SPAN id='show_link'><A HREF='".$line['link']."'>".$line['link']."</A></SPAN><SPAN id='edit_link' STYLE='display:none;'><IMG STYLE='vertical-align: middle;'  SRC='../img/link_32.png'>&nbsp;<INPUT TYPE='text' SIZE=60 NAME='edit_link' VALUE='".$line['link']."' CLASS=' ".($op==FASEINS ? " bordato ":"")."'></SPAN>";
      print "</TD></TR>";
      print "<TR><TD COLSPAN=2 STYLE='border-bottom:1px dashed #0000FF;'>";
        print "<SPAN STYLE='display:none;' id='edit_email'><IMG STYLE='vertical-align: middle;' SRC='../img/email2_32.png'>&nbsp;<INPUT id='reminder_mail_edit' SIZE=60  TYPE='text' NAME='reminder_mail' VALUE='".$line['mail']."' CLASS=' ".($op==FASEINS ? " bordato ":"")."'></SPAN>";
      print "</TR>";
      print "<TR><TD COLSPAN=2>";
        print "<IMG id='notifymail_ok' STYLE='vertical-align: middle;".($line['mail']==""?"display:none;":"")."' SRC='../img/email2_48.png' ALT='"._LBLSINOTIFYMAIL_."' TITLE='"._LBLSINOTIFYMAIL_."'  onClick=toggleemail();><IMG id='notifymail_ko'  STYLE='vertical-align: middle;".($line['mail']=="" ? "":"display:none;")."' SRC='../img/email2_48_grigio.png' ALT='"._LBLNONOTIFYMAIL_."' TITLE='"._LBLNONOTIFYMAIL_."'  onClick=toggleemail();><SPAN STYLE='margin-left:5px;margin-right:5px;border-right:2px dotted #0000FF;'>&nbsp;</SPAN>";
        print "<IMG STYLE='vertical-align: middle;' SRC='../img/matita5_48.png' ALT='"._LBLMODIFICA_."' TITLE='"._LBLMODIFICA_."'  onClick=abilitaedit();><SPAN STYLE='margin-left:5px;margin-right:5px;border-right:2px dotted #0000FF;'>&nbsp;</SPAN>"; 
        print "<IMG id='imgoralibera' STYLE='vertical-align: middle;' SRC='../img/reminder_dataora.png' BORDER=0 ALT='"._LBLORALIBERA_."' TITLE='"._LBLORALIBERA_."'  onClick=mostracalendario(this,document.reminder.show_dtnuova);>";
        print "<SPAN id='oralibera' STYLE='display:none;'>&nbsp;<INPUT STYLE='font-size:12px;font-weight:bolder;' TYPE='text' NAME='show_dtnuova' VALUE=''><INPUT TYPE='hidden' NAME='dtdacal' VALUE='0'></SPAN>&nbsp;<INPUT STYLE='display:none;' id='btn_salva' TYPE='button' NAME='btn_salva' VALUE='"._BTNSALVA_."' onClick=salva()>";
        if ($op!=FASEINS) {
          print "<BR><BR>";
          print "<IMG SRC='../img/5min_32.png' BORDER=0  ALT='"._LBLPIU5MIN_."' TITLE='"._LBLPIU5MIN_."' onClick=aggiornadata(5);>&nbsp;&nbsp;&nbsp;&nbsp;";
          print "<IMG SRC='../img/30min_32.png' BORDER=0 ALT='"._LBLPIU30MIN_."' TITLE='"._LBLPIU30MIN_."' onClick=aggiornadata(30); >&nbsp;&nbsp;&nbsp;&nbsp;";
          print "<IMG SRC='../img/60min_32.png' BORDER=0 ALT='"._LBLPIU60MIN_."' TITLE='"._LBLPIU60MIN_."' onClick=aggiornadata(60);>&nbsp;&nbsp;&nbsp;&nbsp;";
          print "<IMG SRC='../img/1day_32.png' BORDER=0  ALT='"._LBLPIU1DAY_."' TITLE='"._LBLPIU1DAY_."' onClick=aggiornadata(1440);>&nbsp;&nbsp;&nbsp;&nbsp;";
          print "<IMG SRC='../img/cancel_32.png' BORDER=0  ALT='"._LBLTERMINA_."' TITLE='"._LBLTERMINA_."' onClick=termina();>&nbsp;&nbsp;";
        }
      print "</TD></TR>";
    print "</TABLE>";
  print "</FORM>";
  
?>
<embed src="notify.wav" autostart="true" width="0" height="0" id="sound1"
enablejavascript="true">
<?php
  if ($op==FASEINS) {
?>
    <SCRIPT>
      abilitaedit();
      var elem = document.getElementById("imgoralibera");
      mostracalendario(elem,document.reminder.show_dtnuova);
    </SCRIPT>
<?php
  }
?>