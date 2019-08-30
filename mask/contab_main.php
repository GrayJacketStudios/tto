<?php
  include ("contab_menu.php");  
?>
<html>
  <head>
    <link rel="stylesheet" type="text/css" href="../css/main.css" />
    <?php
        echo $ribbon->style_link();
    ?>
  </head>
  <body>
    <?php
        $ribbon->build(false);
    ?>
  </body>
</html>
