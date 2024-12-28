<?php
include_once("../Conexxx.php");
$nit = $_REQUEST["nit"];
$rs=$linkPDO->query("select * from provedores where nit='$nit';");
if($row=$rs->fetch()){
	
	$nom=$row["nom_pro"];
	$dir = $row["dir"]; 
	$tel = $row["tel"];
	$cuidad = $row["ciudad"];
	$cc=$row['nit'];
 
	$mail=$row['mail']; 
			
	$snombr=$row["snombr"];
	$apelli=$row["apelli"];
	
	$claper=$row["claper"];
	
	$coddoc=$row["coddoc"];
	$paicli=$row["paicli"];
	$depcli=$row["depcli"];
	$regFiscal=$row["regFiscal"];
	$nomcon=$row["nomcon"];
	$regtri=$row["regtri"];
	$razsoc=$row["razsoc"];	


//echo ($row["nom_pro"]."?".$row["dir"]."?".$row["tel"]."?".$row["mail"]."?".$row["ciudad"]."?".$row["fax"]);

$return_array[]=array("respuesta"=>1,
						"nombre"=>$nom,
						"direccion"=>$dir,
						"ciudad"=>$cuidad,
						"telefono"=>$tel,
						"email"=>$mail,
						"fax"=>$row["fax"],
						"nit"=>$nit,
						"snombr"=>$snombr,
						"apelli"=>$apelli,
						"claper"=>$claper,
						"coddoc"=>$coddoc,
						"paicli"=>$paicli,
						"depcli"=>$depcli,
						"regFiscal"=>$regFiscal,
						"nomcon"=>$nomcon,
						"regtri"=>$regtri,
						"razsoc"=>$razsoc
						 
						);
						

echo json_encode($return_array);
}
else{$return_array[]=array("respuesta"=>0,
						"nombre"=>"",
						"direccion"=>"",
						"ciudad"=>"",
						"telefono"=>"",
						"email"=>"",
						"nit"=>"",
						"snombr"=>"",
						"apelli"=>"",
						"claper"=>"",
						"coddoc"=>"",
						"paicli"=>"",
						"depcli"=>"",
						"regFiscal"=>"",
						"nomcon"=>"",
						"regtri"=>"",
						"razsoc"=>""
						
						);
						
echo json_encode($return_array);}

?>