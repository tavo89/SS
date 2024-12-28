<?php
include('Conexxx.php');

$num_comp=0;
$num_exp=0;
$tot_cuotas=0;
$tot_fac=0;
$saldo=0;
$nom_cli="";
$id_cli="";
$vendedor="";
$concepto="";

$fecha_comp="";
$valor_comp=0;



if(isset($_SESSION['num_comp_anti']))$num_comp=$_SESSION['num_comp_anti'];
if(isset($_SESSION['num_exp']))$num_exp=$_SESSION['num_exp'];
$ID=s("id_comp_anti");


$titulo="Comprobante de Anticipo No. $num_comp";

$sql="SELECT SUM(valor) as tot_abon FROM comp_anti c INNER JOIN exp_anticipo ex ON c.num_exp=ex.num_exp WHERE ex.num_exp=$num_exp AND c.estado!='ANULADO' AND ex.cod_su=$codSuc AND ex.cod_su=c.cod_su";
	//echo "<br><b>$sql</b>";
	$rs=$linkPDO->query($sql);
	
	if($row=$rs->fetch())
	{
	   	$tot_cuotas=$row['tot_abon'];
	}

$sql="SELECT * FROM exp_anticipo WHERE num_exp=$num_exp AND cod_su=$codSuc";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
	{
		
	   	$tot_fac=$row['tot'];
		$nom_cli=$row['nom_cli'];
		$tel_cli=$row['tel_cli'];
		$id_cli=$row['id_cli'];
		$tot_pa=$row['tot_pa'];
		$saldo=$tot_pa-$tot_cuotas;

		
	}
$sql="SELECT *,DATE(fecha) as fe FROM comp_anti WHERE id='$ID' AND num_exp=$num_exp AND cod_su=$codSuc";
//echo "$sql <br>";
$rs=$linkPDO->query($sql);
$vendedor="";
$TIPO="";
$firmaDoc="";
if($row=$rs->fetch())
	{
		$estado=$row['estado'];
	   	$valor_comp=$row['valor'];
		$fecha_comp=$row['fe'];
		$concepto=$row['concepto'];
		$vendedor=$row['cajero'];
		$tipo_comp=$row['tipo_comprobante'];
		if($usar_firmas_cajas==1){
			$firmaDoc=$row["firma_cajero"];
			//echo "row[]=".$row["firma_cajero"]."<br>";
			}
		if($tipo_comp=="Anticipo"){$TIPO="Comprobante de Anticipo";}
		else {$TIPO="Bono";}
		
	}
	

?>

<!DOCTYPE html PUBLIC >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link href="JS/fac_ven.css?<?php echo $LAST_VER;?>" rel="stylesheet" type="text/css" />
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<script language="javascript" type="text/javascript" src="JS/jQuery1.8.2.min.js"></script>
<script language="javascript" type="text/javascript" src="JS/num_letras.js"></script>
<script language="javascript" type="text/javascript">
$(document).ready(
	function(){$('#val_letras').html('('+covertirNumLetras(""+<?php echo $valor_comp ?>+"")+')')}
);
var key;
function imprimir(){
$('#imp').css('visibility','hidden');
window.print();
$('#imp').css('visibility','visible');
};

</script>
<title><?php echo $titulo ?></title>
</head>

<body>
<?php
if($imp_solo_pos){
	//echo "firma: $firmaDoc";
?>
<table class="imp_pos" align="center" cellspacing="1" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;top:0px; width:100%;">
<tr valign="top">
     <td height="80" colspan="5" align="center">
      <table width="100%" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;">
      <tr>
      
      <td align="left">

<?php echo $PUBLICIDAD2 ?>
 </td>
 </tr>
 <tr>
 <td colspan="2"><div align="center"><b><span style=" font-size:12px"><?php echo $TIPO?></span></b>
  No.
 <span style="color:#F00; font-size:18px"><strong></strong>&nbsp;<?php echo $num_comp?><strong></strong></span>
 </div>

 
 </td></tr>
 
  <?php
 
 if($estado=="ANULADO"){
 ?>
 <tr>
 <td colspan="3" align="center" style="font-size:24px"><B>ANULADO</B></td>
 </tr>
 
 <?php
 
 }
 
 ?>
 </table>
 
 </td>
    </tr>
<?php
}
else{
?>
<table align="center" cellspacing="1" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;top:0px; width:15.5cm; height:8.9cm;">
<tr valign="top">
     <td height="80" colspan="5" align="center">
      <table width="100%" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;">
      <tr>
	  <td width="150px"> <div align="center">
<img src="Imagenes/LOGO.png" width="110px" height="100px" />
</div></td>
      <td align="left">

<?php echo $PUBLICIDAD2 ?>
 </td>
 </tr>
 <tr>
 <td colspan="2"><div align="center"><b><span style=" font-size:18px"><?php echo $TIPO?></span></b>
  No.
 <span style="color:#F00; font-size:18px"><strong></strong>&nbsp;<?php echo $num_comp?><strong></strong></span>
 </div>

 
 </td></tr>
 </table>
 
 </td>
    </tr>
<?php }?>
    
    
   <tr valign="top">
   <td>
   
   <table width="100%" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;">
   <!--
   <tr>
   <td width="20%">Expediente No.:</td><td style="color:red"><?php echo "$num_exp"?></td>
   </tr>
   -->
   <tr>
   <td width="20%">Cliente:</td><td><?php echo "$nom_cli"?></td>
   </tr>
    <tr>
   <td width="20%">Tel:</td><td><?php echo "$tel_cli"?></td>
   </tr>
   <tr>
   <td>NIT/C.C:</td><td><?php echo "$id_cli"?></td>
   </tr>
   <tr>
   <td>Por concepto de:</td><td ><b><i><?php echo "$concepto"?></i></b></td>
   </tr>
   </table>
   
   </td>
   </tr>
   
   <tr valign="top">
   <td height="100%">
   <table width="100%" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;">
   <tr>
   <td width="20%">Efectivo: </td><td>$<?php echo money($valor_comp)?></td><td id="val_letras"></td>
   </tr>
   <tr>
   <td width="20%">Fecha: </td><td><?php echo fecha($fecha_comp)?></td><td ></td>
   </tr>
   <tr>
   <td width="20%">Total Abonos: </td>
   <td>$<?php echo money($tot_cuotas)?></td>
   </tr>
  
      <tr>
   <td width="20%">Total a pagar: </td>
   <td>$<?php echo money($tot_pa)?></td>
   </tr>
      <tr>
   <td width="20%">Saldo: </td>
   <td>$<?php echo money($saldo)?></td>
   </tr>
   
   <tr>
   <td align="center" colspan="3">
  <?php if(!empty($firmaDoc)){?><img src="<?php echo "$firmaDoc"; ?>"   style="max-width:100%; height:100px;"><?php } ?>
   <br />___________________________<BR /><?php echo "$vendedor"?><br />Cajero(a)</td>
   </tr>
   </table>
   </td>
   </tr>
   
    </table>
    <table align="center">
    <tr>
    <td>
    <div id="imp" align="center" style="width:100%;border-style: groove; border-color:#F00">
    
    <input type="button" value="VOLVER" onClick="javascript:location.assign('creditos.php?pag=<?php if(isset($_SESSION['pag']))echo $_SESSION['pag'] ?>');" />
    <input  type="button" value="IMPRIMIR" name="boton"  onClick="imprimir()"/>
    
    <input name="hid" type="hidden" value="<%=dim4%>" id="Nart" />
  </div>
  </td></tr></table>
</body>
</html>