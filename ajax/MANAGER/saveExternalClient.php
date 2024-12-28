<?php
include("../../DB.php");

$cliente=$_REQUEST['cliente'];
$BaseDatos=$CREDENCIALES[$cliente]["db"];
$USU=$CREDENCIALES[$cliente]["usu"];
$CLA=$CREDENCIALES[$cliente]["cla"];
$HOST="127.0.0.1";
$conex = new mysqli("$HOST", "$USU", "$CLA", "$BaseDatos");
if ($conex->connect_errno) {
    echo "<br> Falló la conexión a MySQL[".$CREDENCIALES[$cliente]["db"]."]: (" . $conex->connect_errno . ") " . $conex->connect_error;
}
/////////////////////////////////////// PDO //////////////////////////////////////////////////////
try {
  $linkPDO = new PDO("mysql:host=$HOST;dbname=".$CREDENCIALES[$cliente]["db"]."", $USU, $CLA, 
      array(PDO::ATTR_PERSISTENT => 0));
	  $linkPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (Exception $e) {
  die("Unable to connect: " . $e->getMessage());
}

include("../../offline_LIB.php");


$estadoPago=r('estadoPago');
$fechaPago=r('fechaPago');
$fechaCorte=r('fechaCorte');
$valorAnualidad = r('valorAnualidad');
$valorAnualidad = quitacom($valorAnualidad);
$codSuc = r('codSuc');


try{
$sql="UPDATE sucursal 
      SET estado_pago='$estadoPago', 
      fecha_pago='$fechaPago',
	  fecha_corte='$fechaCorte',
	  valor_anual='$valorAnualidad'
	  WHERE cod_su='$codSuc'";
echo "$sql";
t1($sql);

}
catch(Exception $e){} 

$linkPDO = null;
?>