<?php
function setFormSessionFilter($campo,$valore) {
  if ($valore!="") {
  //if ($_REQUEST['daform']!=1) {
    $_SESSION[FILE_CORRENTE.$campo] = $valore;
    setFlagFormSessionFilter();
  } else {
    if ($_REQUEST['daform']==1) {
      $_SESSION[FILE_CORRENTE.$campo] = $valore;
      setFlagFormSessionFilter();
    }
  }
}
function getFormSessionFilter($campo) {
  return $_SESSION[FILE_CORRENTE.$campo];
}

function setFlagFormSessionFilter() {
  $_SESSION[FILE_CORRENTE.'FILTERED']=1;
}

function resetFormSessionFilter() {
  $curlen = strlen(FILE_CORRENTE);
  foreach ($_SESSION as $keyfilter => $curfilter) {
    if (substr($keyfilter,0,$curlen)==FILE_CORRENTE) {
      unset($_SESSION[$keyfilter]);
    }
  }
}


  //reset session
  if ($_REQUEST['rstflt']==1) {
    resetFormSessionFilter();
  }
  
  //set session
  setFormSessionFilter('form_limit',$_REQUEST['form_limit']);
  setFormSessionFilter('form_sort',$_REQUEST['form_sort']);
  for ($search_i=1;$search_i <= $cmd['ne_search'];$search_i++) {
    setFormSessionFilter('src'.$search_i,$_REQUEST['src'.$search_i]);
  }
  
  //parametri filtro da session
  $cmd['form_limit'] = getFormSessionFilter('form_limit');
  $cmd['form_sort'] = getFormSessionFilter('form_sort');
  for ($search_i=1;$search_i <= $cmd['ne_search'];$search_i++) {
    $cmd['src'.$search_i] = getFormSessionFilter('src'.$search_i);
  }
  
  if ($cmd['form_limit']=="") {$cmd['form_limit']=0;}
  $cmd['form_rowcount'] = $setup['form_rowcount'];
  
?>
