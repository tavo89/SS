<?php
include("../../Conexxx.php");
$idCli=r("idCli");
$nomCli=r("nomCli");
$tipoFac=r("tipo_fac");
$totFac=r("tot_fac");

$sql="SELECT * FROM usuarios WHERE (id_usu='$idCli' AND nombre='$nomCli') AND (monto_ban>0 OR monto_ban_remi>0)";
//echo "$sql";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){
$fechaLimFac=$row["fecha_ban"];
$montoLimFac=$row["monto_ban"];

$fechaLimRemi=$row["fecha_ban_remi"];
$montoLimRemi=$row["monto_ban_remi"];

$totFacturado=0;

//echo "fac: $fechaLimFac/$montoLimFac    remi: $fechaLimRemi/$montoLimRemi";
//echo "tipoFac: $tipoFac totFAc:$totFac";


if($tipoFac=="fac" || $tipoFac=="remi"){
	
	
$sql="SELECT SUM(tot) as tot FROM fac_remi WHERE (id_cli='$idCli' AND nom_cli='$nomCli' AND nit='$codSuc') AND (DATE(fecha)>='$fechaLimRemi' AND anulado!='ANULADO' AND tipo_fac='remision')";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){
$totFacturado+=$row["tot"];
}

$sql="SELECT SUM(tot) as tot FROM fac_venta WHERE (id_cli='$idCli' AND nom_cli='$nomCli' AND nit='$codSuc') AND (DATE(fecha)>='$fechaLimFac' AND anulado!='ANULADO')";
//echo "<li>$sql</li>";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){
$totFacturado+=$row["tot"];
//echo "<li>$totFacturado+$totFac)>$montoLimFac</li>";

$tot_tot=$totFacturado+$totFac;
$sobrePaso=($totFacturado+$totFac)-$montoLimFac;
if(($totFacturado+$totFac)>$montoLimFac){
	
echo "Esta FACTURA supera el LIMITE establecido(<span class=\"uk-text-danger uk-text-large uk-text-bold\">".money3($montoLimFac)."</span>), Monto sobrepasado: <span class=\"uk-text-danger uk-text-large uk-text-bold\">".money3($sobrePaso)."</span>";	
}else {echo "1";}
}

	
	
}else {echo "1";}

}else{echo "1";}

?>