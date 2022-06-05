<?php 
  session_start();

  if(isset($_SESSION['usuario']) && $_SESSION['tipo'] == "Administrador"){
    $_SESSION['pagina'] = "Alumnos";
    /*echo "<br/><br/><br/><br/><br/>";
    print_r($_POST);*/
    $sltCorreo =(isset($_POST["sltCorreo"]))? intval($_POST["sltCorreo"]): "" ;
    $sltDatosE =(isset($_POST["sltDatosE"]))? intval($_POST["sltDatosE"]): "" ;
    $sltBeca =(isset($_POST["sltBeca"]))? $_POST["sltBeca"]: "" ;

    $txtMatricula =(isset($_POST["txtMatricula"]))? $_POST["txtMatricula"]: "" ;
    $txtNombre =(isset($_POST["txtNombre"]))? $_POST["txtNombre"]: "" ;
    $txtAP =(isset($_POST["txtAP"]))? $_POST["txtAP"]: "" ;
    $txtAM =(isset($_POST["txtAM"]))? $_POST["txtAM"]: "" ;
    


    $accion =(isset($_POST["accion"]))? $_POST["accion"]: "" ;
    $txtId =(isset($_POST["txtId"]))? intval($_POST["txtId"]): "" ;

    
    /*echo $accion."<br/>";
    echo $sltCorreo."<br/>";
    echo $sltDatosE."<br/>";
    echo $sltBeca."<br/>";
    echo $txtMatricula."<br/>";
    echo $txtNombre."<br/>";
    echo $txtAP."<br/>";
    echo $txtAM."<br/>";
    echo $txtId."ID DE CAMPO<br/>";*/

    include('config/bd.php');

    switch($accion){
        case "Agregar":
            //INSERT INTO alumnos VALUES (default, 2, 1, 5151651,'Jose Luis', 'Castro', 'Guzman', true, default);
            
            $sqlSentencia = $con ->prepare("INSERT INTO alumnos VALUES (default, :id_Usuario,
             :id_DatosEscolar, :matricula,:nombreAlumno,:apellidoPAlumno,:apellidoMAlumno, :isBecario, default);");
            
            $sqlSentencia -> bindParam(':id_Usuario',$sltCorreo);
            $sqlSentencia -> bindParam(':id_DatosEscolar',$sltDatosE);
            $sqlSentencia -> bindParam(':matricula',$txtMatricula);
            $sqlSentencia -> bindParam(':nombreAlumno',$txtNombre);
            $sqlSentencia -> bindParam(':apellidoPAlumno',$txtAP);
            $sqlSentencia -> bindParam(':apellidoMAlumno',$txtAM);
            $sqlSentencia -> bindParam(':isBecario',$sltBeca);
            $sqlSentencia -> execute();
            break;
        case "Modificar":

            $sqlSentencia = $con ->prepare("UPDATE alumnos SET id_DatosEscolar = :id_DatosEscolar,isBecario = :isBecario,
            matricula = :matricula, nombreAlumno = :nombreAlumno, apellidoPAlumno = :apellidoPAlumno, apellidoMAlumno = :apellidoMAlumno 
            WHERE id_Alumno = :id_Alumno;");
            
            $sqlSentencia -> bindParam(':id_DatosEscolar',$sltDatosE);
            $sqlSentencia -> bindParam(':matricula',$txtMatricula);
            $sqlSentencia -> bindParam(':isBecario',$sltBeca);
            $sqlSentencia -> bindParam(':nombreAlumno',$txtNombre);
            $sqlSentencia -> bindParam(':apellidoPAlumno',$txtAP);
            $sqlSentencia -> bindParam(':apellidoMAlumno',$txtAM);
            $sqlSentencia -> bindParam(':id_Alumno',$txtId);
            $sqlSentencia -> execute();

            header('location: alumnos.php');
            break;
        case "Cancelar":
            header('location: alumnos.php');
            break;
        case "Seleccionar":
            $sqlSentencia = $con ->prepare("SELECT * 
                                            FROM alumnos 
                                            WHERE id_Alumno = :id");
            $sqlSentencia -> bindParam(':id',$txtId);
            $sqlSentencia -> execute();


            $alumno = $sqlSentencia -> fetch(PDO::FETCH_LAZY);
            $sltCorreo = $alumno["id_Usuario"];
            $sltDatosE = $alumno["id_DatosEscolar"];
            $sltBecado = $alumno["isBecario"];
            $txtMatricula = $alumno["matricula"];
            $txtNombre = $alumno["nombreAlumno"];
            $txtAP = $alumno["apellidoPAlumno"];
            $txtAM = $alumno["apellidoMAlumno"];
            
            $id_Alumno= $alumno["id_Alumno"];
            break;
        case "Eliminar":
            $sqlSentencia = $con ->prepare("UPDATE Alumnos
                                            SET isActivo = 'N'
                                            WHERE id_Alumno = :id;");
            $sqlSentencia -> bindParam(':id',$txtId);
            $sqlSentencia -> execute();
            break;
    }


    $sqlSentencia = $con ->prepare("SELECT id_Alumno, CONCAT(nombreAlumno, ' ', apellidoPAlumno, ' ', apellidoMAlumno) as Nombre, matricula, isBecario
                                    FROM Alumnos 
                                    WHERE isActivo LIKE 'A'
                                    ORDER BY id_Alumno;");

    $sqlSentencia -> execute();


    $listaAlumnos = $sqlSentencia -> fetchAll(PDO::FETCH_ASSOC);

    $sqlSentencia = $con ->prepare("SELECT u.id_Usuario, u.correo
                                    FROM usuarios u
                                    INNER JOIN tipousuario t ON u.id_TipoUsuario = t.id_TipoUsuario
                                    WHERE u.isActivo = 'A' AND t.nombreTipo LIKE 'Alumno' AND u.id_Usuario IN (SELECT a.id_Usuario FROM Alumnos a)
                                    ORDER BY u.id_Usuario;");

    $sqlSentencia -> execute();


    $listaUsuarios = $sqlSentencia -> fetchAll(PDO::FETCH_ASSOC);

    $sqlSentencia = $con ->prepare("SELECT u.id_Usuario, u.correo
                                    FROM usuarios u
                                    INNER JOIN tipousuario t ON u.id_TipoUsuario = t.id_TipoUsuario
                                    WHERE u.isActivo = 'A' AND t.nombreTipo LIKE 'Alumno' AND u.id_Usuario NOT IN (SELECT a.id_Usuario FROM Alumnos a)
                                    ORDER BY u.id_Usuario;");

    $sqlSentencia -> execute();


    $listaUsuarios2 = $sqlSentencia -> fetchAll(PDO::FETCH_ASSOC);

    $sqlSentencia = $con ->prepare("SELECT id_DatosEscolar, d.id_Periodo, CONCAT(gradoEscolar, ' - ', grupoEscolar) as Nombre , d.isActivo
                                    FROM datos_escolares d
                                    INNER JOIN periodos p ON d.id_Periodo = p.id_Periodo
                                    WHERE d.isActivo = 'A'
                                    ORDER BY d.id_DatosEscolar;");

    $sqlSentencia -> execute();


    $listaDatosE = $sqlSentencia -> fetchAll(PDO::FETCH_ASSOC);
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



    <div>
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col col-5">
                            <div class="card">
                                <h5 class="card-header">DATOS ALUMNO</h5>
                                <div class="card-body">
                                    <form method="POST" enctype="multipart/form-data">

                                        <div hidden class="form-group" id="divId">
                                            <input  type="text" name="txtId" id="txtId"
                                                value="<?php echo $id_Alumno ?>">

                                        </div>

                                        <div class="form-group group-E"<?php  $r =(isset($_POST["txtId"]))? "": "hidden" ; echo $r; ?> >
                                            <label for="sltCorreo">Correo</label>
                                            <select class="form-select" aria-label="Default select example" 
                                                name="sltCorreo" id="sltCorreo" disabled>
                                                <option selected>Elige una opción</option>
                                                <?php foreach($listaUsuarios as $usuario){?>
                                                <option <?php if(intval($usuario["id_Usuario"]) == $sltCorreo){echo "selected";} ?> value="<?php echo $usuario["id_Usuario"]?>">
                                                    <?php echo $usuario["correo"]?></option>

                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form-group group-E" <?php  $r =(isset($_POST["txtId"]))? "hidden": "" ; echo $r; ?>>
                                            <label for="sltCorreo">Correo</label>
                                            <select class="form-select" aria-label="Default select example" <?php if($sltCorreo > 0){echo "disabled";}?>
                                                name="sltCorreo" id="sltCorreo" >
                                                <option selected>Elige una opción</option>
                                                <?php foreach($listaUsuarios2 as $usuario){?>
                                                <option <?php if(intval($usuario["id_Usuario"]) == $sltCorreo){echo "selected";} ?> value="<?php echo $usuario["id_Usuario"]?>">
                                                    <?php echo $usuario["correo"]?></option>

                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form-group group-E">
                                            <label for="sltDatosE">Datos Escolares</label>
                                            <select class="form-select" aria-label="Default select example"
                                                name="sltDatosE" id="sltDatosE">
                                                <option selected>Elige una opción</option>
                                                <?php foreach($listaDatosE as $datoE){?>

                                                <option <?php if(intval($datoE["id_DatosEscolar"]) == $sltDatosE){echo "selected";} ?> value="<?php echo $datoE["id_DatosEscolar"]?>">
                                                    <?php echo $datoE["Nombre"]?></option>

                                                <?php } ?>
                                            </select>
                                        </div>

                                        <div class="form-group group-E">
                                            <label for="sltBeca">Becado</label>
                                            <select class="form-select" aria-label="Default select example"
                                                name="sltBeca" id="sltBeca">
                                                <option <?php if(isset($sltBecado) && $sltBecado == 1){echo "selected";} ?> value="1" >Si</option>
                                                <option <?php if(isset($sltBecado) && $sltBecado == 0){echo "selected";} ?> value="0">No</option>
                                            </select>
                                        </div>

                                        <div class="form-group group-E">
                                            <label for="txtMatricula">Matrícula</label>
                                            <input type="text" class="form-control" name="txtMatricula"
                                                id="txtMatricula" placeholder="" value="<?php echo $txtMatricula ?>">
                                        </div>

                                        <div class="form-group group-E">
                                            <label for="txtNombre">Nombre(s)</label>
                                            <input type="text" class="form-control" name="txtNombre" id="txtNombre"
                                                placeholder="" value="<?php echo $txtNombre ?>">
                                        </div>


                                        <div class="form-group group-E">
                                            <label for="txtAP">Apellido Paterno</label>
                                            <input type="text" class="form-control" name="txtAP" id="txtAP"
                                                placeholder="" value="<?php echo $txtAP ?>">
                                        </div>


                                        <div class="form-group group-E">
                                            <label for="txtAM">Apellido Materno</label>
                                            <input type="text" class="form-control" name="txtAM" id="txtAM"
                                                placeholder="" value="<?php echo $txtAM ?>">
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
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Matrícula</th>
                                        <th scope="col">Becado</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>


                                    <?php foreach($listaAlumnos as $alumno){?>
                                    <tr>
                                        <td><?php echo $alumno["Nombre"]?></td>
                                        <td><?php echo $alumno["matricula"]?></td>
                                        <td><?php $becado = ($alumno["isBecario"] == 1)? "Si": "No"; echo($becado);?>
                                        </td>
                                        <td>
                                            <form method="post">
                                                <input hidden type="text" name="txtId" id="txtId"
                                                    value="<?php echo $alumno['id_Alumno'] ?>">
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

</html>