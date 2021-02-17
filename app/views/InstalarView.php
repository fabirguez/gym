<?php
     require_once 'includes/head.php';
    ?>

<?php
      if (!$_POST) {
          formulario($error = false);
      } else {
          creaConfig($_POST['servidor'], $_POST['usuario'], $_POST['password'], $_POST['basedatos']);
          $error = errores();
          if ($error) {
              formulario($error);
          } else {
              creaBD();
          }
      }
    ?>

<?php
require_once 'includes/tail.php';
?>

<?php

/**
 * Formulario del instalador.
 *
 * @param bool $error Indica si se ha producido algún error al enviar el formulario
 */
function formulario($error)
{
    ?>
  <div class="container">
    <div class="col-md-8 offset-md-2">
      <div class="card card-block">
        <h2 class="card-title text-md-center">Instalador de la base de datos</h2>
        <hr> 
          <?php if ($error) { ?>
            <div class="row">
              <div class="alert alert-danger col-md-4 offset-md-4 text-md-center">
                  Los datos especificados en el formulario son incorrectos :(!!
              </div>
            </div> 
          <?php } ?>
        <form method="post">
          <div class="form-group row">
            <label for="servidor" class="col-md-3 col-form-label"><strong>Servidor</strong></label>
            <div class="col-md-9">
              <input type="text" class="form-control" name="servidor" value="<?php  if (isset($_POST['servidor'])) {
        echo $_POST['servidor'];
    } ?>"></input>
            </div>
          </div>
          <div class="form-group row">
            <label for="usuario" class="col-md-3 col-form-label"><strong>Usuario</strong></label>
            <div class="col-md-9">
              <input class="form-control" type="text" name="usuario" value="<?php if (isset($_POST['usuario'])) {
        echo $_POST['usuario'];
    } ?>"></input>
            </div>
          </div>
          <div class="form-group row">
            <label for="password" class="col-md-3 col-form-label"><strong>Contraseña</strong></label>
            <div class="col-md-9">
              <input class="form-control" type="password" name="password" value="<?php if (isset($_POST['password'])) {
        echo $_POST['password'];
    } ?>">
            </div>
          </div>
          <div class="form-group row">
            <label for="basedatos" class="col-md-3 col-form-label"><strong>Base de datos</strong></label>
            <div class="col-md-9">
              <input class="form-control" type="text" name="basedatos" value="<?php if (isset($_POST['basedatos'])) {
        echo $_POST['basedatos'];
    } ?>"></input>
            </div>
          </div>
          <div class="text-md-right">
            <input type="submit" value="Aceptar" class="btn btn-primary">
          </div>
        </form>
      </div>
    </div>
  </div> 
    
<?php
} // función formulario($error)

/**
 * Nos indica si se produce algún error al enviar el formulario.
 *
 * @return bool
 */
function errores()
{
    //Cargamos los valores de configuración que deberá tener nuestra base de datos
    include 'config.php';
    $db_conf = new db_conf();
    //Verificamos que los valores del formulario coinciden con los de nuestro fichero de configuración
    if ($_POST['servidor'] != $db_conf->servidor || $_POST['usuario'] != $db_conf->usuario || $_POST['password'] != $db_conf->password || empty(trim($_POST['basedatos']))) {
        return true;
    } else {
        return false;
    }
}

function creaConfig($servidor, $usuario, $password, $basedatos)
{
    //Generamos un fichero con la información de configuración de la BD
    //Abrimos el fichero config.php en modo escritura mediante la función fopen (crear/leer archivos)
    $file = fopen('config.php', 'w');
    //Escribimos en el fichero los datos de configuración, de los que sólo es modificable el nombre de la base datos
    fwrite($file, '
    <?php
      class db_conf {
        public $servidor  = "'.$servidor.'";
        public $usuario   = "'.$usuario.'";
        public $password  = "'.$password.'";
        public $basedatos = "'.$basedatos.'";
      }
      
  ');
    //Cerramos el fichero
    fclose($file);
    unset($file);
}

/**
 * Funcion que  crea la base de datos en función de los datos del archivo de configuración.
 */
function creaBD()
{
    // Incluimos el contenido del archivo de configuración...
    require_once 'config.php';
    //Creamos un objeto de la clase db_conf, que es la definida en config.php
    $db_conf = new db_conf();
    //Definimos el conjunto de instrucciones SQL para crear nuestra base de datos
    $creabd = 'CREATE DATABASE IF NOT EXISTS `'.$db_conf->basedatos.'` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
  	     USE `'.$db_conf->basedatos.'`;';
    //Le añadimos a la consulta de creación el contenido del script sql de nuestros elementos de la base de datos
    $sql = $creabd.file_get_contents('install/gimnasio.sql');
    //Creamos nuestra base de datos como una instancia PDO
    $bd = new PDO('mysql:host='.$db_conf->servidor, $db_conf->usuario, $db_conf->password);
    try {
        $bd->exec($sql);
    } catch (PDOException $e) {
        echo $e->getMessage();
        // die();
    } ?>
  <div class="card card-block text-md-center col-md-6 offset-md-3">
    <h1>Base de datos creada con éxito :)</h1>
    <a href="?controller=index&accion=index" class="btn btn-primary">Ir a la aplicación...</a>
  </div> 
        
<?php
}
?>