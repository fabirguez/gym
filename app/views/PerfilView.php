<?php
     require_once 'includes/head.php';
    ?>

   <div class="centrar">
      <div class="container centrar">
         <div class="container cuerpo text-center centrar">
            <p>
               <h2>Perfil usuario</h2>
            </p>
         </div>
         <?php foreach ($mensajes as $mensaje) : ?>
            <div class="alert alert-<?= $mensaje['tipo']; ?>"><?= $mensaje['mensaje']; ?></div>
         <?php endforeach; ?>
         
         <form action="?controller=user&accion=perfil" method="post" enctype="multipart/form-data">
         
              
          
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
            
            <br />
            <input type="hidden"  name="id" value="<?php echo $id; ?>">
            <input type="submit" value="Actualizar" name="submit" class="btn btn-success">

           
         </form>
      </div>

<?php
require_once 'includes/tail.php';
?>