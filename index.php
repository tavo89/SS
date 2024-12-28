<?php
//ini_set('session.gc_maxlifetime', 1800);
//session_set_cookie_params(1800);
$SUB_DOMINIOS_PARTNER=array("127.0.0.1/SS/",//0
"testserver.nanimosoft.com",//1
"alimentosylicores.nanimosoft.com",//2
"habitatt360.nanimosoft.com",//3
"bar56grados.nanimosoft.com",
"acol.nanimosoft.com",
"idafabra.nanimosoft.com",
"sgb.nanimosoft.com",
"edsas.nanimosoft.com",
"multinegocios.nanimosoft.com");
$hostName=$_SERVER['HTTP_HOST'];
$showPartner="";

if(in_array($hostName,$SUB_DOMINIOS_PARTNER)){$showPartner=1;}
 
//if($hostName=="testserver.nanimosoft.com" || $hostName== "alimentosylicores.nanimosoft.com"){$showPartner=1;}


session_start();
//header("Content-Type: text/html; charset=UTF-8");
include_once("DB.php");
function hora_local($zona_horaria = 0) {
    if ($zona_horaria > -12.1 and $zona_horaria < 12.1) {
        $hora_local = time() + ($zona_horaria * 3600);
        return $hora_local;
    }
    return 'error';
};
function quitar_session($dato = array()) {
    foreach ($dato as $die) {
        unset($_SESSION[$die]);
    }
};

function limpiarcampo($string){
global $conex;

//$string = $conex->real_escape_string($string);
$liberate =$string;

$liberate = str_replace("'","-", $liberate);
$liberate = str_replace("<","&lt;", $liberate);
$liberate = str_replace(">","&gt;", $liberate);
$liberate = str_replace('"',"&quot;", $liberate);
$liberate = str_replace("\\","", $liberate);

return trim(limp2($liberate));
};

function eco_alert($msj) {
    echo "<script type='text/javascript'>simplePopUp('$msj');waitAndReload();</script>";
};

function limp2($str)
{
	$n = preg_split("[|]",$str);
   $i = 0;
    $h = "";
   for($i = 0; $i < count($n); $i++) {
      $h = $h.$n[$i];
      }
   return $h;
};





$usu="";
$cla="";
$boton="";




if(isset($_REQUEST['boton']))$boton=$_REQUEST['boton'];

if($boton=="Cerrar")
{

	session_destroy();

}

if($boton=="Aceptar" && isset($usu)&&isset($cla)){
	$usu=limpiarcampo($_REQUEST['username']);
	$cla=limpiarcampo($_REQUEST['password']);
	$ipClient=limpiarcampo($_REQUEST['IP']);
	$_SESSION['ipClient']=$ipClient;

$qry="SELECT usu_login.id_usu,
			 des,
			 rol_lv 
	 FROM tipo_usu LEFT JOIN usu_login 
	 ON usu_login.id_usu=tipo_usu.id_usu 
	 WHERE 	usu='$usu' 
	  		AND cla='$cla'  
	  		AND estado='ACTIVO' ";
//echo $qry;die();
$row=array();
$rs=$linkPDO->query($qry );
//echo $qry;die();

if($row=$rs->fetch()){
/*echo "IF-><pre>".$qry;
print_r($row);
die();*/
	$id=$row['id_usu'];
	$tipoUsu=$row['des'];
	$lv=$row['rol_lv'];
	$_SESSION['tipo_usu']=$tipoUsu;
	$_SESSION['id_usu']=$id;
	$_SESSION['rol_lv']=$lv;
	$_SESSION["see_warn_ban_list"]=1;
	$_SESSION["see_warn_inv"]=1;


$qry="SELECT  sucursal.*,
             (SELECT departamento FROM departamento WHERE departamento.id_dep=sucursal.id_dep) AS departamento,
			 (SELECT municipio FROM municipio WHERE municipio.id_mun=sucursal.id_mun)          AS municipio,
			 usuarios.nombre,
			 usuarios.firma_op,usuarios.tel,usuarios.cod_caja as cc,
			 usuarios.fecha_crea 
	  FROM usuarios,sucursal
      WHERE usuarios.id_usu='$id' AND usuarios.cod_su=sucursal.cod_su";
//echo $qry;die;
$rs=$linkPDO->query($qry );
if($row=$rs->fetch()){

include('variablesSistema.php');

$sql="SELECT * FROM x_config WHERE cod_su=1";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch()){
$_SESSION[$row["des_config"]]=$row["val"];

}
/*
$mesActual=gmdate("m",hora_local(-5));
$yearActual=gmdate("Y",hora_local(-5));

$mesActual=$mesActual*1;
if($mesActual<12){$mesActual++;}
else {$mesActual=1;$yearActual++;}

if($mesActual<10){$mesActual="0".$mesActual;}

$diaLIM="0".$_SESSION["dia_limite_pago_facturas"];
if($_SESSION["dia_limite_pago_facturas"]>=10){$diaLIM=$_SESSION["dia_limite_pago_facturas"];}

$diaSUS="0".$_SESSION["dia_suspension"];
if($_SESSION["dia_suspension"]>=10){$diaSUS=$_SESSION["dia_suspension"];}

$_SESSION["dia_limite_pago_facturas"]="$yearActual-$mesActual-$diaLIM";
$_SESSION["dia_suspension"]="$yearActual-$mesActual-$diaSUS";
*/
// -----------------------------  VARIABLES VARIAS :V -------------------------------


// Grupos difusion SMS, select options

$sql="select * FROM sms_grupos_difusion WHERE cod_su=".$_SESSION['cod_su'];
$rs=$linkPDO-> query($sql);
$SelOpt1="<option value=\'0\'>TODOS</option>";
$SelOpt2="<option value='0'>TODOS</option>";
$listaGrupos[0]="TODOS";
while($row=$rs->fetch()){

$ID=$row["id"];
$grupo=$row["nombre_grupo"];
$listaGrupos[$ID]=$grupo;
$SelOpt1.="<option value=\'$ID\'>$grupo</option>";
$SelOpt2.="<option value=$ID>$grupo</option>";

}

$_SESSION["sms_grupos_difusion"]=$SelOpt1;
$_SESSION["sms_grupos_difusion2"]=$SelOpt2;
$_SESSION["sms_grupos_difusion_lista"]=$listaGrupos;

//////////////////////// SELECT RUTAS //////////////////////////7

$sql="select * FROM servicio_rutas WHERE cod_su=".$_SESSION['cod_su'];
$rs=$linkPDO-> query($sql);
$SelOpt1="<option value=\'0\'>Sin Ruta</option>";
$SelOpt2="<option value='-1'>TODOS</option><option value='0'>Sin Ruta</option>";
$listaRutas[-1]="TODOS";
$listaRutas[0]="Sin Ruta";
while($row=$rs->fetch()){

$ID=$row["id"];
$ruta=$row["nombre_ruta"];
$listaRutas[$ID]=$ruta;
$SelOpt1.="<option value=\'$ID\'>$ruta</option>";
$SelOpt2.="<option value=$ID>$ruta</option>";
}

$_SESSION["servicios_rutas"]=$SelOpt1;
$_SESSION["servicios_rutas2"]=$SelOpt2;

$_SESSION["servicios_rutas_lista"]=$listaRutas;


//////////////////////// SELECT NODOS //////////////////////////7

$sql="select * FROM servicio_nodos_internet WHERE cod_su=".$_SESSION['cod_su'];
$rs=$linkPDO-> query($sql);
$SelOpt1="<option value=\'0\'>Sin Nodo</option>";
$SelOpt2="<option value='-1'>TODOS</option><option value='0'>Sin Nodo</option>";
$listaNodos[-1]="TODOS";
$listaNodos[0]="Sin Nodo";
while($row=$rs->fetch()){

$ID=$row["id"];
$nodo=$row["nombre_nodo"];
$listaNodos[$ID]=$nodo;
$SelOpt1.="<option value=\'$ID\'>$nodo</option>";
$SelOpt2.="<option value=$ID>$nodo</option>";
}

$_SESSION["servicios_nodos"]=$SelOpt1;
$_SESSION["servicios_nodos2"]=$SelOpt2;

$_SESSION["servicios_nodos_lista"]=$listaNodos;


// ------------------------------ FIN VARS VARIAS -----------------------------------

// variables sistema, tabla x_config
$sql="SELECT * FROM sucursal ";
$rs=$linkPDO->query($sql);
$i=0;

while($row=$rs->fetch()){
$sucursales[$row["cod_su"]]=$row["nombre_su"];

$i++;
}
$_SESSION['sucursales']=$sucursales;






if($_SESSION['tipo_usu']=="Administrador"){
//header("location: dashboard_1.php");
//echo "tipo_usu: ".$_SESSION['tipo_usu'];die();
//echo "IP : : : $ipClient ".$_REQUEST["IP"];
header("location: centro.php");
}
else {
	header("location: centro.php");
	}
}


	//echo $_SESSION['departamento']."-".$_SESSION['municipio']."<br>".$_SESSION['nom_su'];
	//eco_alert("Encontrado!");

}
else eco_alert("Usuario no encontrado...");


}



//echo $qry;

?>
<!DOCTYPE html  >
<html  >
<head>
<link rel="shortcut icon" href="Imagenes/logoICO.ico" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Smart Selling 4.9.5</title>
<!--
<link href="css/login/style.css" rel="stylesheet" type="text/css" />

-->
    <!-- Bootstrap core CSS
<link href="css/cityBG.css" rel="stylesheet" type="text/css" />
     


<link href="font-awesome/css/font-awesome.css" rel="stylesheet">
<link href="css/animate.css" rel="stylesheet">
<link href="css/style.css" rel="stylesheet">

-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <link rel="stylesheet" type="text/css" href="css/iofrm-style.css">
    <link rel="stylesheet" type="text/css" href="css/iofrm-theme9.css">







<script src="JS/jquery-2.1.1.js"></script>
<script language='javascript' src="JS/UNIVERSALES.js?<?php echo "$LAST_VER" ?>"></script>
<script language="javascript1.5" type="text/javascript" src="JS/jquery_browser.js"></script>


<script type="text/javascript" language="javascript1.5">
$(document).ready(function() {$('#username').focus();	});
// NOTE: window.RTCPeerConnection is "not a constructor" in FF22/23
var idBox='ip_box';
var idBox2='ip_box2';
var RTCPeerConnection = /*window.RTCPeerConnection ||*/ window.webkitRTCPeerConnection || window.mozRTCPeerConnection;

if (RTCPeerConnection) (function () {
    var rtc = new RTCPeerConnection({iceServers:[]});
    if (1 || window.mozRTCPeerConnection) {      // FF [and now Chrome!] needs a channel/stream to proceed
        rtc.createDataChannel('', {reliable:false});
    };

    rtc.onicecandidate = function (evt) {
        // convert the candidate to SDP so we can run it through our general parser
        // see https://twitter.com/lancestout/status/525796175425720320 for details
        if (evt.candidate) grepSDP("a="+evt.candidate.candidate);
    };
    rtc.createOffer(function (offerDesc) {
        grepSDP(offerDesc.sdp);
        rtc.setLocalDescription(offerDesc);
    }, function (e) { console.warn("offer failed", e); });


    var addrs = Object.create(null);
    addrs["0.0.0.0"] = false;
    function updateDisplay(newAddr) {
        if (newAddr in addrs) return;
        else addrs[newAddr] = true;
        var displayAddrs = Object.keys(addrs).filter(function (k) { return addrs[k]; });
        document.getElementById(idBox).textContent = displayAddrs.join(" or perhaps ") || "n/a";
		$('#'+idBox2).prop('value',displayAddrs.join(" or perhaps ") || "n/a");
    }

    function grepSDP(sdp) {
        var hosts = [];
        sdp.split('\r\n').forEach(function (line) { // c.f. http://tools.ietf.org/html/rfc4566#page-39
            if (~line.indexOf("a=candidate")) {     // http://tools.ietf.org/html/rfc4566#section-5.13
                var parts = line.split(' '),        // http://tools.ietf.org/html/rfc5245#section-15.1
                    addr = parts[4],
                    type = parts[7];
                if (type === 'host') updateDisplay(addr);
            } else if (~line.indexOf("c=")) {       // http://tools.ietf.org/html/rfc4566#section-5.7
                var parts = line.split(' '),
                    addr = parts[2];
                updateDisplay(addr);
            }
        });
    }
})(); else {
    document.getElementById(idBox).innerHTML = "<code>ifconfig | grep inet | grep -v inet6 | cut -d\" \" -f2 | tail -n1</code>";
    document.getElementById(idBox).nextSibling.textContent = "In Chrome and Firefox your IP should display automatically, by the power of WebRTCskull.";
}
</script>

<style>
 

.footer{
bottom: -1px;

    left: 0;
    right: 0;
    width: 100%;
    position: fixed;
    z-index: 1000;
}
body {
	/*
background: rgb(12,135,176);
background: -moz-linear-gradient(left,  rgba(12,135,176,1) 0%, rgba(8,100,130,1) 100%);
background: -webkit-gradient(linear, left top, right top, color-stop(0%,rgba(12,135,176,1)), color-stop(100%,rgba(8,100,130,1)));
background: -webkit-linear-gradient(left,  rgba(12,135,176,1) 0%,rgba(8,100,130,1) 100%);
background: -o-linear-gradient(left,  rgba(12,135,176,1) 0%,rgba(8,100,130,1) 100%);
background: -ms-linear-gradient(left,  rgba(12,135,176,1) 0%,rgba(8,100,130,1) 100%);
background: linear-gradient(to right,  rgba(12,135,176,1) 0%,rgba(8,100,130,1) 100%);
filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#0c87b0', endColorstr='#086482',GradientType=1 );
color: #333;
 */
 
}
 
.logoA{width:100px; left:0;}
.logoB{width:100px; }
.logoC{width:100px; }
.logoBanner{width:300px;}
 


/* Small devices (landscape phones, 576px and up)*/
@media (max-width: 576px) { 
.logoBanner{width:300px; }

.logoA{width:100px; }
.logoB{width:100px; }
.logoC{width:100px; }
}

/* Medium devices (tablets, 768px and up)*/
@media (max-width: 768px) {

.logoBanner{width:300px; }
.logoA{width:150px; }
.logoB{width:150px; }
.logoC{width:150px; }

 }
 
 @media (max-width: 992px) {

.logoBanner{width:300px; }
.logoA{width:100px; }
.logoB{width:100px; }
.logoC{width:100px; }

 }

/*Extra large devices (large desktops, 1200px and up)*/
@media (min-width: 1200px) {

.logoBanner{width:400px; }

.logoA{width:200px; }
.logoB{width:200px; }
.logoC{width:200px; }


 }
</style>

</head>

<body  class="console">
<div class="form-body">
        <div class="row">
            <div class="img-holder">
                <div class="info-holder">
                    <h3>Software Online de gesti&oacute;n administrativa y facturaci&oacute;n electr&oacute;nica </h3>

                    <img src="images/graphic5.svg" alt="">
                    <div class="website-logo-inside">
                             
                                <div >
                                   <?php if($showPartner==1){?>
<table align="center">
<tr>
<td>
 
      <img src="Imagenes/logoEM.png" width="" class="logoA " />
</td>
    
 
   
</tr>
</table>
<?php }?> 
                                      
                                </div>
                            
                        </div>
                </div>
            </div>
            <div class="form-holder">
                <div class="form-content">
                    <div class="form-items">
                        <div class="website-logo-inside">
                             
                                <div >
                                
                                    <img class="logoBanner"  src="images/logo-image.png" alt="">
                                </div>
                            
                        </div>
                        <div class="page-links">
                            <!-- <a href="login9.html" class="active">Iniciar sesi&oacute; </a> <a href="register9.html">Register</a> -->
                        </div>
                        <form class="form-1  " action="index.php" method="post"  >
                        <input type="hidden" id="ip_box2" value="" name="IP">
                        
                            <input class="form-control" type="text" name="username" id="username" placeholder="Usuario" required>
                            <input class="form-control" type="password" name="password" placeholder="Contrase&ntilde;a" required>
                            <div class="form-button">
                              
                                <button type="submit" name="boton" value="Aceptar" class="ibtn btn-primary block full-width m-b">Entrar </button> 
                            </div>
                        </form>
                       <!--  <div class="other-links">
                            <span>Or login with</span><a href="#">Facebook</a><a href="#">Google</a><a href="#">Linkedin</a>
                        </div> -->
                        <h1 id="ip_box" style="visibility:hidden; font-size:9px;"> </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>


<!-- <script language="javascript1.5" type="text/javascript" src="JS/cityBG.js"></script>-->
<script language="javascript1.5" type="text/javascript" >
$("#username").focus();

</script>
</body>

</html>
