<?php
  require_once("form_cfg.php");
  ?>
<HEAD>
  <link rel="stylesheet" type="text/css" href="../css/titoloframe.css" />
</HEAD>
<BODY bgcolor="#dddddd" STYLE='margin:0;'>
<SCRIPT>
</SCRIPT>
<?
  $query = "SELECT id, dataora, oggetto, descr, link, mail
            FROM reminder
            WHERE idutente=". $_SESSION['user_id']." AND attivo=1 AND trashed<>1 AND dataora<='".date("YmdHi00")."'";
  $result = mysql_query($query) or die ("Errore1.1");
  
  
  $vetreminder=array();
  while ($line = mysql_fetch_assoc($result)) {
    $chiave = $line['id'];
    
    $vetreminder[$chiave]['data']=$line['data'];
    $vetreminder[$chiave]['ora']=$line['ora'];
    $vetreminder[$chiave]['oggetto']=$line['oggetto'];
    $vetreminder[$chiave]['descr']=$line['descr'];
    $vetreminder[$chiave]['link']=$line['link'];
    $vetreminder[$chiave]['mail']=$line['mail'];
  }
  
  print "<CENTER><IMG SRC='../img/sveglia_22.png' BORDER=0 STYLE='margin-top:15px;' onClick=apriins(); ALT='' TITLE=''>";
  print "<BR>";
  print "<SPAN STYLE='font-size:10px;font-weight:bolder;color:#0000FF;margin-top:2px;'>".count($vetreminder)."</SPAN></CENTER>";
  
  /*$query = "SELECT id, data, ora, oggetto, descr, link, mail
            FROM reminder
            WHERE attivo=1 AND trashed<>1 AND data<='".date("Ymd")."' AND ora<='".date("Hi00")."'";
            */
  
  
?>

<SCRIPT>
  function chiamapopup(id,indice) {
    var figlio;
    var width  = 450;
    var height = 330;
    var left   = (screen.width/100)*(10+indice)
    var top    = (screen.height/100)*(10+indice);
    var params = 'width='+width+', height='+height;
    params += ', top='+top+', left='+left;
    params += ', directories=no';
    params += ', location=no';
    params += ', menubar=no';
    params += ', resizable=yes';
    params += ', scrollbars=yes';
    params += ', status=no';
    params += ', toolbar=no';
    
    try
    {	figlio = window.open("","promemoria"+id,params);
      //figlio.checkPage();
      figlio.document.reminder.testpopup.value='B';
    }
    
    catch(error)
    {	figlio = window.open("reminderpopup.php?selobj="+id,"promemoria"+id,params);
      if (figlio.opener==null) {
        figlio.opener=self;
        figlio.frames["saveopener"].opener=self;
      }
    }
    
    
  }
  
function ricaricami() {
  location.reload(true);
}

function apriins() {
  var figlionew;
    var width  = 450;
    var height = 450;
    var left   = (screen.width-width)/2
    var top    = (screen.height-height)/2;
    var params = 'width='+width+', height='+height;
    params += ', top='+top+', left='+left;
    params += ', directories=no';
    params += ', location=no';
    params += ', menubar=no';
    params += ', resizable=yes';
    params += ', scrollbars=yes';
    params += ', status=no';
    params += ', toolbar=no';
    
    figlionew = window.open("reminderpopup.php?op=<?=FASEINS?>","promemorianew",params);
    if (figlionew.opener==null) {
      figlionew.opener=self;
    }
}

//IMPOSTA RELOAD PAGINA CON TIMEOUT
//setTimeout('ricaricami()', 30000);

var data=new Date();

str="";
str = data.getDate()+"/"+(data.getMonth()+1)+"/"+data.getFullYear()+" "+data.getHours()+":"+data.getMinutes()+":"+data.getSeconds();

document.images[0].alt = '<?=_LBLLASTUPDATE_." "?>'+str;
document.images[0].title = '<?=_LBLLASTUPDATE_." "?>'+str;

<?php
  $i=1;
  foreach ($vetreminder as $key=>$cur) {
    print "chiamapopup(".$key.",".$i.");\n";
    $i++;
  }
?>
/*
chiamapopup(100,1);
chiamapopup(100,2);

chiamapopup(100,3);
chiamapopup(100,4);
chiamapopup(100,5);
chiamapopup(100,6);
*/

</SCRIPT>