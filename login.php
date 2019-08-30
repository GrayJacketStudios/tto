<?php error_reporting(E_ALL); ?>
<?php
// includo oggetti di libreria
//require_once("./cgi/f_UserLogin.php");
//require_once("./cgi/f_utility.php");

function valorelivello($livello) {
  switch ($livello) {
    case "R":$livelloval=10;break;
    case "W":$livelloval=20;break;
    case "D":$livelloval=30;break;
  }
  return $livelloval;
}

define ("ERRORTIME",3);
define ("OKTIME",1);
define ("DURATASESSIONE",900);

$logitem = array();

require_once ( "./cgi/cfg.php" );

// recupero i dati di login
if ( !isset( $_POST['username'] ) || $_POST['username']=="" || !isset($_POST['passwd']) || $_POST['passwd']=="" )
  $noparms = true;
else {
  $username = $_POST['username'];
  $password = $_POST['passwd'];
  $noparms = false;
}
// inizializzo l'autenticazione
//$ob_UM = new UserManager();

//FACCIO LA LOGIN E SETTO LE VARIABILI DI SESSIONE


$link = mysql_connect($db_host,$db_user,$db_password);

$db_selected = mysql_select_db($db_dbname, $link);
if (!$db_selected) {
    $msgtouser ="Db non trovato : ". mysql_error();
    $timetowait = ERRORTIME;
    $redirurl ="index.html"; 
} else {
    $query="SELECT user.id as user_id, user.nome as user_nome, user.username as username, user.password as password,
    user.dtcreazione as dtcreazione, user.attivo as user_attivo, user.fboss as fboss, user.fviceboss as fviceboss, user.trashed as trashed,
    docente.id as docente_id, docente.nome as docente_nome, docente.nickname as docente_nickname, 
    studente.id as studente_id, studente.nome as studente_nome
    FROM user LEFT JOIN docente ON user.id=docente.iduser LEFT JOIN studente ON user.id=studente.iduser
    WHERE user.trashed<>1 AND user.attivo=1 AND user.username='".$_REQUEST['username']."' AND user.password='".$_REQUEST['passwd']."'";
    
    //die( $query);
    $result = mysql_query($query);
    if (!$result) {
        die('ERRORE QUERY: ' . mysql_error());
    }
    $row = mysql_fetch_assoc($result);
    $verificapsw=false;
    //echo "username".$row['userName']."<BR>";
    // avvio la sessione
    if ($row['user_id']!="") {
      $verificapsw=true;
      
      ini_set("session.gc_maxlifetime", 3600); 
      
      
      session_start();
      $_SESSION['user_id'] = $row['user_id'];
      $_SESSION['user_nome'] = $row['user_nome'];
      $_SESSION['username'] = $row['username'];
      $_SESSION['fboss'] = $row['fboss'];
      $_SESSION['fviceboss'] = $row['fviceboss'];
      $_SESSION['docente_id'] = $row['docente_id'];
      $_SESSION['docente_nickname'] = $row['docente_nickname'];
      $_SESSION['studente_id'] = $row['studente_id'];
      $_SESSION['studente_nome'] = $row['studente_nome'];
      
      $_SESSION['setNuovaLingua'] = $_REQUEST['setNuovaLingua'];
      $newlingua = $_SESSION['setNuovaLingua'];
      switch ($newlingua) {
        case "it":
                  require_once($path."lang/it.php");
                  break;
        case "en":
                  require_once($path."lang/en.php");
                  break;
        case "es":
                  require_once($path."lang/es.php");
                  break;
        default:
                  require_once($path."lang/it.php");
                  break;
      }
      
      $level = 10;
      if ($row['fviceboss']==1) {$level=20;}
      if ($row['fboss']==1) {$level=30;}
      $_SESSION['user_level'] = $level;
      
      //auth
      $query = "SELECT codice FROM auth";
      $result = mysql_query($query) or die ("Error_auth_001");
      while ($line = mysql_fetch_assoc($result)) {
        //$_SESSION['auth_'.$line['codice']] = valorelivello("D");
        $_SESSION['auth_'.$line['codice']] = 1;
      }
      
      $redirurl = "main.html";
      $timetowait = OKTIME;
      $msgtouser = "Acceso correcto";
      
    } else {
      $verificapsw=false;
      $redirurl = "index.html";
      $timetowait = ERRORTIME;
      $msgtouser = "Nombre de usuario o contraseña invalidos";
    }
    /*print_r($_SESSION);
    if (!$verificapsw) {
        die('Accesso negato. Riprovare... ' . mysql_error());
    }
    echo 'Accesso consentito: caricamento del programma in corso...'; 
    */
    
}

/*LOG
  $flog = fopen(LOGFILE, 'a');
	$logdate = date(Ymd_His);
	foreach ($logitem as $key=>$cur) {
		fwrite($flog, $logdate." ".$cur."\n");
	}
	fclose($flog);
*/
?>

<html>
<head>
<title>validating autentication...</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<?php
    print ("<META HTTP-EQUIV=Refresh CONTENT=\"$timetowait; URL=$redirurl\">");
?>
</head>


<body style="font-family: Verdana, Tahoma, Arial, sans-serif; font-size: 12px;">
<?php echo $msgtouser;?>
</body>
</html>
