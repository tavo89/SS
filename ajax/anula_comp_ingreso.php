<?php
require_once("../Conexxx.php");


$FILTRO_SEDES_FAC="AND nit=$codSuc";
$FILTRO_SEDES_COD_SU="AND cod_su=$codSuc";

if($MODULES["MULTISEDES_UNIFICADAS"]==1){$FILTRO_SEDES_FAC="";$FILTRO_SEDES_COD_SU="";}

if($rolLv==$Adminlvl || val_secc($id_Usu,"anular_comp_ingreso")){
date_default_timezone_set('America/Bogota');
$hoy = gmdate("Y-m-d H:i:s",hora_local(-5));
$qry="";

$num_comp=limpiarcampo($_REQUEST['num_comp']);

$tabla="comprobante_ingreso";
$colPK="num_com";
$colEstado="anulado";
$colFechaAnula="fecha_anula";
$fechaLIM="DATEDIFF(CURDATE(),fecha)<$dias_anula_comps";
$Cliente = new Clientes();

$sql="SELECT * FROM $tabla WHERE $colPK='$num_comp'  AND cod_su='$codSuc'";
//echo $sql."<br>";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){
	
	        $estado=$row['anulado'];
			//$estado="";
			
			if($estado!="ANULADO"){
				
			$sql="SELECT * FROM $tabla WHERE $colPK='$num_comp' AND cod_su=$codSuc AND $fechaLIM";
            //echo $sql."<br>";
            $rs=$linkPDO->query($sql);
			if($row=$rs->fetch()){
			
			//$NUM_EXP=$row['num_exp'];
			$qryA="UPDATE $tabla SET $colEstado='ANULADO', $colFechaAnula=CURDATE() WHERE $colPK='$num_comp'  AND cod_su=$codSuc";
			t1($qryA);
			
			$idCta=$row["id_cuenta"];
			$tot=$row["valor"]-$row["r_fte"]-$row["r_ica"];
			$form_p=$row["forma_pago"];
			up_cta($form_p,$idCta,$tot,"-","ANULA Abono Cartera No.$num_comp","Cartera Clientes",$hoy);
			
			$sql_1="SELECT * FROM cartera_mult_pago WHERE num_comp='$num_comp' AND cod_su=$codSuc";
			//echo "$sql_1";
	  		$rs_1=$linkPDO->query($sql_1);
			while($row_1=$rs_1->fetch())
			{
				$num_fac=$row_1["num_fac"];$pre=$row_1["pre"];
				$qryB="UPDATE fac_venta SET estado='PENDIENTE' where num_fac_ven=$num_fac AND prefijo='$pre' $FILTRO_SEDES_FAC";
				//echo "$qryB";
				t1($qryB);
				$Cliente->setActualizaPagoPlan($num_fac,$pre,$codSuc,"anular");

			}
			
			$qryC="UPDATE cartera_mult_pago SET estado='ANULADO' where num_comp='$num_comp' AND cod_su=$codSuc";
			t1($qryC);
			}
			else {echo "-1";}
			}
			
			else if($estado=="ANULADO"){echo "0";}
	
}
else {echo "-3";}

}
   ?>