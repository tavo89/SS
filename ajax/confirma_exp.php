<?php
include("../Conexxx.php");

$busq=limpiarcampo($_REQUEST['busq']);
$qry="SELECT * FROM exp_anticipo WHERE estado='ABIERTO' AND cod_su=$codSuc AND (nom_cli LIKE '$busq%' OR id_cli='$busq')";
//echo $qry;
$rs=$linkPDO->query($qry);
if($rs->fetch()){
	$des=limpiarcampo($row['des']);
	$des = htmlentities($row["des"], ENT_QUOTES,"$CHAR_SET");
	echo "$des";
}

else echo 0;

?>