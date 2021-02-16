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
    }

    public function delActividad()
    {
    }

    public function actActividad()
    {
    }

    public function listadoActividades()
    {
    }

    public function horario()
    {
        // $parametros = [
        //     'tituloventana' => 'Horario',
        //     'datos' => null,
        //     'mensajes' => [],
        //  ];

        //  $horario = $this->modelo->ActivityModel();
        //  $horario = $horario->listadoHorario();

        // $this->view->show('Horario', $parametros);
    }

    public function apuntarActividad()
    {
    }
}
