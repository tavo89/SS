<?php
/** Mail with attachment */
function mail_attachment($filename, $path, $mailto, $from_mail, $from_name, $replyto, $bcc, $subject, $message){    
    $uid = md5(uniqid(time()));
    $mime_boundary = "==Multipart_Boundary_x{$uid}x"; 

    $header = "From: ".$from_name." <".$from_mail.">\r\n";
    $header .= "Bcc: ".$bcc."\r\n";
    $header .= "Reply-To: ".$replyto."\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$mime_boundary."\"\r\n\r\n";
    $header .= "This is a multi-part message in MIME format.\r\n";
    $header .= "--".$mime_boundary."\r\n";
    $header .= "Content-type:text/html; charset=iso-8859-1\r\n";
    $header .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $header .= nl2br($message)."\r\n\r\n";
    $header .= "--".$mime_boundary."\r\n";

    foreach($filename as $k=>$v){

        $file = $path.$v;
        $file_size = filesize($file);
        $handle = fopen($file, "r");
        $content = fread($handle, $file_size);
        fclose($handle);
        $content = chunk_split(base64_encode($content));

        $header .= "Content-Type: application/octet-stream; name=\"".$v."\"\r\n"; // use different content types here
        $header .= "Content-Transfer-Encoding: base64\r\n";
        $header .= "Content-Disposition: attachment; filename=\"".$v."\"\r\n\r\n";
        $header .= $content."\r\n\r\n";
        $header .= "--".$mime_boundary."--"."\r\n";
    } 

    if (mail($mailto, $subject, "", $header)) {
        //echo "mail send ... OK"; // or use booleans here
        return true;
    } else {
        //echo "mail send ... ERROR!";
        return false;
    }
}
?>