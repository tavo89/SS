<?php
require_once("Conexxx.php");
if(isset($_SESSION['url_back']))header("location: ".$_SESSION['url_back']);

?>