<?php
use Saia\core\DatabaseConnection;
use Saia\MesaAyuda\controllers\MesaAyudaController;
use Saia\MesaAyuda\formatos\mesa_ayuda\FtMesaAyuda;

$max_salida = 6;
$rootPath = $ruta = "";

while ($max_salida > 0) {
    if (is_file($ruta . "sw.js")) {
        $rootPath = $ruta;
    }
    $ruta .= "../";
    $max_salida--;
}

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

function filtroPadreSecundario(){
  $cadenaWhere = '';  
  if(@$_REQUEST["cod_padre"]){    
    $cadenaWhere = "a.cod_padre=" . $_REQUEST["cod_padre"];
  }
  
  return($cadenaWhere);
}
?>