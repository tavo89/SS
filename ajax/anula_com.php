<?php
require_once("../Conexxx.php");
$num_fac=limpiarcampo($_REQUEST['num_fac']);
$nit_pro=limpiarcampo($_REQUEST['nit_pro']);

if($rolLv==$Adminlvl || val_secc($id_Usu,"fac_com_anula")){
	
	$resp=anula_compra($num_fac,$nit_pro,$codSuc);
	
	echo $resp;
	
	}
   ?>