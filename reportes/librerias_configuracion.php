<?php
$max_salida = 6;
$rootPath = $ruta = "";

while ($max_salida > 0) {
    if (is_file($ruta . "index.php")) {
        $rootPath = $ruta;
        break;
    }
    $ruta .= "../";
    $max_salida--;
}

use Saia\core\DatabaseConnection;
use Saia\MesaAyuda\controllers\MesaAyudaController;
use Saia\MesaAyuda\formatos\mesa_ayuda\FtMesaAyuda;

include_once $rootPath . "app/vendor/autoload.php";

function mostrarEnlaceSecundaria($idma_clasificacion,$nombre){
  $enviar = array();
  $enviar["cod_padre"] = $idma_clasificacion;
  
  $componenteSubClasificacion = DatabaseConnection::getQueryBuilder()
        ->select('a.idbusqueda_componente')
        ->from('busqueda_componente','a')
        ->join('a','busqueda','b','a.busqueda_idbusqueda=b.idbusqueda')
        ->where("a.nombre = 'clasificacion_secundaria_configuracion'")
        ->execute()->fetchAll();
  
  $params=http_build_query([
  'idbusqueda_componente'=>$componenteSubClasificacion[0]["idbusqueda_componente"],
  'variable_busqueda'=>'{"cod_padre":"'.$idma_clasificacion.'"}'
  ]);
  
  $url = "views/buzones/grilla.php?".$params;
  
  $cadenaRetorno .= '<div class="kenlace_saia" enlace="' . $url . '" conector="iframe" titulo="' . $nombre . '"><center><button class="btn btn-complete">' . $nombre . '</button></center></div>';
  
  return($cadenaRetorno);
}

function mostrarResponsableClasificacion($responsablesJson){
  $cadena = '';
  
  if($responsablesJson != 'responsables_json'){
    $responsablesArray = json_decode($responsablesJson,true);
    $datosResponsables = array();
    
    foreach($responsablesArray as $id => $valor){            
      $datosResponsables[] = $valor['nombre'];
    }

    $cadena = implode("<br />", $datosResponsables);
  }
  
  return($cadena);
}

function filtroPadreSecundario(){
  $cadenaWhere = '';  
  if(@$_REQUEST["cod_padre"]){    
    $cadenaWhere = "a.cod_padre=" . $_REQUEST["cod_padre"];
  }
  
  return($cadenaWhere);
}
?>