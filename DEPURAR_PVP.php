<?php
include("Conexxx.php");
try { 
$linkPDO->beginTransaction();
$all_query_ok=true;

$lim_01 = 'LIMIT 0, 20000';
$lim_02 = 'LIMIT 20001, 40000';
$lim_03 = 'LIMIT 40001, 60000';
$sql="SELECT * FROM inv_inter WHERE nit_scs='$codSuc' $lim_03";
$rs=$linkPDO->query($sql);
$i=0;
while($row=$rs->fetch()){
	$i++;
	$ref=$row["id_pro"];
	$codBar=$row["id_inter"];
	$util = 30;
	
	$linkPDO->exec("SAVEPOINT A$i");
	
	$sql="SELECT costo,precio,iva FROM `art_fac_ven` WHERE (ref='$ref' AND cod_barras='$codBar' AND nit='$codSuc') AND (precio>0)  ORDER BY `art_fac_ven`.`serial_art_fac` DESC LIMIT 1";
    
	$sql="SELECT costo,pvp,iva FROM `art_fac_com` WHERE (ref='$ref' AND cod_barras='$codBar' AND cod_su='$codSuc') AND (pvp>0)  ORDER BY `id` DESC LIMIT 1";
	

	echo " &nbsp;&nbsp;&nbsp; $sql <br>";
	$rs2=$linkPDO->query($sql);
	if($row2=$rs2->fetch()) {
	    $pvp =   $row2["pvp"];
	    $costo = $row2["costo"];
		$costoF = !empty($costo) && $costo!=0?$costo:round($pvp / ( ($row2["iva"]/100 + $util/100) + 1),2);
		$UP="UPDATE inv_inter SET precio_v='".$pvp."', costo='".$costoF."', iva='".$row2["iva"]."' 
             WHERE id_pro='$ref' AND id_inter='$codBar' AND nit_scs='$codSuc'";
		$linkPDO->exec($UP);
		echo " &nbsp;&nbsp;&nbsp; $UP <br>";
		
		
	}
	
}
$linkPDO->commit();
}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}


?>