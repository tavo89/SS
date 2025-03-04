<?php
require_once("Conexxx.php");
$fechaI=$_SESSION['fechaI'];
$fechaF=$_SESSION['fechaF'];
$NOMBRE_INFORME="COMPRAS DETALLADAS ";
$url=thisURL();
$boton=r("boton");
if($boton=="MS EXCEL"){excel("Cartera Clientes");}
//excel("Cartera Clientes");
$horaI=s("horaI");
$horaF=s("horaF");
$CodCajero=s("cod_caja_arq");
$base_iva16=0;
$iva16=0;
$base_iva5=0;
$iva5=0;
$excentas=0;
$base_iva16B=0;
$iva16B=0;
$base_iva5B=0;
$iva5B=0;
$excentasB=0;
$ult_fac=0;
$pri_fac=0;
$DCTO=0;
$tot_comprobantes=0;

$tot_fac_taller=0;
$tot_fac_inspecciones=0;
$tot_fac_otros_talleres=0;
$tot_fac_mostrador=0;

$tot_fac_contado=0;
$tot_fac_credito=0;
$tot_fac_tarjeta_credito=0;
$tot_fac_cheque=0;
$InteresesCreditos=0;

$TOTAL=0;




//***********************************************************************************************************************************************************************
//***********************************************************************************************************************************************************************
//***********************************************************************************************************************************************************************

 
?>

<!DOCTYPE html PUBLIC >
<html xmlns:v="urn:schemas-microsoft-com:vml"
xmlns:o="urn:schemas-microsoft-com:office:office"
xmlns:x="urn:schemas-microsoft-com:office:excel"
xmlns="http://www.w3.org/TR/REC-html40">
<head>
<link href="font-awesome/css/font-awesome.css" rel="stylesheet">
<link href="css/animate.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<link href="css/bootstrap.min.css" rel="stylesheet">
    
<meta http-equiv=Content-Type content="text/html; charset=<?php echo $CHAR_SET ?>">
<meta name=ProgId content=Excel.Sheet>
<meta name=Generator content="Microsoft Excel 11">

<title><?php echo "$NOMBRE_INFORME";?></title>

<?php require_once("IMP_HEADER.php"); ?>



</head>

<body style="font-size:12px">
<div class="Section1" style=" top:0cm; width:21.5cm; height:27.9cm; position:absolute;">
<table align="center" width="100%">
<tr>

<td valign="top" colspan="3">
<p align="left" style="font-size:12px;">
<span align="center" style=" font-size:24px"><B>INFORME DETALLADO COMPRAS</B></span>
</p>
</td>

</tr>
</table>
Fecha: <?PHP echo $hoy ?>
<br>
<table align="left" width="">
<tr style="font-size:24px; font-weight:bold;">
<td >
Desde: <?PHP echo $_SESSION['fechaI'] ?>
</td>
<td > Hasta: <?PHP echo $_SESSION['fechaF'] ?>
</td>
</tr>
 
</table>


<hr align="center" width="100%">
<table frame="box" rules="all" cellpadding="5" cellspacing="3" width="100%" class="display dataTable table table-striped table-bordered table-hover tablaDataTables">
<thead>
<tr style="font-size:12px; ">
<th width="60">Fecha</th>
<th>#Fac.</th>
<th width="120px" colspan="">Cliente</th>
<th>C.C/NIT</th>
<th>SUB 19%</th>
<th>IVA 19%</th>
<?php if($fechaI<"2017-01-31"){?> 
<th>SUB 16%</th>
<th>IVA 16%</th>
<?php } ?> 
<th>SUB 5%</th>
<th>IVA 5%</th>
<th>Exento</th>
<th>Total</th>
 

</tr>
</thead>
<tbody>

<?php
$filtroCerradas=" AND estado='CERRADA'";

$descuento="costo*(dcto/100)";
$unidades="(unidades_fraccion+(cant*fraccion))/fraccion";
$DCTO="ROUND(SUM( ( $unidades  )*($descuento))) as DCTO";
$IVA="ROUND(SUM( ($unidades  )*(costo - ($descuento))*(iva/100))) as IVA";
$stot="(costo*( $unidades ))";
$dcto="(( $unidades  )*($descuento))";
$Ivaflete="flete*0.19";
$iva="( ($unidades  )*(costo - ($descuento))*(iva/100) )";
$impoConsumo="( ($unidades  )*(costo - ($descuento))*(impuesto_consumo/100) )";
$TOT="ROUND(SUM(  $stot  - $dcto) ) ";
$TOT_FLETE="SUM(flete) as FLETE";


$excento_art="(select $TOT from art_fac_com where art_fac_com.num_fac_com=fac_com.num_fac_com and iva=0 and art_fac_com.cod_su=fac_com.cod_su AND art_fac_com.nit_pro=fac_com.nit_pro  )";


$sub16_art="(select $TOT from art_fac_com where art_fac_com.num_fac_com=fac_com.num_fac_com and iva=16 and art_fac_com.cod_su=fac_com.cod_su AND art_fac_com.nit_pro=fac_com.nit_pro )";


$sub05_art="(select $TOT from art_fac_com 
             where art_fac_com.num_fac_com=fac_com.num_fac_com 
             and iva=5 
             and art_fac_com.cod_su=fac_com.cod_su 
             AND art_fac_com.nit_pro=fac_com.nit_pro )";

$sub10_art="(select $TOT from art_fac_com where art_fac_com.num_fac_com=fac_com.num_fac_com and iva=10 and art_fac_com.cod_su=fac_com.cod_su AND art_fac_com.nit_pro=fac_com.nit_pro )";

$sub19_art="(select $TOT from art_fac_com where art_fac_com.num_fac_com=fac_com.num_fac_com and iva=19 and art_fac_com.cod_su=fac_com.cod_su AND art_fac_com.nit_pro=fac_com.nit_pro )";




$cols="nit_pro,
nit_pro as pre,
nom_pro,estado, 
num_fac_com,
SUM(subtot) subtot,
iva,SUM(IFNULL(($excento_art),0)) as excento,
SUM(tot) tot, 
TIME(fecha) as hora, DATE(fecha) as fe,
SUM($sub16_art) as sub16,
SUM($sub05_art) as sub05,
SUM($sub10_art) as sub10,
SUM($sub19_art) as sub19, 
COUNT(*) AS nf";

 


$sql="SELECT $cols FROM fac_com, sucursal 
      WHERE sucursal.cod_su =$codSuc 
      AND fac_com.cod_su = sucursal.cod_su  
      AND DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' $filtroCerradas GROUP BY  num_fac_com   ORDER BY fecha";
//echo "$sql";
$rs=$linkPDO->query($sql);
$tot_mostrador=0;
$tot_fac_mostrador=0;
$TOTAL=0;


$tot_tarjetaDeb=0;
$tot_contado=0;
$tot_Credito=0;
$tot_tarjetaCre=0;
$tot_cheque=0;

$FOOT_SUB0=0;
$FOOT_IVA0=0;

$FOOT_SUB5=0;
$FOOT_IVA5=0;

$FOOT_SUB16=0;
$FOOT_IVA16=0;

$FOOT_SUB19=0;
$FOOT_IVA19=0;
while($row=$rs->fetch())
{
	$pre=$row['pre'];
	$num_fac=$row['num_fac_com'];
	$subTot=$row['subtot'];
	$IVA=$row['iva'];
	$excento=$row['excento'];
	$total=$row['tot'];

	$HORA=$row['hora'];
	$fecha=$row['fe'];
	$tipo_venta="";
	$tipoCli="";
	$nomCli="$row[nom_pro]";
	$formaPago="";
	$idCli=$row["nit_pro"];
	
	$printAnticipo="";

	
	
	
	
	
	
	$TOTAL+=$row['tot'];
	
	$SUB19=round($row['sub19']);
	$IVA19=round(($row['sub19'])*0.19);
	
	$SUB10=round($row['sub10'] );
	$IVA10=round(($row['sub10'])*0.10);
	
	$SUB16=round($row['sub16']);
	$IVA16=round(($row['sub16'])*0.16);
	$SUB05=round($row['sub05']);
	$IVA05=round(($row['sub05'])*0.05);
	
	$FOOT_SUB0+=$excento;
	 
	$FOOT_SUB5+=$SUB05;
	$FOOT_IVA5+=$IVA05;
	
	$FOOT_SUB16+=$SUB16;
	$FOOT_IVA16+=$IVA16;
	
	$FOOT_SUB19+=$SUB19;
	$FOOT_IVA19+=$IVA19;
	 
	
 
	$NF=$row["nf"];
	
	
	if(1 ){
	?>
    <tr>
	<td><?php echo $fecha ?></td>
    <td><?php echo " $num_fac"; /*$pre*/ ?></td>
    <td colspan=""><?php echo $nomCli ?></td>
        <td colspan=""><?php echo $idCli ?></td>

        <td><?php echo ($SUB19) ?></td>
    <td><?php echo ($IVA19) ?></td>
     <?php if($fechaI<"2017-01-31"){?>   
    <td><?php echo ($SUB16) ?></td>
    <td><?php echo ($IVA16) ?></td>
   <?php }?>
    <td><?php echo ($SUB05) ?></td>
    <td><?php echo ($IVA05) ?></td>
   
    <td><?php echo ($excento) ?></td>
    <td><?php echo round($total) ?></td>
 
    
    </tr>
    
    <?php
	}
	
}


?>
<tfoot>
<td></td>
<td></td>
<td></td>
<td></td>
<td><?php echo money3($FOOT_SUB19);?></td>
<td><?php echo money3($FOOT_IVA19);?></td>
<?php if($fechaI<"2017-01-31"){?>
    <td></td>
    <td></td>
<?php }?>

<td><?php echo money3($FOOT_SUB5);?></td>
<td><?php echo money3( $FOOT_IVA5);?></td>
<td><?php echo money3( $FOOT_SUB0);?></td>
<td><?php echo money3( $TOTAL);?></td>

</tfoot>
</tbody>
    <?php
      
    ?>
    
    <style>
        #asd11 span{
            color:rgba(240,240,240,1);
            height:40px;
            font-size: 12px;
            font-weight: bold;
        }
        #asd11{
            background:rgba(100,100,100,1);
           /* display:none;*/
        }
    </style>
        

</table>

<div id="imp"  align="center">
    <input name="hid" type="hidden" value="<%=dim%>" id="Nart" />
 <!--   <input  type="button" value="Imprimir" name="boton" onMouseDown="javascript:imprimir();" />
    <input type="button" value="MS EXCEL" name="boton" onclick="location.assign('<?php echo "$url?boton=MS EXCEL" ?>')" /> -->
</div> 
</div>
<?php require_once("FOOTER_UK.php"); ?>
<script language="javascript1.5" type="text/javascript" src="JS/jquery_browser.js"></script>
<script src="JS/bootstrap.min.js"></script>
<script src="JS/plugins/dataTables/datatables.min.js"></script>
<script language="javascript1.5" type="text/javascript">
$(document).ready(function(){
	var rangoFecha='<?php if(!empty($fechaI)){echo "$fechaI a $fechaF";}?>';
	$('.tablaDataTables').DataTable({
				/*columnDefs: [
        { type: 'numeric-comma', targets: 2 }],*/
			language: {url: 'locales/es.json'},
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    { extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excelHtml5', title: '<?php echo "$NOMBRE_INFORME";?> '+rangoFecha,footer:true, customize: function ( xlsx ){
                var sheet = xlsx.xl.worksheets['sheet1.xml'];
 
               
				
				// Loop over the cells in column `F`
                $('row c[r^="F"]', sheet).each( function () {
                    // Get the value and strip the non numeric characters
                    if ( $('is t', this).text().replace(/[^\d]/g, '') * 1 >= 500000 ) {
                        $(this).attr( 's', '20' );
                    }
                });
            }},
                    {extend: 'pdf', title: '<?php echo "$NOMBRE_INFORME ";?> '+rangoFecha ,footer:true,orientation:'landscape',pageSize:'letter',
					customize: function ( doc ) {
                // Splice the image in after the header, but before the table
				<?php if($fechaI<"2017-01-31")
				{
                    echo "doc.content[1].table.widths = [ '10%', '15%', '10%', '8%', '7%', '7%', '7%', '7%', '7%', '10%', '15%', '10%'];";
                }
				else {
                    echo "doc.content[1].table.widths = [ '10%', '15%', '8%', '8%', '10%', '10%', '7%', '10%', '15%', '10%'];";
                    } ?> 
                doc.content.splice( 1, 0, {
                    margin: [ 0, 0, 0, 12 ],
                    alignment: 'center'
                    
                } );
                // Data URL generated by http://dataurl.net/#dataurlmaker
            }},

                    {extend: 'print',footer:true,
                     customize: function (win){
                            $(win.document.body).addClass('white-bg');
                            $(win.document.body).css('font-size', '10px');

                            $(win.document.body).find('table')
                                    .addClass('compact')
                                    .css('font-size', 'inherit');
                    }
                    }
                ]

            });
	
});
</script>
</body>
</html>