<?php
require_once("../Conexxx.php");
 
$ID=r("id_compra");

$rs=$linkPDO->query("SELECT * FROM fac_com WHERE serial_fac_com='$ID'");

if($row=$rs->fetch()){



			$return_array[]=array("respuesta"=>1,
						"subtot"=>$row["subtot"],
						"iva"=>$row["iva"],
						"tot"=>$row["tot"]
						);
						
echo json_encode($return_array);

}
else{
				$return_array[]=array("respuesta"=>0,
						"subtot"=>0,
						"iva"=>0,
						"tot"=>0
						);
echo json_encode($return_array);
	}

?>