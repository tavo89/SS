<?php 
include("Conexxx.php");
//add_sede(2);
if($rolLv!=$Adminlvl ){header("location: centro.php");}
 //////////////////////////////////////////////////////////// FILTRO FECHA //////////////////////////////////////////////////////////////////////////
$fechaI="";
$fechaF="";
$PAG_fechaI="fechaI_vehi";
$PAG_fechaF="fechaF_vehi";
$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"Filtrar\" class=\"uk-button uk-button-success\" >";
$A="";
$opc="";
if(isset($_REQUEST['opc'])){$opc=$_REQUEST['opc'];}

if(isset($_REQUEST['fechaI'])){$fechaI=limpiarcampo($_REQUEST['fechaI']); $_SESSION[$PAG_fechaI]=$fechaI;}
if(isset($_REQUEST['fechaF'])){$fechaF=limpiarcampo($_REQUEST['fechaF']);$_SESSION[$PAG_fechaF]=$fechaF;}

if(isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI])){$fechaI=$_SESSION[$PAG_fechaI];}
if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF])){$fechaF=$_SESSION[$PAG_fechaF];$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"QUITAR\" class=\"uk-button uk-button-danger\">";}

if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF]) && isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI]))
{
	$A=" AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF') ";
}




if($opc=="QUITAR")
{
	$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"Filtrar\" class=\"uk-button uk-button-success\" >";
	$fechaI="";
	$fechaF="";
	unset($_SESSION[$PAG_fechaI]);
	unset($_SESSION[$PAG_fechaF]);
	$A="";
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------

$C="";
$nom_cli="";


$opc="";
$busq="";
$val="";
$boton="";
$tot_cuotas=0;
$val_cre=0;
$totCre=0;
$pre="";
if(isset($_GET['opc'])){$opc=r('opc');}
if(isset($_REQUEST['busq']))$busq=r('busq');
if(isset($_REQUEST['valor']))$val=r('valor');
if(isset($_REQUEST['boton']))$boton= r('boton');
if(isset($_REQUEST['pre']))$pre= $_REQUEST['pre'];





//-----------------------------------------------------------------------------------------------------------------------------------------------------


//////////////////////////////////////////////////////////////////////////////// CABECERA TABLA ////////////////////////////////////////////////////






$tabla="x_config";
$codSuCol="cod_su";
$col_id="id_config";
// columnas para mostrar y modificar
$columns="id_config,des_config,val,nota_uso";
$cols=explode(",",$columns);
$maxCols=count($cols);
$colSet[]="";
for($i=0;$i<$maxCols;$i++){$colSet[$i]=$cols[$i];}
$url="SISTEMA_super.php";
$ORDER_BY="ORDER BY id_config";


///////////////////////////////////////////////////////////////////// PAGINACION ///////////////////////////////////////////////////////////////////
$pag="";
$limit = 200; 

 
if(isset($_REQUEST["pag"])){$pag = (int) $_REQUEST["pag"];}
if ($pag < 1) 
{$pag = 1;} 
$offset = ($pag-1) * $limit; 
$ii=$offset;
 
 
 
$sql="SELECT a.COLUMN_NAME, a.COLUMN_COMMENT FROM information_schema.COLUMNS a WHERE a.TABLE_NAME =  '$tabla' AND a.COLUMN_COMMENT !=  ''";
$rs=$linkPDO->query($sql);
$HEADER_TABLE[]="";

while($row=$rs->fetch()){$HEADER_TABLE[$row["COLUMN_NAME"]]=$row["COLUMN_COMMENT"];}
$cols="";
$max=count($colSet);
for($i=0;$i<$max;$i++){
	
	if($HEADER_TABLE[$colSet[$i]]=="ID"){$HIDDEN=" uk-hidden";}
	else{$HIDDEN="";}

	$cols.="<td class=\" $HIDDEN \">".$HEADER_TABLE[$colSet[$i]]."</td>";
}

$sql = "SELECT  $columns FROM $tabla WHERE  $codSuCol=$codSuc $ORDER_BY ASC    LIMIT $offset, $limit "; 
//echo $sql;

$rs =$linkPDO->query($sql); 





//-----------------------------------------------------------------------------------------------------------------------------------------------------



//////////////////////////////////////////////////////////////// BUSCAR ///////////////////////////////////////////////////////////////////////////
//echo "bot: $boton ---->$busq";
if($boton=='Buscar' && isset($busq) && !empty($busq)){

//concepto LIKE '%$busq%' OR
$ND="";
for($i=0;$i<$max;$i++){

	if($i!=$max-1){$ND.="$colSet[$i] LIKE '%$busq%' OR ";}
	else{$ND.=" $colSet[$i] LIKE '%$busq%'";}
}


$sql_busq="SELECT $columns FROM $tabla WHERE $codSuCol=$codSuc AND ( $ND    ) ";


$rs=$linkPDO->query($sql_busq);
	}
$sqlTotal = "SELECT COUNT(*) AS total FROM $tabla WHERE  $codSuCol=$codSuc"; 

$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 
?>
<!DOCTYPE html>
<html  >
<head>
<?php include_once("HEADER.php"); ?>
</head>

<body>
<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php include_once("menu_izq.php"); ?>
            <?php include_once("menu_top.php"); ?>
			<?php include_once("boton_menu.php"); ?>

<div class="uk-width-9-10 uk-container-center">



		<!-- Lado izquierdo del Nav -->
		<nav class="uk-navbar">

		<a class="uk-navbar-brand" href="centro.php"><img src="Imagenes/logoICO.ico" class="icono_ss"> &nbsp;SmartSelling</a> 

			<!-- Centro del Navbar -->

			<ul class="uk-navbar-nav uk-navbar-center" style="width:630px;">   <!-- !!!!!!!!!! AJUSTAR ANCHO PARA AGREGAR NUEVOS ELMENTOS !!!!!!!! -->
		
<li class=""><a href="#"  data-uk-modal onClick="fix_temp();"  style="cursor:pointer;"><i class="uk-icon-barcode <?php echo $uikitIconSize ?>"></i>&nbsp;FIX Temporales Inventario</a></li>
 
 

				<li><a href="<?php echo $url ?>" ><i class="uk-icon-refresh uk-icon-spin <?php echo $uikitIconSize ?>"></i>&nbsp;Recargar P&aacute;g.</a></li>
			</ul>

			
			<!-- Lado derecho del Navbar -->
				
		<div class="uk-navbar-content uk-hidden-small uk-navbar-flip">
			<form class="uk-form uk-margin-remove uk-display-inline-block">
				<div class="uk-form-icon">
						<i class="uk-icon-search"></i>
						<input type="text" name="busq" placeholder="Buscar..." class="">
				</div>
				<input type="submit" value="Buscar" name="boton" class="uk-button uk-button-primary">
			</form>
		</div>
			
		</nav>
<?php
//        0       1       2         3          4              5           6     7        8      9         10        11               12          13    14
/*
$columnas="cod_su,estrato,anchobanda,precioplan,velbajada,velsubida,equipreco,servadic";
$tipoCol=array(0=>"hidden","text","text","text","text","text","text","text" );
$default=array(0=>"$codSuc","","","","","","","" );
crear_any_form("$tabla",$columnas,$tipoCol,$default, " function(){ return true;}", "successAny","Planes Internet $SEDES[$codSuc]");

*/
?>
<h1 align="center">OPCIONES DE SISTEMA - SUPER</h1>

<br><br><br>

<form autocomplete="off" action="<?php echo $url ?>" method="get" name="form">
 
<?php //echo $sql;//echo "opc:".$_REQUEST['opc']."-----valor:".$_REQUEST['valor']; ?>
<?php include("PAGINACION.php"); ?>
<table border="0" align="center" claslpadding="6px" bgcolor="#000000" class="uk-table uk-table-hover uk-table-striped" >
<thead>
<tr bgcolor="#595959" style="color:#FFF" valign="top"> 
<?php echo "<td>#</td><td></td>".$cols;   ?>
</tr>
</thead>      
<tbody>    
<?php 
while ($row = $rs->fetch()) 
{ 
$ii++;
$FunctPop=" ";	
//$FunctDEL="DEL_any('".$row["id"]."');";
?>
<tr  bgcolor="#FFF">
<th width="50px"><?php echo $ii ?></th>
<td width="70px">
<table cellpadding="0" cellspacing="0" width="70px">
<tr>
<td>
<a href="#" class="uk-icon-list uk-icon-button uk-icon-hover uk-icon-small" onClick="<?php echo $FunctPop ?>">
</a>
</td>
<!--
<td>
<a href="#" class="uk-icon-remove uk-icon-button uk-icon-hover uk-icon-small" onClick="<?php echo $FunctDEL ?>">
</a>
</td>
-->
</tr>
</table>


</td> 

<?php

for($i=0;$i<$max;$i++){
	if($HEADER_TABLE[$colSet[$i]]=="ID"){$HIDDEN=" uk-hidden";}
	else{$HIDDEN="";}
	
	//gettype($row[$colSet[$i]])."-".
	
	if($colSet[$i]=="des_config"){$funct="";$funct2="";}
	else{
	$funct="mod_tab_row('tabTD$ii".$i."','$tabla','$colSet[$i]','".$row[$colSet[$i]]."',' $col_id=\'$row[$col_id]\' AND $codSuCol=\'$codSuc\'','$ii','text','','');";
	$funct2="mod_tab_row('tabTD$ii".$i."','$tabla','$colSet[$i]','".$row[$colSet[$i]]."',' $col_id=\'$row[$col_id]\' AND $codSuCol=\'$codSuc\'','$ii','text','','num');";
	}
	if($rolLv!=$Adminlvl){$funct="";}
	if(!is_numeric($row[$colSet[$i]])){echo "<td class=\" $HIDDEN \" id=\"tabTD$ii".$i."\" onDblClick=\"$funct\">".$row[$colSet[$i]]."</td>";}
	else if(($row[$colSet[$i]]*1)<100){echo "<td class=\" $HIDDEN \" id=\"tabTD$ii".$i."\" onDblClick=\"$funct\">".$row[$colSet[$i]]."</td>";}
	else{echo "<td class=\" $HIDDEN \" id=\"tabTD$ii".$i."\" onDblClick=\"$funct2\">$".money($row[$colSet[$i]])."</td>";}
}

?>
</tr> 
         
<?php  } ?>
</tbody>
</table>
</form>

</div>
<?php include("PAGINACION.php"); ?>
<?php include_once("FOOTER.php"); ?>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<!--<script type="text/javascript" language="javascript1.5" src="JS/utiles.js?<?php //echo "$LAST_VER" ?>"></script>-->
<script type="text/javascript" language="javascript1.5">
function DEL_any(id)
{
	var data="ID="+id;
	if(confirm("Eliminar registro?")){
	ajax_x('ajax/FORMS/DEL_serv_internet_plan.php',data,function(resp){
		
 		if(resp==1){
		//alert("ELIMINADO");
		waitAndReload();	
		}
		
		});
		
	}
};
function ver_historial(placa,modelo,color,km,id_cli)
{
	var data='placa='+placa+'&id_cli='+id_cli;
	var URL='ajax/POP_UPS/historial_vehi.php';
	ajax_x(URL,data,function(resp){open_pop3('Mantenimientos '+modelo+'('+placa+')',' Km: '+puntob(km),resp,1)});
	
};
function fix_temp()
{
	var data='';
	var URL='ajax/MANAGER/fix_temp_inv.php';
	ajax_x(URL,data,function(resp){
		if(resp==1){open_pop('Mantenimiendo Base de Datos ','COMPLETADO','');}
		}
		);
	
};
</script>
</div>
</div><!--fin pag 1-->

</body>
</html>