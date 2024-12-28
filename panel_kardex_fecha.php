<?php
require_once("Conexxx.php");
if($rolLv!=$Adminlvl && !val_secc($id_Usu,"informes_kardex_fecha")){header("location: centro.php");}
$boton="";
$num_fac="";
$fechaI="";
$fechaF="";
if(isset($_REQUEST['fechaI']))$fechaI=$_REQUEST['fechaI'];
if(isset($_REQUEST['fechaF']))$fechaF=$_REQUEST['fechaF'];
if(isset($_REQUEST['boton'])){
//$num_fac= limpiarcampo($_REQUEST['num_fac']);
$boton=$_REQUEST['boton'];
$busq=$_REQUEST['busq'];
$col=$_REQUEST['opc'];

}
//echo "$busq, $col";
if($boton=='Ver Kardex'&&isset($busq)&&!empty($busq)&&isset($col)&&!empty($col))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	$_SESSION['busq']=$busq;
	$_SESSION['col']=$col;
	
	?>
                <script language="javascript1.5" type="text/javascript">
				
window.open('kardex_fecha.php','Kardex Inventario','width=850,height=600,scrollbars=YES, location = YES menubar = NO, status = NO, titlebar = NO, toolbar = NO, resizable = YES , directories = NO');
				
				</script>
                
                <?php
	
}
if($boton=='Kardex Inventario'&&isset($fechaI)&&!empty($fechaI)&&isset($fechaF)&&!empty($fechaF))
{
	$_SESSION['fechaI']=$fechaI;
	$_SESSION['fechaF']=$fechaF;
	
	?>
                <script language="javascript1.5" type="text/javascript">
				
window.open('kardex.php','Kardex Inventario','width=850,height=600,scrollbars=YES, location = YES menubar = NO, status = NO, titlebar = NO, toolbar = NO, resizable = YES , directories = NO');
				
				</script>
                
                <?php
	
}


?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<title>
</title>
<link rel="stylesheet" href="JS/fac_ven.css"/>
<link rel="stylesheet" href="JS/lightBox.css"/>
<script type="text/javascript" language="javascript1.5" src="JS/popcalendar.js"></script>
<script src="JS/jquery-2.1.1.js"></script>
<script language="javascript" type="text/javascript" src="JS/fac_ven.js"></script>
<script type="text/javascript" language="javascript1.5" src="JS/TAC.js"></script>


<script type="text/javascript" language="javascript1.5" >

</script>

</head>
<body class="ui-content" >

<div data-role="page" data-theme="b" >

<div data-role="content">
<h1 style="color: #FFF">Kardex de Inventario</h1>
<?php //echo "boton:".$boton." ; dep: ".$dep."<br>INSERT INTO departamento (departamento) VALUES ('$dep')"; ?>
<form action="panel_kardex_fecha.php" method="get" name="anular" id="form_fac">
<div id="query"></div>

<table  width="600px" align="center" style="color:#FFF">
<tr >
<td style="color:#FFF"><label></label></td>
</tr>
<tr>

<td width="400px">
<table id="fecha" align="center" border="0"  style="color:#FFF">
<tr>
<td  align="right">Desde:</td>
<td style="color:#FFF">
<input size="10" name="fechaI" value="<?php echo $fechaI ?>" type="text" id="f_ini" onClick="popUpCalendar(this, form_fac.f_ini, 'yyyy-mm-dd');" readonly placeholder="Fecha Inicial">
</td>
<td>
Hasta:
</td>
<td>
<input size="15" value="<?php echo $fechaF ?>" type="text" name="fechaF" id="f_fin" onClick="popUpCalendar(this, form_fac.f_fin, 'yyyy-mm-dd');" readonly placeholder="Fecha Final">
</td>

<td><input type="text" name="busq" id="cod" value="" placeholder="Referencia" onKeyPress="busq_ref(this,'add');"></td><td>
<img style="cursor:pointer" title="Buscar" onMouseUp="busq_all($('#cod'));" src="Imagenes/search128x128.png" width="34" height="31" />
<div id="Qresp"></div></td>
<td>
<select name="opc">
<option value="" selected></option>
<option value="1"  selected>Referencia</option>
<option value="2" >Descripcion</option>
</select>
</td>
</tr>
<tr>
<td colspan="6" align="center"><input  type="submit" value="Ver Kardex" name="boton" class="button"  /></td>

<td>&nbsp;</td>
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