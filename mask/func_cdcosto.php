<?php
  //FUNZIONI PER CENTRODICOSTO
  
  function ins_struttura($partenza, &$vetcdcosto, $deep, $curcodice) {
    global $appvet;    
    foreach ($appvet[$partenza] as $key => $cur) {  
      //ins
      $vetcdcosto[$partenza][$cur['id']]['id']=$cur['id'];
      $vetcdcosto[$partenza][$cur['id']]['codice']=$curcodice.$cur['codice'];
      $vetcdcosto[$partenza][$cur['id']]['descr']=$cur['descr'];
      $vetcdcosto[$partenza][$cur['id']]['idpadre']=$cur['idpadre'];
      $vetcdcosto[$partenza][$cur['id']]['tipo']=$cur['tipo'];
      
      
      
      if (count($appvet[$key]) > 0) {
        $deep = ins_struttura ($key, $vetcdcosto[$partenza][$cur['id']]['item'], $deep+1, $vetcdcosto[$partenza][$cur['id']]['codice'].".");
      }
      
      $vetcdcosto[$partenza][$cur['id']]['deep']=$deep;
    }
    return $deep;
  }

  function stampa_struttura($partenza, $vettore, $deep) {
    foreach ($vettore[$partenza] as $key => $cur) {
      //spazi
      $pre="";
      for ($i=0;$i<$deep;$i++) {
        $pre .= "&nbsp;&nbsp;&nbsp;";
        //print "XXX|";
      }
      print $pre;
      print "<A HREF=# onClick=selelemento(\"".$cur['id']."\",\"".$cur['codice']."\",\"". fullescape($cur['descr'])."\")>";
      
      print $cur['codice']." ".$cur['descr'];
      
      print "</A>";
      print "<BR>";
      
      if (count($cur['item']) > 0) {
        stampa_struttura ($key, $cur['item'],$deep+1);
      }
      
    }
  }
  
  function vettore_struttura($partenza, $vettore, $deep, &$vetrisultato) {
    foreach ($vettore[$partenza] as $key => $cur) {
      //spazi
      
      $vetrisultato[$cur['codice']]['descr']=$cur['descr'];
      $vetrisultato[$cur['codice']]['id']=$cur['id'];
      
      if (count($cur['item']) > 0) {
        vettore_struttura ($key, $cur['item'],$deep+1, $vetrisultato);
      }
      
    }
  }  
  
  function carica_dati() {
    global $appvet;
    //CENTRI DI COSTO
    $query = "SELECT id, codice, descr, idpadre, tipo, CAST( codice AS DECIMAL ) AS ordinenum
              FROM cdicosto
              WHERE trashed <>1
              ORDER BY ordinenum";
    $result = mysql_query($query) or die ("Error_1.1");
      
    while ($line = mysql_fetch_assoc($result)) {
      $chiave = $line['idpadre'];
      $chiave2 = $line['id'];
      $appvet[$chiave][$chiave2]['id']=$line['id'];
      $appvet[$chiave][$chiave2]['codice']=$line['codice'];
      $appvet[$chiave][$chiave2]['descr']=$line['descr'];
      $appvet[$chiave][$chiave2]['idpadre']=$line['idpadre'];
      $appvet[$chiave][$chiave2]['tipo']=$line['tipo'];
          
    }
  }  
?>
