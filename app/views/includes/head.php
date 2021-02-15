<!-- <meta charset="UTF-8"> -->

<!-- Google Fonts -->
<!-- <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet"> -->
<!--    Referencia a la CDN de la hoja de estilos de Bootstrap-->
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous"> -->

<!-- <link href="../../assets/css/logincss.css" rel="stylesheet"> -->
<!-- <script src="../../assets/js/loginjs.js"></script> -->
<html>
<head>
<?php if (!isset($_SESSION)) {
    session_start();
}?>
<link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap.css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet"> 

<style type="text/css">
    .inicio {
        height: 100%;
        background-color: #F55048;
         /* #ebbbbb; */
        
    }

    .titulo {
        font-family: "Roboto", serif
    }

    .map-responsive{
    overflow:hidden;
    padding-bottom:50%;
    position:relative;
    height:0;
}
.map-responsive iframe{
    left:0;
    top:0;
    height:100%;
    width:100%;
    position:absolute;
}
</style>


<title><?= $tituloventana; ?></title>
<?php
require_once CONTROLLERS_FOLDER.'UserController.php';
$logged = false;
$rol_id = 3; //unregistered
if (isset($_SESSION['email'])) {
    $logged = true;
    // $userController = new UserController();
    $tipoUser = $_SESSION['rol_id'];

    if ($tipoUser == '1') {
        $rol_id = 1;
    } elseif ($tipoUser == '0') {
        $rol_id = 0;
    }
}
?>


</head>
<body class="cuerpo">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="?controller=index&accion=index"> &nbsp; Gimnasio</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
            <?php if ($rol_id == 0) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="verano.html">Actividades</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="verano.html">Horarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="verano.html">Datos Gimnasio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="verano.html">Usuarios</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?controller=index&accion=logout">Cerrar Sesion</a>
                </li>
                <?php } ?>
                <?php if ($rol_id == 1) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="primavera.html">Horario</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="verano.html">Perfil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?controller=index&accion=logout">Cerrar Sesion</a>
                </li>
                <?php } ?>
                <?php if ($rol_id > 1) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="?controller=index&accion=login">Iniciar Sesion</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="?controller=index&accion=register">Registrarse</a>
                </li>
                <?php } ?>
            </ul>

        </div>
    </nav>