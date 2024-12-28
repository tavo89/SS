<?php
require_once("../Conexxx.php");
$q = strtolower(limpiarcampo($_GET["q"]));

$qry="SELECT * FROM usuarios WHERE (CONCAT(nombre,' ', snombr, ' ',  apelli) LIKE '%$q%'  OR alias LIKE '$q%') AND id_usu!='' GROUP BY id_usu";
//echo $qry;
$rs=$linkPDO->query($qry);
$resp="";
$ALIAS="";
while($row=$rs->fetch())
{
	if(!empty($row['alias'])){$ALIAS=" ($row[alias])";}
	 else $ALIAS="";
	$nom=$row['nombre']."$ALIAS";
	$snombr=$row["snombr"];
	$apelli=$row["apelli"];
	$fullNombr="$nom $snombr $apelli";
	$cc=$row['id_usu'];
	$resp.="$fullNombr|$cc|\n";
	//$resp.="$cc|$nom|\n";
	
}
echo trim($resp);
?>