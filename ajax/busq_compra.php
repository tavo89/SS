<?php
require_once("../Conexxx.php");
//header("Content-Type: text/html; charset=UTF-8");
$nf=limpiarcampo($_REQUEST['id_compra']);
$sql="SELECT *,(tot-(r_iva+dcto2)) as TOT FROM fac_com WHERE serial_fac_com=$nf AND cod_su='$codSuc'";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){
$nomPro=$row['nom_pro'];
$nitPro=$row['nit_pro'];
$numFacCom=$row['num_fac_com'];
$tot=$row['TOT'];
$ICA=$row["r_ica"];
$FTE=$row["r_fte"];

$sql="SELECT SUM(valor) as abon FROM comp_egreso WHERE serial_fac_com=$nf AND anulado!='ANULADO' AND cod_su='$codSuc'";
$rs=$linkPDO->query($sql);
$row=$rs->fetch();
$abon=$row['abon'];

$sql="SELECT SUM(tot) as dev FROM fac_dev WHERE nit_pro='$nitPro' AND num_fac_com='$numFacCom' AND anulado!='ANULADO' AND cod_su='$codSuc'";
$rs=$linkPDO->query($sql);
$row=$rs->fetch();
$dev=$row['dev'];



$saldo=$tot-$abon-$dev;
$concept="Pago Factura de Compra No. $numFacCom ";
//					  0       1		  2		3	  4 	  5    6
echo limpiarcampo("$nomPro|$nitPro|$saldo|$dev|$concept|$ICA|$FTE");
}
else echo -1;

?>
