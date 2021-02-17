<?php

/**
 *   Clase 'UserModel' que implementa el modelo de usuarios de nuestra aplicación en una
 * arquitectura MVC. Se encarga de gestionar el acceso a la tabla usuarios.
 */
class UserModel extends BaseModel
{
    private $id;
    private $nif;
    private $nombre;
    private $apellidos;
    private $email;
    private $password;
    private $telefono;
    private $direccion;
    private $estado;
    private $imagen;
    private $rol_id;

    public function __construct()
    {
        // Se conecta a la BD
        parent::__construct();
        $this->table = 'usuarios';
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setNif($nif)
    {
        $this->nif = $nif;
    }

    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = ($password);
    }

    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;
    }

    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;
    }

    public function setEstado($estado)
    {
        $this->estado = $estado;
    }

    public function setImagen($imagen)
    {
        $this->imagen = $imagen;
    }

    public function setRol_id($rol_id)
    {
        $this->rol_id = $rol_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getApellidos()
    {
        return $this->apellidos;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getTelefono()
    {
        return $this->telefono;
    }

    public function getDireccion()
    {
        return $this->direccion;
    }

    public function getEstado()
    {
        return $this->estado;
    }

    public function getImagen()
    {
        return $this->imagen;
    }

    public function getRol_id()
    {
        return $this->rol_id;
    }

    private function getHashedPassword($password)
    {
        //encripta la contraseña, el cost es el numero de veces que la encripta
        return password_hash($password, PASSWORD_DEFAULT, ['cost' => 10]);
    }

    //CREO Q LISTADO YA ESTA

    /**
     * Función que realiza el listado de todos los usuarios registrados
     * Devuelve un array asociativo con tres campos:
     * -'correcto': indica si el listado se realizó correctamente o no.
     * -'datos': almacena todos los datos obtenidos de la consulta.
     * -'error': almacena el mensaje asociado a una situación errónea (excepción).
     *
     * @return type
     *
     * Obtiene una página de resgistros de users
     *
     * @param int $regsxpag
     *
     * @return array con los datos a usar en la vista
     */
    public function listado($regsxpag, $offset)
    {
        $return = [
         'correcto' => false,
         'datos' => null,
         'error' => null,
        //  'datos' => $resultSet,
        //  'numpaginas' => $numpaginas,
        //  'regsxpag' => $regsxpag,
        //  'totalregistros' => $totalregistros,
        //  'sql' => $sql,
      ];

        // $resultSet = null;

        //Definimos la variable $offset que indique la posición del registro desde el que se
        // mostrarán los registros de una página dentro de la paginación.
        // $offset = ($pagina > 1) ? (($pagina - 1) * $regsxpag) : 0;

        // //Calculamos el número de registros obtenidos
        // $totalregistros = $this->db->query('SELECT count(*) as total FROM usuarios');
        // $totalregistros = $totalregistros->fetch()['total'];

        // $numpaginas = ceil($totalregistros / $regsxpag);

        //Realizamos la consulta...
      try {  //Definimos la instrucción SQL
         $sql = 'SELECT * FROM usuarios ORDER BY usuarios.estado LIMIT '.$offset.','.$regsxpag.'';
          // Hacemos directamente la consulta al no tener parámetros
          $resultsquery = $this->db->query($sql);
          $resultsquery->execute();
          //Supervisamos si la inserción se realizó correctamente...
          if ($resultsquery) :
            $return['correcto'] = true;
          $return['datos'] = $resultsquery->fetchAll(PDO::FETCH_ASSOC);
          endif; // o no :(
      } catch (PDOException $ex) {
          $return['error'] = $ex->getMessage();
      }

        return $return;
    }

    /**
     * Método que elimina el usuario cuyo id es el que se le pasa como parámetro.
     *
     * @param $id es un valor numérico. Es el campo clave de la tabla
     *
     * @return bool
     */
    public function deluser($id)
    {
        // La función devuelve un array con dos valores:'correcto', que indica si la
        // operación se realizó correctamente, y 'mensaje', campo a través del cual le
        // mandamos a la vista el mensaje indicativo del resultado de la operación
        $return = [
         'correcto' => false,
         'error' => null,
      ];
        //Si hemos recibido el id y es un número realizamos el borrado...
        if ($id && is_numeric($id)) {
            try {
                //Inicializamos la transacción
                $this->db->beginTransaction();
                //Definimos la instrucción SQL parametrizada
                $sql = 'DELETE FROM usuarios WHERE id=:id';
                $query = $this->db->prepare($sql);
                $query->execute(['id' => $id]);
                //Supervisamos si la eliminación se realizó correctamente...
                if ($query) {
                    $this->db->commit();  // commit() confirma los cambios realizados durante la transacción
                    $return['correcto'] = true;
                } // o no :(
            } catch (PDOException $ex) {
                $this->db->rollback(); // rollback() se revierten los cambios realizados durante la transacción
                $return['error'] = $ex->getMessage();
            }
        } else {
            $return['correcto'] = false;
        }

        return $return;
    }

    /**
     * @param type $datos
     *
     * @return type
     */
    public function adduser($datos)
    {
        $return = [
         'correcto' => false,
         'error' => null,
      ];

        try {
            //Inicializamos la transacción
            $this->db->beginTransaction();
            //Definimos la instrucción SQL parametrizada
            $sql = 'INSERT INTO usuarios(nif,nombre,apellidos,email,password,telefono,direccion,estado,imagen,rol_id) 
                         VALUES(:nif, :nombre, :apellidos, :email, :password, :telefono, :direccion, :estado, :imagen, :rol_id)';
            // Preparamos la consulta...
            $query = $this->db->prepare($sql);
            // y la ejecutamos indicando los valores que tendría cada parámetro
            $query->execute([
                'nif' => $datos['nif'],
                'nombre' => $datos['nombre'],
                'apellidos' => $datos['apellidos'],
                'email' => $datos['email'],
                'password' => $datos['password'],
                'telefono' => $datos['telefono'],
                'direccion' => $datos['direccion'],
                'estado' => $datos['estado'],
                'imagen' => $datos['imagen'],
                'rol_id' => $datos['rol_id'],
         ]); //Supervisamos si la inserción se realizó correctamente...
            if ($query) {
                $this->db->commit(); // commit() confirma los cambios realizados durante la transacción
                $return['correcto'] = true;
            } // o no :(
        } catch (PDOException $ex) {
            $this->db->rollback(); // rollback() se revierten los cambios realizados durante la transacción
            $return['error'] = $ex->getMessage();
            //die();
        }

        return $return;
    }

    public function actuser($datos)
    {
        $return = [
         'correcto' => false,
         'error' => null,
      ];

        try {
            //Inicializamos la transacción
            $this->db->beginTransaction();
            //Definimos la instrucción SQL parametrizada
            $sql = 'UPDATE usuarios SET nif = :nif,nombre = :nombre,apellidos = :apellidos ,email = :email,
                                    password = :password,telefono = :telefono,direccion = :direccion,imagen = :imagen, rol_id = :rol_id WHERE id = :id ';
            $query = $this->db->prepare($sql);
            $query->execute([
                'id' => $datos['id'],
                'nif' => $datos['nif'],
                'nombre' => $datos['nombre'],
                'apellidos' => $datos['apellidos'],
                'email' => $datos['email'],
                'password' => $datos['password'],
                'telefono' => $datos['telefono'],
                'direccion' => $datos['direccion'],
                'imagen' => $datos['imagen'],
                'rol_id' => $datos['rol_id'],
         ]);
            //Supervisamos si la inserción se realizó correctamente...
            if ($query) {
                $this->db->commit();  // commit() confirma los cambios realizados durante la transacción
                $return['correcto'] = true;
            } // o no :(
        } catch (PDOException $ex) {
            $this->db->rollback(); // rollback() se revierten los cambios realizados durante la transacción
            $return['error'] = $ex->getMessage();
            //die();
        }

        return $return;
    }

    public function listausuario($id)
    {
        $return = [
         'correcto' => false,
         'datos' => null,
         'error' => null,
      ];

        if ($id && is_numeric($id)) {
            try {
                $sql = 'SELECT * FROM usuarios WHERE id=:id';
                $query = $this->db->prepare($sql);
                $query->execute(['id' => $id]);
                //Supervisamos que la consulta se realizó correctamente...
                if ($query) {
                    $return['correcto'] = true;
                    $return['datos'] = $query->fetch(PDO::FETCH_ASSOC);
                } // o no :(
            } catch (PDOException $ex) {
                $return['error'] = $ex->getMessage();
                //die();
            }
        }

        return $return;
    }

    public function existeEmail($email)
    {
        $errores = [];
        if ($email) {
            try {
                $sql = 'SELECT count(*) as total FROM usuarios WHERE email=:email';
                $query = $this->db->prepare($sql);
                $query->execute(['email' => $email]);
                $query = $query->fetchAll(PDO::FETCH_ASSOC);
                $total = $query[0]['total'];

                if ($total > 0) {
                    $errores['email'] = 'El correo ya existe';
                } // o no :(
            } catch (PDOException $ex) {
                $errores['error'] = $ex->getMessage();
                //die();
            }
        }

        return $errores;
    }

    public function filtraDatos($filtra)
    {
        $errores = [];

        if (!preg_match('/^[0-9]{8,8}[A-Za-z]$/', $filtra['nif'])) {
            $errores['DNI'] = 'Introduce un DNI correcto.';
        }
        if (!filter_var($filtra['email'], FILTER_VALIDATE_EMAIL)) {
            $errores['email'] = 'Introduce un email correcto.';
        }
        if (array_key_exists('password', $errores)) {
            if (!preg_match('/^(?=.*\d)(?=.*[\u0021-\u002b\u003c-\u0040])(?=.*[A-Z])(?=.*[a-z])\S{8,16}$/', $filtra['password'])) {
                $errores['password'] = 'Introduce una contraseña con al menos un dígito, una minúscula y una mayúscula.';
            }
        }
        if (!preg_match('/^[679]{1}\d{8}$/', $filtra['telefono'])) {
            $errores['telefono'] = 'Introduce un telefono válido español.';
        }

        return $errores;
    }

    public function comparaPassword($password, $password2)
    {
        $errores = [];

        try {
            if ($password != $password2) {
                $errores['email'] = 'Las contraseñas no coinciden';
            } // o no :(
        } catch (PDOException $ex) {
            $errores['error'] = $ex->getMessage();
            //die();
        }

        return $errores;
    }

    public function tryLogin($email, $password)
    {
        $return = [
            'correcto' => false,
            'datos' => null,
            'error' => null,
         ];

        try {
            $sql = "SELECT * FROM usuarios WHERE email='".$email."' AND password = '".$password."'";
            $query = $this->db->prepare($sql);
            $query->execute(['email' => $email,
                                'password' => $password, ]);

            if ($query->rowCount() > 0) {
                $return['datos'] = $query->fetch(PDO::FETCH_ASSOC);
                $return['correcto'] = true;
            } // o no :(
        } catch (PDOException $ex) {
            $return['error'] = $ex->getMessage();
            //die();
        }

        return $return;
    }

    public function rolEmail($email)
    {
        $return = [
            'correcto' => false,
            'datos' => null,
            'error' => null,
         ];

        $sql = "SELECT rol_id FROM usuarios WHERE email='".$email."'";
        $query = $this->db->prepare($sql);
        $query->execute(['email' => $email]);
        $query->fetch(PDO::FETCH_ASSOC);

        try {
            if ($query) {
                $return['correcto'] = true;
                $return['datos'] = $query->fetch(PDO::FETCH_ASSOC);
            } // o no :(
        } catch (PDOException $ex) {
            $return['error'] = $ex->getMessage();
            //die();
        }

        return $return;
    }

    public function esActivo($email)
    {
        $return = [
            'correcto' => false,
            'datos' => null,
            'error' => null,
         ];

        $sql = "SELECT estado FROM usuarios WHERE email='".$email."'";
        $query = $this->db->prepare($sql);
        $query->execute(['email' => $email]);
        $query->fetch(PDO::FETCH_ASSOC);

        try {
            if ($query) {
                $return['correcto'] = true;
            } // o no :(
        } catch (PDOException $ex) {
            $return['error'] = $ex->getMessage();
            //die();
        }

        return $return;
    }

    public function actImg($datos)
    {
        $return = [
         'correcto' => false,
         'error' => null,
      ];

        try {
            //Inicializamos la transacción
            $this->db->beginTransaction();
            //Definimos la instrucción SQL parametrizada
            $sql = 'UPDATE usuarios SET imagen = :imagen WHERE id = :id';
            $query = $this->db->prepare($sql);
            $query->execute([
                'imagen' => $datos['imagen'],
         ]);
            //Supervisamos si la inserción se realizó correctamente...
            if ($query) {
                $this->db->commit();  // commit() confirma los cambios realizados durante la transacción
                $return['correcto'] = true;
            } // o no :(
        } catch (PDOException $ex) {
            $this->db->rollback(); // rollback() se revierten los cambios realizados durante la transacción
            $return['error'] = $ex->getMessage();
            //die();
        }

        return $return;
    }

    public function cuentaUser()
    {
        $sql = 'SELECT count(*) as total FROM usuarios ';
        $query = $this->db->prepare($sql);
        $query->execute();
        $query = $query->fetchAll(PDO::FETCH_ASSOC);
        $total = $query[0]['total'];

        return $total;
    }

    public function activarus($id)
    {
        $return = [
            'correcto' => false,
            'error' => null,
         ];

        try {
            //Inicializamos la transacción
            $this->db->beginTransaction();
            //Definimos la instrucción SQL parametrizada
            $sql = 'UPDATE usuarios SET usuarios.estado = :estado WHERE usuarios.id = :id';
            $query = $this->db->prepare($sql);
            $query->execute([
                   'id' => $id,
                   'estado' => '1',
            ]);
            //Supervisamos si la inserción se realizó correctamente...
            if ($query) {
                $this->db->commit();  // commit() confirma los cambios realizados durante la transacción
                $return['correcto'] = true;
            } // o no :(
        } catch (PDOException $ex) {
            $this->db->rollback(); // rollback() se revierten los cambios realizados durante la transacción
            $return['error'] = $ex->getMessage();
            //die();
        }

        return $return;
    }

    public function desactivarus($id)
    {
        $return = [
            'correcto' => false,
            'error' => null,
         ];

        try {
            //Inicializamos la transacción
            $this->db->beginTransaction();
            //Definimos la instrucción SQL parametrizada
            $sql = 'UPDATE usuarios SET usuarios.estado = :estado WHERE usuarios.id = :id';
            $query = $this->db->prepare($sql);
            $query->execute([
                   'id' => $id,
                   'estado' => '0',
            ]);
            //Supervisamos si la inserción se realizó correctamente...
            if ($query) {
                $this->db->commit();  // commit() confirma los cambios realizados durante la transacción
                $return['correcto'] = true;
            } // o no :(
        } catch (PDOException $ex) {
            $this->db->rollback(); // rollback() se revierten los cambios realizados durante la transacción
            $return['error'] = $ex->getMessage();
            //die();
        }

        return $return;
    }
}
