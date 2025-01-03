<?php
error_reporting(E_ERROR | E_PARSE);
include("DB.php");
$usar_fecha_vencimiento=0;
$fechaKardex="2012-01-01";
include("offline_LIB.php");


foreach($SUB_DOMINIOS as $index => $val)
{
	//&& ($index>=1 && $index<=28)
if($SUB_DOMINIOS[$index]!="127.0.0.1/SS/" ){
echo "<h1>[$index] => $val |".$CREDENCIALES[$val]["db"]."</h1>";

$BaseDatos=$CREDENCIALES[$val]["db"];
$USU=$CREDENCIALES[$val]["usu"];
$CLA=$CREDENCIALES[$val]["cla"];
$HOST="127.0.0.1";
 

$link = new mysqli("$HOST", "$USU", "$CLA", "$BaseDatos");
if ($link->connect_errno) {
    echo "<br> Falló la conexión a MySQL[".$CREDENCIALES[$val]["db"]."]: (" . $link->connect_errno . ") " . $link->connect_error;
}
/////////////////////////////////////// PDO //////////////////////////////////////////////////////
try {
  $linkPDO = new PDO("mysql:host=$HOST;dbname=".$CREDENCIALES[$val]["db"]."", $USU, $CLA, 
      array(PDO::ATTR_PERSISTENT => true));
	  $linkPDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //echo "Connected\n";
} catch (Exception $e) {
  die("Unable to connect: " . $e->getMessage());
}




$conex=$link;

$sql="SELECT * FROM x_config WHERE 1=1";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch()){
	
$_SESSION[$row["des_config"]]=$row["val"];		
	
}
echo "<br>tipo: ".$_SESSION["TIPO_CHUZO"]."<br>";
$usar_fecha_vencimiento=0;
$fechaKardex="2000-05-07";
ini_set('memory_limit', '2048M');
if($_SESSION["TIPO_CHUZO"]=="DRO"){$usar_fecha_vencimiento=1;}


$sql="SELECT * FROM sucursal ";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch()){
if($row['estado_pago']=='Inhabilitado') {
	continue;
}
set_time_limit(300);
$codSuc=$row['cod_su'];
$nomNegocio=$row['nom_negocio'];

echo "<li>$nomNegocio - $codSuc</li>";




////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*  CODIGO SQL ACA   */



try{
	$SQL="
	INSERT INTO `x_config` (`id_config`, `des_config`, `val`, `cod_su`, `user_mod`, `nota_uso`) VALUES (NULL, 'modoPruebas', '0', '1', '0', '1 | 0 Activa/Desactiva modo de testeo, para que los usuarios aprendan a usar la App');

	INSERT INTO `x_config` (`id_config`, `des_config`, `val`, `cod_su`, `user_mod`, `nota_uso`) VALUES (NULL, 'fac_ven_etiqueta_nogravados', 'NO GRAVADO', '1', '0', 'NO GRAVADO | EXCLUIDO');

	INSERT INTO `secciones` (`id_secc`, `des_secc`, `modulo`, `habilita`) VALUES ('refacturar_fe', 'Re factuar POS a Electronica', 'Ventas', '1');

	INSERT INTO `x_config` (`id_config`, `des_config`, `val`, `cod_su`, `user_mod`, `nota_uso`, `nota`) VALUES (NULL, 'mod_resolucion', '0', '1', '0', '0 | 1', 'habilita opc. para cambiar la resolucion de una factura ya hecha');

	ALTER TABLE `permisos` ADD UNIQUE(`id_usu`, `id_secc`, `permite`);

	INSERT INTO `x_config` (`id_config`, `des_config`, `val`, `cod_su`, `user_mod`, `nota_uso`) VALUES (NULL, 'habilitaCorteInventario', '0', '1', '0', '0 | 1 habilita corte inventario en compras');



	ALTER TABLE `x_config` ADD `nota_uso` VARCHAR(250) NOT NULL COMMENT 'Nota' AFTER `user_mod`;
	INSERT INTO `x_config` (`id_config`, `des_config`, `val`, `cod_su`, `user_mod`, `nota_uso`, `nota`) VALUES (NULL, 'formatoFacturaDefecto', '0', '1', '2', 'POS, COM', 'Formato por defecto de las facturas al cerrar una venta');



	INSERT INTO `x_config` (`id_config`, `des_config`, `val`, `cod_su`, `user_mod`, `nota_uso`, `nota`) VALUES (NULL, 'vende_a_costo', '1', '1', '2', 'Permite vender a precio de costo', '');

";

	echo "<li>1 >>$SQL</li>";
	mysqli_multi_query($link, $SQL);

	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	/*
	$S= explode(';', trim($SQL));
	foreach($S as $key => $val){
		set_time_limit(90);
		echo "<li>1 >>$val</li>";
		
		if(!empty($val)){query($val);}
	}
	*/

}
catch(Exception $e){
	
}
 

echo "----------------------------------------------------------------------------------------------------------------------------------------------------<br>";

}






//
	}
}
 
 

?>