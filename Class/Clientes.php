<?php

class Clientes {
    private $idCliente;
    private $DbConnect;
    private $Sucursal;
    private $FechaHoy;

    public function __construct($idCliente='222222222222')
    {
        global $linkPDO,$codSuc;

        $this->idCliente = $idCliente;
        $this->DbConnect = $linkPDO;
        $this->Sucursal  = $codSuc;
        $this->FechaHoy = new DateTime(gmdate("Y-m-d",hora_local(-5)));
    }

    public function defineMoraCliente(String $fechaPago, String $fechaCobro, String $tipoServicio='ANUAL') {
        $ultimoPago = new DateTime($fechaPago);
        $anioUltimoPago = $ultimoPago->format('Y');
        $fechaCobro = new DateTime($fechaCobro);
        $currentYear = $this->FechaHoy->format('Y');
        $ETA_cobro  = $fechaCobro->diff($this->FechaHoy);

        if($tipoServicio=='ANUAL') {
            $estadoPago = ($ETA_cobro->days>30)? 1:0;
        } else {
            $estadoPago = ($ETA_cobro->days>7)? 1:0;
        }

        return array('estadoPago'=>$estadoPago, 'diasRestantes'=>$ETA_cobro->days);
        

    }

    public function getListaDeudoresPlanes(){
        
        $arrayIdClientes = array();

        $sql ="SELECT * FROM servicio_internet_planes";
        $resultSet= $this->DbConnect->query($sql);
        while($row = $resultSet->fetch()){
            $estadoPago = $this->defineMoraCliente($row['ultimoPago'],$row['fechaCobro'], $row['tipoPlan']);
            if(!$estadoPago['estadoPago']) {
                $arrayIdClientes[] = $row['id_cli'];
            } 
        }
        
        return array_unique($arrayIdClientes);
    }

    public function setActualizaPagoPlan($numFactura,$prefijo,$codSuc,$operacion){

        $sql = "SELECT * FROM serv_fac_ven WHERE num_fac_ven='$numFactura' AND prefijo='$prefijo' AND cod_su='$codSuc'";
        $resultSet= $this->DbConnect->query($sql);
        while($row = $resultSet->fetch()){
            if($operacion=='anular'){
                $update ="UPDATE servicio_internet_planes SET 
                          ultimoPago = DATE_ADD(ultimoPago, INTERVAL -1 YEAR), 
                          fechaCobro = DATE_ADD(fechaCobro, INTERVAL -1 YEAR) 
                          WHERE id=".$row['id_serv'];
            } else {
                $update ="UPDATE servicio_internet_planes SET 
                          ultimoPago = '".$this->FechaHoy->format('Y-m-d')."', 
                          fechaCobro = DATE_ADD(fechaCobro, INTERVAL 1 YEAR) 
                          WHERE id=".$row['id_serv'];
            }
            
            $this->DbConnect->exec($update);
        }
        
    }

    public function getEstadoCuentaServicios() {
        $contadorClientes  =0;
        $sumaCobroTotal    =0;

        $sumaPendientes    =0;
        $contadorPendientes=0;

        $sumaPagos         =0;
        $contadorPagos     =0;

        $sql ="SELECT * FROM servicio_internet_planes";
        $resultSet= $this->DbConnect->query($sql);
        while($row = $resultSet->fetch()){
            $contadorClientes++;
            $sumaCobroTotal+= $row['precioplan'];
            $estadoPago = $this->defineMoraCliente($row['ultimoPago'],$row['fechaCobro'], $row['tipoPlan']);

            if($estadoPago['estadoPago']) {
                $sumaPagos+= $row['precioplan'];
                $contadorPagos++;
            } else {
                $sumaPendientes+= $row['precioplan'];
                $contadorPendientes++;
            }

        }

        // pendiente crear funcion para imprimir, dejar esta para consultar unicamente

        echo '<div class="uk-panel uk-panel-box">';
        echo '<div class="uk-badge uk-badge-success uk-text-large">PAGOS</div> $'.number_format($sumaPagos)." ($contadorPagos/$contadorClientes)";
        echo '&nbsp;&nbsp;&nbsp;&nbsp;';
        echo '<div class="uk-badge uk-badge-warning uk-text-large">DEBEN</div> $'.number_format($sumaPendientes)." ($contadorPendientes/$contadorClientes)";
        echo '&nbsp;&nbsp;&nbsp;&nbsp;';
        echo '<div class="uk-badge uk-badge-notification uk-text-large">TOTAL</div> $'.number_format($sumaCobroTotal)." ($contadorClientes)";
        echo '</div>';

    }

    public function getDataCliente() {
        $datosCliente = array('nombre'=>'','direccion'=>'','telefono'=>'','ciudad'=>'');
        $sql      = "SELECT nombre,dir,tel,cuidad FROM usuarios WHERE id_usu='".$this->idCliente."' LIMIT 1";
        $resultSet= $this->DbConnect->query($sql);
        if($row = $resultSet->fetch()){
            $datosCliente['nombre']    = $row['nombre'];
            $datosCliente['direccion'] = $row['dir'];
            $datosCliente['telefono']  = $row['tel'];
            $datosCliente['ciudad']    = $row['cuidad'];
        }

        return $datosCliente;
    }

    public function getServiciosSuscripcion() {
        $sql = "SELECT `nombre_servicio`,`nombreSucursal`,`subDominioApp`,`precioplan`,`fechaCobro`,`ultimoPago`, tipoPlan
                FROM servicio_internet_planes WHERE id_cli='".$this->idCliente."'";
        $resultSet = $this->DbConnect->query($sql);

        $htmlList='<ul>';
        $rowNUmber =0;
        while($row = $resultSet->fetch()){
            $rowNUmber++;

            $estadoPago = $this->defineMoraCliente($row['ultimoPago'],$row['fechaCobro'], $row['tipoPlan']);
            $botonEstadoPago = ($estadoPago['estadoPago']) ? '<div class="uk-badge uk-badge-success uk-text-large">PAGO</div>':'<div class="uk-badge uk-badge-warning uk-text-large">DEBE</div>';
            $cobroString = $botonEstadoPago.' <b>'.$estadoPago['diasRestantes']."</b> Días restantes";
            
            $infoSucursal = $rowNUmber<=1?$row['subDominioApp']:$row['nombreSucursal'];

            $htmlList.="<li>$cobroString/<b>$row[nombre_servicio]</b>/$".number_format($row['precioplan'])."/$row[fechaCobro]/$infoSucursal";
            $htmlList.='</li>';
        }
        $htmlList.='</ul>';

        return $htmlList;
    }
}
