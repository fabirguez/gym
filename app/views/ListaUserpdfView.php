<html>
<head>
<link rel="stylesheet" type="text/css" href="../../assets/css/bootstrap.css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet"> 

<style type="text/css">

    .map-responsive{
    overflow:hidden;
    padding-bottom:50%;
    position:relative;
    height:0;
}
.map-responsive iframe{
    left:0;
    top:0;
    height:100%;
    width:100%;
    position:absolute;
}
</style>
</head>
<body>

         
         <div class="row">
            <div class="col-md-12 mx-auto">
               <?php  ?>
               <table class="table table-striped table-hover">
                  <thead class="thead-dark">
                     <tr>
                        <!-- <td>ID</td> -->
                        <td>NIF</td>
                        <td>Nombre</td>
                        <td>Apellidos</td>
                        <td>Email</td>
                        <td>Telefono</td>
                        <td>Direccion</td>
                        <td>Estado</td>
                        <td>Imagen</td>
                        <td>Rol</td>
                        
                        
                     </tr>
                  </thead>
                  <tbody>
                  <?php
                //    if ($totalregistros >= 1) :
                        foreach ($datos as $d) {
                            ?>
                     
                     
                           <tr>
                           
                              <td><?= $d['nif']; ?></td>
                              <td><?= $d['nombre']; ?></td>
                              <td><?= $d['apellidos']; ?></td>
                              <td><?= $d['email']; ?></td>
                              <td><?= $d['telefono']; ?></td>
                              <td><?= $d['direccion']; ?></td>
                              <td><?php echo ($d['estado'] == 1) ? 'Activo' : 'Inactivo'; ?></td>
                              <td><img src='<?= $d['imagen']; ?>' style="width:7rem;"/></td>
                              <td><?php echo ($d['rol_id'] == 0) ? 'Administrador' : 'Usuario'; ?></td>
                              
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
               
               
</body>
</html>