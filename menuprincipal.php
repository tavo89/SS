<?php 

require_once("Conexxx.php");

valida_session();


?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href="Imagenes/favicon2.ico" rel="shortcut icon" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo "$TITULO" ?></title>
</head>
<frameset rows="4%,*" frameborder="0" framespacing="1">
<frame name="sesion" src="session.php" />
<frameset cols="15%,*" frameborder="0" framespacing="1">
<frame name="menuL" src="menu_lateral.php" />
<frame name="centro" src="centro.php" />
</frameset>
</frameset>
<noframes></noframes>
</html>