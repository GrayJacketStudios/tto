<?php
  $pagever = "1.0";
  $pagemod = "12/07/2010 21.56.52";
  define("APP_DEBUG",0);
  
  require_once ("form_cfg.php");
  require "../phpmailer/class.phpmailer.php";
  
  $backurl = urldecode($_REQUEST['backurl']);
  $tipoact = $_REQUEST['tipoact'];
  
  //myprint_r($_REQUEST);
  
  switch ($tipoact) {
    case "1":
            $notify=1;
            break;
    case "2":
            $notify=2;
            break;
  }
  
  $admaddr[1]['nome'] = "Michele";
  $admaddr[1]['addr'] = "michele.mentucci@gmail.com";
  $admaddr[2]['nome'] = "Gionata admin";
  $admaddr[2]['addr'] = "partireper@gmail.com";
  
  $query = "SELECT user.nome, user.email FROM user  WHERE trashed<>1 AND attivo=1 AND (fboss=1 OR fviceboss=1)";
  $result = mysql_query ($query) or die ("Error5.1");
  $i=3;
  while ($line = mysql_fetch_assoc($result)) {
    if ($line['email']!="") {
      $admaddr[$i]['nome']=$line['nome'];
      $admaddr[$i]['addr']=$line['email'];
      $i++;
    }
  }
    
  $query = "SELECT * FROM tipo";
  $result = mysql_query ($query) or die ("Error2.1");
  while ($line = mysql_fetch_assoc($result)) {
    $tipo[$line['id']]['descr']=$line['descr'];
  }
  mysql_free_result($result);
  
  $query = "SELECT appuntamento.id as appuntamento_id, appuntamento.idpstudi as idpstudi, appuntamento.iddocente as iddocente,
    appuntamento.dtini as dtini, appuntamento.dtfine as dtfine, appuntamento.tipo as tipo, appuntamento.note as note, 
    appuntamento.idsostituzione as idsostituzione, appuntamento.fsost as fsost, appuntamento.fnotify as fnotify,
    docente.nome as docente_nome, docente.email as docente_email, pstudi.descr as pstudi_descr, corso.codlivello as codlivello
    FROM appuntamento LEFT JOIN docente ON appuntamento.iddocente=docente.id AND docente.trashed<>1
    LEFT JOIN pstudi ON appuntamento.idpstudi=pstudi.id AND pstudi.trashed<>1
    LEFT JOIN corso ON pstudi.idcorso=corso.id
    WHERE appuntamento.trashed<>1 AND fnotify=".$notify;
  $result = mysql_query ($query) or die ("Error_1.1");
  
  $idpstudi = "-1, ";
  while ($line = mysql_fetch_assoc($result)) {
    $chiave = $line['appuntamento_id'];
    
    $evt[$chiave]['idpstudi'] = $line['idpstudi'];
    $evt[$chiave]['iddocente'] = $line['iddocente'];
    $evt[$chiave]['dtini'] = $line['dtini'];
    $evt[$chiave]['dtfine'] = $line['dtfine'];
    $evt[$chiave]['tipo'] = $line['tipo'];
    $evt[$chiave]['note'] = $line['note'];
    $evt[$chiave]['idsostituzione'] = $line['idsostituzione'];
    $evt[$chiave]['fsost'] = $line['fsost'];
    $evt[$chiave]['fnotify'] = $line['fnotify'];
    $evt[$chiave]['docente_nome'] = $line['docente_nome'];
    $evt[$chiave]['docente_email'] = $line['docente_email'];
    $evt[$chiave]['pstudi_descr'] = $line['pstudi_descr'];
    $evt[$chiave]['codlivello'] = $line['codlivello'];
    
    if ($line['idsostituzione']!="") {$evt[$line['idsostituzione']]['idsostda']=$chiave;}
    
    $idpstudi .= $line['idpstudi'].", ";
    
  }
  
  $query = "SELECT studente.id as studente_id, studente.idcustomer as studente_idcustomer, studente.nome as studente_nome,
studente.email as studente_email1, studente.email2 as studente_email2, customer.nome as customer_nome, customer.email as customer_email, classe.idpstudi as idpstudi
FROM studente INNER JOIN classe ON studente.id=classe.idstudente
LEFT JOIN customer ON studente.idcustomer=customer.id
WHERE classe.idpstudi IN (".substr($idpstudi,1,-2).") AND studente.trashed<>1";

  $result = mysql_query ($query) or die ("Error1.2$query");
  while ($line = mysql_fetch_assoc($result)) {
    $studenti[$line['idpstudi']][$line['studente_id']]['studente_idcustomer']=$line['studente_idcustomer'];
    $studenti[$line['idpstudi']][$line['studente_id']]['studente_nome']=$line['studente_nome'];
    $studenti[$line['idpstudi']][$line['studente_id']]['studente_email1']=$line['studente_email1'];
    $studenti[$line['idpstudi']][$line['studente_id']]['studente_email2']=$line['studente_email2'];
    $studenti[$line['idpstudi']][$line['studente_id']]['customer_nome']=$line['customer_nome'];
    
    $customer[$line['idpstudi']][$line['studente_idcustomer']]['customer_nome']=$line['customer_nome'];
    $customer[$line['idpstudi']][$line['studente_idcustomer']]['customer_email']=$line['customer_email'];
    
  }
  
  //myprint_r($studenti);
  //myprint_r($customer);
  
  //myprint_r($evt);

  $header = "<HTML><HEAD><STYLE>body {font-family:verdana, helvetica;font-size:12pt;color:#2600A5;}</STYLE></HEAD><BODY><center><img src=\"cid:logotop\"/></center><BR>";
  $footer = "<BR><BR><BR><BR><TABLE WIDTH=100%><TR><TD>www.chilenghish.com Tels (56 2) 665 1676 - 665 0965, Ram&oacute;n Carnicer 81, Of. 607</TD><TD><img src=\"cid:logofooter\"/></TD></TR></TABLE>";  
  //testomail
  $numemail=0;
  foreach ($evt as $key=>$cur) {
    $messaggio = new PHPmailer();
    $messaggio->From='eteam@sec-community.it';
    $messaggio->FromName='GESTIONE TTO';
    
    $messaggio->AddEmbeddedImage("../img/logochilenglish.jpg", "logotop", "../img/logochilenglish.jpg");
    $messaggio->AddEmbeddedImage("../img/logochilenglishfooter.jpg", "logofooter", "../img/logochilenglishfooter.jpg");
    
    $txt="";
    $toadmin=0;
    //messaggi
    if ($cur['fsost']==1) {
      //il presente evento è stato sostituito
      $txt = "MESSAGGIO RICEVUTO DAL PROFESSORE NON DISPONIBILE - RICEVUTA DI CONFERMA DELL'ACCETTAZIONE DELLA SOSTITUZIONE<BR>Sostituzione confermata:<BR>
      Tipo evento:".$tipo[$cur['tipo']]['descr']."<BR>
      Data evento:".date("d/m/Y",$cur['dtini'])."<BR>
      Ora inizio:".date("H:i",$cur['dtini'])."<BR>
      Ora fine:".date("H:i",$cur['dtfine'])."<BR>
      Classe:".$cur['pstudi_descr']."<BR>
      Corso:".$cur['codlivello']."<BR>
      <BR>
      Docente supplente:".$evt[$cur['idsostda']]['docente_nome'];
      $subject = "Sostituzione confermata";
      
      //to docente, admin
      $toadmin=1;
      $noagain=1;
      $messaggio->AddAddress($cur['docente_email']);
    }
    if ($cur['fsost']==0 && $cur['idsostituzione']!="") {
      //sostituzione confermata
      $txt = "MESSAGGIO RICEVUTO DAL DOCENTE - RICEVUTA DI CONFERMA DELL'ACCETTAZIONE DELLA SOSTITUZIONE<BR>Confermata la seguente sostituzione:<BR>
      Tipo evento:".$tipo[$cur['tipo']]['descr']."<BR>
      Data evento:".date("d/m/Y",$cur['dtini'])."<BR>
      Ora inizio:".date("H:i",$cur['dtini'])."<BR>
      Ora fine:".date("H:i",$cur['dtfine'])."<BR>
      Classe:".$cur['pstudi_descr']."<BR>
      Corso:".$cur['codlivello']."<BR>
      <BR>
      Docente da sostituire:".$evt[$cur['idsostituzione']]['docente_nome'];
      $subject = "Ricevuta conferma sostituzione";
      
      //to docente, admin
      $toadmin=1;
      $noagain=1;
      $messaggio->AddAddress($cur['docente_email']);
    }
    
    if ($cur['fsost']==-1) {
      //evt in attesa di essere sostituito
      $txt = "MESSAGGIO RICEVUTO DALL'ADMIN CHE SEGNALA LA PRESENZA DI EVENTI DI CUI DEVE ESSERE PROGRAMMATA LA SOSTITUZIONE<BR>Non e' stata programmata nessuna sostituzione per il seguente evento:<BR>
      Tipo evento:".convlang($tipo[$cur['tipo']]['descr'])."<BR>
      Data evento:".date("d/m/Y",$cur['dtini'])."<BR>
      Ora inizio:".date("H:i",$cur['dtini'])."<BR>
      Ora fine:".date("H:i",$cur['dtfine'])."<BR>
      Classe:".$cur['pstudi_descr']."<BR>
      Corso:".$cur['codlivello'];
      $subject = "Evento in attesa di sostituzione";
      
      //admin
      $toadmin=1;
    }
    
    if ($cur['fsost']==-2) {
      //evt in attesa di conferma
      $txt = "MESSAGGIO RICEVUTO DAL PROFESSORE NON DISPONIBILE CHE SEGNALA CHE LA SOSTITUZIONE E' STATA PROGRAMMATA MA ANCORA NON CONFERMATA<BR>Sostituzione programmata, in attesa di ricevere conferma.<BR>Evento sostituito:<BR>
      Tipo evento:".convlang($tipo[$cur['tipo']]['descr'])."<BR>
      Data evento:".date("d/m/Y",$cur['dtini'])."<BR>
      Ora inizio:".date("H:i",$cur['dtini'])."<BR>
      Ora fine:".date("H:i",$cur['dtfine'])."<BR>
      Classe:".$cur['pstudi_descr']."<BR>
      Corso:".$cur['codlivello']."<BR>
      <BR>
      Docente supplente:".$evt[$cur['idsostda']]['docente_nome'];
      $subject = "Sostituzione programmata, attesa per conferma";
      
      //to docente, admin
      $toadmin=1;
      $messaggio->AddAddress($cur['docente_email']);
    }
    if ($cur['fsost']==-3) {
      //evt in attesa di conferma
      $txt = "MESSAGGIO RICEVUTO DAL DOCENTE CHE DOVRA' SOSTITUIRE IL PROFESSORE NON DISPONIBILE<BR>Confermare la disponibilita' ad effettuare la seguente sostituzione:<BR>
      Tipo evento:".convlang($tipo[$cur['tipo']]['descr'])."<BR>
      Data evento:".date("d/m/Y",$cur['dtini'])."<BR>
      Ora inizio:".date("H:i",$cur['dtini'])."<BR>
      Ora fine:".date("H:i",$cur['dtfine'])."<BR>
      Classe:".$cur['pstudi_descr']."<BR>
      Corso:".$cur['codlivello']."<BR>
      <BR>
      Docente da sostituire:".$evt[$cur['idsostituzione']]['docente_nome']."<BR>
      <BR>
      <A HREF='".$urlbase."/mask/confsost.php?selobj=$key&ids=".$cur['idsostituzione']."'>Click here to confirm</A>";
      $subject = "Sostituzione in attesa di conferma";
      
      //to docente, admin
      $toadmin=1;
      $messaggio->AddAddress($cur['docente_email']);
    }
    if ($cur['fsost']==4) {

      //evt in attesa di conferma
      $txt = "Cancellazione lezione con recupero.<BR>
      Tipo evento:".convlang($tipo[$cur['tipo']]['descr'])."<BR>
      Data evento:".date("d/m/Y",$cur['dtini'])."<BR>
      Ora inizio:".date("H:i",$cur['dtini'])."<BR>
      Ora fine:".date("H:i",$cur['dtfine'])."<BR>
      Classe:".$cur['pstudi_descr']."<BR>
      Corso:".$cur['codlivello'];
      $subject = "Cancellazione lezione con recupero delle ore";
      
      //to docente, admin
      $toadmin=1;
      $noagain==1;
      $updquery[] = "UPDATE appuntamento SET fsost=0 WHERE id=".$key;
      $messaggio->AddAddress($cur['docente_email']);
    }
    if ($cur['fsost']==5) {//elimina con notifica

      //evt in attesa di conferma
      $txt = "Cancellazione lezione senza recupero.<BR>
      Tipo evento:".convlang($tipo[$cur['tipo']]['descr'])."<BR>
      Data evento:".date("d/m/Y",$cur['dtini'])."<BR>
      Ora inizio:".date("H:i",$cur['dtini'])."<BR>
      Ora fine:".date("H:i",$cur['dtfine'])."<BR>
      Classe:".$cur['pstudi_descr']."<BR>
      Corso:".$cur['codlivello']."<BR>
      Docente:".$cur['docente_nome'];
      $subject = "Cancellazione lezione senza recupero";
      
      //to docente, admin
      $toadmin=1;
      $noagain==1;
      $updquery[] = "UPDATE appuntamento SET fsost=0 WHERE id=".$key;
      $messaggio->AddAddress($cur['docente_email']);
    }
    if ($cur['fsost']==-4) {

      //evt in attesa di conferma
      $txt = "Lezione di recupero pianificata.<BR>
      Tipo evento:".convlang($tipo[$cur['tipo']]['descr'])."<BR>
      Data evento:".date("d/m/Y",$cur['dtini'])."<BR>
      Ora inizio:".date("H:i",$cur['dtini'])."<BR>
      Ora fine:".date("H:i",$cur['dtfine'])."<BR>
      Classe:".$cur['pstudi_descr']."<BR>
      Corso:".$cur['codlivello'];
      $subject = "Lezione di recupero programmata";
      
      //to docente, admin
      $toadmin=1;
      $noagain==1;
      $updquery[] = "UPDATE appuntamento SET fsost=0 WHERE id=".$key;
      $messaggio->AddAddress($cur['docente_email']);
    }
    
    if ($toadmin==1) {
      foreach ($admaddr as $keyaddr=>$curaddr) {
        $messaggio->AddAddress($curaddr['addr']);
      }
    }
    foreach ($studenti[$cur['idpstudi']] as $keystu =>$curstu) {
      if ($curstu['studente_email1']!="") {
        $messaggio->AddAddress($curstu['studente_email1']);
      }
      if ($curstu['studente_email2']!="") {
        $messaggio->AddAddress($curstu['studente_email2']);
      }
    }
    
    foreach ($customer[$cur['idpstudi']] as $keystu =>$curstu) {
      if ($curstu['customer_email']!="") {
        $messaggio->AddAddress($curstu['customer_email']);
      }
    }
    
    $messaggio->MsgHTML($header.$txt.$footer);
    $messaggio->Subject=$subject;
    
    /*
    ### RIMOZIONE PER SICUREZZA - NON INVIA MAIL
    MICHELE
    
    if(!$messaggio->Send()){ 
      //echo $messaggio->ErrorInfo; 
    }else{ 
      //echo "- Email inviata correttamente!<BR>";
    }
    
    */
    
    /*$numemail++;
    $msgemail[$numemail]['to'] = "?";
    $msgemail[$numemail]['txt'] = $txt;*/
    unset ($messaggio);
    if ($noagain==1) {
      $updquery[] = "UPDATE appuntamento SET fnotify=0 WHERE id=".$key;
    } else {
      $updquery[] = "UPDATE appuntamento SET fnotify=2 WHERE id=".$key;
    }
    
  }
  //myprint_r($updquery);
  foreach ($updquery as $keyquery => $curquery) {
    mysql_query($curquery) or die ("Error3.1");
  }
?>
<HTML>
<head>
  <title>...</title>
  <meta http-equiv="refresh" content="1;url=<?=$backurl?>">
</head>
<body></body>
</HTML>