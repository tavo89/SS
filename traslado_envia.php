<?php 
require_once("Conexxx.php");
if($rolLv!=$Adminlvl && !val_secc($id_Usu,"compras")){header("location: centro.php");}
$tabla="fac_tras";



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
$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"Filtrar\" class=\"uk-button\">";
$A="";
if(isset($_REQUEST['fechaI'])){$fechaI=limpiarcampo($_REQUEST['fechaI']); $_SESSION[$PAG_fechaI]=$fechaI;}
if(isset($_REQUEST['fechaF'])){$fechaF=limpiarcampo($_REQUEST['fechaF']);$_SESSION[$PAG_fechaF]=$fechaF;}

if(isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI])){$fechaI=$_SESSION[$PAG_fechaI];}
if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF])){$fechaF=$_SESSION[$PAG_fechaF];$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"QUITAR\" class=\"uk-button uk-icon-undo\">";}

if(isset($_SESSION[$PAG_fechaF]) && !empty($_SESSION[$PAG_fechaF]) && isset($_SESSION[$PAG_fechaI]) && !empty($_SESSION[$PAG_fechaI]))
{
	$A=" AND (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF') ";
}





if($opc=="QUITAR")
{
	$botonFiltro="<input type=\"submit\" name=\"opc\" value=\"Filtrar\" class=\"uk-button\" >";
	$fechaI="";
	$fechaF="";
	unset($_SESSION[$PAG_fechaI]);
	unset($_SESSION[$PAG_fechaF]);
	$A="";
}
//-----------------------------------------------------------------------------------------------------------------------------------------------------

$cols="<th width=\"90px\">#</th>
<td></td>
<th width=\"100\">Cod.</th>
<th width=\"200\">Sede Destino</th>
<th width=\"100\">Ciudad</th>

<th width=\"50\">TOT</th>

<th width=\"150\">Estado</th>
<th width=\"150\">Fecha Envia</th>
<th width=\"150\">Fecha Recibe</th>

";



$col_id="num_fac_com";
$columns="num_fac_com,nit_pro,nom_pro,fecha,tel,dir,ciudad,flete,cod_su,tot,DATE(fecha_crea) as fecha_crea,fecha_mod,tipo_fac,serial_fac_com,estado,pago,fecha_pago,fecha_recibe,sede_origen,sede_destino";
$url="compras.php";
$url_dialog="dialog_invIni.php";
$url_mod="mod_fac_tras.php";
$url_new="fac_tras.php";
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

$sql = "SELECT  $columns FROM $tabla  WHERE  cod_su=$codSuc $FILTRO_VENCIMIENTO $FILTRO_TIPO  $A ORDER BY serial_fac_com DESC   LIMIT $offset, $limit"; 



if($boton=='mod'&& !empty($val)){
	if($fe!="CERRADA" || $rolLv>=$Adminlvl){
	$_SESSION['num_fac']=$val;
	$_SESSION['nit_pro']=$val2;
	$_SESSION['pag']=$pag;
	header("location: $url_mod");
	}
	else eco_alert("Esta Factura ya esta Cerrada, Ya no se permite hacer Modificaciones!");
	}

if($boton=='imp'&& !empty($val)){
	
	$_SESSION['num_fac']=$val;
	$_SESSION['nit_pro']=$val2;
	$_SESSION['pag']=$pag;
	header("location: imp_fac_com.php");
	
	}
 
if($boton=="Cuentas")
{
	//echo "ENTRA".$opc."<br>";
	
	popup("cuentas_pagar.php","Factura No. $val", "900px","650px");
};

 
 
$sqlTotal = "SELECT COUNT(*) as total FROM $tabla WHERE nit=$codSuc"; 
$rs = $linkPDO->query($sql ); 
$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 

	
if($boton=='Buscar' && isset($busq) && !empty($busq)){

$sql_busq="SELECT $columns FROM fac_com WHERE cod_su=$codSuc $FILTRO_VENCIMIENTO $FILTRO_TIPO $A AND(num_fac_com LIKE '$busq%' OR serial_fac_com LIKE '$busq%' OR nom_pro LIKE '$busq%' OR nit_pro LIKE '$busq%' OR estado LIKE '$busq%')";



$rs=$linkPDO->query($sql_busq );

	
	}
if($boton=='Buscar' && isset($busq) && !empty($busq)){

$sql_busq="SELECT $columns FROM fac_com WHERE cod_su=$codSuc $FILTRO_VENCIMIENTO $FILTRO_TIPO AND (num_fac_com LIKE '$busq%' OR serial_fac_com LIKE '$busq%' OR nom_pro LIKE '$busq%' OR nit_pro LIKE '$busq%' OR estado LIKE '$busq%');";



$rs=$linkPDO->query($sql_busq );

	
	}

 if($opc=="new_comp")
{
	//echo "ENTRA".$opc."<br>";
	//$_SESSION['n_fac_ven']=$num_fac;
	popup("comp_egreso.php","Comprobante de Ingreso", "1200px","500px");
};
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php require_once("HEADER.php"); ?>	
<script src="JS/jquery-2.1.1.js"></script>
</head>

<body>
<div class="container ">
<!-- Push Wrapper -->
<div class="mp-pusher" id="mp-pusher">
            <?php require_once("menu_izq.php"); ?>
            <?php require_once("menu_top.php"); ?>
			<?php require_once("boton_menu.php"); ?>

<div class="uk-width-9-10 uk-container-center">


<nav class="uk-navbar">
<ul class="uk-navbar-nav">
<li><a href="<?php echo $url_new ?>" ><i class="uk-icon-plus-square <?php echo $uikitIconSize ?>"></i>&nbsp;Nueva Factura</a></li>
<li><a href="<?php echo $url ?>?opc=new_comp&valor=<?php echo 0 ?>&pag=<?php echo $pag ?>" ><i class="uk-icon-plus-square-o <?php echo $uikitIconSize ?>"></i>&nbsp;Crear Comprobante</a></li>

<li><a href="lista_comp_egreso.php" ><i class="uk-icon-list <?php echo $uikitIconSize ?>"></i>&nbsp;Lista Comprobantes</a></li>

<li><a href="#filtros" data-uk-modal><i class="uk-icon-filter   <?php echo $uikitIconSize ?>"></i>&nbsp;Filtros</a></li>
<li><a href="<?php echo "$url?opc=quitarFiltros" ?>"><i class="uk-icon-rotate-left <?php echo $uikitIconSize ?>"></i>&nbsp;QUITAR Filtros</a></li>

<li><a href="<?php  echo "$url?opc=Cuentas&pag=$pag" ?>" ><i class="uk-icon-file-text-o <?php echo $uikitIconSize ?>"></i>&nbsp;Estados de Cuenta</a></li>

<li><a href="<?php echo $url ?>" ><i class="uk-icon-refresh uk-icon-spin <?php echo $uikitIconSize ?>"></i>&nbsp;Recargar P&aacute;g.</a></li>
</ul>
</nav>
<h1 align="center">TRASLADOS ENVIADOS</h1>

<div id="sb-search" class="sb-search">
						<form>
							<input class="sb-search-input" placeholder="Ingrese su b&uacute;squeda..." type="text" value="" name="busq" id="search">
							<input class="sb-search-submit" type="submit" value="Buscar" name="opc">
							<span class="sb-icon-search"></span>
						</form>
					</div>
 <br><br><br><br><br>
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
<form action="<?php echo $url ?>" method="post" name="form">
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
</select></td>
</tr>

<tr>
<td colspan="4" align="center"><input type="submit" value="Aplicar Filtros" name="filtro"  class="uk-button uk-button-large uk-button-primary uk-width-1-1"></td>
</tr>
</table>
    </div>
</div>
<div class="grid-20">
<table   cellpadding="0" cellspacing="1" align="" >
<thead>
<TR bgcolor="#CCCCCC">
<TH colspan="5" align="center">FECHA </TH>
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
</div>

<br><br><br><br>
<?php require("PAGINACION.php"); ?>	
<?php //echo $sql;//echo "opc:".$_REQUEST['opc']."-----valor:".$_REQUEST['valor']; ?>
<table border="0" align="center" claslpadding="6px" bgcolor="#000000"  class="uk-table uk-table-hover uk-table-striped"> 
 <thead>
      <tr bgcolor="#595959" style="color:#FFF" valign="top"> 
      
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
			
		
			 
			 $PAGO_STYLE="";
			 if($estado=="RECIBIDO")$PAGO_STYLE="style=\"color:#0C0; font-weight:bold;\"";
			 else $PAGO_STYLE="style=\"color:#F00; font-weight:bold;\"";
			
         ?>
 
<tr  bgcolor="#FFF" style="font-size:12px">
<th><?php echo $ii ?></th>
<td>
<table cellpadding="0" cellspacing="0">
<tr>
<?php 
if($rolLv==$Adminlvl || val_secc($id_Usu,"compras_mod")){
?>
<td>
<a href="<?php echo $url ?>?opc=mod&valor=<?php echo $cod_fac ?>&valor2=<?php echo $nit_pro ?>&valor3=<?php echo $tipo_fac ?>&fecha=<?php echo $estado ?>&pag=<?php echo $pag ?>" data-ajax="false" data-role="button" data-inline="true" data-mini="true" data-uk-tooltip title="Modificar Fac.">

<img src="Imagenes/b_edit.png">
</a>
</td>

<td>


<img src="Imagenes/confirm.png" data-uk-tooltip title="Cerrar Fac." width="25" height="25" onClick="cerrar_fc('<?php echo $cod_fac ?>','<?php echo $nit_pro ?>')">

</td>

<?php
}
?>
</tr>
<tr>
<td>
<a href="<?php echo $url ?>?opc=imp&valor=<?php echo $cod_fac ?>&valor2=<?php echo $nit_pro ?>&fecha=<?php echo $fecha ?>&pag=<?php echo $pag ?>" data-ajax="false" data-role="button" data-inline="true" data-mini="true" data-uk-tooltip title="Imprimir Fac.">

<img src="Imagenes/printer.png" width="18" height="18">
</a>
</td>
<?php 
if($rolLv==$Adminlvl || val_secc($id_Usu,"fac_com_anula")){
?>


<td>
<a href="#" data-role="button" data-inline="true" data-mini="true" onMouseUp="anular_fac_com('<?php echo $cod_fac; ?>','<?php echo $nit_pro; ?>');" data-uk-tooltip title="ANULAR FAC.">
<img src="Imagenes/cancel (1).png" width="20" height="20">
</a>
</td>

<?php 
}
?>

</tr>
</table>


</td>     
<th><?php echo $serialFac ?></th>        

<td style="font-size:10px; font-weight:bold"><?php echo $nom_pro ?></td>
<td><?php echo $nit_pro ?></td> 
<!--<td><?php echo $ciudad ?></td>-->

<td><?php echo $tot ?></td>
<td><b><?php echo $estado ?></b></td>
<td><?php echo $fecha ?></td>
<td><?php echo $fecha_recibe ?></td>
<td <?php echo $PAGO_STYLE ?>><?php echo $estado ?></td>
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



</form>
<?php require("PAGINACION.php"); ?>	


<?php require_once("FOOTER.php"); ?>	
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script> 
<script type="text/javascript" language="javascript1.5" src="JS/TAC.js?<?php echo "$LAST_VER" ?>"></script>
<script type="text/javascript" language="javascript1.5" src="JS/utiles.js?<?php echo "$LAST_VER" ?>"></script>
<script type="text/javascript" language="javascript1.5">
ajax_load(350,'.grid-container');
function cerrar_fc(num_fac,nit_pro)
{
	var DATOS='num_fac='+num_fac+'&nit='+nit_pro;
	//alert(DATOS);
	if(confirm('Desea Cerrar esta  Factura de Compra?NO SE PERMITIRAN MODIFICACIONES'))
	{

	
	$.ajax({
		url:'ajax/cerrar_fc.php',
		data:{num_fac:num_fac,nit:nit_pro},
		type:'POST',
		dataType:'text',
		success:function(text){
			
			//alert(text);
			//$('<p>'+text+'</p>').appendTo('#salida');
			if(text!=0)
			{
				simplePopUp('FACTURA CERRADA');
				//simplePopUp(text);
				open_pop2('OPERACION EXITOSA','Factura de Compra No.'+num_fac+'','',0);
				waitAndReload();
				
				}
				else if(text==0){
					
					simplePopUp('La factura ya  esta CERRADA');
					open_pop2('OPERACION CANCELADA','La factura ya  esta CERRADA','',0);
					}
					
			//else simplePopUp('Actualizado');
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
		
		});
		
	}
	
};
function anular_fac_com(num_fac,nit_pro)
{
	//alert(num_fac);
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
		
		if(resp==0){open_pop2('OPERACION CANCELADA','Esta Factura YA esta Anulada!','',0);}
		else if(resp!=-2&&resp!=-1)
		{
			open_pop2('OPERACION EXITOSA','Fac. No.'+num_fac+' ANULADA','',0);
			waitAndReload();
		
		}
		else if(resp==-1){
			open_pop2('OPERACION CANCELADA','Esta Factura supera el limite de tiempo permitido(1 dia) para modificaciones, accion cancelada.... ','',0);
			}
		else if(resp==-2){
			open_pop2('OPERACION CANCELADA','Esta Factura esta ABIERTA, no se puede Anular','',0);
			}
		else {
			open_pop2('OPERACION CANCELADA','Factura No.'+num_fac+' NO encontrada','',0);
		}
		
		},
		error:function(xhr,status){warrn_pop('La conexion Falló, SU INFORMACIÓN NO SE GUARDÓ');playAlert('alertAudio');}
	 });
	 
	}// if confirma
	
	}// if vacios
else {alert('Complete los espacios! No. Factura y PREFIJO(MTRH,RH,RAC,etc.)')}
	};

</script>
</body>
</html>