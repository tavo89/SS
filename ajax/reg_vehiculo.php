<?php
require_once("../Conexxx.php");

$idCLi=r("id_usu");
$placa=r("placa");
$modelo=r("modelo");
$marca=r("marca");
$idRuta=r("ruta");

if(!empty($idCLi) && !empty($placa) && !empty($modelo) && !empty($idRuta))
{

	$sql="INSERT IGNORE INTO camion(id_usu,placa,modelo,marca,id_ruta,cod_su) VALUES('$idCLi','$placa','$modelo','$marca','$idRuta','$codSuc')";
	$rs=$linkPDO->exec($sql);
	
	$codSede=3;
$sqlSede="INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'ajustes',  '1',  '10000',  '$codSede'
);

INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'cartera_ant',  '1',  '10000',  '$codSede'
);
INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'comprobante anticipo',  '1',  '100000',  '$codSede'
);

INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'comprobante ingreso',  '1',  '10000',  '$codSede'
);

INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'comp_egreso',  '1',  '10000',  '$codSede'
);

INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'credito',  '754',  '1500',  '$codSede'
);

INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'expedientes',  '1',  '100000',  '$codSede'
);
INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'factura compra',  '1',  '40000',  '$codSede'
);
INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'factura dev',  '1',  '10000000',  '$codSede'
);

INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'factura venta',  '1',  '50000',  '$codSede'
);

INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'Inventario Inicial',  '1',  '10000',  '$codSede'
);

INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'ref',  '1',  '10000',  '$codSede'
);

INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'remision',  '1',  '100000',  '$codSede'
);

INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'remision_com',  '1',  '100000',  '$codSede'
);

INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'resol_papel',  '1',  '1000',  '$codSede'
);
INSERT INTO  `seriales` (
`id_serial` ,
`seccion` ,
`serial_inf` ,
`serial_sup` ,
`nit_sede`
)
VALUES (
NULL ,  'traslado',  '1',  '100000',  '$codSede'
);
";
	if($rs)
	{
	echo "1";	
	}
	else echo "0";
	
}
else echo 2;

?>