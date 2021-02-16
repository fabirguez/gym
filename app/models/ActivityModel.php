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

    public function listadoActividades($id)
    {
    }

    public function addActividad($datos)
    {
    }

    public function actActividad($datos)
    {
    }

    public function delActividad($id)
    {
    }

    public function horario()
    {
    }
}
