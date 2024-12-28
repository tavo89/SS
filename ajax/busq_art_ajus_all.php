<?php
require_once("../Conexxx.php");
$qry="SELECT $cols_busq FROM ".tabProductos." INNER JOIN inv_inter ON (".tabProductos.".id_pro=inv_inter.id_pro) WHERE (exist>0 OR unidades_frac>0) AND nit_scs=$codSuc LIMIT 0,200";

$rs=$linkPDO->query($qry);

if(isset($rs)){
?>
<table border="0" align="left" claslpadding="6px"  class="uk-table uk-table-hover" id="tab_art"> 
       <?php require_once("encabezado_busq.php"); ?>
<?php
$i=0;
while ($row = $rs->fetch()) 
{ 
		    
			include("busq_rows.php");
			 
			
         ?>
 
<tr onclick="selc_ajus('<?php echo $i ?>','<?php echo $id ?>')" bgcolor="#FFFFFF" class="can_be_selected">
<?php include("busq_td.php");  ?> 
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