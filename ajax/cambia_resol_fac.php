<?php
require_once("../Conexxx.php");

$nf=limpiarcampo($_REQUEST['num_fac']);
$pre=limpiarcampo($_REQUEST['pre']);
$tipoResol=r("resol");
$ID_FAC=r("id");

if($tipoResol=="POS")
	{
		$num_fac=serial_fac("factura venta","POS");
		$PRE=$codContadoSuc;
		$RESOL=$ResolContado;
		$fechaRESOL=$FechaResolContado;
		$RANGO_RESOL=$RangoContado;

		$sql="UPDATE fac_venta SET num_fac_ven='$num_fac',prefijo='$PRE', resolucion='$RESOL',rango_resol='$RANGO_RESOL',fecha_resol='$fechaRESOL',TIPDOC='' WHERE num_fac_ven='$nf' AND prefijo='$pre' AND nit='$codSuc'";
		t1($sql);
		
	}
	else if($tipoResol=="PAPEL"){
		/*
		$codPapelSuc=$RESOLUCIONES['PAPEL'][0];
$ResolPapel=$RESOLUCIONES['PAPEL'][1];
$FechaResolPapel=$RESOLUCIONES['PAPEL'][2];
$RangoPapel=$RESOLUCIONES['PAPEL'][3];
		*/
		$num_fac=serial_fac("resol_papel","PAPEL");
		$PRE=$codPapelSuc;
		$RESOL=$ResolPapel;
		$fechaRESOL=$FechaResolPapel;
		$RANGO_RESOL=$RangoPapel;
		$sql="UPDATE fac_venta SET num_fac_ven='$num_fac',prefijo='$PRE', resolucion='$RESOL',rango_resol='$RANGO_RESOL',fecha_resol='$fechaRESOL',TIPDOC='7' WHERE num_fac_ven='$nf' AND prefijo='$pre' AND nit='$codSuc'";
		t1($sql);
		
	}
$sqlArts = "UPDATE `art_fac_ven` SET num_fac_ven='$num_fac', prefijo='$PRE'  
            WHERE `num_fac_ven` = '$nf' AND prefijo='$pre' AND nit='$codSuc'";
t1($sqlArts);

$sql="UPDATE fac_venta SET num_fac_ven='$num_fac',prefijo='$PRE', resolucion='$RESOL',rango_resol='$RANGO_RESOL',fecha_resol='$fechaRESOL' 
      WHERE num_fac_ven='$nf' AND prefijo='$pre' AND nit='$codSuc'";
//echo $sql;
$sql2="INSERT INTO fac_ven_cambios(num_fac,pre,nf_o,pre_o,id_fac,id_usu,nom_usu,cod_su) VALUES('$num_fac','$PRE','$nf','$pre','$ID_FAC','$id_Usu','$nomUsu','$codSuc')";
//echo "$sql2";
t1($sql2);
echo "1";

?>