<?php
include_once('Conexxx.php');

$t=r("t");
if($MODULES["modulo_planes_internet"]==1 && $t!=2 && $t!=3){header("location: imp_fac_ven_custom.php");}

$num_fac=$_SESSION['n_fac_ven'];
$pre=$_SESSION['prefijo'];
$hash=s('hashFacVen');

$datosCliente = array();
$sql ="SELECT * FROM fac_venta WHERE num_fac_ven='$num_fac' AND prefijo='$pre' AND nit=$codSuc";
$rs=$linkPDO->query($sql);

if($row=$rs->fetch()){
	$datosCliente['nombre'] = $row['nom_cli'];
	$datosCliente['id'] = $row['id_cli'];
}
//fac_servicios_mensuales
//if($fac_servicios_mensuales==1 && $t!=2 && $t!=3){header("location: imp_fac_ven_custom.php");}
?>
<!DOCTYPE html PUBLIC >
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $CHAR_SET ?>" />
<title><?php echo "Factura $pre$num_fac ".$datosCliente['nombre']."" ?></title>
<link href="JS/fac_ven.css?<?php echo $LAST_VER ?>" rel="stylesheet" type="text/css" />
<?php 

include_once("IMP_HEADER.php"); 

?>
<style type="text/css">
@media print
{
      .page-break  { display:block; page-break-before:always; }

}
.pie_pagina_nanimo{

font-family:"Arial Black", Gadget, sans-serif;
width:100%;

text-size-adjust:none;
font-size:5px;
line-height:7px;
}

.vertical-text {
	transform: rotate(270deg);
	transform-origin:59% 84%;
	-webkit-transform:matrix(0, -1, 1, 0, -95, 86);
-moz-transform:matrix(0, -1, 1, 0, -95, 86);
-o-transform:matrix(0, -1, 1, 0, -95, 86);
-ms-transform:matrix(0, -1, 1, 0, -95, 86);
transform:matrix(0, -1, 1, 0, -95, 86);
}
</style>

</head>

<body >



<?php 



imprimir_fac($num_fac,$pre,$hash);
 
include_once("FOOTER_UK.php"); 

?>
<!--
<script type="text/javascript" src="PLUG-INS/jsPDF-master/dist/jspdf.debug.js"></script>
<script type="text/javascript" src="PLUG-INS/jsPDF-master/examples/js/jquery/jquery-ui-1.8.17.custom.min.js"></script>


<script src="PLUG-INS/es6-promise-master/lib/es6-promise.auto.js"></script>
<script src="PLUG-INS/jsPDF-master/dist/jspdf.min.js"></script>
<script src="PLUG-INS/jsPDF-master/libs/html2canvas/dist/html2canvas.js"></script>
<script src="PLUG-INS/jsPDF-master/libs/html2pdf.js"></script>
-->

<script src="PLUG-INS/html2pdf-master/dist/html2pdf.bundle.min.js"></script>

<script language="javascript1.5" type="text/javascript">
$(document).ready(
	function(){

	//imprimir();
	
	}
	
);
function printPDF(){
var element = document.getElementById('imprimirFacturaPDF');
//html2pdf(element);
var opt = {
  margin:       0,
  filename:     '<?php echo "Factura Venta $pre$num_fac ";?>.pdf',
  image:        { type: 'jpeg', quality: 2 },
  html2canvas:  { scale: 1 },
  jsPDF:        { unit: 'in', format: 'letter', orientation: 'portrait' }
};

html2pdf().from(element).set(opt).save();
};

function demoTwoPageDocument() {
	var doc = new jsPDF();
	doc.text(20, 20, 'Hello world!');
	doc.text(20, 30, 'This is client-side Javascript, pumping out a PDF.');
	doc.addPage();
	doc.text(20, 20, 'Do you like that?');
	
	// Save the PDF
	doc.save('Test.pdf');
}
function demoFromHTML() {
	var pdf = new jsPDF('p', 'pt', 'letter')

	// source can be HTML-formatted string, or a reference
	// to an actual DOM element from which the text will be scraped.
	, source = $('#imprimirFacturaPDF')[0]

	// we support special element handlers. Register them with jQuery-style 
	// ID selector for either ID or node name. ("#iAmID", "div", "span" etc.)
	// There is no support for any other type of selectors 
	// (class, of compound) at this time.
	, specialElementHandlers = {
		// element with id of "bypass" - jQuery style selector
		'#bypassme': function(element, renderer){
			// true = "handled elsewhere, bypass text extraction"
			return true
		},
		'.hide': function(element, renderer){
      		// true = "handled elsewhere, bypass text extraction"
			return true
		}
	}

	margins = {
      top: 80,
      bottom: 60,
      left: 40,
      width: 522
    };
    // all coords and widths are in jsPDF instance's declared units
    // 'inches' in this case
    pdf.fromHTML(
    	source // HTML string or DOM elem ref.
    	, margins.left // x coord
    	, margins.top // y coord
    	, {
    		'width': margins.width // max width of content on PDF
    		, 'elementHandlers': specialElementHandlers
    	},
    	function (dispose) {
    	  // dispose: object with X, Y of the last line add to the PDF 
    	  //          this allow the insertion of new lines after html
          pdf.save('Test.pdf');
        },
    	margins
    )
}
</script>
</body>
</html>