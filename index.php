<?php
require 'database/db_conn.php';
$todos = $conn->query("SELECT * FROM todos ORDER BY id DESC");
session_start();

// Comprobar si hay un mensaje de éxito en la sesión
if(isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    // Limpiar el mensaje de éxito después de mostrarlo
    unset($_SESSION['success_message']);
}
if(isset($_SESSION['error_message'])) {
    $error_message = $_SESSION['error_message'];
    // Limpiar el mensaje de error después de mostrarlo
    unset($_SESSION['error_message']);
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>To-do List</title>
    <link rel="stylesheet" href="resources/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body >
    <div class="container-fluid">
    <div class="main-section ">
        <div class="add-section border">
            <form action="app/add.php" method="POST" autocomplete="off">

                <?php if(isset($error_message)){?>

                    <div class="alert alert-danger text-center" role="alert"> <?php echo $error_message;?></div>
                    
                    <input type="text" name="title" placeholder="Add to do item" style="border-color:rgba(255, 0, 0, 0.302)" autofocus>
                    <button type="submit" data-bs-toggle="modal" data-bs-target="#exampleModal">Agregar <i class="fa-solid fa-plus"></i></button>

                <?php }else{ ?>

                    <input type="text" name="title" placeholder="Add to do item" autofocus>
                    <button type="submit" data-bs-toggle="modal" data-bs-target="exampleModalLabel" data-bs-toggle="modal" data-bs-target="#exampleModal">Agregar <i class="fa-solid fa-plus"></i></button>

                <?php } ?>

            </form>
            
            <?php if(isset($success_message)){?>
                <div id="success-modal-trigger" style="display: none;"></div>
            <?php }?>
        </div>

                <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Tarea agregada</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        ¡Tu tarea se ha agregado correctamente!
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-success mx-auto " data-bs-dismiss="modal"><i class="fa-solid fa-check"></i></button>
                    </div>
                </div>
            </div>
        </div>



        <div class="show-list">

            <?php if($todos->rowCount() === 0){?>
                <h2 class="noItems">No hay tareas pendientes</h2>
            <?php }; ?>

            <?php while($todo = $todos->fetch(PDO::FETCH_ASSOC)){; ?>
          
                <div class="item">
                        <span id="<?= $todo['id']?>" class="removeItem"><i class="fa-solid fa-x"></i></span>

                        <?php if($todo['checked']){?>

                            <input type="checkbox" class="check-box" checked data-todo-id ="<?php echo $todo['id']; ?>">
                           
                            <h2 class="checked"><?= $todo['title']; ?></h2>
                            <small><?= $todo['date_time']; ?></small>

                        <?php }else{?>

                            <input type="checkbox" class="check-box" data-todo-id ="<?php echo $todo['id']; ?>">
                            <h2><?= $todo['title']; ?></h2>
                            <small><?= $todo['date_time']; ?></small>
                            
                        <?php };?>
                        
                </div>

            <?php }; ?>
        </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script
    src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
    crossorigin="anonymous"></script>

    <script>
          $(document).ready(function(){
            $('.removeItem').click(function(){
                const id = $(this).attr('id');
                
                $.post("app/remove.php", 
                      {
                          id: id
                      },
                      (data)  => {
                         if(data){
                             $(this).parent().hide(600);
                         }
                      }
                );
            });

            $(".check-box").click(function(e){
                const id = $(this).attr('data-todo-id');
                
                $.post('app/check.php', 
                      {
                          id: id
                      },
                      (data) => {
                          if(data != 'error'){
                              const h2 = $(this).next();
                              if(data === '1'){
                                  h2.removeClass('checked');
                              }else {
                                  h2.addClass('checked');
                              }
                          }
                      }
                );
            });
        });
    </script>

    <!--  para mostrar el modal automáticamente -->
<script>
    // Espera a que el documento esté completamente cargado
    document.addEventListener("DOMContentLoaded", function() {
        // Verifica si el div con ID "success-modal-trigger" está presente
        if (document.getElementById('success-modal-trigger')) {
            // Si está presente, muestra el modal automáticamente
            var myModal = new bootstrap.Modal(document.getElementById('exampleModal'));
            myModal.show();
        }
    });
</script>
</body>
</html>