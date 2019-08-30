<?php
header('Content-Disposition: attachement; filename="'.$name.'";');
	 header('Content-Type: application/txt');
	readfile($name);

?>