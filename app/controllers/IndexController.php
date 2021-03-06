<?php
/**
 * Controlador de la página index desde la que se puede hacer el login, el registro, cerrar sesión, instalar bbdd y recordar contraseña.
 */

/**
 * Incluimos todos los modelos que necesite este controlador.
 */
require_once MODELS_FOLDER.'UserModel.php';

/**
 * Clase controlador que será la encargada de obtener, para cada tarea, los datos
 * necesarios de la base de datos, y posteriormente, tras su proceso de elaboración,
 * enviarlos a la vista para su visualización.
 */
class IndexController extends BaseController
{
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
     * Método llama a la vista de la página de inicio.
     */
    public function index()
    {
        $parametros = [
         'tituloventana' => 'Inicio del gimnasio',
      ];

        $this->view->show('Index', $parametros);
    }

    /**
     * Funcion login.
     *
     * @return void
     */
    public function login()
    {
        $parametros = [
            'tituloventana' => 'Login',
            'datos' => null,
            'mensajes' => [],
         ];

        $errores = [];

        if (isset($_POST['txtemail']) && isset($_POST['txtpassword'])) {
            // $u = new UserModel();
            $tryLogin = $this->modelo->tryLogin($_POST['txtemail'], sha1($_POST['txtpassword']));

            if ($tryLogin['correcto'] == true) {
                $this->mensajes[] = [
                'tipo' => 'success',
                'mensaje' => 'Login correcto',
             ];
                $esActivo = $this->modelo->esActivo($_POST['txtemail']);
                if ($tryLogin['datos']['estado'] == 1) {
                    $_SESSION['email'] = $_POST['txtemail'];
                    $parametros['email'] = $_POST['txtemail'];

                    // $rol_id = $this->modelo->rolEmail($_SESSION['email']);   //de esta forma no funciona
                    $rol_id = $tryLogin['datos']['rol_id'];
                    $_SESSION['rol_id'] = $rol_id;
                    $parametros['rol_id'] = $rol_id;

                    // if (isset($_POST['recuerda']) && $_POST['recuerda'] == 'on') {
                    //     setcookie('email', $_POST['email'], time() + (15 * 24 * 60 * 60));
                    //     setcookie('password', $_POST['password'], time() + (15 * 24 * 60 * 60));
                    // } else {
                    //     if (isset($_COOKIE['email'])) {
                    //         setcookie('email', '');
                    //         setcookie('password', '');
                    //     }
                    // }
                    // if (isset($_POST['mantieneSesion']) && $_POST['mantieneSesion'] == 'on') {
                    //     setcookie('mantieneSesion', $_POST['email'], time() + (15 * 24 * 60 * 60));
                    // } else {
                    //     if (isset($_COOKIE['mantieneSesion'])) {
                    //         setcookie('mantieneSesion', '');
                    //     }
                    // }
                    $parametros['mensajes'] = $this->mensajes;
                    $this->view->show('Index', $parametros);
                } else {
                    // foreach ($errores as $e) {
                    //     $this->mensajes[] = [
                    //         'tipo' => 'danger',
                    //         'mensaje' => $e,
                    //      ];
                    // }
                    $this->mensajes[] = [
                        'tipo' => 'danger',
                        'mensaje' => 'El usuario debe activarlo un administrador. Contacte con la empresa',
                     ];
                    $parametros['mensajes'] = $this->mensajes;
                    $this->view->show('Login', $parametros);
                }
            } else {
                // foreach ($errores as $e) {
                //     $this->mensajes[] = [
                //         'tipo' => 'danger',
                //         'mensaje' => $e,
                //      ];
                // }
                $this->mensajes[] = [
                    'tipo' => 'danger',
                    'mensaje' => 'Usuario y contraseña no coinciden o no existe!! :( ',
                 ];
                $parametros['mensajes'] = $this->mensajes;
                $this->view->show('Login', $parametros);
            }
        } else {
            //Definimos el mensaje para el alert de la vista de que se produjeron errores
            $this->mensajes[] = [
                'tipo' => 'danger',
                'mensaje' => 'Usuario y o contraseña vacios!! :( ',
             ];

            ///MIRARRRR!!!!
            $parametros['mensajes'] = $this->mensajes;
            $this->view->show('Login', $parametros);
        }
    }

    /**
     * Acción de registrar usuarios, el usuario añade sus datos y luego el administrador
     * tiene que activarlo.
     *
     * @return void
     */
    public function register()
    {
        $parametros = [
            'tituloventana' => 'Registro de usuario',
            'datos' => null,
            'mensajes' => [],
         ];
        // Array asociativo que almacenará los mensajes de error que se generen por cada campo
        $errores = [];
        // Si se ha pulsado el botón guardar...
        if (isset($_POST['txtnombre']) && isset($_POST['txtnif']) && isset($_POST['txtapellidos'])
      && isset($_POST['txtemail']) && isset($_POST['txtpassword']) && isset($_POST['txtpassword2'])
      && isset($_POST['txttelefono']) && isset($_POST['txtdireccion'])) { // y hemos recibido las variables del formulario y éstas no están vacías...
            $nombre = filter_var($_POST['txtnombre'], FILTER_SANITIZE_STRING);
            $nif = filter_var($_POST['txtnif'], FILTER_SANITIZE_STRING);
            $apellidos = filter_var($_POST['txtapellidos'], FILTER_SANITIZE_STRING);
            $email = filter_var($_POST['txtemail'], FILTER_SANITIZE_STRING);
            $password = filter_var($_POST['txtpassword'], FILTER_SANITIZE_STRING);
            $password2 = filter_var($_POST['txtpassword2'], FILTER_SANITIZE_STRING);
            $telefono = filter_var($_POST['txttelefono'], FILTER_SANITIZE_STRING);
            $direccion = filter_var($_POST['txtdireccion'], FILTER_SANITIZE_STRING);
            //opcion de imagen no añadida, añadir mas adelante
            $imagen = '-';
            //el estado está desactivado
            $estado = 0;
            //y el rol sería de usuario por defecto
            $rol_id = 1;
            $filtrardatos = [
            'nif' => $nif,
            'email' => $email,
            'password' => $password,
            'telefono' => $telefono,
        ];

            //Llama a la funcion para que filtre los datos pasandole los datos a filtrar
            $errores = $this->modelo->filtraDatos($filtrardatos);
            //Se añaden mas errores, con += ha sido la forma con la que lo he conseguido
            $errores += $this->modelo->existeEmail($_POST['txtemail']);
            $errores += $this->modelo->comparaPassword($password, $password2);

            $password = sha1($password);
            $password2 = sha1($password2);

            if (count($errores) == 0) {
                $resultModelo = $this->modelo->adduser([
             'nif' => $nif,
             'nombre' => $nombre,
             'apellidos' => $apellidos,
              'email' => $email,
              'password' => $password,
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
                //recorre el array errores para mostrar los mensajes
                foreach ($errores as $e) {
                    $this->mensajes[] = [
                        'tipo' => 'danger',
                        'mensaje' => $e,
                     ];
                }
            }
        }

        $parametros = [
       'tituloventana' => 'Registro de usuario',
       'datos' => [
          'txtnombre' => isset($nombre) ? $nombre : '',
          'txtnif' => isset($nif) ? $nif : '',
             'txtnombre' => isset($nombre) ? $nombre : '',
             'txtapellidos' => isset($apellidos) ? $apellidos : '',
              'txtemail' => isset($email) ? $email : '',
              'txtpassword' => isset($password) ? $password : '',
              'txttelefono' => isset($telefono) ? $telefono : '',
              'txtdireccion' => isset($direccion) ? $direccion : '',
              'txtestado' => isset($estado) ? $estado : 3,
              'imagen' => isset($imagen) ? $imagen : '',
              'rol_id' => isset($rol_id) ? $rol_id : 1,
       ],
       'mensajes' => $this->mensajes,
    ];
        //Visualizamos la vista asociada al registro de usuarios
        $this->view->show('Register', $parametros);
    }

    /**
     * Acción de cerrar sesion, borrando las variables de sesion.
     *
     * @return void
     */
    public function logout()
    {
        $parametros = [
            'tituloventana' => 'Logout',
            'datos' => null,
            'mensajes' => [],
         ];

        //Vacía las variables de sesión para que los usuarios no puedan hacer nada
        $_SESSION['email'] = '';
        $_SESSION['rol_id'] = '4';
        $this->view->show('Index', $parametros);
    }

    /**
     * Acción de recordar contraseña, guardando una nueva y desactivando la cuenta, para
     * que el administrador vuelva a activarla. No funciona.
     *
     * @return void
     */
    public function recordarPassword()
    {
        $parametros = [
            'tituloventana' => 'Recordar contraseña',
            'datos' => null,
            'mensajes' => [],
         ];

        $errores = [];

        if (isset($_POST['txtemail']) && isset($_POST['txtpassword']) && isset($_POST['txtpassword2'])) {
            $filtrardatos = [
                'email' => $_POST['txtemail'],
                'password' => $_POST['txtpassword'],
            ];

            $nuevopassword = $_POST['txtpassword'];
            $nuevopassword2 = $_POST['txtpassword2'];

            $errores = $this->modelo->filtraDatos($filtrardatos);

            //Busca si existe en los errores la clave email, porque significará
            //que existe el mail, y si es así, añade un error
            if (!array_key_exists('email', $this->modelo->existeEmail($nuevoemail))) {
                $errores += ['mail' => 'No existe la cuenta escrita'];
            }

            $errores += $this->modelo->comparaPassword($nuevopassword, $nuevopassword2);

            $recordarPass = $this->modelo->recordarPass($_POST['txtemail'], sha1($nuevopassword));

            if ($recordarPass['correcto'] == true) {
                $this->mensajes[] = [
                'tipo' => 'success',
                'mensaje' => 'Se ha cambiado. Contacta con el administrador para que te reactive la cuenta',
             ];

                $parametros['mensajes'] = $this->mensajes;
            } else {
                foreach ($errores as $e) {
                    $this->mensajes[] = [
                        'tipo' => 'danger',
                        'mensaje' => $e,
                     ];
                }

                $parametros['mensajes'] = $this->mensajes;
                $this->view->show('RecordarPass', $parametros);
            }
        } else {
            //Definimos el mensaje para el alert de la vista de que se produjeron errores
            $this->mensajes[] = [
                'tipo' => 'danger',
                'mensaje' => 'Usuario y o contraseña vacios!! :( ',
             ];

            ///MIRARRRR!!!!
            $parametros['mensajes'] = $this->mensajes;
            $this->view->show('RecordarPass', $parametros);
        }
        $this->view->show('RecordarPass', $parametros);
    }

    /**
     * Acción de instalar la base de datos, pidiendo los datos como el servidor y el nombre de usuario.
     *
     * @return void
     */
    public function instalar()
    {
        $this->view->show('Instalar');
    }
}
