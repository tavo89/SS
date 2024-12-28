<?php
require_once("../Conexxx.php");

$placa=rm("placa");

$sql="select * FROM vehiculo WHERE placa='$placa'";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){
	$id=$row["id_propietario"];
	echo "1|$id";
	
}
else{echo "0|";}

?>