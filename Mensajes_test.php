<?php
    $message = '';
    if (isset($_POST['message'])) {

        if (empty($_POST['msisdn']) || empty($_POST['message'])) {
            $message = 'All fields need to be filled in';
        } else {
            $url = 'http://api.labsmobile.com/get/send.php?';
            $url .= 'username=admin@interplustel.com&';
            $url .= 'password=PvJgMdN4bEqTjLv2zyWTIFX8WgzPdUFS&';
            $url .= 'msisdn=' . $_POST['msisdn'] . '&';
            $url .= 'message=' . $_POST['message'] . '&';

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
			
 
			
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

            $result = curl_exec($ch);
            curl_close($ch);

            $message = htmlentities('Message has been sent.<br />Details:' . "<br />" . $result);
        }
    }
?>
<html>
    <head>
        <title>SMS Example</title>
    </head>
    <body>
        <p><?php echo $message ?></p>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
            <p>MSISDN: <input type="text" value="" name="msisdn" /></p>
            <p>Message: <input type="text" value="" maxlength="160" name="message" /></p>
            <p><input type="submit" value="Send SMS" name="send" /></p>
        </form>
    </body>
</html>