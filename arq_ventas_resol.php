<?php
$sql="SELECT MAX(`num_fac_ven`) ultimo,MIN(`num_fac_ven`) primero,COUNT(`num_fac_ven`) total,`prefijo`,`resolucion`,`fecha_resol`,`rango_resol` FROM `fac_venta` WHERE resolucion!='' AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroHora) AND ".VALIDACION_VENTA_VALIDA."  $filtroSEDE_nit GROUP BY `resolucion`,rango_resol,fecha_resol,prefijo
ORDER BY `fac_venta`.`fecha_resol` DESC";
$rs=$linkPDO->query($sql);

while($row=$rs->fetch())
{
	$TOT_VENTAS0516=tot_ventas_0516($fechaI,$fechaF,"all",$horaI,$horaF,$CodCajero,0,$row["resolucion"],$row["prefijo"],$row["rango_resol"]);
	$ARQ_TITULO="ResolDian $row[resolucion]  <br>$row[fecha_resol] $row[rango_resol] ";
	include("arq_ventas_tot.php");
}

?>