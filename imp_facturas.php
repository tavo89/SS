<?php
require_once("Conexxx.php");
$boton="";
$num_fac="";

if(isset($_REQUEST['boton'])){
$num_fac= limpiarcampo($_REQUEST['num_fac']);
$boton=$_REQUEST['boton'];

}

//echo "boton: $boton";
if($boton=="Imprimir" && isset($num_fac) &&!empty($num_fac)){
	//echo "<br>if, corregir....<br>";
	
$sql="SELECT * FROM fac_venta WHERE num_fac_ven='$num_fac'";

$rs=$linkPDO->query($sql);
$tipoImp="";
if(isset($_REQUEST['tipo_imp']))$tipoImp=$_REQUEST['tipo_imp'];

if($row=$rs->fetch())
{
	$sql="SELECT * FROM fac_venta WHERE num_fac_ven='$num_fac' AND ".VALIDACION_VENTA_VALIDA."";
            $rs=$linkPDO->query($sql);
			if($row=$rs->fetch())
			{
				$fecha=$row['fecha'];
				$_SESSION['n_fac_ven']=$num_fac;
				$_SESSION['anulada']="0";
				//echo "anulada".$_SESSION['anulada'];
				
			  	
				if($tipoImp=="post")
				{popup("imp_fac_ven.php","Factura No. $num_fac","800px","600px");}
				else popup("imp_fac_ven_cre.php","Factura No. $num_fac","800px","600px");
				?>
                <script language="javascript1.5" type="text/javascript">
				
//window.open('imp_fac_ven.php','Correccion Fac. de Venta','width=1300,height=550,scrollbars=YES, location = YES menubar = NO, status = NO, titlebar = NO, toolbar = NO, resizable = YES , directories = NO');
				
				</script>
                
               <?php
			   
			}//if fac anulada
			else 		
			{
				$_SESSION['n_fac_ven']=$num_fac;
				$_SESSION['anulada']="ANULADA";
			   ?>
                <script language="javascript1.5" type="text/javascript">
				var tipoImp=<?php echo $tipoImp ?>;
				if(confirm('La Factura No.<?php echo $num_fac?> Fue ANULADA, Imprimir de todas formas?'))
				{
					if(tipoImp=='post'){
                                        window.open('imp_fac_ven.php','Correccion Fac. de Venta','width=1300,height=500,scrollbars=YES, location = YES menubar = NO, status = NO, titlebar = NO, toolbar = NO, resizable = YES , directories = NO');
					}
					else {
						window.open('imp_fac_ven_cre.php','Correccion Fac. de Venta','width=1300,height=500,scrollbars=YES, location = YES menubar = NO, status = NO, titlebar = NO, toolbar = NO, resizable = YES , directories = NO');
						}
}
				
				
								
				</script>
                
                <?php
			}

}//if no encontrado

else
{
?>
                <script language="javascript1.5" type="text/javascript">
				simplePopUp('La Factura No.<?php echo $num_fac?> NO se encuentra en la Base de Datos..');				
				</script>
                
                <?php	
	
}
}







if($boton=="Crear Nueva" && isset($num_fac) &&!empty($num_fac)){
	//echo "<br>if, crear nueva....<br>";
	
$sql="SELECT * FROM fac_venta WHERE num_fac_ven='$num_fac'";

$rs=$linkPDO->query($sql);


if($row=$rs->fetch())
{
	$sql="SELECT * FROM fac_venta WHERE num_fac_ven='$num_fac' AND anulado='ANULADO'";
	$_SESSION['n_fac_ven']=$num_fac;
            //echo $sql;
            $rs=$linkPDO->query($sql);
			if($row=$rs->fetch())
			{
				
				?>
                <script language="javascript1.5" type="text/javascript">
				
window.open('nueva_fac_ven.php','Factura de Venta','width=1300,height=550,scrollbars=YES, location = YES menubar = NO, status = NO, titlebar = NO, toolbar = NO, resizable = YES , directories = NO');
				
				</script>
                
                <?php
			}
			else 
			
			{
			   
			   ?>
                <script language="javascript1.5" type="text/javascript">
				
				if(confirm('La factura No.<?php echo $num_fac ?> NO esta Anulada, Desea crear otra en base a esa?'))
				{
					window.open('nueva_fac_ven.php','Factura de Venta','width=1300,height=550,scrollbars=YES, location = YES menubar = NO, status = NO, titlebar = NO, toolbar = NO, resizable = YES , directories = NO');
				}
				else 
				{
					
				}
				</script>
                
                <?php
			   	
			}

}
else
{
?>
                <script language="javascript1.5" type="text/javascript">
				simplePopUp('La Factura No.<?php echo $num_fac?> NO se encuentra en la Base de Datos..');				
				</script>
                
                <?php	
	
}
}


?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>
</title>
<link rel="stylesheet" href="JS/fac_ven.css?<?php echo $LAST_VER;?>"/>
<link rel="stylesheet" href="JS/lightBox.css"/>
<script src="JS/jquery-2.1.1.js"></script>
<script type="text/javascript" language="javascript1.5" src="JS/TAC.js"></script>
<script type="text/javascript" language="javascript1.5" >

</script>

</head>
<body style="color:#FFF">

<div data-role="page" data-theme="b" >

<div data-role="content">
<h1>Imprimir Facturas</h1>
<?php //echo "boton:".$boton." ; dep: ".$dep."<br>INSERT INTO departamento (departamento) VALUES ('$dep')"; ?>
<form action="imp_facturas.php" method="get" name="anular" id="form_fac">
<div id="query"></div>

<table  width="600px" cellspacing="0">
<tr>
<td colspan="3"><label>Factura:</label></td>
</tr>
<tr>

<td width="">
<input name="num_fac" type="text" placeholder="Num. Factura de Venta"/ id="num_fac">
</td>
<td align="left">Tipo de Impresi&oacute;n:<select name="tipo_imp"><option value="post" selected="selected">Post</option><option value="carta">Carta</option></select></td>
<td align="left" ><img id="loader" src="Imagenes/ajax-loader.gif" width="31" height="31"  style="position:relative; left:0px"/></td>
</tr>

<tr>
<td colspan="3">
<table >
<tr>
<td >
<input type="submit" value="Imprimir" name="boton"   class="button">
</td>
</tr>
</table>
</td>
</tr>

</table>
</form>
</div>
</div>
</body>
</html>