<?php
require("../Conexxx.php");
//$codSuc=1;
$sql="SELECT * FROM inv_inter a INNER JOIN (SELECT id_pro,detalle FROM productos ) b ON a.id_pro=b.id_pro  WHERE (exist<0 OR unidades_frac<0) AND nit_scs=$codSuc";
$rs=$linkPDO->query($sql);
$numRows=$rs->rowCount();
echo "<h1><b>Numero de registros:</b> $numRows</h1>";
echo "<table border=\"1\" rules=\"all\"><thead><td>PRODUCTO</td><td>REF</td><td>COD</td><td>FechaVen</td><td>CANT SYS</td><td>CANT alter</td></thead><tbody>";
while($row=$rs->fetch()){
	
$ref=$row['id_pro'];
$cod=$row['id_inter'];
$fe=$row['fecha_vencimiento'];
$cantidades=$row["exist"];
$fracciones=$row["unidades_frac"];
$des=$row['detalle'];

$sql2="SELECT * FROM inv_inter  WHERE (exist>=$cantidades OR unidades_frac>=$fracciones) AND (id_inter='$cod' AND fecha_vencimiento='$fe') AND nit_scs=$codSuc ORDER BY exist DESC LIMIT 1";
$rs2=$linkPDO->query($sql2);
if($row2=$rs2->fetch()){
$ref2=$row2['id_pro'];
$cod2=$row2['id_inter'];
$fe2=$row2['fecha_vencimiento'];
$cantidades2=$row2["exist"];
$fracciones2=$row2["unidades_frac"];
$s="UPDATE art_fac_ven SET ref='$ref2', fecha_vencimiento='$fe2' WHERE (cod_barras='$cod' AND ref='$ref' AND fecha_vencimiento='$fe') AND nit=$codSuc";
//echo "<tr><td colspan=\"6\">$s</td></tr>";
$linkPDO->exec($s);
		}

echo "<tr><td>$des</td><td>$ref</td><td>$cod</td><td>$fe</td><td> $cantidades ; $fracciones </td><td><B>$cantidades2 ; $fracciones2  </B></td></tr>";
}
echo "</tbody></table>";
?>