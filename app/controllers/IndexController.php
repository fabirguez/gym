<?php
/**
 * Controlador de la página index desde la que se puede hacer el login y el registro.
 */

/**
 * Incluimos todos los modelos que necesite este controlador.
 */
require_once MODELS_FOLDER.'UserModel.php';

class IndexController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->modelo = new UserModel();
        $this->mensajes = [];
    }

    public function index()
    {
        $parametros = [
         'tituloventana' => 'Inicio del gimnasio',
      ];

        $this->view->show('Index', $parametros);
    }

    /**
     * Podemos implementar la acción login.
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
                // if ($esActivo['correcto']) {
                if ($tryLogin['datos']['estado'] == 1) {
                    session_start();
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
     * Podemos implementar la acción registro de usuarios.
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
            $imagen = '-';
            $estado = 0;
            $rol_id = 1;
            $filtrardatos = [
            'nif' => $nif,
            'email' => $email,
            'password' => $password,
            'telefono' => $telefono,
        ];

            $errores = $this->modelo->filtraDatos($filtrardatos);

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
              'rol_id' => isset($rol_id) ? $rol_id : 0,
       ],
       'mensajes' => $this->mensajes,
    ];
        //Visualizamos la vista asociada al registro de usuarios
        $this->view->show('Register', $parametros);
    }

    /*
     * Otras acciones que puedan ser necesarias
     */

    public function logout()
    {
        $parametros = [
            'tituloventana' => 'Logout',
            'datos' => null,
            'mensajes' => [],
         ];
        session_start();
        session_unset();
        session_destroy();
        $this->view->show('Index', $parametros);
    }
}
