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

        if (isset($_POST['txtemail']) && isset($_POST['txtpassword'])) {
            $u = new UserModel();
            $errores = $this->modelo->tryLogin($_POST['txtemail'], $_POST['txtpassword']);
            if (count($errores) == 0) {
                $this->mensajes[] = [
                'tipo' => 'success',
                'mensaje' => 'Login correcto',
             ];
                $esActivo = $this->modelo->esActivo($_POST['txtemail']);
                if ($esActivo['correcto'] && ($esActivo['datos'] == 1)) {
                    session_start();
                    $_SESSION['email'] = $_POST['txtemail'];
                    $parametros['email'] = $_POST['txtemail'];

                    $rol_id = $this->modelo->rolEmail($_POST['txtemail']);
                    $rol_id = $rol_id['datos'];
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
                    $this->view->show('Inicio', $parametros);
                } else {
                    $this->mensajes[] = [
                        'tipo' => 'danger',
                        'mensaje' => 'El usuario debe activarlo un administrador. Contacte con la empresa',
                     ];
                    $this->view->show('Login', $parametros);
                }
            } else {
                $this->mensajes[] = [
                'tipo' => 'danger',
                'mensaje' => 'Usuario y contraseña no coinciden o no existe!! :( ',
             ];
                $this->view->show('Login', $parametros);
            }
        } else {
            //Definimos el mensaje para el alert de la vista de que se produjeron errores
            $this->mensajes[] = [
                'tipo' => 'danger',
                'mensaje' => 'Usuario y o contraseña vacios!! :( ',
             ];
            $this->view->show('Login', $parametros);
        }
        $this->view->show('Login', $parametros);
    }

    /**
     * Podemos implementar la acción registro de usuarios.
     *
     * @return void
     */
    public function register()
    {
    }

    /*
     * Otras acciones que puedan ser necesarias
     */
}
