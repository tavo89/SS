<?php
include_once("../DB.php");
session_start();
header("Content-Type: text/html; charset=ISO-8859-1");
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
    echo "<script type='text/javascript'>alert('$msj');</script>";
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
function valida_session()
{
global $BaseDatos,$HOST,$USU,$CLA,$conex;
global $linkPDO;

//echo "<br><span style='color:white'>BD:$BaseDatos, HOST: $HOST</span>";
	if(isset($_SESSION['id_usu'])&&isset($_SESSION['tipo_usu']))
	{
		$id=$_SESSION['id_usu'];
		$tipoUsu=$_SESSION['tipo_usu'];
		$qry="SELECT 
		      usu_login.usu,
			  usu_login.cla,
			  usu_login.id_usu 
			  FROM usu_login,tipo_usu,usuarios 
			  WHERE usuarios.id_usu='$id' AND usu_login.id_usu=tipo_usu.id_usu AND tipo_usu.des='$tipoUsu'";

		$rs=$linkPDO->query($qry);
		if($row=$rs->fetch())
		{

		}
		else {header("location: sesioncerrada.php");}

	}
	else {header("location: sesioncerrada.php");}

};

	$_SESSION['tipo_usu']=$_SESSION['tipo_usu'];
	$_SESSION['id_usu']=$_SESSION['id_usu'];
	$_SESSION['rol_lv']=$_SESSION['rol_lv'];
	$_SESSION["see_warn_ban_list"]=1;
	$_SESSION["see_warn_inv"]=1;

	$id=$_SESSION['id_usu'];
	$codSuc=$_SESSION['cod_su'];

valida_session();

	$qry="SELECT  sucursal.*,
             (SELECT departamento FROM departamento WHERE departamento.id_dep=sucursal.id_dep) AS departamento,
			 (SELECT municipio FROM municipio WHERE municipio.id_mun=sucursal.id_mun)          AS municipio,
			 usuarios.nombre,
			 usuarios.firma_op,usuarios.tel,usuarios.cod_caja as cc,
			 usuarios.fecha_crea 
	  FROM usuarios,sucursal
      WHERE sucursal.cod_su='$codSuc'
	  AND usuarios.id_usu='$id'  LIMIT 1";
//echo $qry;
$rs=$linkPDO->query($qry );
if($row=$rs->fetch()){

	include('../variablesSistema.php');

$sql="SELECT * FROM x_config WHERE cod_su=1";
$rs=$linkPDO->query($sql);
while($row=$rs->fetch()){

$_SESSION[$row["des_config"]]=$row["val"];

}


if($codSuc!=1){
    $_SESSION['url_LOGO_A']= $logoSucursalA;
	$_SESSION['url_LOGO_B']= $logoSucursalB;
}

}


$NOMSU=$_SESSION['nombre_su'];
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
echo "$NOMSU - $codSuc<br>";

?>
