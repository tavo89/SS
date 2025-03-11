<?php 
require_once("Conexxx.php");
$busq="";
$val="";
$val2=urlencode(r("valor2"));
$boton="";
$idx="";
$tabla="inv_inter";
$col_id="id_pro";
$columns="ubicacion,serial_pro,serial_inv,id_sub_clase,".tabProductos.".id_pro id_glo,inv_inter.id_inter  id_sede,detalle,id_clase,fraccion,fab,max,min,costo,precio_v,exist,iva,gana,talla,color,".tabProductos.".presentacion,fecha_vencimiento,fraccion,unidades_frac,pvp_credito,certificado_importacion,url_img,pvp_may,aplica_vehi,des_full,nit_scs";
$url=rawurlencode("inventario_sedes.php");
$url_dialog="dialog_invIni.php";
$url_mod="modificar_inv.php";
$url_new="agregar_producto.php";
$pag="";
$limit = 20; 
$order="detalle";
$sort="";
$colArray=array(0=>'id_glo','id_sede','detalle','gana');
$classActive=array(0=>'','','','');
$offset=0;

$opc="";
if(isset($_REQUEST['opc'])){$opc=$_REQUEST['opc'];}

 

/////////////////////////////////////////////////////////////// FILTRO FECHA //////////////////////////////////////////////////////////////////////////
$fechaI="";
$fechaF="";
$PAG_fechaI="fechaI_inv";
$PAG_fechaF="fechaF_inv";
$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"Filtrar\" class=\"uk-button uk-button-success\">";
$A="";
if(isset($_REQUEST['fechaI'])){$fechaI=limpiarcampo($_REQUEST['fechaI']); $_SESSION[$PAG_fechaI]=$fechaI;}
if(isset($_REQUEST['fechaF'])){$fechaF=limpiarcampo($_REQUEST['fechaF']);$_SESSION[$PAG_fechaF]=$fechaF;}

if(isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI])){$fechaI=$_SESSION[$PAG_fechaI];}
if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF])){$fechaF=$_SESSION[$PAG_fechaF];$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"QUITAR\" class=\"uk-button uk-button-danger uk-icon-undo\">";}

if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF]) && isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI]))
{
	$A=" AND (DATE(fecha_vencimiento)>='$fechaI' AND DATE(fecha_vencimiento)<='$fechaF') ";
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

//////////////////////////////////////////////////////// FILTROS TABLA ////////////////////////////////////////


$FILTRO_CLASE=multiSelcFilter("clases","filtro_clase","id_clase",$opc);

$FILTRO_SUB_CLASE=multiSelcFilter("sub_clases","filtro_sub_clase","id_sub_clase",$opc);

$FILTRO_PROVEDORES=multiSelcFilter("provedores","filtro_provedores","nit_proveedor",$opc);

//print_r( $_REQUEST["sub_clases"]);
//$FILTRO_LAB="";
$FILTRO_LAB=multiSelcFilter("fabricantes","filtro_lab","fab",$opc);

//filtroExist

$FILTRO_EXIST=existFilter("filtroExist","filtro_existencias","",$opc);

$FILTRO_UTIL=utilFilter("filtroUtil","filtro_utilidades","",$opc);

$FILTRO_DES=desFilter("filtroDes","filtro_descripcion","",$opc);

$FILTRO_VENCIDOS=venciFilter("filtroVencidos","filtroVencidos",$opc);

$FILTROS_TABLA=" $FILTRO_DES $FILTRO_EXIST $FILTRO_CLASE $FILTRO_LAB $FILTRO_VENCIDOS $FILTRO_SUB_CLASE $FILTRO_PROVEDORES";

//echo "FILTROS: $FILTROS_TABLA";

//-----------------------------------------------------------------------------------------------------------//

////////////////////////////////////////////////////////////////////// busquedas precisas //////////////////////////////////////////////////////////

$bp=0;
if(isset($_REQUEST['bp']))
{
	$bp=$_REQUEST['bp'];
	$_SESSION['bp']=$bp;
}

if(isset($_SESSION['bp']))
{
 $bp=$_SESSION['bp'];	
}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

if(isset($_REQUEST["pag"])){$pag = (int) $_REQUEST["pag"];}
if ($pag < 1) 
{ 
   $pag = 1; 
} 
$offset = ($pag-1) * $limit; 
$ii=$offset;


if(isset($_REQUEST['sort']))
{
	$sort=$_REQUEST['sort'];
	$order= $colArray[$sort];
	$_SESSION['sort_inv']=$sort;
	$classActive[$sort]="ui-btn-active ui-state-persist";
}

if(isset($_SESSION['sort_inv']))
{        
        $sort=$_SESSION['sort_inv'];
		$order= $colArray[$sort];
		$classActive[$_SESSION['sort_inv']]="ui-btn-active ui-state-persist";
}

if(isset($_SESSION['id']))$idx=$_SESSION['id'];
if(isset($_REQUEST['opc'])){$boton= urlencode($_REQUEST['opc']);}
if(isset($_REQUEST['busq'])) $busq= rm('busq');
if(isset($_REQUEST['valor']))$val= urlencode($_REQUEST['valor']);

$colColor="";
$colTalla="";
if($usar_color==1)$colColor="<th width=\"250\">
<div style=\"display:inline-block;table-layout:fixed;width:80%;\">Color</div>
</th>";
if($usar_talla==1)$colTalla="<th width=\"250\">
<div style=\"display:inline-block;table-layout:fixed;width:80%;\">Talla</div>
</th>";


$cols="<th width=\"90px\">#</th>
 
 
<th width=\"200\">
Referencia
</th>
<th width=\"200\">
Codigo
</th>

<th width=\"200\">
Descripci&oacute;n
</th>
<th width=\"200\">PvP</th>
";

 
 
foreach($SEDES as $clave => $valor){
		$style="style=\" \"";
	
	switch($clave){
	case 1:$style="style=\"background-color:rgb(110,106,92);\"";break;
	case 2:$style="style=\"background-color:rgb(201,204,35);\"";break;	
	case 3:$style="style=\"background-color:rgb(105,171,191);\"";break;	
	case 4:$style="style=\"background-color:rgb(184,119,105);\"";break;
	case 5:$style="style=\"background-color:rgb(110,166,99);\"";break;
	case 6:$style="style=\"background-color:rgb(81,74,181);\"";break;
	case 7:$style="style=\"background-color:rgb(204,144,75);\"";break;	
		
	}
	
	$cols.="<th $style width=\"30\">$valor</th>";
}
 
 
$cols.=" 


$colColor
$colTalla
 
<th width=\"200\">Fabricante</th>

 ";

if($MODULES["APLICA_VEHI"]==1){$cols.="<th width=\"200\">Aplica Veh&iacute;culo</th>";	}

if($usar_fecha_vencimiento==1)
{
$cols.="<th width=\"200\">Vencimiento</th>";	
}

$cols.="<th width=\"120\">IMG</th>";

if(empty($FILTRO_UTIL))$sql = "SELECT  $columns FROM ".tabProductos." INNER JOIN inv_inter ON ".tabProductos.".id_pro=inv_inter.id_pro WHERE  nit_scs>0  $A $FILTROS_TABLA GROUP BY inv_inter.id_inter ORDER BY $order    LIMIT $offset, $limit"; 
else $sql = "SELECT  $columns FROM ".tabProductos." INNER JOIN inv_inter ON ".tabProductos.".id_pro=inv_inter.id_pro WHERE  nit_scs>0  $A $FILTROS_TABLA $FILTRO_UTIL GROUP BY inv_inter.id_inter  LIMIT $offset, $limit";

//echo "$sql<BR>";


if($boton=='mod'&& !empty($val) && !empty($val2)){
	
	//$_SESSION['id']=$val;
	$_SESSION['pag']=$pag;
	$feVen=r('fe_ven');
	$serialInv=r("serial_inv");
	$serialPro=r("serial_pro");
	header("location: $url_mod?REF=$val&fe_ven=$feVen&idPro=$val2&serial_inv=$serialInv&serial_pro=$serialPro");
	}
 


 
 
$sqlTotal = "SELECT COUNT(DISTINCT(inv_inter.id_inter)) as total FROM ".tabProductos." INNER JOIN inv_inter ON ".tabProductos.".id_pro=inv_inter.id_pro WHERE nit_scs>0 $A $FILTROS_TABLA  "; 
//echo "$sqlTotal";
$rs = $linkPDO->query($sql); 
$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 

	
if($boton=='Buscar' && isset($busq) && !empty($busq)){

$Sdetalle=fullBusq($busq,"detalle");

if(isset($bp)&&$bp==1)
{
$sql_busq="SELECT $columns FROM ".tabProductos." INNER JOIN inv_inter ON (inv_inter.id_pro=".tabProductos.".id_pro) WHERE nit_scs>0 AND ($Sdetalle) $A $FILTROS_TABLA GROUP BY inv_inter.id_inter";
}
else{
$sql_busq="SELECT $columns FROM ".tabProductos." INNER JOIN inv_inter ON (inv_inter.id_pro=".tabProductos.".id_pro) WHERE nit_scs>0 AND ($Sdetalle OR ".tabProductos.".id_pro = '$busq' OR  id_clase LIKE '$busq%' OR inv_inter.id_inter = '$busq') $A $FILTROS_TABLA GROUP BY inv_inter.id_inter";	
}

//echo "$sql_busq";

$rs=$linkPDO->query($sql_busq);

	
	}
	
//echo "<li>$sql</li> ";
 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<?php require_once("HEADER.php"); ?>
<link href="css/multi-select.css" rel="stylesheet" type="text/css" />	

</head>

<body>
<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php require_once("menu_izq.php"); ?>
            <?php require_once("menu_top.php"); ?>
			<?php require_once("boton_menu.php"); ?>

<div class="uk-width-9-10 uk-container-center">



	<!-- Lado izquierdo del Nav -->
		<nav class="uk-navbar">

		<a class="uk-navbar-brand uk-visible-large" href="centro.php"><img src="Imagenes/favSmart.png" class="icono_ss"> &nbsp;SmartSelling</a> 

			<!-- Centro del Navbar -->

			<ul class="uk-navbar-nav">   <!-- !!!!!!!!!! No se Puede centrar, navbar non-compliant !!!!!!!! -->
		
				<li class="ss-navbar-center"><a href="<?php echo $url_new ?>" ><i class="uk-icon-plus-square <?php echo $uikitIconSize ?>"></i>&nbsp;Crear Producto</a></li>
				<!--
				<li><a href="<?php if(isset($bp)&&$bp==1)echo "$url?bp=0";else echo "$url?bp=1";  ?>" ><i class="<?php if(isset($bp)&&$bp==1)echo "uk-icon-check-circle $uikitIconSize";else echo "uk-icon-check-circle-o $uikitIconSize"; ?>"></i>&nbsp;B&uacute;squedas precisas</a></li>

				
				-->
				
				
				
				
				<li class="uk-parent ss-navbar-center" data-uk-dropdown="{pos:'bottom-center'mode:'click'}" aria-haspopup="true" aria-expanded="false">
					<a href="#"><i class="uk-icon-filter <?php echo $uikitIconSize ?>"></i> Filtros</a>

					<div class="uk-dropdown uk-dropdown-navbar uk-dropdown-bottom" style="top: 40px; left: 0px;">
						<ul class="uk-nav uk-nav-navbar">
						
							<li><a href="#filtros" data-uk-modal><i class="uk-icon-filter -o"></i>&nbsp;Aplicar Filtros</a></li>
							<li><a href="<?php echo "$url?opc=quitarFiltros" ?>"><i class="uk-icon-rotate-left -o"></i>&nbsp;QUITAR Filtros</a></li>

						</ul>

					</div>
				</li>
				
				
				<li class="uk-parent ss-navbar-center" data-uk-dropdown="{pos:'bottom-center'mode:'click'}" aria-haspopup="true" aria-expanded="false">
					<a href="#"><i class="uk-icon-file-excel-o <?php echo $uikitIconSize ?>"></i> Reportes</a>

					<div class="uk-dropdown uk-dropdown-navbar uk-dropdown-bottom" style="top: 40px; left: 0px;">
						<ul class="uk-nav uk-nav-navbar">
				
                
                <li><a href="<?php echo "$url?opc=Costo Inventario" ?>" ><i class="uk-icon-file-text-o"></i>&nbsp;Costo Inventario</a></li>	
				<li><a href="ReporteInvExcel.php" ><i class="uk-icon-file-excel-o"></i>&nbsp;Lista Inventario</a></li>
                <?php  
											if($MODULES["MULTISEDES"]==1){
											?>
                <li><a href="ReporteInvExcel.php?tipoReport=all_su" ><i class="uk-icon-file-excel-o"></i>&nbsp;Resumen Inv. Todas las Bodegas</a></li>
                
                
               							 <?php  
											}
											?>
                
				<li><a href="ReporteInv2.php" ><i class="uk-icon-file-excel-o"></i>&nbsp;Reporte PVP</a></li>

						</ul>

					</div>
				</li>
				
				

				<!--<li><a href="inventarioMSW.php" ><i class="uk-icon-file-word-o"></i>&nbsp;Reporte Inventario.</a></li>

				<li><a href="AJUSTES_PRECIOS.php" ><i class="uk-icon-gear <?php echo $uikitIconSize ?>"></i>&nbsp;Ajuste de Precios</a></li>
				-->
                
                <?php
				$rsX=$linkPDO->query("SELECT * FROM repetidos_inv");
                if($rowX=$rsx->fetch()){
				?>
                <!--
                <li ><a href="<?php echo "$url?opc=borrar_rep" ?>" class="uk-button-danger" ><i class="uk-icon-warning    <?php echo $uikitIconSize ?>"></i>&nbsp;BORRAR REPETIDOS</a></li>-->
                
                  <?php
                }
				?>
                
                
				<li><a href="<?php echo $url ?>" ><i class="uk-icon-refresh uk-icon-spin <?php echo $uikitIconSize ?>"></i>&nbsp;Recargar P&aacute;g.</a></li>
			</ul>
			
			<!-- Lado derecho del Navbar -->
				
		<div class="uk-navbar-content uk-hidden-small uk-navbar-flip">
			<form class="uk-form uk-margin-remove uk-display-inline-block">
				<div class="uk-form-icon">
						<i class="uk-icon-search"></i>
						<input type="text" name="busq" placeholder="Buscar..." class="">
				</div>
				<input type="submit" value="Buscar" name="opc" class="uk-button uk-button-primary">
			</form>
		</div>
			
		</nav>
		
		
<h1 align="center">INVENTARIO</h1>
 
 
<?php
//echo "boton $boton";
if($boton=='Costo+Inventario')
{
popup("informe_costo_inv.php","LISTADO DE CLIENTES","850px","500px");	
};


if(!empty($FILTRO_CLASE) || !empty($FILTRO_DES)|| !empty($FILTRO_EXIST)|| !empty($FILTRO_LAB)|| !empty($FILTRO_UTIL) || !empty($FILTRO_PROVEDORES)){
?>
<div class="uk-alert uk-alert-warning" data-uk-alert>
    <a href="" class="uk-alert-close uk-close"></a>
    <p>HAY FILTROS APLICADOS &nbsp;&nbsp;&nbsp;
    <a style="color:#000;" href="<?php echo "$url?opc=quitarFiltros" ?>"><i class="uk-icon-rotate-left <?php echo $uikitIconSize ?>"></i>&nbsp;QUITAR Filtros</a>
    
   </p>
</div>
<?php
}

?>
<form action="<?php echo $url ?>" method="post" name="form" class="uk-form">
<div class="grid-20">
	<table   cellpadding="0" cellspacing="1" align="" class="creditos_filter_table">
	<thead>
	<TR bgcolor="#CCCCCC">
	<TH colspan="5" align="center">Fecha </TH>
	</TR>
	</thead>
	<tbody>
	<tr>
	<td>Inicia:
	</td>
	<td>
	<input type="date" name="fechaI" id="date" value="<?php echo $fechaI ?>"  style="width:135px;">
	</td>
	<td>Termina:
	</td>
	<td>
	<input type="date" name="fechaF" id="date" value="<?php echo $fechaF ?>" style="width:135px;">
	</td>
	<td><?php echo $botonFiltro ?></td>
	</tr>
	</tbody>
	</table>
	
	<br>
	
</div>



<div class="uk-width-1-1">
<?php 

//echo "FECHAS: $fechaVenciALERT, $fechaVenciMIN";
require("PAGINACION.php");


 ?>
<div class="uk-overflow-container uk-width-1-1">
<table border="0" align="center" cellpadding="6px" bgcolor="#000000" class="uk-table uk-table-hover uk-table-striped uk-width-1-1"> 
 <thead>
      <tr bgcolor="#595959" style="color:#FFF" valign="top"> 
      
<?php echo $cols;   ?>

       </tr>
        </thead>
        <tbody>
          
      
<?php
$cod_su_col="nit_scs";
$COL1="";	
$COL4="";	
$COL3="";	
$COL2="";
$COL5="";
$COL6="";
$COL7="";
$COL8="";
$COL9="";
$COL10="";
$COL11="";
$COL12="";


//if($rolLv==$Adminlvl || val_secc($id_Usu,"mod_comp_egreso")){




 
$bgColor="";

while ($row = $rs->fetch()) 
{ 
$ii++;
			$ref = $row["id_glo"]; 
			$codBar = $row["id_sede"];
			$fechaVen=$row['fecha_vencimiento'];


if(1){
	
	
$where_inv="(id_pro=\'$ref\' AND id_inter=\'$codBar\' AND fecha_vencimiento=\'$fechaVen\') AND $cod_su_col=\'$codSuc\'";
$where_pro="id_pro=\'$ref\'";
	
//$functNumCom="mod_tab_row('tabTD08$ii','comp_egreso','num_com','$num_comp',' id=\'$codBar\' AND cod_su=\'$codSuc\'','$ii','text','','');";	

//$COL1="mod_tab_row('tabTD01$ii','orden_garantia','tipo_pago','$valor',' id=\'$codBar\' AND cod_su=\'$codSuc\' ','$ii','select',FPEgresoOpt,'');";

$COL3="mod_tab_row('tabTD03$ii','".tabProductos."','detalle','$row[detalle]','  $where_pro ','$ii','text','','');";



$COL4="mod_tab_row('tabTD04$ii','inv_inter','iva','$row[iva]',' $where_inv ','$ii','text','','');";
//$COL5="mod_tab_row('tabTD05$ii','$tabla','cod_garantia','$row[cod_garantia]',' $col_id=\'$codBar\' AND $cod_su_col=\'$codSuc\'','$ii','text','');";
$COL6="mod_tab_row('tabTD06$ii','inv_inter','precio_v','$row[precio_v]',' $where_inv ','$ii','text','','num');";

$COL7="mod_tab_row('tabTD07$ii','inv_inter','pvp_credito','$row[pvp_credito]',' $where_inv ','$ii','text','','num');";
$COL8="mod_tab_row('tabTD08$ii','inv_inter','pvp_may','$row[pvp_may]',' $where_inv ','$ii','text','','num');";
$COL9="mod_tab_row('tabTD09$ii','inv_inter','color','$row[color]',' $where_inv ','$ii','text','','');";
$COL10="mod_tab_row('tabTD10$ii','inv_inter','talla','$row[talla]',' $where_inv ','$ii','text','','');";
$COL11="mod_tab_row('tabTD11$ii','".tabProductos."','fab','$row[fab]',' $where_pro ','$ii','text','','');";
$COL12="mod_tab_row('tabTD12$ii','".tabProductos."','id_clase','$row[id_clase]',' $where_pro ','$ii','text','','');";
$COL13="mod_tab_row('tabTD13$ii','inv_inter','ubicacion','$row[ubicacion]',' $where_inv ','$ii','text','','');";
//$functNumComp="mod_tab_row('tabTD08$ii','comp_egreso','num_com','$num_comp',' id=\'$codBar\' AND cod_su=\'$codSuc\'','$ii','text','','');";
}


		    
            
			
            $des = $row["detalle"]; 
			$clase =$row["id_clase"];
			
			$frac = $row["fraccion"];
			$uni = $row["unidades_frac"];
			$fab =$row["fab"];
			$color=$row['color'];
			$talla=$row['talla']; 
			$marca=$row["certificado_importacion"];
			
			$DES_FULL=$row["des_full"];
			
			$costoIVA=$row['costo']*(($row['iva']/100)+1);
			
			if($idx==$ref)$bgColor="#999999";
			else $bgColor="#FFFFFF";
			
			$sub_clase=$row["id_sub_clase"];
			
			if($fechaVen<$FechaHoy && $fechaVen!="0000-00-00")$bgColor="#FF0000";
			else if($fechaVen<$fechaVenciMIN && $fechaVen!="0000-00-00")$bgColor="#FFFF00";
			else if($fechaVen<$fechaVenciALERT && $fechaVen!="0000-00-00")$bgColor="#FF6600";
			else $bgColor="#FFFFFF";
			
			$URL_IMG=$row["url_img"];
			
			 
			
         ?>
 
<tr  bgcolor="#FFFFFF" tabindex="0" id="tr<?php echo $ii ?>" onClick="click_ele(this);" onBlur="resetCss(this);">
<th><?php echo $ii ?></th>
 
<td id="tabTD02<?php echo $ii ?>" onDblClick="<?php echo $COL2; ?>"><?php echo $row['id_glo']; ?></td>
<td id="tabTD02<?php echo $ii ?>" onDblClick="<?php echo $COL2; ?>"><?php echo $row['id_sede']; ?></td>
<td id="tabTD03<?php echo $ii ?>" onDblClick="<?php echo $COL3; ?>"><?php echo $des; ?></td> 

 
 
 
<td id="tabTD06<?php echo $ii ?>" onDblClick="<?php echo $COL6; ?>"><?php echo money($row['precio_v']*1); ?></td>
 
<?php
foreach($SEDES as $clave => $valor){
	
	$sqlAux="SELECT exist, unidades_frac FROM inv_inter WHERE nit_scs='$clave' AND id_pro='$ref' AND id_inter='$codBar' AND fecha_vencimiento='$fechaVen'";
	//echo "<li>$sqlAux</li>";
	$rsAux=$linkPDO->query($sqlAux);
	$rowAux=$rsAux->fetch();
	$cantA=$rowAux["exist"];
	$uniAux=$rowAux["unidades_frac"];

	if($usar_fecha_vencimiento==1){$showCant="$cantA ; $uniAux";}
	else {$showCant="$cantA";}
	$style="style=\" \"";
	
	switch($clave){
	case 1:$style="style=\"background-color:rgb(110,106,92);\"";break;
	case 2:$style="style=\"background-color:rgb(201,204,35);\"";break;	
	case 3:$style="style=\"background-color:rgb(105,171,191);\"";break;	
	case 4:$style="style=\"background-color:rgb(184,119,105);\"";break;
	case 5:$style="style=\"background-color:rgb(110,166,99);\"";break;
	case 6:$style="style=\"background-color:rgb(81,74,181);\"";break;
	case 7:$style="style=\"background-color:rgb(204,144,75);\"";break;	
		
	}
	if(empty($showCant)){$showCant="0";}
	if($cantA!=0 || $uniAux!=0){echo "<td $style  align=\"center\"><b>$showCant</b></td>";}
	else 	{echo "<td width=\"\" $style align=\"center\">$showCant</td>";}
}

?>


<?php
if($usar_color==1){
?>
<td id="tabTD09<?php echo $ii ?>" onDblClick="<?php echo $COL9; ?>"><?php echo $color ; ?></td>
<?php
}
?>  

<?php
if($usar_talla==1){
?>
<td id="tabTD10<?php echo $ii ?>" onDblClick="<?php echo $COL10; ?>"><?php echo $talla ; ?></td>
<?php
}
?>  
 
<td id="tabTD11<?php echo $ii ?>" onDblClick="<?php echo $COL11; ?>"><?php echo $fab ; ?></td>




 
<td><a href="<?php echo  $URL_IMG ; ?>" data-uk-lightbox title="<?php echo $des; ?>"><?php echo print_pro($URL_IMG) ; ?></a></td> 
</tr> 
         
<?php 
         } 
      ?>
 
          
  </tbody>
</table>

</div>
<div id="filtros" class="uk-modal">
<div class="uk-modal-dialog">

<a class="uk-modal-close uk-close"></a>
 <h1 class="uk-text-primary uk-text-bold">Filtros</h1>
<table width="100%" class="uk-table">
<thead>
</thead>
<tbody>
<tr>
<th align="left"> Clase:</th>
<td id="filtroClassBox" >
<input type="button" name="botonFiltro" value="Ver Lista" onClick="addMultiSelc('filtroClassBox','','clases[]','clases','','clases','','des_clas','des_clas');" class="uk-button uk-button-success uk-button-large uk-width-1-2">
</td>
</tr>


 
<tr>
<th align="left"> Sub Clase:</th>
<td   id="filtroSubClassBox">
<input type="button" name="botonFiltro" value="Ver Lista" onClick="addMultiSelc('filtroSubClassBox','','sub_clases[]','sub_clase','','sub_clase','','des_sub_clase','des_sub_clase');" class="uk-button uk-button-success uk-button-large uk-width-1-2">
</td>
</tr>

 
<tr>
<th align="left"> Laboratorio:</th>
<td id="filtroLabBox" >
<input type="button" name="botonFiltro" value="Ver Lista" onClick="addMultiSelc('filtroLabBox','','fabricantes[]','fabricantes','','fabricantes','','fabricante','fabricante');" class="uk-button uk-button-success uk-button-large uk-width-1-2">
</td>
</tr>

<!--addMultiSelc(idBox,selectedEle,nameEle,idEle,claseEle,table,where,idCol,desCol) -->
<tr>
<th align="left"> Proveedor:</th>
<td id="filtroProBox" >
<input type="button" name="botonFiltro" value="Ver Lista" onClick="addMultiSelc('filtroProBox','','provedores[]','provedores','','provedores','','nit','nom_pro');" class="uk-button uk-button-success uk-button-large uk-width-1-2">
</td>
</tr>


<tr>
<th align="left">Descripci&oacute;n</th>
<td ><input type="text" name="filtroDes" value="" placeholder="NOMBRE PRODUCTO"></td>
</tr>
 
<tr>
<th align="left">Cantidades/Fracciones/Unidades</th>
<td ><select name="filtroExist">

<option value="">TODAS</option>
<option value="1">Mayores iguales a 1</option>
<option value="2">Iguales a 0</option>
<option value="-1">NEGATIVOS</option>
</select></td>
</tr>

<tr>
<th align="left">Utilidad</th> 
<td ><select name="filtroUtil">

<option value="">TODAS</option>
<option value="1">Mayor a  menor</option>
<option value="2">Menor a mayor</option>
</select></td>
</tr>
<tr>
<td colspan="2" align="center"><input type="submit" value="Aplicar Filtros" name="filtro"  class="uk-button uk-button-large uk-button-primary uk-width-1-1"></td>
</tr>
</tbody>
</table>
    </div>
</div>
<?php require("PAGINACION.php"); ?>
</div>
</form>
	
<?php require_once("FOOTER.php"); ?>


<script language="javascript1.5" type="text/javascript" src="JS/jquery.multi-select.js?<?php echo "$LAST_VER" ?>"></script>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script type="text/javascript" language="javascript1.5" src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script>	

<script language="javascript1.5" type="text/javascript">


function list_inv_warn()
{
	var data='';
	ajax_b('ajax/WARNINGS/list_inv_pedido.php',data,function(resp){
		
		var msg1='<div class="uk-alert-close">MIRAR DE INMEDIATO!!!</div>',msg2='Mercancia Proxima a agotarse',msg3=resp;
		
		if(resp!=0)open_pop(msg1,msg2,msg3)
		
		});
	
};
function disable_list_inv_warn()
{
	var data='';
	ajax_b('ajax/WARNINGS/disable_list_inv.php',data,function(resp){
		hide_pop('#modal');
	});
};


$(document).ready(function() {
	
list_inv_warn();	


$('.tablaDataTables').DataTable({
		language: {url: 'locales/es.json'},
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    { extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excelHtml5', title: 'Rotacion Inventario', customize: function ( xlsx ){
                var sheet = xlsx.xl.worksheets['sheet1.xml'];
 
                // jQuery selector to add a border
                $('row c[r*="10"]', sheet).attr( 's', '25' );
				
				
				// Loop over the cells in column `F`
                $('row c[r^="F"]', sheet).each( function () {
                    // Get the value and strip the non numeric characters
                    if ( $('is t', this).text().replace(/[^\d]/g, '') * 1 >= 500000 ) {
                        $(this).attr( 's', '20' );
                    }
                });
            }},
                    {extend: 'pdf', title: 'Rotacion Inventario',orientation:'landscape',pageSize:'letter',
					customize: function ( doc ) {
                // Splice the image in after the header, but before the table
				//doc.content[1].table.widths = [ '10%', '10%', '15%', '12%', '5%', '5%', '5%', '5%', '5%', '12%','*'];
				
                doc.content.splice( 1, 0, {
                    margin: [ 0, 0, 0, 12 ],
                    alignment: 'center',
                    image: 'data:image/png;base64,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'
                } );
                // Data URL generated by http://dataurl.net/#dataurlmaker
            }},

                    {extend: 'print',
                     customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                    }
                    }
                ]

            });
	


$.ajaxSetup({
'beforeSend' : function(xhr) {
try{xhr.overrideMimeType('text/html; charset=<?php echo $CHAR_SET ?>');}
catch(e){}
}});


 
//$('html, body').animate({scrollTop: $("#<?php //echo $idx ?>").offset().top-120}, 2000);
//$('body').scrollTo('#<?php //echo $idx ?>');
});
function resetCss(ele)
{
  //$(ele).removeAttr('style');
};
function click_ele(ele)
{
	//$(ele).css('background-color', '#FF0');
};
function eli_inv(id,idPro,feVen,des)
{
	//alert("heyyy LMAO");
	var datos='id='+id+'&idPro='+idPro+'&feVen='+feVen;
	//alert(datos);
if(confirm('Desea Borrar'+des+' cod:'+id+', fecha Vencimiento:'+feVen+'?')){
ajax_a('ajax/del_inv.php',datos,'Eliminado con Exito');
//location.assign('inventario_sedes.php');
}

}
</script>
</div><!--fin pag 1-->

</body>
</html>