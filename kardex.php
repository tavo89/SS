<?php
include_once("Conexxx.php");
ini_set('memory_limit', '1024M'); 
$codBar=r('codBar');
$ref=r("Ref");
$feVen=r("feVen");


$saldo[][]=0;
$rs=$linkPDO->query("SELECT *  FROM inv_inter WHERE nit_scs=$codSuc");
while($row=$rs->fetch())
{
	
$saldo[$row['id_inter']][1]=0;
$saldo[$row['id_inter']][2]=0;	
	
}

?>

<!DOCTYPE html PUBLIC >
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="CONtent-Type" cONtent="text/html; charset=<?php echo $CHAR_SET ?>" />
 <link href="JS/print.css" rel="stylesheet" type="text/css" media="print"> 
<title>K&aacute;rdex Inventario</title>
<script language="javascript1.5" type="text/javascript" src="JS/jQuery1.8.2.min.js">
</script>
<script language="javascript1.5" type="text/javascript">
function imprimir(){
$('#imp').css('visibility','hidden');
window.print();
$('#imp').css('visibility','visible');
};
</script>
</head>

<body style="fONt-size:12px">
<div style=" top:0cm; width:21.5cm; height:27.9cm; positiON:absolute;">
<table align="center" width="100%">
<tr>
<td>
<?php echo $PUBLICIDAD2 ?>
</td>
<td valign="top">
<p align="left" style="fONt-size:12px;">
<span align="center" style="fONt-size:24px"><B>K&aacute;rdex Inventario </B></span>
</p>
</td>

</tr>
</table>
Fecha: <?PHP echo $hoy ?>
<br>
<table align="center" width="100%">
<tr>
<td colspan="3">
<table cellspacing="0px" cellpadding="0px">
<tr>
<td><b>Usuario </b></td><td width="50px"></td><td colspan=""><b><?php echo $_SESSION['nom_usu'] ?></b>
<?php //echo "$ORDEN[$col] , $col, $codBar" ?>
</td>
</tr>
<tr style="fONt-size:18px; fONt-weight:bold;">
<td colspan="3">B&uacute;squeda: <?php echo "Ref: $ref Codigo :$codBar" ?></td>
</tr>
</table>
</td>
</tr>

</table>


<hr align="center" width="100%">
<table align="center" width="100%" cellpadding="5" cellspacing="2" style="fONt-size:11px" rules="rows">
<tr style="fONt-size:18px; ">
<td width="80">Factura</td>
<td width="40">Num.</td>

<td width="100">Referencia</td>
<td width="30%">Descripci&oacute;n</td>
<td>Costo</td>
<td>PvP</td>
<td>Fracc.</td>
<td>Entran</td>
<td>Salen</td>
<td>Saldo</td>

<td width="100">Fecha</td>
</tr>
<?php
 
$sql=q_kardex($ref,$codBar,$feVen);
//echo $sql;
$rs=$linkPDO->query($sql);

//                   1       2          3        4          5                 6                7              8					 	9					10					11
$fuente=array(1=>"Compra","Venta","Remision","ANULADO","Envio Traslado","Recibo Traslado","Devolucion","Ajuste Inventario","Devolucion Venta","ANULA Devolucion Venta","Remision Facturada","Compra ANULADA");
//                12

//               1   2   3   4   5   6   7   8   9   10  11  12 
$signo=array(1=>"+","-","-","~","-","+","-","+","+","~","~","~");
while($row=$rs->fetch())
{
	$num_fac=$row['num'];
	$subTot=money($row['tot']);
	$IVA=money($row['iva']);
	//$des=htmlentities($row['des'], ENT_QUOTES,"$CHAR_SET");
	$des=$row['des'];
	$cant=$row['cant']*1;
	$uni=$row["unidades_fraccion"];
	$FRAC=$row["fraccion"];
	if($FRAC<=0){$FRAC=1;}
	$costo=$row["costo"];
	$valor=($row['precio']);
	$ref=$row['cod_barras'];
	//$vendedor=htmlentities($row["vendedor"], ENT_QUOTES,"$CHAR_SET");
	//$HORA=$row['hora'];
	$fecha=$row['fecha'];
	$src=$row['src'];
	if($signo[$src]=="+"){$saldo[$ref][1]+=$cant;$saldo[$ref][2]+=$uni;}
	else if($signo[$src]=="-"){$saldo[$ref][1]-=$cant;$saldo[$ref][2]-=$uni;}
	
	$saldoFrac=ajuste_frac($saldo[$ref][1],$saldo[$ref][2],$FRAC,$ref);
	
	$style = "font-size:11px; text-transform:uppercase;font-weight:bold;";
	$errorWarning ='';
	if($uni>$FRAC){
	    $style.='background-color:#F00';
		$errorWarning ='Error!';	
	}
	?>
    <tr style=" 
    <?php 
	if($signo[$src]=="+"||$signo[$src]=="~"){echo $style;} 
	
	?>" >
    <td><?php echo $fuente[$src]." ".$errorWarning ?></td>
    <td width="center"><?php echo $num_fac ?></td>
 
    <td><?php echo $ref ?></td>
    <td><?php echo $des ?></td>
    <td><?php echo money($costo) ?></td>
    <td><?php echo money($valor) ?></td>
    <td><?php echo "[$FRAC]" ?></td>
    <td ><?php if($signo[$src]=="+"||$signo[$src]=="~")echo "$cant;$uni" ?></td>
    <td><?php if($signo[$src]=="-")echo "$cant;$uni" ?></td>
    <td><?php echo ($saldoFrac[1]*1).";".$saldoFrac[2];
			  //echo ($saldo[$ref][1]).";".$saldo[$ref][2];
	 ?></td>
     <td><?php echo $fecha ?></td>
    </tr>
    
    <?php
	
	
}

?>
</table>
<hr align="center" width="100%" />
<div id="imp"  align="center">
    <input name="hid" type="hidden" value="<%=dim%>" id="Nart" />
    <input  type="buttON" value="Imprimir" name="botON" ONMouseDown="javascript:imprimir();" />
</div> 
</div>
</body>
</html>