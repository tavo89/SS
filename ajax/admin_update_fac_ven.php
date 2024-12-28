<?php
require_once("../Conexxx.php");

$num_fac=r("num_fac");
$pre=r("pre");
$resolucion=r("resol");
$resp=r("resp");

$opc=r("opc");
$rta=0;

if($opc=="1")
{
	$sql="UPDATE fac_venta SET tipo_venta='$resp' WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND resolucion='$resolucion' AND anticipo_bono=0";
 $rta=$t1($sql);
	
}
else if($opc=="2")
{
	$sql="UPDATE fac_venta SET vendedor='$resp' WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND resolucion='$resolucion'";
	$rta=t1($sql);
}

if($rta==1)echo "Cambio Efectuado";
else echo "CAMBIO NO PERMITIDO";


   ?>