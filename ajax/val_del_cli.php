<?php
include("../Conexxx.php");
	$id_cli=r('id_cli');
	$ID=r("id");
	$nom=r("nom");
	$sql="SELECT * FROM fac_venta WHERE id_cli='$id_cli' AND nom_cli='$nom'";
	$rs=$linkPDO->query($sql);
	if($rs->fetch())
	{
		echo 2;	
	}
	else echo 3;
	
?>