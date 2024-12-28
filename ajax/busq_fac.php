<?php
require_once("../Conexxx.php");

$busq=limpiarcampo($_REQUEST['busq']);
$qry="SELECT * FROM fac_venta WHERE estado='PENDIENTE' AND tipo_venta='Credito' AND nit=$codSuc AND (nom_cli LIKE '$busq%' OR id_cli='$busq' OR placa='$busq')";
echo $qry;
$rs=$linkPDO->query($qry);
if(isset($rs)){
?>
<div id="Qtab_layer" class="qtab_layer" onclick="$('#Qtab').remove();$('#Qtab_layer').remove();$('#cod').focus();" >
<table border="0" align="left" claslpadding="6px"  class="s_table" id="Qtab"> 
 
      <tr > 
        <th width="200">No. Fac.</th>
        <th width="200">C.C.</th>
        <th width="200">Cliente</th>
        <th width="200">Tel.</th> 
         <th width="200">Tot.</th> 
        
          <th width="200">Fecha</th>
          
          <th><img onMouseUp="$('#Qtab').remove();$('#Qtab_layer').remove();$('#cod').focus();" src="Imagenes/close.gif"></th>  
         </tr>

<?php
$i=0;
//$qry="SELECT * FROM fac_venta WHERE estado!='PAGADO' AND tipo_venta='Credito' AND nit=$codSuc AND (nom_cli LIKE '$busq' OR id_cli='$busq' OR placa='$busq')";
while ($row = $rs->fetch()) 
{ 
		    
			$cod_fac = htmlentities($row["num_fac_ven"]); 
            $nom_cli = htmlentities($row["nom_cli"], ENT_QUOTES,"$CHAR_SET"); 
			$dir = htmlentities($row["dir"], ENT_QUOTES,"$CHAR_SET");
			$tel = htmlentities($row["tel_cli"]);
			$mecanico = htmlentities($row["mecanico"]);
			$vendedor = htmlentities($row["vendedor"], ENT_QUOTES,"$CHAR_SET"); 
			$fecha = htmlentities($row["fecha"]);
			$tot = htmlentities(money($row["tot"]));
			$placa = htmlentities($row["placa"]);
			$id_cli = htmlentities($row["id_cli"]);
			 
			
         ?>
 
<tr onmouseup="selc('<?php echo $i ?>','<?php echo $cod_fac ?>')" bgcolor="#FFFFFF">
<td><?php echo $cod_fac; ?></td>  
<td><?php echo $id_cli; ?></td> 
<td><?php echo $nom_cli; ?></td> 

<td><?php echo $tel; ?></td>
<td><?php echo money($tot); ?></td>
<td colspan="2"><?php echo $fecha ; ?></td>  
</tr> 
         
<?php 

$i++;
         } 
      
?>
</table>
</div>
<?php

}
else echo "0";

?>