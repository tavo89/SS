<?php
require_once("../Conexxx.php");

$ref=limpiarcampo($_REQUEST['ref']);
$codBar=r("codBar");
$fechaVen=r('fecha_ven');
$num_fac=limpiarcampo($_REQUEST['num_fac']);
$nitPro=limpiarcampo($_REQUEST['nit_pro']);
$ID=r("id");
if(empty($fechaVen))$fechaVen="0000-00-00";

//ref='$ref' AND cod_barras='$codBar' AND fecha_vencimiento='$fechaVen' AND num_fac_com='$num_fac' AND cod_su=$codSuc AND nit_pro='$nitPro' AND 

$del="DELETE FROM art_fac_com WHERE id='$ID'";
 
$linkPDO->exec($del);


?>