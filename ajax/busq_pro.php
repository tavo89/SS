<?php
require_once("../Conexxx.php");
$q = strtolower(limpiarcampo($_GET["q"]));

$qry="SELECT nom_pro,nit FROM provedores WHERE nom_pro LIKE '%$q%'";

$rs=$linkPDO->query($qry);
$resp="";
while($row=$rs->fetch())
{
	$nom=$row['nom_pro'];
	$cc=$row['nit'];
	$resp.="$nom|$cc|\n";
	
}
echo $resp;

?>