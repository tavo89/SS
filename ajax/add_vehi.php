<?php
require_once("../Conexxx.php");

$TABLA="vehiculo";
$COL_ID_TABLA="placa";

$UNIC_INDEX=rm("placa_ve");
$modelo=rm("modelo_ve");
$color=rm("color_ve");
$idProp=rm("id_prop");
$nomProp=rm("nom_prop");
$telProp=rm("tel_prop");

try { 
$linkPDO->beginTransaction();
$all_query_ok=true;


if(!empty($UNIC_INDEX) &&!empty($modelo)&&!empty($color)&&!empty($idProp)){
if(!empty($idProp)){
$sql="INSERT IGNORE INTO usuarios(id_usu,nombre,tel,cliente,cod_su) VALUES('$idProp','$nomProp','$telProp',1,'$codSuc')";
$linkPDO->exec($sql);

}

$sql="SELECT * FROM $TABLA WHERE $COL_ID_TABLA='$UNIC_INDEX'";
//echo "$sql";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){echo "2";}
else{
	
	$sql="INSERT INTO $TABLA(placa,modelo,color,id_propietario,cod_su) VALUES('$UNIC_INDEX','$modelo','$color','$idProp','$codSuc')";
	$linkPDO->exec($sql);
	
	
	
	
	
$linkPDO->commit();

$rs=null;
$stmt=null;
$linkPDO= null;

if($all_query_ok){
echo "1";
}
else{eco_alert("ERROR! Intente nuevamente");}

}

}
	else {echo "0";}
	
	}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}


   ?>