<?php
  require_once("form_cfg.php");

?>
<HEAD>
  <link rel="stylesheet" type="text/css" href="../css/titoloframe.css" />
  <TITLE>TimeTableOnline</TITLE>
</HEAD>
<BODY bgcolor="#dddddd">

<?=$redir?>
<div style="margin:0; padding:0;">


  <h1><?=$_SESSION['enti_descr']?></h1>
  <table border="0" align="left" width="99%" class='intestazione'>
    <TR>
      <TD align="center" nowrap>
        <img src="../img/user.png" border="0" width="32" height="32"><br> <? echo _LBLMBARUSER_." ".$_SESSION['user_nome']." [".$_SESSION['username']."]" ?></a>
        <?php
          if ($_SESSION['mgdebug']==1) {print "<BR>IN DEBUG";}
        ?>
      </TD>

     
      <?
      foreach ($menu as $keymenu =>$curmenu) {
        if (!isset($curmenu['level']) || $_SESSION['user_level']>=$curmenu['level']) {
          print "<TD><A HREF='".$curmenu['link']."' TARGET=inserimento><IMG SRC='".$curmenu['img']."' border='0'><BR>".$curmenu['label']."</A></TD>";
        }
      }
      
      ?>  
     <!--<TD align="center" nowrap>
        <a href="../prog/changepw.php" target="inserimento"><img src="../img/edit_user.png" border="0" width="32" height="32"><br>Modifica password</a>
      </TD>-->   
      <TD align="center" nowrap>
        <a href="../logout.php" target="_top"><img src="../img/exit.gif" border="0" width="32" height="32"><br><?=_LBLMBARLOGOUT_?></a>
      </TD>
    </TR>
  </table>
  <SCRIPT>
  function ricaricami() {
    parent.reminder.location.reload(true);
    setTimeout('ricaricami()', 30000);
  }
  ricaricami();
  </SCRIPT>
</BODY>
</HTML>