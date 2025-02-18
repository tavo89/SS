<?php 
include_once("Conexxx.php");
$busq="";
$val="";
$val2=urlencode(r("valor2"));
$boton="";
$idx="";
$tabla="inv_inter";
$col_id="id_pro";
$columns="ubicacion,serial_pro,serial_inv,id_sub_clase,".tabProductos.".id_pro id_glo,inv_inter.id_inter  cod_barras,detalle,id_clase,fraccion,fab,max,min,costo,precio_v,exist,iva,impuesto_saludable,gana,talla,color,".tabProductos.".presentacion,fecha_vencimiento,fraccion,unidades_frac,pvp_credito,certificado_importacion,url_img,pvp_may,aplica_vehi,des_full,nit_scs,tipo_producto";
$url=rawurlencode("inventario_inicial.php");
$url_dialog="dialog_invIni.php";
$url_mod="modificar_inv.php";
$url_new="agregar_producto.php";
$pag="";
$limit = 20; 
$order="detalle";
$sort="";
$colArray=array(0=>'id_glo','cod_barras','detalle','gana');
$classActive=array(0=>'','','','');
$offset=0;

$opc="";
if(isset($_REQUEST['opc'])){$opc=$_REQUEST['opc'];}



if($opc=="borrar_rep"){
	
//t1("DELETE FROM  `inv_inter`  WHERE  `id_inter` IN ( SELECT  `id_inter` FROM  `repetidos_inv`) AND  `id_pro` =  `id_inter` ");
}

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




/////////////////////////////////////////////////////////////////////// FILTRO tipo Producto. ///////////////////////////////////////////////////////////
$filtroB="";
$B="";

if(isset($_REQUEST['filtroTipoPro'])){
	$filtroB=$_REQUEST['filtroTipoPro'];
	$_SESSION['filtroTipoPro']=$filtroB;
	if($filtroB=="Normal")$B="AND tipo_producto='Normal'";
	else if($filtroB=="Manufacturado")$B="AND tipo_producto='Manufacturado'";
	else if($filtroB=="Materia prima"){$B=" AND tipo_producto='Materia prima'";}
	else $B="";
}

if(isset($_SESSION['filtroTipoPro']))
{
	$filtroB=$_SESSION['filtroTipoPro'];
	if($filtroB=="Normal")$B="AND tipo_producto='Normal'";
	else if($filtroB=="Manufacturado")$B="AND tipo_producto='Manufacturado'";
	else if($filtroB=="Materia prima"){$B=" AND tipo_producto='Materia prima'";}
	else $B="";
	
}
 
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



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

$FILTROS_TABLA=" $FILTRO_DES $FILTRO_EXIST $FILTRO_CLASE $FILTRO_LAB $FILTRO_VENCIDOS $FILTRO_SUB_CLASE $FILTRO_PROVEDORES ";

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
<td></td>
<th width=\"250\">
<div style=\"display:inline-block;table-layout:fixed;width:80%;\">Ref</div>
</th>

<th width=\"200\">
<div style=\"display:inline-block;table-layout:fixed;width:80%;\">Codigo</div>
</th>

<th width=\"200\">
<div style=\"display:inline-block;width:80%;\">Descripci&oacute;n</div>
</div></th>
";

if($MODULES["DES_FULL"]==1){$cols.="<th width=\"150\">USO</th>";}

$cols.="
<!--
<th width=\"200\">Costo</th>
-->
<th width=\"150\">I.V.A</th>
<th width=\"150\">Imp. Saludable</th>
<th width=\"200\">Costo+IVA</th>
";
if($usar_ubica==1){
	$cols.="
<th width=\"150\">
<div style=\"display:inline-block;width:80%;\">Ubicaci&oacute;n</div>
</div>
</th>";
}

$cols.="
<th width=\"150\">Util</th>
<th width=\"200\">Fraccion</th>
";

if($MODULES["PVP_CREDITO"]==1){$cols.="<th width=\"200\">Credito</th>";}

if($MODULES["PVP_MAYORISTA"]==1){
$cols.="<th width=\"200\">$label_pvp_mayo</th>";
}
$cols.="<th width=\"200\">P.V.P</th>
<th width=\"200\">Cant/Uni.</th>

<!--<th width=\"50\">Marca</th>-->
$colColor
$colTalla
<!--
<th width=\"200\">Fabricante</th>
-->
<th width=\"200\">Fabricante</th>
<th width=\"200\">Clase</th>
<!--
<th width=\"200\">Sub Clase</th>
-->";

if($MODULES["APLICA_VEHI"]==1){$cols.="<th width=\"200\">Aplica</th>";	}

if($usar_fecha_vencimiento==1)
{
$cols.="<th width=\"200\">Vencimiento</th>";	
}

$cols.="<th width=\"120\">IMG</th>";

if(empty($FILTRO_UTIL))$sql = "SELECT  $columns FROM ".tabProductos." INNER JOIN inv_inter ON ".tabProductos.".id_pro=inv_inter.id_pro WHERE  nit_scs=$codSuc  $A $B $FILTROS_TABLA ORDER BY $order  LIMIT $offset, $limit"; 
else $sql = "SELECT  $columns FROM ".tabProductos." INNER JOIN inv_inter ON ".tabProductos.".id_pro=inv_inter.id_pro WHERE  nit_scs=$codSuc  $A  $B $FILTROS_TABLA $FILTRO_UTIL  LIMIT $offset, $limit";



if($boton=='mod'&& !empty($val) && !empty($val2)){
	
	//$_SESSION['id']=$val;
	$_SESSION['pag']=$pag;
	$feVen=r('fe_ven');
	$serialInv=r("serial_inv");
	$serialPro=r("serial_pro");
	header("location: $url_mod?REF=$val&fe_ven=$feVen&idPro=$val2&serial_inv=$serialInv&serial_pro=$serialPro");
	}
 
 

 
 
$sqlTotal = "SELECT COUNT(*) as total FROM ".tabProductos." INNER JOIN inv_inter ON ".tabProductos.".id_pro=inv_inter.id_pro WHERE nit_scs='$codSuc' $A  $B $FILTROS_TABLA"; 
$rs = $linkPDO->query($sql); 
$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 

	
if($boton=='Buscar' && isset($busq) && !empty($busq)){

$Sdetalle=fullBusq($busq,"detalle");

if(isset($bp)&&$bp==1)
{
$sql_busq="SELECT $columns FROM ".tabProductos." INNER JOIN inv_inter ON (inv_inter.id_pro=".tabProductos.".id_pro) WHERE nit_scs=$codSuc AND ($Sdetalle) $A $FILTROS_TABLA";
}
else{
$sql_busq="SELECT $columns FROM ".tabProductos." INNER JOIN inv_inter ON (inv_inter.id_pro=".tabProductos.".id_pro) WHERE nit_scs=$codSuc AND ($Sdetalle OR ".tabProductos.".id_pro = '$busq' OR  id_clase LIKE '$busq%' OR inv_inter.id_inter = '$busq') $A $FILTROS_TABLA";	
}

//echo "$sql_busq";

$rs=$linkPDO->query($sql_busq);

	
	}
	
//echo "<li>$sql</li> ";
 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<?php include_once("HEADER.php"); ?>
<link href="css/multi-select.css" rel="stylesheet" type="text/css" />	

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

		<a class="uk-navbar-brand uk-visible-large" href="centro.php"><img src="Imagenes/logoICO.ico" class="icono_ss"> &nbsp;SmartSelling</a> 

			<!-- Centro del Navbar -->

			<ul class="uk-navbar-nav">   <!-- !!!!!!!!!! No se Puede centrar, navbar non-compliant !!!!!!!! -->
		
				<li class="ss-navbar-center"><a href="<?php echo $url_new ?>" ><i class="uk-icon-plus-square <?php echo $uikitIconSize ?>"></i>&nbsp;Crear Producto</a></li>
				<!--
				<li><a href="<?php if(isset($bp)&&$bp==1)echo "$url?bp=0";else echo "$url?bp=1";  ?>" ><i class="<?php if(isset($bp)&&$bp==1)echo "uk-icon-check-circle $uikitIconSize";else echo "uk-icon-check-circle-o $uikitIconSize"; ?>"></i>&nbsp;B&uacute;squedas precisas</a></li>

				
				-->
				
				
				
				
				<li class="uk-parent ss-navbar-center" data-uk-dropdown="{pos:'bottom-center',mode:'click'}" aria-haspopup="true" aria-expanded="false" >
					<a href="#" style="cursor:pointer;"><i class="uk-icon-filter <?php echo $uikitIconSize ?>"></i> Filtros</a>

					<div class="uk-dropdown uk-dropdown-navbar uk-dropdown-bottom" style="top: 40px; left: 0px;">
						<ul class="uk-nav uk-nav-navbar">
						
							<li><a href="#filtros" data-uk-modal><i class="uk-icon-filter -o"></i>&nbsp;Aplicar Filtros</a></li>
							<li><a href="<?php echo "$url?opc=quitarFiltros" ?>"><i class="uk-icon-rotate-left -o"></i>&nbsp;QUITAR Filtros</a></li>

						</ul>

					</div>
				</li>
				
				
				<li class="uk-parent ss-navbar-center" data-uk-dropdown="{pos:'bottom-center',mode:'click'}" aria-haspopup="true" aria-expanded="false">
					<a href="#" style="cursor:pointer;"><i class="uk-icon-file-excel-o <?php echo $uikitIconSize ?>"></i> Reportes</a>

					<div class="uk-dropdown uk-dropdown-navbar uk-dropdown-bottom" style="top: 40px; left: 0px;">
						<ul class="uk-nav uk-nav-navbar">
				
                
                <li><a href="<?php echo "$url?opc=Costo Inventario" ?>" ><i class="uk-icon-file-text-o"></i>&nbsp;Costo Inventario</a></li>	
				<li><a href="ReporteInventarioExcel.php" ><i class="uk-icon-file-excel-o"></i>&nbsp;Lista Inventario</a></li>
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
				//$rsX=$linkPDO->query("SELECT * FROM repetidos_inv");
                //if($rowX=$rsX->fetch()){
				?>
                <!--
                <li ><a href="<?php echo "$url?opc=borrar_rep" ?>" class="uk-button-danger" ><i class="uk-icon-warning    <?php echo $uikitIconSize ?>"></i>&nbsp;BORRAR REPETIDOS</a></li>-->
                
                  <?php
                //}
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
<form action="<?php echo $url ?>" method="post" name="form" class="uk-form" >
<div class="">
	<table   cellpadding="0" cellspacing="1" align="" class="creditos_filter_table tabla-datos">
	<thead>
	<TR bgcolor="#CCCCCC">
	<TH colspan="5" align="center" class="<?php if($usar_fecha_vencimiento!=1){echo "uk-hidden";}?>">Filtro Fecha Vencimiento</TH>
    <TH colspan="2" align="center" class="<?php if($usar_tipos_productos!=1){echo "uk-hidden";}?>">Tipo Producto</TH>
	</TR>
	</thead>
	<tbody>
	<tr class="<?php if($usar_fecha_vencimiento!=1){echo "uk-hidden";}?>">
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
    <tr class="<?php if($usar_tipos_productos!=1){echo "uk-hidden";}?>">
    <td colspan="2">
   <select name="filtroTipoPro" onChange="submit()">
   <option value="TODOS" <?php if($filtroB=="TODOS")echo "selected" ?>>TODOS</option>
   <option value="Normal" <?php if($filtroB=="Normal")echo "selected" ?>>Normales</option>
<option value="Manufacturado" <?php if($filtroB=="Manufacturado")echo "selected" ?>>Manufacturados</option>
<option value="Materia prima" <?php if($filtroB=="Materia prima")echo "selected" ?>>Materia prima</option>
</select>
    </td>
	</tr>
 
    
    
	</tbody>
	</table>
	
	<br>
	
</div>



<div class="uk-width-1-1">
<?php 

//echo "FECHAS: $fechaVenciALERT, $fechaVenciMIN";
 


 ?>
<div class="uk-overflow-containerZ uk-width-1-1">
<table border="0" align="center" cellpadding="6px" bgcolor="#000000" class="uk-table uk-table-hover uk-table-striped uk-width-1-1 tabla-datos" style=""> 
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
			$id_inter = $row["id_glo"]; 
			$id = $row["cod_barras"];
			$fechaVen=$row['fecha_vencimiento'];


if(1){
	
	
$where_inv="(id_pro=\'$id_inter\' AND id_inter=\'$id\' AND fecha_vencimiento=\'$fechaVen\') AND $cod_su_col=\'$codSuc\'";
$where_pro="id_pro=\'$id_inter\'";
	
//$functNumCom="mod_tab_row('tabTD08$ii','comp_egreso','num_com','$num_comp',' id=\'$ID\' AND cod_su=\'$codSuc\'','$ii','text','','');";	

//$COL1="mod_tab_row('tabTD01$ii','orden_garantia','tipo_pago','$valor',' id=\'$ID\' AND cod_su=\'$codSuc\' ','$ii','select',FPEgresoOpt,'');";

$COL3="mod_tab_row('tabTD03$ii','".tabProductos."','detalle','$row[detalle]','  $where_pro ','$ii','text','','');";



$COL4="mod_tab_row('tabTD04$ii','inv_inter','iva','$row[iva]',' $where_inv ','$ii','text','','');";
//$COL5="mod_tab_row('tabTD05$ii','$tabla','cod_garantia','$row[cod_garantia]',' $col_id=\'$ID\' AND $cod_su_col=\'$codSuc\'','$ii','text','');";
$COL6="mod_tab_row('tabTD06$ii','inv_inter','precio_v','$row[precio_v]',' $where_inv ','$ii','text','','num');";

$COL7="mod_tab_row('tabTD07$ii','inv_inter','pvp_credito','$row[pvp_credito]',' $where_inv ','$ii','text','','num');";
$COL8="mod_tab_row('tabTD08$ii','inv_inter','pvp_may','$row[pvp_may]',' $where_inv ','$ii','text','','num');";
$COL9="mod_tab_row('tabTD09$ii','inv_inter','color','$row[color]',' $where_inv ','$ii','text','','');";
$COL10="mod_tab_row('tabTD10$ii','inv_inter','talla','$row[talla]',' $where_inv ','$ii','text','','');";
$COL11="mod_tab_row('tabTD11$ii','".tabProductos."','fab','$row[fab]',' $where_pro ','$ii','text','','');";
$COL12="mod_tab_row('tabTD12$ii','".tabProductos."','id_clase','$row[id_clase]',' $where_pro ','$ii','text','','');";
$COL13="mod_tab_row('tabTD13$ii','inv_inter','ubicacion','$row[ubicacion]',' $where_inv ','$ii','text','','');";
//$functNumComp="mod_tab_row('tabTD08$ii','comp_egreso','num_com','$num_comp',' id=\'$ID\' AND cod_su=\'$codSuc\'','$ii','text','','');";
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
			
			$iva = !empty($row['iva'])?$row['iva']:0;
			$costo = !empty($row['costo'])?$row['costo']:0;
			
			$costoIVA=$costo*(($iva/100)+1);
			
			if($idx==$id_inter)$bgColor="#999999";
			else $bgColor="#FFFFFF";
			
			$sub_clase=$row["id_sub_clase"];
			
			if($fechaVen<$FechaHoy && $fechaVen!="0000-00-00")$bgColor="#FF0000";
			else if($fechaVen<$fechaVenciMIN && $fechaVen!="0000-00-00")$bgColor="#FFFF00";
			else if($fechaVen<$fechaVenciALERT && $fechaVen!="0000-00-00")$bgColor="#FF6600";
			else $bgColor="#FFFFFF";
			
			$URL_IMG=$row["url_img"];
			$tipoPro=$row["tipo_producto"];
			
			 
			
         ?>
 
<tr  bgcolor="#FFFFFF" tabindex="0" id="tr<?php echo $ii ?>" onClick="click_ele(this);" onBlur="resetCss(this);">
<th><?php echo $ii ?></th>
<td>

<div class="uk-button-dropdown" data-uk-dropdown="{mode:'click'}" aria-haspopup="true" aria-expanded="false">

<a class="uk-button uk-button-primary" style="width:100px;font-size:16px;">Opciones <i class="uk-icon-caret-down"></i></a>
<div class="uk-dropdown uk-dropdown-small uk-dropdown-bottom" style="top: 30px; left: 0px;">
<ul class="uk-nav uk-nav-dropdown" style=" font-size:18px;">
<?php
if(($rolLv==$Adminlvl || val_secc($id_Usu,"inventario_mod")) && $codSuc>0){
?>
<li><a href="<?php echo $url."?opc=mod&valor=".urlencode($id)."&valor2=".urlencode($id_inter)."&pag=$pag&fe_ven=$fechaVen&serial_inv=$row[serial_inv]&serial_pro=$row[serial_pro]" ?>" class=""  ><i class="uk-icon-pencil    uk-icon-small"></i> Modificar<input type="checkbox" id="<?php echo $id ?>" style="visibility:hidden"></a></li>


<?php if($usar_tipos_productos==1 && $tipoPro=="Manufacturado"){ ?>
<li><a href="<?php echo "inv_gestion_receta.php?detalle=$des&serial_inv=$row[serial_inv]&codBar=$row[cod_barras]&Ref=$id_inter&feVen=$fechaVen";?>" onClick="" class="" ><i class="uk-icon-book     uk-icon-small"></i> Gestionar Receta</a></li>
<?php }?>
 

<li><a href="#" onClick="eli_inv('<?php echo $id ?>','<?php echo $id_inter ?>','<?php echo $fechaVen ?>','<?php echo $des ?>')" class="" ><i class="uk-icon-remove     uk-icon-small"></i> Eliminar Producto</a></li>
            
<?php
}
?>






<li class="uk-nav-divider"></li>
<li><a href="#" onClick="print_pop('<?php echo "kardex.php?codBar=$row[cod_barras]&Ref=$id_inter&feVen=$fechaVen"; ?>','Kardex',850,650);" class=""><i class="uk-icon-list     uk-icon-small"></i> K&aacute;rdex/Historial Movimientos</a></li>

</ul>
</div>
</div>
 


</td>             
<td id="tabTD01<?php echo $ii ?>" onDblClick="<?php echo $COL1; ?>"><?php echo $id_inter; ?></td>
<td id="tabTD02<?php echo $ii ?>" onDblClick="<?php echo $COL2; ?>"><?php echo $row['cod_barras']; ?></td>
<td id="tabTD03<?php echo $ii ?>" onDblClick="<?php echo $COL3; ?>"><?php echo $des; ?></td> 

<?php if($MODULES["DES_FULL"]==1){?>

<td><?php echo $DES_FULL; ?></td> 
<?php }?>

<!--
<td><?php echo money($row['costo']*1); ?></td>
-->

<td id="tabTD04<?php echo $ii ?>" onDblClick="<?php echo $COL4; ?>"><?php echo $row['iva']; ?>%</td>
<td id="tabTD14<?php echo $ii ?>" onDblClick="<?php echo ''; ?>"><?php echo $row['impuesto_saludable']; ?>%</td>

<td><?php echo money($costoIVA); ?></td>
<?php

if($usar_ubica==1){
	
?>
<td id="tabTD13<?php echo $ii ?>" onDblClick="<?php echo $COL13; ?>"><?php echo $row['ubicacion']; ?></td>
<?php }?>
<td id="tabTD04<?php echo $ii ?>" onDblClick="<?php echo $COL4; ?>"><?php echo $row['gana']; ?>%</td>
<td align="center"><?php echo $frac; ?></td>

<?php
if($MODULES["PVP_CREDITO"]==1){
?>
<td id="tabTD07<?php echo $ii ?>" onDblClick="<?php echo $COL7; ?>"><?php echo money($row['pvp_credito']*1); ?></td>
<?php
}
?>
<?php
if($MODULES["PVP_MAYORISTA"]==1){
?>
<td id="tabTD08<?php echo $ii ?>" onDblClick="<?php echo $COL8; ?>"><?php echo money($row['pvp_may']*1); ?></td>
<?php
}
?>
<td id="tabTD06<?php echo $ii ?>" onDblClick="<?php echo $COL6; ?>"><?php echo money($row['precio_v']*1); ?></td>

<td align="center"><?php echo (1*$row['exist']).";".$row['unidades_frac']; ?></td>
<!--<td><?php //echo $marca; ?></td> -->
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
<!--
<td><?php echo $sub_clase ; ?></td>
<td><?php echo $fab ; ?></td>
-->
<td id="tabTD11<?php echo $ii ?>" onDblClick="<?php echo $COL11; ?>"><?php echo $fab ; ?></td>
<td id="tabTD12<?php echo $ii ?>" onDblClick="<?php echo $COL12; ?>"><?php echo $clase ; ?></td>

<?php if($MODULES["APLICA_VEHI"]==1){?>
<td><?php echo $row['aplica_vehi']; ?></td>
<?php }?>

<?php
if($usar_fecha_vencimiento==1){
?>
<td bgcolor="<?php echo $bgColor ?>"><?php echo $fechaVen ; ?></td>
<?php
}
?> 
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
<option value="3">Fracciones Incorrectas</option>
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
<?php include("PAGINACION.php"); ?>
</div>
</form>
	
<?php include_once("FOOTER.php"); 
include('alertaPagoClienteSS.php');?>


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

/*$(document).bind("mobileinit", function(){ 
    $('#page').on('pageinit', function() { 
        simplePopUp('Welcome'); 
    }); 
});
*/

$(document).ready(function() {
	
list_inv_warn();


	


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

	var datos='id='+id+'&idPro='+idPro+'&feVen='+feVen;
	if(confirm('Desea Borrar'+des+' cod:'+id+', fecha Vencimiento:'+feVen+'?')){
	ajax_a('ajax/del_inv.php',datos,'Eliminado con Exito');
	}

}
</script>
</div><!--fin pag 1-->

</body>
</html>