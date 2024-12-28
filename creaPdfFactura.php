<?php
require 'vendor/autoload.php';
ini_set('error_reporting', E_ALL);

ob_end_clean();
ob_start();
include  "verFacturaElecPDF.php";
$html = ob_get_contents();
ob_end_clean();

//echo $html;


use Dompdf\Dompdf;
use Dompdf\Options;

$options = new Options();
$options->set('isRemoteEnabled',true);  
//define("DOMPDF_ENABLE_REMOTE", false);
//$options->setIsHtml5ParserEnabled(true);
//$options->setIsRemoteEnabled(true);
$dompdf = new Dompdf($options);
$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
//$dompdf->setPaper('letter', 'portrait');

// Render the HTML as PDF
$dompdf->render();
// Output the generated PDF to Browser
$dompdf->stream();
//$output = $dompdf->output();
//file_put_contents('Factura.pdf', $output);
//unlink("Brochure.pdf");
?>
