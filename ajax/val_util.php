<?php
include_once("../Conexxx.php");
$ref = r("ref");
$cod = r("cod");

$tipo="A";

$sql="SELECT * FROM x_config WHERE id_config=1";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$tipo=$row["val"];
}

if($tipo=="A"){$sql="SELECT ROUND( ((precio_v-costo*(1+iva/100))/(costo*(1+iva/100)))*100 ,-1) as GAN FROM inv_inter WHERE id_inter='$cod' AND id_pro='$ref'";}
else {$sql="SELECT ROUND((1-((costo*(1+iva/100))/(precio_v)))*100,-1) as GAN FROM inv_inter WHERE id_inter='$cod' AND id_pro='$ref'";}
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
{
	$UTIL=$row["GAN"];
	echo "$UTIL";
}
else echo "-404";

?>