<?php 
include("STAND_ALONE_CONEXXX.php");

//****************************************************************** P A R A M E T R O S*************************************************************//
require_once("param_rotacion.php");
//**************************************************************************************************************************************************//

//$sql = "SELECT  * FROM vista_inventario  WHERE  $F2 $D $C ORDER BY $order  LIMIT $offset, $limit"; 

$sql = "SELECT  * FROM vista_inventario  WHERE  $F2 $D $C ORDER BY $order  "; 


$rs = $linkPDO->query($sql);

$sqlTotal = "SELECT COUNT(*) as total FROM vista_inventario WHERE $F2 $D $C ORDER BY $order";  
$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 


$bgColor="";
$rotacion=0;
$IR=0;
//echo last_day($FechaHoy);
$s="TRUNCATE TABLE rotacion_inv;";
$linkPDO->exec($s);

$sqlINSERT="INSERT IGNORE INTO  `rotacion_inv` (`ref` ,`cod_bar` ,`des` ,`clase` ,`fab` ,`costo` ,`pvp` ,`iva` ,`cod_su` ,`proveedor` ,`color` ,`talla` ,`exist` ,`min` ,`max` ,`CP` ,`Pp` ,`tot_ventas` ,`fecha_i` ,`fecha_f`)
VALUES ";

$izz=0;
//while ($row = $rs->fetch()) 

//echo "$total";
while ($row = $rs->fetch()) 
{ 
$izz++;

			$nit_scs=$row['nit_scs'];
		    $rotacion=0;
			$IR=0;
            $id_inter =$row["id_glo"]; 
            $des =$row["detalle"]; 
			$clase = $row["id_clase"];
			$id = $row["id_sede"];
			$frac = $row["fraccion"];
			$fab = $row["fab"];
			$costo=$row['costo']*1; 
			$exist=$row['exist'];
			/*
			$promInv=promedio_inventario($codSuc,$FECHA_I,$FECHA_F,$id,$Cp[$id]);
			//if($promInv<=0)$promInv=1;
			if($promInv!=0){$IR=redondeo3(($tot_vendidos[$id]*$costo)/($promInv*$costo));}
			else $IR=0;
			
			//if($IR!=0)$rotacion=redondeo3($DIAS/$IR);
			
			if($tot_vendidos[$id]>0&&$promInv!=0)$rotacion=redondeo($DIAS*(($promInv*$costo) / ($tot_vendidos[$id]*$costo)));
			else $rotacion=0;
			*/
			if($DIAS>0)$minSeg[$id][$nit_scs]=(sqrt($xyz[$id][$nit_scs]/$DIAS)) * $Z*$t;
			else $minSeg[$id][$nit_scs]=0;
			//$minSeg[$r][$nit_scs]=$Z*$t;
			$Emn[$id][$nit_scs]=redondeo(($Cp[$id][$nit_scs])+$minSeg[$id][$nit_scs]);
			
			$IR=$tot_vendidos[$id][$nit_scs];
			$rotacion=0;
			//$rotacion=redondeo3($promInv);
			//$rotacion=$promInv;
			
			/*if($promInv>0)$a=$tot_vendidos[$id]/$promInv;
			else $a=0;
			
			if($a>0)$diasRotacion=$DIAS/$a;
			else $diasRotacion=0;
			*/
			//$diasRotacion=45;
			
			if($DIAS>0)$Emx[$id][$nit_scs]=redondeo(($diasRotacion/$DIAS)*$tot_vendidos[$id][$nit_scs]);
			else $Emx[$id][$nit_scs]=0;
			
			
			$Pp[$id][$nit_scs]=redondeo($Cp[$id][$nit_scs]*$Tr +$Emn[$id][$nit_scs]);
			$CP[$id][$nit_scs]=$Emx[$id][$nit_scs] - $E[$id][$nit_scs];
			
			
			if($idx==$id)$bgColor="#999999";
			else $bgColor="#FFFFFF";
			
			if($Emx[$id][$nit_scs]==1)$Pp[$id][$nit_scs]=1;
			if($Emx[$id][$nit_scs]==0)$Pp[$id][$nit_scs]=0;
			if($Pp[$id][$nit_scs]>$Emx[$id][$nit_scs])$Pp[$id][$nit_scs]=redondeo($Cp[$id][$nit_scs]*$Tr);
			
			
			if($Emn[$id][$nit_scs]>$Emx[$id][$nit_scs])	
			{
		
		        $MinFlag=$Emn[$id][$nit_scs];
				$Emn[$id][$nit_scs]=$Emx[$id][$nit_scs];
				$Emx[$id][$nit_scs]=$MinFlag;
				}
			


if($Cp[$id][$nit_scs]<1){$Cp[$id][$nit_scs]=0;}

//  TRUNCATE TABLE rotacion_inv;


/*$sqlINSERT="INSERT IGNORE INTO  `rotacion_inv` (`ref` ,`cod_bar` ,`des` ,`clase` ,`fab` ,`costo` ,`pvp` ,`iva` ,`cod_su` ,`proveedor` ,`color` ,`talla` ,`exist` ,`min` ,`max` ,`CP` ,`Pp` ,`tot_ventas` ,`fecha_i` ,`fecha_f`)
VALUES (
'$id_inter',  '$row[id_sede]',  ' $des ',  '$row[id_clase]',  '$row[fab]',  '$row[costo]',  '$row[precio_v]',  '$row[iva]',  '$codSuc','$row[nit_proveedor]',  '$row[color]',  '$row[talla]',  '$row[exist]',  '".$Emn[$id][$nit_scs]."',  '".$Emx[$id][$nit_scs]."',  '".$CP[$id][$nit_scs]."',  '".$Pp[$id][$nit_scs]."',  '".$IR."',$FECHA_LIMITE_INI, $FECHA_LIMITE_FIN);";
*/






if($row["exist"]>0 || $IR>0){
set_time_limit(60);	
	/*
if($izz!=$total){$sqlINSERT.=" (
'$id_inter',  '$row[id_sede]',  ' $des ',  '$row[id_clase]',  '$row[fab]',  '$row[costo]',  '$row[precio_v]',  '$row[iva]',  '$codSuc','$row[nit_proveedor]',  '$row[color]',  '$row[talla]',  '$row[exist]',  '".$Emn[$id][$nit_scs]."',  '".$Emx[$id][$nit_scs]."',  '".$CP[$id][$nit_scs]."',  '".$Pp[$id][$nit_scs]."',  '".$IR."',$FECHA_LIMITE_INI, $FECHA_LIMITE_FIN),";}
else{
	$sqlINSERT.=" (
'$id_inter',  '$row[id_sede]',  ' $des ',  '$row[id_clase]',  '$row[fab]',  '$row[costo]',  '$row[precio_v]',  '$row[iva]',  '$codSuc','$row[nit_proveedor]',  '$row[color]',  '$row[talla]',  '$row[exist]',  '".$Emn[$id][$nit_scs]."',  '".$Emx[$id][$nit_scs]."',  '".$CP[$id][$nit_scs]."',  '".$Pp[$id][$nit_scs]."',  '".$IR."',$FECHA_LIMITE_INI, $FECHA_LIMITE_FIN);";}

*/
$sqlINSERT="INSERT IGNORE INTO  `rotacion_inv` (`ref` ,`cod_bar` ,`des` ,`clase` ,`fab` ,`costo` ,`pvp` ,`iva` ,`cod_su` ,`proveedor` ,`color` ,`talla` ,`exist` ,`min` ,`max` ,`CP` ,`Pp` ,`tot_ventas` ,`fecha_i` ,`fecha_f`)
VALUES (
'$id_inter',  '$row[id_sede]',  ' $des ',  '$row[id_clase]',  '$row[fab]',  '$row[costo]',  '$row[precio_v]',  '$row[iva]',  '$codSuc','$row[nit_proveedor]',  '$row[color]',  '$row[talla]',  '$row[exist]',  '".$Emn[$id][$nit_scs]."',  '".$Emx[$id][$nit_scs]."',  '".$CP[$id][$nit_scs]."',  '".$Pp[$id][$nit_scs]."',  '".$IR."',$FECHA_LIMITE_INI, $FECHA_LIMITE_FIN);";
	t1($sqlINSERT);
	}
		   		 
		 } ////////////////////////////////////// FIN WHILE QUERY ///////////////////////////////////////////////////
		
	
	
     ?>