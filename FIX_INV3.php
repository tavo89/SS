<?php
include("Conexxx.php");
$url="FIX_INV3.php";
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="viewport" content="width=device-width,initial-scale=1,minimum-scale=1,maximum-scale=1" charset="UTF-8"/>
<link rel="stylesheet" type="text/css" href="css/fonts.css" />

<link rel="shortcut icon" href="Imagenes/favSmart.png" />
<link rel="stylesheet" type="text/css" href="css/uikit-2.24.2/css/components/tooltip.gradient.min.css">
<link rel="stylesheet" type="text/css" href="css/uikit-2.24.2/css/components/datepicker.min.css" />
<link rel="stylesheet" type="text/css" href="css/uikit-2.24.2/css/components/progress.gradient.min.css">
<link rel="stylesheet" type="text/css" href="css/uikit-2.24.2/css/components/progress.min.css">
<link rel="stylesheet" type="text/css" href="css/uikit-2.24.2/css/components/sticky.min.css">
 
<link rel="stylesheet" type="text/css" href="css/uikit-2.24.2/css/uikit.gradient.min.css?<?php  echo "?$LAST_VER"; ?>" />

<link rel="stylesheet" type="text/css" href="css/menu_ui_off.css?<?php  echo "$LAST_VER"; ?>" />

<link rel="stylesheet" type="text/css" href="css/general_ss.css?<?php  echo "$LAST_VER"; ?>" />
</head>
<body>
<?php
/////////////////////////////////////////////////////////////////////// PAGINACION ////////////////////////////////////////////////////
$pag="1";
$limit = 10000; 
if(isset($_REQUEST["pag"])){$pag = (int) $_REQUEST["pag"];}
if ($pag < 1) { $pag = 1; } 
$offset = ($pag-1) * $limit; 
$ii=$offset;
////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
$sqlTotal = "SELECT COUNT(*) as total FROM inv_inter WHERE nit_scs=$codSuc"; 
echo "$sqlTotal<br>";
$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 

@valida_kardex_all($codSuc,$offset,$limit);

$totalPag = ceil($total/$limit); 
         $links = array(); 
		 $sig=$pag+1;
		 if($sig>$totalPag)$sig=$pag;
		 $ant=$pag-1;
		 if($ant<1)$ant=$pag;



?>
<form action="" method="get" class=" uk-form">
<br />
<div class="uk-button-group uk-width-1-1">
<div class="uk-button-group uk-width-1-2">
<?php 
//<a href=\"$url?pag=".$sig."\"  class=\"uk-button uk-button-large\"><i class=\"\">".$pag."/$totalPag</li></a>
echo "<a href=\"$url?pag=1\" class=\"uk-button uk-button-large\"><i class=\"uk-icon-angle-double-left uk-icon-large\">Inicio</i></a>
<a href=\"$url?pag=".$ant."\" class=\"uk-button uk-button-large\"><i class=\"uk-icon-angle-left uk-icon-large\"></i></a>

<a href=\"$url?pag=".$sig."\"class=\"uk-button uk-button-large\"><i class=\"uk-icon-angle-right uk-icon-large\"></i></a>
<a href=\"$url?pag=$totalPag\" class=\"uk-button uk-button-large\"><i class=\"uk-icon-angle-double-right uk-icon-large\">Fin</i></a>";  ?>

</div>
<div class="uk-width-1-2">
<input type="text" name="pag" value=""  placeholder="P&aacute;gina <?php echo $pag ?>" id="pag" class="uk-form-large">
</div>
</div>
</form>
</body>
</html>
