<?php
require '../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
require_once("../../Conexxx.php");
//----------VARIABLES POST----
$num_fac=r('n_fac_ven');
$prefijo=r('prefijo');
$hash=r('hashFacVen');
$serial_fac = r('serial_fac');
$codSuc = r('codSuc');
$mailDestino = r('mailDestino');
$idCliente =r('idCliente');
$mailCopia   = $email_sucursal;
$Subject ='Factura Electronica #'.$prefijo.''.$num_fac.' ';

$sql="SELECT  AttachedDocument,XmlFileName FROM fac_respuestas_dian WHERE serial_fac ='$serial_fac' ";
$rs = $linkPDO->query($sql);
$xmlBody = '';
$XmlFileName = $prefijo.''.$num_fac;
if($row=$rs->fetch())
{
    $xmlBody = base64_decode($row['AttachedDocument']);
	$XmlFileName = $row['XmlFileName'];
	
}

$sql = "SELECT mail_cli FROM usuarios WHERE id_usu = '$idCliente' AND mail_cli!=''";
$rs = $linkPDO->query($sql);
if($row=$rs->fetch())
{
	$mailDestino = $row['mail_cli'];
}
$mailDestino = limpiaEspaciosVacios($mailDestino);
$urlVars="t=1&n_fac_ven=$num_fac&prefijo=$prefijo&hashFacVen=$hash&codSuc=$codSuc";
$linkQR = "http://$_SERVER[HTTP_HOST]/verFacturaElec.php?$urlVars";
$QR= getQRcode("$linkQR");
$imgQR='<img src="'.$QR.'" width="100" height="100" alt="">';

$bodyMail = '<a href="'.$linkQR.'" style="font-size:35px">>>HACER CLICK EN ESTE MENSAJE PARA VER LA FACTURA<<<</a>'
            .'<br> o Escanee el siguiente QR:<br>'.$imgQR;
sleep(2);
//-----------------------------
$mail = new PHPMailer;
$mail->SMTPDebug = 0;
$mail->isSMTP();
$mail->IsHTML(true); 
$mail->Host = 'smtp.hostinger.com';
$mail->Port = 587;
$mail->SMTPAuth = true;
$mail->Username = 'facturacion_dian@nanimosoft.com';
$mail->Password = 'Cronos9173';
$mail->setFrom('facturacion_dian@nanimosoft.com', $NOM_NEGOCIO);
$mail->addReplyTo($mailCopia, 'Your Name');
$mail->addAddress($mailDestino, 'Receiver Name');
$mail->Subject = $Subject;
//$mail->msgHTML(file_get_contents('message.html'), __DIR__);
$mail->AddAttachment(
    "/home/u155514936/public_html/facturacion/ajax/correos/$XmlFileName.xml",
    $XmlFileName.'.xml',
	'base64',
    'application/octet-stream'
);
/*$mail->AddAttachment(
    '/home/u155514936/public_html/facturacion/ajax/FE/No. MFD 21.pdf',
    'No. MFD 21.pdf',
	'base64',
    'application/octet-stream'
);*/
$mail->Body = $bodyMail;
//$mail->addAttachment('test.txt');
if (!$mail->send()) {
    echo 'Mailer Error: ' . $mail->ErrorInfo.", mail: $mailDestino";
	unlink($XmlFileName.'.xml');
} else {
    echo 'Correo enviado con &Eacute;xito!. Destino: '.$mailDestino;
	//unlink($XmlFileName.'.xml');
}
?>