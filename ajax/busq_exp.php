<?php
include("../Conexxx.php");

$busq=limpiarcampo($_REQUEST['busq']);
$qry="SELECT * FROM exp_anticipo WHERE estado='ABIERTO' AND cod_su=$codSuc AND (nom_cli = '$busq' OR id_cli='$busq')";
//echo $qry;
$rs=$linkPDO->query($qry);
if($row=$rs->fetch()){
?>
<!--<div id="Qtab_layer" class="qtab_layer" onclick="$('#Qtab').remove();$('#Qtab_layer').remove();" >
</div>
-->
<table border="0" align="center"    class="uk-table uk-table-hover" id="Qtab"> 
 <thead>
      <tr  class="uk-text-large"> 
      <!--
        <th width="200">No. Expediente</th>
        -->
        
        <th width="200">C.C.</th>
        <th width="200">Cliente</th>
        <th width="200">Tel.</th> 
        <th width="200">Descripci&oacute;n Anticipo</th>
         <th width="200">Total Abono</th> 
          <th width="200">Fecha</th>
          <th></th>  
         </tr>
</thead>
<tbody>
<?php
$qry="SELECT * FROM exp_anticipo WHERE estado='ABIERTO' AND cod_su=$codSuc AND (nom_cli LIKE '$busq%' OR id_cli='$busq')";
//echo $qry;
$rs=$linkPDO->query($qry);
$i=0;
//$qry="SELECT * FROM fac_venta WHERE estado!='PAGADO' AND tipo_venta='Credito' AND nit=$codSuc AND (nom_cli LIKE '$busq' OR id_cli='$busq' OR placa='$busq')";
while ($row = $rs->fetch()) 
{ 
		    
			$num_exp = $row["num_exp"]; 
            $nom_cli = $row["nom_cli"]; 
			$des = minify_html($row["des"]); 
			$tel = $row["tel_cli"];
			$fecha = $row["fecha"];
			$tot = $row["tot"]*1;
			$id_cli = $row["id_cli"];
			 
			
         ?>
 
<tr onmouseup="sel_exp('<?php echo $i ?>','<?php echo $num_exp ?>','<?php echo $tot ?>','<?php echo $des ?>')" bgcolor="#FFFFFF">
<!--
<td><?php echo $num_exp; ?></td>  
-->

<td><?php echo $id_cli; ?></td> 
<td><?php echo $nom_cli; ?></td> 

<td><?php echo $tel; ?></td>
<td><?php echo $des; ?></td>  
<td class="uk-text-large uk-text-bold uk-block-primary"><?php echo money3($tot); ?></td>
<td colspan="2"><?php echo $fecha ; ?></td>  
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