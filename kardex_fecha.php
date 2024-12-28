<?php
require_once("Conexxx.php");


$saldo[]=0;
$refColor[]="";
$refCosto[]=0;
$SUMsaldos[]=0;
$SUMcostos[]=0;
$rs=$linkPDO->query("SELECT *  FROM inv_inter WHERE nit_scs=$codSuc");
while($row=$rs->fetch())
{
	
$saldo[$row['id_pro']]=0;	
$refColor[$row['id_pro']]=randomColor();
$refCosto[$row['id_pro']]=$row['costo'];
$SUMsaldos[$row['id_pro']]=0;
$SUMcostos[$row['id_pro']]=0;
}

$fechaI="";
$fechaF="";
$busq="";
$col="";
$Az="";
$Za="";

if(isset($_SESSION['fechaI']))$fechaI=$_SESSION['fechaI'];
if(isset($_SESSION['fechaF']))$fechaF=$_SESSION['fechaF'];
if(isset($_SESSION['busq']))$busq=$_SESSION['busq'];
if(isset($_SESSION['col']))$col=$_SESSION['col'];
$ORDEN=array(1=>"ref","des");

$ORDER_BY="";
if($col==1)$ORDER_BY="fe";
else $ORDER_BY="fe";

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
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

<body style="font-size:12px">
<div style=" top:0cm; width:21.5cm; height:27.9cm; position:absolute;">
<table align="center" width="100%">
<tr>
<td>
<?php echo $PUBLICIDAD2 ?>
</td>
<td valign="top">
<p align="left" style="font-size:12px;">
<span align="center" style="font-size:24px"><B>K&aacute;rdex Inventario </B></span>
</p>
</td>

</tr>
</table>
Fecha: <?PHP echo $hoy ?>
<br>
<table align="center" width="100%">
<tr>
<td style="font-size:24px; font-weight:bold;">
Desde: <?PHP echo fecha($_SESSION['fechaI']) ?>
</td>
<td style="font-size:24px; font-weight:bold;"> Hasta: <?php echo fecha($_SESSION['fechaF']) ?>
</td>
</tr>
<tr>
<td colspan="3">
<table cellspacing="0px" cellpadding="0px">
<tr>
<td><b>Usuario </b></td><td width="50px"></td><td colspan=""><b><?php echo $_SESSION['nom_usu'] ?></b>
<?php //echo "$ORDEN[$col] , $col, $busq" ?>
</td>
</tr>
<tr style="font-size:18px; font-weight:bold;">
<td colspan="3">B&uacute;squeda: <?php echo $busq ?></td>
</tr>
</table>
</td>
</tr>

</table>


<hr align="center" width="100%">
<table align="center" width="100%" cellpadding="1" cellspacing="1" style="font-size:11px" rules="rows">
<tr style="font-size:18px; ">
<td width="80">Factura</td>
<td width="40">Num.</td>

<td width="100">Referencia</td>
<td width="30%">Descripci&oacute;n</td>
<td>Costo U.</td>
<td>Entran</td>
<td>Salen</td>
<td>Saldo</td>

<td width="100">Fecha</td>
</tr>
<?php
if($col==1){
$sql="select 1 as src,a.num_fac_com as num,ref,des,cant,a.iva,costo as precio,a.tot,DATE(f.fecha_mod) fecha,f.fecha as fe from art_fac_com a inner join fac_com f on a.num_fac_com=f.num_fac_com  where f.estado='CERRADA' AND a.cod_su=f.cod_su and a.nit_pro=f.nit_pro and a.cod_su=$codSuc  and DATE(f.fecha)>='$fechaKardex' and ($ORDEN[$col]='$busq' )
UNION

select 2 as src,a.num_fac_ven as num,ref,des,cant,a.iva,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe from art_fac_ven a inner join fac_venta f on a.num_fac_ven=f.num_fac_ven  where a.prefijo=f.prefijo and a.nit=f.nit and f.".VALIDACION_VENTA_VALIDA." and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($ORDEN[$col]='$busq' )
UNION

select 4 as src,a.num_fac_ven as num,ref,des,cant,a.iva,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe from art_fac_ven a inner join fac_venta f on a.num_fac_ven=f.num_fac_ven  where a.prefijo=f.prefijo AND a.nit=f.nit and f.anulado='ANULADO' and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($ORDEN[$col]='$busq' )

UNION

select 5 as src,a.cod_tras as num,ref,des,cant,a.iva,costo,a.tot,DATE(f.fecha_envio) fecha,f.fecha_envio as fe from art_tras a inner join traslados f on a.cod_tras=f.cod_tras  where a.cod_su=f.cod_su and a.cod_su=$codSuc and DATE(f.fecha_envio)>='$fechaKardex' and ($ORDEN[$col]='$busq' )
UNION

select 6 as src,a.cod_tras as num,ref,des,cant,a.iva,costo,a.tot,DATE(f.fecha_envio) fecha,f.fecha_envio as fe from art_tras a inner join (select * from traslados) f on a.cod_tras=f.cod_tras  where a.cod_su=f.cod_su and f.cod_su_dest=$codSuc AND f.estado='CONFIRMADO' and DATE(f.fecha_envio)>='$fechaKardex' and ($ORDEN[$col]='$busq' )
UNION

select 7 as src,a.num_fac_com as num,ref,des,cant,a.iva,costo as precio,a.tot,DATE(f.fecha) fecha,f.fecha as fe from art_fac_dev a inner join fac_dev f on a.num_fac_com=f.num_fac_com  where a.cod_su=f.cod_su and a.nit_pro=f.nit_pro and a.cod_su=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($ORDEN[$col]='$busq' )
UNION

select 8 as src,a.num_ajuste as num,ref,des,cant,a.iva,costo as precio,a.precio,DATE(f.fecha) fecha,f.fecha as fe from art_ajuste a inner join ajustes f on a.num_ajuste=f.num_ajuste  where a.cod_su=f.cod_su  and a.cod_su=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($ORDEN[$col]='$busq' )

ORDER BY $ORDER_BY
 ";
}

else if($col==2){
$sql="select 1 as src,a.num_fac_com as num,ref,des,cant,a.iva,costo as precio,a.tot,DATE(f.fecha_mod) fecha,f.fecha as fe from art_fac_com a inner join fac_com f on a.num_fac_com=f.num_fac_com  where f.estado='CERRADA' AND a.cod_su=f.cod_su and a.nit_pro=f.nit_pro and a.cod_su=$codSuc  and DATE(f.fecha)>='$fechaKardex' and ($ORDEN[$col] LIKE '$busq%' )
UNION

select 2 as src,a.num_fac_ven as num,ref,des,cant,a.iva,precio as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe from art_fac_ven a inner join fac_venta f on a.num_fac_ven=f.num_fac_ven  where a.prefijo=f.prefijo and a.nit=f.nit and f.".VALIDACION_VENTA_VALIDA." and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($ORDEN[$col] LIKE '$busq%' )
UNION


select 4 as src,a.num_fac_ven as num,ref,des,cant,a.iva,costo as precio,a.sub_tot as tot,DATE(f.fecha) fecha,f.fecha as fe from art_fac_ven a inner join fac_venta f on a.num_fac_ven=f.num_fac_ven  where a.prefijo=f.prefijo AND a.nit=f.nit and f.anulado='ANULADO' and a.nit=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($ORDEN[$col] LIKE '$busq%' )

UNION

select 5 as src,a.cod_tras as num,ref,des,cant,a.iva,costo,a.tot,DATE(f.fecha_envio) fecha,f.fecha_envio as fe from art_tras a inner join traslados f on a.cod_tras=f.cod_tras  where a.cod_su=f.cod_su and a.cod_su=$codSuc and DATE(f.fecha_envio)>='$fechaKardex' and ($ORDEN[$col] LIKE '$busq%' )
UNION

select 6 as src,a.cod_tras as num,ref,des,cant,a.iva,costo,a.tot,DATE(f.fecha_envio) fecha,f.fecha_envio as fe from art_tras a inner join (select * from traslados) f on a.cod_tras=f.cod_tras  where a.cod_su=f.cod_su and f.cod_su_dest=$codSuc AND f.estado='CONFIRMADO' and DATE(f.fecha_envio)>='$fechaKardex' and ($ORDEN[$col] LIKE '$busq%' )
UNION

select 7 as src,a.num_fac_com as num,ref,des,cant,a.iva,costo as precio,a.tot,DATE(f.fecha) fecha,f.fecha as fe from art_fac_dev a inner join fac_dev f on a.num_fac_com=f.num_fac_com  where a.cod_su=f.cod_su and a.nit_pro=f.nit_pro and a.cod_su=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($ORDEN[$col] LIKE '$busq%')
UNION

select 8 as src,a.num_ajuste as num,ref,des,cant,a.iva,costo as precio,a.precio,DATE(f.fecha) fecha,f.fecha as fe from art_ajuste a inner join ajustes f on a.num_ajuste=f.num_ajuste  where a.cod_su=f.cod_su  and a.cod_su=$codSuc and DATE(f.fecha)>='$fechaKardex' and ($ORDEN[$col] LIKE '$busq%')

ORDER BY $ORDER_BY
 ";
}

//echo "ORDEN:  $ORDEN[$col], col: $col";
$rs=$linkPDO->query($sql);

$fuente=array(1=>"Compra","Venta","Orden Taller","ANULADO","Envio Traslado","Recibo Traslado","Devolucion","Ajuste Inventario");
$signo=array(1=>"+","-","-","~","-","+","-","+");

$refQuery[]=0;
/*

//////////////////////////// PRE ANALISIS ///////////////////////////////////////////////////
while($row=$rs->fetch())
{
	$num_fac=$row['num'];
	$subTot=money($row['tot']);
	$IVA=money($row['iva']);
	//$des=htmlentities($row['des'], ENT_QUOTES,"$CHAR_SET");
	$des=$row['des'];
	$cant=$row['cant'];
	$valor=money($row['precio']);
	$ref=$row['ref'];
	//$vendedor=htmlentities($row["vendedor"], ENT_QUOTES,"$CHAR_SET");
	//$HORA=$row['hora'];
	$fecha=$row['fecha'];
	$src=$row['src'];
	if($signo[$src]=="+")$saldo[$ref]+=$cant;
	else if($signo[$src]=="-")$saldo[$ref]-=$cant;
	$refQuery[$ref]=$saldo[$ref];
	
	
	if($fecha>=$fechaI && $fecha<=$fechaF){
		
		
		
	}
	
}
*/
$fuente=array(1=>"Compra","Venta","Orden Taller","ANULADO","Envio Traslado","Recibo Traslado","Devolucion","Ajuste Inventario");
////////////////////////////////////////////////////////// CONSULTA //////////////////////////////////////////////////////////////
$rs=$linkPDO->query($sql);
$contMES=0;
$flagMES=0;
$dateArray="";
while($row=$rs->fetch())
{
	$num_fac=$row['num'];
	$subTot=money($row['tot']);
	$IVA=money($row['iva']);
	//$des=htmlentities($row['des'], ENT_QUOTES,"$CHAR_SET");
	$des=$row['des'];
	$cant=$row['cant']*1;
	$valor=money($row['precio']);
	$ref=$row['ref'];
	//$vendedor=htmlentities($row["vendedor"], ENT_QUOTES,"$CHAR_SET");
	//$HORA=$row['hora'];
	$fecha=$row['fecha'];
	$src=$row['src'];
	if($signo[$src]=="+")$saldo[$ref]+=$cant;
	else if($signo[$src]=="-")$saldo[$ref]-=$cant;
	
	if($fecha>=$fechaI && $fecha<=$fechaF){
	
	$dateArray=explode("-",$fecha);
	$MES=1*$dateArray[1];
	if($MES!=$flagMES)$contMES=0;
	//if($fuente[$src]=="Venta"||$fuente[$src]=="Envio Traslado"||$fuente[$src]=="Orden Taller"||$fuente[$src]=="Devolucion")$saldo[$ref]+=$cant;
	$SUMsaldos[$ref]=$saldo[$ref];
	$SUMcostos[$ref]=$saldo[$ref]*$refCosto[$ref];
	
	
	
	$SUM_CANT=array_sum($SUMsaldos);
	$SUM_COSTOS=array_sum($SUMcostos);
	
	
	
	if($contMES==0){
		$contMES=1;
	?>
    <tr>
    <td colspan="9" style="font-size:18px; font-weight:bold;"><?php echo $MESES[$MES]." Inventario Inicial Mes: $SUM_CANT, TOT. Costo:".money($SUM_COSTOS) ?></td>
    </tr>
    <?php
	}
	
	
	
	$flagMES=$MES;
	?>
    
    <tr>
    <td <?php if($signo[$src]=="+"||$signo[$src]=="~")echo "style='font-size:11px; text-transform:uppercase;font-weight:bold'" ?>><?php echo $fuente[$src] ?></td>
    <td width="center"><?php echo $num_fac ?></td>
 
    <td style="color:<?php echo $refColor[$ref] ?>;"><?php echo $ref ?><sup></sup></td>
    <td><?php echo $des ?></td>
    <td><?php echo money($refCosto[$ref]) ?></td>
    <td ><?php if($signo[$src]=="+"||$signo[$src]=="~")echo $cant ?></td>
    <td><?php if($signo[$src]=="-")echo $cant ?></td>
    <td><?php echo $saldo[$ref] ?></td>
     <td><?php echo $fecha ?></td>
    </tr>
    
    <?php
	}// FIN RANGO FECHA
	
}

?>
</table>
<hr align="center" width="100%" />
<div id="imp"  align="center">
    <input name="hid" type="hidden" value="<%=dim%>" id="Nart" />
    <input  type="button" value="Imprimir" name="boton" onMouseDown="javascript:imprimir();" />
</div> 
</div>
</body>
</html>