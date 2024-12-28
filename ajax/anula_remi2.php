<?php
require_once("../Conexxx.php");

date_default_timezone_set('America/Bogota');
$hoy = gmdate("Y-m-d H:i:s",hora_local(-5));
$qry="";

$num_fac=limpiarcampo($_REQUEST['num_fac']);
$pre=mb_strtoupper(limpiarcampo($_REQUEST['pre'],"$CHAR_SET"));
$codSuc=r("cod_su");

$sql="SELECT * FROM fac_remi WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit='$codSuc'";
//echo $sql."<br>";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){
	
	        $sql="SELECT * FROM fac_remi WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND anulado!='ANULADO' AND nit=$codSuc";
            //echo $sql."<br>";
            $rs=$linkPDO->query($sql);
			if($row=$rs->fetch()){
				$karDex=$row['kardex'];
				$nf=$row["nf"];
				$PRE=$row["pre"];
			$sql="SELECT * FROM fac_remi WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit=$codSuc";
            //echo $sql."<br>";
            $rs=$linkPDO->query($sql);
			if($row=$rs->fetch()){
			//$NUM_EXP=$row['num_exp'];
			$qryA="UPDATE fac_remi SET anulado='ANULADO',estado='', fecha_anula='$hoy', usu='$nomUsu',id_usu='$id_Usu',modifica='$nomUsu ANULA FAC. $pre-$num_fac' WHERE num_fac_ven=$num_fac AND prefijo='$pre' AND nit=$codSuc";
			//echo $qry."<br>";
			//query($qryA);
if($FLUJO_INV==1 && $karDex==1){
$qryB="UPDATE `inv_inter` i 
INNER JOIN 
(select ar.nit nitAr,sum(cant) cant,ref,cod_barras,fraccion,unidades_fraccion,fecha_vencimiento from art_fac_remi ar inner join (select * from fac_remi f WHERE num_fac_ven=$num_fac AND prefijo='$pre'  ) fv ON fv.num_fac_ven=ar.num_fac_ven WHERE fv.nit=ar.nit and fv.nit=$codSuc and fv.prefijo=ar.prefijo group by ar.cod_barras,ar.fecha_vencimiento,ar.ref) a 
ON i.id_inter=a.cod_barras 
SET i.exist=(i.exist-a.cant), i.unidades_frac=(i.unidades_frac-a.unidades_fraccion) WHERE i.nit_scs=a.nitAr and i.nit_scs=$codSuc AND i.fecha_vencimiento=a.fecha_vencimiento AND i.id_pro=a.ref";
t2($qryA,$qryB);

anula_fac_ven($nf,$PRE,$codSuc);
}
else{
	t1($qryA);
	anula_fac_ven($nf,$PRE,$codSuc);
}

	$HTML_antes="";
	$HTML_despues="<div style='font-size:24px;'>REMISION <span style='color:red'>No. $num_fac - $pre</span> <b>ANULADA</b></div>";
	logDB($qryA."<br>$qryB",$SECCIONES[5],$OPERACIONES[2],$HTML_antes,$HTML_despues,$num_fac);

				/*$Q1="UPDATE exp_anticipo SET estado='FAC. ANULADA' WHERE cod_su=$codSuc AND num_exp=$NUM_EXP";
				$Q2="UPDATE comp_anti SET estado='FAC. ANULADA', fecha_anula=CURRENT_DATE() WHERE cod_su=$codSuc AND num_exp=$NUM_EXP";
				t2($Q1,$Q2);*/
//query($qryB);
			
			
			}
			else {echo "-1";}
			}
			
			
			else {echo "0";}
	
}
else {echo "-2";}
   ?>