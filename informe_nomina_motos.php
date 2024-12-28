<?php
require_once("Conexxx.php");
$fechaI=$_SESSION['fechaI'];
$fechaF=$_SESSION['fechaF'];
$clase="";
$fab="";

$opt=r("opt");

$filtroSede="";$filtroSede2="";
if($opt=="A"){$filtroSede=" AND nit='$codSuc'";$filtroSede2=" AND cod_su='$codSuc'";}

$filtroCerradas=" AND anulado='CERRADA'";
$filtroNOanuladas="AND ( anulado!='ANULADO' $filtroCerradas  ) ";



$METAS_SEDES[][]=0;
$BONO_SEDES[][]=0;
$sql="SELECT * FROM metas_ventas";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch()){
	
	$METAS_SEDES[$row["cod_su"]][$row["marca1"]]=$row["meta"];
	$BONO_SEDES[$row["cod_su"]][$row["marca1"]]=$row["bono_meta"];
	
}
 
$sql="SELECT * FROM x_config_nomina";
$rs=$linkPDO->query($sql);
if($row=$rs->fetch()){
$porcentaje_a=$row["per_a"]/100;
$porcentaje_b=$row["per_b"]/100;
$porcentaje_c=$row["per_c"]/100;
}

 


///////////////////////////// INI. VARS ////////////////////////////////
$NOMINAS_METAS[][]=0;
$NOMINAS_VENTA[][]=0;
$NOMINAS_CURSO[]=0;
$NUM_MOTOS[][]=0;

$NOMINAS[][]=0;
$TOT_VENTAS[][]=0;

$TOT_BONOS_VENTAS[]=0;

$cols="vendedor,id_vendedor,marca_moto,tot,entrega,nit";
$sql=" SELECT * FROM fac_venta WHERE  (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF') $filtroNOanuladas $filtroSede GROUP BY id_vendedor";
$rs=$linkPDO->query($sql);
$porcentaje_venta=0;
while($row=$rs->fetch())
{
$codigoSede=$row["nit"];
$IDvendedor=$row["id_vendedor"];
$MARCA=$row["marca_moto"];


$TOT_BONOS_VENTAS[$IDvendedor]=0;

$MARCA="HONDA";
$NOMINAS_METAS[$IDvendedor][$MARCA]=0;
$NOMINAS_VENTA[$IDvendedor][$MARCA]=0;
$NOMINAS_CURSO[$IDvendedor]=0;
$NOMINAS[$IDvendedor][$MARCA]=0;
$TOT_VENTAS[$IDvendedor][$MARCA]=0;
$NUM_MOTOS[$IDvendedor][$MARCA]=0;

$MARCA="HERO";
$NOMINAS_METAS[$IDvendedor][$MARCA]=0;
$NOMINAS_VENTA[$IDvendedor][$MARCA]=0;
$NOMINAS_CURSO[$IDvendedor]=0;
$NOMINAS[$IDvendedor][$MARCA]=0;
$TOT_VENTAS[$IDvendedor][$MARCA]=0;
$NUM_MOTOS[$IDvendedor][$MARCA]=0;

$MARCA="AIIMA";
$NOMINAS_METAS[$IDvendedor][$MARCA]=0;
$NOMINAS_VENTA[$IDvendedor][$MARCA]=0;
$NOMINAS_CURSO[$IDvendedor]=0;
$NOMINAS[$IDvendedor][$MARCA]=0;
$TOT_VENTAS[$IDvendedor][$MARCA]=0;
$NUM_MOTOS[$IDvendedor][$MARCA]=0;
	
}



///////////// BONOS  VENTAS ///////////////

$cols="vendedor,id_vendedor,marca_moto,tot,entrega,nit";
$sql=" SELECT * FROM comprobante_ingreso WHERE  (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF')  AND anulado!='ANULADO' $filtroSede2";
$rs=$linkPDO->query($sql);
$porcentaje_venta=0;
while($row=$rs->fetch())
{

$codigoSede=$row["cod_su"];
$IDvendedor=$row["id_vendedor"];
$TOT=$row["valor"];
 
$MARCA="";

if(isset($TOT_BONOS_VENTAS[$IDvendedor])){$TOT_BONOS_VENTAS[$IDvendedor]+=$TOT;}




//echo "$NOMINAS_VENTA[$IDvendedor][$MARCA] <br>";
}




///////////// NOMINA PORCENTAJE VENTAS ///////////////

$cols="vendedor,id_vendedor,marca_moto,tot,entrega,nit";
$sql=" SELECT *,DATE(fecha) as fe FROM fac_venta WHERE  (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF') $filtroNOanuladas $filtroSede";
$rs=$linkPDO->query($sql);
$porcentaje_venta=0;
while($row=$rs->fetch())
{

$codigoSede=$row["nit"];
$IDvendedor=$row["id_vendedor"];
$TOT=$row["tot"];
$SUB_TOT=$row["sub_tot"];
$INICIAL=$row["entrega"];
$MARCA=$row["marca_moto"];
$fe=$row["fe"];
if($TOT==0)$TOT=1;
$per=redondeo(100* ($INICIAL/$TOT) );

if($fe <= "2018-06-30"){$SUB_TOT=$TOT;}
//$SUB_TOT=$TOT;

$TOT2=money2($TOT);
if($per>=19 && $per<=20){ $porcentaje_venta=$SUB_TOT*$porcentaje_a;   }
else if($per>20 && $per<=25){$porcentaje_venta=$SUB_TOT*$porcentaje_b;  }
else if($per>25){$porcentaje_venta=$SUB_TOT*$porcentaje_c;  }
else {$porcentaje_venta=0;  }





if(isset($NOMINAS_VENTA[$IDvendedor][$MARCA])){$NOMINAS_VENTA[$IDvendedor][$MARCA]+=$porcentaje_venta;}
if(isset($TOT_VENTAS[$IDvendedor][$MARCA])){$TOT_VENTAS[$IDvendedor][$MARCA]+=$TOT;}

//echo "$NOMINAS_VENTA[$IDvendedor][$MARCA] <br>";
}



///////////// NUMERO MOTOS VENDIDAS X ASESOR///////////////

$cols="vendedor,id_vendedor,marca_moto,tot,entrega,nit";
$sql=" SELECT * FROM fac_venta WHERE  (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF') $filtroNOanuladas $filtroSede";
$rs=$linkPDO->query($sql);
$porcentaje_venta=0;
while($row=$rs->fetch())
{

$codigoSede=$row["nit"];
$IDvendedor=$row["id_vendedor"];
$TOT=$row["tot"];
$INICIAL=$row["entrega"];
$MARCA=$row["marca_moto"];
 

if(isset($NUM_MOTOS[$IDvendedor][$MARCA])){$NUM_MOTOS[$IDvendedor][$MARCA]+=1;}

}


/////// NOMINA X METAS
$cols="vendedor,id_vendedor,marca_moto,tot,entrega,nit";
$sql=" SELECT * FROM fac_venta WHERE  (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF') $filtroNOanuladas $filtroSede GROUP BY id_vendedor";
$rs=$linkPDO->query($sql);
$porcentaje_venta=0;
while($row=$rs->fetch())
{

$codigoSede=$row["nit"];
$IDvendedor=$row["id_vendedor"];
$TOT=$row["tot"];
$INICIAL=$row["entrega"];



$MARCA="HONDA";
//echo "NUM_MOTOS[$IDvendedor][$MARCA]  =>".$NUM_MOTOS[$IDvendedor][$MARCA]." /// METAS_SEDES[$codigoSede][$MARCA]=>". $METAS_SEDES[$codigoSede][$MARCA]  ." <br>";

//echo "NUM_MOTOS[$IDvendedor][HERO]  =>".$NUM_MOTOS[$IDvendedor]["HERO"]." /// METAS_SEDES[$codigoSede][$MARCA]=>". $METAS_SEDES[$codigoSede]["HERO"]  ." <br>";
if($NUM_MOTOS[$IDvendedor][$MARCA] >= $METAS_SEDES[$codigoSede][$MARCA]  && $NUM_MOTOS[$IDvendedor]["HERO"] >= $METAS_SEDES[$codigoSede]["HERO"]){
$NOMINAS_METAS[$IDvendedor][$MARCA] =$NUM_MOTOS[$IDvendedor][$MARCA]*	$BONO_SEDES[$codigoSede][$MARCA];
}

$MARCA="HERO";
if($NUM_MOTOS[$IDvendedor][$MARCA] >= $METAS_SEDES[$codigoSede][$MARCA]  && $NUM_MOTOS[$IDvendedor]["HONDA"] >= $METAS_SEDES[$codigoSede]["HONDA"]){
$NOMINAS_METAS[$IDvendedor][$MARCA] =$NUM_MOTOS[$IDvendedor][$MARCA]*	$BONO_SEDES[$codigoSede][$MARCA];
}

$MARCA="AIIMA";
if($NUM_MOTOS[$IDvendedor]["HONDA"] >= $METAS_SEDES[$codigoSede]["HONDA"]  && $NUM_MOTOS[$IDvendedor]["HERO"] >= $METAS_SEDES[$codigoSede]["HERO"]){
$NOMINAS_METAS[$IDvendedor][$MARCA] =$NUM_MOTOS[$IDvendedor][$MARCA]*	$BONO_SEDES[$codigoSede][$MARCA];
}

 
}


/// NOMINA X CURSO
$rs=$linkPDO->query("SELECT b.id_usu,a.id_vendedor,nomina_1 FROM fac_venta a INNER JOIN usuarios b ON a.id_vendedor=b.id_usu WHERE   (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' ) $filtroNOanuladas $filtroSede GROUP BY a.id_vendedor" );
while($row=$rs->fetch()){
$IDvendedor=$row["id_vendedor"];

if($row["nomina_1"]==1){
$NOMINAS_CURSO[$IDvendedor]=($NUM_MOTOS[$IDvendedor]["HONDA"]+$NUM_MOTOS[$IDvendedor]["HERO"]+$NUM_MOTOS[$IDvendedor]["AIIMA"])* $MODULES["VALOR_CURSO_NOMINA"] ;
}
}
?>

<!DOCTYPE html  >
<html  >
<head>
<?php require_once("HEADER_UK.php"); ?>
 <style type="text/css" media="print">
/* ISO Paper Size */
@page {
  size: A4 landscape;
   
}

/* Size in mm */    
@page {
  size: 100mm 200mm landscape;
}

/* Size in inches */    
@page {
  size: 4in 6in landscape;
}

@media print{    
    div{
        background-color: #FFFFFF;
		font-size:xx-small;
    }
}
</style>
 

</head>

<body >
<div style=" top:0cm; width:30.5cm;    " class="uk-width-7-10 uk-container-center">
<table align="center" width="100%">
<thead>
<tr>
<td>
<?php echo $PUBLICIDAD2 ?></td>
<td valign="top">
<p align="left" style="font-size:14px;">
<span align="center" style="font-size:24px;"><B>Nomina Ventas</B></span>
</p>
</td>

</tr>
</thead>
</table>
Fecha: <?PHP echo $hoy ?>

<table align="center" width="100%">
<tr style="font-size:24px; font-weight:bold;">
<td>
Desde: <?PHP echo $_SESSION['fechaI'] ?>
</td>
<td> Hasta: <?PHP echo $_SESSION['fechaF'] ?>
</td>
</tr>
</table>
</td>
</tr>

</table>
<?php



//$total_vendedores=tot_nomina_motos($fechaI,$fechaF,$codSuc,"","","",$clase,$fab);



?>

<p align="left" style="font-size:16px;">
<table align="center"  frame="box" rules="cols" cellspacing="0" cellpadding="0"  class="uk-table uk-table-striped" >
<thead>
<tr class="uk-text-large uk-text-bold uk-text-center uk-block-secondary uk-contrast">
<td>Asesor</td><td colspan="4">Honda</td><td colspan="4">Hero</td><td colspan="4">AIIMA</td><td>Curso</td><td>Bono</td><td>N&oacute;mina</td>
</tr>
<tr>
<td> </td>
<td>#</td><td>Comisi&oacute;n</td><td>Tot Ventas</td><td>Metas</td>
<td>#</td><td>Comisi&oacute;n</td> <td>Tot Ventas</td><td>Metas</td>
<td>#</td><td>Comisi&oacute;n</td><td>Tot Ventas</td><td>Metas</td>
<td></td><td></td><td>N&oacute;mina</td>
</tr>
</thead>
<tbody>
<?php
$rs=$linkPDO->query("SELECT $cols from fac_venta WHERE   (DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' ) $filtroNOanuladas $filtroSede GROUP BY id_vendedor" );
$totVendedores=0;
$totNominaUni=0;
$TOT_PAGO_NOMINA=0;
while($row=$rs->fetch())
{
$codigoSede=$row["nit"];
$IDvendedor=$row["id_vendedor"];
$MARCA=$row["marca_moto"];
$nomVende=ucwords(strtolower(htmlentities($row["vendedor"], ENT_QUOTES,"$CHAR_SET")));
$totNominaUni=0;
 




$totVendedores+=$TOT_VENTAS[$IDvendedor]["HONDA"]+$TOT_VENTAS[$IDvendedor]["HERO"]+$TOT_VENTAS[$IDvendedor]["AIIMA"];

$totNominaUni=$NOMINAS_VENTA[$IDvendedor]["HONDA"]+$NOMINAS_VENTA[$IDvendedor]["HERO"]+$NOMINAS_VENTA[$IDvendedor]["AIIMA"]+$TOT_BONOS_VENTAS[$IDvendedor];
$totNominaUni+=$NOMINAS_METAS[$IDvendedor]["HONDA"]+$NOMINAS_METAS[$IDvendedor]["HERO"]+$NOMINAS_METAS[$IDvendedor]["AIIMA"]+$NOMINAS_CURSO[$IDvendedor];
$TOT_PAGO_NOMINA+=$totNominaUni;
?>
<tr>

<td><?php echo "$nomVende";?></td>
<td><?php echo $NUM_MOTOS[$IDvendedor]["HONDA"];?></td>
<td><?php echo money2($NOMINAS_VENTA[$IDvendedor]["HONDA"]);?></td>
<td><?php echo money2($TOT_VENTAS[$IDvendedor]["HONDA"]);?></td>
<td><?php echo money2($NOMINAS_METAS[$IDvendedor]["HONDA"]);?></td>

<td><?php echo $NUM_MOTOS[$IDvendedor]["HERO"];?></td>
<td><?php echo money2($NOMINAS_VENTA[$IDvendedor]["HERO"]);?></td>
<td><?php echo money2($TOT_VENTAS[$IDvendedor]["HERO"]);?></td>
<td><?php echo money2($NOMINAS_METAS[$IDvendedor]["HERO"]);?></td>

<td><?php echo $NUM_MOTOS[$IDvendedor]["AIIMA"];?></td>
<td><?php echo money2($NOMINAS_VENTA[$IDvendedor]["AIIMA"]);?></td>
<td><?php echo money2($TOT_VENTAS[$IDvendedor]["AIIMA"]);?></td>
<td><?php echo money2($NOMINAS_METAS[$IDvendedor]["AIIMA"]);?></td>
<td><?php echo money2($NOMINAS_CURSO[$IDvendedor]);?></td>
<td><?php echo money2($TOT_BONOS_VENTAS[$IDvendedor]);?></td>
<td><?php echo money2($totNominaUni);?></td>
</tr>
<?php

 
	
}

?>
</tbody>
<tfoot>
<tr style="font-size:20px; font-weight:bold;" class="uk-block-secondary uk-contrast">
<th colspan="4">Total N&oacute;mina </th><th colspan="4"><?php echo money3($TOT_PAGO_NOMINA) ?></th>
</tr>
</tfoot>
</table>
 
<BR /><BR /><BR />
<hr align="center" width="100%" />
<!--
<table width="100%" cellpadding="4" style="font-size:18px;">
<tr>
<td>C./Coordinador:
<br />
<p align="center">________________________</p>
<?php echo ""?>
</td>
<td>
Jefe venta POS:
<br />
<p align="center">________________________</p>
<?php echo ""?>
</td>
<td >
Contador:
<br />
<p align="center">________________________</p>
<?php echo ""?>
</td>
</tr>
</table>
-->
<div id="imp"  align="center">
    <input name="hid" type="hidden" value="<%=dim%>" id="Nart" />
    <input  type="button" value="Imprimir" name="boton" onMouseDown="javascript:imprimir();" />
</div> 


</div>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER"; ?>"></script> 
<?php require_once("FOOTER_UK.php"); ?>
<script language="javascript1.5" type="text/javascript">
function imprimir(){
$('#imp').css('visibility','hidden');
window.print();
$('#imp').css('visibility','visible');
};
</script>
</body>
</html>