<?php
include("../Conexxx.php");
$num=limpiarcampo($_REQUEST['num']);
//&& ctype_digit($num)
$rs=$linkPDO->query("SELECT * FROM exp_anticipo WHERE estado='ABIERTO' AND num_exp='$num' AND cod_su=$codSuc" );
if($row=$rs->fetch())
{
	$des=limpiarcampo($row['des']);
	$totPa=$row['tot_pa'];
	$totAbo=$row['tot'];
	$saldo=$totPa-$totAbo;
	if($saldo<0)$saldo=0;
	
	$saldo=money2($saldo);
	echo "1|$des|$totPa|$totAbo|$saldo";
	
}
else echo "0";

?>