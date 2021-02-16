<?php
     require_once 'includes/head.php';
    ?>

   <div class="centrar">
      <div class="container centrar">
         <div class="container cuerpo text-center centrar">
            <p>
               <h2>Registrar Usuario</h2>
            </p>
         </div>
         <?php foreach ($mensajes as $mensaje) : ?>
            <div class="alert alert-<?= $mensaje['tipo']; ?>"><?= $mensaje['mensaje']; ?></div>
         <?php endforeach; ?>
         <form action="?controller=user&accion=actuser" method="post" enctype="multipart/form-data">
         <label for="txtnif">Nif
               <input type="text" class="form-control" name="txtnif" value="<?= $datos['txtnif']; ?>"></label>
            <br />
            <label for="txtnombre">Nombre
               <input type="text" class="form-control" name="txtnombre" value="<?= $datos['txtnombre']; ?>"></label>
            <br />
            <label for="txtapellidos">Apellidos
               <input type="text" class="form-control" name="txtapellidos" value="<?= $datos['txtapellidos']; ?>"></label>
            <br />
            <label for="txtpassword">Contraseña
               <input type="password" class="form-control" name="txtpassword"  ></label>
            <br />
            <label for="txtpassword2">Repetir contraseña
               <input type="password" class="form-control" name="txtpassword2" ></label>
            <br />
            <label for="txtemail">Email
               <input type="email" class="form-control" name="txtemail" value="<?= $datos['txtemail']; ?>" ></label>
            <br />
            <label for="txtdireccion">Direccion
               <input type="text" class="form-control" name="txtdireccion" value="<?= $datos['txtdireccion']; ?>"></label>
            <br />
            <label for="txttelefono">Telefono
               <input type="text" class="form-control" name="txttelefono" value="<?= $datos['txttelefono']; ?>"></label>
            <br />
            <label for="txttelefono">Telefono
               <input type="text" class="form-control" name="txttelefono" value="<?= $datos['txttelefono']; ?>"></label>
            <br />
            <label for="txtrol_id">Radio
            <?php
              if ($datos['txtrol_id'] == 0) {
                  ?>
<input type="radio" name="txtrol_id" value="1" class="radio" /> Usuario

            <?php
              } else {
                  ?>
            <input type="radio" name="txtrol_id" value="0" class="radio" /> Administrador
            <?php
              } ?>

            
            
            <input type="submit" value="Guardar" name="submit" class="btn btn-success">
         </form>
      </div>

<?php
require_once 'includes/tail.php';
?>