<?php

/**
 *   Clase 'ActivityModel' que implementa el modelo de usuarios de nuestra aplicación en una
 * arquitectura MVC. Se encarga de gestionar el acceso a la tabla usuarios.
 */
class ActivityModel extends BaseModel
{
    private $id;
    private $nombre;
    private $descripcion;
    private $aforo;

    public function __construct()
    {
        // Se conecta a la BD
        parent::__construct();
        $this->table = 'actividades';
    }

    public function setid($id)
    {
        $this->id = $id;
    }

    public function setnombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function setdescripcion($descripcion)
    {
        $this->descripcion = $descripcion;
    }

    public function setaforo($aforo)
    {
        $this->aforo = $aforo;
    }

    public function getid()
    {
        return $this->id;
    }

    public function getnombre()
    {
        return $this->nombre;
    }

    public function getdescripcion()
    {
        return $this->descripcion;
    }

    public function getaforo()
    {
        return $this->aforo;
    }

    public function listadoActividades($regsxpag, $offset)
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
         $sql = 'SELECT * FROM actividades ORDER BY actividades.id LIMIT '.$offset.','.$regsxpag.'';
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

    public function addActividad($datos)
    {
        $return = [
         'correcto' => false,
         'error' => null,
      ];

        try {
            //Inicializamos la transacción
            $this->db->beginTransaction();
            //Definimos la instrucción SQL parametrizada
            $sql = 'INSERT INTO actividades(nombre,descripcion,aforo) 
                         VALUES(:nombre, :descripcion, :aforo)';
            // Preparamos la consulta...
            $query = $this->db->prepare($sql);
            // y la ejecutamos indicando los valores que tendría cada parámetro
            $query->execute([
                'nombre' => $datos['nombre'],
                'descripcion' => $datos['descripcion'],
                'aforo' => $datos['aforo'],
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

    public function actActividad($datos)
    {
        $return = [
         'correcto' => false,
         'error' => null,
      ];

        try {
            //Inicializamos la transacción
            $this->db->beginTransaction();
            //Definimos la instrucción SQL parametrizada
            $sql = 'UPDATE actividades SET nombre = :nombre,descripcion = :descripcion ,aforo = :aforo WHERE id = :id ';
            $query = $this->db->prepare($sql);
            $query->execute([
                'id' => $datos['id'],
                'nombre' => $datos['nombre'],
                'descripcion' => $datos['descripcion'],
                'aforo' => $datos['aforo'],
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

    public function delActividad($id)
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
                $sql = 'DELETE FROM actividades WHERE id=:id';
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

    public function filtraDatos($filtra)
    {
        $errores = [];

        if (!preg_match('/^([0-9])*$/', $filtra['aforo'])) {
            $errores['aforo'] = 'Introduce un numero.';
        }

        return $errores;
    }

    public function existeActividad($nombre)
    {
        $errores = [];
        if ($nombre) {
            try {
                $sql = 'SELECT count(*) as total FROM actividades WHERE nombre=:nombre';
                $query = $this->db->prepare($sql);
                $query->execute(['nombre' => $nombre]);
                $query = $query->fetchAll(PDO::FETCH_ASSOC);
                $total = $query[0]['total'];

                if ($total > 0) {
                    $errores['nombre'] = 'La actividad ya existe';
                } // o no :(
            } catch (PDOException $ex) {
                $errores['error'] = $ex->getMessage();
                //die();
            }
        }

        return $errores;
    }

    public function listaActividad($id)
    {
        $return = [
         'correcto' => false,
         'datos' => null,
         'error' => null,
      ];

        if ($id && is_numeric($id)) {
            try {
                $sql = 'SELECT * FROM actividades WHERE id=:id';
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

    public function cuentaActividad()
    {
        $sql = 'SELECT count(*) as total FROM actividades ';
        $query = $this->db->prepare($sql);
        $query->execute();
        $query = $query->fetchAll(PDO::FETCH_ASSOC);
        $total = $query[0]['total'];

        return $total;
    }

    public function horario()
    {
    }
}
