<?php
//admin@interplustel.com:PvJgMdN4bEqTjLv2zyWTIFX8WgzPdUFS
include("../../Conexxx.php");
include("SMS_lib.php");
$credenciales=credenciales_sms();

$auth_basic = base64_encode("$credenciales[usuario]:$credenciales[clave]");

$SMS=$_REQUEST["mensaje"];
$SMS = str_replace(array("\r\n", "\r", "\n"), "\\n", $SMS);
 
$telefonos=r_array("telefonos");
$telefonosRutas=r_array("telefonosRutas");

//echo "$SMS";
$filtroGruposDifusionClientes=multiSelcSql($telefonos,"id_grupo_difusion");
$filtroRutasClientes=multiSelcSql($telefonosRutas,"id_ruta");

print_r($telefonos);
$sql="select * from usuarios a INNER JOIN servicio_internet_planes b ON a.id_usu=b.id_cli AND b.habilitado=1 WHERE a.cod_su=$codSuc AND tel!='' $filtroGruposDifusionClientes $filtroRutasClientes GROUP BY a.tel";
if(!empty($telefonos)){
if(in_array(4,$telefonos)){
$subQUERY="select a.tel,b.id_cli from usuarios a INNER JOIN servicio_internet_planes b ON a.id_usu=b.id_cli AND b.habilitado=1 WHERE a.cod_su=$codSuc AND tel!=''  GROUP BY a.id_usu";

$sql="SELECT a.tel,a.id_cli,$limite_pago_facturas AS fecha_lim,  $dia_suspension AS fecha_sus FROM ($subQUERY) a INNER JOIN fac_venta b ON a.id_cli=b.id_cli AND b.anulado!='ANULADO' AND b.estado='PENDIENTE' WHERE $limite_pago_facturas<DATE(NOW()) GROUP BY a.tel";	
}
}

 
//echo "<li>$sql</li>";
$rs=$linkPDO->query($sql);
$numRows=num_rows($rs);
//echo "$numRows<br>";
$telArray="";
$i=0;
while($row=$rs->fetch()){
	$i++;
	$telCliente=limpianum3($row["tel"]);
	if($i<$numRows){$telArray.="{\"msisdn\":\"57$telCliente\"},";}
	else {$telArray.="{\"msisdn\":\"57$telCliente\"}";}
}

//echo "$telArray";

$RESPUESTA=envia_SMS($SMS,$telArray,$auth_basic);




?>