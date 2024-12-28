<?php
require_once("../../Conexxx.php");

$num_fac=r('n_fac_ven');
$pre=r('prefijo');
$hash=r('hashFacVen');

require '../../vendor/autoload.php';


use JonnyW\PhantomJs\Client;

$client = Client::getInstance();

$client->getEngine()->setPath('/home/u155514936/public_html/facturacion/bin/phantomjs');
//$client->getEngine()->setPath('../bin/phantomjs.exe');
	
	

	$width  = 840;
    $height = 2400;
    $top    = 0;
    $left   = 0;
    
    /** 
     * @see JonnyW\PhantomJs\Http\CaptureRequest
     **/
	$request = $client->getMessageFactory()->createPdfRequest("http://$hostName/verFacturaElec.php?t=1&codSu=$codSuc&prefijo=$pre&n_fac_ven=$num_fac", 'GET');
    //$request = $client->getMessageFactory()->createCaptureRequest("http://$hostName/verFacturaElec.php?t=1&codSu=$codSuc&prefijo=$pre&n_fac_ven=$num_fac", 'GET');
	//echo "http://127.0.0.1/motos/imp_fac_taller.php?nf=$num_fac&num_ot=$num_fac&nit=$codSuc";
    $request->setOutputFile('/home/u155514936/public_html/facturacion/Imagenes/FE_copias/file_0'.$codSuc.'.pdf');
    /*$request->setViewportSize($width, $height);
    $request->setCaptureDimensions($width, $height, $top, $left);*/
	$request->setFormat('A4');
    $request->setOrientation('landscape');
    $request->setMargin('1cm');

    /** 
     * @see JonnyW\PhantomJs\Http\Response 
     **/
    $response = $client->getMessageFactory()->createResponse();
	

    // Send the request
    $client->send($request, $response);
	
	echo "1";
?>