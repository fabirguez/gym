<?php

/**
 * Incluimos los modelos que necesite este controlador.
 */
require_once MODELS_FOLDER.'UserModel.php';

/**
 * Clase controlador que será la encargada de obtener, para cada tarea, los datos
 * necesarios de la base de datos, y posteriormente, tras su proceso de elaboración,
 * enviarlos a la vista para su visualización.
 */
class UserController extends BaseController
{
    // El atributo $modelo es de la 'clase modelo' y será a través del que podremos
    // acceder a los datos y las operaciones de la base de datos desde el controlador
    private $modelo;
    //$mensajes se utiliza para almacenar los mensajes generados en las tareas,
    //que serán posteriormente transmitidos a la vista para su visualización
    private $mensajes;

    /**
     * Constructor que crea automáticamente un objeto modelo en el controlador e
     * inicializa los mensajes a vacío.
     */
    public function __construct()
    {
        parent::__construct();
        $this->modelo = new UserModel();
        $this->mensajes = [];
    }

    /**
     * Método que obtiene de la base de datos el listado de usuarios y envía dicha
     * infomación a la vista correspondiente para su visualización.
     */
    public function listado()
    {
        if ($_SESSION['rol_id'] == 0) {
            // Almacenamos en el array 'parametros[]'los valores que vamos a mostrar en la vista
            $parametros = [
         'tituloventana' => 'Listado de usuarios',
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

            $totalregistros = $this->modelo->cuentaUser();

            //Determinamos el número de páginas de la que constará mi paginación
            $numpaginas = ceil($totalregistros / $regsxpag);
            // Realizamos la consulta y almacenamos los resultados en la variable $resultModelo
            $resultModelo = $this->modelo->listado($regsxpag, $offset);

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
            'tituloventana' => 'Listado de usuarios',
            'datos' => $resultModelo['datos'],
            'mensajes' => $this->mensajes,
            'numpaginas' => $numpaginas,
            'regsxpag' => $regsxpag,
            'totalregistros' => $totalregistros,
            'pagina' => $pagina,
         ];
            // Incluimos la vista en la que visualizaremos los datos o un mensaje de error
            $this->view->show('ListaUser', $parametros);
        } else {
            $parametros = [
            'tituloventana' => 'Prohibido el paso',
            'datos' => [],
            'mensajes' => $this->mensajes,
         ];
            $this->view->show('Prohibido', $parametros);
        }
    }

    /**
     * Método de la clase controlador que realiza la eliminación de un usuario a
     * través del campo id.
     */
    public function deluser()
    {
        if ($_SESSION['rol_id'] == 0) {
            // verificamos que hemos recibido los parámetros desde la vista de listado
            if (isset($_GET['id']) && (is_numeric($_GET['id']))) {
                $id = $_GET['id'];

                //Realizamos la operación de suprimir el usuario con el id=$id
                $resultModelo = $this->modelo->deluser($id);
                //Analizamos el valor devuelto por el modelo para definir el mensaje a
                //mostrar en la vista listado
                if ($resultModelo['correcto']) :
            $this->mensajes[] = [
               'tipo' => 'success',
               'mensaje' => "Se eliminó correctamente el usuario $id",
            ]; else :
            $this->mensajes[] = [
               'tipo' => 'danger',
               'mensaje' => "La eliminación del usuario no se realizó correctamente!! :( <br/>({$resultModelo['error']})",
            ];
                endif;
            } else { //Si no recibimos el valor del parámetro $id generamos el mensaje indicativo:
                $this->mensajes[] = [
            'tipo' => 'danger',
            'mensaje' => 'No se pudo acceder al id del usuario a eliminar!! :(',
         ];
            }
            //Realizamos el listado de los usuarios
            $this->listado();
        } else {
            $parametros = [
            'tituloventana' => 'Prohibido el paso',
            'datos' => [],
            'mensajes' => $this->mensajes,
         ];
            $this->view->show('Prohibido', $parametros);
        }
    }

    public function adduser()
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
          $nif = filter_var($_POST['txtnif'], FILTER_SANITIZE_STRING);
          $apellidos = filter_var($_POST['txtapellidos'], FILTER_SANITIZE_STRING);
          $email = filter_var($_POST['txtemail'], FILTER_SANITIZE_STRING);
          $password = filter_var($_POST['txtpassword'], FILTER_SANITIZE_STRING);
          $password2 = filter_var($_POST['txtpassword2'], FILTER_SANITIZE_STRING);
          $telefono = filter_var($_POST['txttelefono'], FILTER_SANITIZE_STRING);
          $direccion = filter_var($_POST['txtdireccion'], FILTER_SANITIZE_STRING);
          $imagen = '-';
          $estado = $_POST['txtestado'];
          $rol_id = $_POST['txtrol_id'];
          $filtrardatos = [
            'nif' => $nif,
            'email' => $email,
            'password' => $password,
            'telefono' => $telefono,
        ];

          $errores = $this->modelo->filtraDatos($filtrardatos);

          $errores += $this->modelo->existeEmail($email);
          $errores += $this->modelo->comparaPassword($password, $password2);

          /* Realizamos la carga de la imagen en el servidor */
          //LA IMAGEN SE AÑADE DESPUES
          //       Comprobamos que el campo tmp_name tiene un valor asignado para asegurar que hemos
          //       recibido la imagen correctamente
          //       Definimos la variable $imagen que almacenará el nombre de imagen
          //       que almacenará la Base de Datos inicializada a NULL
          /*           $imagen = null;

                    if (isset($_FILES['imagen']) && (!empty($_FILES['imagen']['tmp_name']))) {
                        // Verificamos la carga de la imagen
                        // Comprobamos si existe el directorio fotos, y si no, lo creamos
                        if (!is_dir('assets/img/perfil')) {
                            $dir = mkdir('assets/img/perfil', 0777, true);
                        } else {
                            $dir = true;
                        }
                        // Ya verificado que la carpeta uploads existe movemos el fichero seleccionado a dicha carpeta
                        if ($dir) {
                            //Para asegurarnos que el nombre va a ser único...
                            $nombrefichimg = time().'-'.$_FILES['imagen']['name'];
                            // Movemos el fichero de la carpeta temportal a la nuestra
                            $movfichimg = move_uploaded_file($_FILES["imagen"]["tmp_name"], "assets/img/perfil/" . $nombrefichimg);
                            $imagen = $nombrefichimg;
                            // Verficamos que la carga se ha realizado correctamente
                            if ($movfichimg) {
                                $imagencargada = true;
                            } else {
                                $imagencargada = false;
                                $this->mensajes[] = [
                               'tipo' => 'danger',
                               'mensaje' => 'Error: La imagen no se cargó correctamente! :(',
                            ];
                                $errores['imagen'] = 'Error: La imagen no se cargó correctamente! :(';
                            }
                        }
                    } */
          // Si no se han producido errores realizamos el registro del usuario

          if (count($errores) == 0) {
              $resultModelo = $this->modelo->adduser([
               'nif' => $nif,
               'nombre' => $nombre,
               'apellidos' => $apellidos,
                'email' => $email,
                'password' => sha1($password),
                'telefono' => $telefono,
                'direccion' => $direccion,
                'estado' => $estado,
                'imagen' => $imagen,
                'rol_id' => $rol_id,
            ]);
              if ($resultModelo['correcto']) :
               $this->mensajes[] = [
                  'tipo' => 'success',
                  'mensaje' => 'El usuarios se registró correctamente!! :)',
               ]; else :
               $this->mensajes[] = [
                  'tipo' => 'danger',
                  'mensaje' => "El usuario no pudo registrarse!! :( <br />({$resultModelo['error']})",
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
         'tituloventana' => 'Registro de usuario by administrador',
         'datos' => [
            'txtnif' => isset($nif) ? $nif : '',
               'txtnombre' => isset($nombre) ? $nombre : '',
               'txtapellidos' => isset($apellidos) ? $apellidos : '',
                'txtemail' => isset($email) ? $email : '',
                'txtpassword' => isset($password) ? $password : '',
                'txttelefono' => isset($telefono) ? $telefono : '',
                'txtdireccion' => isset($direccion) ? $direccion : '',
                'txtestado' => isset($estado) ? $estado : '',
                'imagen' => isset($imagen) ? $imagen : '',
                'rol_id' => isset($rol_id) ? $rol_id : 3,
         ],
         'mensajes' => $this->mensajes,
      ];
            //Visualizamos la vista asociada al registro de usuarios
            $this->view->show('AddUser', $parametros);
        } else {
            $parametros = [
            'tituloventana' => 'Prohibido el paso',
            'datos' => [],
            'mensajes' => $this->mensajes,
         ];
            $this->view->show('Prohibido', $parametros);
        }
    }

    /**
     * Método de la clase controlador que permite actualizar los datos del usuario
     * cuyo id coincide con el que se pasa como parámetro desde la vista de listado
     * a través de GET.
     */
    public function actuser()
    {
        // Array asociativo que almacenará los mensajes de error que se generen por cada campo
        $errores = [];

        if ($_SESSION['rol_id'] == 0) {
            // Inicializamos valores de los campos de texto

            $valnombre = '';
            $valnif = '';
            $valapellidos = '';
            $valemail = '';
            $valpassword = '';
            $valpassword2 = '';
            $valtelefono = '';
            $valdireccion = '';
            $valrol_id = 3;

            //dnd guardo el id!!!

            // Si se ha pulsado el botón actualizar...
      if (isset($_POST['submit'])) { //Realizamos la actualización con los datos existentes en los campos
          $id = $_POST['id'];
          $nuevonombre = filter_var($_POST['txtnombre'], FILTER_SANITIZE_STRING);
          $nuevonif = filter_var($_POST['txtnif'], FILTER_SANITIZE_STRING);
          $nuevoapellidos = filter_var($_POST['txtapellidos'], FILTER_SANITIZE_STRING);
          $nuevoemail = filter_var($_POST['txtemail'], FILTER_SANITIZE_STRING);

          $nuevopassword = filter_var($_POST['txtpassword'], FILTER_SANITIZE_STRING);
          $nuevopassword2 = filter_var($_POST['txtpassword2'], FILTER_SANITIZE_STRING);

          $nuevotelefono = filter_var($_POST['txttelefono'], FILTER_SANITIZE_STRING);
          $nuevodireccion = filter_var($_POST['txtdireccion'], FILTER_SANITIZE_STRING);
          $nuevoimagen = '-';
          $nuevorol_id = $_POST['txtrol_id'];

          if ($_POST['txtpassword'] == '' && $_POST['txtpassword2'] == '') {
              $filtrardatos = [
                'nif' => $nuevonif,
                'email' => $nuevoemail,
                'telefono' => $nuevotelefono,
            ];
          } else {
              $filtrardatos = [
          'nif' => $nuevonif,
          'email' => $nuevoemail,
          'password' => $nuevopassword,
          'telefono' => $nuevotelefono,
      ];
          }

          $errores = $this->modelo->filtraDatos($filtrardatos);
          if ($this->modelo->listausuario($id)['datos']['email'] != $nuevoemail) {
              $errores += $this->modelo->existeEmail($nuevoemail);
          }
          if ($_POST['txtpassword'] != '' && $_POST['txtpassword2'] != '') {
              $errores += $this->modelo->comparaPassword($nuevopassword, $nuevopassword2);
              $nuevopassword = sha1($nuevopassword);
          }

          // Definimos la variable $imagen que almacenará el nombre de imagen
          // que almacenará la Base de Datos inicializada a NULL
          //   $imagen = null;

          //   if (isset($_FILES['imagen']) && (!empty($_FILES['imagen']['tmp_name']))) {
          //       // Verificamos la carga de la imagen
          //       // Comprobamos si existe el directorio fotos, y si no, lo creamos
          //       if (!is_dir('fotos')) {
          //           $dir = mkdir('fotos', 0777, true);
          //       } else {
          //           $dir = true;
          //       }
          //       // Ya verificado que la carpeta fotos existe movemos el fichero seleccionado a dicha carpeta
          //       if ($dir) {
          //           //Para asegurarnos que el nombre va a ser único...
          //           $nombrefichimg = time().'-'.$_FILES['imagen']['name'];
          //           // Movemos el fichero de la carpeta temportal a la nuestra
          //           $movfichimg = move_uploaded_file($_FILES['imagen']['tmp_name'], 'fotos/'.$nombrefichimg);
          //           $imagen = $nombrefichimg;
          //           // Verficamos que la carga se ha realizado correctamente
          //           if ($movfichimg) {
          //               $imagencargada = true;
          //           } else {
          //               //Si no pudo moverse a la carpeta destino generamos un mensaje que se le
          //               //mostrará al usuario en la vista actuser
          //               $imagencargada = false;
          //               $errores['imagen'] = 'Error: La imagen no se cargó correctamente! :(';
          //               $this->mensajes[] = [
          //              'tipo' => 'danger',
          //              'mensaje' => 'Error: La imagen no se cargó correctamente! :(',
          //           ];
          //           }
          //       }
          //   }
          //   $nuevaimagen = $imagen;

          //Ejecutamos la instrucción de actualización a la que le pasamos los valores
          if (count($errores) == 0) {
              if (empty($nuevopassword)) {
                  $nuevopassword = $this->modelo->listausuario($id)['datos']['password'];
              }
              $resultModelo = $this->modelo->actuser([
                  'id' => $id,
                 'nif' => $nuevonif,
                 'nombre' => $nuevonombre,
                 'apellidos' => $nuevoapellidos,
                  'email' => $nuevoemail,
                  'password' => $nuevopassword,
                  'telefono' => $nuevotelefono,
                  'direccion' => $nuevodireccion,
                  'imagen' => $nuevoimagen,
                  'rol_id' => $nuevorol_id,
              ]);
              //Analizamos cómo finalizó la operación de registro y generamos un mensaje
              //indicativo del estado correspondiente
              if ($resultModelo['correcto']) :
            //    $this->listado();
              $this->mensajes[] = [
                  'tipo' => 'success',
                  'mensaje' => 'El usuario se actualizó correctamente!! :)',
               ]; else :
               $this->mensajes[] = [
                  'tipo' => 'danger',
                  'mensaje' => "El usuario no pudo actualizarse!! :( <br/>({$resultModelo['error']})",
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
          $valnif = $nuevonif;
          $valapellidos = $nuevoapellidos;
          $valemail = $nuevoemail;
          $valpassword = $nuevopassword;
          $valtelefono = $nuevotelefono;
          $valdireccion = $nuevodireccion;
          $valrol_id = $nuevorol_id;
      } else { //Estamos rellenando los campos con los valores recibidos del listado
          if (isset($_GET['id']) && (is_numeric($_GET['id']))) {
              $id = $_GET['id'];
              //Ejecutamos la consulta para obtener los datos del usuario #id
              $resultModelo = $this->modelo->listausuario($id);
              //Analizamos si la consulta se realiz´correctamente o no y generamos un
              //mensaje indicativo
              if ($resultModelo['correcto']) :
                 $this->mensajes[] = [
                    'tipo' => 'success',
                    'mensaje' => 'Los datos del usuario se obtuvieron correctamente!! :)',
                 ];
              $valnombre = $resultModelo['datos']['nombre'];
              $valnif = $resultModelo['datos']['nif'];
              $valapellidos = $resultModelo['datos']['apellidos'];
              $valemail = $resultModelo['datos']['email'];
              $valpassword = $resultModelo['datos']['password'];
              $valtelefono = $resultModelo['datos']['telefono'];
              $valdireccion = $resultModelo['datos']['direccion'];
              $valrol_id = $resultModelo['datos']['rol_id']; else :
                 $this->mensajes[] = [
                    'tipo' => 'danger',
                    'mensaje' => "No se pudieron obtener los datos de usuario!! :( <br/>({$resultModelo['error']})",
                 ];
              endif;
          }
      }
            //Preparamos un array con todos los valores que tendremos que rellenar en
            //la vista adduser: título de la página y campos del formulario
            $parametros = [
         'tituloventana' => 'Actualiza usuario',
         'datos' => [
            'txtnombre' => $valnombre,
            'txtnif' => $valnif,
               'txtapellidos' => $valapellidos,
                'txtemail' => $valemail,
                'txtpassword' => '',
                'txttelefono' => $valtelefono,
                'txtdireccion' => $valdireccion,
                // 'txtestado' => isset($estado) ? $estado : '',
                // 'imagen' => isset($imagen) ? $imagen : '',
                 'txtrol_id' => $valrol_id,
         ],
         'mensajes' => $this->mensajes,
          'id' => $id,
      ];
            //Mostramos la vista actuser
            $this->view->show('ActUser', $parametros);
        } else {
            $parametros = [
                'tituloventana' => 'Prohibido el paso',
                'datos' => [],
                'mensajes' => $this->mensajes,
             ];
            $this->view->show('Prohibido', $parametros);
        }
    }

    public function actFoto()
    {
        $imagen;

        if (isset($_FILES['imagen']) && (!empty($_FILES['imagen']['tmp_name']))) {
            if (!is_dir('assets/img/perfil')) {
                $dir = mkdir('assets/img/perfil', 0777, true);
            } else {
                $dir = true;
            }
            // Ya verificado que la carpeta uploads existe movemos el fichero seleccionado a dicha carpeta
            if ($dir) {
                //Para asegurarnos que el nombre va a ser único...
                $nombrefichimg = time().'-'.$_FILES['imagen']['name'];
                // Movemos el fichero de la carpeta temportal a la nuestra
                $movfichimg = move_uploaded_file($_FILES['imagen']['tmp_name'], 'assets/img/perfil'.$nombrefichimg);
                $imagen = $nombrefichimg;
                // Verficamos que la carga se ha realizado correctamente
                if ($movfichimg) {
                    $imagencargada = true;
                } else {
                    $imagencargada = false;
                    $this->mensajes[] = [
                   'tipo' => 'danger',
                   'mensaje' => 'Error: La imagen no se cargó correctamente! :(',
                ];
                    $errores['imagen'] = 'Error: La imagen no se cargó correctamente! :(';
                }
            }
        }
        // Si no se han producido errores realizamos el registro del usuario
        if (count($errores) == 0) {
            $resultModelo = $this->modelo->actImg([
               'imagen' => $imagen,
            ]);
            if ($resultModelo['correcto']) :
                $this->mensajes[] = [
                   'tipo' => 'success',
                   'mensaje' => 'La foto se actualizó correctamente!! :)',
                ]; else :
                $this->mensajes[] = [
                   'tipo' => 'danger',
                   'mensaje' => "La foto no pudo actualizarse!! :( <br />({$resultModelo['error']})",
                ];
            endif;
        }
        // Obtenemos los valores para mostrarlos en los campos del formulario
        $valimagen = $nuevaimagen;

        //Preparamos un array con todos los valores que tendremos que rellenar en
        //la vista actimg: título de la página y campos del formulario
        $parametros = [
        'tituloventana' => 'Actualiza imagen',
        'datos' => [
           'imagen' => $valimagen,
        ],
        'mensajes' => $this->mensajes,
        'id' => $id,
     ];
        //Mostramos la vista actuser
        $this->view->show('ActImg', $parametros);
    }

    public function activarus()
    {
        if ($_SESSION['rol_id'] == 0) {
            if (isset($_GET['id'])) {
                $id = $_GET['id'];

                $resultModelo = $this->modelo->activarus($id);
                //Analizamos el valor devuelto por el modelo para definir el mensaje a
                //mostrar en la vista listado
                if ($resultModelo['correcto']) :
            $this->mensajes[] = [
               'tipo' => 'success',
               'mensaje' => "Se activó correctamente el usuario $id",
            ]; else :
            $this->mensajes[] = [
               'tipo' => 'danger',
               'mensaje' => "La activacion del usuario no se realizó correctamente!! :( <br/>({$resultModelo['error']})",
            ];
                endif;
            } else { //Si no recibimos el valor del parámetro $id generamos el mensaje indicativo:
                $this->mensajes[] = [
            'tipo' => 'danger',
            'mensaje' => 'No se pudo acceder al id del usuario a activar!! :(',
         ];
            }

            $parametros = [
            'tituloventana' => 'Listado usuarios',
            'datos' => [],
            'mensajes' => $this->mensajes,
         ];
            $this->listado();
        } else {
            $parametros = [
            'tituloventana' => 'Prohibido el paso',
            'datos' => [],
            'mensajes' => $this->mensajes,
         ];
            $this->view->show('Prohibido', $parametros);
        }
    }

    public function desactivarus()
    {
        if ($_SESSION['rol_id'] == 0) {
            if (isset($_GET['id'])) {
                $id = $_GET['id'];

                $resultModelo = $this->modelo->desactivarus($id);
                //Analizamos el valor devuelto por el modelo para definir el mensaje a
                //mostrar en la vista listado
                if ($resultModelo['correcto']) :
            $this->mensajes[] = [
               'tipo' => 'success',
               'mensaje' => "Se desactivó correctamente el usuario $id",
            ]; else :
            $this->mensajes[] = [
               'tipo' => 'danger',
               'mensaje' => "La desactivacion del usuario no se realizó correctamente!! :( <br/>({$resultModelo['error']})",
            ];
                endif;
            } else { //Si no recibimos el valor del parámetro $id generamos el mensaje indicativo:
                $this->mensajes[] = [
            'tipo' => 'danger',
            'mensaje' => 'No se pudo acceder al id del usuario a desactivar!! :(',
         ];
            }

            $parametros = [
            'tituloventana' => 'Listado usuarios',
            'datos' => [],
            'mensajes' => $this->mensajes,
         ];
            $this->listado();
        } else {
            $parametros = [
            'tituloventana' => 'Prohibido el paso',
            'datos' => [],
            'mensajes' => $this->mensajes,
         ];
            $this->view->show('Prohibido', $parametros);
        }
    }
}
