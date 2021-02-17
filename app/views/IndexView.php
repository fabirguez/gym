
    <?php require_once 'includes/head.php'; ?>

    <body style="background-image: url('assets/img/gym/fondo.jpg'); height: 100vh; ">
    <div class="container centrar">
      <div class="container cuerpo text-center">	
        <p><h1>Gimnasio Andalucía</h1> </p>
      </div>
      <ul>
         <br/><br/>
         <div class="container cuerpo text-center">	
      
         <h2>Bienvenido al portal del gimnasio Andalucía</h2>
</br></br></br>
         
<?php
          if ($_SESSION['rol_id'] == 0) {
              echo '<h2> ADMINISTRADOR </h2>';
          } elseif ($_SESSION['rol_id'] == 1) {
              echo '<h2> USUARIO </h2>';
          }
         ?>
        
        </div>
      </ul>
    </div>
    </body>

    <?php require_once 'includes/tail.php'; ?>
