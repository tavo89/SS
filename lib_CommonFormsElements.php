<?php

class lib_CommonFormsElements
{
    // property declaration
    public $var = 'a default value';
    private $DbConnect;
    private $Sucursal;

    public function __construct()
    {
        global $linkPDO,$codSuc;

        $this->DbConnect = $linkPDO;
        $this->Sucursal  = $codSuc;
    }

    // method declaration
    public function impuestoSaludable($onChangeFunction,$formValue,$idIndex) {
		$html="<select name=\"impSaludable$idIndex\" id=\"impSaludable$idIndex\" onChange=\"$onChangeFunction\" class=\"art$idIndex\">";
		$html.="<option selected value=\"$formValue\"> $formValue%</option>";
    $html.="<option value=\"0\">0%</option>";
		$html.="<option value=\"15\">15%</option>";
		$html.="</select>";

		return $html;
    }

    public function botonesCategoriasMesas() {

        $sql="SELECT * FROM clases_favoritos WHERE cod_su=$this->Sucursal ORDER BY nombre_clase";
				$rs=$this->DbConnect->query($sql);

				if($row=$rs->fetch()){
          echo '<tr>
          <td colspan="6" align="right">
          <div class="uk-grid">
          <div class="uk-width-1-1">
          <div class="uk-button-dropdown uk-dropdown-close" data-uk-dropdown>
          <span class="uk-button uk-button-primary">Menu x Categorias <i class="uk-icon-caret-down"></i></span>
          <div class="uk-dropdown uk-dropdown-small">
          <ul class="uk-nav uk-nav-dropdown">';
          $rs=$this->DbConnect->query($sql);
				  $Xi=0;
				  while($row=$rs->fetch()){
            $clase=$row["nombre_clase"];
				    echo "<input type=\"hidden\" id=\"clase_busq$Xi\" name=\"clase_busq$Xi\" value=\"$clase\">";
				    echo "<li><a class=\"\" href=\"#\" onClick=\"busq($('#clase_busq$Xi'));\">$clase</a></li>";
				
				    $Xi++;
				  }

          echo ' </ul>
                 </div>
                 </div>
                </td>
                </tr>';
      }
      $this->DbConnect = null;
    }

    public function valorPropinas($onChangeFunction,$formValue,$idIndex) {
      $html="<select name=\"propina$idIndex\" id=\"propina$idIndex\" onChange=\"$onChangeFunction\" class=\"uk-form $idIndex\">";
      $html.="<option selected value=\"$formValue\"> $formValue%</option>";
      $html.="<option value=\"0\">0%</option>";
      $html.="<option value=\"5\">5%</option>";
      $html.="<option value=\"7\">7%</option>";
      $html.="<option value=\"10\">10%</option>";
      $html.="</select>";
  
      return $html;
      }

    // 
    public function getListaServiciosSuscripcion() {
      

      $listaJS = "";
      $listaHtml="";

      $sql= "SELECT clasificacion,servicio FROM servicios WHERE clasificacion='SUBSCRIPCION'";
      $resultSet= $this->DbConnect->query($sql);
      $rs=$this->DbConnect->query($sql);
      
      $nombreServicio='';
      while($row=$rs->fetch()){
        $nombreServicio = $row['servicio'];
        $listaJS.="<option value=\'$nombreServicio\'>$nombreServicio</option>";
        $listaHtml.="<option value=\"$nombreServicio\">$nombreServicio</option>";
      }
      $this->DbConnect = null;

  
      return array('listaHtml'=>$listaHtml, 'listaJS'=>$listaJS);
      }



}