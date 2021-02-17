<?php
     require_once 'includes/head.php';
    ?>

   <div class="centrar">
      <div class="container centrar">
         <div class="container cuerpo text-center centrar">
            <p>
               <h2>Actualizar datos usuario</h2>
            </p>
         </div>
         <?php foreach ($mensajes as $mensaje) : ?>
            <div class="alert alert-<?= $mensaje['tipo']; ?>"><?= $mensaje['mensaje']; ?></div>
         <?php endforeach; ?>
         
         <form action="?controller=user&accion=actuser" method="post" enctype="multipart/form-data">
         
              
          
         <label for="txtnif">Nif
               <input type="text" class="form-control" name="txtnif" value="<?= $datos['txtnif']; ?>" required></label>
            <br />
            <label for="txtnombre">Nombre
               <input type="text" class="form-control" name="txtnombre" value="<?= $datos['txtnombre']; ?>" required></label>
            <br />
            <label for="txtapellidos">Apellidos
               <input type="text" class="form-control" name="txtapellidos" value="<?= $datos['txtapellidos']; ?>" required></label>
            <br />
            <label for="txtpassword">Contraseña
               <input type="password" class="form-control" name="txtpassword"  value='' ></label>
            <br />
            <label for="txtpassword2">Repetir contraseña
               <input type="password" class="form-control" name="txtpassword2" value='' ></label>
            <br />
            <label for="txtemail">Email
               <input type="email" class="form-control" name="txtemail" value="<?= $datos['txtemail']; ?>" required></label>
            <br />
            <label for="txtdireccion">Direccion
               <input type="text" class="form-control" name="txtdireccion" value="<?= $datos['txtdireccion']; ?>" required></label>
            <br />
            <label for="txttelefono">Telefono
               <input type="text" class="form-control" name="txttelefono" value="<?= $datos['txttelefono']; ?>" required></label>
            <br />
            <label for="txtrol_id">
            
<input type="radio" name="txtrol_id" value="0" class="form-control" <?php if ($datos['txtrol_id'] == 0) {
        echo 'checked';
    }?> /> Administrador

            
            <input type="radio" name="txtrol_id" value="1" class="form-control" <?php if ($datos['txtrol_id'] == 1) {
        echo 'checked';
    }?> /> Usuario
            

            
            <br />
            <input type="hidden"  name="id" value="<?php echo $id; ?>">
            <input type="submit" value="Actualizar" name="submit" class="btn btn-success">

            <a href="?controller=user&accion=listado&pagina=1&regsxpag=5"><input type="button" value="Volver" class="btn btn-warning"/></a>
         </form>
      </div>

<?php
require_once 'includes/tail.php';
?>