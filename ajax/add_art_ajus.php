<?php
require_once("../Conexxx.php");
//header("Content-Type: text/html; charset=UTF-8");
$ref=limpiarcampo($_REQUEST['ref']);
$codBar=r("codBar");
$feVencimiento=limpiarcampo($_REQUEST['fe_ven']);

$refOPT="";
$feVenOPT="";
$rowsSql="exist,i.id_pro ref,detalle,precio_v,iva,costo,gana,color,talla,fab,id_inter,dcto3,fraccion,unidades_frac,fecha_vencimiento,p.presentacion as prese";

if(!empty($ref))$refOPT="id_pro='$ref' AND ";

if(!empty($feVencimiento) && $feVencimiento!="0000-00-00")$sql="SELECT $rowsSql FROM ".tabProductos." p INNER JOIN (SELECT * FROM inv_inter WHERE $refOPT inv_inter.id_inter='$codBar' AND fecha_vencimiento='$feVencimiento' AND nit_scs=$codSuc ) AS i ON i.id_pro=p.id_pro";

else $sql="SELECT $rowsSql FROM ".tabProductos." p INNER JOIN (SELECT * FROM inv_inter WHERE $refOPT  inv_inter.id_inter='$codBar' AND nit_scs=$codSuc  ) AS i ON i.id_pro=p.id_pro";

//echo $sql;
$rs=$linkPDO->query($sql);
//$nr2=$rs->rowCount();;
$resp=1;
$filtro="";
if($row=$rs->fetch())
{
if(0){
	$des = $row["destalle"];
	if($usar_fecha_vencimiento==1){$feVenOPT="detalle='$des' ";$filtro=$feVenOPT;}
	else $filtro=$refOPT;
	if(!empty($des))$rs=$linkPDO->query("SELECT $rowsSql FROM ".tabProductos." p INNER JOIN inv_inter i ON i.id_pro=p.id_pro WHERE  nit_scs=$codSuc  AND ($feVenOPT  ) ");	
	$nr=$rs->rowCount();;
	if($nr>1){
		$resp=2;
		$rs=$linkPDO->query($sql);
		}
	else $rs=$linkPDO->query($sql);

}
	
}
$nr2=$rs->rowCount();;
if($nr2>1)$resp=2;
$rs=$linkPDO->query($sql);


if($row=$rs->fetch()){
	        
	        $cant = $row["exist"]*1;
            $des = $row["detalle"];
			$presentacion = $row["prese"]; 
			$frac=$row['fraccion'];
			$uni=$row['unidades_frac'];
			$feVen=$row['fecha_vencimiento'];
			$id = $row["ref"];
			$pvp =$row["precio_v"]*1;
			$iva = $row["iva"];
			$costo= $row["costo"]*1;
			$ref=$row['ref'];
			$codBar=$row['id_inter'];
			$util=$row['gana']*1;
			if($util>=40)$util=($util-20)/100;
			else if ($util<40&&($util-20)>20)$util=($util-20)/100;
			else $util=(20)/100;
			
			//$valBase=($costo/($util/((1-$util))))*((1+$iva)/100);
			
			if($iva==0)$valBase=($costo/(1-$util));
			else $valBase=($costo/(1-$util))*(1+($iva/100));
			$util=$row['gana'];
			//$html=htmlentities("$cant|$ref|$des|$iva|$pvp|$costo|$valBase|$util", ENT_QUOTES,"$CHAR_SET");
			$html="$cant|$ref|$des|$iva|$pvp|$costo|$valBase|$util|$codBar|$presentacion|$frac|$uni|$feVen|$resp";
			$html=limpiarcampo($html);
			echo $html;
			
}
else {echo "-2020";}
   ?>