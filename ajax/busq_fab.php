<?php
require_once("../Conexxx.php");
$q = strtolower(limpiarcampo($_GET["q"]));

$qry="SELECT fabricante,id_fab FROM fabricantes WHERE fabricante LIKE '%$q%'";

$rs=$linkPDO->query($qry);
$resp="";
while($row=$rs->fetch())
{
	$nom=$row['fabricante'];
	$cc=$row['id_fab'];
	$resp.="$nom|$cc|\n";
	
}
echo $resp;

?>