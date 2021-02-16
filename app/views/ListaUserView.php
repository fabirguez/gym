<?php require_once 'includes/head.php'; ?>
<main>


      
         <!--Mostramos los mensajes que se hayan generado al realizar el listado-->
      <?php foreach ($mensajes as $mensaje) : ?> 
        <div class="alert alert-<?= $mensaje['tipo']; ?>"><?= $mensaje['mensaje']; ?></div>
      <?php endforeach; ?>

         <div class="container p-3 my-3 rounded shadow-sm">
            <div class="row">
               <div class="col-sm-5">
                  <h4 class="text-primary">Listado de Usuarios</h4>
               </div>
               <div class="col-sm-4">
                  <a class="text-blue nounderline" href="?controller=user&accion=adduser"><i class="fas fa-plus-square"></i>Nuevo usuario</a>
               </div>
               <div class="col-sm-3">
                  <div class="dropdown">
                     <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Registros por página
                     </button>
                     <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a class="dropdown-item" href="?controller=user&accion=listado&pagina=1&regsxpag=5">5</a>
                        <a class="dropdown-item" href="?controller=user&accion=listado&pagina=1&regsxpag=10">10</a>
                        <a class="dropdown-item" href="?controller=user&accion=listado&pagina=1&regsxpag=15">15</a>
                     </div>
                  </div>
               </div>
               
            </div>

         </div>
         <div class="row">
            <div class="col-md-12 mx-auto">
               <?php  ?>
               <table class="table table-striped table-hover">
                  <thead class="thead-dark">
                     <tr>
                        <td>ID</td>
                        <td>NIF</td>
                        <td>Nombre</td>
                        <td>Apellidos</td>
                        <td>Email</td>
                        <td>Telefono</td>
                        <td>Direccion</td>
                        <td>Estado</td>
                        <td>Imagen</td>
                        <td>Rol</td>
                        <td>Borrar o editar </td>
                        
                     </tr>
                  </thead>
                  <tbody>
                  <?php
                //    if ($totalregistros >= 1) :
                        foreach ($datos as $d) {
                            ?>
                     
                     
                           <tr>
                           <td><?= $d['id']; ?></td>
                              <td><?= $d['nif']; ?></td>
                              <td><?= $d['nombre']; ?></td>
                              <td><?= $d['apellidos']; ?></td>
                              <td><?= $d['email']; ?></td>
                              <td><?= $d['telefono']; ?></td>
                              <td><?= $d['direccion']; ?></td>
                              <td><?= $d['estado']; ?></td>
                              <td><img src='<?= $d['imagen']; ?>' style="width:7rem;"/></td>
                              <td><?= $d['rol_id']; ?></td>
                              <td>
                                 <a href="?controller=user&accion=actuser&id=<?= $d['id']; ?>" data-toggle="tooltip" data-placement="left" title="Editar"><i class="fas fa-edit"></i></a>
                                 <a class="confirmar" href="?controller=user&accion=deluser&id=<?= $d['id']; ?>" data-toggle="tooltip" data-placement="right" title="Borrar"><i class="fas fa-trash-alt"></i></a>
                              </td>
                           </tr>

                     <?php
                        }
                //    endif;
                     ?>
                  </tbody>
               </table>
            </div>
         </div>
         <div class="row text-center">
            <div class="col-md-6">
               <?php
               $url = '?controller=user&accion=listado'; ?>
               <?php //Sólo mostramos los enlaces a páginas si existen registros a mostrar
    if ($totalregistros >= 1):
?>
<nav aria-label="Page navigation example" class="text-center">
    <ul class="pagination">
        <?php
        //Comprobamos si estamos en la primera página. Si es así, deshabilitamos el botón 'anterior'
            if ($pagina == 1):
        ?>
        <li class="page-item disabled">
            <a class="page-link" href="#">&laquo;</a>
        </li>
        <?php else: ?>
        <li class="page-item">
            <a class="page-link" href="<?= $url; ?>&pagina=<?= $pagina - 1; ?>&regsxpag=<?= $regsxpag; ?>"> &laquo;</a>
        </li>
        <?php
        endif;
        //Mostramos como activos el botón de la página actual
        for ($i = 1; $i <= $numpaginas; ++$i) {
            if ($pagina == $i) {
                echo '<li class="page-item active"> 
                <a class="page-link" href="'.$url.'&pagina='.$i.'&regsxpag='.$regsxpag.'">'.$i.'</a></li>';
            } else {
                echo '<li class="page-item"> 
                <a class="page-link" href="'.$url.'&pagina='.$i.'&regsxpag='.$regsxpag.'">'.$i.'</a></li>';
            }
        }
        //Comprobamos si estamos en la última página. Si es así, deshabilitamos el botón 'siguiente'
        if ($pagina == $numpaginas): ?>
        <li class="page-item disabled"><a class="page-link" href="#">&raquo;</a></li>
        <?php else: ?>
        <li class="page-item">
            <a class="page-link" href="<?= $url; ?>&pagina=<?= $pagina + 1; ?>&regsxpag=<?= $regsxpag; ?>"> &raquo; </a>
        </li>
        <?php endif; ?>
    </ul>
</nav>


               <?php endif;  //if($totalregistros>=1):?>
            </div>
         </div>
    

   </main>
   <?php require_once 'includes/tail.php'; ?>