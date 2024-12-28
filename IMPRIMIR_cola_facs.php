<?php
include("Conexxx.php");
$fechaI=r("fechaI");
$fechaF=r("fechaF");
$limFac=r("limFacs");


//----------------------------------------------------- FILTRO RUTAS --------------------------------------------

$filtroRutas=-1;
$C="";
$colRutas=", (select orden_ruta from usuarios where id_usu=id_cli LIMIT 1) as ruta";
if(isset($_REQUEST['filtroRutas'])){
	$filtroRutas=$_REQUEST['filtroRutas'];
	$_SESSION['filtroRutas']=$filtroRutas;
	if($filtroRutas!=-1){$C=" AND id_cli in (select id_usu from usuarios where id_ruta='$filtroRutas' )";}
	else {$C="";}
}

if(isset($_SESSION['filtroRutas']))
{
	$filtroRutas=$_SESSION['filtroRutas'];
	if($filtroRutas!=-1){$C=" AND id_cli in (select id_usu from usuarios where id_ruta='$filtroRutas' )";}
	else {$C="";}
	
	
}

//----------------------------------------------------------------------------------------------------------------


// ------------------------------ filtro anuladas ---------------------------------------
$filtroAnuladas=r("filtroAnuladas");
if(empty($filtroAnuladas)){$filtroAnuladas="no";}
if(!empty($filtroAnuladas)){$_SESSION["filtroAnuladas"]=$filtroAnuladas;}
if(!empty($_SESSION["filtroAnuladas"])){$filtroAnuladas=s("filtroAnuladas");}

$filtroSQL_anuladas="";
if($filtroAnuladas=="si"){$filtroSQL_anuladas=" AND anulado='ANULADO'";}
else {$filtroSQL_anuladas=" AND anulado!='ANULADO'";}
// ----------------------------------------------------------------------------------------

// -------------------------------- FILTRO ORDEN ----------------------------------------
$filtroOrden="";
$orden_radio=r("radio_orden");
if(!empty($orden_radio)){$_SESSION["radio_orden"]=$orden_radio;}
if(!empty($_SESSION["radio_orden"])){$orden_radio=s("radio_orden");}

if($orden_radio=="DESC"){$ORDEN_ASC_DESC="DESC";}
else {$ORDEN_ASC_DESC="ASC";}
// ------------------------------------------------------------------------------------------


// ------------------------------- CAMPO PARA ORDENAR CONSULTA ------------------------------------
$sel_col_orden=r("campo_orden_consulta");
if(!empty($sel_col_orden)){$_SESSION["campo_orden_consulta"]=$sel_col_orden;}
if(!empty($_SESSION["campo_orden_consulta"])){$sel_col_orden=s("campo_orden_consulta");}

$columna_orden="nom_cli";
if($sel_col_orden=="numeracion"){$columna_orden="num_fac_ven";}
else if($sel_col_orden=="alfabetico"){$columna_orden="nom_cli";}
else if($sel_col_orden=="ruta"){$columna_orden="ruta";}

// -------------------------------------------------------------------------------------------------


// ------------------------------- FILTRO FECHA -----------------------------------------------------

if(!empty($fechaI) && !empty($fechaF)){

$_SESSION["fechaI_cola"]=$fechaI;
$_SESSION["fechaF_cola"]=$fechaF;
$_SESSION["limFacs"]=$limFac;
	
}

if(isset($_SESSION["fechaI_cola"]) && isset($_SESSION["fechaF_cola"])){

$fechaI=$_SESSION["fechaI_cola"];
$fechaF=$_SESSION["fechaF_cola"];
$limFac=$_SESSION["limFacs"];
	
}

///////////////////////////////////////////////////////////////////////////////////////////////////////
$PUBLICIDAD="
<p align=\"center\" style=\"font-size:10px;line-height:12px;\" class=\"imp_pos\">
<B>$NOM_NEGOCIO</B>
<BR />
$showNIT
<br>
E-mail: $email_sucursal
</p>
";
?>
<!DOCTYPE html PUBLIC >
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?php require_once("HEADER_UK.php"); ?>
<style type="text/css">

@media print
{		
	  @page {size: landscape;
	  			/*margin: 0mm;*/}
	  body {-webkit-print-color-adjust: exact;
	  /*margin: 0px;*/}
      .page-break  { display:block; page-break-before:always; }
	  .no-print, .no-print *
    {
        display: none !important;
    }

}

.barra_negra{
vertical-align:top;
position:relative;
top:0px;
background-color:rgb(0, 0, 0) !important;
color:#FFF !important;
-webkit-border-radius:29px;	
	
}
.label_titulo{
	background-color:#063A9B !important;;
-webkit-border-radius:2px;
-moz-border-radius:2px;
border-radius:2px;
color:rgb(255, 255, 255) !important;;
	
}
.label_titulo_warning{
background-color:rgb(252, 0, 130) !important;;
color:rgb(255, 255, 255) !important;;

-webkit-border-radius:2px;
-moz-border-radius:2px;
border-radius:2px;
	
}
.sub_table{
border-width:2px;
border-style:solid;
-webkit-border-radius:8px;
-moz-border-radius:8px;
border-radius:8px;	
	
}
body{ font-size:10px;	
}
table{ font-size:10px;}
</style>
</head>

<body>
<?php
$url="IMPRIMIR_cola_facs.php";
$limit = 50;
if(!empty($limFac)){$limit=$limFac;}
$pag="";
if(isset($_REQUEST["pag"])){$pag = (int) $_REQUEST["pag"];}
if ($pag < 1) 
{ 
   $pag = 1; 
} 
$offset = ($pag-1) * $limit; 
$ii=$offset;

$sqlTotal = "SELECT COUNT(*) as total FROM fac_venta WHERE nit=$codSuc AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND anulado!='ANULADO'"; 
$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 

?>
<?php require("PAGINACION2.php"); 
echo "<span style=\" font-size:8px;\">-- Pag $pag / $totalPag --</span>";
?>
<div  style="height:20cm; width:27.9cm;" class="  uk-grid  uk-grid-collapse uk-width-1-1" data-uk-grid-margin>
<?php

$sql="SELECT num_fac_ven, prefijo, nit, hash, nom_cli,id_cli $colRutas FROM fac_venta WHERE nit=$codSuc AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroSQL_anuladas $C ORDER BY $columna_orden  $ORDEN_ASC_DESC LIMIT $offset, $limit ";
//echo "$sql";
$stm=$linkPDO->query($sql);
$pairCounter=0;
$pageCounter=0;
while($row = $stm->fetch())
{


$num_fac=$row["num_fac_ven"];
$pre=$row["prefijo"];
$hash=$row["hash"];

if($fac_servicios_mensuales==1){imprimir_fac_custom2($num_fac,$pre,$hash,$codSuc);}
else {imprimir_fac_custom($num_fac,$pre,$hash,$codSuc);}


$pairCounter++;
$pageCounter++;

if($pairCounter==2)
{
	$pairCounter=0;
	
	if($pageCounter < $limit){
	echo  '</div><div class="page-break"></div>';	
	echo "<span style=\" font-size:8px;\">-- Pag $pag / $totalPag --</span>";
	echo  '<div  style="height:20cm; width:27.9cm;" class="  uk-grid  uk-grid-collapse uk-width-1-1" data-uk-grid-margin>';
	}
	else{


	}
}
}
?>
</div>
<?php
require_once("FOOTER_UK.php"); 
?>
</body>
</html>