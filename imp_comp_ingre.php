<?php
include_once('Conexxx.php');
valida_session();

$firmaEMPRESA="";
if($usar_firmas_factura==1){
$rs=$linkPDO->query("SELECT firma_fac FROM sucursal WHERE cod_su='$codSuc'" );
//echo "SELECT firma_fac FROM sucursal WHERE cod_su='$codSuc'";
$row=$rs->fetch();
$firmaEMPRESA=$row["firma_fac"];	
	
}

$num_comp=0;
$num_fac=0;
$tot_cuotas=0;
$tot_fac=0;
$saldo=0;
$nom_cli="";
$id_cli="";
$vendedor="";
$concepto="";

$fecha_comp="";
$valor_comp=0;

$pre="";

if(isset($_SESSION['num_comp_ingre']))$num_comp=$_SESSION['num_comp_ingre'];
if(isset($_SESSION['num_fac_ven']))$num_fac=$_SESSION['num_fac_ven'];
$pre=s('pre');

$titulo="Comprobante de Ingreso No. $num_comp";

$sql="SELECT *,DATE(fecha) as fe,(select CONCAT(nombre,' ',snombr,' ',apelli) from usuarios WHERE usuarios.id_usu=comprobante_ingreso.id_cli LIMIT 1) as nom_cli FROM comprobante_ingreso WHERE num_com=$num_comp AND cod_su=$codSuc";
//echo "<li>$sql</li>";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch())
	{
	   	$valor_comp=$row['valor'];
		$fecha_comp=$row['fe'];
		$concepto=$row['concepto'];
		$pre=$row['pre'];
		$nom_cli=$row['nom_cli'];
		$id_cli=$row['id_cli'];
		$FechaC=$row["fecha"];
		$tipo_op=$row["tipo_operacion"];
		$feI=$row["fe_ini"];
		$feF=$row["fe_fin"];
		$CAJERO=$row["cajero"];
		$r_fte=$row["r_fte"];
		$r_ica=$row["r_ica"];
		
		
	}

if($MODULES["CARTERA_MASS"]==1){$STATS_PAGOS=tot_saldo_cre_rango($FechaC,$feI,$feF,$num_comp);}
else {$STATS_PAGOS=tot_saldo($id_cli,$FechaC,$num_comp);}
$tot_fac=$STATS_PAGOS["tot"];
$tot_cuotas=$STATS_PAGOS["abono"];


/*
$sql="SELECT SUM(valor) as tot_abon FROM comprobante_ingreso c INNER JOIN fac_venta p ON p.num_fac_ven=c.num_fac WHERE p.prefijo=c.pre AND p.prefijo='$pre' AND p.num_fac_ven=$num_fac AND c.anulado!='ANULADO' AND date(c.fecha)<='$fecha_comp' AND  c.cod_su=p.nit AND p.nit='$codSuc'";	
	//echo "<br><b>$sql</b>";
	$rs=$linkPDO->query($sql);
	
	if($row=$rs->fetch())
	{
	   	$tot_cuotas=$row['tot_abon'];
	}

*/	
$saldo=$STATS_PAGOS["saldo"];
?>

<!DOCTYPE html PUBLIC >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<?php include_once("IMP_HEADER.php"); ?>
<link href="JS/fac_ven.css?<?php echo $LAST_VER;?>" rel="stylesheet" type="text/css" />
<style type="text/css" media="print">
    @page 
    {
        size: auto;   /* auto is the initial value */
        margin: 3mm;  /* this affects the margin in the printer settings */
    }

    body 
    {
        background-color:#FFFFFF; 
       /** border: solid 1px black ;**/
        margin: 3px;  /* this affects the margin on the content before sending to printer */
   }
</style>
<script language="javascript" type="text/javascript" src="JS/num_letras.js"></script>
<script language="javascript" type="text/javascript">
$(document).ready(
	function(){$('#val_letras').html('('+covertirNumLetras(""+<?php echo $valor_comp ?>+"")+')')}
);
</script>
<title><?php echo $titulo ?></title>
</head>

<body>
<table class="imp_pos" align="center" cellspacing="1" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;top:0px; width:100%; height:8.9cm;">
    <tr valign="top">
     <td height="80" colspan="5" align="center">
      <table width="100%" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;" class="imp_pos">
      
	  <tr>
	  <td width=" " colspan="2"> <div align="center">
<img src="<?php echo $url_LOGO_A ?>" width="<?php echo $X ?>" height="<?php echo $Y ?>">
</div></td>
</tr>
<tr>
      <td colspan="2" align="center">


<?php echo $PUBLICIDAD2 ?>

 </td>
 </tr>
 <tr>
 <td colspan="2"><p align="center"><b><span style=" font-size:12px">Comprobante de Ingreso</span></b>
  No.
 <span style="color:#F00; font-size:14px"><strong></strong>&nbsp;<?php echo $num_comp?><strong></strong></span>
 </p>
 
 
 </td></tr>
 </table>
 
 </td>
    </tr>
    
   <tr valign="top">
   <td>
   
   <table width="100%" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;" class="imp_pos">
  <!-- <tr>
   <td width="20%">Factura:</td><td style="color:red"><?php echo "$pre-$num_fac"?></td>
   </tr>
   -->
   <tr>
   <td width="20%">Cliente:</td><td><?php echo "$nom_cli"?></td>
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
   <table width="100%" frame="box" style="-webkit-border-radius:10px;-moz-border-radius:10px;" class="imp_pos">
   <tr>
   <td width="20%">Efectivo: </td><td>$<?php echo money($valor_comp)?></td><td id=""></td>
   </tr>
     <tr>
   <td width="20%">Rete FTE: </td><td>$<?php echo money($r_fte)?></td><td id=""></td>
   </tr>
      <tr>
   <td width="20%">Rete ICA: </td><td>$<?php echo money($r_ica)?></td><td id=""></td>
   </tr>
      <tr>
   <td width="20%">Pago: </td><td>$<?php echo money($valor_comp-$r_fte-$r_ica)?></td><td id="val_letras"></td>
   </tr>
   <tr>
   <td width="20%">Fecha: </td><td><?php echo fecha($fecha_comp)?></td><td ></td>
   </tr>

   <tr>
      <td width="20%">Saldo: </td><td>$<?php echo money($saldo)?></td>
   </tr>
   <tr>
   <td align="center" colspan="3">
   <?php if(!empty($firmaEMPRESA)){?>
<div><img src="<?php echo "$firmaEMPRESA"; ?>"   style="max-width:60%; height:50px;"></div><?php } ?>___________________________<BR /><?php echo "$CAJERO"?><br />Recibe</td>
   </tr>
   </table>
   </td>
   </tr>
   
    </table>
    <table align="center" class="imp_pos">
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