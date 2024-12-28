<?php
require_once("../Conexxx.php");
$q = strtolower(limpiarcampo($_GET["q"]));

$qry="SELECT des_sub_clase,id_sub_clase FROM sub_clase WHERE des_sub_clase LIKE '%$q%'";

$rs=$linkPDO->query($qry);
$resp="";
while($row=$rs->fetch())
{
	$nom=$row['des_sub_clase'];
	$cc=$row['id_sub_clase'];
	$resp.="$nom|$cc|\n";
	
}
echo $resp;

?>