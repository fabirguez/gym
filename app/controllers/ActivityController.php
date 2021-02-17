<?php

require_once MODELS_FOLDER.'ActivityModel.php';
/**
 * Controlador de las distintas actividades, horarios,etc del portal desde la que se pueden hacer las funciones que te permita tu rol.
 */
class ActivityController extends BaseController
{
    private $modelo;
    private $mensajes;

    public function __construct()
    {
        parent::__construct();
        $this->modelo = new ActivityModel();
        $this->mensajes = [];
    }

    public function addActividad()
    {
        if ($_SESSION['rol_id'] == 0) {
            $parametros = [
                    'tituloventana' => 'Registro de usuario by admin',
                    'datos' => null,
                    'mensajes' => [],
                 ];
            // Array asociativo que almacenará los mensajes de error que se generen por cada campo
            $errores = [];
            // Si se ha pulsado el botón guardar...
          if (isset($_POST) && !empty($_POST) && isset($_POST['submit'])) { // y hemos recibido las variables del formulario y éstas no están vacías...
            $nombre = filter_var($_POST['txtnombre'], FILTER_SANITIZE_STRING);
              $descripcion = filter_var($_POST['txtdescripcion'], FILTER_SANITIZE_STRING);
              $aforo = $_POST['txtaforo'];

              $filtrardatos = [
                'aforo' => $aforo,
            ];

              $errores = $this->modelo->filtraDatos($filtrardatos);

              $errores += $this->modelo->existeActividad($nombre);

              // Si no se han producido errores realizamos el registro del usuario

              if (count($errores) == 0) {
                  $resultModelo = $this->modelo->addActividad([
                   'nombre' => $nombre,
                   'aforo' => $aforo,
                   'descripcion' => $descripcion,
                ]);
                  if ($resultModelo['correcto']) :
                   $this->mensajes[] = [
                      'tipo' => 'success',
                      'mensaje' => 'La actividad se añadió correctamente!! :)',
                   ]; else :
                   $this->mensajes[] = [
                      'tipo' => 'danger',
                      'mensaje' => "La actividad no se pudo registrar!! :( <br />({$resultModelo['error']})",
                   ];
                  endif;
              } else {
                  foreach ($errores as $e) {
                      $this->mensajes[] = [
                        'tipo' => 'danger',
                        'mensaje' => $e,
                     ];
                  }
              }
          }

            $parametros = [
             'tituloventana' => 'Actividad nueva',
             'datos' => [
                'txtnombre' => isset($nombre) ? $nombre : '',
                   'txtdescripcion' => isset($descripcion) ? $descripcion : '',
                   'txtaforo' => isset($aforo) ? $aforo : '',
             ],
             'mensajes' => $this->mensajes,
          ];
            //Visualizamos la vista asociada al registro de usuarios
            $this->view->show('AddActividad', $parametros);
        } else {
            $parametros = [
                'tituloventana' => 'Prohibido el paso',
                'datos' => [],
                'mensajes' => $this->mensajes,
             ];
            $this->view->show('Prohibido', $parametros);
        }
    }

    public function delActividad()
    {
        if ($_SESSION['rol_id'] == 0) {
            // verificamos que hemos recibido los parámetros desde la vista de listado
            if (isset($_GET['id']) && (is_numeric($_GET['id']))) {
                $id = $_GET['id'];

                //Realizamos la operación de suprimir el usuario con el id=$id
                $resultModelo = $this->modelo->delActividad($id);
                //Analizamos el valor devuelto por el modelo para definir el mensaje a
                //mostrar en la vista listado
                if ($resultModelo['correcto']) :
                $this->mensajes[] = [
                   'tipo' => 'success',
                   'mensaje' => "Se eliminó correctamente la actividad $id",
                ]; else :
                $this->mensajes[] = [
                   'tipo' => 'danger',
                   'mensaje' => "La eliminación de la actividad no se realizó correctamente!! :( <br/>({$resultModelo['error']})",
                ];
                endif;
            } else { //Si no recibimos el valor del parámetro $id generamos el mensaje indicativo:
                $this->mensajes[] = [
                'tipo' => 'danger',
                'mensaje' => 'No se pudo acceder al id de la actividad a eliminar!! :(',
             ];
            }
            //Realizamos el listado de los usuarios
            $this->listadoActividades();
        } else {
            $parametros = [
                'tituloventana' => 'Prohibido el paso',
                'datos' => [],
                'mensajes' => $this->mensajes,
             ];
            $this->view->show('Prohibido', $parametros);
        }
    }

    public function actActividad()
    {
        // Array asociativo que almacenará los mensajes de error que se generen por cada campo
        $errores = [];

        if ($_SESSION['rol_id'] == 0) {
            // Inicializamos valores de los campos de texto

            $valnombre = '';
            $valdescripcion = '';
            $valaforo = 0;

            //dnd guardo el id!!!

            // Si se ha pulsado el botón actualizar...
      if (isset($_POST['submit'])) { //Realizamos la actualización con los datos existentes en los campos
          $id = $_POST['id'];
          $nuevonombre = filter_var($_POST['txtnombre'], FILTER_SANITIZE_STRING);
          $nuevodescripcion = filter_var($_POST['txtdescripcion'], FILTER_SANITIZE_STRING);
          $nuevoaforo = $_POST['txtaforo'];

          $filtrardatos = [
          'aforo' => $nuevoaforo,
      ];

          $errores = $this->modelo->filtraDatos($filtrardatos);
          if ($this->modelo->listaActividad($id)['datos']['nombre'] != $nuevonombre) {
              $errores += $this->modelo->existeActividad($nuevonombre);
          }

          //Ejecutamos la instrucción de actualización a la que le pasamos los valores
          if (count($errores) == 0) {
              $resultModelo = $this->modelo->actActividad([
                  'id' => $id,
                 'nombre' => $nombre,
                 'descripcion' => $descripcion,
                 'aforo' => $aforo,
              ]);
              //Analizamos cómo finalizó la operación de registro y generamos un mensaje
              //indicativo del estado correspondiente
              if ($resultModelo['correcto']) :
            //    $this->listado();
              $this->mensajes[] = [
                  'tipo' => 'success',
                  'mensaje' => 'La actividad se actualizó correctamente!! :)',
               ]; else :
               $this->mensajes[] = [
                  'tipo' => 'danger',
                  'mensaje' => "La actividad no pudo actualizarse!! :( <br/>({$resultModelo['error']})",
               ];
              endif;
          } else {
              foreach ($errores as $e) {
                  $this->mensajes[] = [
                        'tipo' => 'danger',
                        'mensaje' => $e,
                     ];
              }
          }

          //   // Obtenemos los valores para mostrarlos en los campos del formulario
          $valnombre = $nuevonombre;
          $valdescripcion = $nuevodescripcion;
          $valaforo = $nuevoaforo;
      } else { //Estamos rellenando los campos con los valores recibidos del listado
          if (isset($_GET['id']) && (is_numeric($_GET['id']))) {
              $id = $_GET['id'];
              //Ejecutamos la consulta para obtener los datos del usuario #id
              $resultModelo = $this->modelo->listaActividad($id);
              //Analizamos si la consulta se realiz´correctamente o no y generamos un
              //mensaje indicativo
              if ($resultModelo['correcto']) :
                 $this->mensajes[] = [
                    'tipo' => 'success',
                    'mensaje' => 'Los datos de la actividad se obtuvieron correctamente!! :)',
                 ];
              $valnombre = $resultModelo['datos']['nombre'];
              $valdescripcion = $resultModelo['datos']['descripcion'];
              $valaforo = $resultModelo['datos']['aforo']; else :
                 $this->mensajes[] = [
                    'tipo' => 'danger',
                    'mensaje' => "No se pudieron obtener los datos de la actividad!! :( <br/>({$resultModelo['error']})",
                 ];
              endif;
          }
      }
            //Preparamos un array con todos los valores que tendremos que rellenar en
            //la vista adduser: título de la página y campos del formulario
            $parametros = [
         'tituloventana' => 'Actualiza actividad',
         'datos' => [
            'txtnombre' => $valnombre,
            'txtdescripcion' => $valdescripcion,
               'txtaforo' => $valaforo,
         ],
         'mensajes' => $this->mensajes,
          'id' => $id,
      ];
            //Mostramos la vista actuser
            $this->view->show('ActActividad', $parametros);
        } else {
            $parametros = [
                'tituloventana' => 'Prohibido el paso',
                'datos' => [],
                'mensajes' => $this->mensajes,
             ];
            $this->view->show('Prohibido', $parametros);
        }
    }

    public function listadoActividades()
    {
        if ($_SESSION['rol_id'] == 0) {
            // Almacenamos en el array 'parametros[]'los valores que vamos a mostrar en la vista
            $parametros = [
             'tituloventana' => 'Listado de Actividades',
             'datos' => null,
             'mensajes' => [],
             'numpaginas' => null,
             'regsxpag' => null,
             'totalregistros' => null,
          ];
            $regsxpag = (isset($_GET['regsxpag'])) ? (int) $_GET['regsxpag'] : 5;
            //Establecemos la página que vamos a mostrar, por página, por defecto la 1
            $pagina = (isset($_GET['pagina'])) ? (int) $_GET['pagina'] : 1;
            //Definimos la variable $offset que indique la posición del registro desde el que se
            // mostrarán los registros de una página dentro de la paginación.
            $offset = ($pagina > 1) ? (($pagina - 1) * $regsxpag) : 0;

            $totalregistros = $this->modelo->cuentaActividad();

            //Determinamos el número de páginas de la que constará mi paginación
            $numpaginas = ceil($totalregistros / $regsxpag);
            // Realizamos la consulta y almacenamos los resultados en la variable $resultModelo
            $resultModelo = $this->modelo->listadoActividades($regsxpag, $offset);

            // Realizamos la consulta y almacenamos los resultados en la variable $resultModelo
            // $resultModelo = $this->modelo->listado();
            // Si la consulta se realizó correctamente transferimos los datos obtenidos
            // de la consulta del modelo ($resultModelo["datos"]) a nuestro array parámetros
            // ($parametros["datos"]), que será el que le pasaremos a la vista para visualizarlos
            if ($resultModelo['correcto']) :
             $parametros['datos'] = $resultModelo['datos'];
            //Definimos el mensaje para el alert de la vista de que todo fue correctamente
            $this->mensajes[] = [
                'tipo' => 'success',
                'mensaje' => 'El listado se realizó correctamente',
             ]; else :
             //Definimos el mensaje para el alert de la vista de que se produjeron errores al realizar el listado
             $this->mensajes[] = [
                'tipo' => 'danger',
                'mensaje' => "El listado no pudo realizarse correctamente!! :( <br/>({$resultModelo['error']})",
             ];
            endif;
            //Asignamos al campo 'mensajes' del array de parámetros el valor del atributo
            //'mensaje', que recoge cómo finalizó la operación:

            $parametros = [
                'tituloventana' => 'Listado de actividades',
                'datos' => $resultModelo['datos'],
                'mensajes' => $this->mensajes,
                'numpaginas' => $numpaginas,
                'regsxpag' => $regsxpag,
                'totalregistros' => $totalregistros,
                'pagina' => $pagina,
             ];
            // Incluimos la vista en la que visualizaremos los datos o un mensaje de error
            $this->view->show('ListaActividades', $parametros);
        } else {
            $parametros = [
                'tituloventana' => 'Prohibido el paso',
                'datos' => [],
                'mensajes' => $this->mensajes,
             ];
            $this->view->show('Prohibido', $parametros);
        }
    }

    public function horario()
    {
    }

    public function apuntarActividad()
    {
    }
}
