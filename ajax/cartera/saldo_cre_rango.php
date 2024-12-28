<?php
require_once("../../Conexxx.php");
$feI=r('feI');
$feF=r('feF');
$RESP=tot_abon_cre_rango($feI,$feF);

if($RESP[0]!=2020)
{	
	if($RESP["saldo"]<=0){echo "-1|$RESP[saldo]";}
	else {echo "-3|$RESP[saldo]";}
}
else echo "0|$RESP[0]";

?>
