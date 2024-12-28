<?php
require_once("../Conexxx.php");
$q = strtolower(limpiarcampo($_GET["q"]));

$qry="SELECT des_clas,id_clas FROM clases WHERE des_clas LIKE '%$q%'";

$rs=$linkPDO->query($qry);
$resp="";
while($row=$rs->fetch())
{
	$nom=$row['des_clas'];
	$cc=$row['id_clas'];
	$resp.="$nom|$cc|\n";
	
}
echo $resp;

?>