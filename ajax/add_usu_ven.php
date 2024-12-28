<?php
include_once("../Conexxx.php");
$ced=r('ced');

$sql="SELECT * FROM usuarios WHERE id_usu='$ced' OR nombre='$ced'";
$rs=$linkPDO->query($sql);
//echo "$sql";

if($row=$rs->fetch()){
	        	      
			$nom=$row["nombre"];
			$dir = $row["dir"]; 
			$tel = $row["tel"];
			$cuidad = $row["cuidad"];
			$cc=$row['id_usu'];
 
			$mail=$row['mail_cli'];
			$fecha=$row['fe_naci'];
			$topeCre=$row["tope_credito"];
			$auth=$row["auth_credito"];
			$tipoUsu=$row["tipo_usu"];
			
			$aliasCli=$row["alias"];
			
			$totCredito=limit_cre($cc);
		
	if($MODULES["FACTURACION_ELECTRONICA"]==1){	
	$snombr=$row["snombr"];
	$apelli=$row["apelli"];
	
	$claper=$row["claper"];
	
	$coddoc=$row["coddoc"];
	switch ($row['coddoc']) {
		case '13':
			$coddoc ='1';//CC
			break;
		case '22':
			$coddoc ='CE';
			break;
		case '31':
			$coddoc ='3';//NIT
			break;
		case '4':
			$coddoc ='3';
			break;
		case '11':
			$coddoc ='6';//Reg. civil
			break;
		case '12':
			$coddoc ='7';//T. identidad
			break;
		case '21':
			$coddoc ='8';// TE
			break;
		case '41':
			$coddoc ='9';//pasaporte
			break;
		case '42':
			$coddoc ='10';//Documento de identificacion extranjero
			break;
		default:
		$party_identification_type ='3';
	}

	$paicli=$row["paicli"];
	$depcli=$row["depcli"];
	$regFiscal=$row["regFiscal"];
	$nomcon=$row["nomcon"];
	$regtri=$row["regtri"];
	$razsoc=$row["razsoc"];
	}
	else
	{
	$snombr="";
	$apelli="";
	
	$claper="";
	
	$coddoc="";
	$paicli="";
	$depcli="";
	$regFiscal="";
	$nomcon="";
	$regtri="";
	$razsoc="";	
	}
			
			
			//$html=htmlentities("$nom|$dir|$tel|$cuidad|$mail|$fecha|$cc",ENT_QUOTES,"$CHAR_SET");
			//$html=htmlentities("$nom|$dir|$tel|$cuidad|$mail|$fecha|$cc");
			//      0	 1	   2	 3	 	4	  5	    6    7      8          9        10		  11
			$html="$nom|$dir|$tel|$cuidad|$mail|$fecha|$cc|$auth|$topeCre|$totCredito|$tipoUsu|$aliasCli";
		
			$return_array[]=array("respuesta"=>1,
						"nombreCompleto"=>"$nom $snombr $apelli",
						"nombre"=>$nom,
						"direccion"=>$dir,
						"ciudad"=>$cuidad,
						"tipo_cliente"=>$tipoUsu,
						"telefono"=>$tel,
						"email"=>$mail,
						"fecha_nacimiento"=>$fecha,
						"cc"=>$cc,
						"alias"=>$aliasCli,
						"tot_credito"=>$totCredito,
						"tope_credito"=>$topeCre,
						"autoriza_cre"=>$auth,
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
else {$return_array[]=array("respuesta"=>0,
						"nombre"=>"",
						"direccion"=>"",
						"ciudad"=>"",
						"tipo_cliente"=>"",
						"telefono"=>"",
						"email"=>"",
						"fecha_nacimiento"=>"",
						"cc"=>"",
						"tipo_usu"=>"",
						"alias"=>"",
						"tot_credito"=>"",
						"tope_credito"=>"",
						"autoriza_cre"=>"",
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