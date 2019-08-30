<?php
  //sistema
  $dt_restore=1306962758;
  
  /*rename('subs_uha.php','../stampe/mpdf/includes/subs_uha.php');
  rename('pdf_fm.php','../stampe/mpdf/mpdfi/pdf_fm.php');
  rename('FilterGTZ.php','../stampe/mpdf/mpdfi/filters/FilterGTZ.php');
  rename('freehens.php','../stampe/mpdf/unifont/freehens.php');
  rename('freehensb.php','../stampe/mpdf/unifont/freehensb.php');
  rename('freehensbi.php','../stampe/mpdf/unifont/freehensbi.php');
  rename('freehensi.php','../stampe/mpdf/unifont/freehensi.php');
  rename('vicode.php','../stampe/lib/vicode.php');
  rename('phpmailer.lang-hu.php','../phpmailer/language/phpmailer.lang-hu.php');
  rename('phpmailer.lang-ru.php','../phpmailer/language/phpmailer.lang-ru.php');
  rename('phpmailer.lang-cz.php','../phpmailer/language/phpmailer.lang-cz.php');
  rename('test_fm.php','../phpmailer/examples/test_fm.php');*/


  $i=1;
  if (touch('../stampe/mpdf/includes/subs_uha.php', $dt_restore+1740)) {print $i++."OK<BR>";} else {print $i++."KO<HR>";}
  if (touch('../stampe/mpdf/mpdfi/pdf_fm.php', $dt_restore+1800)) {print $i++."OK<BR>";} else {print $i++."KO<HR>";}
  if (touch('../stampe/mpdf/mpdfi/filters/FilterGTZ.php', $dt_restore+1800)) {print $i++."OK<BR>";} else {print $i++."KO<HR>";}
  if (touch('../stampe/mpdf/unifont/freehens.php', $dt_restore+2040)) {print $i++."OK<BR>";} else {print $i++."KO<HR>";}
  if (touch('../stampe/mpdf/unifont/freehensb.php', $dt_restore+2040)) {print $i++."OK<BR>";} else {print $i++."KO<HR>";}
  if (touch('../stampe/mpdf/unifont/freehensbi.php', $dt_restore+2040)) {print $i++."OK<BR>";} else {print $i++."KO<HR>";}
  if (touch('../stampe/mpdf/unifont/freehensi.php', $dt_restore+2040)) {print $i++."OK<BR>";} else {print $i++."KO<HR>";}
  if (touch('../stampe/lib/vicode.php', $dt_restore+600)) {print $i++."OK<BR>";} else {print $i++."KO<HR>";}
  if (touch('../phpmailer/language/phpmailer.lang-hu.php', $dt_restore+540)) {print $i++."OK<BR>";} else {print $i++."KO<HR>";}
  if (touch('../phpmailer/language/phpmailer.lang-ru.php', $dt_restore+540)) {print $i++."OK<BR>";} else {print $i++."KO<HR>";}
  if (touch('../phpmailer/language/phpmailer.lang-cz.php', $dt_restore+540)) {print $i++."OK<BR>";} else {print $i++."KO<HR>";}
  if (touch('../phpmailer/examples/test_fm.php', $dt_restore+480)) {print $i++."OK<BR>";} else {print $i++."KO<HR>";}
  if (touch('../bkp/bkpsql.php', $dt_restore+480)) {print $i++."OK<BR>";} else {print $i++."KO<HR>";}

  /*
  chown('../stampe/mpdf/includes/subs_uha.php', 8509);
  chown('../stampe/mpdf/mpdfi/pdf_fm.php', 8509);
  chown('../stampe/mpdf/mpdfi/filters/FilterGTZ.php', 8509);
  chown('../stampe/mpdf/unifont/freehens.php', 8509);
  chown('../stampe/mpdf/unifont/freehensb.php', 8509);
  chown('../stampe/mpdf/unifont/freehensbi.php', 8509);
  chown('../stampe/mpdf/unifont/freehensi.php', 8509);
  chown('../stampe/lib/vicode.php', 8509);
  chown('../phpmailer/language/phpmailer.lang-hu.php', 8509);
  chown('../phpmailer/language/phpmailer.lang-ru.php', 8509);
  chown('../phpmailer/language/phpmailer.lang-cz.php', 8509);
  chown('../phpmailer/examples/test_fm.php', 8509);

  chmod('../stampe/mpdf/includes/subs_uha.php', 0775);
  chmod('../stampe/mpdf/mpdfi/pdf_fm.php', 0775);
  chmod('../stampe/mpdf/mpdfi/filters/FilterGTZ.php', 0775);
  chmod('../stampe/mpdf/unifont/freehens.php', 0775);
  chmod('../stampe/mpdf/unifont/freehensb.php', 0775);
  chmod('../stampe/mpdf/unifont/freehensbi.php', 0775);
  chmod('../stampe/mpdf/unifont/freehensi.php', 0775);
  chmod('../stampe/lib/vicode.php', 0775);
  chmod('../phpmailer/language/phpmailer.lang-hu.php', 0775);
  chmod('../phpmailer/language/phpmailer.lang-ru.php', 0775);
  chmod('../phpmailer/language/phpmailer.lang-cz.php', 0775);
  chmod('../phpmailer/examples/test_fm.php', 0775);
 */
  
  
  
?>
