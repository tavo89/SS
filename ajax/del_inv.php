<?php
require_once("../Conexxx.php");

$id=limpiarcampo($_REQUEST['id']);
$idPro=r('idPro');
$feVen=limpiarcampo($_REQUEST['feVen']);
if(empty($feVen)){$feVen="0000-00-00";}

$sql="SELECT id_pro FROM inv_inter WHERE  id_pro='$idPro'";
$rs=$linkPDO->query($sql);
$nr=$rs->rowCount();;

$del="DELETE FROM inv_inter WHERE id_inter='$id' AND id_pro='$idPro' AND nit_scs='$codSuc' AND fecha_vencimiento='$feVen'";
t1($del);

if($nr==1){
	$del="DELETE FROM ".tabProductos." WHERE  id_pro='$idPro'";
	t1($del);
}


echo "Eliminado";

?>