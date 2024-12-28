<?php
require_once("../Conexxx.php");
$q = strtolower(limpiarcampo($_GET["q"]));

$qry="SELECT presentacion,pre_id FROM presentacion WHERE presentacion LIKE '%$q%'";

$rs=$linkPDO->query($qry);
$resp="";
while($row=$rs->fetch())
{
	$nom=$row['presentacion'];
	$cc=$row['pre_id'];
	$resp.="$nom|$cc|\n";
	
}
echo $resp;

?>