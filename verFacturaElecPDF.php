<?php
include_once("DB.php");
include_once('offline_LIB.php');
include_once("initVariablesSistema.php");
include_once('empresa.php');
include_once('LIB_imprimir_popups.php');
/*


$num_fac=s('n_fac_ven');
$pre=s('prefijo');
$hash=s('hashFacVen');
$urlVars="n_fac_ven=$num_fac&prefijo=$pre&hashFacVen=$hash&codSuc=$codSuc";
$t=r("t");
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[SCRIPT_FILENAME]?$urlVars";
    // outputs image directly into browser, as PNG stream
$generateQR = r('creaQR');
$actual_link=    str_replace('&creaQR=1','',$actual_link);
//&creaQR=1
if($generateQR ==1){
	QRcode::png($actual_link);
	}
*/

$t=r("t");
if($MODULES["modulo_planes_internet"]==1 && $t!=2 && $t!=3){header("location: imp_fac_ven_custom.php");}


$num_fac=r('n_fac_ven');
$pre=r('prefijo');
$hash=r('hashFacVen');

?>
<!DOCTYPE html>
<head>
<title>No.  <?php echo "$pre $num_fac" ?></title>
<meta  content="text/html; charset=<?php echo $CHAR_SET ?>" />
<link href="JS/fac_ven.css?<?php echo $LAST_VER ?>" rel="stylesheet" type="text/css" />
<style type="text/css">
@media print
{
      .page-break  { display:block; page-break-before:always; }

}
.pie_pagina_nanimo{

font-family:"Arial Black", Gadget, sans-serif;
width:100%;

text-size-adjust:none;
font-size:5px;
line-height:7px;
}

.vertical-text {
	transform: rotate(270deg);
	transform-origin:59% 84%;
	-webkit-transform:matrix(0, -1, 1, 0, -95, 86);
-moz-transform:matrix(0, -1, 1, 0, -95, 86);
-o-transform:matrix(0, -1, 1, 0, -95, 86);
-ms-transform:matrix(0, -1, 1, 0, -95, 86);
transform:matrix(0, -1, 1, 0, -95, 86);
}
</style>

</head>

<body >



<?php 



imprimir_fac($num_fac,$pre,$hash);



?>

</body>
</html>