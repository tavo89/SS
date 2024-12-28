<?php
$num_fac="";
$nit_pro="";
$num_facH=0;
$nit_proH="";
if(isset($_SESSION['num_fac'])){$num_fac=$_SESSION['num_fac'];$num_facH=$_SESSION['num_fac'];}
if(isset($_SESSION['nit_pro'])){$nit_pro=$_SESSION['nit_pro'];$nit_proH=$_SESSION['nit_pro'];}

if(isset($_SESSION['num_fac']) || isset($_SESSION['nit_pro'])){



$E=" INNER JOIN (SELECT  ref,cod_barras,fecha_vencimiento,COUNT(`ref`) c FROM art_fac_com WHERE num_fac_com='$_SESSION[num_fac]' AND cod_su=$codSuc AND nit_pro='$_SESSION[nit_pro]' GROUP BY ref,cod_barras,fecha_vencimiento HAVING c>1 ) b ON (a.ref=b.ref AND a.cod_barras=b.cod_barras AND a.fecha_vencimiento=b.fecha_vencimiento) ";


$sql="SELECT  * FROM art_fac_com a $E WHERE a.num_fac_com='$num_fac'  AND a.cod_su=$codSuc AND a.nit_pro='$nit_pro'  ";

$sumRep="UPDATE art_fac_com a 
INNER JOIN 
(SELECT count(*) as n, SUM(cant) as cant,SUM(unidades_fraccion) as unidades_fraccion, ref,cod_barras,cod_su FROM `art_fac_com` WHERE num_fac_com='$_SESSION[num_fac]' AND cod_su=$codSuc AND nit_pro='$_SESSION[nit_pro]'  group by `ref`,`cod_barras`,`cod_su` having n>1) b 

ON a.ref=b.ref AND a.cod_barras=b.cod_barras AND a.cod_su=b.cod_su  SET a.cant=b.cant, a.unidades_fraccion=b.unidades_fraccion  WHERE num_fac_com='$_SESSION[num_fac]' AND cod_su=$codSuc AND nit_pro='$_SESSION[nit_pro]'";

echo "<li>SUM :$sumRep</li>";

//header("location: fac_com_quitar_rep.php");


}
else{
	echo "PAILASSS [$num_fac]".$_SESSION['num_fac'];
	
	
	}



?>