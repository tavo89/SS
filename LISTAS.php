<?php
require_once("Conexxx.php");
if($rolLv!=$Adminlvl && !val_secc($id_Usu,"informes_ventas")){header("location: centro.php");}
$boton="";
$num_fac="";
$fechaI="";
$fechaF="";
$cli="";
$pro="";
$dcto="";
$city="";




$tipo1="";
$orden="";
$fabs="";
$clase="";
$nom_cli=r("nom_cli");

if(isset($_REQUEST['fechaI']))$fechaI=$_REQUEST['fechaI'];
if(isset($_REQUEST['fechaF']))$fechaF=$_REQUEST['fechaF'];
if(isset($_REQUEST['nom_cli']))$cli=$_REQUEST['nom_cli'];
if(isset($_REQUEST['dcto']))$dcto=$_REQUEST['dcto'];
if(isset($_REQUEST['tipo_1']))$tipo1=$_REQUEST['tipo_1'];
if(isset($_REQUEST['ciudad']))$city=$_REQUEST['ciudad'];
if(isset($_REQUEST['fab']))$pro=$_REQUEST['fab'];
if(isset($_REQUEST['boton'])){$boton=$_REQUEST['boton'];}
if(isset($_REQUEST['orden']))$orden=$_REQUEST['orden'];
if(isset($_REQUEST['clases']))$clase=$_REQUEST['clases'];
if(isset($_REQUEST['fabs']))$fabs=$_REQUEST['fabs'];

if(empty($nom_cli)){unset($_SESSION['nom_cli_inf2']);}
if(empty($cli)){unset($_SESSION['cli']);}


if(empty($clase))unset($_SESSION['clases']);
if(empty($fabs))unset($_SESSION['fabs']);
if(empty($pro))unset($_SESSION['pro']);				


$formaPago=r("formaPago");
if(empty($formaPago))unset($_SESSION['forma_pago_informes']);	

if($boton=='Totales ventas por vendedor-Creditos'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	$_SESSION['clases']=$clase;
	$_SESSION['fabs']=$fabs;
	$_SESSION['forma_pago_informes']=$formaPago;
	popup("informe_nomina_ventas_credito.php","LISTADO DE CLIENTES","850px","500px");		
	
}




if($boton=='Totales ventas por vendedor'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	$_SESSION['clases']=$clase;
	$_SESSION['fabs']=$fabs;
	$_SESSION['forma_pago_informes']=$formaPago;
	popup("total_ventas_vendedores.php","LISTADO DE CLIENTES","850px","500px");		
	
}



if($boton=='Informe Fac. x Producto Detallado')
{

	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	
	$_SESSION['cli']=$cli;
	$_SESSION['ciudad']=$city;
	$_SESSION['dcto']=$dcto;
	$_SESSION['pro']=$pro;
	$_SESSION['orden_ven']=$orden;
	$_SESSION['fe_crea']=0;
	$_SESSION['forma_pago_informes']=$formaPago;
	
	popup("informe_ventas_clientes.php","LISTADO DE CLIENTES","850px","500px");
	
	/*
	if($tipo1=="det")popup("informe_ventas_clientes.php","LISTADO DE CLIENTES","850px","500px");
	else popup("informe_ventas_resumen_pro.php","VENTAS X PRODUCTO","850px","500px");
	*/

};
if($boton=='Generar Informe Fac')
{

	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	
	$_SESSION['cli']=$cli;
	$_SESSION['ciudad']=$city;
	$_SESSION['dcto']=$dcto;
	$_SESSION['pro']=$pro;
	$_SESSION['orden_ven']=$orden;
	$_SESSION['fe_crea']=0;
	$_SESSION['forma_pago_informes']=$formaPago;
	popup("informe_ventas_clientes3.php","LISTADO DE CLIENTES","850px","500px");
	
};

if($boton=='Generar Informe notas')
{

	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	
	$_SESSION['cli']=$cli;
	$_SESSION['ciudad']=$city;
	$_SESSION['dcto']=$dcto;
	$_SESSION['pro']=$pro;
	$_SESSION['orden_ven']=$orden;
	$_SESSION['fe_crea']=0;
	$_SESSION['forma_pago_informes']=$formaPago;
	popup("informe_ventas_clientes3.php?t=1","LISTADO DE CLIENTES","850px","500px");
	
};
if($boton=='Ventas Por Producto' )
{

	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	$_SESSION['clases']=$clase;
	$_SESSION['fabs']=$fabs;
	$_SESSION['nom_cli_inf2']=$nom_cli;
	$_SESSION['cli']=$nom_cli;
	$_SESSION['ciudad']=$city;
	$_SESSION['dcto']=$dcto;
	$_SESSION['pro']=$pro;
	$_SESSION['orden_ven']=$orden;
	$_SESSION['fe_crea']=0;
	
	$_SESSION['forma_pago_informes']=$formaPago;

 	popup("informe_ventas_resumen_pro.php","VENTAS X PRODUCTO","850px","500px");

}
 if($boton=='Ventas Por Producto 2')
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	
	$_SESSION['cli']=$nom_cli;
	$_SESSION['ciudad']=$city;
	$_SESSION['dcto']=$dcto;
	$_SESSION['pro']=$pro;
	$_SESSION['orden_ven']=$orden;
	$_SESSION['fe_crea']=0;
	$_SESSION['nom_cli_inf2']=$nom_cli;
	
	$_SESSION['forma_pago_informes']=$formaPago;

 	popup("informe_ventas_producto_cliente.php","VENTAS X PRODUCTO","850px","500px");
	
	
}

if($boton=='Generar Informe RESUMEN')
{

	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	
	$_SESSION['orden_ven']=$orden;
	$_SESSION['cli']=$cli;
	$_SESSION['ciudad']=$city;
	$_SESSION['dcto']=$dcto;
	$_SESSION['pro']=$pro;
	$_SESSION['fe_crea']=0;
	
	$_SESSION['forma_pago_informes']=$formaPago;
	if(!empty($pro))popup("informe_ventas_clientes2B.php","LISTADO DE CLIENTES","850px","500px");
	else if($tipo1=="det")popup("informe_ventas_clientes2.php","LISTADO DE CLIENTES","850px","500px");
	else if($tipo1=="res") popup("informe_ventas_resumen_pro.php","VENTAS X PRODUCTO","850px","500px");
	else popup("informe_ventas_clientes2.php","LISTADO DE CLIENTES","850px","500px");
	

};

if($boton=='Lista de Clientes')
{
	$_SESSION['dcto']=$dcto;
	$_SESSION['fe_crea']=0;
	
	popup("informe_clientes.php","LISTADO DE CLIENTES","850px","500px");
};

if($boton=='Clientes Nuevos'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	$_SESSION['fe_crea']=1;
	$_SESSION['forma_pago_informes']=$formaPago;
	popup("informe_clientes_registrados.php","","900px","700px");	
};
if($boton=='Ventas Clientes Nuevos')
{

	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	
	$_SESSION['cli']=$cli;
	$_SESSION['ciudad']=$city;
	$_SESSION['dcto']=$dcto;
	$_SESSION['pro']=$pro;
	$_SESSION['orden_ven']=$orden;
	$_SESSION['fe_crea']=1;
	$_SESSION['forma_pago_informes']=$formaPago;
	if($tipo1=="det")popup("informe_ventas_clientes.php","LISTADO DE CLIENTES","850px","500px");
	else popup("informe_ventas_clientes2B.php","VENTAS X PRODUCTO","850px","500px");

};

if($boton=='ventas tecnicos'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	$_SESSION['clases']=$clase;
	$_SESSION['fabs']=$fabs;
	$_SESSION['forma_pago_informes']=$formaPago;
	popup("informe_ventas_tecnicos.php","LISTADO DE CLIENTES","850px","500px");		
	
}
if($boton=='ventas tecnicos2'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	$_SESSION['clases']=$clase;
	$_SESSION['fabs']=$fabs;
	$_SESSION['forma_pago_informes']=$formaPago;
	popup("ventas_tecnicos_detallada.php","LISTADO DE CLIENTES","850px","500px");		
	
}

if($boton=='Ventas x Comision'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	$_SESSION['clases']=$clase;
	$_SESSION['fabs']=$fabs;
	$_SESSION['nom_cli_inf2']=$nom_cli;
	$_SESSION['forma_pago_informes']=$formaPago;
	popup("informe_comision_ventas.php","VENTAS X COMISION","850px","500px");		
	
}
if($boton=='Ventas x Cliente x Producto'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	$_SESSION['clases']=$clase;
	$_SESSION['fabs']=$fabs;
	$_SESSION['nom_cli_inf2']=$nom_cli;
	$_SESSION['forma_pago_informes']=$formaPago;
	popup("venta_x_cli_x_producto.php","VENTAS X COMISION","850px","500px");		
	
}

if($boton=='Relacion Gastos'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	popup("informe_egresos_resumen.php","Informe Facturas Venta Anuladas","900px","600px");
};

if($boton=='RESUMEN Gastos'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	popup("informe_egresos_resumen.php?TIPO_INF=2","Informe Facturas Venta Anuladas","900px","600px");
};

if($boton=='RELACION GASTOS'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	popup("resumen_gastos_2.php?TIPO_INF=2","Informe Facturas Venta Anuladas","900px","600px");
};

if($boton=='RESUMEN EGRESOS'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	popup("RESUMEN_GASTOS.php?TIPO_INF=2","Informe Facturas Venta Anuladas","900px","600px");
};


if($boton=='RELACION INGRESOS'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	popup("RELACION_INGRESOS.php?TIPO_INF=2","Informe Facturas Venta Anuladas","900px","600px");
};

?>
<!DOCTYPE html>
<html>
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
	<?php require_once("sub_menu_informes.php"); ?>

	<div class=" grid-100 posicion_form">
	<h1 align="center">INFORMES </h1>
	<?php //echo "boton:".$boton." ; dep: ".$dep."<br>INSERT INTO departamento (departamento) VALUES ('$dep')"; ?>
	
	
	
	
	
	<!-- Informes Layout -->
	<center>
	
	<form  method="post" name="anular" id="form_fac" class="uk-form">
			<div id="query"></div>
			
		<div style="background:rgba(200,200,200,0);"> <!-- Left -->
				
				
				
				<br>
				
				<div class="ms_panels" id="botones_gen_informe">
					<h2>Generar Informe de:</h2>
					
					<input type="hidden" id="boton_sbmt_info" name="boton" value="">
					
					
				<!--	<button class="uk-button" onClick="document.getElementById('boton_sbmt_info').value='Generar Informe'; submit();">DETALLADO Ventas por Cliente</button> -->
                
                <?php
                if(($rolLv==$Adminlvl ||  val_secc($id_Usu,"informes_ventas")) && $codSuc>0){
				?>
                
                 <div class="uk-panel uk-panel-box uk-panel-box-primary">
   				<div class="uk-panel-badge uk-badge">Hot</div>
    			<h3 class="uk-panel-title">Clientes</h3>

<button class="uk-button uk-button-large" onClick="document.getElementById('boton_sbmt_info').value='Lista de Clientes'; submit();">Lista de Clientes</button>
 
                </div>
                
                 
                 
                            
                            
                            <?php }?>
                            
                            
                        
   
					
					
				</div>	
						

						<!--
												<div class="ms_panels">
												<h2  align="center">Generar Informe de Clientes Con Descuentos</h2> <br>

												
												<label>Descuento:</label><input type="text" value="" name="dcto" id="dcto" placeholder="Porcentaje %">
												<input  type="submit" value="Lista de Clientes" name="boton" class="button" />
												

												
												<input  type="submit" value="Compras Almacen" name="boton" class="button"  />

												<input  type="submit" value="Totales ventas por vendedor" name="boton" class="button"  />
												<input  type="submit" value="Total Ventas" name="boton" class="button" />
												
												
												</div>
												
						-->
		</div>
			
	</form>
	</center>
	<!-- Final Informes Layout -->
	
	
		<?php require_once("FOOTER.php"); ?>	
		<?php require_once("autoCompletePack.php"); ?>	
		<script type="text/javascript" language="javascript1.5" src="JS/TAC.js"></script>
		<script language="javascript1.5" type="text/javascript" src="JS/jquery.multi-select.js"></script>
        <script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
		<script type="text/javascript" language="javascript1.5">

		/*$(document).bind("mobileinit", function(){ 
			$('#page').on('pageinit', function() { 
				simplePopUp('Welcome'); 
			}); 
		});
		*/
		//$(document).bind("pagebeforeshow", function() {
		$(document).ready( function() {

			call_autocomplete('ID',$('#nom_cli'),'ajax/busq_cli.php');

			
		});

		function pagar(url,pre,fac)
		{
			if(confirm('Desea pagar Factura:'+pre+'-'+fac+' ??'))
			{
				location.assign(url);
			}
			else
			{
				simplePopUp('Operacion Cancelada');
			}
		};

		</script>
	</body>
</html>