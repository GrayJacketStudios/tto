<?php
require_once("form_cfg.php");

$lblfsost[0]=_SOSTCONF_;
$lblfsost[1]=_EVTSOSTITUITO_;
$lblfsost[-1]=_SOSTDAPROG_;
$lblfsost[-2]=_SOSTPROGDACONF_;
$lblfsost[-3]=_SOSTDACONF_;


function stampaAlertBox($boxtype=1) {
  global $lblfsost;
  $output="";
  
  $query = "SELECT * FROM tipo";
  $result = mysql_query ($query) or die ("Error2.1");
  while ($line = mysql_fetch_assoc($result)) {
    $tipo[$line['id']]['descr']=$line['descr'];
  }
  
  $query = "SELECT appuntamento.id as appuntamento_id, appuntamento.idpstudi as idpstudi, appuntamento.iddocente as iddocente,
    appuntamento.dtini as dtini, appuntamento.dtfine as dtfine, appuntamento.tipo as tipo, appuntamento.note as note, 
    appuntamento.idsostituzione as idsostituzione, appuntamento.fsost as fsost, appuntamento.fnotify as fnotify,
    docente.nome as docente_nome, docente.email as docente_email, pstudi.descr as pstudi_descr, corso.codlivello as codlivello
    FROM appuntamento LEFT JOIN docente ON appuntamento.iddocente=docente.id AND docente.trashed<>1
    LEFT JOIN pstudi ON appuntamento.idpstudi=pstudi.id AND pstudi.trashed<>1
    LEFT JOIN corso ON pstudi.idcorso=corso.id
    WHERE appuntamento.trashed<>1 AND fsost<0;";
    $result = mysql_query($query) or die ("Error4.1");
    
    while ($line=mysql_fetch_assoc($result)) {
      $evt[$line['fsost']][$line['appuntamento_id']]['idpstudi']=$line['idpstudi'];
      $evt[$line['fsost']][$line['appuntamento_id']]['fsost']=$line['fsost'];
      $evt[$line['fsost']][$line['appuntamento_id']]['iddocente']=$line['iddocente'];
      $evt[$line['fsost']][$line['appuntamento_id']]['docente_nome']=$line['docente_nome'];
      $evt[$line['fsost']][$line['appuntamento_id']]['docente_email']=$line['docente_email'];
      $evt[$line['fsost']][$line['appuntamento_id']]['dtini']=$line['dtini'];
      $evt[$line['fsost']][$line['appuntamento_id']]['dtfine']=$line['dtfine'];
      $evt[$line['fsost']][$line['appuntamento_id']]['tipo']=$line['tipo'];
      $evt[$line['fsost']][$line['appuntamento_id']]['note']=$line['note'];
      $evt[$line['fsost']][$line['appuntamento_id']]['idsostituzione']=$line['idsostituzione'];
      $evt[$line['fsost']][$line['appuntamento_id']]['pstudi_descr']=$line['pstudi_descr'];
      $evt[$line['fsost']][$line['appuntamento_id']]['codlivello']=$line['codlivello'];      
    }
    //myprint_r($evt);
    //myprint_r($lblfsost);
    
    /*foreach ($evt as $key=>$cur) {
      print $lblfsost[$key].": ".count($count)." eventi<BR>";
    }*/
    $elabfsost = array(-1,-3);
    
    foreach ($elabfsost as $key=>$cur) {
      $app = count($evt[$cur]);
      if ($app>0) {
        $output .= "<IMG SRC='../img/ledred.png' STYLE='vertical-align:middle;' ALT='"._ALERT_."' TITLE='"._ALERT_."'>&nbsp;";
      } else {
        $output .= "<IMG SRC='../img/ledgreen.png' STYLE='vertical-align:middle;' ALT='"._OK_."' TITLE='"._OK_."'>&nbsp;";
      }
      $output .= $lblfsost[$cur].": ".$app."<BR>";
      
      if ($boxtype==2) {
        $output.= "<TABLE BORDER=0 WIDTH=100%>";
        //elenco degli eventi e relativi link
        foreach ($evt[$cur] as $key2 => $cur2) {
          if ($cur2['fsost']==-1) {
            //evt in attesa di essere sostituito
            $output .="<TR><TD>".$cur2['docente_nome']."</TD><TD STYLE='font-weight:bolder;font-size:12px;'>"._ABOXTITSOSTREQ_."</TD></TR>
            <TR><TD COLSPAN=2>"._ABOXMSGSOSTREQ_."<BR>
            "._TIPOEVENTO_.": ".convlang($tipo[$cur2['tipo']]['descr'])."<BR>
            "._DATAEVENTO_.": ".date("d/m/Y",$cur2['dtini'])."<BR>
            "._ORAINIZIO_.": ".date("H:i",$cur2['dtini'])."<BR>
            "._ORAFINE_.": ".date("H:i",$cur2['dtfine'])."<BR>
            "._CLASSE_.": ".$cur2['pstudi_descr']."<BR>
            "._CORSO_.": ".$cur2['codlivello']."<BR><BR><INPUT TYPE='button' VALUE='"._BTNPLANSOST_."' onClick=apriupdvalori(\"".date("d/m/Y",$cur2['dtini'])."\",\"".date("H:i",$cur2['dtini'])."\",".$key2.");><BR></TD></TR><TR><TD COLSPAN=2><HR SIZE=2></TD></TR>";
          }
          if ($cur2['fsost']==-3) {
            //evt in attesa di conferma
            $output .="<TR><TD>".$cur2['docente_nome']."</TD><TD STYLE='font-weight:bolder;font-size:12px;'>"._ABOXTITCONFSOST_."</TD></TR>
            <TR><TD COLSPAN=2>"._ABOXMSGCONFSOST_."<BR>
            "._TIPOEVENTO_.": ".convlang($tipo[$cur2['tipo']]['descr'])."<BR>
            "._DATAEVENTO_.": ".date("d/m/Y",$cur2['dtini'])."<BR>
            "._ORAINIZIO_.": ".date("H:i",$cur2['dtini'])."<BR>
            "._ORAFINE_.": ".date("H:i",$cur2['dtfine'])."<BR>
            "._CLASSE_.": ".$cur2['pstudi_descr']."<BR>
            "._CORSO_.": ".$cur2['codlivello']."<BR>
            <BR>
            "._LBLDOCSOSTITUIRE_.": ".$evt[-2][$cur2['idsostituzione']]['docente_nome']."<BR>
            <BR>
            <A HREF='".$urlbase."/mask/confsost.php?selobj=$key2&ids=".$cur2['idsostituzione']."'>"._ABOXMSGCLICKCONFIRM_."</A>
            <BR></TD></TR><TD COLSPAN=2><HR SIZE=2></TD></TR>";
          }
        }
        $output .= "</TABLE><BR>";
      }
      
    }
    
    
    return $output;
}
?>
<SCRIPT>
  function apriupdvalori (data, ora, selobj){
    window.opener.globalupdvalori(data,ora,selobj);
    this.close();
  }
</SCRIPT>