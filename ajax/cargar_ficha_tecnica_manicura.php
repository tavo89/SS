<?php
require_once("../Conexxx.php");

header("Content-type:application/json"); /** JSON Pretty print **/

$idFicha = limpiarcampo($_GET['ficha']);

$queryFicha = "SELECT * FROM ficha_tecnica_manicura WHERE num_ficha=$idFicha AND nit=$codSuc";
$rsFicha = $linkPDO->query($queryFicha);

$fichaArray = []; /** Se declara el array para que el json encode devuelva un objeto json vacio cuando no hay datos, en lugar de un error **/

    $fichaArray[0] = $rsFicha->fetch();


$queryCitas = "SELECT * FROM art_ficha_manicura_citas WHERE num_ficha=$idFicha AND nit=$codSuc ORDER BY id ASC";
$rsCitas = $linkPDO->query($queryCitas);

    $fichaArray[1] = []; /** Se declara el array para que el json encode devuelva un objeto json vacio cuando no hay datos, en lugar de un error **/

$b = 0;
while( $citasArrayTemp = $rsCitas->fetch() ){
    $fichaArray[1][$b] = $citasArrayTemp;
    $b++;
}

echo json_encode($fichaArray,JSON_PRETTY_PRINT);
