<?php
     require_once 'includes/head.php';
    ?>

   <div class="centrar">
      <div class="container centrar">
         
         <div class="container cuerpo text-center centrar">
            <p>
               <h2>Recordar Password Usuario</h2>
            </p>
         </div>
         <?php foreach ($mensajes as $mensaje) : ?>
            <div class="alert alert-<?= $mensaje['tipo']; ?>"><?= $mensaje['mensaje']; ?></div>
         <?php endforeach; ?>
         <form action="?controller=index&accion=login" method="post" enctype="multipart/form-data">
            <label for="txtemail">Introduce tu Email
               <input type="text" class="form-control" name="txtemail" ></label>
            <br />
            
            <label for="txtpassword">Introduce una nueva contraseña
               <input type="password" class="form-control" name="txtpassword" ></label>
            <br />
            <label for="txtpassword2">Otra vez
               <input type="password" class="form-control" name="txtpassword2" ></label>
            <br />
            
            <input type="submit" value="Guardar" name="submit" class="btn btn-success">
            <a href="?controller=index&accion=login"><input type="button" value="Volver a login" class="btn btn-warning"/></a>
         </form>
      </div>

<?php
require_once 'includes/tail.php';
?>