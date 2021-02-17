<?php
     require_once 'includes/head.php';
    ?>

   <div class="centrar">
      <div class="container centrar">
         <div class="container cuerpo text-center centrar">
            <p>
               <h2>Añadir datos usuario</h2>
            </p>
         </div>
         <?php foreach ($mensajes as $mensaje) : ?>
            <div class="alert alert-<?= $mensaje['tipo']; ?>"><?= $mensaje['mensaje']; ?></div>
         <?php endforeach; ?>
         
         <form action="?controller=user&accion=adduser" method="post" enctype="multipart/form-data">
         
              
          
         <label for="txtnif">Nif
               <input type="text" class="form-control" name="txtnif"  required></label>
            <br />
            <label for="txtnombre">Nombre
               <input type="text" class="form-control" name="txtnombre" required></label>
            <br />
            <label for="txtapellidos">Apellidos
               <input type="text" class="form-control" name="txtapellidos"  required></label>
            <br />
            <label for="txtpassword">Contraseña
               <input type="password" class="form-control" name="txtpassword"   required></label>
            <br />
            <label for="txtpassword2">Repetir contraseña
               <input type="password" class="form-control" name="txtpassword2" required></label>
            <br />
            <label for="txtemail">Email
               <input type="email" class="form-control" name="txtemail" required></label>
            <br />
            <label for="txtdireccion">Direccion
               <input type="text" class="form-control" name="txtdireccion"  required></label>
            <br />
            <label for="txttelefono">Telefono
               <input type="text" class="form-control" name="txttelefono"  required></label>
            <br />
            <label for="txtrol_id">
            Administrador
<input type="radio" name="txtrol_id" value="0" class="form-control" /> 
Usuario
<input type="radio" name="txtrol_id" value="1" class="form-control" checked/> 
</br> </br>
Desactivado
<input type="radio" name="txtestado" value="0" class="form-control" /> 
Activado
<input type="radio" name="txtestado" value="1" class="form-control" checked/> 
            

            
            <br /> </br>
            <input type="submit" value="Guardar" name="submit" class="btn btn-success">

            <a href="?controller=user&accion=listado&pagina=1&regsxpag=5"><input type="button" value="Volver" class="btn btn-warning"/></a>
         </form>
      </div>

<?php
require_once 'includes/tail.php';
?>