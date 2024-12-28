<?php
require_once("../Conexxx.php");

$busq=limpiarcampo($_REQUEST['busq']);
$qry="SELECT * FROM usuarios WHERE nombre LIKE '$busq%'";
//echo $qry;
$rs=$linkPDO->query($qry);
if(isset($rs)){
?>
<div id="Qtab_layer" class="qtab_layer" onclick="$('#Qtab').remove();$('#Qtab_layer').remove();" >
<table border="0" align="left" claslpadding="6px"  class="s_table" id="Qtab"> 
 
      <tr > 
        <th width="200">Cliente</th>
        <th width="200">C.C.</th>
        <th width="200">Tel.</th> 
        <th width="200">Dir.</th> 
        <th width="100">E-mail</th>
        <th width="200">Fecha Nacimiento</th>
          
          <th><img onMouseUp="$('#Qtab').remove();$('#Qtab_layer').remove();" src="Imagenes/close.gif"></th>  
         </tr>

<?php
$i=0;
//$qry="SELECT * FROM fac_venta WHERE estado!='PAGADO' AND tipo_venta='Credito' AND nit=$codSuc AND (nom_cli LIKE '$busq' OR id_cli='$busq' OR placa='$busq')";
while ($row = $rs->fetch()) 
{ 
		    
            $nom_cli = $row["nombre"]; 
			$dir = htmlentities($row["dir"], ENT_QUOTES,"$CHAR_SET");
			$tel = htmlentities($row["tel"]);
			$mail = htmlentities($row["mail_cli"]);
			$fecha = htmlentities($row["fe_naci"]);
			$id_cli = htmlentities($row["id_usu"]);
			 
			
         ?>
 
<tr onmouseup="sel_nom('<?php echo $i ?>','<?php echo $nom_cli ?>')" bgcolor="#FFFFFF">   
<td><?php echo $nom_cli; ?></td> 
<td><?php echo $id_cli; ?></td>
<td><?php echo $tel; ?></td>
<td><?php echo $dir ?></td>
<td><?php echo $mail ?></td>  
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