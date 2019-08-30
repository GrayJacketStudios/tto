<?php
	$pagever = "1.0";
  $pagemod = "23/06/2010 16.51.48";
  define("APP_DEBUG",0);
  
  
  require_once ("form_cfg.php");

  $valori = array();
  $query = array();

  //if (!isset($_POST['cmd'])) {$cmd=$_GET['cmd'];} else {$cmd = $_POST['cmd'];}
  //if (!isset($_POST['codicepagina'])) {$codicepagina = $_GET['codicepagina'];} else {$codicepagina = $_POST['codicepagina'];}
  //if (!isset($_POST['selobj'])) {$valori['selobj'] = $_GET['selobj'];} else {$valori['selobj'] = $_POST['selobj'];}
	
	$cmd = $_REQUEST['cmd'];
	$codicepagina = $_REQUEST['codicepagina'];
	$valori['selobj'] = $_REQUEST['selobj'];
	$noback = $_REQUEST['noback'];
	$takeid=-1;
	
	switch ($codicepagina) {
    case "TIMETAB":
        $valori['idpstudi'] = $_REQUEST['idpstudi'];
        $valori['iddocente'] = $_REQUEST['idprof'];
        $valori['dt']=$_REQUEST['dt'];
        $valori['oraini']= $_REQUEST['oraini'];
        $valori['durata'] = $_REQUEST['dlezione'];
        
        $valori['compenso']=$_REQUEST['compenso'];
        if ($valori['compenso']=="") {$valori['compenso']=0;}
        
        $valori['note'] = $_REQUEST['note'];
        $valori['tipo']=$_REQUEST['idtipo'];
        $valori['idsostituzione']=$_REQUEST['idsostituzione'];
        $valori['newdt'] = $_REQUEST['newdt'];
        
        $valori['evtdbne'] = $_REQUEST['evtdbne'];
        for ($i=1;$i<=$valori['evtdbne'];$i++) {
          $valori['evtdbid'.$i] = $_REQUEST['evtdbid'.$i];
          $valori['dbact'.$i] = $_REQUEST['dbact'.$i];
        }
        $valori['insandcanc'] = $_REQUEST['insandcanc'];
        $valori['idtocanc'] = $_REQUEST['idtocanc'];
        
        //if ($_SESSION['mgdebug']==1) {myprint_r($valori);die("qui");}
        
        break;
    case "DOCENTI":
        $valori['nome'] = $_REQUEST['nome'];
        $valori['nickname'] = $_REQUEST['nickname'];
        $valori['tel'] = $_REQUEST['tel'];
        $valori['mobile'] = $_REQUEST['mobile'];
        $valori['email'] = $_REQUEST['email'];
        $valori['iduser'] = $_REQUEST['iduser'];
        $valori['banca'] = $_REQUEST['banca'];
        $valori['conto'] = $_REQUEST['conto'];
        $valori['rut'] = $_REQUEST['rut'];
        $valori['tipoconto'] = $_REQUEST['tipoconto'];
        $valori['contatto'] = $_REQUEST['contatto'];
        $valori['note'] = $_REQUEST['note'];  
        $valori['nomefile']=$_REQUEST['nomefile'];    
        $valori['idd'] = $_REQUEST['idd'];
        
        $valori['idbanca'] = $_REQUEST['idbanca'];
        $valori['ccbanca_codice'] = $_REQUEST['ccbanca_codice'];
        $valori['ccbanca_id'] = $_REQUEST['ccbanca_id'];
        $valori['ccbanca_tipocc'] = $_REQUEST['ccbanca_tipocc'];
        
                                            
        break;
    case "CLIENTI":
        $valori['id'] = $_REQUEST['id'];
        $valori['nome'] = $_REQUEST['nome'];
        $valori['apellido'] = $_REQUEST['apellido'];
        $valori['posizione'] = $_REQUEST['posizione'];
        $valori['ragsoc'] = $_REQUEST['ragsoc'];
        $valori['rut_ditta'] = $_REQUEST['rut_ditta'];
        $valori['rut_persona'] = $_REQUEST['rut_persona'];
        $valori['giro'] = $_REQUEST['giro'];
        $valori['legale_nome'] = $_REQUEST['legale_nome'];
        $valori['legale_rut'] = $_REQUEST['legale_rut'];
        $valori['addr_pri'] = $_REQUEST['addr_pri'];
        $valori['addr_work'] = $_REQUEST['addr_work'];
        $valori['tel_pri'] = $_REQUEST['tel_pri'];
        $valori['tel_lavoro'] = $_REQUEST['tel_lavoro'];
        $valori['tel_mobile'] = $_REQUEST['tel_mobile'];
        $valori['mail1'] = $_REQUEST['mail1'];
        $valori['mail2'] = $_REQUEST['mail2'];
        $valori['mail3'] = $_REQUEST['mail3'];
        $valori['fatturaz'] = $_REQUEST['fatturaz'];
        $valori['seg_nome'] = $_REQUEST['seg_nome'];
        $valori['seg_foto'] = $_REQUEST['seg_foto'];
        $valori['seg_mail'] = $_REQUEST['seg_mail'];
        $valori['fantasia'] = $_REQUEST['fantasia'];
        $valori['web'] = $_REQUEST['web'];
        $valori['skype'] = $_REQUEST['skype'];
        $valori['codsence'] = $_REQUEST['codsence'];
        $valori['note'] = $_REQUEST['note'];
        $valori['impresa'] = $_REQUEST['impresa'];
        
        $valori['contatti'] = conv_textarea($_REQUEST['contatti']);
        
        //adattamento campi
        if ($valori['impresa']==1) {
          //impresa
          $valori['fatturaz'] = $_REQUEST['fatturaz_impresa'];
          $valori['ragsoc'] = $_REQUEST['ragsoc_impresa'];
          $valori['nome'] = $_REQUEST['ragsoc_impresa'];
        }
        
        $valori['fatturazrut'] = $_REQUEST['fatturazrut'];
        $valori['fatturazgiro'] = $_REQUEST['fatturazgiro'];
        $valori['fatturazragsoc'] = $_REQUEST['fatturazragsoc'];
        
        break;

    case "FORNITORI":
        $valori['id'] = $_REQUEST['id'];
        $valori['nome'] = $_REQUEST['nome'];
        $valori['apellido'] = $_REQUEST['apellido'];
        $valori['posizione'] = $_REQUEST['posizione'];
        $valori['ragsoc'] = $_REQUEST['ragsoc'];
        $valori['rut_ditta'] = $_REQUEST['rut_ditta'];
        $valori['rut_persona'] = $_REQUEST['rut_persona'];
        $valori['giro'] = $_REQUEST['giro'];
        $valori['legale_nome'] = $_REQUEST['legale_nome'];
        $valori['legale_rut'] = $_REQUEST['legale_rut'];
        $valori['addr_pri'] = $_REQUEST['addr_pri'];
        $valori['addr_work'] = $_REQUEST['addr_work'];
        $valori['tel_pri'] = $_REQUEST['tel_pri'];
        $valori['tel_lavoro'] = $_REQUEST['tel_lavoro'];
        $valori['tel_mobile'] = $_REQUEST['tel_mobile'];
        $valori['mail1'] = $_REQUEST['mail1'];
        $valori['mail2'] = $_REQUEST['mail2'];
        $valori['mail3'] = $_REQUEST['mail3'];
        $valori['fatturaz'] = $_REQUEST['fatturaz'];
        $valori['seg_nome'] = $_REQUEST['seg_nome'];
        $valori['seg_foto'] = $_REQUEST['seg_foto'];
        $valori['seg_mail'] = $_REQUEST['seg_mail'];
        $valori['fantasia'] = $_REQUEST['fantasia'];
        $valori['web'] = $_REQUEST['web'];
        $valori['skype'] = $_REQUEST['skype'];
        $valori['codsence'] = $_REQUEST['codsence'];
        $valori['note'] = $_REQUEST['note'];
        $valori['impresa'] = $_REQUEST['impresa'];
        
        $valori['contatti'] = conv_textarea($_REQUEST['contatti']);
        $valori['idcategoria'] = $_REQUEST['idcategoria'];
        $valori['categ2'] = $_REQUEST['categ2'];
        
        //adattamento campi
        if ($valori['impresa']==1) {
          //impresa
          $valori['fatturaz'] = $_REQUEST['fatturaz_impresa'];
          $valori['ragsoc'] = $_REQUEST['ragsoc_impresa'];
          $valori['nome'] = $_REQUEST['ragsoc_impresa'];
        }
        
        $valori['fatturazrut'] = $_REQUEST['fatturazrut'];
        $valori['fatturazgiro'] = $_REQUEST['fatturazgiro'];
        $valori['fatturazragsoc'] = $_REQUEST['fatturazragsoc'];
        
        
        $valori['idbanca'] = $_REQUEST['idbanca'];
        $valori['ccbanca_codice'] = $_REQUEST['ccbanca_codice'];
        $valori['ccbanca_id'] = $_REQUEST['ccbanca_id'];
        $valori['ccbanca_tipocc'] = $_REQUEST['ccbanca_tipocc'];
        
        break;

    case "STUDENTI":
        $valori['nome'] = $_REQUEST['nome'];
        $valori['rut'] = $_REQUEST['rut'];
        $valori['giro'] = $_REQUEST['giro'];
        $valori['indirizzo'] = $_REQUEST['indirizzo'];
        $valori['num'] = $_REQUEST['num'];
        $valori['numappartamento'] = $_REQUEST['numappartamento'];
        $valori['comune'] = $_REQUEST['comune'];
        $valori['tel'] = conv_textarea($_REQUEST['tel']);
        $valori['mobile'] = $_REQUEST['mobile'];
        $valori['fax'] = $_REQUEST['fax'];
        $valori['email'] = $_REQUEST['email'];
        $valori['email2'] = $_REQUEST['email2'];
        $valori['idcustomer'] = $_REQUEST['idcustomer'];
        $valori['iduser'] = -1;

        break;
    case "CLASSI":
        $valori['classi_descr'] = $_REQUEST['classi_descr'];
        $valori['classi_idcustomer'] = $_REQUEST['classi_idcustomer'];
        $valori['idc'] = $_REQUEST['idc'];
        $valori['dtf'] = $_REQUEST['dtf'];
        
        $vet_enrollstudenti = $_REQUEST['enrollstudenti'];
        break;
    case "PSTUDIO":
        $valori['idstudente'] = $_REQUEST['idstudente'];
        $valori['idpstudi'] = $_REQUEST['idpstudi'];
        $valori['dataf'] = $_REQUEST['dataf'];
        $valori['datai'] = $_REQUEST['datai'];
        $valori['idcorso'] = $_REQUEST['idcorso'];
        $valori['idclasse'] = $_REQUEST['idclasse'];
        $valori['descr'] = $_REQUEST['descr'];
        $valori['datai'] = $_REQUEST['datai'];
        $valori['dataf'] = $_REQUEST['dataf'];
        
        $valori['compenso'] = $_REQUEST['compenso'];
        if ($valori['compenso']=="") {$valori['compenso']=0;}
        
        $valori['trasporto'] = $_REQUEST['trasporto'];
        if ($valori['trasporto']=="") {$valori['trasporto']=0;}
        
        $valori['durata'] = $_REQUEST['durata'];
        $valori['dlezione'] = $_REQUEST['dlezione'];
        $valori['codsense'] = $_REQUEST['codsense'];
        $valori['codotec'] = $_REQUEST['codotec'];
        $valori['attivita'] = conv_textarea($_REQUEST['attivita']);
        $valori['luogo'] = conv_textarea($_REQUEST['luogo']);

        $valori['style'] = $_REQUEST['styleValue'];
        if (count($_REQUEST['iddocente'])>0) {
          foreach ($_REQUEST['iddocente'] as $keyprof =>$curprof) {
            $vet_docenti[$curprof]=1;
          }
        }    
        break;
    case "PSTUDIO_EVT":
        $valori['datai'] = $_REQUEST['dataini'];
        $valori['dataf'] = $_REQUEST['datafine'];
        $valori['evtne'] = $_REQUEST['evtne'];
        for ($i=1;$i<=$valori['evtne'];$i++) {
          $valori['evtdataini'.$i] = $_REQUEST['evtdataini_'.$i];
          $valori['evtdatafine'.$i] = $_REQUEST['evtdatafine_'.$i];
          $valori['evtiddocente'.$i] = $_REQUEST['evtiddocente_'.$i];
        }
        $valori['compenso'] = $_REQUEST['compenso'];
        $valori['idpstudi'] = $_REQUEST['idpstudi'];
        
        $valori['evtdbne'] = $_REQUEST['evtdbne'];
        for ($i=1;$i<=$valori['evtdbne'];$i++) {
          $valori['evtdbid'.$i] = $_REQUEST['evtdbid'.$i];
          $valori['dbact'.$i] = $_REQUEST['dbact'.$i];
        }
        $valori['prefne'] = $_REQUEST['prefne'];
        $valori['lesdone'] = $_REQUEST['lesdone'];
        $valori['les_delid'] = $_REQUEST['les_delid'];
        for ($i=1;$i<=$valori['prefne'];$i++) {
          $valori['wkday_'.$i] = $_REQUEST['wkday_'.$i];
          $valori['orai_'.$i] = $_REQUEST['orai_'.$i];
          $valori['oraf_'.$i] = $_REQUEST['oraf_'.$i];
          $valori['iddocente_'.$i] = $_REQUEST['iddocente_'.$i];
        }
        break;
    case "USER":
        $valori['username']=$_REQUEST['username'];
        $valori['password']=$_REQUEST['password'];
        $valori['user_nome']=$_REQUEST['user_nome'];
        $valori['dtcreazione']=$_REQUEST['dtcreazione'];
        $valori['permessi']=$_REQUEST['permessi'];
        $valori['user_email']=$_REQUEST['user_email'];
        break;
    case "CONTATTICUST":
        $valori['idcustomer'] = $_REQUEST['idcustomer'];
        $valori['nome'] = $_REQUEST['nome'];
        $valori['posizione'] = $_REQUEST['posizione'];
        $valori['tel_lavoro'] = $_REQUEST['tel_lavoro'];
        $valori['tel_privato'] = $_REQUEST['tel_privato'];
        $valori['mobile'] = $_REQUEST['mobile'];
        $valori['fax'] = $_REQUEST['fax'];
        $valori['skype'] = $_REQUEST['skype'];
        $valori['mail1'] = $_REQUEST['mail1'];
        $valori['note'] = $_REQUEST['note'];
        break;
    case "CONTATTIFORN":
        $valori['idfornitore'] = $_REQUEST['idfornitore'];
        $valori['nome'] = $_REQUEST['nome'];
        $valori['posizione'] = $_REQUEST['posizione'];
        $valori['tel_lavoro'] = $_REQUEST['tel_lavoro'];
        $valori['tel_privato'] = $_REQUEST['tel_privato'];
        $valori['mobile'] = $_REQUEST['mobile'];
        $valori['fax'] = $_REQUEST['fax'];
        $valori['skype'] = $_REQUEST['skype'];
        $valori['mail1'] = $_REQUEST['mail1'];
        $valori['note'] = $_REQUEST['note'];
        break;
    case "CLIENTISTATO":
        $valori['stato'] = $_REQUEST['stato'];
        $app = explode("#",$valori['stato']);
        $valori['stato'] = $app[0];
        $valori['statotxt'] = $app[1]; 
        
        $valori['stato2'] = $_REQUEST['stato2'];
        $app = explode("#",$valori['stato2']);
        $valori['stato2'] = $app[0];
        $valori['stato2txt'] = $app[1];         
        
        if ($valori['stato']!=POTENZIALE) {
          $valori['stato2']=-1;
          $valori['stato2txt']="";          
        }
        
        $valori['msg'] = conv_textarea($_REQUEST['msg']);
        $valori['mezzo'] = $_REQUEST['mezzo'];
        $valori['idc'] = $_REQUEST['idc'];
        break;
    case "DOCENTISTATO":
        $valori['stato'] = $_REQUEST['stato'];
        $app = explode("#",$valori['stato']);
        $valori['stato'] = $app[0];
        $valori['statotxt'] = $app[1]; 
                
        $valori['msg'] = conv_textarea($_REQUEST['msg']);
        $valori['mezzo'] = $_REQUEST['mezzo'];
        break;
    case "DOCENTIFILE":
        $valori['descr'] = $_REQUEST['descr'];
        $valori['idd']=$_REQUEST['idd'];
        break;
    case "DOCENTILINGUA":
       // myprint_r($_REQUEST);
        
        //insegna
        foreach ($_REQUEST['linguainsegna'] as $cur) {
          $valori['insegna_ne']++;
          
          $valori['insegna_'.$valori['insegna_ne']] = $cur;
          $valori['insegna_'.$valori['insegna_ne'].'_data'] = $_REQUEST['data_'.$cur];
        }
        
        
        
        //---------------------------------------------------
        
        $valori['tra_ne'] = $_REQUEST['canc_tra_ne'];
        
        $j=0;
        for ($i=1;$i<=$valori['tra_ne'];$i++) {
          if ($_REQUEST['canc_tra_'.$i]==1) {
            $j++;
            $valori['canc_tra_'.$j.'_da'] = $_REQUEST['canc_tra_'.$i.'_da'];
            $valori['canc_tra_'.$j.'_a'] = $_REQUEST['canc_tra_'.$i.'_a'];
          }
        }
        $valori['tra_ne'] = $j;
        
        if ($_REQUEST['new_traduce_da']!="") {
          $valori['new_tra_da']=$_REQUEST['new_traduce_da'];
          $valori['new_tra_a']=$_REQUEST['new_traduce_a'];
          $valori['new_tra']=1; 
        }
        
        //-----------------------------------------
        
        $valori['int_ne'] = $_REQUEST['canc_int_ne'];
        
        $j=0;
        for ($i=1;$i<=$valori['int_ne'];$i++) {
          if ($_REQUEST['canc_int_'.$i]==1) {
            $j++;
            $valori['canc_int_'.$j.'_da'] = $_REQUEST['canc_int_'.$i.'_da'];
            $valori['canc_int_'.$j.'_a'] = $_REQUEST['canc_int_'.$i.'_a'];
          }
        }
        $valori['int_ne'] = $j;
        
        if ($_REQUEST['new_interpreta_da']!="") {
          $valori['new_int_da']=$_REQUEST['new_interpreta_da'];
          $valori['new_int_a']=$_REQUEST['new_interpreta_a'];
          $valori['new_int']=1; 
        }
        
        //----------------------------------------------
        //myprint_r($valori);
        
        break;
    case "CONTABILITA":
        //myprint_r($_REQUEST);
        
        $valori['descr']=$_REQUEST['descr'];
        $valori['importo']=$_REQUEST['importo'];
        $valori['idtipo']=$_REQUEST['idtipo'];
        
        $valori['idext']=$_REQUEST['idext'];
        if ($valori['idext']=="") {
          $valori['idext']="NULL";
        }
        
        
        $valori['tipocliente']=$_REQUEST['tipocliente'];
        $valori['idcliente']=$_REQUEST['idcliente'];
        $valori['dtcreazione']=$_REQUEST['dtcreazione'];
        $valori['dtpagoprev']=$_REQUEST['dtpagoprev'];
        $valori['dtpagoprev_times'] = eT_dt2times($valori['dtpagoprev']);
        
        
        
        
        $valori['doc']=$_REQUEST['doc'];
        $valori['tipopago']=$_REQUEST['tipopago'];
        $valori['refpago']=$_REQUEST['refpago'];
        $valori['idcc']=$_REQUEST['idcc'];
        $valori['idcdcosto']=$_REQUEST['idcdcosto'];
        
        $valori['fncredito']=0;
        $valori['ncidannullo']=0;
        
        $valori['note']=$_REQUEST['note'];
        
        $valori['dtpagoreale'] = $_REQUEST['dtpagoreale'];
        
        //promemoria
        $valori['fpromemoria']=$_REQUEST['fpromemoria'];
        
        if ($valori['fpromemoria']==1) { 
          $valori['dtpromemoria']=$_REQUEST['dtpromemoria'];
        } else {
          $valori['dtpromemoria']=0;
        }
        
        //quote
        $valori['nquote']=$_REQUEST['nquote'];
        if ($valori['nquote']=="") {$valori['nquote']=1;}
        
        if ($valori['nquote']>1) {
          //ci sono quote
          for ($iquota=1;$iquota<=$valori['nquote'];$iquota++) {
            $valori['qmt_'.$iquota] = $_REQUEST['qmt_'.$iquota];
            $valori['qdt_'.$iquota]= $_REQUEST['qdt_'.$iquota];
            
          }  
        } else {
          //eccezione per 1 sola quota
          $valori['qmt_1'] = $valori['importo'];
          $valori['qdt_1'] = $valori['dtpagoprev'];  
        }
        
        $valori['importosinquota']=$valori['importo'];
        $valori['modquote'] = $_REQUEST['modquote'];
        
        $valori['quotaroot'] = $_REQUEST['quotaroot'];
        $valori['quotaids'] = $_REQUEST['quotaids'];
        $valori['quotatotimporto'] = $_REQUEST['quotatotimporto'];
        
        $valori['updquotadescr'] = $_REQUEST['updquotadescr'];
        $valori['updquotatotale'] =$_REQUEST['updquotatotale'];

        break;
    case "BANCA":
        $appne = $_REQUEST['ne'];
        //$valori['banca_delete'] = "-1, ";
        for ($i=1;$i<=$appne;$i++) {
          if ($_REQUEST['mod_'.$i]==1) {
            $valori['banca_ne']++;
            $valori['id_'.$valori['banca_ne']] = $_REQUEST['id_'.$i];
            $valori['nomebanca_'.$valori['banca_ne']] = $_REQUEST['nomebanca_'.$i];
          }
          if ($_REQUEST['banca_delete_'.$i]=="1") {
          $valori['banca_delete'] .= $_REQUEST['id_'.$i].", ";
        }
        }
        if ($_REQUEST['banca_new']!="") {
          $valori['banca_new'] = $_REQUEST['banca_new'];
        }
        break;
    
    case "CCBANCA":
        //myprint_r($_REQUEST);
        $valori['idbanca']=$_REQUEST['idbanca'];
        $valori['tipoconto'] = $_REQUEST['tipoconto'];
        $valori['ccbanca_codice'] = $_REQUEST['ccbanca_codice'];
        
        $valori['tiposoggetto']=$_REQUEST['ts'];
        $valori['idsoggetto']=$_REQUEST['ids'];
        break;
        
    case "SALDO":
        $valori['dt'] = $_REQUEST['dt'];
        $valori['importo'] = $_REQUEST['importo'];
        $valori['note']=$_REQUEST['note'];
        break;
    case "LINGUA":
        $PATHFLAG = "../flag/";
        $valori['ne'] = $_REQUEST['ne'];
        
        for ($i=1;$i<=$valori['ne'];$i++) {
          $valori['id_'.$i] = $_REQUEST['id_'.$i];
          $valori['descr_'.$i] = $_REQUEST['descr_'.$i];
          if ($_FILES['imgfile_'.$i]['error']==0) {
            unlink($PATHFLAG.$_REQUEST['actimg_'.$i]);
            $tmpname = $_FILES['imgfile_'.$i]['tmp_name'];
            $valori['img1_'.$i] = $_FILES['imgfile_'.$i]['name'];
            move_uploaded_file($tmpname, $PATHFLAG.$valori['img1_'.$i]);
          } else {
            $valori['img1_'.$i]="";
          }
        }
        
        if ($_REQUEST['descr_new']!="") {
          $valori['lingua_new']=1;
          
          $valori['descr_new'] = $_REQUEST['descr_new'];
          $valori['codint_new'] = $_REQUEST['codint_new'];
          if ($_FILES['imgfile_new']['error']==0) {
            $tmpname = $_FILES['imgfile_new']['tmp_name'];
            $valori['img1_new'] = $_FILES['imgfile_new']['name'];
            move_uploaded_file($tmpname, $PATHFLAG.$valori['img1_new']);
          } else {
            $valori['img1_new']="";
          }          
        }

        break;
    case "REMINDER":
        $valori['nuovasdt']=$_REQUEST['nuovasdt'];
        $valori['edit_oggetto']=$_REQUEST['edit_oggetto'];
        $valori['edit_link']=$_REQUEST['edit_link'];
        $valori['reminder_mail']=$_REQUEST['reminder_mail'];
        $valori['dtdacal']=$_REQUEST['dtdacal'];
        $valori['notifymail']=$_REQUEST['notifymail'];
        $valori['reminder_descr']=$_REQUEST['reminder_descr'];
        
        if ($valori['dtdacal']==1) {
          $valori['show_dtnuova']=$_REQUEST['show_dtnuova'];
          $valori['nuovasdt']=eT_hdt2sdt($valori['show_dtnuova']);
        }
        break;
  }
	
  //myprint_r($valori);
  $valori = clean_par($valori);
  //myprint_r($valori);
  
  //echo $selquery;
  $selquery = $codicepagina.$cmd;
  $querynum=0;
  $adesso = time();
  
  unset($query);
	$query = array();
  switch ($selquery) {

      //HOST  frm_server.php
      case "TIMETAB". FASEINS:
                   $valori['fsost']=0;
                   switch ($valori['insandcanc']) {
                      case 1:
                              //con recupero
                              $query[$querynum++] = "UPDATE appuntamento SET fsost=4, fdel=1, frecupera=1, fnotify=1 WHERE id=".$valori['idtocanc'];
                              $valori['fsost']=-4;
                              break;
                      case 2://senza recupero
                              $query[$querynum++] = "UPDATE appuntamento SET fsost=4, fdel=1, frecupera=0, fnotify=1 WHERE id=".$valori['idtocanc'];
                              $valori['fsost']=-4;
                              break;                      
                   }
                   
                   //calcola datainizio e datafine
                   $dtini = eT_dt2times($valori['dt']) + eT_ora2times($valori['oraini'],0);
                   $dtfine = $dtini + ($valori['durata']) * 30 *60;  //19/11 c'era un +1 nella durata
                   
                   //print "datai:".date("d/m/Y H:i:s",$dtini)."<BR>dataf:".date("d/m/Y H:i:s",$dtfine)."<BR>";
                   
                   $query[$querynum++] = "INSERT INTO appuntamento (idpstudi, iddocente, dtini, dtfine, compenso, tipo, note, idsostituzione, fsost, trashed) VALUES (".$valori['idpstudi'].", ".$valori['iddocente'].", ".$dtini.", ".$dtfine.", ".$valori['compenso'].", ".$valori['tipo'].", '".$valori['note']."', NULL, ".$valori['fsost'].", 0);";
                  
                    //conflitto
                    for ($i=1;$i<=$valori['evtdbne'];$i++) {
                      switch ($valori['dbact'.$i]) {
                        case 3://sostituzione
                          $query[$querynum++] = "UPDATE appuntamento SET fsost=-1, fnotify=1 WHERE id=".$valori['evtdbid'.$i];
                          break;
                        case 4://elimina senza recupero
                          $query[$querynum++] = "UPDATE appuntamento SET trashed=1, frecupera=0 WHERE id=".$valori['evtdbid'.$i];
                          break;
                        case 5:
                          $query[$querynum++] = "UPDATE appuntamento SET trashed=1, frecupera=1 WHERE id=".$valori['evtdbid'.$i];
                          break;
                      }
                    }
                  
                  //myprint_r($query);
                  //die("qui");
                   $valori['newdt'] = date("d/m/Y",$dtini);
                   /*if ($_SESSION['mgdebug']==1) {
                      print $valori['newdt'];
                   }*/ 
                   $backurl = urlencode("timetable.php?idprof=".$valori['iddocente']."&newdt=".$valori['newdt']);
                   //$backpage = "timetable.php?idprof=".$valori['iddocente']."&newdt=".$valori['newdt'];
                   $backpage = "inviamail.php?tipoact=1&backurl=".$backurl;
                   //if ($_SESSION['mgdebug']==1) {myprint_r($valori);die("qu");}
                   $diretto=1;
                   break;
                   
      case "TIMETAB". FASEMOD:
                   $dtini = eT_dt2times($valori['dt']) + eT_ora2times($valori['oraini'],0);
                   $dtfine = $dtini + ($valori['durata']+1) * 30 *60;
                   //print "datai:".date("d/m/Y H:i:s",$dtini)."<BR>dataf:".date("d/m/Y H:i:s",$dtfine)."<BR>";
                   
                   $query[$querynum++] = "UPDATE appuntamento SET idpstudi=".$valori['idpstudi'].", iddocente=".$valori['iddocente'].", dtini=".$dtini.", dtfine=".$dtfine.", compenso=".$valori['compenso'].", tipo=".$valori['tipo'].", note='".$valori['note']."' WHERE id=".$valori['selobj'];
                   
                   //conflitto
                    for ($i=1;$i<=$valori['evtdbne'];$i++) {
                      switch ($valori['dbact'.$i]) {
                        case 3://sostituzione
                          $query[$querynum++] = "UPDATE appuntamento SET fsost=-1, fnotify=1 WHERE id=".$valori['evtdbid'.$i];
                          break;
                        case 4://elimina senza recupero
                          $query[$querynum++] = "UPDATE appuntamento SET trashed=1, frecupera=0 WHERE id=".$valori['evtdbid'.$i];
                          break;
                        case 5:
                          $query[$querynum++] = "UPDATE appuntamento SET trashed=1, frecupera=1 WHERE id=".$valori['evtdbid'.$i];
                          break;
                      }
                    }
                    //myprint_r($query);
                    //die("qui");
                   //$backpage = "timetable.php?idprof=".$valori['iddocente'];
                   $backurl = urlencode("timetable.php?idprof=".$valori['iddocente']);
                   $backpage = "inviamail.php?backurl=".$backurl."&tipoact=1";
                   $diretto=1;
                   break;
      case "TIMETAB". FASEDEL:
      						 $query[$querynum++] = "UPDATE appuntamento SET trashed=1 WHERE id=".$valori['selobj'];
                   
      						 $backpage = "timetable.php?idprof=".$valori['iddocente'];
                   $diretto=1;
                   //myprint_r($query);
                   //die("q");
      						 break;
      case "TIMETAB". FASEDELSAMEDAY:
      						 $query[$querynum++] = "UPDATE appuntamento SET fdel=1, fsost=5, fnotify=1 WHERE id=".$valori['selobj'];
                   
      						 $backurl = urlencode("timetable.php?idprof=".$valori['iddocente']);
                   $backpage = "inviamail.php?backurl=".$backurl."&tipoact=1";
                   $diretto=1;
                   //myprint_r($query);
                   //die("q");
      						 break;      						 
      case "TIMETAB".FASESUPPLENZA:
                   //myprint_r($valori);
                   //calcola datainizio e datafine
                   $dtini = eT_dt2times($valori['dt']) + eT_ora2times($valori['oraini'],0);
                   $dtfine = $dtini + ($valori['durata']+1) * 30 *60;
                   
                   //print "datai:".date("d/m/Y H:i:s",$dtini)."<BR>dataf:".date("d/m/Y H:i:s",$dtfine)."<BR>";
                   
                   $query[$querynum++] = "INSERT INTO appuntamento (idpstudi, iddocente, dtini, dtfine, compenso, tipo, note, idsostituzione, fsost, fnotify, trashed) VALUES (".$valori['idpstudi'].", ".$valori['iddocente'].", ".$dtini.", ".$dtfine.", ".$valori['compenso'].", ".$valori['tipo'].", '".$valori['note']."', ".$valori['selobj'].", -3, 1, 0);";
                   $query[$querynum++]  ="UPDATE appuntamento SET fsost=-2, fnotify=1 WHERE id=".$valori['selobj'];
                  
                    //conflitto
                    for ($i=1;$i<=$valori['evtdbne'];$i++) {
                      switch ($valori['dbact'.$i]) {
                        case 3://sostituzione
                          $query[$querynum++] = "UPDATE appuntamento SET fsost=-1, fnotify=1 WHERE id=".$valori['evtdbid'.$i];
                          break;
                        case 4://elimina senza recupero
                          $query[$querynum++] = "UPDATE appuntamento SET trashed=1, frecupera=0 WHERE id=".$valori['evtdbid'.$i];
                          break;
                        case 5:
                          $query[$querynum++] = "UPDATE appuntamento SET trashed=1, frecupera=1 WHERE id=".$valori['evtdbid'.$i];
                          break;
                      }
                    }
                  
                   //myprint_r($query);
                   //die("qui");
                   //$backpage = "timetable.php?idprof=".$valori['iddocente']."&newdt=".$valori['newdt'];
                   $backurl = urlencode("timetable.php?idprof=".$valori['iddocente']."&newdt=".$valori['newdt']);
                   $backpage = "inviamail.php?backurl=".$backurl."&tipoact=1";
                   $diretto=1;
                   //die("qui");
                   break;
      case "TIMETAB".FASECONFSUPPLENZA:
                    $query[$querynum++] = "UPDATE appuntamento SET fsost=0, fnotify=1 WHERE id=".$valori['selobj'];
                    $query[$querynum++] = "UPDATE appuntamento SET fsost=1, fnotify=1 WHERE id=".$valori['idsostituzione'];
                    //myprint_r($query);
                    //die("qui");
                    //$backpage = "timetable.php?idprof=".$valori['iddocente']."&newdt=".$valori['newdt'];
                    $backurl = urlencode("timetable.php?idprof=".$valori['iddocente']."&newdt=".$valori['newdt']);
                    $backpage = "inviamail.php?backurl=".$backurl."&tipoact=1";
                    $diretto=1;
                    //die("qui");
                    break;
      case "DOCENTI".FASEINS:
                    $query[$querynum++] = "INSERT INTO docente (iduser, nome, nickname, tel, mobile, email, banca, conto, rut, tipoconto, contatto, note, attivo, trashed) VALUES (".$valori['iduser'].", '".$valori['nome']."', '".$valori['nickname']."', '".$valori['tel']."', '".$valori['mobile']."', '".$valori['email']."','".$valori['banca']."', '".$valori['conto']."', '".$valori['rut']."', '".$valori['tipoconto']."', '".$valori['contatto']."', '".$valori['note']."', 2, 0)";
                    
                    //TEMP BANCA
                    if ($valori['ccbanca_id']=="") {
                      //nuovo allora ins
                      $query[$querynum] = "INSERT INTO ccbanca(idbanca, tiposoggetto, idsoggetto, codice, note, tipocc) VALUES (".$valori['idbanca'].", 3, ###INSID_".($querynum-1)."###, '".$valori['ccbanca_codice']."', '', ".$valori['ccbanca_tipocc'].")";
                      $querynum++;
                    } else {
                      $query[$querynum++] = "UPDATE ccbanca SET idbanca=".$valori['idbanca'].", codice='".$valori['ccbanca_codice']."', tipocc=".$valori['ccbanca_tipocc']." WHERE id=".$valori['ccbanca_id'];
                    }

                    $backpage = "frm_docenti.php?cmd=".FASESEARCH."&filtra_stato=2";
                    $diretto=1;
                    $takeid=0;
                    break;
      case "DOCENTI".FASEMOD:
                    $query[$querynum++] = "UPDATE docente SET iduser=".$valori['iduser'].", nome='".$valori['nome']."', nickname='".$valori['nickname']."', tel='".$valori['tel']."', mobile='".$valori['mobile']."', email='".$valori['email']."', banca='".$valori['banca']."', conto='".$valori['conto']."', rut='".$valori['rut']."', tipoconto='".$valori['tipoconto']."', contatto='".$valori['contatto']."', note='".$valori['note']."'  WHERE id=".$valori['selobj'];
                    
                    //TEMP BANCA
                    if ($valori['ccbanca_id']=="") {
                      //nuovo allora ins
                      $query[$querynum] = "INSERT INTO ccbanca(idbanca, tiposoggetto, idsoggetto, codice, note, tipocc) VALUES (".$valori['idbanca'].", 3, ".$valori['selobj'].", '".$valori['ccbanca_codice']."', '', ".$valori['ccbanca_tipocc'].")";
                      $querynum++;
                    } else {
                      $query[$querynum++] = "UPDATE ccbanca SET idbanca=".$valori['idbanca'].", codice='".$valori['ccbanca_codice']."', tipocc=".$valori['ccbanca_tipocc']." WHERE id=".$valori['ccbanca_id'];
                    }

                    $backpage = "frm_docenti.php?selobj=".$valori['selobj'];
                    $diretto=1;
                    break;
      case "DOCENTI".FASEDEL:
                    $query[$querynum++] = "UPDATE docente SET trashed=1, attivo=0 WHERE id=".$valori['selobj'];
                    $backpage = "frm_docenti.php";
                    $diretto=1;
                    break;
      case "DOCENTI".FASEDENY:
                    $query[$querynum++] = "UPDATE docente SET attivo=0 WHERE id=".$valori['selobj'];
                    $backpage = "frm_docenti.php?selobj=".$valori['selobj'];
                    $diretto=1;
                    break;
      case "DOCENTI".FASEALLOW:
                    $query[$querynum++] = "UPDATE docente SET attivo=1 WHERE id=".$valori['selobj'];
                    $backpage = "frm_docenti.php?selobj=".$valori['selobj'];
                    $diretto=1;
                    break;
                    
                    
      case "DOCENTISTATO".FASESETTASTATO:
                    $msgcambio = "nuevo estado >> ".$valori['statotxt']."\n";

                    $query[$querynum++] = "UPDATE docente SET attivo=".$valori['stato']." WHERE id=".$valori['selobj'];
                    $query[$querynum++] = "INSERT INTO docente_log(iddocente, times, mezzo, msg,user) VALUES (".$valori['selobj'].",".time().",".$valori['mezzo'].",'".$msgcambio.$valori['msg']."',".$_SESSION['user_id'].")";
                    $backpage = "frm_docenti.php?cmd=".FASESEARCH."&filtra_stato=".$valori['stato'];
                    $diretto=1;
                    break;
                    
      case "DOCENTILINGUA".FASESETTALINGUA:
                    
                    $query[$querynum++] = "DELETE FROM docente_insegna WHERE iddocente=".$valori['selobj'];
                    for ($i=1;$i<=$valori['insegna_ne'];$i++) {
                      if ($valori['insegna_'.$i.'_data']=="-2") {
                        $valori['insegna_'.$i.'_data'] = date("Ymd");
                      } 
                      $query[$querynum++] = "INSERT INTO docente_insegna(iddocente, idlingua, data) VALUES (".$valori['selobj'].", ".$valori['insegna_'.$i].", '".$valori['insegna_'.$i.'_data']."')";
                    }
                    
                    for ($i=1;$i<=$valori['tra_ne'];$i++) {
                      $query[$querynum++] = "DELETE FROM docente_traduce WHERE iddocente=".$valori['selobj']." AND idlingua_da=".$valori['canc_tra_'.$i.'_da']." AND idlingua_a=".$valori['canc_tra_'.$i.'_a'];
                    }
                    if ($valori['new_tra']==1) {
                      $query[$querynum++] = "INSERT INTO docente_traduce (iddocente, idlingua_da, idlingua_a, data) VALUES (".$valori['selobj'].", ".$valori['new_tra_da'].", ".$valori['new_tra_a'].", '".date("Ymd")."')";
                    }
                    
                    //interprete
                    for ($i=1;$i<=$valori['int_ne'];$i++) {
                      $query[$querynum++] = "DELETE FROM docente_interpreta WHERE iddocente=".$valori['selobj']." AND idlingua_da=".$valori['canc_int_'.$i.'_da']." AND idlingua_a=".$valori['canc_int_'.$i.'_a'];
                    }
                    if ($valori['new_int']==1) {
                      $query[$querynum++] = "INSERT INTO docente_interpreta (iddocente, idlingua_da, idlingua_a, data) VALUES (".$valori['selobj'].", ".$valori['new_int_da'].", ".$valori['new_int_a'].", '".date("Ymd")."')";
                    }
                    $backpage = "frm_docenti.php?selobj=".$valori['selobj'];
                    $diretto=1;
                    break;              
                    
      case "CLIENTI".FASEINS:
                    $query[$querynum++] = "INSERT INTO customer (nome, apellido, posizione, ragsoc, rut_ditta, rut_persona, giro, legale_nome, legale_rut, addr_pri, addr_work, tel_pri, tel_lavoro, tel_mobile, mail1, mail2, mail3, fatturaz, seg_nome, seg_mail, fantasia, web, skype, codsence, note, impresa, fatturaz_rut, fatturaz_giro, fatturaz_ragsoc, stato, trashed) VALUES ('".$valori['nome']."', '".$valori['apellido']."', '".$valori['posizione']."', '".$valori['ragsoc']."', '".$valori['rut_ditta']."', '".$valori['rut_persona']."', '".$valori['giro']."', '".$valori['legale_nome']."', '".$valori['legale_rut']."', '".$valori['addr_pri']."', '".$valori['addr_work']."', '".$valori['tel_pri']."', '".$valori['tel_lavoro']."', '".$valori['tel_mobile']."', '".$valori['mail1']."', '".$valori['mail2']."', '".$valori['mail3']."', ".$valori['fatturaz'].", '".$valori['seg_nome']."', '".$valori['seg_mail']."', '".$valori['fantasia']."', '".$valori['web']."', '".$valori['skype']."', '".$valori['codsence']."', '".$valori['note']."', ".$valori['impresa'].", '".$valori['fatturazrut']."', '".$valori['fatturazgiro']."', '".$valori['fatturazragsoc']."', 2, 0)";
                    $backpage = "frm_clienti.php?cmd=".FASESEARCH."&filtra_stato=2";
                    $diretto=1;
                    $takeid=$querynum-1;
                    break;
                    
      case "CLIENTI".FASEMOD:
                    $query[$querynum++] = "UPDATE customer SET nome='".$valori['nome']."', apellido='".$valori['apellido']."', posizione='".$valori['posizione']."', ragsoc='".$valori['ragsoc']."', rut_ditta='".$valori['rut_ditta']."', rut_persona='".$valori['rut_persona']."', giro='".$valori['giro']."', legale_nome='".$valori['legale_nome']."', legale_rut='".$valori['legale_rut']."', addr_pri='".$valori['addr_pri']."', addr_work='".$valori['addr_work']."', tel_pri='".$valori['tel_pri']."', tel_lavoro='".$valori['tel_lavoro']."', tel_mobile='".$valori['tel_mobile']."', mail1='".$valori['mail1']."', mail2='".$valori['mail2']."', mail3='".$valori['mail3']."', fatturaz=".$valori['fatturaz'].", seg_nome='".$valori['seg_nome']."', seg_mail='".$valori['segmail']."', fantasia='".$valori['fantasia']."', web='".$valori['web']."', skype='".$valori['skype']."', codsence='".$valori['codsence']."', note='".$valori['note']."', impresa=".$valori['impresa'].", fatturaz_rut='".$valori['fatturazrut']."', fatturaz_giro='".$valori['fatturazgiro']."', fatturaz_ragsoc='".$valori['fatturazragsoc']."' WHERE id=".$valori['selobj']; 
                    $backpage = "frm_clienti.php?selobj=".$valori['selobj']."#SELOBJ";
                    $diretto=1;
                    break;
      case "CLIENTI".FASEDEL:
                    $query[$querynum++] = "UPDATE customer SET trashed=1 WHERE id=".$valori['selobj'];
                    $backpage = "frm_clienti.php";
                    $diretto=1;
                    break;
      case "CLIENTI".FASECREASTUDENTE:
                    //select dei dati
                    $appquery = "SELECT id, id as idgeneratore, nome, apellido, rut_persona, addr_pri, tel_pri, tel_mobile, fax, mail1, mail2
                                  FROM customer
                                  WHERE id=".$valori['selobj'];
                    $result = mysql_query($appquery);
                    $line = mysql_fetch_assoc($result);
                    
                    $valori['idcustomer'] = $line['id'];
                    $valori['nome'] = $line['nome']." ".$line['apellido'];
                    $valori['rut'] = $line['rut_persona'];
                    $valori['indirizzo'] = $line['addr_pri'];
                    $valori['tel'] = $line['tel_pri'];
                    $valori['mobile'] = $line['tel_mobile'];
                    $valori['fax'] = $line['fax'];
                    $valori['email'] = $line['mail1'];
                    $valori['email2'] = $line['mail2'];
                    $valori['attivo'] = 1;
                    
                    //cerca se insert o update
                    $appquery = "SELECT id FROM studente WHERE idgeneratore=".$valori['selobj'];
                    $result = mysql_query($appquery);
                    if (mysql_num_rows($result)>0) {
                      $line = mysql_fetch_assoc($result);
                      $query[$querynum++] = "UPDATE studente SET nome='".$valori['nome']."', rut='".$valori['rut']."', indirizzo='".$valori['addr_pri']."', tel='".$valori."', mobile='".$valori['mobile']."', fax='".$valori['fax']."', email='".$valori['email']."', email2='".$valori['email2']."' WHERE id = ".$line['id']; 
                    } else {
                      $query[$querynum++] = "INSERT INTO studente (idcustomer, idgeneratore, nome, rut, indirizzo, tel, mobile, fax, email, email2, attivo, trashed)
                                              SELECT id, id as idgeneratore, nome, rut_persona, addr_pri, tel_pri, tel_mobile, fax, mail1, mail2, 1 as attivo, 0 as trashed
                                              FROM customer
                                              WHERE id=".$valori['selobj']; 
                    }
                    $backpage = "frm_studenti.php?p=1";
                    $diretto=1;
                    break;
                    
      case "CLIENTISTATO".FASESETTASTATO:
                    //$query[$querynum++] = "UPDATE customer SET stato=".$valori['stato'].", stato2=".$valori['stato2'].", note='".$valori['note']."' WHERE id=".$valori['selobj'];
                    //cambio stato
                    $msgcambio = "nuevo estado >> ".$valori['statotxt'].($valori['stato2txt']!="" ? ": ".$valori['stato2txt'] : "")."\n";

                    $query[$querynum++] = "UPDATE customer SET stato=".$valori['stato'].", stato2=".$valori['stato2']." WHERE id=".$valori['selobj'];
                    $query[$querynum++] = "INSERT INTO customer_log(idcustomer, times, mezzo, msg,user) VALUES (".$valori['selobj'].",".time().",".$valori['mezzo'].",'".$msgcambio.$valori['msg']."',".$_SESSION['user_id'].")";
                    $backpage = "frm_clienti.php?cmd=".FASESEARCH."&filtra_stato=".$valori['stato']."&selobj=".$valori['selobj']."#SELOBJ";
                    $diretto=1;
                    break;
      case "CLIENTISTATO".FASEDELLOG:
                    $query[$querynum++] = "UPDATE customer_log SET trashed=1 WHERE id=".$valori['selobj'];
                    $backpage = "frm_clientistato_popup.php?selobj=".$valori['idc'];
                    $diretto=1;
                    break;
                    
                    
      case "FORNITORI".FASEINS:
                    $query[$querynum++] = "INSERT INTO fornitore (idcategoria, nome, apellido, posizione, ragsoc, rut_ditta, rut_persona, giro, legale_nome, legale_rut, addr_pri, addr_work, tel_pri, tel_lavoro, tel_mobile, mail1, mail2, mail3, fantasia, web, skype, codsence, note, categ2,stato, trashed) VALUES (".$valori['idcategoria'].", '".$valori['nome']."', '".$valori['apellido']."', '".$valori['posizione']."', '".$valori['ragsoc']."', '".$valori['rut_ditta']."', '".$valori['rut_persona']."', '".$valori['giro']."', '".$valori['legale_nome']."', '".$valori['legale_rut']."', '".$valori['addr_pri']."', '".$valori['addr_work']."', '".$valori['tel_pri']."', '".$valori['tel_lavoro']."', '".$valori['tel_mobile']."', '".$valori['mail1']."', '".$valori['mail2']."', '".$valori['mail3']."', '".$valori['fantasia']."', '".$valori['web']."', '".$valori['skype']."', '".$valori['codsence']."', '".$valori['note']."',  '".$valori['categ2']."', 1, 0)";
                    
                    //TEMP BANCA
                    if ($valori['ccbanca_id']=="") {
                      //nuovo allora ins
                      $query[$querynum] = "INSERT INTO ccbanca(idbanca, tiposoggetto, idsoggetto, codice, note, tipocc) VALUES (".$valori['idbanca'].", 2, ###INSID_".($querynum-1)."###, '".$valori['ccbanca_codice']."', '', ".$valori['ccbanca_tipocc'].")";
                      $querynum++;
                    } else {
                      $query[$querynum++] = "UPDATE ccbanca SET idbanca=".$valori['idbanca'].", codice='".$valori['ccbanca_codice']."', tipocc=".$valori['ccbanca_tipocc']." WHERE id=".$valori['ccbanca_id'];
                    }
                    
                    //myprint_r($query);
                    //die("w");
                    
                    $backpage = "frm_fornitori.php?p=1";
                    $diretto=1;
                    $takeid=0;
                    break;
                    
      case "FORNITORI".FASEMOD:
                    $query[$querynum++] = "UPDATE fornitore SET nome='".$valori['nome']."', apellido='".$valori['apellido']."', posizione='".$valori['posizione']."', ragsoc='".$valori['ragsoc']."', rut_ditta='".$valori['rut_ditta']."', rut_persona='".$valori['rut_persona']."', giro='".$valori['giro']."', legale_nome='".$valori['legale_nome']."', legale_rut='".$valori['legale_rut']."', addr_pri='".$valori['addr_pri']."', addr_work='".$valori['addr_work']."', tel_pri='".$valori['tel_pri']."', tel_lavoro='".$valori['tel_lavoro']."', tel_mobile='".$valori['tel_mobile']."', mail1='".$valori['mail1']."', mail2='".$valori['mail2']."', mail3='".$valori['mail3']."', fantasia='".$valori['fantasia']."', web='".$valori['web']."', skype='".$valori['skype']."', codsence='".$valori['codsence']."', note='".$valori['note']."', idcategoria=".$valori['idcategoria'].", categ2='".$valori['categ2']."' WHERE id=".$valori['selobj']; 
                    
                    //TEMP BANCA
                    if ($valori['ccbanca_id']=="") {
                      //nuovo allora ins
                      $query[$querynum] = "INSERT INTO ccbanca(idbanca, tiposoggetto, idsoggetto, codice, note, tipocc) VALUES (".$valori['idbanca'].", 2, ".$valori['selobj'].", '".$valori['ccbanca_codice']."', '', ".$valori['ccbanca_tipocc'].")";
                      $querynum++;
                    } else {
                      $query[$querynum++] = "UPDATE ccbanca SET idbanca=".$valori['idbanca'].", codice='".$valori['ccbanca_codice']."', tipocc=".$valori['ccbanca_tipocc']." WHERE id=".$valori['ccbanca_id'];
                    }
                    
                    //myprint_r($query);
                    //die("w");
                    
                    $backpage = "frm_fornitori.php?selobj=".$valori['selobj'];
                    $diretto=1;
                    break;
      case "FORNITORI".FASEDEL:
                    $query[$querynum++] = "UPDATE fornitore SET trashed=1 WHERE id=".$valori['selobj'];
                    $backpage = "frm_fornitori.php";
                    $diretto=1;                 
                    break;                    
      case "FORNITORI".FASEDENY:
                    $query[$querynum++] = "UPDATE fornitore SET stato=0 WHERE id=".$valori['selobj'];
                    $backpage = "frm_fornitori.php";
                    $diretto=1;
                    break;
      case "FORNITORI".FASEALLOW:
                    $query[$querynum++] = "UPDATE fornitore SET stato=1 WHERE id=".$valori['selobj'];
                    $backpage = "frm_fornitori.php";
                    $diretto=1;
                    break;                    
                    
      case "STUDENTI".FASEINS:
                    $query[$querynum++] = "INSERT INTO studente(idcustomer, iduser, nome, nome2, cognome, cognome2, foto, rut, giro, indirizzo, num, numappartamento, comune, tel, mobile, fax, email, email2, attivo, trashed) VALUES (".$valori['idcustomer'].", ".$valori['iduser'].", '".$valori['nome']."', '', '', '', '', '".$valori['rut']."', '".$valori['giro']."', '".$valori['indirizzo']."', '".$valori['num']."', '".$valori['numappartamento']."', '".$valori['comune']."', '".$valori['tel']."', '".$valori['mobile']."', '".$valori['fax']."', '".$valori['email']."', '".$valori['email2']."', 1, 0)";
                    $backpage = "frm_studenti.php?p=1";
                    $diretto=1;
                    $takeid=0;
                    break;
      case "STUDENTI".FASEMOD:
                    $query[$querynum++] = "UPDATE studente SET idcustomer=".$valori['idcustomer'].", iduser=".$valori['iduser'].", nome='".$valori['nome']."', foto='', rut='".$valori['rut']."', giro='".$valori['giro']."', indirizzo='".$valori['indirizzo']."', num='".$valori['num']."', numappartamento='".$valori['numappartamento']."', comune='".$valori['comune']."', tel='".$valori['tel']."', mobile='".$valori['mobile']."', fax='".$valori['fax']."', email='".$valori['email']."', email2='".$valori['email2']."' WHERE id=".$valori['selobj'];
                    $backpage = "frm_studenti.php?selobj=".$valori['selobj'];
                    $diretto=1;
                    break;
      case "STUDENTI".FASEDEL:
                    $query[$querynum++] = "UPDATE studente SET trashed=1, attivo=0 WHERE id=".$valori['selobj'];
                    $backpage = "frm_studenti.php";
                    $diretto=1;
                    break;
      case "STUDENTI".FASEDENY:
                    $query[$querynum++] = "UPDATE studente SET attivo=0 WHERE id=".$valori['selobj'];
                    $backpage = "frm_studenti.php?selobj=".$valori['selobj'];
                    $diretto=1;
                    break;
      case "STUDENTI".FASEALLOW:
                    $query[$querynum++] = "UPDATE studente SET attivo=1 WHERE id=".$valori['selobj'];
                    $backpage = "frm_studenti.php?selobj=".$valori['selobj'];
                    $diretto=1;
                    break;
      case "CLASSI".FASEINS:
                    $query[$querynum++]  = "INSERT INTO classi (descr, idcustomer, dt, attivo, trashed) VALUES ('".$valori['classi_descr']."', ".$valori['classi_idcustomer'].", ".$adesso.", 1,0)";
                    $backpage = "frm_classi.php?p=1";
                    $diretto=1;
                    $takeid=0;
                    break;
      case "CLASSI".FASEMOD:
                    $query[$querynum++]  = "UPDATE classi SET descr='".$valori['classi_descr']."', idcustomer=".$valori['classi_idcustomer']." WHERE id=".$valori['selobj'];
                    $backpage = "frm_classi.php?selobj=".$valori['selobj'];
                    $diretto=1;
                    break;
      case "CLASSI".FASEDEL:
                    $query[$querynum++]  = "UPDATE classi SET trashed=1 WHERE id=".$valori['selobj'];
                    $backpage = "frm_classi.php?selobj=".$valori['selobj'];
                    $diretto=1;
                    break;
      case "CLASSI".FASEALLOW:
                    $query[$querynum++]  = "UPDATE classi SET attivo=1 WHERE id=".$valori['selobj'];
                    $backpage = "frm_classi.php?selobj=".$valori['selobj'];
                    $diretto=1;
                    break;
      case "CLASSI".FASEDENY:
                    $query[$querynum++]  = "UPDATE classi SET attivo=0 WHERE id=".$valori['selobj'];
                    $backpage = "frm_classi.php?selobj=".$valori['selobj'];
                    $diretto=1;
                    break;                                            
      case "CLASSI".FASECLASSE:
                    $appquery = "INSERT INTO enroll (idclasse, idstudente, dtini) VALUES ";
                    foreach ($vet_enrollstudenti as $keyenroll => $curenroll) {
                      $appquery .= "(".$valori['selobj'].", ".$curenroll.", ".$adesso."), ";
                    }
                    $appquery = substr($appquery,0,-2);
                    $query[$querynum++] = $appquery;
                    $query[$querynum++] = "UPDATE classi SET attivo=1 WHERE id=".$valori['selobj'];
                    $backpage = "frm_classi.php?cmd=".FASECLASSE."&selobj=".$valori['selobj'];
                    $diretto=1;
                    break;
      case "CLASSI".FASEREFERSTUDENTE:
                    $query[$querynum++] = "UPDATE enroll SET frefer=NULL WHERE idclasse=".$valori['idc'];
                    $query[$querynum++] = "UPDATE enroll SET frefer=1 WHERE idclasse=".$valori['idc']." AND idstudente=".$valori['selobj'];
                    $backpage = "frm_classi_enrollpopup.php?selobj=".$valori['idc'];
                    $diretto=1;
                    break;   
      case "CLASSI".FASEDELSTUDENTE:
                    $query[$querynum++] = "DELETE FROM enroll WHERE idclasse=".$valori['idc']." AND idstudente=".$valori['selobj'];
                    $backpage = "frm_classi_enrollpopup.php?cmd=".FASECLASSE."&selobj=".$valori['idc'];
                    $diretto=1;
                    break;                 
      case "CLASSI".FASEFINESTUDENTE:
                    $query[$querynum++] = "UPDATE enroll SET dtfine=".eT_dt2times($valori['dtf'])." WHERE idclasse=".$valori['idc']." AND idstudente=".$valori['selobj'];                                        
                    $backpage = "frm_classi_enrollpopup.php?cmd=".FASECLASSE."&selobj=".$valori['idc'];
                    $diretto=1;
                    break;                    
      case "CLASSI".FASERESTORESTUDENTE:
                    $query[$querynum++] = "UPDATE enroll SET dtfine=NULL WHERE idclasse=".$valori['idc']." AND idstudente=".$valori['selobj'];                    
                    $backpage = "frm_classi_enrollpopup.php?cmd=".FASECLASSE."&selobj=".$valori['idc'];
                    $diretto=1;
                    break;                                 
/*
                      ########   ######  ######## ##     ## ########  ####  #######  
                      ##     ## ##    ##    ##    ##     ## ##     ##  ##  ##     ## 
                      ##     ## ##          ##    ##     ## ##     ##  ##  ##     ## 
                      ########   ######     ##    ##     ## ##     ##  ##  ##     ## 
                      ##              ##    ##    ##     ## ##     ##  ##  ##     ## 
                      ##        ##    ##    ##    ##     ## ##     ##  ##  ##     ## 
                      ##         ######     ##     #######  ########  ####  #######  
*/
                    
      case "PSTUDIO".FASEINS:
                    //$idpstudi = getnewid("pstudi");
                    $query[$querynum++] = "INSERT INTO pstudi ( idcorso, idclasse,  descr, datai, dataf, compenso, durata, dlezione, codsense, codotec, attivita, luogo, style, attivo, trasporto, trashed) VALUES (
                              ".$valori['idcorso'].", ".$valori['idclasse'].", '".$valori['descr']."', NULL, NULL, ".$valori['compenso'].", ".$valori['durata'].", ".$valori['dlezione'].", '".$valori['codsense']."', '".$valori['codotec']."', '".$valori['attivita']."', '".$valori['luogo']."', '".$valori['style']."', 1, ".$valori['trasporto'].", 0)";
                    
                    
                    /*
                    tolta 19/11
                    if ($valori['iddocente']!="") {
                      $query[$querynum++] = "INSERT INTO insegna(idpstudi, iddocente, attivo) VALUES (".$idpstudi.", ".$valori['iddocente'].", 1)";
                    }
                    */
                    if (count($vet_docenti)>0) {
                      foreach ($vet_docenti as $keydoc => $curdoc) {
                        $query[$querynum++] = "INSERT INTO insegna(".$vetinsid["###INSID_".($querynum-1)."###"].", iddocente, attivo) VALUES (".$idpstudi.", ".$keydoc.", 1)";
                      }
                    }
              
                    $backpage = "frm_pstudio.php?selobj=$idpstudi";
                    $diretto=1;
                    break;
      case "PSTUDIO".FASEMOD:
                    $query[$querynum++] = "UPDATE pstudi SET idcorso=".$valori['idcorso'].", idclasse=".$valori['idclasse'].", descr='".$valori['descr']."', compenso=".$valori['compenso'].", trasporto=".$valori['trasporto'].", durata=".$valori['durata'].", dlezione=".$valori['dlezione'].", codsense='".$valori['codsense']."', codotec='".$valori['codotec']."', attivita='".$valori['attivita']."', luogo='".$valori['luogo']."', style='".$valori['style']."' WHERE id=".$valori['selobj']; 
                    
                    if (count($vet_docenti)>0) {
                      $query[$querynum++] = "DELETE FROM insegna WHERE idpstudi=".$valori['selobj'];
                      foreach ($vet_docenti as $keydoc => $curdoc) {
                        $query[$querynum++] = "INSERT INTO insegna(idpstudi, iddocente, attivo) VALUES (".$valori['selobj'].", ".$keydoc.", 1)";
                      }
                    }
                    
                    $backpage = "frm_pstudio.php?selobj=".$valori['selobj'];
                    $diretto=1;
                    break;
      case "PSTUDIO".FASEDEL:
                    $query[$querynum++] = "UPDATE pstudi SET trashed=1, attivo=0 WHERE id=".$valori['selobj'];
                    $query[$querynum++] = "UPDATE appuntamento SET trashed=1 WHERE idpstudi=".$valori['selobj'];
                    $query[$querynum++] = "DELETE FROM prefday WHERE idpstudi=".$valori['selobj'];
                                     
                    $backpage = "frm_pstudio.php";
                    $diretto=1;
                    break;
      case "PSTUDIO".FASEDENY:
                    $query[$querynum++] = "UPDATE pstudi SET attivo=0 WHERE id=".$valori['selobj'];
                    $backpage = "frm_pstudio.php";
                    $diretto=1;
                    break;
      case "PSTUDIO".FASEALLOW:
                    $query[$querynum++] = "UPDATE pstudi SET attivo=1 WHERE id=".$valori['selobj'];
                    $backpage = "frm_pstudio.php";
                    $diretto=1;
                    break;                                        
                    
/*      case "PSTUDIO".FASEASSDOCENTE:
                    $query[$querynum++] = "INSERT INTO insegna (idpstudi, iddocente, attivo) VALUES (".$valori['selobj'].", ".$valori['iddocente'].",1)";
                    $backpage = "frm_classi.php?cmd=".FASEMOD."&selobj=".$valori['selobj'];
                    $diretto=1;
                    break;      
      case "PSTUDIO".FASEREMDOCENTE:
                    $query[$querynum++] = "DELETE FROM insegna WHERE idpstudi=".$valori['idpstudi']." AND iddocente=".$valori['iddocente']." AND attivo=1";
                    $backpage = "frm_classi.php?cmd=".FASEMOD."&selobj=".$valori['idpstudi'];
                    $diretto=1;
                    break;
*/
                     
/*      case "PSTUDIO".FASEADDSTUDENTE:
                    $query[$querynum++] = "INSERT INTO classe (idstudente, idpstudi, datai, dataf) VALUES (".$valori['idstudente'].",".$valori['idpstudi'].",".time().",1)";
                    $backpage = "frm_classi.php?cmd=".FASECLASSE."&selobj=".$valori['idpstudi'];
                    $diretto=1;
                    break; 
      case "PSTUDIO".FASEDELSTUDENTE:
                    $query[$querynum++] = "DELETE FROM classe WHERE idstudente=".$valori['idstudente']." AND idpstudi=".$valori['idpstudi']." AND datai=".$valori['datai']." AND dataf=1";
                    $backpage = "frm_classi.php?cmd=".FASECLASSE."&selobj=".$valori['idpstudi'];
                    $diretto=1;
                    break;
      case "PSTUDIO".FASEFINESTUDENTE:
                    $query[$querynum++] = "UPDATE classe SET dataf=".eT_dt2times($valori['dataf'])." WHERE idstudente=".$valori['idstudente']." AND idpstudi=".$valori['idpstudi']." AND datai=".$valori['datai']." AND dataf=1";
                    $backpage = "frm_classi.php?cmd=".FASECLASSE."&selobj=".$valori['idpstudi'];
                    $diretto=1;
                    break;
*/
/*
      ########   ######  ######## ##     ## ########  ####  #######          ######## ##     ## ######## 
      ##     ## ##    ##    ##    ##     ## ##     ##  ##  ##     ##         ##       ##     ##    ##    
      ##     ## ##          ##    ##     ## ##     ##  ##  ##     ##         ##       ##     ##    ##    
      ########   ######     ##    ##     ## ##     ##  ##  ##     ##         ######   ##     ##    ##    
      ##              ##    ##    ##     ## ##     ##  ##  ##     ##         ##        ##   ##     ##    
      ##        ##    ##    ##    ##     ## ##     ##  ##  ##     ##         ##         ## ##      ##    
      ##         ######     ##     #######  ########  ####  #######  ####### ########    ###       ##   
*/  
      case "PSTUDIO_EVT".FASEPREFDAYINS:
                    //sett datai e dataf
                    $query[$querynum++] = "UPDATE pstudi SET datai=".eT_dt2times($valori['datai']).", dataf=".eT_dt2times($valori['dataf'])." WHERE id=".$valori['selobj'];
                    
                    //azzero attuale situazione prefday
                    $query[$querynum++] = "DELETE FROM prefday WHERE idpstudi = ".$valori['selobj'];
                    for ($i=1;$i<=$valori['prefne'];$i++) {
                      $query[$querynum++] = "INSERT INTO prefday (idpstudi, wkday, orai, oraf, iddocente) VALUES (".$valori['selobj'].", ".$valori['wkday_'.$i].", ".$valori['orai_'.$i].", ".$valori['oraf_'.$i].", ".$valori['iddocente_'.$i].")";
                    }
                    
                    $query[$querynum++] = "UPDATE appuntamento SET trashed=1 WHERE id IN (".$valori['les_delid'].")";
                    for ($i=1;$i<=$valori['evtne'];$i++) {
                      $query[$querynum++] = "INSERT INTO appuntamento (idpstudi, iddocente, dtini, dtfine, compenso, tipo, note, idsostituzione, trashed) VALUES (".$valori['idpstudi'].", ".$valori['evtiddocente'.$i].", ".$valori['evtdataini'.$i].", ".$valori['evtdatafine'.$i].", ".$valori['compenso'].", 1, '_LEZIONENUM_".($i + $valori['lesdone'])."\n', NULL, 0);";
                    }
                    
                    //conflitto
                    for ($i=1;$i<=$valori['evtdbne'];$i++) {
                      switch ($valori['dbact'.$i]) {
                        case 3://sostituzione
                          $query[$querynum++] = "UPDATE appuntamento SET fsost=-1, fnotify=1 WHERE id=".$valori['evtdbid'.$i];
                          break;
                        case 4://elimina senza recupero
                          $query[$querynum++] = "UPDATE appuntamento SET trashed=1, frecupera=0 WHERE id=".$valori['evtdbid'.$i];
                          break;
                        case 5:
                          $query[$querynum++] = "UPDATE appuntamento SET trashed=1, frecupera=1 WHERE id=".$valori['evtdbid'.$i];
                          break;
                      }
                    }
                    //$backurl = urlencode("frm_pstudio.php");
                    //$backpage = "inviamail.php?backurl=".$backurl."&tipoact=1";
                    $backpage = "timetable.php";
                    //$backpage = "frm_classi.php";
                    $diretto=1;
                    break;                                                                 
      case "USER".FASEINS:
                    $fboss = 0;
                    $fviceboss=0;
                    switch ($valori['permessi']) {
                      case 30:
                        $fboss=1;break;
                      case 20:
                        $fviceboss=1;break;
                    }
                    $query[$querynum++] = "INSERT INTO user (nome, username, password, email, dtcreazione, attivo, fboss, fviceboss, trashed) VALUES ('".$valori['user_nome']."', '".$valori['username']."', '".$valori['password']."', '".$valori['user_email']."', ".time().", 0, ".$fboss.", ".$fviceboss.", 0);";
                    $backpage = "frm_user.php";
                    $diretto=1;
                    break;
      case "USER".FASEMOD:
                    $fboss = 0;
                    $fviceboss=0;
                    switch ($valori['permessi']) {
                      case 30:
                        $fboss=1;break;
                      case 20:
                        $fviceboss=1;break;
                    }
                    $query[$querynum++] = "UPDATE user SET nome='".$valori['user_nome']."', fboss=".$fboss.", fviceboss=".$fviceboss.", email='".$valori['user_email']."' WHERE id=".$valori['selobj'];
                    if ($valori['password']!=FIXPASSWORD) {
                      $query[$querynum++] = "UPDATE user SET password='".$valori['password']."' WHERE id=".$valori['selobj'];
                    }                    
                    $backpage = "frm_user.php";
                    $diretto=1;
                    break;
      case "USER".FASEDEL:
                    $query[$querynum++] = "UPDATE user SET trashed=1, attivo=0 WHERE id=".$valori['selobj'];                    
                    $backpage = "frm_user.php";
                    $diretto=1;
                    break;
      case "USER".FASEDENY:
                    $query[$querynum++] = "UPDATE user SET attivo=0 WHERE id=".$valori['selobj'];
                    $backpage = "frm_user.php";
                    $diretto=1;
                    break;
      case "USER".FASEALLOW:
                    $query[$querynum++] = "UPDATE user SET attivo=1 WHERE id=".$valori['selobj'];
                    $backpage = "frm_user.php";
                    $diretto=1;
                    break;
      case "CONTATTICUST".FASEINS:
                    $query[$querynum++] = "INSERT INTO customer_contatto(idcustomer, nome, posizione, tel_lavoro, tel_privato, fax, mail1, mobile, skype, note, predef, trashed) VALUES (".$valori['idcustomer'].", '".$valori['nome']."', '".$valori['posizione']."', '".$valori['tel_lavoro']."', '".$valori['tel_privato']."', '".$valori['fax']."', '".$valori['mail1']."', '".$valori['mobile']."', '".$valori['skype']."', '".$valori['note']."', 0,0)";
                    $backpage = "frm_clienti.php?op=".$valori['idcustomer']."#blocco".$valori['idcustomer'];
                    $diretto=1;
                    break;
      case "CONTATTICUST".FASEMOD:
                    $query[$querynum++] = "UPDATE customer_contatto SET idcustomer=".$valori['idcustomer'].", nome='".$valori['nome']."', posizione='".$valori['posizione']."', tel_lavoro='".$valori['tel_lavoro']."', tel_privato='".$valori['tel_privato']."', fax='".$valori['fax']."', mail1='".$valori['mail1']."', mobile='".$valori['mobile']."', skype='".$valori['skype']."', note='".$valori['note']."' WHERE id=".$valori['selobj'];
                    $backpage = "frm_clienti.php?op=".$valori['idcustomer']."#blocco".$valori['idcustomer'];
                    $diretto=1;
                    break;
      case "CONTATTICUST".FASEDEL:
                    $query[$querynum++] = "UPDATE customer_contatto SET trashed=1 WHERE id=".$valori['selobj'];
                    $backpage = "frm_clienti.php?op=".$valori['idcustomer']."#blocco".$valori['idcustomer'];
                    $diretto=1;
                    break;
      case "CONTATTICUST".FASEPREFERITO:
                    $query[$querynum++] = "UPDATE customer_contatto SET predef=0 WHERE idcustomer=".$valori['idcustomer'];
                    $query[$querynum++] = "UPDATE customer_contatto SET predef=1 WHERE id=".$valori['selobj'];
                    $backpage = "frm_clienti.php?op=".$valori['idcustomer']."#blocco".$valori['idcustomer'];
                    $diretto=1;
                    break;


      case "CONTATTIFORN".FASEINS:
                    $query[$querynum++] = "INSERT INTO fornitore_contatto(idfornitore, nome, posizione, tel_lavoro, tel_privato, fax, mail1, mobile, skype, note, predef, trashed) VALUES (".$valori['idfornitore'].", '".$valori['nome']."', '".$valori['posizione']."', '".$valori['tel_lavoro']."', '".$valori['tel_privato']."', '".$valori['fax']."', '".$valori['mail1']."', '".$valori['mobile']."', '".$valori['skype']."', '".$valori['note']."', 0,0)";
                    $backpage = "frm_fornitori.php?op=".$valori['idfornitore']."#blocco".$valori['idfornitore'];
                    $diretto=1;                      
                    break;
      case "CONTATTIFORN".FASEMOD:
                    $query[$querynum++] = "UPDATE fornitore_contatto SET idfornitore=".$valori['idfornitore'].", nome='".$valori['nome']."', posizione='".$valori['posizione']."', tel_lavoro='".$valori['tel_lavoro']."', tel_privato='".$valori['tel_privato']."', fax='".$valori['fax']."', mail1='".$valori['mail1']."', mobile='".$valori['mobile']."', skype='".$valori['skype']."', note='".$valori['note']."' WHERE id=".$valori['selobj'];
                    $backpage = "frm_fornitori.php?op=".$valori['idfornitore']."#blocco".$valori['idfornitore'];
                    $diretto=1;                  
                    break;
      case "CONTATTIFORN".FASEDEL:
                    $query[$querynum++] = "UPDATE fornitore_contatto SET trashed=1 WHERE id=".$valori['selobj'];
                    $backpage = "frm_fornitori.php?op=".$valori['idfornitore']."#blocco".$valori['idfornitore'];
                    $diretto=1;                    
                    break;
      case "CONTATTIFORN".FASEPREFERITO:
                    $query[$querynum++] = "UPDATE fornitore_contatto SET predef=0 WHERE idfornitore=".$valori['idfornitore'];
                    $query[$querynum++] = "UPDATE fornitore_contatto SET predef=1 WHERE id=".$valori['selobj'];
                    $backpage = "frm_fornitori.php?op=".$valori['idfornitore']."#blocco".$valori['idfornitore'];
                    $diretto=1;
                    break;
      case "DOCENTIFILE".FASEINS:
                    //carica file
                    $tmpfname = tempnam($pathfileupload, "TTO");
                    $pos = strrpos($_FILES['nomefile']['name'], ".");
                    $tmpfname .= substr($_FILES['nomefile']['name'],$pos);
                    
                    $pos = strrpos($tmpfname, "/");
                    $nomefiledb = substr($tmpfname,($pos+1));
                    
                    if (!move_uploaded_file($_FILES['nomefile']['tmp_name'], $tmpfname)) {
                      print "<H1>"._ERRORE_UPFILE_."<H1>";
                    } else {
                      $valori['mime'] = $_FILES['nomefile']['type'];
                      $valori['descr'].= "(".$_FILES['nomefile']['name'].")";
                    }
                    
                    $query[$querynum++] = "INSERT INTO archiviofile(idgruppo, idext, nomefile, txt, mime,times) VALUES (1,".$valori['selobj'].",'".$nomefiledb."', '".$valori['descr']."','".$valori['mime']."',".time().")";
                    $backpage = "frm_docenti_filepopup.php?selobj=".$valori['selobj'];
                    $diretto=1;
                    break;
      
      case "DOCENTI".FASEDELFILE:
      case "DOCENTIFILE".FASEDELFILE:
                    //auth
                    if (!checkauth("TTO702","D")) {
                      die (_MSG_ERRORE_AUTORIZZAZIONE_);
                    }
                    $query[$querynum++] = "DELETE FROM archiviofile WHERE id=".$valori['selobj'];
                    unlink ($pathfileupload.$valori['nomefile']);
                    if ($codicepagina=="DOCENTI") {
                      $backpage = "frm_docenti.php?op=".$valori['idd'];
                    } else {
                      $backpage = "frm_docenti_filepopup.php?selobj=".$valori['idd'];
                    }
                    $diretto=1;
                    break;

      case "CONTABILITA".FASEMOD:
                    
                    if ($valori['modquote']==0) {
                      
                      $dtpagoprev = eT_dt2times($valori['dtpagoprev']);
                      if ($valori['dtpromemoria']==0) {
                        $dtpromemoria=0;
                      } else {
                        $dtpromemoria = eT_dt2times($valori['dtpromemoria']);
                      }
                      
                      
                      
                      
                      $query[$querynum++] = "UPDATE entrate SET descr='".$valori['descr']."', importo=".$valori['importo'].", idtipo=".$valori['idtipo'].", idext=".$valori['idext'].", 
                           tipocliente=".$valori['tipocliente'].", idcliente=".$valori['idcliente'].", dtpagoprev=".$dtpagoprev.", doc='".$valori['doc']."', 
                           tipopago=".$valori['tipopago'].", refpago='".$valori['refpago']."', idcc=".$valori['idcc'].", idcdcosto=".$valori['idcdcosto'].", fncredito=".$valori['fncredito'].",
                           ncidannullo=".$valori['ncidannullo'].", note='".$valori['note']."', fpromemoria=".$valori['fpromemoria'].", dtpromemoria=".$dtpromemoria.", importosinquota=".$valori['importosinquota']."
                           WHERE id=".$valori['selobj'];
                           
                      $query[$querynum++] = "UPDATE entrate SET dtpagoreale=0 WHERE dtpagoreale < dtpagoprev AND dtpagoreale>0 AND id=".$valori['selobj'];
                      
                      
                      //sistema le quote
                      if ($valori['updquotadescr']==1) {
                        $query[$querynum++] = "UPDATE entrate SET descr='".$valori['descr']."' WHERE quotaroot=".$valori['quotaroot'];
                      }
                      
                      if ($valori['updquotatotale']==1) {
                        $query[$querynum++] = "UPDATE entrate SET importosinquota=".($valori['quotatotimporto'] + $valori['importo'])." WHERE quotaroot=".$valori['quotaroot'];
                      }
                      
                      $backpage = "frm_contabshow.php?op=".$valori['idfornitore'];                
                      $diretto=1;

                      //myprint_r($query);
                      //die("W");

                      break;
                    } else {
                      //$query[$querynum++] = "DELETE FROM entrate WHERE idtipo=".$valori['idtipo']." AND tipocliente=".$valori['tipocliente']." AND idcliente=".$valori['idcliente']." AND importosinquota=".$valori['importosinquota']." AND idcdcosto=".$valori['idcdcosto'];
                      //$query[$querynum++] = "DELETE FROM entrate WHERE quotaroot=".$valori['quotaroot'];
                      $query[$querynum++] = "UPDATE entrate SET trashed=1 WHERE quotaroot<>0 AND quotaroot=".$valori['quotaroot'];
                    }      
      case "CONTABILITA".FASEINS:
                    //myprint_r($query);
                    //ciclo x numero di quote
                    
                    
                    
                    if ($valori['nquote']>1) {
                      $idquota = eT_getnewnum("IDQUOTA");
                    } else {
                      $idquota=0;
                    }
                    
                    for ($iquota=1;$iquota<=$valori['nquote'];$iquota++) {
                      $curquota = $valori['qmt_'.$iquota];
                      $curdata = eT_dt2times($valori['qdt_'.$iquota]);
                      
                      $valori['dtpagoreale']=0;
                      if ($curdata<=day_mezzanotte(time())) {
                        $valori['dtpagoreale'] = $curdata;
                      }
                      
                      $query[$querynum++] = "INSERT INTO entrate(descr, importo, idtipo, idext, tipocliente, idcliente, dtcreazione, 
                      dtpagoprev, dtpagoreale, doc, tipopago, refpago, idcc, idcdcosto, fncredito, ncidannullo, note, fpromemoria, 
                      dtpromemoria, nquota, thisquota, quotaroot, importosinquota, trashed) VALUES (
                      '".$valori['descr']."',".$curquota.", ".$valori['idtipo'].", ".$valori['idext'].", ".$valori['tipocliente'].", 
                      ".$valori['idcliente'].", $adesso, ".$curdata.", ".$valori['dtpagoreale'].", '".$valori['doc']."', 
                      ".$valori['tipopago'].", '".$valori['refpago']."', ".$valori['idcc'].", ".$valori['idcdcosto'].", 
                      ".$valori['fncredito'].", ".$valori['ncidannullo'].", '".$valori['note']."', ".$valori['fpromemoria'].", 
                      ".$valori['dtpromemoria'].", ".$valori['nquote'].", ".$iquota.", ".$idquota.", 
                      ".$valori['importosinquota'].", 0)";
                      
                      /*
                      $query[$querynum++] = "INSERT INTO entrate(descr, importo, idtipo, idext, tipocliente, idcliente, dtcreazione, 
                      dtpagoprev, dtpagoreale, doc, tipopago, refpago, idcc, idcdcosto, fncredito, ncidannullo, note, fpromemoria, 
                      dtpromemoria, nquota, thisquota, quotaroot, importosinquota, trashed) VALUES (
                      '".$valori['descr']."',".$valori['importo'].", ".$valori['idtipo'].", ".$valori['idext'].", ".$valori['tipocliente'].", 
                      ".$valori['idcliente'].", $adesso, ".$valori['dtpagoprev_times'].", 0, '".$valori['doc']."', 
                      ".$valori['tipopago'].", '".$valori['refpago']."', ".$valori['idcc'].", ".$valori['idcdcosto'].", 
                      ".$valori['fncredito'].", ".$valori['ncidannullo'].", '".$valori['note']."', ".$valori['fpromemoria'].", 
                      ".$valori['dtpromemoria'].", ".$valori['nquota'].", ".$valori['thisquota'].", ".$valori['quotaroot'].", 
                      ".$valori['importosinquota'].", 0)";
                      */
                    }
                    
                    //myprint_r($query);
                    //die("w");
                    
                    $backpage = "frm_contabshow.php?op=".$valori['idfornitore'];

                    $diretto=1;
                    break;
      case "CONTABILITA".FASEDEL:
                    $query[$querynum++] = "UPDATE entrate SET trashed=1 WHERE id=".$valori['selobj'];
                    $backpage = "frm_contabshow.php";
                    $diretto=1;
                    break;
                     
           
      case "CONTABILITA".FASESETUPDPAGOREALE:
                    $query[$querynum++] = "UPDATE entrate SET dtpagoreale=".eT_dt2times($valori['dtpagoreale'])." WHERE id=".$valori['selobj'];
                    $backpage = "frm_contabshow.php?op=".$valori['idfornitore'];
                    $diretto=1;
                    break;
      
      case "CONTABILITA".FASERESETUPDPAGOREALE:
                    $query[$querynum++] = "UPDATE entrate SET dtpagoreale=0 WHERE id=".$valori['selobj'];
                    $backpage = "frm_contabshow.php?op=".$valori['idfornitore'];
                    $diretto=1;
                    break;
      case "BANCA".FASEMOD:
                    //ins
                    if ($valori['banca_new']!="") {
                      $query[$querynum++] = "INSERT INTO banca (nomebanca, trashed) VALUES ('".$valori['banca_new']."',0)";
                    }
                    
                    //del
                    if ($valori['banca_delete']!="") {
                      $query[$querynum++] = "DELETE FROM banca WHERE id IN (".substr($valori['banca_delete'],0,-2).")";
                    }
                    
                    //mod
                    for ($r=1;$r<=$valori['banca_ne'];$r++) {
                      $query[$querynum++] = "UPDATE banca set nomebanca='".$valori['nomebanca_'.$r]."' WHERE id=".$valori['id_'.$r];
                    }                                        
                    $backpage = "frm_contab_anagbanchepopup.php?cmd=".FASEREQUERY;
                    $diretto=1;
                    break;
      
      case "CCBANCA".FASEMOD:
                    $query[$querynum++] = "UPDATE ccbanca SET idbanca=".$valori['idbanca'].", tiposoggetto=".$valori['tiposoggetto'].", idsoggetto=".$valori['idsoggetto'].", codice='".$valori['ccbanca_codice']."', tipocc=".$valori['tipoconto']." WHERE id=".$valori['selobj'];
                    $backpage = "frm_contab_banchecc.php?op=".$valori['selobj'];
                    $diretto=1;
                    break;
                    
      case "SALDO".FASEINS:
                    $query[$querynum++] = "INSERT INTO saldo(dt, importo, note) VALUES ('".eT_Rstrdt2string($valori['dt'])."', ".$valori['importo'].", '".$valori['note']."')";
                    $backpage = "frm_contabshow.php";
                    $diretto=1;
                    break;
      case "SALDO".FASEMOD:
                    $query[$querynum++] = "UPDATE saldo SET dt='".eT_Rstrdt2string($valori['dt'])."', importo=".$valori['importo'].", note='".$valori['note']."' WHERE id=".$valori['selobj'];
                    $backpage = "frm_contabshow.php";
                    $diretto=1;
                    break;
      case "SALDO".FASEDEL:
                    $query[$querynum++] = "DELETE FROM saldo WHERE id=".$valori['selobj'];
                    $backpage = "frm_contabshow.php";
                    $diretto=1;
                    break;
      case "LINGUA".FASEMOD:
                    for($i=1;$i<=$valori['ne'];$i++) {
                      $updimg="";
                      if ($valori['img1_'.$i]!="") {
                        $updimg = ", img1='".$valori['img1_'.$i]."'";
                      }
                      $query[$querynum++] = "UPDATE lingua SET descr='".$valori['descr_'.$i]."' ".$updimg." WHERE id=".$valori['id_'.$i];
                    }
                    if ($valori['lingua_new']==1) {
                      if ($valori['img1_new']!="") {
                        $updimg = "'".$valori['img1_new']."'";
                      } else {
                        $updimg = "''";
                      }
                      $query[$querynum++] = "INSERT INTO lingua(codint, descr, img1, img2) VALUES ('".$valori['codint_new']."', '".$valori['descr_new']."', ".$updimg.",'')";
                    }
                    
                    $backpage = "frm_lingua.php?chrit=2102";
                    $diretto=1;
                    break;
      case "LINGUA".FASEDEL:
                    $query[$querynum++] = "DELETE FROM lingua WHERE id=".$valori['selobj'];
                    $backpage = "frm_lingua.php";
                    $diretto=1;
                    break;
      case "REMINDER".FASEUPDDATA:
                    $query[$querynum++] = "UPDATE reminder SET dataora='".$valori['nuovasdt']."' WHERE id=".$valori['selobj'];
                    $backpage = "reminderpopup.php?selobj=".$valori['selobj'];
                    $diretto=1;
                    
                    break;
      case "REMINDER".FASEMOD:
                    $query[$querynum++] = "UPDATE reminder SET oggetto='".$valori['edit_oggetto']."', link='".$valori['edit_link']."', mail='".$valori['reminder_mail']."', descr='".$valori['reminder_descr']."' WHERE id=".$valori['selobj'];

                    //myprint_r($query);
                    //die("w");
                    $backpage = "reminderpopup.php?selobj=".$valori['selobj'];
                    $diretto=1;
                    break;
      case "REMINDER".FASEINS:
                    $query[$querynum++] = "INSERT INTO reminder(dataora, oggetto, descr, link, mail, attivo, idutente, idgruppo, trashed) VALUES (
                      '".$valori['nuovasdt']."', '".$valori['edit_oggetto']."', '".$valori['reminder_descr']."', '".$valori['edit_link']."',
                      '".$valori['reminder_mail']."', 1, ".$_SESSION['user_id'].", 0, 0)";

                    //myprint_r($query);
                    //die("w");
                    $backpage = "reminderpopup.php?nop=1";
                    $takeid=$querynum-1;
                    $diretto=1;
                    
                    break;
      case "REMINDER".FASETERMINA:
                    $query[$querynum++] = "UPDATE reminder SET attivo=0 WHERE id=".$valori['selobj'];

                    //myprint_r($query);
                    //die("w");
                    $backpage = "reminderpopup.php?selobj=".$valori['selobj'];
                    $diretto=1;
                    break;
                    
                                       
      default:
									echo "<H2>Error</H2><BR><TABLE BORDER=1><TR><TD>[1]</TD><TD>$codicepagina</TD></TR>
                  <TR><TD>[2]</TD><TD>$cmd</TD></TR>
                  </TABLE>";
									$backpage = "";
									$diretto=0;
									$errore = "Error 1000";
									/*
                  <TABLE>
                  <TR><TD>[1]CODICEPAGINA</TD><TD>$codicepagina</TD></TR>
                  <TR><TD>[2]FASE</TD><TD>$cmd</TD></TR>
                  </TABLE>
                  */
          //"ERRORE";

      }
//	$result =mysql_query($query);
	//BLOCCO EXECUTE

	foreach($query as $keyquery => $curquery) {
		if (!$errore) {
				if (APP_DEBUG==1) {
						?><HR><?
						print $curquery;
						?><HR><?
				} else {
						//NON IN DEBUG
						//myprint_r($curquery);
						if (count($vetinsid)>0) {
              $curquery =  strtr($curquery,$vetinsid);
            }
						
            mysql_query($curquery) or $errore = _MSGDBQUERYKOSAVE_."<BR><SPAN STYLE='font-size:small;'>QN:$keyquery."-".$curquery</SPAN>";
						$vetinsid["###INSID_".$keyquery."###"] = mysql_insert_id(); 
						
            if ($takeid==$keyquery) {
              $lastinsertid = mysql_insert_id();
              $backpage = $backpage . "&selobj=".$lastinsertid."#SELOBJ";
            }
		//$result =mysql_query($curquery) or $errore = "Non &egrave; stato possibile memorizzare i dati: riprovare pi tardi.<BR><SPAN STYLE='font-size:small;'>QN:$keyquery</SPAN>";
		
						//INSERIMENTO NELLA TABELLA LOG
						//$log="INSERT INTO mas_log (sql,ts,idOrganization,us) VALUES ('".addslashes($curquery)."',now(),".$_SESSION ['IdOrganization'].",".$_SESSION['UserID'].")";
		
					//	$ob_mysql->ExecuteQuery($log) or die("query log error:$log"); //88.35.247.178
				}
		}
	}
	//$ob_mysql->ExecuteQuery("UNLOCK TABLES");
	$msgok = _MSGDBQUERYOKSAVE_;
	
	?>
	<head>
  <title><?=_TITDBQUERY_?></title>
  <meta name="GENERATOR" content="Dev-PHP 2.2.2">
  <?
  	if($diretto==1 && APP_DEBUG=="0" && !$errore && $noback!=1) {print "<meta http-equiv=\"refresh\" content=\"0;url=$backpage\">";}
  ?>
  <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15">
  <link media="screen" type="text/css" rel="StyleSheet" href="../css/general.css">
  <style>
    body {font-family: Verdana,Arial, sans-serif;}
    .loginform {
      background-color: #8BA4DF;
      color: white;
      font-size: 12px;
      font-weight: bold;
      border: 1px solid #333;
      -moz-border-radius: 5px;
			}
    .header {
      padding: 0;
      margin: 0;
      background-color: #4F659F;
      background-image: url(../img/edit_user.gif);
      background-position: 2px;
      background-repeat: no-repeat;
      height: 37px;
      font-variant: small-caps;
      border-bottom: 1px solid #000000;
			}
    .loginform .header .header-text {
      margin-left: 54px;
      font-size: 14px;
			}
    a, a:visited { text-decoration:none; color: #000; background-color: #FFF; border: 1px solid #444; padding: 3px; font-size: 12px; }
    a:hover,a:focus { background-color: #AEB5CA; }
  </style>
	</head>
	<BODY>
	<br>
	<table class="loginform" align="center" cellpadding="4" cellspacing="0" border="0" style="border: 1px solid #000000;">
	  <tbody>
	    <tr>
	      <td class="header" rowSpan="1" colspan="2" style=""><div class="header-text"><?=_TITDBQUERY_?></div></td>
	    </tr>
	    <tr>
	      <td></td>
	      <td></td>
	    </tr>
	    <tr>
	      <td width="340" align="center" nowrap="true" height="50">
	      <?php if ($errore) echo $errore; else echo $msgok;?>
	      </td>
	    </tr>
	<?php if ($errore) { ?>
	    <tr>
	      <td align="center" height="40" valign="middle"><a href="javascript: history.back();"><?=_BTNDBQUERYBACK_?></a></td>
	    </tr>
	<?php }
	else { 
				if ($diretto==0 && APP_DEBUG=="0" && $noback!=1) {
						?>
				    <tr>
				      <td align="center" height="40" valign="middle"><a href="<?=$backpage?>"><?=_BTNDBQUERYGO_?></a></td>
				    </tr>
				<?
				}
				?>
	<?php } ?>
	  </tbody>
	</table>
	</body>
	</html>
