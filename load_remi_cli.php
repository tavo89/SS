<?php
if(!empty($idCliente) || !empty($filtroA) || !empty($filtroB)){
if(!empty($idCliente)){
	$rs=$linkPDO->query("SELECT *,TIME(fecha) as hora, DATE(fecha) as fecha FROM fac_remi WHERE nit='$codSuc' $filtroA $filtroB");
}
else {
	$rs=$linkPDO->query("SELECT *,TIME(fecha) as hora, DATE(fecha) as fecha FROM fac_venta WHERE nit='$codSuc' $filtroA $filtroB");
}
  
if($row=$rs->fetch()){

if(!empty($filtroB)){
	
$nomCli=$row['nom_cli'];
$idCli=$row['id_cli'];
$telCli=$row['tel_cli'];
$dirCli=$row['dir'];
$mailCli=$row['mail'];	
	
}

	$mecanico = $row["mecanico"];
	$mecanico2 = $row["tec2"];
	$mecanico3 = $row["tec3"];
	$mecanico4 = $row["tec4"];

$ciudadCli=$row['ciudad'];

$vendedor=nomTrim($row['vendedor']);	
$tipoPago=$row['tipo_venta'];
$tipoCli=$row['tipo_cli'];
$pagare=$row['num_pagare'];
$fecha_hora=$row['fecha']."T".$row['hora'];

$SUB=$row['sub_tot'];
$DESCUENTO=0;
$IVA=$row['iva'];
$TOT=$row['tot'];
$TOT_BSF=$row['tot_bsf'];

$val_letras=$row['val_letras'];
$entrega=$row['entrega'];
$cambio=$row['cambio'];
$entregaBSF=$row['entrega_bsf'];

$abon_anti=$row['abono_anti'];	
$num_exp=$row['num_exp'];

$R_FTE=$row['r_fte'];
$R_IVA=$row['r_iva'];
$R_ICA=$row['r_ica'];

$R_FTE_PER=0;
$R_IVA_PER=0;
$R_ICA_PER=0;
$TOT_PAGAR=$TOT-($R_FTE+$R_ICA+$R_IVA);
$KM=$row["km"];
$PLACA=$row["placa"];


}

}

?>