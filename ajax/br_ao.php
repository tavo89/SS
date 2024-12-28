<?php
require_once("../Conexxx.php");


$busq= $_REQUEST['ba'];

$rs=$linkPDO->query("SELECT * FROM ".tabProductos." WHERE id_pro LIKE '$busq%' OR detalle LIKE '$busq%' OR fab LIKE '$busq%' OR id_clase LIKE '$busq%'");



if(isset($rs)){
?>
<h1>Art&iacute;culos encontrados:</h1>
<table border="0" align="left" claslpadding="6px" bgcolor="#000000" class="autoco" id="Qtab"> 
 
      <tr bgcolor="#4B8EFA" style="color:#FFF" class="ui-btn-active"> 
        <th width="200">Ref.</th>
        <th width="200">Descripci&oacute;n</th> 
         <th width="200">Clasificaci&oacute;n</th> 
          <th width="100">Fracciones</th>
           <th width="200">Fabricante</th><th><img onMouseUp="$('#Qtab').remove()" src="Imagenes/close.gif"></th>  
         </tr>

<?php
$i=0;
while ($row = $rs->fetch()) 
{ 
		    
            $id_inter = htmlentities($row["id_pro"]); 
            $des = htmlentities($row["detalle"], ENT_QUOTES,'ISO-8859-1'); 
			$clase = htmlentities($row["id_clase"], ENT_QUOTES,'ISO-8859-1');
			$id = htmlentities($row["serial_pro"]);
			$frac = htmlentities($row["frac"]);
			$fab = htmlentities($row["fab"], ENT_QUOTES,'ISO-8859-1'); 
			 
			
         ?>
 
<tr  bgcolor="#FFF" onmouseup="selc('<?php echo $i ?>','<?php echo $id_inter ?>')">
<td id="<?php echo $i ?>id_int"><?php echo $id_inter; ?></td> 
<td><?php echo $des; ?></td> 
<td><?php echo $clase; ?></td> 

<td><?php echo $frac; ?></td>
<td colspan="2"><?php echo $fab ; ?></td>  
</tr> 
         
<?php 

$i++;
         } 
      


}
else echo "0";
   ?>
