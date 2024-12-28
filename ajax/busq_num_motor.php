<?php
require_once("../Conexxx.php");
$q = strtolower(limpiarcampo($_GET["q"]));
$FILTRO_1="(select num_motor FROM art_fac_ven a INNER JOIN fac_venta b ON a.num_fac_ven=b.num_fac_ven AND a.nit=b.nit AND b.".VALIDACION_VENTA_VALIDA." AND a.nit=$codSuc)";

$FILTRO_2="(select num_motor FROM art_fac_remi a INNER JOIN fac_remi b ON a.num_fac_ven=b.num_fac_ven AND a.nit=b.nit AND b.anulado!='ANULADO' AND a.nit=$codSuc)";

$FILTRO_3="(select num_motor FROM art_ajuste WHERE cod_su=$codSuc GROUP BY num_motor HAVING SUM(cant)<0)";


$sql2="SELECT num_motor,linea,modelo,cant FROM art_fac_remi a INNER JOIN fac_remi b ON a.num_fac_ven=b.num_fac_ven AND a.nit=b.nit AND a.prefijo=b.prefijo AND b.tipo_cli='Traslado' AND b.sede_destino=$codSuc WHERE (num_motor LIKE '%$q%' ) AND num_motor!='' AND num_motor NOT IN  $FILTRO_1 AND num_motor NOT IN $FILTRO_2 AND num_motor NOT IN $FILTRO_3 ";

$sql3="SELECT num_motor,linea,modelo,SUM(cant) as stot FROM art_ajuste WHERE (num_motor LIKE '%$q%' ) AND cod_su=$codSuc AND num_motor!=''  AND num_motor NOT IN $FILTRO_1 AND num_motor NOT IN $FILTRO_2 GROUP BY num_motor HAVING stot>0";

$sql="SELECT num_motor,linea,modelo,cant FROM art_fac_com WHERE (num_motor LIKE '%$q%' ) AND cod_su=$codSuc AND num_motor!=''  AND num_motor NOT IN $FILTRO_1 AND num_motor NOT IN $FILTRO_2  AND num_motor NOT IN $FILTRO_3
UNION
$sql2
UNION
$sql3
";
//echo "$sql";
$rs=$linkPDO->query($sql);

$resp="";
$ALIAS="";

while($row=$rs->fetch())
{


	$num_motor=$row['num_motor'];
	$linea=$row['linea']." ".$row["modelo"];
	$resp.="$num_motor|$linea|\n";
	//$resp.="$cc|$nom|\n";
	
}


echo trim($resp);

?>