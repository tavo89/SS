<?php
include("../../Conexxx.php");

$sql="SELECT valor_anual,nombre_su,cod_su,datediff(fecha_corte,NOW()) as diasRestantes, fecha_corte
	  FROM sucursal 
	  WHERE estado_pago!='Inhabilitado' AND estado_pago!='Free' AND datediff(fecha_corte,NOW())<10 ORDER BY cod_su;";
$rs=$linkPDO->query($sql);
$datosSucursales = '<ul class="uk-list uk-list-striped">';
$totalFactura=0;
$diasSucursal=0;
$fechaCorte='';
$banderaAlerta=0;
while($row = $rs->fetch()){
	
	$datosSucursales.='<li>'.$row['nombre_su'].' ('.$row['diasRestantes'].' D&iacute;as restantes)</li>';
	$totalFactura+=$row['valor_anual']*1;
	if($codSuc==$row['cod_su']){
		$banderaAlerta=1;
		$diasSucursal=$row['diasRestantes'];
		$fechaCorte=$row['fecha_corte'];
	}
	
}

$datosSucursales.= '</ul>';


$return_array=array("alerta"				=>$banderaAlerta,
					  "totalFactura"		=>money2($totalFactura),
					  "datosSucursales"		=>$datosSucursales,
					  "fechaCorte"			=>$fechaCorte,
					  "diasRestantes" 	 	=>$diasSucursal
						);
						
echo json_encode($return_array);
?>
