<?php
include("../Conexxx.php");
	$resp=r('resp');
	$id_cli=r('id_cli');
	$nom=r("nom");
	$ID=r("id");

	
	 if($resp==1 && ($rolLv==$Adminlvl || val_secc($id_Usu,"clientes_eli")))
	{
		$sql="";
		//$rs=$linkPDO->$linkPDO->exec("DELETE FROM usuarios WHERE id_usu='$id_cli' AND nombre='$nom' AND cliente=1");
		$rs=$linkPDO->exec("DELETE FROM usuarios WHERE id='$ID'");
		if($rs)echo 1;
		else echo -1;	
	}
	else echo 404;
?>