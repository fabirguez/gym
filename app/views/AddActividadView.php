<?php
     require_once 'includes/head.php';
    ?>

   <div class="centrar">
      <div class="container centrar">
         <div class="container cuerpo text-center centrar">
            <p>
               <h2>Añadir actividad</h2>
            </p>
         </div>
         <?php foreach ($mensajes as $mensaje) : ?>
            <div class="alert alert-<?= $mensaje['tipo']; ?>"><?= $mensaje['mensaje']; ?></div>
         <?php endforeach; ?>
         
         <form action="?controller=activity&accion=addActividad" method="post" enctype="multipart/form-data">
         
             <label for="txtnombre">Nombre
               <input type="text" class="form-control" name="txtnombre" required></label>
            <br /> 
          
         <label for="txtaforo">Aforo
               <input type="text" class="form-control" name="txtaforo"  required></label>
            <br />
            
            <label for="txtdescripcion">Descripcion
               <input type="text" class="form-control" name="txtdescripcion"  required></label>
            <br />
            
            
            

            
            <br /> </br>
            <input type="submit" value="Guardar" name="submit" class="btn btn-success">

            <a href="?controller=activity&accion=listadoActividades&pagina=1&regsxpag=5"><input type="button" value="Volver" class="btn btn-warning"/></a>
         </form>
      </div>

<?php
require_once 'includes/tail.php';
?>