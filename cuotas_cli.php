<?php 
require_once("Conexxx.php");
valida_session();

$num_fac=$_SESSION['num_fac_cuota'];
$pre=$_SESSION['prefijo'];
$tot_cuotas=0;
$val_cre=0;
$tot_fac=0;

$sql="SELECT p.id_cli,(tot-r_fte-r_ica-r_iva) TOT FROM fac_venta  p  WHERE  p.prefijo='$pre' AND p.num_fac_ven=$num_fac AND nit='$codSuc'";	//echo "<br><b>$sql</b>";
	$rs=$linkPDO->query($sql);
	
	if($row=$rs->fetch())
	{
	   
		$ID_CLI=$row["id_cli"];
		$tot_fac=$row["TOT"]*1;
	}
$sql="SELECT SUM(a.abono) as tot,a.pre,a.num_fac FROM cartera_mult_pago a LEFT JOIN (SELECT cod_su,fecha,num_com,cajero,valor,num_fac,pre,anulado FROM comprobante_ingreso WHERE cod_su='$codSuc'  ) b ON a.num_comp=b.num_com WHERE  estado!='ANULADO' AND a.cod_su=b.cod_su AND a.cod_su='$codSuc' AND (a.num_fac='$num_fac' AND a.pre='$pre')";	
//echo "<br><b>$sql</b>";
$rs=$linkPDO->query($sql);
	
if($row=$rs->fetch())
{$tot_cuotas=$row['tot'];}

$busq="";
$val="";
$val2="";
$boton="";
$opc="";
if(isset($_REQUEST['opc'])){$opc=$_REQUEST['opc'];}
if(isset($_REQUEST['busq']))$busq=$_REQUEST['busq'];
if(isset($_REQUEST['valor']))$val= $_REQUEST['valor'];
if(isset($_REQUEST['valor2']))$val2= $_REQUEST['valor2'];
if(isset($_REQUEST['opc']))$boton= $_REQUEST['opc'];



$cols="<th width=\"90px\">#</th>
<td></td>
<th width=\"250\">No. Comprobante</th>
<th width=\"200\">Valor Pagado</th>
<th width=\"200\">Fecha Comprobante</th>
<th width=\"200\">Fecha Cuota</th>
<th width=\"200\">Anulado</th>
";

$saldo=$tot_fac-$tot_cuotas;
//echo "<li>saldo $saldo</li>";
if($saldo<0)$saldo=0;
$tabla="comprobante_ingreso";
$col_id="id_pro";
$columns="num_com,num_fac,cod_su,fecha,fecha_cuota,valor,anulado";
$columns="b.cajero, b.num_com,b.valor,a.abono,DATE(b.fecha) as fecha2,b.anulado,fecha_cuota";
$url="cuotas_cli.php";
$url_dialog="dialog_invIni.php";
$url_mod="modificar_inv.php";
$url_new="comp_ingreso.php";
$pag="";
$limit = 20; 
$order="fecha";
 
if(isset($_REQUEST["pag"])){$pag = (int) $_REQUEST["pag"];}
if ($pag < 1) 
{ 
   $pag = 1; 
} 
$offset = ($pag-1) * $limit; 
$ii=$offset;
 
$sql="SELECT  $columns  FROM cartera_mult_pago a LEFT JOIN comprobante_ingreso b ON a.num_comp=b.num_com WHERE ((a.num_fac='$num_fac' AND a.pre='$pre') OR (b.num_fac='$num_fac' AND b.pre='$pre')) AND b.anulado!='ANULADO' AND a.cod_su=b.cod_su AND a.cod_su='$codSuc' ORDER BY b.fecha LIMIT $offset, $limit";

//$sql = "SELECT  $columns FROM comprobante_ingreso WHERE num_fac=$num_fac AND cod_su=$codSuc AND pre='$pre'  LIMIT $offset, $limit"; 
 //echo $sql;
$sqlTotal = "SELECT COUNT(*) as total FROM cartera_mult_pago "; 
$rs = $linkPDO->query($sql ); 
$rsTotal = $linkPDO->query($sqlTotal); 
$rowTotal = $rsTotal->fetch(); 
$total = $rowTotal["total"]; 


	

 
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<?php require_once("HEADER.php"); ?>
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

		<?php 
		if(($rolLv==$Adminlvl || val_secc($id_Usu,"crea_recibo_caja")) && $codSuc>0){
		?>
		<li><a class="ss-navbar-center" href="<?php echo "$url?valor=$ID_CLI&pre=$pre&opc=new_comp&valor2=$num_fac&valor3=$tot_fac" ?>&pag=<?php echo $pag ?>" ><i class="uk-icon-plus-square "></i>&nbsp;Crear Comprobante</a></li>
		<?php 
		}
		?>
		<li><a href="creditos_parti.php"><i class="uk-icon-list"></i>&nbsp;Creditos - P&uacute;blico</a>
		</li>
        <!--<li><a href="<?php echo $url."?opc=ver_plan" ?>"><i class="uk-icon-file-text-o"></i>&nbsp;Ver Plan</a></li>
		<li><a href="<?php echo $url."?opc=ver_fac" ?>"><i class="uk-icon-file-text-o"></i>&nbsp;Ver Factura</a></li>-->
		<li><a href="<?php echo $url ?>" ><i class="uk-icon-refresh uk-icon-spin"></i>&nbsp;Recargar P&aacute;g.</a></li>

	</ul>

</nav>


<?php //echo $opc."<br>"; 
if($opc=="ver_fac")
{
	//echo "ENTRA".$opc."<br>";
	$_SESSION['n_fac_ven']=$num_fac;
	$_SESSION['prefijo']=$pre;
	popup("imp_fac_ven.php","Factura No. $num_fac", "900px","600px");
};

if(($rolLv==$Adminlvl || val_secc($id_Usu,"creditos_publico")) && $codSuc>0){
if($opc=="new_comp")
{
	//echo "ENTRA".$opc."<br>";
	//$_SESSION['n_fac_ven']=$num_fac;
	popup("comp_ingreso.php?cc=$val&nf=$val2&pre=$pre&tot_fac=$tot_fac&saldo=$saldo","Comprobante de Ingreso", "900px","600px");
};
};
if($opc=="ver_plan")
{
	//echo "ENTRA".$opc."<br>";
	//$_SESSION['n_fac_ven']=$num_fac;
	$_SESSION['cod_plan']=$num_fac;
	$_SESSION['prefijo']=$pre;
	popup("imp_plan_amor.php","Comprobante de Ingreso", "900px","600px");
};

if($opc=="imp_comp")
{
	
/*$_SESSION['num_fac_ven']=$num_fac;
$_SESSION['pre']=$pre;
$_SESSION['num_comp_ingre']=$val;*/
//imp_a("num_comp_ingre",$val,"imp_comp_ingre.php","Comprobante de Ingreso No. $val","800px","600px");

imp_comp_ingre($num_fac,$pre,$val);
};
?>

<h1 align="center">Cuotas Factura No.<?php echo $num_fac ?></h1>

		<!--			<div id="sb-search" class="sb-search">
						<form class="uk-form ms_panels">
							<input class="sb-search-input" placeholder="Ingrese su b&uacute;squeda..." type="text" value="" name="busq" id="search">
							<input class="sb-search-submit" type="submit" value="Buscar" name="opc">
							<span class="sb-icon-search"></span>
						</form>
					</div>
-->

<form action="<?php echo $url ?>" method="post" name="form" class="uk-form">


<?php require("PAGINACION.php"); ?>	
<table border="0" align="center" claslpadding="6px" bgcolor="#000000" class="uk-table uk-table-hover uk-table-striped" > 
 <thead>
      <tr bgcolor="#595959" style="color:#FFF" valign="top"> 
      
<?php echo $cols;   ?>

       </tr>
        
</thead>
<tbody>          
      
<?php 

while ($row = $rs->fetch()) 
{ 
$ii++;
		    
            $num_comp =$row["num_com"];
			$fecha_comp=$row["fecha2"];
			$fecha_cuotas=$row["fecha_cuota"]; 
			$valor=money($row['abono']);
			$anulado=$row["anulado"];
			 
			
         ?>
 
<tr  bgcolor="#FFF">
<th><?php echo $ii ?></th>
<td>
<table cellpadding="0" cellspacing="0">
<tr>
<td>
<a href="<?php echo $url ?>?opc=imp_comp&valor=<?php echo $num_comp ?>&pag=<?php echo $pag ?>" data-ajax="false" data-role="button" data-inline="true" data-mini="true">

<img src="Imagenes/printer.png" width="26" height="26">
</a>
</td>
<?php 
if($rolLv==$Adminlvl || val_secc($id_Usu,"anular_comp_ingreso")){
?>
<td>
<a href="#"  onMouseUp="anular_comp('<?php echo $num_comp; ?>');">
<img src="Imagenes/cancel (1).png" width="20" height="20">
</a>
</td>
<?php 
}
?>

</tr>
</table>


</td>             
<td><?php echo $num_comp; ?></td>
<td><?php echo $valor; ?></td>
<td><?php echo $fecha_comp; ?></td> 
<td><?php echo $fecha_cuotas; ?></td>
<td><?php echo $anulado; ?></td>
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
<script type="text/javascript" language="javascript1.5">
function anular_comp(num_comp)
{
	if(!esVacio(num_comp)){
	if(confirm('Desea ANULAR Comprobante Ingreso No.'+num_comp)){
	 $.ajax({
		url:'ajax/anula_comp_ingreso.php',
		data:{num_comp:$.trim(num_comp)},
	    type: 'POST',
		dataType:'text',
		success:function(text){
		var resp=parseInt(text);
		var r=text.split('|');
		if(resp==0)simplePopUp('Este Comprobante YA esta Anulado!');
		else if(resp!=-2&&resp!=-1&&resp!=-4)
		{
			simplePopUp('Comprobante No.'+num_comp+' ANULADO');
			waitAndReload();
			
		}
		else if(resp==-1){simplePopUp('Este Comprobante supera el limite de tiempo permitido(<?php echo $dias_anula_comps?> dia) para modificaciones, accion cancelada.... ');}
		else if(resp==-4)simplePopUp('Este comprobante ya fue COBRADO');
		else simplePopUp('Comprobante No.'+num_comp+' NO encontrado');
		
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