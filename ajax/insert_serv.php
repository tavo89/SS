<?php
require_once("../Conexxx.php");

$TABLA="servicios";
$COL_ID_TABLA="servicio";

$UNIC_INDEX=rm("serv");


$des=r("des_serv");
$iva=limpianum(r("iva"));
$pvp=quitacom(r("pvp"));
$km_rev=limpianum(r("km_rev"));
$COD=r("cod_serv");


$sql="SELECT * FROM $TABLA WHERE $COL_ID_TABLA='$UNIC_INDEX'";
//echo "$sql";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){echo "2";}
else{
	
	$sql="INSERT INTO $TABLA(servicio,des_serv,iva,pvp,km_revisa,cod_su,cod_serv) VALUES('$UNIC_INDEX','$des','$iva','$pvp','$km_rev','$codSuc','$COD')";
	t1($sql);
	echo "1";
	
	
}




?>