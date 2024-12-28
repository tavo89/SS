<?php
require_once("../Conexxx.php");

$c=limpiarcampo($_REQUEST['c']);
$busq=limpiarcampo($_REQUEST['busq']);
$Sdetalle=fullBusq($busq,"detalle");
$qry="SELECT inv_inter.id_pro ref,inv_inter.id_inter cod,exist,precio_v,iva,detalle,fab,id_clase,color,talla FROM ".tabProductos." INNER JOIN inv_inter ON (".tabProductos.".id_pro=inv_inter.id_pro) WHERE nit_scs=$codSuc AND (".tabProductos.".id_pro LIKE '$busq%' OR $Sdetalle OR fab LIKE '$busq%' OR id_clase LIKE '$busq%' OR inv_inter.id_inter LIKE '$busq%')";

$rs=$linkPDO->query($qry);




if(isset($rs)){
?>
<table border="0" align="left" claslpadding="6px"  class="uk-table "> 
<?php require_once("encabezado_busq.php"); ?>

<?php
$i=0;
while ($row = $rs->fetch()) 
{ 
		    
			$cod = htmlentities($row["cod"]); 
			$pvp = $row["precio_v"]*1; 
			$iva = $row["iva"]; 
            $des = htmlentities($row["detalle"], ENT_QUOTES,"$CHAR_SET");
			$color = htmlentities($row["color"]);
			$talla = htmlentities($row["talla"]); 
			//$des = htmlentities($row["detalle"]); 
			//$clase = htmlentities($row["id_clase"], ENT_QUOTES,"$CHAR_SET");
			$clase = $row["id_clase"];
			//$id = htmlentities($row["ref"]);
			$id = $row["ref"];
			$frac = $row["exist"]*1;
			$fab = htmlentities($row["fab"]); 
			 
			
         ?>
 
<tr onclick="selc('<?php echo $i ?>','<?php echo $cod ?>',<?php echo $c ?>)" bgcolor="#FFFFFF">
<td id="<?php echo $i ?>id_int"><?php echo $id; ?></td>
<td><?php echo $cod; ?></td>  
<td><?php echo $des; ?></td> 
<?php if($usar_color==1){ ?> <td><?php echo $color; ?></td><?php }?>
<?php if($usar_talla==1){ ?><td><?php echo $talla; ?></td> <?php } ?>
<td><?php echo $clase; ?></td> 
<td><?php echo $frac; ?></td>
<td><?php echo $iva; ?></td>
<td><?php echo money($pvp); ?></td>  
<td colspan="2"><?php echo $fab ; ?></td>  
</tr> 
         
<?php 

$i++;
         } 
      
?>
</table>


<?php

}
else echo "0";

?>