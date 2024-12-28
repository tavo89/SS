<?php
//////////// DB///////////////////
// CONEXION LOCAL
$BaseDatos="frenopartes";$USU="root";$CLA="laconchadelalora9173";

$HOST="localhost";
$conex = new mysqli("$HOST", "$USU", "$CLA", "$BaseDatos");
if ($conex->connect_errno) {
    echo "Falló la conexión a MySQL: (" . $conex->connect_errno . ") " . $conex->connect_error;
}

$conex->set_charset('utf8');
$conex=$conex;
/////////////////////////////////////// PDO //////////////////////////////////////////////////////
try {
  $linkPDO = new PDO("mysql:host=$HOST;dbname=$BaseDatos", $USU, $CLA, 
      array(PDO::ATTR_PERSISTENT => 0));
	  $linkPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	  
      $linkPDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  //echo "Connected\n";
} catch (Exception $e) {
  die("Unable to connect: " . $e->getMessage());
}
?>