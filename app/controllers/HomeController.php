<?php

/**
 * Controlador de la página de entrada al portal desde la que se pueden hacer las funciones que te permita tu rol.
 */
class HomeController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $parametros = [
         'tituloventana' => 'Login a la aplicación',
      ];
        $this->view->show('inicio', $parametros);
    }
}
