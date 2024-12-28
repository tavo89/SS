<?php
require_once("../Conexxx.php");
$ref=limpiarcampo($_REQUEST['ref']);
$sql="SELECT exist,inv_inter.id_pro ref,detalle,precio_v,costo,iva,gana FROM ".tabProductos." INNER JOIN inv_inter ON inv_inter.id_pro=".tabProductos.".id_pro WHERE  (inv_inter.id_pro='$ref' OR inv_inter.id_inter='$ref') AND nit_scs=$codSuc";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){
	        $id_pro=$row['ref'];
	        $cant = $row["exist"];
            $des = $row["detalle"]; 
			$id = $row["ref"];
			$costo=$row['costo'];
			$pvp = $row["precio_v"];
			$iva = $row["iva"];
			$gan=$row['gana'];
			//if($cant>0)
			echo limpiarcampo("$cant|$ref|$des|$costo|$iva|$gan|$pvp|$id_pro");
			//else echo "0";
}
else {echo "-2";}
   ?>