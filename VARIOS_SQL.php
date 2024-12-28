<?php
include("Conexxx.php");
 

$sql="SELECT * FROM `usuarios` WHERE orden_ruta!=0 AND id_ruta=1 ORDER BY `usuarios`.`orden_ruta` ASC ";
$rs=$linkPDO->query($sql);
 
$i=10;
while($row=$rs->fetch())
{
	
 
 	$ID=$row["id"];
	//echo "<li>$DES - ref[$refMAYBE] talla[$tallaMAYBE] descripcion[$desfinal]</li>";
	$update="UPDATE usuarios SET orden_ruta='$i' WHERE orden_ruta!=0 AND id_ruta=1 AND id='$ID'";
	$linkPDO->exec($update);
	echo "<li>$update ---> $row[orden_ruta]</li>";
	$i=$i+10;

	

}
?>