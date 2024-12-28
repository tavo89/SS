<?php
//admin@interplustel.com:PvJgMdN4bEqTjLv2zyWTIFX8WgzPdUFS
include("../../Conexxx.php");
include("WISPRO_lib.php");
 

$auth_basic = "9b19115f-9cdf-4abe-b3ef-1319a5bc9da8";

/*
	
Estado del contrato. Valores aceptados:

"enabled"

"alerted"

"disabled"

*/
$estadoContrato="Edwincito Oliveirus Sanabria";
$estadoContrato="enabled";
$idCliente="76c5da67-aa4b-4600-8f8f-e90136cc8a44";
$idContrato="9255ab10-bc16-4f8e-83c8-27fae8e34562";
//mod_contrato($estadoContrato,$idContrato,$auth_basic);


$respuesta=mostrar_contratos(1,50,$auth_basic);

print_r( $respuesta);


 



?>
HOLIS :3