<?php
include("../../Conexxx.php");
$idCli=r("idCli");
$nomCli=r("nomCli");
$tipoFac=r("tipo_fac");
$totFac=r("tot_fac");

$filtroCreditos="";
if($MODULES["limitar_solo_contratos_credito"]==1){
	$filtroCreditos=" AND tipo_venta='Credito'";
}

$sql="SELECT * FROM usuarios WHERE (id_usu='$idCli' AND nombre='$nomCli') AND (monto_ban>0 OR monto_ban_remi>0)";
//echo "$sql";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){
$fechaLimFac=$row["fecha_ban"];
$montoLimFac=$row["monto_ban"];

$fechaLimRemi=$row["fecha_ban"];
$montoLimRemi=$row["monto_ban"];

$saldo=0;
$totFacturado=0;

	


if($tipoFac=="fac" || $tipoFac=="remi"){
$sql="SELECT SUM(tot) as tot FROM fac_venta WHERE (id_cli='$idCli' AND nom_cli='$nomCli' AND nit='$codSuc') AND (DATE(fecha)>='$fechaLimFac' AND anulado!='ANULADO') $filtroCreditos";
//echo "<li>$sql</li>";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){
$totFacturado+=$row["tot"];
  

	
//echo "SALDO L&iacute;mite Facturaci&oacute;n (<span class=\"uk-text-danger uk-text-large uk-text-bold\">".money3($saldo)."</span>)";	

}


$sql="SELECT SUM(tot) as tot FROM fac_remi WHERE (id_cli='$idCli' AND nom_cli='$nomCli' AND nit='$codSuc') AND (DATE(fecha)>='$fechaLimRemi' AND anulado!='ANULADO' AND tipo_fac='remision')";

$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){
$totFacturado+=$row["tot"];
$saldo=-($totFacturado)+$montoLimRemi;	

}

echo "SALDO L&iacute;mite (<span class=\"uk-text-danger uk-text-large uk-text-bold\">".money3($saldo)."</span>)";		

	}
else {echo "1";}
}else {echo "1";}

?>