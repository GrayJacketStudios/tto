<?php

  $pagever = "1.0";
  $pagemod = "20/03/2011 10.21.39";
  define ("SCRIPT_AUTOMATICO",2102);
  
  require_once("../mask/form_cfg.php");
  require "../phpmailer/class.phpmailer.php";

  //error_reporting(E_ALL);
  //ini_set("display_errors", 1);

  
  function caricaparser($codice, $vettore) {
    switch ($codice) {
      case 7:
              $query[1] = "INSERT INTO customer(nome, apellido, rut_persona, tel_pri, mail1, note,stato, stato2, trashed) VALUES (
                '".$vettore[3]."','".$vettore[4]."','".$vettore[5]."','".$vettore[6]."','".$vettore[7]."','".$vettore[8]."\n".$vettore[9]."',
                2,NULL,0)";
              
              $query[2] = "INSERT INTO customer_log (idcustomer, times, mezzo, msg, user, trashed) VALUES (
                ###INSID_1###, ".time().", 20, '".$vettore[8]."<BR>".$vettore[9]."', 0, 0)";
              $mailmessage .= "NUEVO CLIENTE<BR>ID: ###INSID_1###<BR>Nombre: ".$vettore[3]."<BR>Apellido: ".$vettore[4]."<BR> Fono: ".$vettore[6]."<BR>Email: ".$vettore[7]."<BR>Notas:".$vettore[8]."<BR>".$vettore[9]."<BR>"."<BR><BR><HR><BR>"; 
              break;
      default:
              $mailmessage = "ATENCION: el codigo ".$codice." es incorrecto.<BR><BR>ATTENZIONE: il codice ".$codice." &egrave; errato<BR><BR><HR><BR>";
              break;
    }
    $vetinsid = array (" "=>" ");
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
  				}
  		}
  	}
  	
  	$mailmessage = strtr($mailmessage,$vetinsid);
  	
    return $mailmessage;
  }
  
  
  /*
  #######
   PARSER POP3
  #######
  */
  
  
  require("pop3.php");
  
  $pop3=new pop3_class;
	$pop3->hostname="tto.chilenglish.com";             /* POP 3 server host name                      */
	$pop3->port=110;                         /* POP 3 server host port,
	                                            usually 110 but some servers use other ports
	                                            Gmail uses 995                              */
	$pop3->tls=0;                            /* Establish secure connections using TLS      */
	$user="ttoparser@tto.chilenglish.com";                        /* Authentication user name                    */
	$password="5abbracci";                    /* Authentication password                     */
	$pop3->realm="";                         /* Authentication realm or domain              */
	$pop3->workstation="";                   /* Workstation for NTLM authentication         */
	$apop=0;                                 /* Use APOP authentication                     */
	$pop3->authentication_mechanism="USER";  /* SASL authentication mechanism               */
	$pop3->debug=0;                          /* Output debug information                    */
	$pop3->html_debug=0;                     /* Debug information is in HTML                */
	$pop3->join_continuation_header_lines=1; /* Concatenate headers split in multiple lines */
	
	$dati = array();
	$k=0;
	if(($error=$pop3->Open())=="")
	{
	  if(($error=$pop3->Login($user,$password,$apop))=="") {
      if(($error=$pop3->Statistics($messages,$size))=="") {
        $result=$pop3->ListMessages("",0);
        if(GetType($result)=="array") {
          $num_msg = count($result);
          if ($num_msg>0) {
            for ($j=0;$j<=$num_msg;$j++) {
              $cattura=false;
              $valido=false;
              if(($error=$pop3->RetrieveMessage($j,$headers,$body,-1))=="") {
                for($line=0;$line<count($headers);$line++) {
                  //echo "<PRE>[",HtmlSpecialChars($headers[$line]),"]</PRE>\n";
                  if (substr($headers[$line],0,7)=="Subject") {
                    $k++;
                    $dati[$k]['oggetto'] = $headers[$line];
                  }
                }
								//echo "<PRE>---Message headers ends above---\n---Message body starts below---</PRE>\n";
								
								for($line=0;$line<count($body);$line++) {
									//echo "<PRE>",HtmlSpecialChars($body[$line]),"</PRE>\n";
									print "POSIZIONE".strpos($body[$line],"#!!#");
									$pos = strpos($body[$line],"#!!#"); 
									if ($pos !== false) {
									  $cattura=true;
									  $valido=true;
                  }
                  
                  if ($cattura) {
                    $dati[$k]['str'] .= htmlentities ($body[$line],ENT_COMPAT,"UTF-8");
                  }
                  
                  print "[".htmlentities ($body[$line],ENT_COMPAT,"UTF-8")."]";
                  
                  if ($cattura){
                    print "[".htmlentities ($body[$line],ENT_COMPAT,"UTF-8")."]";
                  }
                  
                  if (strpos($dati[$k]['str'],"#!*!#")) {
                    $cattura=false;
                  }
								}
								
                //$valido=false;
                if ($valido) {
								  //cancella solo i messaggi effettivamente acquisiti
                  if(($error=$pop3->DeleteMessage($j))=="") {
                  } else {
                    $msg_errore .= "Error 5 - Delete message ".$result."\n";
                  }
                }
							} else {
                $msg_error .= "Error 7 - Retrive ".$result."\n";
              }
						}	
						
            if ($error=="") {
              // ELABORAZIONE
  						foreach ($dati as $key=>$cur) {
  						  print "<HR>ELABORA MESSAGGIO<HR>";
                $dati[$key]['parsed'] = explode("#", $cur['str']);
                
                myprint_r($dati);
                if (count($dati[$key]['parsed'])>1) {
                  $testomail .= caricaparser($dati[$key]['parsed'][2],$dati[$key]['parsed']);
                }
              }
            } else {
              //nessuna elaborazione per errore
            }
          } else {
            print "<HR>Nessun messaggio da elaborare<HR>";
          }
        } else {
          $msg_errore .= "Error 4 - Result ".$result."\n";
        }
      } else {
        $msg_errore .= "Error 3 - Statistics ".$result."\n";
      }
    } else {
      $msg_errore .= "Error 2 - Login ".$result."\n";
    }
	} else {
    $msg_errore .= "Error 1 - POP3 Connection ".$result."\n";
  }
  
  
  if($error==""	&& ($error=$pop3->Close())=="") {
  } else {
    $msg_errore .= "Error 6 - Close ".$result."\n";
  }
  
  
  if ($error=="" && $testomail!="") {
    //invia mail
    
    $indirizzi = "michele.mentucci@gmail.com;marketing@chilenglish.com;cwilson@chilenglish.com;acastillo@chilenglish.com";
    
    $header = "<HTML><HEAD><STYLE>body {font-family:verdana, helvetica;font-size:12pt;color:#2600A5;}</STYLE></HEAD><BODY><center><img src=\"cid:logotop\"/></center><BR>";
    $footer = "<BR><BR><BR><BR><TABLE WIDTH=100%><TR><TD>www.chilenghish.com Tels (56 2) 665 1676 - 665 0965, Ram&oacute;n Carnicer 81, Of. 607</TD><TD><img src=\"cid:logofooter\"/></TD></TR></TABLE>";
    $messaggio = new PHPmailer();
    $messaggio->From='marketing@chilenglish.com';
    $messaggio->FromName='TTO';
    
    $messaggio->AddEmbeddedImage("../img/logochilenglish.jpg", "logotop", "../img/logochilenglish.jpg");
    $messaggio->AddEmbeddedImage("../img/logochilenglishfooter.jpg", "logofooter", "../img/logochilenglishfooter.jpg");
    
    $vetaddr = explode(";", $indirizzi);
    foreach ($vetaddr as $curaddr) {
      $messaggio->AddAddress($curaddr);
    }
    $messaggio->MsgHTML($header.$testomail.$footer);
    $messaggio->Subject="WEB FORM";
    
    $messaggio->Send();
  }
  
?>
