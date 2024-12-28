<?php
require '../../vendor/autoload.php';
//require_once("../../Conexxx.php");

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

try {
    ob_start();
	$req = '?t=1&codSu=1&prefijo=SETP&n_fac_ven=990000016';
	$_REQUEST['t'] = 1;
	$_REQUEST['codSu'] = 1;
	$_REQUEST['prefijo'] = 'SETP';
	$_REQUEST['n_fac_ven'] = 990000016;	
	
    include  "/home/u155514936/public_html/facturacion/verFacturaElec.php";
    $content = ob_get_clean();

    $html2pdf = new Html2Pdf('P', 'A4', 'fr');
    $html2pdf->writeHTML($content);
    $html2pdf->output('example01.pdf');
} catch (Html2PdfException $e) {
    $html2pdf->clean();

    $formatter = new ExceptionFormatter($e);
    echo $formatter->getHtmlMessage();
}
?>