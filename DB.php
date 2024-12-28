<?php
//////////// DB///////////////////
$usuarioHostinger='u155514936';
$dominioPrincipal='nanimosoft.com';
/**
 * DBs locales
 * u155514936_metal
 * u155514936_mafan
 * u155514936_tavo
 * u155514936_minibuengusto
 * u155514936_bardubai
 * u155514936_casaDelHielo
 * u155514936_secre
 * u155514936_tavo
 * u155514936_elaraucomusika
 * u155514936_testwirpat
 * u155514936_tittoscomida
 * 
 */
//                	1             2          3        4           5             6	     	   7		       8
$DB_test=array(1,"u155514936_testwirpat","interplus","metalhierro","elbutaco","comidas","interplus","motos_nomina","u155514936_analu");

// CONEXION LOCAL
$SUB_DOMINIOS=[0=>"127.0.0.1/SS/"];
$CREDENCIALES[$SUB_DOMINIOS[0]]['db']="$DB_test[1]";
$CREDENCIALES[$SUB_DOMINIOS[0]]['usu']='root';
$CREDENCIALES[$SUB_DOMINIOS[0]]['cla']='';

$hostName=$_SERVER['HTTP_HOST'];

if($hostName!='127.0.0.1'){
  require_once('db/SSconfig.php');
}




if(in_array($hostName,$SUB_DOMINIOS))
{
  $BaseDatos=$CREDENCIALES[$hostName]["db"];$USU=$CREDENCIALES[$hostName]["usu"];$CLA=$CREDENCIALES[$hostName]["cla"];
}
else if($hostName=="preproduccion.$dominioPrincipal"){
  $hostName=$dominioPrueba;
  $BaseDatos=$CREDENCIALES[$hostName]["db"];$USU=$CREDENCIALES[$hostName]["usu"];$CLA=$CREDENCIALES[$hostName]["cla"];
}
else{
  $BaseDatos=$CREDENCIALES[$SUB_DOMINIOS[0]]["db"];$USU=$CREDENCIALES[$SUB_DOMINIOS[0]]["usu"];$CLA=$CREDENCIALES[$SUB_DOMINIOS[0]]["cla"];
}

$HOST="localhost";
//$HOST="127.0.0.1";

$conex = new mysqli("$HOST", "$USU", "$CLA", "$BaseDatos");
if ($conex->connect_errno) {
    echo "Falló la conexión a MySQL: (" . $conex->connect_errno . ") " . $conex->connect_error;
}


/////////////////////////////////////// PDO //////////////////////////////////////////////////////
try {
  $linkPDO = NULL;
  $linkPDO = new PDO("mysql:host=$HOST;dbname=$BaseDatos;", $USU, $CLA,
      array(PDO::ATTR_PERSISTENT => 0));
	  $linkPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $linkPDO->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
} catch (Exception $e) {
  die("Unable to connect: " . $e->getMessage());
}

?>
