<?php
  require_once("dbcfg.php");

  //MENU
  $ne=0;
  $menu[$ne]['img']="../img/timetable.png";
  $menu[$ne]['label']=_LBLMBARTIMETAB_;
  $menu[$ne]['link']="timetable.php";
  $ne++;
  $menu[$ne]['img']="../img/fornitori.png";
  $menu[$ne]['label']=_LBLMBARFORNITORI_;
  $menu[$ne]['link']="frm_fornitori.php";
  $menu[$ne]['level']=20;
  $ne++;
  $menu[$ne]['img']="../img/persone.png";
  $menu[$ne]['label']=_LBLMBARCLIENTI_;
  $menu[$ne]['link']="frm_clienti.php";
  $menu[$ne]['level']=20;
  $ne++;
  $menu[$ne]['img']="../img/dizionario.png";
  $menu[$ne]['label']=_LBLMBARSTUDENTI_;
  $menu[$ne]['link']="frm_studenti.php";
  $menu[$ne]['level']=20;
  $ne++;
  $menu[$ne]['img']="../img/classe.png";
  $menu[$ne]['label']=_LBLMBARCLASSI_;
  $menu[$ne]['link']="frm_classi.php";
  $menu[$ne]['level']=20;
  
  $ne++;
  $menu[$ne]['img']="../img/lavagna.png";
  $menu[$ne]['label']=_LBLMBARPSTUDIO_;
  $menu[$ne]['link']="frm_pstudio.php";
  $menu[$ne]['level']=20;

  $ne++;
  $menu[$ne]['img']="../img/docente.png";
  $menu[$ne]['label']=_LBLMBARDOCENTI_;
  $menu[$ne]['link']="frm_docenti.php";
  $menu[$ne]['level']=20;
  $ne++;
  $menu[$ne]['img']="../img/dollaro.png";
  $menu[$ne]['label']=_LBLMBARREPORT_;
  $menu[$ne]['link']="contab_main.php";
  $menu[$ne]['level']=30;
  $ne++;
  $menu[$ne]['img']="../img/gestuser.png";
  $menu[$ne]['label']=_LBLMBARUSERS_;
  $menu[$ne]['link']="frm_user.php";
  $menu[$ne]['level']=30;
  
  if ($_SESSION['mgdebug']==1) {
    $ne++;
    $menu[$ne]['img']="../img/gestuser.png";
    $menu[$ne]['label']=_LBLMBARCONTABILITA_;
    $menu[$ne]['link']="frm_main_contabilita.php";
    $menu[$ne]['level']=30;
  }
  
  if ($_SESSION['mgdebug']==1) {
    $ne++;
  $menu[$ne]['img']="../img/lavagna.png";
  $menu[$ne]['label']=_LBLMBARCLASSI_;
  $menu[$ne]['link']="frm_classi2.php";
  $menu[$ne]['level']=20;
  }
  
  
  
  //MENU CONTABILITA
  $curmenu=1;//CONTABILITA
  $ne=0;
  
  $ne++;
  $submenu[$curmenu][$ne]['label'] = "Entrate";
  $submenu[$curmenu][$ne]['link'] = "frm_entrate.php";
  $submenu[$curmenu][$ne]['label'] = "Entrate";
  $submenu[$curmenu][$ne]['label'] = "Entrate";
?>