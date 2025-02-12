<?php 
include_once("Conexxx.php");
 

$UserFilter="AND fecha_crea>='$fechaCreaUsu'";
if($rolLv==$Adminlvl || $separar_registros_por_usuarios==0)$UserFilter="";

if($rolLv==$Adminlvl && $id_Usu=="$NIT_PUBLICO_GENERAL" || $id_Usu=="100100100"){
//ajustar_precios_compras("2018-01-01","2018-07-27");
}

if($rolLv!=$Adminlvl && !val_secc($id_Usu,"compras")){header("location: centro.php");}
$busq="";
$val="";
$val2="";
$val3="";
$boton="";
$fe="";
$opc="";
if(isset($_REQUEST['opc'])){$opc=$_REQUEST['opc'];}
if(isset($_REQUEST['busq']))$busq=$_REQUEST['busq'];
if(isset($_REQUEST['valor']))$val= $_REQUEST['valor'];
if(isset($_REQUEST['valor2']))$val2= $_REQUEST['valor2'];
if(isset($_REQUEST['valor3']))$val3= $_REQUEST['valor3'];
if(isset($_REQUEST['opc']))$boton= $_REQUEST['opc'];
if(isset($_REQUEST['fecha']))$fe=$_REQUEST['fecha'];



/////////////////////////////////////////////////////////////// FILTRO FECHA //////////////////////////////////////////////////////////////////////////
$fechaI="";
$fechaF="";
$PAG_fechaI="fechaI_com";
$PAG_fechaF="fechaF_com";
$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"Filtrar\" class=\"uk-button\" class=\"uk-button uk-button-success\">";
$A="";
if(isset($_REQUEST['fechaI'])){$fechaI=limpiarcampo($_REQUEST['fechaI']); $_SESSION[$PAG_fechaI]=$fechaI;}
if(isset($_REQUEST['fechaF'])){$fechaF=limpiarcampo($_REQUEST['fechaF']);$_SESSION[$PAG_fechaF]=$fechaF;}

if(isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI])){$fechaI=$_SESSION[$PAG_fechaI];}
if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF])){$fechaF=$_SESSION[$PAG_fechaF];$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"QUITAR\" class=\"uk-button uk-button-danger  \">";}

if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF]) && isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI]))
{
	$A=" AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF') ";
}





if($opc=="QUITAR")
{
	$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"Filtrar\" class=\"uk-button uk-button-danger\" >";
	$fechaI="";
	$fechaF="";
	unset($_SESSION[$PAG_fechaI]);
	unset($_SESSION[$PAG_fechaF]);
	$A="";
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------


/////////////////////////////////////////////////////// FILTRO NOMBRE ////////////////////////////////////////////////////////

$C="";
$nom="";
$nom_request="nom_pro";
$nom_session="nombre_proveedor";
$nom_col="nom_pro";
$botonFiltroNom="<input type=\"submit\" name=\"opc\" value=\"Buscar Nombre\" class=\"uk-button uk-button-success\">";

if(isset($_SESSION[$nom_session])){$nom=limpiarcampo($_SESSION[$nom_session]);$C=" AND ($nom_col='$nom' OR nit_pro='$nom')";

$botonFiltroNom="<input type=\"submit\" name=\"opc\" value=\"QUITAR NOMBRE\"  class=\"uk-button uk-button-danger\">";
};

if(isset($_REQUEST[$nom_request]) && !empty($_REQUEST[$nom_request])){$nom=limpiarcampo($_REQUEST[$nom_request]); $_SESSION[$nom_session]=$nom;$C=" AND $nom_col='$nom'";
$botonFiltroNom="<input type=\"submit\" name=\"opc\" value=\"QUITAR NOMBRE\" class=\"uk-button uk-button-danger \" >";
}

/*
if(isset($_REQUEST['nom_cli'])){
	
	$nom=limpiarcampo($_REQUEST['nom_cli']);
	$botonFiltroNom="<input type=\"submit\" name=\"opc\" value=\"QUITAR NOMBRE\" data-inline=\"true\" data-mini=\"true\" >";	
}
*/

if($opc=="QUITAR NOMBRE")
{
	$botonFiltroNom="<input type=\"submit\" name=\"opc\" value=\"Buscar Nombre\" class=\"uk-button uk-button-success\" >";
	$nom="";
	unset($_SESSION[$nom_session]);
	$C="";
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////// FILTRO ESTADO FAC. ///////////////////////////////////////////////////////////
$filtroB="";
$B="";
if(isset($_REQUEST['filtroB'])){
	$filtroB=$_REQUEST['filtroB'];
	$_SESSION['filtroB_compras']=$filtroB;
	if($filtroB=="Pendientes")$B="AND pago='PENDIENTE'";
	else if($filtroB=="Pagados")$B="AND pago='PAGADO'";
	else if($filtroB=="Anuladas")$B="AND estado='ANULADA'";
	else if($filtroB=="Morosos"){$B=" AND DATEDIFF(CURRENT_DATE(),DATE(fecha) )>$DIAS_BAN_CLI AND fecha_pago='0000-00-00 00:00:00' ";}
	else $B="";
}

if(isset($_SESSION['filtroB_compras']))
{
	$filtroB=$_SESSION['filtroB_compras'];
	if($filtroB=="Pendientes")$B="AND pago='PENDIENTE'";
	else if($filtroB=="Pagados")$B="AND pago='PAGADO'";
	else if($filtroB=="Anuladas")$B="AND estado='ANULADA'";
	else if($filtroB=="Morosos"){$B=" AND DATEDIFF(CURRENT_DATE(),DATE(fecha) )>$DIAS_BAN_CLI AND fecha_pago='0000-00-00 00:00:00' ";}
	else $B="";
	
}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////


$cols="<th width=\"90px\">#</th>
 
<th width=\"100\">Cod.</th>
<th width=\"100\">No. Fac</th>
<th width=\"200\">Proveedor</th>
<th width=\"200\">NIT</th>
<!--<th width=\"100\">Ciudad</th>

-->
<th width=\"50\">TOT</th>
<th width=\"200\">Saldo</th>
<th width=\"150\">Estado</th>
<th width=\"150\">Fecha</th>

";
if($MODULES["CUENTAS_PAGAR"]==1){
$cols.=
"<th width=\"150\" class=\"uk-hiddens\">Vencimiento</th>
<th width=\"100\">Pago</th>
<th width=\"150\" class=\"uk-visible-large\">Fecha pago</th>
<th width=\"50\" class=\"uk-hidden\">Dias L&iacute;mite</th>
";
}
$cols.="<th width=\"80\">TIPO</th>";


$tabla="fac_com";
$col_id="num_fac_com";
$columns="feVen,num_fac_com,nit_pro,nom_pro,fecha,tel,dir,ciudad,flete,cod_su,(tot-(r_fte+r_ica+r_iva+dcto2)) as tot,DATE(fecha_crea) as fecha_crea,fecha_mod,tipo_fac,serial_fac_com,estado,pago,fecha_pago,DATEDIFF(CURRENT_DATE(),DATE(fecha) ) AS mora,DATEDIFF(DATE(feVen),CURRENT_DATE() ) AS mora2, DATEDIFF(CURRENT_DATE(),DATE(fecha_crea)) AS limAnula";
$url="compras.php";
$url_dialog="dialog_invIni.php";
$url_mod="mod_fac_com.php";
$url_new="fac_compra.php";
$pag="";
$limit = 20; 
$order="fecha_crea";
 
if(isset($_REQUEST["pag"])){$pag = (int) $_REQUEST["pag"];}
if ($pag < 1) 
{ 
   $pag = 1; 
} 
$offset = ($pag-1) * $limit; 
$ii=$offset;
 
 
/*********************************************************************************************************************/

$FILTRO_VENCIMIENTO= comVenciFilter("filtroVenci","Filtro_venci_com","fac_com",$opc);
$FILTRO_TIPO=tipoCompraFilter("FiltroTipoFac_compra","FiltroTipoFacCompra","tipo_fac",$opc);

$sql = "SELECT  $columns FROM fac_com  WHERE  cod_su=$codSuc $UserFilter AND tipo_fac!='Traslado' $FILTRO_VENCIMIENTO $FILTRO_TIPO $C $A $B ORDER BY serial_fac_com DESC   LIMIT $offset, $limit"; 



if($boton=='mod'&& !empty($val)){
	if($fe!="CERRADA"  ){
	$_SESSION['num_fac']=$val;
	$_SESSION['nit_pro']=$val2;
	$_SESSION['pag']=$pag;
	header("location: $url_mod");
	}
	else {
		
			$_SESSION['num_fac']=$val;
			$_SESSION['nit_pro']=$val2;
			$_SESSION['pag']=$pag;
			header("location: mod_fac_com_limited.php");
		}
	}


if($boton=='Elimina Listado'){
    $num_fac=r('numFac');
	$nit_pro=r('nit_fac');
    $sql_delete_inv = " DELETE inv_inter
	                    FROM inv_inter
	                    INNER JOIN art_fac_com ON art_fac_com.ref = inv_inter.id_pro
						AND art_fac_com.cod_barras=inv_inter.id_inter
						AND art_fac_com.fecha_vencimiento = inv_inter.fecha_vencimiento
						AND art_fac_com.cod_su = inv_inter.nit_scs
	                    WHERE art_fac_com.num_fac_com = '$num_fac'
						AND   art_fac_com.nit_pro='$nit_pro'     
						AND   art_fac_com.cod_su ='$codSuc' ";
    $linkPDO->exec($sql_delete_inv);

    $sql_delete_productos = "DELETE FROM productos WHERE id_pro NOT IN(SELECT id_pro FROM inv_inter)";
	$linkPDO->exec($sql_delete_productos);

	$sql="DELETE FROM art_fac_com WHERE num_fac_com='$num_fac' AND nit_pro='$nit_pro' AND cod_su=$codSuc";
	$linkPDO->exec($sql);

	$sql="DELETE FROM fac_com WHERE num_fac_com='$num_fac' AND nit_pro='$nit_pro' AND cod_su=$codSuc";
	$linkPDO->exec($sql);
	header("location: compras.php");
}

if($boton=='imp'&& !empty($val)){
	
	 
	$_SESSION['pag']=$pag;
	header("location: imp_fac_com.php?num_fac=$val&nit_pro=$val2");
	
	}
 
if($boton=="Cuentas")
{
	//echo "ENTRA".$opc."<br>";
	
	popup("cuentas_pagar.php","Factura No. $val", "900px","650px");
};

if($boton=="Cuentas2")
{
	//echo "ENTRA".$opc."<br>";
	
	popup("cuentas_pagar.php?resumen=1","Factura No. $val", "900px","650px");
};

 
 
$sqlTotal = "SELECT COUNT(*) as total FROM fac_com WHERE cod_su='$codSuc' $UserFilter"; 
$rs = $linkPDO->query($sql ); 
$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 

	

	
if($boton=='Buscar' && isset($busq) && !empty($busq)){

$sql_busq="SELECT $columns FROM fac_com WHERE cod_su=$codSuc $UserFilter AND tipo_fac!='Traslado' $FILTRO_VENCIMIENTO $FILTRO_TIPO AND (num_fac_com LIKE '$busq%' OR serial_fac_com LIKE '$busq%' OR nom_pro LIKE '$busq%' OR nit_pro LIKE '$busq%' OR estado LIKE '$busq%') OR (select serial_art FROM art_fac_com WHERE art_fac_com.num_fac_com=fac_com.num_fac_com AND art_fac_com.nit_pro=fac_com.nit_pro AND art_fac_com.cod_su=fac_com.cod_su AND serial_art='$busq' LIMIT 1)='$busq';";



$rs=$linkPDO->query($sql_busq );

	
	}

 if($opc=="new_comp")
{
	//echo "ENTRA".$opc."<br>";
	//$_SESSION['n_fac_ven']=$num_fac;
	popup("comp_egreso.php","Comprobante de Ingreso", "600px","620px");
};
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<?php include_once("HEADER.php"); ?>	
<!-- <script src="JS/jquery-2.1.1.js"></script>-->
</head>

<body>
<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php include_once("menu_izq.php"); ?>
            <?php include_once("menu_top.php"); ?>
			<?php include_once("boton_menu.php"); ?>

<div class="uk-width-9-10 uk-container-center">












<center>

<!-- Lado Izquierdo del Navbar -->

<nav class="uk-navbar" > <a class="uk-navbar-brand uk-visible-large" href="centro.php"><img src="Imagenes/logoICO.ico" class="icono_ss"> &nbsp;SmartSelling</a> 
<!-- Centro del Navbar -->
<ul class="uk-navbar-nav " style=" ">  <!--  !!!!!!!    AUMENTAR ANCHO AL AGREGAR NUEVOS ELEMENTOS AL CENTRO, ES NECESARIO PARA QUE CENTRE ADECUADAMENTE !!!!!!!!! -->
		
		
			
<li class="ss-navbar-center"><a href="<?php echo $url_new ?>" ><i class="uk-icon-plus-square-o <?php echo $uikitIconSize ?>"></i>&nbsp;Compra</a></li>
<?php
if($MODULES["CUENTAS_PAGAR"]==1){
?>
<!--
<li class="ss-navbar-center"><a href="#"  onClick="print_pop('comp_egreso.php','EGRESO',600,650)"><i class="uk-icon-bank <?php echo $uikitIconSize ?>"></i>&nbsp;EGRESO</a></li>				
 	-->
			
<li class="uk-parent ss-navbar-center" data-uk-dropdown="{pos:'bottom-center',mode:'click'}" aria-haspopup="true" aria-expanded="false">
<a href="#" style="cursor:pointer;"><i class="uk-icon-file-text-o <?php echo $uikitIconSize ?>"></i> Informes</a>

<div class="uk-dropdown uk-dropdown-navbar uk-dropdown-bottom" style="top: 40px; left: 0px;">
<ul class="uk-nav uk-nav-navbar">
<li><a href="<?php  echo "$url?opc=Cuentas&pag=$pag" ?>" ><i class="uk-icon-file-text-o "></i>&nbsp;Cuentas por Pagar</a></li>
<li><a href="<?php  echo "$url?opc=Cuentas2&pag=$pag" ?>" ><i class="uk-icon-file-text-o "></i>&nbsp;Cuentas por Pagar RESUMEN</a></li>
</ul>

</div>
</li>
<?php
}
?>	
<li class="uk-parent ss-navbar-center" data-uk-dropdown="{pos:'bottom-center',mode:'click'}" aria-haspopup="true" aria-expanded="false">
<a href="#" style="cursor:pointer;"><i class="uk-icon-filter   <?php echo $uikitIconSize ?>"></i> Filtros</a>
<div class="uk-dropdown uk-dropdown-navbar uk-dropdown-bottom" style="top: 40px; left: 0px;">
<ul class="uk-nav uk-nav-navbar">
<li><a href="#filtros" data-uk-modal style="cursor:pointer;"><i class="uk-icon-filter -o"></i>&nbsp;Aplicar Filtros</a></li>
<li><a href="<?php echo "$url?opc=quitarFiltros" ?>"><i class="uk-icon-rotate-left -o"></i>&nbsp;QUITAR Filtros</a></li>
</ul>
</div>
</li>

<!--
<li><a   href="<?php echo $url ?>" ><i class="uk-icon-refresh uk-icon-spin <?php echo $uikitIconSize ?>"></i>&nbsp;Recargar P&aacute;g.</a> </li>

-->
</ul>
<!--<div class="uk-navbar-content">Some <a href="#">link</a>.</div>-->
									
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



</center>





















<h1 align="center">COMPRAS</h1>


<?php

if(!empty($FILTRO_TIPO) || !empty($FILTRO_VENCIMIENTO)){
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
<div id="filtros" class="uk-modal">
<div class="uk-modal-dialog">

<a class="uk-modal-close uk-close"></a>
<h1 style="color:#000">FILTROS FAC. COMPRA</h1>
<table width="100%">
</tr>

<tr>
<td colspan="2"><h2 style="color:#000">D&Iacute;AS RESTANTES VENCIMIENTO</h2></td>
</tr>
<tr>
<td colspan="2"><select name="filtroVenci">

<option value="">TODAS</option>
<option value="5">5 o menos</option>
<option value="10">10 o menos</option>
<option value="15">15</option>
<option value="20">20</option>
<option value="30">30</option>
<option value="45">45</option>
</select></td>
</tr>

<tr>
<td colspan="2"><h2 style="color:#000">TIPO DE FACTURA</h2></td>
</tr>
<tr>
<td colspan="2"><select name="FiltroTipoFac_compra">

<option value="">TODAS</option>
<option value="1">Compras</option>
<option value="2">Remisiones</option>
<option value="3">Traslados</option>
<option value="4">Inventario Inicial</option>
<option value="5">Corte Inventario</option>
<option value="6">Ajuste Seccion</option>
</select></td>



</tr>

<tr>
<td colspan="4" align="center"><input type="submit" value="Aplicar Filtros" name="filtro"  class="uk-button uk-button-large uk-button-primary uk-width-1-1"></td>
</tr>
</table>
    </div>
</div>
<div class="grid-20">
<table   cellpadding="0" cellspacing="1" align="" class="creditos_filter_table tabla-datos">
<thead>
<TR bgcolor="#CCCCCC" style="color:#000; font-size:24px;">
<TH colspan="5" align="center">Fecha </TH>
	<TH colspan="2" align="center">Nombre </TH>
    <TH colspan="2" align="center">Estado pago</TH>
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


<td><input type="text" name="<?php echo $nom_request ?>" value="<?php echo $nom ?>"  placeholder="Nombre Proveedor" id="<?php echo $nom_request ?>"></td>
<td><?php echo $botonFiltroNom ?></td>

<td>
				<select name="filtroB" onChange="submit()">
					<option value="TODOS" <?php if($filtroB=="TODOS")echo "selected" ?>>TODOS</option>
					<option value="Pendientes" <?php if($filtroB=="Pendientes")echo "selected" ?>>Pendientes</option>
					<option value="Pagados" <?php if($filtroB=="Pagados")echo "selected" ?>>Pagados</option>
                    <option value="Anuladas" <?php if($filtroB=="Anuladas")echo "selected" ?>>Anuladas</option>
					<option value="Morosos" <?php if($filtroB=="Morosos")echo "selected" ?>>MOROSOS</option>
				</select>
			</td>
</tr>
</tbody>
</table>
</div>


<?php include("PAGINACION.php"); ?>	
 
<?php //echo $sql;//echo "opc:".$_REQUEST['opc']."-----valor:".$_REQUEST['valor']; ?>
<div class="uk-overflow-containerZ">
<table border="0" align="center" claslpadding="6px" bgcolor="#000000"  class="uk-table uk-table-hover uk-table-striped tabla-datos" > 
 <thead>
      <tr   valign="top" class="uk-block uk-block-secondary uk-contrast"> 
      
<?php 


//echo $sql_busq;
echo $cols;   ?>

       </tr>
        
  </thead>
  <tbody>        
      
<?php 

while ($row = $rs->fetch()) 
{ 
$ii++;
		    
            $cod_fac = $row["num_fac_com"] ; 
            $nom_pro = $row["nom_pro"]; 
			$dir = $row["dir"];
			$tel = $row["tel"];
			$ciudad =$row["ciudad"];
			$nit_pro =$row["nit_pro"]; 
			$fecha = $row["fecha"];
			$tot =money($row["tot"]*1);
			$flete = $row["flete"]*1;
			 $fecha_creacion=$row['fecha_crea'];
			 $fecha_mod=$row['fecha_mod'];
			 $tipo_fac=$row['tipo_fac'];
			 $serialFac=$row['serial_fac_com'];
			 $estado=$row['estado'];
			 
			 $mora=$row['mora'];
			 $mora2=$row['mora2'];
			 $fecha_pago =$row["fecha_pago"];
			  $fecha_venci =$row["feVen"];
			 $pago=$row['pago'];
			 if($pago=="PAGADO")$mora=$mora2;
			 
			 $limAnulacion=$row["limAnula"];
			 
			 
			 
			 $saldo=saldo_compra($serialFac,$codSuc);
			 
			 $PAGO_STYLE="";
			 if($pago=="PAGADO")$PAGO_STYLE="style=\"color:#0C0; font-weight:bold;\"";
			 else $PAGO_STYLE="style=\"color:#F00; font-weight:bold;\"";
			 
			 if($tipo_fac!="Compra"){$PAGO_STYLE="style=\  font-weight:bold;\"";}
			
         ?>
 
<tr  bgcolor="#FFF" >
<th><?php echo $ii ?></th>
<td>


<div class="uk-button-dropdown"  data-uk-dropdown="{mode:'click'}" aria-haspopup="true" aria-expanded="false">
<a class="uk-button uk-button-primary" style="width:100px;font-size:16px;">Opciones <i class="uk-icon-caret-down"></i></a>
<div class="uk-dropdown uk-dropdown-small uk-dropdown-bottom" style="top: 30px; left: 0px;">
<ul class="uk-nav uk-nav-dropdown" style=" font-size:18px;">

<?php 
if($rolLv==$Adminlvl || val_secc($id_Usu,"compras_mod")){
	if($tipo_fac=='Importar BD' || $cod_fac=='1122334455-IMP'){
?>
<li>
<a href="<?php echo $url."?opc=Elimina Listado&numFac=$cod_fac&nit_fac=$nit_pro" ?>" class="uk-button-danger" >
<i class="uk-icon-eraser     uk-icon-small"></i> Eliminar listado de Inventario</a>
</li>
<?php
	}
?>
<li><a href="#" class="" onClick="print_pop('comp_egreso.php?facCom=<?php echo "$serialFac ";?>','EGRESO',600,650)">
<i class="uk-icon-dollar    uk-icon-small"></i> Pago/Abono</a></li>

<li>
<a href="<?php echo $url ?>?opc=mod&valor=<?php echo $cod_fac ?>&valor2=<?php echo $nit_pro ?>&valor3=<?php echo $tipo_fac ?>&fecha=<?php echo $estado ?>&pag=<?php echo $pag ?>" class="" >
<i class="uk-icon-edit     uk-icon-small"></i> Modificar</a>
</li>

<?php 
if( ($rolLv==$Adminlvl || val_secc($id_Usu,"fac_com_anula") ) && $tipo_fac!="Corte Inventario" && $tipo_fac!="Ajuste Seccion"){
	if($limAnulacion<$limite_anula_compras){
?>
<li><a href="#" class="" onMouseUp="anular_fac_com('<?php echo $cod_fac; ?>','<?php echo $nit_pro; ?>');"><i class="uk-icon-remove     uk-icon-small"></i> Anular Factura</a></li>

<?php 
	}
}
?>

<?php
}
?>

<li><a href="<?php echo $url ?>?opc=imp&valor=<?php echo $cod_fac ?>&valor2=<?php echo $nit_pro ?>&fecha=<?php echo $fecha ?>&pag=<?php echo $pag ?>" class="" ><i class="uk-icon-print     uk-icon-small"></i> Imprimir Factura</a></li>

</ul>
</div>
</div>




</td>     
<th><?php echo $serialFac ?></th>        
<td><?php echo $cod_fac; ?></td>
<td style=" font-weight:bold"><?php echo $nom_pro ?></td>
<td><?php echo $nit_pro ?></td> 
<!--<td><?php echo $ciudad ?></td>-->

<td><?php echo $tot ?></td>
<td><?php echo money($saldo) ?></td>
<td><b><?php if($estado=="CERRADA"){echo "<div class=\"   \">$estado</div>";}else if($estado!="ANULADA"){echo "<div class=\"  uk-badge uk-badge-notification\">$estado</div>";}else{echo "<div class=\"  uk-badge uk-badge-danger\">$estado</div>";} ?></b></td>
<td><?php echo $fecha ?></td>
<?php
if($MODULES["CUENTAS_PAGAR"]==1){

?>
<td class="uk-hidden2"><?php echo $fecha_venci ?></td>
<td <?php echo $PAGO_STYLE ?>><?php if($pago=="PENDIENTE" && $tipo_fac=="Compra"){echo "<div class=\"  uk-badge uk-badge-warning\">$pago</div>";}else if($tipo_fac=="Compra"){echo "<div class=\"  uk-badge uk-badge-success\">$pago</div>";}else {echo "<div class=\"  uk-badge uk-badge-success\">- - -</div>";} ?></td>
<td class="uk-visible-large"><?php echo $fecha_pago; ?></td>
<td class="uk-hidden"><?php echo $mora2; ?></td>
<?php }?>
<td><?php echo $tipo_fac; ?></td>
</tr> 
         
<?php 
         } 
      ?>
 

 
             <?php 
         $totalPag = ceil($total/$limit); 
         $links = array(); 
		 $sig=$pag+1;
		 if($sig>$totalPag)$sig=$pag;
		 $ant=$pag-1;
		 if($ant<1)$ant=$pag;
         for( $i=1; $i<=$totalPag ; $i++) 
         { 
            $links[] = "<a href=\"?pag=$i\">$i</a>";  
         } 
         //echo "<a href=\"?pag=1\">Inicio</a>-<a href=\"?pag=".$ant."\" data-icon=\"row-l\" data-role=\"button\">Ant</a>-".implode(" - ", $links)."-<a href=\"?pag=".$sig."\">Sig</a>-<a href=\"?pag=$totalPag\">Fin</a>"; 
      ?>
     </tbody>     
   
</table>

</div>

</form>
<?php include("PAGINACION.php"); ?>	


<?php include_once("FOOTER.php"); 
include('alertaPagoClienteSS.php');?>	
<?php include_once("autoCompletePack.php"); ?>	
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script type="text/javascript" language="javascript1.5" src="JS/TAC.js?<?php echo "$LAST_VER" ?>"></script>
<script type="text/javascript" language="javascript1.5" src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script>
<script type="text/javascript" language="javascript1.5">
ajax_load(350,'.grid-container');
$(document).ready( function() {

call_autocomplete('NOM',$('#<?php echo $nom_request ?>'),'ajax/busq_pro.php');
}
);
function cerrar_fc(num_fac,nit_pro)
{
	var DATOS='num_fac='+num_fac+'&nit='+nit_pro;
	if(confirm('Desea Cerrar esta  Factura de Compra?NO SE PERMITIRAN MODIFICACIONES'))
	{
		$.ajax({
			url:'ajax/cerrar_fc.php',
			data:{num_fac:num_fac,nit:nit_pro},
			type:'POST',
			dataType:'text',
			success:function(text){
				
				if(text!=0)
				{
					simplePopUp('FACTURA CERRADA');
					open_pop3('OPERACION EXITOSA','Factura de Compra No.'+num_fac+'','',0);
					waitAndReload();
					
					}
					else if(text==0){
						
						simplePopUp('La factura ya  esta CERRADA');
						open_pop3('OPERACION CANCELADA','La factura ya  esta CERRADA','',0);
						}
						
			},
			error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
			
			});
		
	}
	
};
function anular_fac_com(num_fac,nit_pro)
{
	var DATOS='num_fac='+num_fac+'&nit_pro='+nit_pro;
	if(!esVacio(nit_pro)&&!esVacio(num_fac)){
	if(confirm('Desea ANULAR Factura de Compra No.'+num_fac)){
		
	 $.ajax({
		url:'ajax/anula_com.php',
		data:{num_fac:$.trim(num_fac),nit_pro:$.trim(nit_pro)},
	    type: 'POST',
		dataType:'text',
		success:function(text){
		var resp=parseInt(text);
		var r=text.split('|');
		if(resp==0){open_pop3('OPERACION CANCELADA','Esta Factura YA esta Anulada!','',0);}
		else if(resp!=-2&&resp!=-1)
		{
			open_pop3('OPERACION EXITOSA','Fac. No.'+num_fac+' ANULADA','',0);
			waitAndReload();
		
		}
		else if(resp==-1){
			open_pop3('OPERACION CANCELADA','Esta Factura supera el limite de tiempo permitido(1 dia) para modificaciones, accion cancelada.... ','',0);
			}
		else if(resp==-2){
			open_pop3('OPERACION CANCELADA','Esta Factura esta ABIERTA, no se puede Anular','',0);
			}
				else if(resp==500){
			open_pop3('ERROR','Fallo la conexion, no se puede Anular','',0);
			}
		else {
			open_pop3('OPERACION CANCELADA','Factura No.'+num_fac+' NO encontrada','',0);
		}
		
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
	 });
	 
	}// if confirma
	
	}// if vacios
else {simplePopUp('Complete los espacios! No. Factura y PREFIJO(MTRH,RH,RAC,etc.)')}
	};

</script>
</body>
</html>