<?php
require_once("Conexxx.php");
$fechaI=$_SESSION['fechaI'];
$fechaF=$_SESSION['fechaF'];

$CONT=0;
$T_DEB=0;
$T_CRE=0;
$CRED=0;  

$TOTAL=0;

$TIPO_INF=1;
$inversion=0;
if(!empty($_REQUEST["TIPO_INF"]))$TIPO_INF=r("TIPO_INF");

if(!empty($_REQUEST["inversion"]))$inversion=r("inversion");

if($inversion==1){$FILTRO_INVERSIONES=$FILTRO_INVERSIONES2;}


?>

<!DOCTYPE html  >
<html  >
<head>
<?php //require("HEADER_UK.php"); ?>
<link href="font-awesome/css/font-awesome.css" rel="stylesheet">
<link href="css/animate.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">
<link href="css/plugins/dataTables/datatables.min.css" rel="stylesheet">
<link href="css/bootstrap.min.css" rel="stylesheet">

<meta http-equiv=Content-Type content="text/html; charset=<?php echo $CHAR_SET ?>">

<?php require_once("IMP_HEADER.php"); ?>
</head>

<body style="font-size:12px">
<div style="width:21.5cm; height:27.9cm; padding-left:10px;">
<table align="center" width="100%">
<tr>

<td valign="top">
<p align="left" style="font-size:12px;">
<span align="center" style=" font-size:24px"><B>INFORME EGRESOS</B></span></p>
</td>

</tr>
</table>

<table align="center" width="100%">
<tr style="font-size:24px; font-weight:bold;">
<td >
Desde: <?PHP echo $_SESSION['fechaI'] ?>
</td>
<td> Hasta: <?PHP echo $_SESSION['fechaF'] ?>
</td>
</tr>
<tr>
<td colspan="3">
<table cellspacing="0px" cellpadding="0px">

</table>
</td>
</tr>
</table>


 
<table align="center" width="100%" cellpadding="0" cellspacing="0" style="font-size:10px" id="TabClientes" class="display dataTable table table-striped table-bordered table-hover tablaDataTables" rules="all" frame="box">
<thead  >
<tr style="font-size:9px;" class="uk-block-secondary uk-contrast ">
<?php if($TIPO_INF==1)echo "<th>#</th><th>Fecha</th>";
$FORMAS_PAGO[]=0;
$FORMAS_PAGO_TEMP[][]=0;
$limit=count($FP_egresos);
	for($i=0;$i<$limit;$i++){
		
	$FORMAS_PAGO[$FP_egresos[$i]]=0;
	$FORMAS_PAGO_TEMP[$FP_egresos[$i]] =0;	
		
	}

?>

<th align="center">Tipo</th>

<th>C.C/NIT</th>

<th>Beneficiario</th>
<th>Concepto</th>
 <th>Direcci&oacute;n</th>
<th align="">Total</th>
<th> FTE</th>
<!--
<th> ICA</th>
-->
<?php
for($i=0;$i<$limit;$i++){
$val=strtoupper($FP_egresos[$i]);
$val2=mb_strimwidth($val, 0, 5, ".");

echo "<th>$val2</th>";	

}
?>
</tr>
</thead>
<tbody>
<?php
 
////////////////////////////////////////////// EGRESOS //////////////////////////////////////////////////
$filtroAnula="anulado!='ANULADO' OR (DATE(fecha_anula)!=DATE(fecha) AND  anulado='ANULADO')";
$filtroAnula="anulado!='ANULADO' ";

 
$GROUP_BY="GROUP BY num_com";
if($TIPO_INF==2)$GROUP_BY="GROUP BY tipo_gasto,id_beneficiario";
$sql="select *,SUM(valor-(r_fte+r_ica)) as valor2,SUM(r_fte) as r_fte, SUM(r_ica) as r_ica,DATE(fecha) as fe2 FROM comp_egreso WHERE DATE(fecha)>='$fechaI' AND DATE(fecha)<='$fechaF' AND cod_su='$codSuc' AND ($filtroAnula) $FILTRO_INVERSIONES $GROUP_BY ORDER BY valor DESC";
//echo "$sql";
$rs=$linkPDO->query($sql);
$TOT_CONT=0;
$TOT_CONT_CAJA=0;
$TOT_TDEB=0;
$TOT_TCRE=0;
$TOT_CRE=0;
$TOT_CHE=0;

$R_FTE=0;
$R_ICA=0;

$SUM_FTE=0;
while($row=$rs->fetch())
{
	$total_contado_fac=0;
	$total_cheque_fac=0;
	$total_contado_caja_fac=0;
	$total_Tdeb_fac=0;
	$total_Tcre_fac=0;
	$total_credito_fac=0;
	
	$fechaComp=$row["fe2"];
	$num_fac=$row['num_com'];
	$subTotArt=$row['valor2']*1;
	$cant=1;
	$val_uni=$row['valor2']*1;
	$val_fac=$row['valor2']*1;
	$r_fte=$row['r_fte'];
	
	$SUM_FTE+=$r_fte;
	
	$r_ica=$row['r_ica'];
	$val_tot=$val_uni*$cant;
	$tipo_venta=$row['tipo_pago'];	
	$TOTAL+=$row['valor2'];
	$FORMAS_PAGO_TEMP[$tipo_venta]=0;
	$R_FTE+=$r_fte;
	$R_ICA+=$r_ica;
	
	
	$nomCli=$row['beneficiario'];
	$idBene=$row["id_beneficiario"];

	$tipo="".$row['tipo_gasto'];
	$desArt=$row['concepto'];
	
	$FORMAS_PAGO_TEMP[$tipo_venta]=$val_fac;
	$FORMAS_PAGO[$tipo_venta]+=$val_fac;
	
	$Sql2="SELECT * FROM usuarios WHERE id_usu='$idBene' ";
	$rs2=$linkPDO->query($Sql2);
	$row2=$rs2->fetch();
	$dir=$row2["dir"];
				
	
	$serialFac=$row["serial_fac_com"];
			$comText="";
			
			if(!empty($serialFac)){
				
				$Sql1="SELECT * FROM fac_com WHERE serial_fac_com=$serialFac AND cod_su=$codSuc";
				$rs1=$linkPDO->query($Sql1);
				$row1=$rs1->fetch();
				
				$numFacCom=$row1["num_fac_com"];
				$nomPro=$row1["nom_pro"];
				$nitPro=$row1["nit_pro"];
				$feFacCom=$row1["fecha"];
				$comText="<b>Pago factura: $numFacCom del $feFacCom</b>";
				
				
				
			}
	
	?>
    <tr>

    <?php if($TIPO_INF==1)echo "<td>$num_fac</td><th>$fechaComp</th>"; ?>
    <td><?php echo "$tipo" ?></td>
    
    <td><?php echo $idBene ?></td>
    
    <td><?php echo $nomCli." $comText" ?></td>
    <td><?php echo $desArt ?></td>
     <td><?php echo $dir ?></td>
    <!--
    <td><?php echo $desArt ?></td>
    <td><?php echo $nomCli ?></td>

   -->
    <td align="right"><?php echo money_dt($val_tot) ?></td>
    <td align="right"><?php echo money_dt($r_fte) ?></td>
    <!--
    <td align="right"><?php echo money_dt($r_ica) ?></td>
   -->
   
   <?php
for($i=0;$i<$limit;$i++){
$val=money_dt($FORMAS_PAGO_TEMP[$FP_egresos[$i]]);
if($FP_egresos[$i]!=$tipo_venta)$val=0;
echo "<td align=\"right\">$val</td>";	

}
?>

    </tr>
    
    <?php
	
	
}//////////////////////////////////// FIN EGRESOS ////////////////////////

?>
<tfoot>
<tr style="font-size:16px;">
<?php 
$SUM_FTE=money_dt($SUM_FTE);

if($TIPO_INF==1){echo "<td></td><td></td><td></td><td></td><td></td><td></td><td></td>"; }else {echo "<td></td><td></td><td></td><td></td><td></td>";}?>
<td ></td>
<td ><?php echo $SUM_FTE; ?></td>

<?php
for($i=0;$i<$limit;$i++){
$val=money_dt($FORMAS_PAGO[$FP_egresos[$i]]);

echo "<td align=\"right\">$val</td>";	

}
?>
</tr>

 </tfoot>
</tbody>
</table>
<hr align="center" width="100%" />

</div>
<?php require_once("FOOTER_UK.php"); ?>
<script language="javascript1.5" type="text/javascript" src="JS/jquery_browser.js"></script>
<script src="JS/bootstrap.min.js"></script>
<script src="JS/plugins/dataTables/datatables.min.js"></script>
<script language="javascript1.5" type="text/javascript">
$(document).ready(function()
	{
		
		var rangoFecha='<?php if(!empty($fechaI)){echo "$fechaI a $fechaF";}?>';
		$('.tablaDataTables').DataTable({
			language: {url: 'locales/es.json'},
                dom: '<"html5buttons"B>lTfgitp',
                buttons: [
                    { extend: 'copy'},
                    {extend: 'csv'},
                    {extend: 'excelHtml5', title: '<?php echo "$NOM_NEGOCIO"; ?> Informe Egresos '+rangoFecha,footer:true, customize: function ( xlsx ){
                var sheet = xlsx.xl.worksheets['sheet1.xml'];
				//$( 'xf[numFmtId=9]', xlsx.xl['styles.xml'] ).attr( 10 );
 
                // jQuery selector to add a border
                $('row c[r*="10"]', sheet).attr( 's', '25' );
				
				
				// Loop over the cells in column `F`
                $('row c[r^="F"]', sheet).each( function () {
                    // Get the value and strip the non numeric characters
                    if ( $('is t', this).text().replace(/[^\d]/g, '') * 1 >= 500000 ) {
                        $(this).attr( 's', '20' );
                    }
                });
            }},
                    {extend: 'pdf', title: '<?php echo "$NOM_NEGOCIO"; ?> Informe Egresos '+rangoFecha,footer:true,orientation:'landscape',pageSize:'letter',
					customize: function ( doc ) {
                // Splice the image in after the header, but before the table
				//                               							  1      2     3       4     5      6       7     8      9    
				<?php if($TIPO_INF==1){echo "doc.content[1].table.widths = [ '5%', '10%', '15%', '15%', '10%', '10%', '12%', '12%', '12%'];";}
						else echo "doc.content[1].table.widths = [ '25%', '10%', '15%', '15%',  '12%', '12%', '12%'];";
				
				?>
				
                doc.content.splice( 1, 0, {
                    margin: [ 0, 0, 0, 12 ],
                    alignment: 'center',
                    image: '<?php echo $url_a; ?>'
                } );
                // Data URL generated by http://dataurl.net/#dataurlmaker
            }},

                    {extend: 'print',footer:true,title: 'Informe Egresos '+rangoFecha,
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
	}
);
function imprimir(){
$('#imp').css('visibility','hidden');
window.print();
$('#imp').css('visibility','visible');
};
</script>
</body>
</html>