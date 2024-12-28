<?php
require_once("../Conexxx.php");

$c=limpiarcampo($_REQUEST['c']);
$num_fac=limpiarcampo($_REQUEST['num_fac']);
$mod=limpiarcampo($_REQUEST['mod']);
$busq=limpiarcampo($_REQUEST['busq']);
$nit=limpiarcampo($_REQUEST['nit']);
$Sdetalle=fullBusq($busq,"des");
$qry="SELECT * FROM art_fac_com WHERE nit_pro='$nit' AND num_fac_com='$num_fac' AND cod_su=$codSuc AND (ref LIKE '$busq%' OR $Sdetalle OR cod_barras='$busq')";

$rs=$linkPDO->query($qry);

//echo "$qry";


if(isset($rs)){
?>
<table border="0" align="left" claslpadding="6px"  class="uk-table" id="tab_art"> 
<?php require_once("encabezado_busq.php"); ?>
<tbody>
<?php
$i=0;
while ($row = $rs->fetch()) 
{ 
			$cod = $row["cod_barras"]; 
			$pvp = $row["pvp"]*1; 
			$iva = $row["iva"]; 
			$des = htmlentities($row["des"], ENT_QUOTES,"$CHAR_SET"); 
			$color = htmlentities($row["color"]);
			$talla = htmlentities($row["talla"]);
			$clase =$row["clase"];
			$id = $row["ref"];
			$cant = $row["cant"]*1;
			$presentacion=$row['presentacion'];
			$fab =htmlentities($row["fabricante"]); 
			$fe_ven=$row['fecha_vencimiento'];
			$frac=$row['fraccion'];
			$uni=$row['unidades_fraccion'];
			if($frac==1)$uni=0;
			$marca=$row["fabricante"];
			$tipoProducto="Normal";
			
//$selFunc="selc('$i','$cod',$c,$mod,'$fe_ven');";	
$selFunc="selc('$i','$id','$cod',$c,'$mod','$fe_ven');";	    
			
			 
			
         ?>
 
<tr onclick="<?php echo $selFunc ?>" bgcolor="#FFFFFF" class="can_be_selected">
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
else echo "0";

?>