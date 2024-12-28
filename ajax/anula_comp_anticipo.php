<?php
require_once("../Conexxx.php");

if($rolLv==$Adminlvl || val_secc($id_Usu,"anula_comp_egreso")){
date_default_timezone_set('America/Bogota');
$hoy = gmdate("Y-m-d H:i:s",hora_local(-5));
$qry="";

$num_comp=limpiarcampo($_REQUEST['num_comp']);

$tabla="comp_anti";
$colPK="num_com";
$colEstado="estado";
$colFechaAnula="fecha_anula";
$fechaLIM="DATEDIFF(CURDATE(),fecha)<$dias_anula_comps";

$sql="SELECT * FROM $tabla WHERE $colPK='$num_comp'  AND cod_su='$codSuc'";
//echo $sql."<br>";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){
	
	        $estado=$row['estado'];
			$valor=$row['valor'];
			$exp=$row['num_exp'];
			if($estado!="ANULADO"&&$estado!="COBRADO"){
				
			$sql="SELECT * FROM $tabla WHERE $colPK='$num_comp' AND cod_su=$codSuc AND $fechaLIM";
			
			
            //echo $sql."<br>";
            $rs=$linkPDO->query($sql);
			if($row=$rs->fetch()){
			//$NUM_EXP=$row['num_exp'];
			$idCta=$row["id_cuenta"];
			$tot=$row["valor"];
			$form_p="Penes Enormes, venosos y arenosos";
			up_cta($form_p,$idCta,$tot,"-","ANULA Bono/Apartado #:$num_comp","Anticipos",$hoy);
			
			$qryA="UPDATE $tabla SET $colEstado='ANULADO', $colFechaAnula=CURDATE() WHERE $colPK='$num_comp'  AND cod_su=$codSuc";
			
			$qryB="UPDATE exp_anticipo SET tot=tot-$valor WHERE num_exp=$exp";
			t2($qryA,$qryB);
					
			}
			else {echo "-1";}
			}
			
			else if($estado=="ANULADO"){echo "0";}
			else if($estado=="COBRADO"){echo "-4";}
	
}
else {echo "-3";}

}
   ?>