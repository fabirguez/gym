<?php
     require_once 'includes/head.php';
    ?>

   <div class="centrar">
      <div class="container centrar">
         <a href="?controller=home&accion=index">Inicio</a>
         <div class="container cuerpo text-center centrar">
            <p>
               <h2><img class="alineadoTextoImagen" src="images/user.png" width="50px" />Añadir Usuario</h2>
            </p>
         </div>
         <?php foreach ($mensajes as $mensaje) : ?>
            <div class="alert alert-<?= $mensaje['tipo']; ?>"><?= $mensaje['mensaje']; ?></div>
         <?php endforeach; ?>
         <form action="?controller=user&accion=adduser" method="post" enctype="multipart/form-data">
            <label for="txtnombre">Nombre
               <input type="text" class="form-control" name="txtnombre" required value="<?= $datos['txtnombre']; ?>"></label>
            <br />
            <label for="txtemail">Email
               <input type="email" class="form-control" name="txtemail" value="<?= $datos['txtemail']; ?>"></label>
            <br />
            <label for="txtpass">Contraseña
               <input type="password" class="form-control" name="txtpassword" required value="<?= $datos['txtpassword']; ?>"></label>
            <br />
            <label for="imagen">Imagen <input type="file" name="imagen" class="form-control" value="<?= $datos['imagen']; ?>" /></label>
            </br>
            <input type="submit" value="Guardar" name="submit" class="btn btn-success">
         </form>
      </div>

<?php
require_once 'includes/tail.php';
?>