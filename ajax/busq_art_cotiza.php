<?php
require_once("../Conexxx.php");
//header("Content-Type: text/html; charset=UTF-8");
$busq=rm('busq');
$fullSearch=r("fs");

 
$filtroIVA="AND (iva=5 OR iva=19)";
$Sdetalle=fullBusq($busq,"detalle");
$colBusq="inv_inter.id_inter = '$busq' OR ".tabProductos.".id_pro='$busq' OR fab LIKE '$busq%' OR id_clase LIKE '$busq%' OR $Sdetalle ";
$filtroCant=" AND (exist>0 OR unidades_frac>0) ";
$filtroCant="";

$qry="SELECT $cols_busq FROM ".tabProductos." INNER JOIN inv_inter ON (".tabProductos.".id_pro=inv_inter.id_pro) WHERE nit_scs=$codSuc $filtroCant AND ($colBusq) LIMIT 100";
$q=$qry;

$rs=$linkPDO->query($qry);
$nr=$rs->rowCount();;

$divCols=4;
$tableClass="uk-table uk-table-hover uk-table-striped";
if(isset($rs) && $nr>0){
	
	if($MODULES["IMG_PRODUCTO"]==1){

$i=0;
$selFunct="";
while ($row = $rs->fetch()) 
{ 

		   include("busq_rows.php");
			$selFunct="selc('$i','$id','$cod','$fe_ven');";
?>
<div class="uk-margin" data-uk-margin="" onclick="<?php echo "$selFunct";?>">

                                 
<a class="uk-thumbnail" href="#">
<img src="<?php echo "$IMG";?>" width="200" height="100" alt="">
<div class="uk-thumbnail-caption"><?php echo "$des <span class=\" uk-badge uk-badge-notification \" style=\"font-size:16px;\">$ ".money($pvp)."</span>"?> </div>
</a>

                                
                              
</div>
<?php
} /// FIN WHILE


?>

 


<?PHP
	}
	
	else{
?>
<table rules="all" frame="box" border="0" align="left" claslpadding="6px"  class="uk-table" id="tab_art" > 
 <?php require_once("encabezado_busq.php"); ?> 
<tbody>
<!--
<tr>
<td colspan="9"><?php echo "$qry<br>nr: $nr";  ?></td>
</tr>
-->
<?php
$i=0;
$selFunct="";
while ($row = $rs->fetch()) 
{ 
//eco_alert("row!");
		   include("busq_rows.php");
			$selFunct="selc('$i','$id','$cod','$fe_ven');";
			
         ?>
 
<tr onclick="<?php echo $selFunct ?>"  bgcolor="#FFFFFF" class="can_be_selected">
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
