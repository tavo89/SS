<?php
require_once("../Conexxx.php");
//header("Content-Type: text/html; charset=UTF-8");


$filtroIVA="AND (iva=5 OR iva=19) AND fraccion!=1";
//$filtroIVA="AND (iva=5 )";
$filtroIVA="";

$filtroCant=" AND (exist>0 OR unidades_frac>0)  ";
if($FLUJO_INV==1 && $vender_sin_inv==0 && $vende_sin_cant==0)$filtroCant=" AND (exist>0 OR unidades_frac>0) ";
else $filtroCant="";

$ORDER_BY="";
if($MODULES["IMG_PRODUCTO"]==1){$ORDER_BY="ORDER BY fab";}

$qry="SELECT $cols_busq FROM ".tabProductos." INNER JOIN inv_inter ON (".tabProductos.".id_pro=inv_inter.id_pro) WHERE nit_scs=$codSuc $filtroCant $filtroIVA $ORDER_BY  LIMIT 0,50";

$rs=$linkPDO->query($qry);

if(isset($rs)){
	
	
$divCols=4;
$tableClass="uk-table uk-table-hover uk-table-striped";

	
	if($MODULES["IMG_PRODUCTO"]==1){

$i=0;
$selFunct="";
$flagClase="";
while ($row = $rs->fetch()) 
{ $i++;

		   include("busq_rows.php");
			$selFunct="selc('$i','$id','$cod','$fe_ven');";
			
			$clase =$row["id_clase"];
			if(empty($flagClase)){$flagClase=$clase;}
			
			//echo "1. $flagClase ; $clase /// ";

if($i==1){echo "<div class=\"uk-margin\" data-uk-margin=\"\"   >";}

?>                              
<a class="uk-thumbnail" href="#" onclick="<?php echo $selFunct;?> ">
<img src="<?php echo "$IMG";?>" width="200" height="100" alt="">
<div class="uk-thumbnail-caption">
<?php echo "$des &nbsp;&nbsp; <span class=\" uk-badge uk-badge-notification \" style=\"font-size:16px;\">$ ".money($pvp)."</span>"?> 
</div>
</a>                           
                         
<?php


//echo " 2. $flagClase ; $clase ///";
//($i % $divCols)==0 || $flagClase!=$clase
if(  ($i % $divCols)==0   ){
	//echo $flagClase!=$clase;
	
	echo "</div>";
	echo "<div class=\"uk-margin\" data-uk-margin=\"\"    >";}
	
	if($flagClase!=$clase){
		//echo " 2. $flagClase ; $clase ///";
		$flagClase=$clase;}
	
	
} /// FIN WHILE


?>

 


<?PHP
	}
	
	else{

?>
<table border="0" align="left" claslpadding="6px"  class="uk-table uk-table-hover" id="tab_art"> 
 <?php require_once("encabezado_busq.php"); ?> 
<tbody>
<?php
$i=0;
$selFunct="";
while ($row = $rs->fetch()) 
{ 
		    
			include("busq_rows.php");
			 
			$selFunct="selc('$i','$id','$cod','$fe_ven');";
         ?>

<tr onclick="<?php echo $selFunct ?>" bgcolor="#FFFFFF" class="can_be_selected">
<?php include("busq_td.php");  ?>

</tr> 
         
<?php 

$i++;
         } 
      
?>
</tbody>
</table>
<?php

}


}
else {echo "0";}

?>