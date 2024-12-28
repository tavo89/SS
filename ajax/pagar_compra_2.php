<?php
require_once("../Conexxx.php");
$ID=r('ID');
$ID_compra=r('id_compra');
 


$sql="SELECT *,(tot-(r_iva+dcto2)) as TOT FROM fac_com WHERE serial_fac_com=$ID_compra AND cod_su='$codSuc'";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){
$nomPro=$row['nom_pro'];
$nitPro=$row['nit_pro'];
$numFacCom=$row['num_fac_com'];
$tot=$row['TOT'];
$ICA=$row["r_ica"];
$FTE=$row["r_fte"];
 
$sql="SELECT SUM(valor) as abon, SUM(r_fte) s_fte, SUM(r_ica) s_ica FROM comp_egreso WHERE serial_fac_com=$ID_compra AND anulado!='ANULADO' AND cod_su='$codSuc'";
$rs=$linkPDO->query($sql);
$row=$rs->fetch();
$abon=$row['abon'];
$totFte=$row["s_fte"];
$totIca=$row["s_ica"];

$sql="SELECT SUM(tot) as dev FROM fac_dev WHERE nit_pro='$nitPro' AND num_fac_com='$numFacCom' AND anulado!='ANULADO' AND cod_su='$codSuc'";
$rs=$linkPDO->query($sql);
$row=$rs->fetch();
$dev=$row['dev'];



$saldo=$tot-$abon-$dev;
$concept="Pago Factura de Compra No. $numFacCom ";

if($totFte==0){$linkPDO->exec("UPDATE comp_egreso SET r_fte=$FTE  WHERE id='$ID'");}
if($totIca==0){$linkPDO->exec("UPDATE comp_egreso SET r_ica=$ICA  WHERE id='$ID'");}
echo "fte= $FTE, S_fte= $totFte, ica= $ICA, S_ica= $totIca";
	
}
else {echo "666";}

?>