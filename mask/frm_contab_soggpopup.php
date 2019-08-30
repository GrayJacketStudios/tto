<?php
  $pagever = "1.0";
  $pagemod = "22/01/2011 16.54.48";
  require_once("form_cfg.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
  <link rel="stylesheet" type="text/css" href="../css/main.css" />
  <STYLE>
   .opt_individuale {
      background-color:#5FE4FF;
      text-align:center;
   }
   .opt_impresa {
      background-color:#C1FF5F;
      text-align:center;
   }
   .tr_evid:hover {
    background-color:red;
   }
   .tr_evid {
    border-bottom:1px solid #DCDCDC;
   }
  </STYLE>
</head>
<body>
<?php
  include ("iniziopagina.php");

  //CARICAMENTO VALORI PER EDIT
  define("FILE_CORRENTE", "frm_contab_soggpopup.php");

  //$cmd = array();
  $cmd = array();
  $cmd['comando'] = $_REQUEST['cmd'];
  $cmd['jslb'] = $_REQUEST['jslb']; //JS-LOADBANCHE

  $selobj = $_REQUEST['selobj'];
  $cmd['src1'] = $_REQUEST['src1'];
  $cmd['idtipo'] = $_REQUEST['idtipo'];
  
  $ids = "-1, ";
  
  if ($cmd['idtipo']==0 || $cmd['idtipo']==1) {
    $query = "SELECT customer.id as customer_id, customer.nome as customer_nome, customer.apellido as customer_apellido,
          customer.posizione as posizione, customer.ragsoc as ragsoc, customer.rut_ditta as rut_ditta, customer.rut_persona as rut_persona,
          customer.giro as customer_giro, customer.legale_nome as legale_nome, customer.legale_rut as legale_rut, customer.addr_pri as addr_pri,
          customer.addr_work as addr_work, customer.tel_pri as tel_pri, customer.tel_lavoro as tel_lavoro, customer.tel_mobile as tel_mobile,
          customer.mail1 as customer_mail1, customer.mail2 as customer_mail2, customer.mail3 as customer_mail3,customer.fatturaz as fatturaz,
          customer.seg_nome as seg_nome, customer.seg_foto as seg_foto, customer.seg_mail as seg_mail, customer.fantasia as fantasia, customer.web as customer_web,
          customer.skype as customer_skype, customer.fb as customer_fb, customer.codsence as customer_codsence, customer.note as customer_note, customer.impresa as customer_impresa, 
          customer.fatturaz_rut as fatturaz_rut, customer.fatturaz_giro as fatturaz_giro, customer.fatturaz_ragsoc as fatturaz_ragsoc,
          customer.stato as customer_stato, customer.stato2 as customer_stato2, customer.rubro as rubro
          
          FROM customer
          WHERE customer.stato=1 AND customer.trashed<>1
          
          AND (customer.apellido like '%".$cmd['src1']."%' OR customer.nome like '%".$cmd['src1']."%' OR customer.posizione like '%".$cmd['src1']."%'
              OR customer.ragsoc like '%".$cmd['src1']."%' OR customer.rut_ditta like '%".$cmd['src1']."%' OR customer.rut_persona like '%".$cmd['src1']."%' 
              OR customer.giro like '%".$cmd['src1']."%' OR customer.legale_nome like '%".$cmd['src1']."%' OR customer.legale_rut like '%".$cmd['src1']."%' 
              OR customer.addr_pri like '%".$cmd['src1']."%' OR customer.addr_work like '%".$cmd['src1']."%' OR customer.tel_pri like '%".$cmd['src1']."%' 
              OR customer.tel_lavoro like '%".$cmd['src1']."%' OR customer.tel_mobile like '%".$cmd['src1']."%' OR customer.mail1 like '%".$cmd['src1']."%' 
              OR customer.mail2 like '%".$cmd['src1']."%' OR customer.codsence like '%".$cmd['src1']."%' OR customer.rubro like '%".$cmd['src1']."%' )
              
          ORDER BY customer.ragsoc, customer.impresa DESC, customer.nome, customer.apellido";
    
    
    $result = mysql_query($query) or die ("Error 1.1");
    
    $curgruppo=1;//clienti
    
    while ($line = mysql_fetch_assoc($result)) {
      $chiave = $line['customer_id'];
      
      $vetsogg[$curgruppo][$chiave]['label'] = $line['customer_nome']." ".$line['customer_apellido']." ".$line['ragsoc'];
      $vetsogg[$curgruppo][$chiave]['tipo'] = $line['customer_impresa'];
      
      $ids .= $chiave.", ";
    }
  }
  
  if ($cmd['idtipo']==0 || $cmd['idtipo']==2) {
    $query = "SELECT fornitore.id as fornitore_id, fornitore.nome as fornitore_nome, fornitore.apellido as fornitore_apellido,
          fornitore.posizione as posizione, fornitore.ragsoc as ragsoc, fornitore.rut_ditta as rut_ditta, fornitore.rut_persona as rut_persona,
          fornitore.giro as fornitore_giro, fornitore.legale_nome as legale_nome, fornitore.legale_rut as legale_rut, fornitore.addr_pri as addr_pri,
          fornitore.addr_work as addr_work, fornitore.tel_pri as tel_pri, fornitore.tel_lavoro as tel_lavoro, fornitore.tel_mobile as tel_mobile,
          fornitore.mail1 as fornitore_mail1, fornitore.mail2 as fornitore_mail2, fornitore.mail3 as fornitore_mail3,fornitore.fatturaz as fatturaz,
          fornitore.seg_nome as seg_nome, fornitore.seg_foto as seg_foto, fornitore.seg_mail as seg_mail, fornitore.fantasia as fantasia, fornitore.web as fornitore_web,
          fornitore.skype as fornitore_skype, fornitore.fb as fornitore_fb, fornitore.codsence as fornitore_codsence, fornitore.note as fornitore_note, fornitore.impresa as fornitore_impresa, 
          fornitore.fatturaz_rut as fatturaz_rut, fornitore.fatturaz_giro as fatturaz_giro, fornitore.fatturaz_ragsoc as fatturaz_ragsoc, fornitore.idcategoria as idcategoria,
          fornitore.categ2 as categ2, fornitore.stato as fornitore_stato, stati_categ.valore as stati_categ_valore,
          stati_categ.label as stati_categ_label, stati_categ.img as stati_categ_img
          
          FROM fornitore LEFT JOIN stati as stati_categ ON fornitore.idcategoria=stati_categ.valore AND stati_categ.idgruppo=3
          WHERE fornitore.trashed<>1 AND fornitore.stato=1 AND
          
          (fornitore.apellido like '%".$cmd['src1']."%' OR fornitore.nome like '%".$cmd['src1']."%' OR fornitore.ragsoc like '%".$cmd['src1']."%' OR fornitore.fantasia like '%".$cmd['src1']."%')
          
          ORDER BY stati_categ.ordine, categ2, fornitore.ragsoc, fornitore.impresa DESC, fornitore.nome, fornitore.apellido";
    
    $result = mysql_query($query) ;
    
    $curgruppo=2;//fornitori
    
    while ($line = mysql_fetch_assoc($result)) {
      $chiave = $line['fornitore_id'];
      
      $vetsogg[$curgruppo][$chiave]['label'] = $line['fornitore_nome']." ".$line['fornitore_apellido']." [".($line['fantasia']==""? $line['ragsoc']:$line['fantasia'])."]";
      $vetsogg[$curgruppo][$chiave]['tipo'] = $line['fornitore_impresa'];
      $vetsogg[$curgruppo][$chiave]['categ'] = $line['stati_categ_label'].($line['categ2']=="" ? "":" - ".$line['categ2']);
      $vetsogg[$curgruppo][$chiave]['imgcateg'] = $line['stati_categ_img'];
      
      $ids .= $chiave.", ";
    }    
  }
  
  if ($cmd['idtipo']==0 || $cmd['idtipo']==3) {
    $query = "SELECT id, nome, nickname
              FROM docente
              WHERE trashed<>1 AND attivo=1 AND 
          
          (nome like '%".$cmd['src1']."%' OR nickname like '%".$cmd['src1']."%')
          
          ORDER BY nome";
    
    $result = mysql_query($query) or die ("Error 1.3");
    
    $curgruppo=3;//docenti
    
    while ($line = mysql_fetch_assoc($result)) {
      $chiave = $line['id'];
      
      $vetsogg[$curgruppo][$chiave]['label'] = $line['nome']." (".$line['nickname'].")";
      $vetsogg[$curgruppo][$chiave]['tipo'] = 3;
      
      $ids .= $chiave.", ";
    }    
  }
  
  //C/C dei soggetti READY per soggunico
  
  $query = "SELECT banca.nomebanca as nomebanca, ccbanca.id as ccbanca_id, tiposoggetto, idsoggetto, ccbanca.codice as ccbanca_codice
            FROM ccbanca INNER JOIN banca ON ccbanca.idbanca=banca.id
            WHERE idsoggetto IN (".substr($ids,0,-2).")";
  $result = mysql_query($query) or die ("Error 3.1");
  while ($line = mysql_fetch_assoc($result)) {
    $tiposoggetto = $line['tiposoggetto'];
    $idsoggetto = $line['idsoggetto'];
    
    if (isset($vetsogg[$tiposoggetto][$idsoggetto])) {
      $vetsogg[$tiposoggetto][$idsoggetto]['banche'][$line['ccbanca_id']]['ccbanca_id']=$line['ccbanca_id'];
      $vetsogg[$tiposoggetto][$idsoggetto]['banche'][$line['ccbanca_id']]['nomebanca']=$line['nomebanca'];
      $vetsogg[$tiposoggetto][$idsoggetto]['banche'][$line['ccbanca_id']]['ccbanca_codice']=$line['ccbanca_codice'];
    }
  } 
  
  //CC AZIENDA
  $query = "SELECT banca.nomebanca as nomebanca, ccbanca.id as ccbanca_id, tiposoggetto, idsoggetto, ccbanca.codice as ccbanca_codice
            FROM ccbanca INNER JOIN banca ON ccbanca.idbanca=banca.id
            WHERE tiposoggetto=9999";
  $result = mysql_query($query) or die ("Error 3.2");
  $bancaazienda="<OPTION VALUE='0'>"._OPZNESSUNCONTOBANCA_."</OPTION>";
  while ($line = mysql_fetch_assoc($result)) {
    $bancaazienda .= "<OPTION VALUE='".$line['ccbanca_id']."'>".$line['nomebanca']."-".$line['ccbanca_codice']."</OPTION>";
  } 
  
      
    print "<BR><H1>"._SOGGETTI_."</H1>";
    print "<BR>";
    
    //form x search
    print "<FORM NAME='ricerca' METHOD=POST ACTION='".FILE_CORRENTE."'><INPUT TYPE='hidden' NAME='selobj' VALUE='".$selobj."'>";
    print "<INPUT TYPE='hidden' NAME='cmd' VALUE='".FASESEARCH."'>";
    print "<INPUT TYPE='hidden' NAME='jslb' VALUE='".$cmd['jslb']."'>";
    
    print "<TABLE align='center' class='inputform' cellpadding='2' cellspacing='0' width='80%'>";
    
    //select tutti, cli, forn
    //src1
    //lista
    
    print "<TR>";
    print "<TD>"._LBLTIPOCLIENTE_."</TD>";
    print "<TD>";
    print "<SELECT SIZE=1 NAME='idtipo' onChange=updlista()>";
  
      $query = "SELECT id, label, valore FROM stati WHERE idgruppo=12 ORDER BY ordine";
      $result = mysql_query ($query) or die ("Error_2.1");
      while ($line = mysql_fetch_assoc($result)) {
        print "<OPTION VALUE='".$line['valore']."' ".($line['valore']==$cmd['idtipo'] ? " SELECTED ":"").">".convlang($line['label'])."</OPTION>";
      }
      print "</SELECT>";
    print "</TD>";
    print "</TR>";
    
    print "<TR>";
    print "<TD>"._LBLRICERCA_."</TD>";
    print "<TD>";
    print "<INPUT TYPE='text' SIZE='30' NAME='src1' VALUE='".$cmd['src1']."'>";
    print "<IMG SRC='../img/ricerca.png' BORDER=0 onClick=updlista();>";
    print "</TD>";
    print "</TR>";
    
    print "<TR><TD COLSPAN=2>";
    print "<TABLE CELLSPACING=0 CELLPADDING=0 WIDTH=100%>";
    //clienti
    $vet_tipoclienti[1]['val'] = 1;
    $vet_tipoclienti[1]['label'] = _LBLMOSTRACLIENTI_;
    
    $vet_tipoclienti[2]['val'] = 2;
    $vet_tipoclienti[2]['label'] = _LBLMOSTRAFORNITORI_;
    
    $vet_tipoclienti[3]['val'] = 3;
    $vet_tipoclienti[3]['label'] = _LBLMOSTRAPROFESSORI_;
    $curcateg="-----";
    foreach ($vet_tipoclienti as $key_tipo => $cur_tipo) {
      $tipoclienti = $cur_tipo['val'];
      
      if (count($vetsogg[$tipoclienti])>0) {
        print "<TR><TD COLSPAN=3 STYLE='height:45px;border-top:3px solid #0037AF;border-bottom:1px solid #0037AF;font-size:18px;'>".$cur_tipo['label']."</TD></TR>";
        foreach ($vetsogg[$tipoclienti] as $key =>$cur) {
          //TR CATEG
          if ($tipoclienti==2) {
            //provedores -> stampa categoria non selezionabile
            if ($curcateg!=$cur['categ']) {
              print "<TR STYLE='margin-top:3px;margin-bottom:3px;'><TD COLSPAN=3 STYLE='height:30px; border-bottom:1px dashed #0037AF;font-size:14px;'><IMG SRC='../img/".$cur['imgcateg']."' WIDTH=20 HEIGHT=20 BORDER=0>&nbsp;&nbsp;".$cur['categ']."</TD></TR>";
              $curcateg=$cur['categ'];
            }
          }
          //banche
          if ($tipoclienti==1) {
            $appbanca = $bancaazienda;
          } else {
            $appbanca="<OPTION VALUE='0'>"._OPZNESSUNCONTOBANCA_."</OPTION>";
            $appsel = " SELECTED ";
            foreach ($cur['banche'] as $keybanche => $curbanche) {
              $appbanca .= "<OPTION VALUE='".$curbanche['ccbanca_id']."' $appsel>".$curbanche['nomebanca']."-".$curbanche['ccbanca_codice']."</OPTION>";
              $appsel="";
            }
          }
          
          print "<TR CLASS=tr_evid onClick=selelemento(".$tipoclienti.",".$key.",\"".fullescape($cur['label'])."\",\"".fullescape($appbanca)."\")>";
          print "<TD><IMG SRC='../img/next.png'></TD>";
          print "<TD>";
            switch ($tipoclienti) {
              case 1://customer
                    switch ($cur['tipo']) {
                      case 1:
                            //impresa
                            $img = "industria20.png";break;
                      default:
                            $img = "persone20.png";break;
                    }
                    break;
              case 2://provedores
                      $img = $cur['imgcateg'];
                    break;
              case 3://professores
                      $img = "professori_20.png";break;
                    break;
            }
            /*switch ($cur['tipo']) {
              case 1:
                  //impresa
                  $img = "industria20.png";break;
              case 3:
                  //professore
                  $img = "professori_20.png";break;
              default:
                  $img = "persone20.png";break;
            }*/
            print "<IMG SRC='../img/$img' WIDTH=20 HEIGHT=20 BORDER=0>";
          print "</TD>";
          print "<TD>".$cur['label']."</TD>";
          print "</TR>";
        }
      }
    }
    
    
    /*
    //fornitori
    $tipoclienti= 2;
    print "<TR><TD COLSPAN=3>".."</TD></TR>";
    foreach ($vetsogg[$tipoclienti] as $key =>$cur) {
      print "<TR CLASS=tr_evid onClick=selelemento(".$tipoclienti.",".$key.",\"".fullescape($cur['label'])."\")>";
      print "<TD><IMG SRC='../img/next.png'></TD>";
      print "<TD>";
        if ($cur['tipo']==1) {
          //impresa
          $img = "industria20.png";
        } else {
          $img = "persone20.png";
        }
        print "<IMG SRC='../img/$img' BORDER=0>";
      print "</TD>";
      print "<TD>".$cur['label']."</TD>";
      print "</TR>";
    }
    */
    print "</TABLE></TD></TR>";
    print "</TABLE>";
    print "</FORM>";

  include ("finepagina.php");
?>
<SCRIPT>
function updlista() {
  document.ricerca.submit();
}

function selelemento(tipocliente, idcliente, cliente_labsogunico, banche)  {
      opener.document.caricamento.tipocliente.value=tipocliente;
      opener.document.caricamento.idcliente.value=idcliente;
      opener.document.caricamento.cliente_labsogunico.value=unescape(cliente_labsogunico);
      
      if (document.ricerca.jslb.value=="1") {
        var blocco_banche = opener.document.getElementById("idcc");
        blocco_banche.innerHTML = unescape(banche);
      }
      this.close();
}


function btnsalva() {
  document.caricamento.submit();
  self.close();
}

</SCRIPT>
<BR>
<BR>
</body>
</html>
