<?php
     require_once 'includes/head.php';
    ?>

   <div class="centrar">
      <div class="container centrar">
         
         <div class="container cuerpo text-center centrar">
            <p>
               <h2>Logear Usuario</h2>
            </p>
         </div>
         <?php foreach ($mensajes as $mensaje) : ?>
            <div class="alert alert-<?= $mensaje['tipo']; ?>"><?= $mensaje['mensaje']; ?></div>
         <?php endforeach; ?>
         <form action="?controller=index&accion=login" method="post" enctype="multipart/form-data">
            <label for="txtemail">Email
               <input type="text" class="form-control" name="txtemail" ></label>
            <br />
            
            <label for="txtpassword">Contraseña
               <input type="password" class="form-control" name="txtpassword" ></label>
            <br />
            
            <input type="submit" value="Guardar" name="submit" class="btn btn-success">
            <a href="?controller=index&accion=recordarPassword"><input type="button" value="Recordar contraseña" class="btn btn-warning"/></a>
         </form>
      </div>

<?php
require_once 'includes/tail.php';
?>