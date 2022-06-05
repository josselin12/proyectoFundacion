<?php 
  session_start();

  if(isset($_SESSION['usuario']) && $_SESSION['tipo'] == "Administrador"){
    $_SESSION['pagina'] = "Usuarios";
    /*print_r($_POST);
    echo "<br/><br/><br/>";
    print_r($_POST);
    echo "<br/>";*/

    $txtEliminar = (isset($_POST["txtEliminar"]))? $_POST["txtEliminar"]: "" ;
    
    $txtCorreo =(isset($_POST["txtCorreo"]))? $_POST["txtCorreo"]: "" ;
    $txtContra =(isset($_POST["txtContra"]))? $_POST["txtContra"]: "" ;
    $sltTipo =(isset($_POST["sltTipo"]))? intval($_POST["sltTipo"]): "" ;
    $accion =(isset($_POST["accion"]))? $_POST["accion"]: "" ;
    $txtId =(isset($_POST["txtId"]))? intval($_POST["txtId"]): "" ;
    //echo $accion."<br/>";
    //echo $txtId."ID DE CAMPO{d.ñ<br/>";
    

    if(isset($_SESSION['accionEliminar']) && $_SESSION['accionEliminar'] == "Eliminar"){
        $accion = "Eliminar";
    }

    if($txtEliminar == "NO"){
        $_SESSION['accionEliminar'] = "NO";
        $_SESSION['idEliminar'] = "";
        header('location: usuarios.php');
        $accion = "";
    }

    include('config/bd.php');

    switch($accion){
        case "Agregar":
            //INSERT INTO usuarios VALUES (default, 1, 'admin@gmail.com','4Dmin.1', default);

            if($sltTipo == "" ||$txtCorreo == "" ||$txtContra == ""){
                $accion = "Vacio";
            }else{
                $sqlSentencia = $con ->prepare("INSERT INTO usuarios (id_TipoUsuario, correo, contrasena) 
                VALUES (:id_TipoUsuario,:correo,:contrasena);");
                
                try{
                    $sqlSentencia -> bindParam(':id_TipoUsuario',$sltTipo);
                    $sqlSentencia -> bindParam(':correo',$txtCorreo);
                    $sqlSentencia -> bindParam(':contrasena',$txtContra);
                    $sqlSentencia -> execute();
                }catch(Exception $e){
                    $accion = "Error";
                }
            }
            break;
        case "Modificar":
            if($sltTipo == "" ||$txtCorreo == "" ||$txtContra == ""){
                $accion = "Vacio";
            }else{
                try{
                    $sqlSentencia = $con ->prepare("UPDATE usuarios 
                    SET correo = :correo, contrasena = :contrasena, id_TipoUsuario= :id_TipoUsuario WHERE id_Usuario = :id;");
                    $sqlSentencia -> bindParam(':correo',$txtCorreo);
                    $sqlSentencia -> bindParam(':contrasena',$txtContra);
                    $sqlSentencia -> bindParam(':id_TipoUsuario',$sltTipo);
                    $sqlSentencia -> bindParam(':id',$txtId);
                    $sqlSentencia -> execute();
                }catch(Exception $e){
                    $accion = "Error";
                }
            }
            break;
        case "Cancelar":
            header('location: usuarios.php');
            break;
        case "Seleccionar":
            $sqlSentencia = $con ->prepare("SELECT * 
                                        FROM usuarios
                                        WHERE id_Usuario = :id;");
            $sqlSentencia -> bindParam(':id',$txtId);
            $sqlSentencia -> execute();


            $usuario = $sqlSentencia -> fetch(PDO::FETCH_LAZY);
            $txtCorreo = $usuario["correo"];
            $txtContra = $usuario["contrasena"];
            $sltTipo = $usuario["id_TipoUsuario"];
            $idUsuario = $usuario["id_Usuario"];
 
            break;
        case "Eliminar":
            if($txtEliminar == ""){
                $_SESSION['accionEliminar'] = "Eliminar";
                $_SESSION['idEliminar'] = $txtId;
            }else if($txtEliminar == "SI"){
                $_SESSION['accionEliminar'] = "NO";
                
                $idEliminar = intval($_SESSION['idEliminar']);

                $sqlSentencia = $con ->prepare("UPDATE usuarios
                                            SET isActivo = 'N'
                                            WHERE id_usuario = :id;");
                $sqlSentencia -> bindParam(':id',$idEliminar);
                $sqlSentencia -> execute();
                
            }
            break;
    }

    


    $sqlSentencia = $con ->prepare("SELECT id_usuario, nombreTipo, correo, contrasena, u.isActivo 
                                        FROM usuarios u
                                        INNER JOIN tipousuario t ON u.id_TipoUsuario = t.id_TipoUsuario
                                        WHERE u.isActivo LIKE 'A'
                                        ORDER BY id_Usuario;");

    $sqlSentencia -> execute();


    $listaUsuarios = $sqlSentencia -> fetchAll(PDO::FETCH_ASSOC);


    $sqlSentencia = $con ->prepare("SELECT *
                                    FROM tipousuario
                                    WHERE isActivo = 'A'
                                    ORDER BY id_TipoUsuario;");

    $sqlSentencia -> execute();


    $listaTUsuarios = $sqlSentencia -> fetchAll(PDO::FETCH_ASSOC);
  }else{
    header("Location:error.php");
  }

 
  
?>

<!DOCTYPE html>
<html lang="es">
<?php include('template/head.php') ?>

<body id="body-pd">

    <?php include('template/barra.php') ?>

    <div style="margin-top: 80px;"></div>

    <div class="form-group" id="divId">
        <input hidden type="text" name="txtPagina" id="txtPagina" value="usuarios.php">
    </div>

    <div>
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col col-5">
                            <div class="card">
                                <h5 class="card-header">DATOS USUARIO</h5>
                                <div class="card-body">
                                    <form method="POST" enctype="multipart/form-data" >

                                        <div class="form-group" id="divId">
                                            <input hidden type="text" name="txtId" id="txtId"
                                                value="<?php echo $idUsuario ?>">

                                        </div>

                                        <div class="form-group" id="divId">
                                            <input hidden type="text" name="txAccion" id="txAccion"
                                                value="<?php echo $accion ?>">

                                        </div>




                                        <div class="form-group group-E">
                                            <label for="txtCorreo">Correo</label>
                                            <input type="text" class="form-control" name="txtCorreo" id="txtCorreo"
                                                placeholder="ejemplo@gmail.com" value="<?php echo $txtCorreo ?>">
                                        </div>

                                        <div class="form-group group-E">
                                            <label for="txtContra">Contraseña</label>
                                            <input type="password" class="form-control" name="txtContra" id="txtContra"
                                                placeholder="" value="<?php echo $txtContra ?>">
                                        </div>

                                        <div class="form-group group-E">
                                            <label for="sltTipo">Tipo Usuario</label>
                                            <select class="form-select" aria-label="Default select example"
                                                name="sltTipo" id="sltTipo">
                                                <option selected>Elige una opción</option>
                                                <?php foreach($listaTUsuarios as $tUsuario){?>

                                                <option
                                                    <?php if(intval($tUsuario["id_TipoUsuario"]) == $sltTipo){echo "selected";} ?>
                                                    value="<?php echo $tUsuario["id_TipoUsuario"]?>">
                                                    <?php echo $tUsuario["nombreTipo"]?></option>

                                                <?php } ?>
                                            </select>
                                        </div>
                                        <br />
                                        <div class="btn-group flex-wrap text-center" role="group" aria-label=""
                                            style="width: 100%;">
                                            <button type="submit" name="accion" value="Agregar"
                                                class="btn btn-primary">Agregar</button>
                                            <button type="submit" name="accion" value="Modificar"
                                                class="btn btn-warning"
                                                style="margin-left:10px!important;">Modificar</button>
                                            <button type="submit" name="accion" value="Cancelar"
                                                class="btn btn-secondary"
                                                style="margin-left:10px!important;">Cancelar</button>
                                        </div>

                                    </form>
                                </div>
                            </div>



                        </div>
                        <div class="col col-7">
                            <table class="table table-responsive table-wrapper text-center">
                                <thead>
                                    <tr>
                                        <th scope="col">Correo</th>
                                        <th scope="col">Contraseña</th>
                                        <th scope="col">Tipo Usuario</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>


                                    <?php foreach($listaUsuarios as $usuario){?>
                                    <tr>
                                        <td><?php echo $usuario["correo"]?></td>
                                        <td><?php echo $usuario["contrasena"]?></td>
                                        <td><?php echo $usuario["nombreTipo"]?></td>
                                        <td>
                                            <form method="post" id="formUser">
                                                <input hidden type="text" name="txtId" id="txtId"
                                                    value="<?php echo $usuario['id_usuario'] ?>">
                                                <input hidden type="text" name="txtEliminar" id="txtEliminar">
                                                <button type="submit" name="accion" value="Seleccionar"
                                                    class="btn btn-primary">Seleccionar</button>
                                                <button type="submit" name="accion" value="Eliminar"
                                                    class="btn btn-danger">Eliminar</button>
                                            </form>

                                        </td>
                                    </tr>

                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>


<?php include('template/scripts.php') ?>
<script type="text/javascript" src="js/var/usuarios.js"></script>
<script type="text/javascript" src="js/notify.js"></script>

</html>