<?php
  session_start();
  
  //test timezone
  date_default_timezone_set('Europe/Rome');  //   //America/Santiago
  ini_set('max_execution_time',300);
  
  
  error_reporting(E_ERROR);
  
  if ($_REQUEST['setNuovaLingua']!="") {
   $_SESSION['setNuovaLingua'] = $_REQUEST['setNuovaLingua']; 
  }
  $newlingua = $_SESSION['setNuovaLingua'];
  switch ($newlingua) {
    case "it":
              require_once($path."../lang/it.php");
              break;
    case "en":
              require_once($path."../lang/en.php");
              break;
    case "es":
              require_once($path."../lang/es.php");
              break;
    default:
              require_once($path."../lang/it.php");
              break;
  }
  
  
  
  require_once($path."../cgi/cfg.php");
  require_once($path."../cgi/dbconnect.php");
  
  
  
  
//  require_once("custom.php");
//	require_once("rootpath.php");
//  require_once("../cgi/obj_MYSql.php");  
//  require_once ($PATH."components/PageInit_Maschere.php");
//  $ob_mysql =new OB_MYSql();
  
  
	
	if (SCRIPT_AUTOMATICO!="2102") {
  	$relogin = ( isset($_SESSION['user_id']) ? FALSE : TRUE );
  	if ( $relogin ) {
  	  header("location: /relogin.html"); 
  	}
  }


//###################################################
//       VALORI PREDEFINITI
//###################################################

//codice disegno matrice
//$tt_orai = (8-1)*60*60;
//$tt_oraf = (22-1)*60*60;

$tt_orai = mktime(7, 0, 0, 1, 1, 1970);
$tt_oraf = mktime(24, 0, 0, 1, 1, 1970);

$numerogg = 7;
$tt_step = 30*60;//30 min
//$tt_noday[0] = 1; //0 domenica - 6 sabato

//valori da setup
$setup = array();
$setup['form_rowcount']=5;
$setup['form_btnpage']=15;



define ("FASEINS", "2");
define ("FASEMOD", "3");
define ("FASEDEL", "4");
define ("FASESEARCH", "5");

define ("FASECLASSE", "100");
define ("FASEADDSTUDENTE", "101");
define ("FASEDELSTUDENTE", "102");
define ("FASEFINESTUDENTE", "103");
define ("FASEASSDOCENTE", "104");
define ("FASEREMDOCENTE", "105");
define ("FASEPREFDAY", "106");
define ("FASEPREFDAYINS", "107");
define ("FASESUPPLENZA", "108");
define ("FASECONFSUPPLENZA", "109");
define ("FASEDELSAMEDAY", "110");
define ("FASEREFERSTUDENTE", "111");
define ("FASERESTORESTUDENTE", "112");
define ("FASEPREFERITO", "113");
define ("FASECREASTUDENTE",114);
define ("FASESETTASTATO",115);
define ("FASEDELFILE",116);
define ("FASESETUPDPAGOREALE",117);
define ("FASERESETUPDPAGOREALE",118);
define ("FASEREQUERY",119);
define ("FASEDELLOG",120);
define ("FASESETTALINGUA",121);
define ("FASEUPDDATA",122);
define ("FASETERMINA",123);





define ("FIXPASSWORD", "v#ib-.vi@ipqnf0923n03b2&4vo9203+n");

define ("LEVEL_NORMAL",10);
define ("LEVEL_VICEBOSS",20);
define ("LEVEL_BOSS",30);

define ("BTN_PROF_SELECTED","STYLE=\"background-color:#FF4800;font-weight:bolder;color:#FFFFFF;border:1px solid #FFFFFF;\"");


define ("FASEADD_NODEFVUL", "8");
define ("FASEUPD_RID", "9");
define ("FASEDENY", "10");
define ("FASEALLOW", "11");
define ("FASECHANGEATTUAZIONE","12");
define ("FASEASSOCIA", "13");
define ("FASEDISASSOCIA", "14");
define ("FASESUSPEND", "15");
define ("CREADBBACKUP","16");
define ("TEXTAREA_ROWS", "3");
define ("TEXTAREA_COLS", "70");
define ("FILE_DBQUERY", "./dbquery.php");
define ("HELPICON", "<IMG SRC='".$PATH."img/help.gif' BORDER=0>");
define ("STYLE_RIGABIANCA", " STYLE='background-color:#888888;' ");
define ("STYLE_RIGAORANGE", " STYLE='background-color: #FFC266;' ");
define ("STYLE_RIGAGREEN", " STYLE='background-color: #9ABF5F;' ");
define ("STILERIGACANCELLATA", "background-color:#FF0000;");
define ("IMG_LENTE", "<IMG SRC='".$PATH."img/dettagli.gif' BORDER=0>");
define("RIS", 0);
define("DIS", 1);
define("INT", 2);
define("RIGA_PARI", "#547EDF");
define("RIGA_DISPARI", "#8BA4DF");
define("RIGA_SELEZIONATA", "#FFD68F");

define("LOGFILE","/var/securebox/log/securebox.log");


define("CORRENTE",1);
define("POTENZIALE",2);
define("VECCHIO",3);

define("DACONTATTARE",4);
define("CONTATTATO",5);
define("INTERESSATO_INCORSO",6);
define("NONINTERESSATO",7);
define("NOACCETTA",8);
define("INFUTURO",9);



/* 
//		COLORI PASTELLO
define("FONDO_CRITICO", "#FF6868");
define("FONDO_ALTO", "#FF9BFF");
define("FONDO_MEDIO", "#FFFF8C");
define("FONDO_BASSO", "#84FF85");
define("FONDO_NULLO", "#D4FFD6");
*/

//		COLORI INTENSI
define("FONDO_CRITICO", "#FF1F1F");
define("FONDO_ALTO", "#FF0FFA");
define("FONDO_MEDIO", "#FDFF0F");
define("FONDO_BASSO", "#1CFF0F");
define("FONDO_NULLO", "#0FFFFA");



define("ATTIVALOG",30);
define("DISATTIVALOG",31);



function connessione() {
$link = mysql_connect('localhost','root','root');
$db_selected = mysql_select_db('securebox', $link); 
}


//include ("dblink.php");

function getnewid($tabella,$campo='id') {
    $query = "SELECT max($campo)+1 as nuovoid FROM ". $tabella;
    //$result = $ob_mysql->ExecuteQuery($query);
    $result = mysql_query($query);
    $line = mysql_fetch_assoc($result);
    if ($line['nuovoid']=="") {$line['nuovoid']=1;}
    return $line['nuovoid'];
    //return $result[0]['nuovoid'];
    }

function eT_getnewnum($codice, $tabella="numeratori") {
  $query = "SELECT codice, num FROM ".$tabella." WHERE codice='".$codice."'";
  $result = mysql_query($query);
  $line = mysql_fetch_assoc($result);
  
  if ($line['codice']=="") {
    //codice non presente in numeratore, lo creo
    $query = "INSERT INTO ".$tabella." (codice, num, note) VALUES ('".$codice."', 1,'')";
    $valore=1;
  } else {
    if ($line['num']=="") {
      $query = "UPDATE ".$tabella." SET num=1 WHERE codice='".$codice."'";
      $valore=1;
    } else {
      $valore = $line['num'] + 1;
      $query = "UPDATE ".$tabella." SET num=".$valore." WHERE codice='".$codice."'";
    }
  }
  mysql_query($query);
  
  return $valore;
}

function printif($cond, $val1, $val2) {
    if ($cond=="1") {return $val1;} else {return $val2;}
    }

function codiceform($pagina) {
    switch ($pagina) {
        case "timetable.php":
                               $codice = "TIMETAB";
                               break;
        case "frm_docenti.php":
        case "frm_docenti_addpopup.php":
                               $codice = "DOCENTI";
                               break; 
        case "frm_docenti_lingua.php":
                               $codice = "DOCENTILINGUA";
                               break;              
         case "frm_clienti.php":
         case "frm_clienti_popup.php":
                               $codice = "CLIENTI";
                               break;      
         case "frm_fornitori.php":
         case "frm_fornitori_popup.php":
                               $codice = "FORNITORI";
                               break;                               
        case "frm_studenti.php":
        case "frm_studenti_popup.php":
                               $codice = "STUDENTI";
                               break;
        case "frm_classi.php":
        case "frm_classi_addpopup.php":
        case "frm_classi_enrollpopup.php":
                               $codice = "CLASSI";
                               break;
        case "frm_classi2.php":
        case "frm_pstudio.php":
        case "frm_pstudio_addpopup.php":
        case "frm_pstudio_evtpopup.php":                       
                               $codice = "PSTUDIO";
                               break;
        case "frm_user.php":
                               $codice = "USER";
                               break;
        case "frm_contatticust_popup.php":
                               $codice = "CONTATTICUST";
                               break;
        case "frm_contattiforn_popup.php":
                               $codice = "CONTATTIFORN";
                               break;	    
        case "frm_clientistato_popup.php":
                               $codice = "CLIENTISTATO";
                               break;
        case "frm_docenti_statopopup.php":
                               $codice = "DOCENTISTATO";
                               break;
        case "frm_docenti_filepopup.php":
                               $codice = "DOCENTIFILE";
                               break;
        case "frm_contab_addpopup.php":
        case "frm_contab_dtpagopopup.php":
        case "frm_contabshow.php":
                               $codice = "CONTABILITA";
                               break;
        case "frm_contab_anagbanchepopup.php":
                               $codice = "BANCA";
                               break;
        case "frm_banchecc_addpopup.php":
                               $codice = "CCBANCA";
                               break;
        case "frm_contab_saldo.php":
        case "frm_contab_saldopopup.php":
                               $codice = "SALDO";
                               break;
        case "frm_lingua.php":
                               $codice = "LINGUA";
                               break;
        case "reminderpopup.php":
                               $codice = "REMINDER";
                               break;
                                                                                         												                                
        }        
     return $codice;


    }

function conv_textarea($str,$toprint=0) {
  $convertire = array(
                      "\n"=>"<BR>"
  );
  
  if ($toprint==1) {
    $convertire=array_flip($convertire);
  }
  
  $app = strtr($str,$convertire);
  return $app;
}

function clean_par($array_valori) {
	$convertire = array(
												"à" => "&agrave;",
												"è" => "&egrave;",
												"é" => "&eacute;",
												"ì" => "&igrave;",
												"=" => "&ograve;",
												"ù" => "&ugrave;",
												"'" => "&rsquo;",
												"\"" => "&quot;",
												"&#8217;" => "&rsquo;",
												"&#039;" => "&rsquo;",
												"â??" => "&rsquo;",
												"&#8220;" => "&quot;",
												"&#8221;" => "&quot;",
												//"\n" => "<BR>",
												//"\r\n" => "<BR>",
												"<BR>" => "\n",
												"+ " => "&agrave;",
												"+" => "&agrave;",
												"+¨" => "&egrave;",
												"+¬" => "&igrave;",
												"+Ý" => "&ograve;",
												"+Ý" => "&ugrave;",
												"-Ý" => "&deg;",
												"Ý" => "&deg;",
												"â€™" => "&rsquo;",
	);
  //Ripulitura array valori
  foreach($array_valori as $key => $value) {
    //1Ý CONVERSIONE - CARATTERI DEFINITI SOPRA
		$array_valori[$key] = strtr($value, $convertire);
		
		//2Ý CONVERSIONE - AUTOMATICA
		//$array_valori[$key] = htmlspecialchars($value, ENT_QUOTES);
  }
  return $array_valori;
}

/*function ripulisci($vettore) {
		foreach($vettore as $key_clean=>$cur_clean) {
				$vettore[$key_clean] = clean_par($cur_clean);
		}
		return $vettore;
}*/

//----------------AGG. Davide 19/11/2006------------------------------------





function conv_x_ezpdf($stringa) {
	$convertire = array(
												"&agrave;" => "à",
												"&egrave;" => "è",
												"&eacute;" => "é",
												"&igrave;" => "ì",
												"&ograve;" => "=",
												"&ugrave;" => "ù",
												"&rsquo;" => "'",
												"&#8217;" => "'",
												"&quote;" => "\"",
												"&quot;" => "\"",
												"&#8220;" => "\"",
												"&#8221;" => "\"",
												"&deg;"=> "Ý",
												"+ " => "à",
												"+¨" => "è",
												"+¬" => "ì",
												"+Ý" => "=",
												"+Ý" => "ù",
												"-Ý" => "Ý",
												"<LI STYLE=\"list-style: disc;\">" => "  - ",
												"<LI STYLE=\"list-style: square;\">" => "    - ",
												"<UL>" => "",
												"</UL>" => "",
												"<CENTER>" => "",
												"</CENTER>" => "",
												"&nbsp;" => " ",
												"<BR>" => "\n",
												"â€™" => "'",
												"&Agrave;"=>"+",
												"&Egrave;"=>"+",
												"&Ugrave;"=>"+",
												"&Ograve;"=>"Ò",
												"&Igrave;"=>"Ý",
												"+¢â‚¬-¦"=>"...",
												"+¢â‚¬-¢"=>"\n   - "
		);
    //$convertiti = array("&agrave;","&egrave;","&eacute;","&igrave;","&ograve;","&ugrave;","&rsquo;","&quote;");
    //$array_valori[$key] = str_replace($convertire, $convertiti, $value);
    $stringa = strtr($stringa, $convertire);
    
  	$trans = array(
   chr(225)=>'á', chr(193)=>'-', chr(232)=>'è', chr(200)=>'+', chr(239)=>'ï', chr(207)=>'Ï',
   chr(233)=>'é', chr(201)=>'+', chr(236)=>'ì', chr(204)=>'Ý', chr(237)=>'í', chr(205)=>'-',
   chr(181)=>'¾', chr(165)=>'+', chr(242)=>'=', chr(210)=>'Ò', chr(243)=>'ó', chr(211)=>'Ó',
   chr(248)=>'ø', chr(216)=>'Ø', chr(185)=>'š', chr(169)=>'Š', chr(187)=>'', chr(171)=>'',
   chr(250)=>'ú', chr(218)=>'+', chr(249)=>'ù', chr(217)=>'+', chr(253)=>'ý', chr(221)=>'Ý',
   chr(190)=>'ž', chr(174)=>'Ž', chr(180)=>"'"
   );
   
   return $stringa;

}

function converti_x_xml ($in) {
$convertire = array("<BR>" => "%BR%",
  									"&Agrave;" => "%Agrave;",
										"&Egrave;" => "%Egrave;",
										"&Eacute;" => "%Eacute;",
										"&Igrave;" => "%Igrave;",
										"&Ograve;" => "%Ograve;",
										"&Ugrave;" => "%Ugrave;",
										"&agrave;" => "%agrave;",
										"&egrave;" => "%egrave;",
										"&eacute;" => "%eacute;",
										"&igrave;" => "%igrave;",
										"&ograve;" => "%ograve;",
										"&ugrave;" => "%ugrave;",
										"&rsquo;" => "%rsquo;",
										"&#8217;" => "%rsquo;",
										"&#8220;" => "%quot;",
										"&#8221;" => "%quot;",
										"&quote;" => "%quot;",
										"&quot;" => "%quot;",
										"&nbsp;" => "%nbsp;",
										"&deg;" => "%deg;",
										"<IMG" => "%lt;IMG",
										"jpg>" => "jpg%gt;",
										"\"" => "%quot;",
										"&"=> "%amp;"
  									);
	return strtr($in, $convertire);
}

function converti_x_xml_back ($in) {
$convertire = array("%BR%" => "<BR>",
  									"%Agrave;" => "&Agrave;",
										"%Egrave;" => "&Egrave;",
										"%Eacute;" => "&Eacute;",
										"%Igrave;" => "&Igrave;",
										"%Ograve;" => "&Ograve;",
										"%Ugrave;" => "&Ugrave;",
										"%agrave;" => "&agrave;",
										"%egrave;" => "&egrave;",
										"%eacute;" => "&eacute;",
										"%igrave;" => "&igrave;",
										"%ograve;" => "&ograve;",
										"%ugrave;" => "&ugrave;",
										"%rsquo;" => "&rsquo;",
										"%#8217;" => "&rsquo;",
										"%quote;" => "&quot;",
										"%quot;" => "&quot;",
										"%nbsp;" => "&nbsp;",
										"%deg;" => "&deg;",
										"%lt;IMG" => "<IMG",
										"jpg%gt;" => "jpg>",
										"'" => "&rsquo;",
										"%amp;" => "&"
  									);
	return strtr($in, $convertire);
}
function tipo_campo ($tipo) {
		$tipo = strtolower($tipo);
		$car = "S";
		$pos = strpos($tipo,"char");
		if ($pos === false) {
				$pos = strpos($tipo,"binary");
				if ($pos === false) {
						$pos = strpos($tipo, "blob");
						if ($pos === false) {
								$pos = strpos($tipo, "text");
								if ($pos === false) {
										$pos = strpos($tipo, "datetime");
										if ($pos === false) {
												$car = "N";
										}
								}
						}
				}
		}
		
		return $car;
}

function eT_dt_dritta2reverse($datadritta){
  $giorno = substr($datadritta, 0, 2);
  $mese = substr($datadritta,3,2);
  $anno = substr($datadritta,6,4);
  
  return $anno.$mese.$giorno;
}

function eT_dt_reverse2dritta($datareverse) {
  $anno = substr($datareverse,0,4);
  $mese = substr($datareverse,4,2);
  $giorno = substr($datareverse,6,2);
  
  return $giorno."/".$mese."/".$anno;  
}

function eT_dtora_dritta2reverse($datadritta){
  $giorno = substr($datadritta, 0, 2);
  $mese = substr($datadritta,3,2);
  $anno = substr($datadritta,6,4);
  
  return $anno.$mese.$giorno;
}

function eT_dtora_reverse2dritta($datareverse, $sepaore=":", $sepadtora=" ", $sepadt="/") {
  //tutto attaccato senza separatori
  $anno = substr($datareverse,0,4);
  $mese = substr($datareverse,4,2);
  $giorno = substr($datareverse,6,2);
  $ore = substr($datareverse,8,2);
  $minuti = substr($datareverse,10,2);
  $secondi = substr($datareverse,12,2);
  
  return $giorno.$sepadt.$mese.$sepadt.$anno.$sepadtora.$ore.$sepaore.$minuti.$sepaore.$secondi;  
}

function eT_dt2times ($strdt,$separatore="/") {
  $app = explode($separatore, $strdt);
  
  $gg = intval($app[0]);
  $mm = intval($app[1]);
  $aa = intval($app[2]);
  
  $appdt = mktime(0, 0, 0, $mm, $gg, $aa);
  return $appdt;
}

function eT_ora2times ($strora,$secondi=1,$separatore=":") {
  $app = explode($separatore, $strora);
  
  $hh = intval($app[0]);
  $ii = intval($app[1]);
  if ($secondi==1) {
    $ss = intval($app[2]);
  } else {
    $ss=0;
  }
  
  $appdt = ($hh*60*60) + ($ii*60) + $ss;
  return $appdt;
}

function eT_getStyleValue($stile, $elemento) {
  $s = strpos($stile, $elemento);
  if ($s!==false) {
    $s += strlen($elemento);
    $f = strpos($stile,";",$s);
    if ($f===false) {$f = strlen($stile);}
    $app = substr($stile,$s+1,$f-$s-1);
    return $app;
  }
}

//--------------------------------------------------------------------------------------------------------

function convlang($stringa) {
  global $vetconvlang;
  
  /*
  if (isset($vetconvlang[$stringa])) {
    return $vetconvlang[$stringa];
  } else {
    return $stringa;
  }*/
  $app = strtr($stringa, $vetconvlang);
  return $app;
}

function letter_weekday($wday) {
    switch ($wday) {
      case 0: $str=_DOMENICA_;break;
      case 1: $str=_LUNEDI_;break;
      case 2: $str=_MARTEDI_;break;
      case 3: $str=_MERCOLEDI_;break;
      case 4: $str=_GIOVEDI_;break;
      case 5: $str=_VENERDI_;break;
      case 6: $str=_SABATO_;break;     
    }
    return $str;
}

function letter_mesi($wday) {
    switch ($wday) {
      case 1: $str=_GENNAIO_;break;
      case 2: $str=_FEBBRAIO_;break;
      case 3: $str=_MARZO_;break;
      case 4: $str=_APRILE_;break;
      case 5: $str=_MAGGIO_;break;
      case 6: $str=_GIUGNO_;break;
      case 7: $str=_LUGLIO_;break;
      case 8: $str=_AGOSTO_ ;break;
      case 9: $str=_SETTEMBRE_;break;
      case 10: $str=_OTTOBRE_;break;
      case 11: $str=_NOVEMBRE_;break;
      case 12: $str=_DICEMBRE_;break;     
    }
    return $str;
} 

function eT_combomesi($curmese,$fvalnumero=1, $flblnumero=0) {
  $bufout="";
  
  for($i=1;$i<=12;$i++) {
    $bufout .= "<OPTION VALUE='".($fvalnumero==1?$i:letter_mesi($i))."' ".($i==$curmese?" SELECTED ":"").">".($flblnumero==1?$i:letter_mesi($i))."</OPTION>";
  }
  return $bufout;
}
  
function day_mezzanotte ($dt) {
    $d=date("d",$dt);
    $m=date("m",$dt);
    $y=date("Y",$dt);
    
    return mktime (0,0,0,$m,$d,$y);
}

function myprint_r($v) {
  print "<PRE>";
  print_r($v);
  print "</PRE>";
}

define ("IMMAGINE",1);
define ("TESTO",2);

function tipomime($mime,$tipo) {
  $vet = array();
  switch ($mime) {
    case "text/plain":
          $vet['img'] = "mime-text.png";
          $vet['txt'] = _TIPOFILETESTO_;
          break;
    case "image/jpeg":
    case "image/gif":
    case "image/png":
    case "image/tiff":
          $vet['img'] = "mime-image.png";
          $vet['txt'] = _TIPOFILEIMMAGINE_;
          break;
    case "application/pdf":
          $vet['img'] = "mime-pdf.png";
          $vet['txt'] = _TIPOFILEPDF_;
          break;                    
    default:
          $vet['img'] = "mime-def.png";
          $vet['txt'] = _TIPOFILEDEFAULT_;
          break;
  }
  
  switch ($tipo) {
    case IMMAGINE:
        $ret = $vet['img'];break;
    case TESTO:
        $ret = $vet['txt'];break;        
  }
  return $ret;
}

function valorelivello($livello) {
  switch ($livello) {
    case "R":$livelloval=10;break;
    case "W":$livelloval=20;break;
    case "D":$livelloval=30;break;
  }
  return $livelloval;
}

function checkauth($codice,$livello) {  
  if ($_SESSION['auth_'.$codice]>=valorelivello($livello)) {
    return 1;
  } else {
    return 0;
  }
}

function fullescape($in) 
{ 
  $out = ''; 
  for ($i=0;$i<strlen($in);$i++) 
  { 
    $hex = dechex(ord($in[$i])); 
    if ($hex=='') 
       $out = $out.urlencode($in[$i]); 
    else 
       $out = $out .'%'.((strlen($hex)==1) ? ('0'.strtoupper($hex)):(strtoupper($hex))); 
  } 
  $out = str_replace('+','%20',$out); 
  $out = str_replace('_','%5F',$out); 
  $out = str_replace('.','%2E',$out); 
  $out = str_replace('-','%2D',$out); 
  return $out; 
 }

function eT_strdt2times($strdt) {
  $anno = substr($strdt,0,4);
  $mese = substr($strdt,4,2);
  $giorno = substr($strdt,6,2);
  
  $times = mktime(0, 0, 0, $mese, $giorno, $anno);
  return $times;
}

function eT_strdt2string($strdt,$sepadt="/", $sepahr=":",$ora=0,$sec=0) {
  $anno = substr($strdt,0,4);
  $mese = substr($strdt,4,2);
  $giorno = substr($strdt,6,2);
  if ($ora==1) {
    $hr = substr($strdt,8,2);
    $min = substr($strdt,10,2);
    $secondi = substr($strdt,12,2);
  }
  
  $stringa = $giorno.$sepadt.$mese.$sepadt.$anno;
  if ($ora==1) {
    $stringa .= " ".$hr.$sepahr.$min;
    if ($sec==1) {
      $stringa .= $sepahr.$secondi;
    }
  }
  
  return $stringa;
}

function eT_Rstrdt2string($dt,$sepadt="/") {
  $vetdt = explode($sepadt, $dt);
  
  
  
  $stringa = $vetdt[2].$vetdt[1].$vetdt[0]; 
  $giorno.$sepadt.$mese.$sepadt.$anno;
  if ($ora==1) {
    $stringa .= " ".$hr.$sepahr.$min;
    if ($sec==1) {
      $stringa .= $sepahr.$secondi;
    }
  }
  
  return $stringa;
}

function eT_sdtprint($sdt,$strformato) {
  //dmY H:i:s
  //20110317151700
  $anno = substr($sdt,0,4);
  $mese = substr($sdt,4,2);
  $giorno = substr($sdt,6,2);
  $ora = substr($sdt,8,2);
  $min = substr($sdt,10,2);
  $sec = substr($sdt,12,2);
  
  $tr = array(
    "d"=>$giorno,"m"=>$mese,"Y"=>$anno,"H"=>$ora,"i"=>$min,"s"=>$sec,
    "!d"=>"d","!m"=>"m","!Y"=>"Y","!H"=>"H","!i"=>"i","!s"=>"s"
  );
  
  $app =strtr($strformato,$tr);

  return $app;
  
}

//human date/time to stringdatetime (sdt)
//converte purch‚ data e ora separate da spazio
//dd/mm/YYYY HH:ii:ss
function eT_hdt2sdt ($hdt, $orasn=1, $sepadt="/", $sepaora=":", $sepadthr=" ") {
  $appvet = explode($sepadthr, $hdt);
  list($d,$m,$Y) = explode($sepadt, $appvet[0]);
  
  if ($orasn==1) {
    list($H,$i,$s) = explode($sepaora,$appvet[1]);
    
    $s = str_pad((intval($s)+0), 2, "0");
  }
  
  return $Y.$m.$d.$H.$i.$s;
}


$labels=array();
$labels['hrless'] = _LBLMENODIUNAORA_;
$labels['ore'] = " horas";
$labels['giorni'] = " d&iacute;as";
$labels['week'] = " semanas";
$labels['mesi'] = " meses";


function eT_diffts($t1, $t2, $labels=array()) {
  define ("sogliaore",60*60);
  define ("sogliagiorni",24*sogliaore);
  define ("sogliaweek",7*sogliagiorni);
  define ("sogliamesi",4 * sogliaweek);
  
  $app = $t2 - $t1;
  
  if ($app < sogliaore) {
    $ret = $labels['hrless'];
  } else {
    if ($app < sogliagiorni) {
      $ret = number_format($app/sogliaore,0).$labels['ore'];
    } else {
      if ($app < sogliaweek) {
        $ret = number_format($app/sogliagiorni,0).$labels['giorni'];
      } else {
        if ($app < sogliamesi) {
          $ret = number_format($app/sogliaweek,0).$labels['week'];
        } else {
          $ret = number_format($app/sogliamesi,0).$labels['mesi'];
        }
      }
    }
  }
  
  return $ret; 
}

function labelsoggettounico($tiposoggetto, $vetsoggetto, $fimg=1,$fragsoc=1,$ffantasia=1) {
  
  //print $tiposoggetto;
  /*
  $loadcli[$tipocliente][$chiave]['nome'] = $line['nome'];
  $loadcli[$tipocliente][$chiave]['apellido'] = $line['apellido'];
  $loadcli[$tipocliente][$chiave]['ragsoc'] = $line['ragsoc'];
  $loadcli[$tipocliente][$chiave]['fantasia'] = $line['fantasia'];
  $loadcli[$tipocliente][$chiave]['impresa'] = $line['impresa'];
  */
  
  switch ($tiposoggetto) {
    case 1://CLIENTI
    case 2://FORNITORI
            if ($vetsoggetto['impresa']==1) {
              //impresa
              $img = "industria20.png";
            } else {
              //persona
              $img = "persone20.png";
            }
            
            if ($vetsoggetto['nome']!="" || $vetsoggetto['apellido']!="") {
              $buf .= $vetsoggetto['nome']." ".$vetsoggetto['apellido']." -";
            }
            
            if ($vetsoggetto['ragsoc']!="" && $fragsoc==1) {
              $buf .=" ".$vetsoggetto['ragsoc'];
            } else {
              $ffantasia=1;
            }
            if ($vetsoggetto['fantasia']!="" && $ffantasia==1) {
              $buf .= " (".$vetsoggetto['fantasia'].")";
            } else {
              $buf .= " ".$vetsoggetto['ragsoc'];
            }
            //print "<HR>".$buf."<HR>";
            break; 
    case 3://PROFESSORI
            $img = "professori_20.png";
            $buf .= $vetsoggetto['nome'];
            if ($vetsoggetto['nickname']!="") {
              $buf .= " (".$vetsoggetto['nickname'].")";
            }
            break;
  }
  
  if ($fimg==1) {
    $buf = "<IMG SRC='../img/$img' BORDER='0'>&nbsp;".$buf;
  }
  
  return $buf;
}

?>

