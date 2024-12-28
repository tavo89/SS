<?php 
include("Conexxx.php");
include("vars_rotacion.php");
$show_cols="all";
$filtro="all";
$filtro_cant="all";$D="";
$filtro_fab="ALL";$C="";
$sede="";$F="";$F2="";

$opc="";
if(isset($_REQUEST['opc'])){$opc=$_REQUEST['opc'];}



////////////////////////////////////////////////////////////////// FILTRO SEDE ///////////////////////////////////////////////////////////////////


$sede="$codSuc";$F=" a.nit=$codSuc ";$F2=" nit_scs=$codSuc ";
if(isset($_REQUEST['sede'])){$sede=$_REQUEST['sede'];$_SESSION['sede']=$sede;}
if(isset($_SESSION['sede'])){$sede=$_SESSION['sede'];}

$sede=limpiarcampo($sede);
if($sede=="TODAS"){$F=" a.nit>0 ";$F2=" nit_scs>0 ";}
else if($sede!="TODAS"){$F=" a.nit='$sede' ";$F2=" nit_scs='$sede' ";}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$busq="";
$val="";
$boton="";
$idx="";
$tabla="inv_inter";
$col_id="id_pro";
$columns="id_inv,".tabProductos.".id_pro id_glo,inv_inter.id_inter  id_sede,detalle,id_clase,fraccion,fab,max,min,costo,precio_v,exist,iva,gana,id_fab,nit_scs,ult_venta,ult_compra";
$url="PEDIDOS.php";
$url_dialog="dialog_invIni.php";
$url_mod="modificar_inv.php";
$url_new="agregar_producto.php";
$pag="";
$limit = 40; 
$order="detalle";
$sort="";
$colArray=array(0=>'id_glo','id_sede','detalle','gana','exist');
$classActive=array(0=>'','','','','');
$offset=0;

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
if(isset($_REQUEST['opc'])){
$busq=r('busq');
$val= r('valor');
$boton= r('opc');
}
$cols="
<th width=\"90px\">#</th>
<th width=\"250\">Ref</th>

<th width=\"200\">Codigo</th>

<th width=\"200\">Descripci&oacute;n</th>

<th width=\"200\">Costo</th>
<th width=\"200\">Exist.</th>
<th width=\"50\">Min</th>
<th width=\"50\">Max</th>
<th width=\"50\">Cantidad <br>a Pedir</th>
<th width=\"\">Punto <br>de Pedido</th>
<th width=\"\">Total<br> Vendidos($DIAS Dias)</th>
<th width=\"50\">Ult. Compra</th>
<th width=\"50\">Ult. Venta</th>

<th width=\"50\">Sede</th>
";



$sql = "SELECT   $columns FROM ".tabProductos." INNER JOIN inv_inter ON ".tabProductos.".id_pro=inv_inter.id_pro  AND  $F2 $D $C ORDER BY $order LIMIT $offset, $limit"; 

if($filtro=="bad"){$sql = "SELECT   $columns FROM ".tabProductos." INNER JOIN inv_inter ON ".tabProductos.".id_pro=inv_inter.id_pro AND inv_inter.id_pro NOT IN (SELECT ref FROM art_fac_com a INNER JOIN fac_com b  ON a.num_fac_com=b.num_fac_com AND a.cod_su=b.cod_su AND a.nit_pro=b.nit_pro AND (DATE(b.fecha)>=DATE_SUB(NOW(), INTERVAL 3 MONTH) AND DATE(b.fecha)<=NOW()   )  )  AND  $F2 $D $C  ORDER BY $order LIMIT $offset, $limit"; }

 $sqlTOTexist = "SELECT   COUNT(*) AS total FROM ".tabProductos." INNER JOIN inv_inter ON ".tabProductos.".id_pro=inv_inter.id_pro  AND  $F2 AND exist>0"; 

$rsTotalExist = $linkPDO->query($sqlTOTexist); 
$rowTotal = $rsTotalExist->fetch(); 
$totalExist = $rowTotal["total"]; 
 
 
 


// echo "$sql";
 
$sqlTotal = "SELECT FOUND_ROWS() as total"; 
$rs = $linkPDO->query($sql ); 
 $rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 

$TotExist = "SELECT FOUND_ROWS() as total WHERE exist>0"; 



	

	
if($boton=='Buscar' && isset($busq) && !empty($busq)){

$sql_busq="SELECT $columns FROM ".tabProductos." INNER JOIN inv_inter ON (inv_inter.id_pro=".tabProductos.".id_pro) WHERE $F2 AND (".tabProductos.".id_pro LIKE '$busq%' OR detalle LIKE '$busq%' OR id_clase LIKE '$busq%' OR inv_inter.id_inter LIKE '$busq%')";


//$busq=mysql_escape_string($busq);
$rs=$linkPDO->query($sql_busq );
//$busq=mysql_real_escape_string($busq);
	
	}
	
//echo "$sql";
 
 
?>
<!DOCTYPE html>
<html>
<head>
<?php  include("HEADER_UK.php"); ?>
</head>

<body>
<div  class="uk-width-9-10 uk-container-center">
<?php require_once("menu_izq.php"); ?>
            <?php require_once("menu_top.php"); ?>
			<?php require_once("boton_menu.php"); ?>
<!-- Lado izquierdo del Nav -->
		<nav class="uk-navbar">

		<a class="uk-navbar-brand" href="centro.php"><?php echo $logo_menus ?> &nbsp;Motos</a> 

			<!-- Centro del Navbar -->

			<ul class="uk-navbar-nav">   <!-- !!!!!!!!!! No se Puede centrar, navbar non-compliant !!!!!!!! -->
		
				<li class="ss-navbar-center"><a href="informe_SR_resumen_sedes.php"   ><i class="uk-icon-file-excel-o <?php echo $uikitIconSize ?>"></i>&nbsp;Sin rotaci&oacute;n RESUMEN</a></li>
		 
				
				
 
				<li class="uk-parent ss-navbar-center" data-uk-dropdown="{pos:'bottom-center'mode:'click'}" aria-haspopup="true" aria-expanded="false">
					<a href="#"><i class="uk-icon-file-excel-o <?php echo $uikitIconSize ?>"></i> Reportes Excel</a>

					<div class="uk-dropdown uk-dropdown-navbar uk-dropdown-bottom" style="top: 40px; left: 0px;">
						<ul class="uk-nav uk-nav-navbar">
						
				<li><a href="reporte_mas_vendidos.php" ><i class="uk-icon-file-excel-o"></i>&nbsp;Inventario x Ventas MS Excel.</a></li>
				 
                <li><a href="reporte_cotizacion_nal.php"   >Cotizaci&oacute;n</a></li>
				<li><a href="Orden_pedido_excel.php"   ><i class="uk-icon-file-excel-o"></i>&nbsp;Orden de Pedido Excel</a></li>
				<li><a href="rotacion_productos.php"   target="blank"><i class="uk-icon-file-excel-o"></i>&nbsp;ROTACION</a></li>
                 
				<li><a href="inventarioMSW.php"   >Inventario MS Word</a></li>
				<li><a href="Orden_pedido_excel.php?filtro_sem=1"   ><i class="uk-icon-file-excel-o"></i>&nbsp;<span style="color:#F00" >Orden Pedido SEMANAL</span></a></li>

						</ul>

					</div>
				</li>
				
				

			 
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
 
 
 
 
 
 


 


 

<div   class="">
<form action="<?php echo $url ?>" method="post" name="form" class="uk-form">
 
<h1 class="uk-text-bold" align="center">Orden de Pedido <?php echo "$munSuc $FechaHoy" ?></h1>

<div class=" uk-block-secondary uk-contrast uk-grid uk-container-center">
<table width=""  cellpadding="0" cellspacing="0" align="right" class="uk-text-bold ">
<tr>
<td>Rotaci&oacute;n</td>
<td>Existencias</td>
<td>Proveedores</td>
<td>Sede</td>
</tr>
<tr>
<td>
<select name="filtro" onChange="submit();">
<option value="all" <?php if($filtro=="all")echo "selected";?>>TODAS</option>
<option value="Pp" <?php if($filtro=="Pp")echo "selected";?>>Exist. en Punto de Pedido</option>
<option value="bad" <?php if($filtro=="bad")echo "selected";?>>Exist. Sin Rotaci&oacute;n</option>
</select>
</td>
<td>
<select name="filtro_cant" onChange="submit();">
<option value="ALL" <?php if($filtro_cant=="ALL")echo "selected";?>>Existencias-TODOS</option>
<option value="cero" <?php  if($filtro_cant=="cero")echo "selected";?>>Iguales  Cero</option>
<option value="noCero" <?php if($filtro_cant=="noCero")echo "selected";?>>En Stock</option>
</select>
</td>
<td>
           <select name="filtro_fab" onChange="submit();">
           <option value="ALL" <?php if($filtro_fab=="ALL")echo "selected";?>>Proveedor-TODOS</option>
           <optgroup label="FANALCA">
            <option value="<?php echo $NIT_FANALCA ?>" <?php if($filtro_fab=="$NIT_FANALCA")echo "selected";?>>FANALCA S.A.</option>
            </optgroup>
             <optgroup label="HERO">
            <option value="<?php echo $NIT_HERO_PARTS ?>" <?php if($filtro_fab=="$NIT_HERO_PARTS")echo "selected";?>>HERO</option>
            </optgroup>
            <optgroup label="Otros">
            <option value="otrosAll" <?php if($filtro_fab!="$NIT_FANALCA"&&$filtro_fab!="ALL"&&$filtro_fab!="$NIT_HERO_PARTS")echo "selected";?>>Otros-TODOS</option>
            <?php
		    $rs2=$linkPDO->query("SELECT * FROM provedores WHERE nit!='$NIT_FANALCA' AND nit!='$NIT_HERO_PARTS' ORDER BY nom_pro" );
			while($row=$rs2->fetch()){
			?>
            <option value="<?php echo $row['nit'] ?>"><?php echo $row['nom_pro'] ?></option>
            <?php
			}
			?>
            </optgroup>
            </select>
</td>
<td>
<select name="sede" onChange="submit();">

<option value="TODAS" <?php if($sede=="TODAS")echo "selected";?>>TODAS</option>
<option value="1" <?php if($sede=="1")echo "selected";?>>ARAUCA</option>
<option value="2" <?php if($sede=="2")echo "selected";?>>TAME</option>
<option value="3" <?php if($sede=="3")echo "selected";?>>ARAUQUITA</option>
<option value="4" <?php if($sede=="4")echo "selected";?>>SARAVENA</option>
</select>

 
</td>


</tr>
<tr>
<td>Dias Inventario:</td>
<td>Dias Entrega Proveedor:</td>
<td colspan="3" align="center">Per&iacute;odo(6 meses atr&aacute;s por defecto):</td>
</tr>
<tr>
<TD>
<table>
<tr>
<td align="left"><input palceholder="" type="text" name="diasInv"  value="<?php echo $diasRotacion ?>"  data-inline="true" data-mini="true"/></td>
<td align="left"><input type="submit" value="Dias" name="opc" data-inline="true" data-mini="true" ></td>
</tr>
</table>
</TD>

<TD>
<table>
<tr>
<td align="left"><input palceholder="" type="text" name="diasPro"  value="<?php echo $Tr ?>"  data-inline="true" data-mini="true"/></td>
<td align="left"><input type="submit" value="Dias" name="opc" data-inline="true" data-mini="true" ></td>
</tr>
</table>
</TD>

<td>
<div class="uk-form-icon">
    <i class="uk-icon-calendar"></i>
<input size="15" name="fechaI" value="<?php echo $fechaI ?>" type="text" id="f_ini" onClick="//popUpCalendar(this, form_fac.f_ini, 'yyyy-mm-dd');" readonly placeholder="Fecha Inicial" class="uk-form  " data-uk-datepicker="{<?php echo $UKdatePickerFormat; ?> }">
</div>
</td>
<td>
 <div class="uk-form-icon">
    <i class="uk-icon-calendar"></i>
    <!-- uk-animation-hover  uk-animation-reverse uk-animation-scale-->
<input size="15" value="<?php echo $fechaF ?>" type="text" name="fechaF" id="f_fin" onClick="//popUpCalendar(this, form_fac.f_fin, 'yyyy-mm-dd');" readonly placeholder="Fecha Final" class="  " data-uk-datepicker="{<?php echo $UKdatePickerFormat; ?>}">
</div>
</td>
<td><?php echo $botonFiltro ?></td>
</tr>
 
</table>
 
</div>



<div class=" ">
<table border="0" align="center" cellpadding="6px" bgcolor="#000000" class=" uk-table uk-table-hover uk-border-rounded   "> 
<thead>
<tr bgcolor="#595959" style="color:#FFF" valign="top"> 
       
<th width="90px">#</th>
<th width="250">
<div style="display:inline-block;table-layout:fixed;width:80%;">Ref</div>
 
</th>

<th width="200">
<div style="display:inline-block;table-layout:fixed;width:80%;">Codigo</div>
 
</th>

<th width="200">
Descripci&oacute;n
 
</th>

<th width="200">Costo</th>
<?php
$CONT_MESES=0;
$text="";
	foreach($meses_ventas as $key=>$resultado)
	{
		$CONT_MESES++;
	$text.="<td>$MESES[$resultado]</td>";
	
	}
	echo $text."";

?>
<th width="200">ROTACION <?php //echo $CONT_MESES; ?></th>
<th width="200">
Exist.
 
</th>
<th width="50">Min</th>
<th width="50">Max</th>
<th width="50">Promedio</th>
<th width="">Promedio2</th>
<th width="50">Pedir</th>
<th width="50">Pedir Real</th>

<!--
<th width="50">Ult. Compra</th>
<th width="50">Ult. Venta</th>
-->
<th width="50">Otras Sedes</th>
<th width="">Vendidos</th>
<th width="50">Sede</th>

       </tr>
        
</thead>
<tbody>          
      
<?php 
$bgColor="";
$rotacion=0;
$IR=0;
//echo last_day($FechaHoy);
$TOT_SIN_ROTACION=0;
$TOT_SIN_ROTACION_IVA=0;

$TOT_INV_IDEAL=0;
$TOT_INV_IDEAL_IVA=0;

$TOT_INV_SOBRANTE=0;
$TOT_INV_SOBRANTE_IVA=0;

$TOT_INV_NOW=0;
$TOT_INV_NOW_IVA=0;
while ($row = $rs->fetch()) 
{ 
			$nit_scs=$row['nit_scs'];
		    $rotacion=0;
			$IR=0;
            $id_inter = $row["id_glo"]; 
            $des =$row["detalle"]; 
			$clase = $row["id_clase"];
			$id = $row["id_sede"];
			$frac = $row["fraccion"];
			$fab = $row["fab"];
			$costo=$row['costo']*1; 
			$iva=$row['iva'];
			$costoIva=$costo*($iva/100 + 1);
			$exist=$row['exist'];
			$COD_SUC=$row['nit_scs'];
			
			$ultCom=$row["ult_compra"];
			$ultVen=$row["ult_venta"];
			
			$TOT_INV_NOW+=$costo*$exist;
			$TOT_INV_NOW_IVA+=$costoIva*$exist;
			
			/*
			$promInv=promedio_inventario($codSuc,$FECHA_I,$FECHA_F,$id_inter,$Cp[$id_inter]);
			//if($promInv<=0)$promInv=1;
			if($promInv!=0){$IR=redondeo3(($tot_vendidos[$id_inter]*$costo)/($promInv*$costo));}
			else $IR=0;
			
			//if($IR!=0)$rotacion=redondeo3($DIAS/$IR);
			
			if($tot_vendidos[$id_inter]>0&&$promInv!=0)$rotacion=redondeo($DIAS*(($promInv*$costo) / ($tot_vendidos[$id_inter]*$costo)));
			else $rotacion=0;
			*/
			if($DIAS>0)$minSeg[$id_inter][$nit_scs]=(sqrt($x2[$id_inter][$nit_scs]/$DIAS)) * $Z*$t;
			//$minSeg[$r][$nit_scs]=$Z*$t;
			$Emn[$id_inter][$nit_scs]=redondeo(($Cp[$id_inter][$nit_scs])+$minSeg[$id_inter][$nit_scs]);
			
			$IR=$tot_vendidos[$id_inter][$nit_scs];
			if($FILTRO_SEMANA==1)$IR=$tot_vendidos2[$id_inter][$nit_scs];
			$rotacion=0;
			//$rotacion=redondeo3($promInv);
			//$rotacion=$promInv;
			
			/*if($promInv>0)$a=$tot_vendidos[$id_inter]/$promInv;
			else $a=0;
			
			if($a>0)$diasRotacion=$DIAS/$a;
			else $diasRotacion=0;
			*/
			//$diasRotacion=45;
			
			if($DIAS>0)$Emx[$id_inter][$nit_scs]=redondeo(($diasRotacion/$DIAS)*$tot_vendidos[$id_inter][$nit_scs]);
			
			$ST1=$Emn[$id_inter][$nit_scs]==1 && $Emx[$id_inter][$nit_scs]==0;
			
			$Pp[$id_inter][$nit_scs]=redondeo($Cp[$id_inter][$nit_scs]*$Tr +$Emn[$id_inter][$nit_scs]);
			$CP[$id_inter][$nit_scs]=$Emx[$id_inter][$nit_scs] - $E[$id_inter][$nit_scs];
			if($CP[$id_inter][$nit_scs]==0 && $Emn[$id_inter][$nit_scs]=1)	{$CP[$id_inter][$nit_scs]=1;}	
			if($Pp[$id_inter][$nit_scs]>$Emx[$id_inter][$nit_scs])$Pp[$id_inter][$nit_scs]=redondeo($Cp[$id_inter][$nit_scs]*$Tr);
			if($ST1){$Pp[$id_inter][$nit_scs]=1;}	
			
			if($idx==$id_inter)$bgColor="#999999";
			else $bgColor="#FFFFFF";
			
			if($Emx[$id_inter][$nit_scs]==1){$Pp[$id_inter][$nit_scs]=1;}
			if($Emx[$id_inter][$nit_scs]==0 && $Emn[$id_inter][$nit_scs]==0){$Pp[$id_inter][$nit_scs]=0;}
			
			 
			
			
			
if($CP[$id_inter][$nit_scs]<0){$TOT_INV_SOBRANTE+=$costo*$CP[$id_inter][$nit_scs];$TOT_INV_SOBRANTE_IVA+=$costoIva*$CP[$id_inter][$nit_scs];}
			
if($exist>0 && $IR==0){$TOT_SIN_ROTACION+=$exist*$costo;$TOT_SIN_ROTACION_IVA+=$exist*$costoIva;}
			
$TOT_INV_IDEAL+=$Emx[$id_inter][$nit_scs]*$costo;
$TOT_INV_IDEAL_IVA+=$Emx[$id_inter][$nit_scs]*$costoIva;
			
			
			   
if($filtro=="bad" && $Emn[$id_inter][$nit_scs]<=0){	
$ii++;		

include("rotacion_productos_DIAS_APEX01.php");

 
}/////////////////////// FIN SIN ROTACION /////////////////////////////////////////////////////////		
//$filtro=="Pp" && $row['exist']<$Pp[$id_inter][$nit_scs] && $Emn[$id_inter][$nit_scs]>0 &&$Pp[$id_inter][$nit_scs]>0 
if($filtro=="Pp" && $IR>=1){
$ii++;
include("rotacion_productos_DIAS_APEX01.php");
}///////////////////////////////////////// FIN Pp ////////////////////////////////////////////////////////

if($filtro=="all"){
$ii++;
include("rotacion_productos_DIAS_APEX01.php");
}///////////////////////////////////////////////// FIN TODAS //////////////////////////////////////////////////////////
		 
} ////////////////////////////////////// FIN WHILE QUERY ///////////////////////////////////////////////////
      ?>
 

 
</tbody>
</table>
</div> <!-- grid table -->

</form>
<?php include("PAGINACION.php");?>
</div> <!-- grid contend -->

</div><!--fin pag 1-->
<?php  include("FOOTER_UK.php"); ?>
<script language="javascript1.5" type="text/javascript">
$(document).ready(function(){
$('html, body').animate({scrollTop: $("#<?php echo $idx ?>").offset().top-120}, 2000);
//$('body').scrollTo('#<?php echo $idx ?>');
});

function resetCss(ele)
{
  $(ele).removeAttr('style');
};
function click_ele(ele)
{
	$(ele).css('background-color', '#FF0');
};
</script>


</body>
</html>