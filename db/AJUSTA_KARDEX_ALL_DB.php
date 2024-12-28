<?php
include("DB.php");
$usar_fecha_vencimiento=0;
$fechaKardex="2012-01-01";
include("offline_LIB.php");
 

foreach($SUB_DOMINIOS as $index => $val)
{
	//&& ($index>=1 && $index<=28)
if($SUB_DOMINIOS[$index]!="127.0.0.1/SS/" ){
echo "<h1>[$index] => $val |".$CREDENCIALES[$val]["db"]."</h1>";

$BaseDatos=$CREDENCIALES[$val]["db"];
$USU=$CREDENCIALES[$val]["usu"];
$CLA=$CREDENCIALES[$val]["cla"];
$HOST="127.0.0.1";
 

$conex = new mysqli("$HOST", "$USU", "$CLA", "$BaseDatos");
if ($conex->connect_errno) {
    echo "<br> Falló la conexión a MySQL[".$CREDENCIALES[$val]["db"]."]: (" . $conex->connect_errno . ") " . $conex->connect_error;
}
/////////////////////////////////////// PDO //////////////////////////////////////////////////////
try {
  $linkPDO = new PDO("mysql:host=$HOST;dbname=".$CREDENCIALES[$val]["db"]."", $USU, $CLA, 
      array(PDO::ATTR_PERSISTENT => 0));
	  $linkPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //echo "Connected\n";
} catch (Exception $e) {
  die("Unable to connect: " . $e->getMessage());
}



$sql="SELECT * FROM x_config WHERE 1=1";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch()){
	
$_SESSION[$row["des_config"]]=$row["val"];		
	
}
echo "<br>tipo: ".$_SESSION["TIPO_CHUZO"]."<br>";
$usar_fecha_vencimiento=0;
$fechaKardex="2000-05-07";
ini_set('memory_limit', '2048M');
if($_SESSION["TIPO_CHUZO"]=="DRO"){$usar_fecha_vencimiento=1;}


$sql="SELECT * FROM sucursal ";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch()){
if($row['estado_pago']=='Inhabilitado') {
	continue;
}
set_time_limit(300);
$codSuc=$row['cod_su'];
$nomNegocio=$row['nom_negocio'];

echo "<li>$nomNegocio - $codSuc</li>";

if(1){
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*  CODIGO SQL ACA   */

$SQL="
ALTER TABLE `fac_venta` ADD `propina` TINYINT NOT NULL AFTER `DESCUENTO_IVA`;
ALTER TABLE `sucursal` ADD `resolPosElectronica` TINYINT NOT NULL DEFAULT '0' AFTER `frecuencia_pago`;

";

echo "<li>1 >>$SQL</li>";
mysqli_multi_query($conex, $SQL);
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
$S= explode(';', trim($SQL));
foreach($S as $key => $val){
	set_time_limit(90);
	echo "<li>1 >>$val</li>";
	 
	if(!empty($val)){query($val);}
}
*/
}
 

echo "----------------------------------------------------------------------------------------------------------------------------------------------------<br>";

}






//
	}
}
 
$linkPDO = null;

?>