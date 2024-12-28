<?php
require_once("../Conexxx.php");

if($rolLv==$Adminlvl || val_secc($id_Usu,"anula_comp_egreso")){
date_default_timezone_set('America/Bogota');
$hoy = gmdate("Y-m-d H:i:s",hora_local(-5));
$qry="";

$num_comp=limpiarcampo($_REQUEST['num_comp']);

$tabla="comp_egreso";
$colPK="num_com";
$colEstado="anulado";
$colFechaAnula="fecha_anula";
$fechaLIM="DATEDIFF(CURDATE(),fecha)<500";

$sql="SELECT * FROM $tabla WHERE $colPK='$num_comp'  AND cod_su='$codSuc' FOR UPDATE";
//echo $sql."<br>";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){
	
	        $estado=$row['anulado'];
			if($estado!="ANULADO"){
				
			$sql="SELECT * FROM $tabla WHERE $colPK='$num_comp' AND cod_su=$codSuc AND $fechaLIM FOR UPDATE";
            //echo $sql."<br>";
            $rs=$linkPDO->query($sql);
			if($row=$rs->fetch()){
				$idCOM=$row['serial_fac_com'];
				$idCuenta=$row['id_cuenta'];
				$idCtaTrans=$row['id_cuenta_trans'];
				$valor=$row["valor"]-$row["r_fte"]-$row["r_ica"];
				$tipo_pago=$row["tipo_pago"];
				
				
				
				up_cta($tipo_pago,$idCuenta,$valor,"+","ANULA Gasto/Egreso Comp:$num_comp","Egresos",$hoy);
				
			 	if($idCtaTrans!=0){up_cta($tipo_pago,$idCtaTrans,$valor,"-","ANULA  TRANSFERENCIA Comp:$num_comp","Egresos",$hoy);}
			//$NUM_EXP=$row['num_exp'];
			$qryA="UPDATE comp_egreso SET $colEstado='ANULADO', $colFechaAnula=CURDATE() WHERE $colPK='$num_comp'  AND cod_su=$codSuc";
			$sql2="UPDATE fac_com SET pago='PENDIENTE' WHERE serial_fac_com=$idCOM AND cod_su='$codSuc'";
			t1($qryA);
			if($idCOM!=0)t1($sql2);
					
			}
			else {echo "-1";}
			}
			
			else if($estado=="ANULADA"){echo "0";}
	
}
else {echo "-3";}

}

$rs=null;
$stmt=null;
$linkPDO= null;

   ?>