<?php
function reg_cli_fac($DATOS_CLI)
{
	global $linkPDO;
	
$rs=$linkPDO->query("SELECT * FROM usuarios WHERE id_usu='$DATOS_CLI[id_usu]'");
if($row=$rs->fetch()){
	$Fnombre=$row["nombre"];
	
}

$sql="INSERT IGNORE INTO usuarios(id_usu,nombre,dir,tel,cuidad,cod_su,cliente,mail_cli,fe_naci,alias) VALUES('$DATOS_CLI[id_usu]','$DATOS_CLI[nombre]','$DATOS_CLI[dir]','$DATOS_CLI[tel]','$DATOS_CLI[cuidad]','$DATOS_CLI[cod_su]','$DATOS_CLI[cliente]','$DATOS_CLI[mail]','$DATOS_CLI[fe_naci]','$DATOS_CLI[alias]')";
$linkPDO->exec($sql);
	
}
