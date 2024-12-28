<?php
require_once("../Conexxx.php");
//header("Content-Type: text/html; charset=UTF-8");
$idBanco=limpiarcampo($_REQUEST['id_banco']);
$sql="SELECT * FROM bancos_cuentas WHERE id_banco=$idBanco";
$rs=$linkPDO->query($sql);
?>
<select name="id_cuenta" id="id_cuenta">
<?php
while($row=$rs->fetch()){
	$id=$row['id_cuenta'];
	$tipoCuenta=$row['tipo_cuenta'];
	$numCuenta=$row['num_cuenta'];
	echo limpiarcampo("<option value=\"$id\" >$tipoCuenta - $numCuenta</option>");
}

?>
</select>