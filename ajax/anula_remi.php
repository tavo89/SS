<?php
require_once("../Conexxx.php");

date_default_timezone_set('America/Bogota');
$hoy = gmdate("Y-m-d H:i:s",hora_local(-5));
$qry="";

try {  

ini_set('memory_limit', '1024M'); 
$linkPDO->beginTransaction();
$all_query_ok=true;


$codOrig=r("orig");
$num_fac=r('num_fac');
$pre=r("pre");
$pre=mb_strtoupper($pre,"$CHAR_SET");

$sql="SELECT * FROM fac_remi WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit='$codOrig' FOR UPDATE";
//echo $sql."<br>";
$rs=$linkPDO->query($sql);
if($row= $rs->fetch() ){
	
	        $sql="SELECT * FROM fac_remi WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND anulado!='ANULADO' AND nit=$codOrig FOR UPDATE";
            //echo $sql."<br>";
            $rs=$linkPDO->query($sql);
			if($row=$rs->fetch()){
				$karDex=$row['kardex'];
				$tipoRemi=$row["tipo_cli"];
				$codDest=$row["sede_destino"];
				$estadoTras=$row["estado"];
				
			$sql="SELECT * FROM fac_remi WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit=$codOrig FOR UPDATE";
            //echo $sql."<br>";
            $rs=$linkPDO->query($sql);
			if($row=$rs->fetch()){
			//$NUM_EXP=$row['num_exp'];
			$qryA="UPDATE fac_remi SET anulado='ANULADO', fecha_anula='$hoy', usu='$nomUsu',id_usu='$id_Usu',modifica='$nomUsu ANULA FAC. $pre-$num_fac' WHERE num_fac_ven=$num_fac AND prefijo='$pre' AND nit=$codOrig";

			$linkPDO->exec($qryA);
			
if($FLUJO_INV==1 && $karDex==1){
$qryB="UPDATE `inv_inter` i 
INNER JOIN 
(select ar.nit nitAr,sum(cant) cant,ref,cod_barras,fraccion,unidades_fraccion,fecha_vencimiento from art_fac_remi ar inner join (select * from fac_remi f WHERE num_fac_ven=$num_fac AND prefijo='$pre'  ) fv ON fv.num_fac_ven=ar.num_fac_ven WHERE fv.nit=ar.nit and fv.nit=$codOrig and fv.prefijo=ar.prefijo group by ar.cod_barras,ar.fecha_vencimiento,ar.ref) a 
ON i.id_inter=a.cod_barras 
SET i.exist=(i.exist+a.cant), i.unidades_frac=(i.unidades_frac+a.unidades_fraccion) WHERE i.nit_scs=a.nitAr and i.nit_scs=$codOrig AND i.fecha_vencimiento=a.fecha_vencimiento AND i.id_pro=a.ref";
$linkPDO->exec($qryB);

if($tipoRemi=="Traslado" && $estadoTras=="Recibido"){
	
$sql="UPDATE `inv_inter` i 
INNER JOIN 
(select ar.nit nitAr,sum(cant) cant,ref,cod_barras,fraccion,unidades_fraccion,fecha_vencimiento from art_fac_remi ar inner join (select * from fac_remi f WHERE num_fac_ven=$num_fac AND prefijo='$pre'  ) fv ON fv.num_fac_ven=ar.num_fac_ven WHERE fv.nit=ar.nit and fv.nit=$codOrig and fv.prefijo=ar.prefijo group by ar.cod_barras,ar.fecha_vencimiento,ar.ref) a 
ON i.id_inter=a.cod_barras 
SET i.exist=(i.exist-a.cant), i.unidades_frac=(i.unidades_frac-a.unidades_fraccion) WHERE   i.nit_scs=$codDest AND i.fecha_vencimiento=a.fecha_vencimiento AND i.id_pro=a.ref";
$linkPDO->exec($sql);
	}
}

if($all_query_ok){
$linkPDO->commit();

$rs=null;
$stmt=null;
$linkPDO= null;

}
else{echo "ERROR";}			
			
			}
			else {echo "-1";}
			}
			
			
			else {echo "0";}
	
}
else {echo "-2";}

}catch (Exception $e) {
  $linkPDO->rollBack();
  echo "Failed: " . $e->getMessage();
}
?>