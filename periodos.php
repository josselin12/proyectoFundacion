<?php 
  session_start();

  if(isset($_SESSION['usuario']) && $_SESSION['tipo'] == "Administrador"){
    $_SESSION['pagina'] = "Periodos";
    /*echo "<br/><br/><br/><br/><br/>";
    print_r($_POST);*/

    $txtEliminar = (isset($_POST["txtEliminar"]))? $_POST["txtEliminar"]: "" ;

    $txtNombre =(isset($_POST["txtNombre"]))? $_POST["txtNombre"]: "" ;
    $txtDateI =(isset($_POST["txtDateI"]))? $_POST["txtDateI"]: "" ;
    $txtDateF =(isset($_POST["txtDateF"]))? $_POST["txtDateF"]: "" ;

    $accion =(isset($_POST["accion"]))? $_POST["accion"]: "" ;
    $txtId =(isset($_POST["txtId"]))? intval($_POST["txtId"]): "" ;

    /*echo $accion."<br/>";
    echo $txtNombre."<br/>";
    echo $txtDateI."<br/>";
    echo $txtDateF."<br/>";
    echo $txtId."ID DE CAMPO<br/>";*/

    if(isset($_SESSION['accionEliminar']) && $_SESSION['accionEliminar'] == "Eliminar"){
        $accion = "Eliminar";
    }

    if($txtEliminar == "NO"){
        $_SESSION['accionEliminar'] = "NO";
        $_SESSION['idEliminar'] = "";
        header('location: periodos.php');
        $accion = "";
    }

    include('config/bd.php');

    switch($accion){
        case "Agregar":
            //INSERT INTO periodos VALUES (default, :nombrePeriodo, :fechaInicia, :fechaTermina, default);

            if($txtNombre == "" ||$txtDateI == "" ||$txtDateF == ""){
                $accion = "Vacio";
            }else{
                try {
                    $sqlSentencia = $con ->prepare("INSERT INTO periodos 
                    VALUES (default, :nombrePeriodo, :fechaInicia, :fechaTermina, default);");
                    
                    $sqlSentencia -> bindParam(':nombrePeriodo',$txtNombre);
                    $sqlSentencia -> bindParam(':fechaInicia',$txtDateI);
                    $sqlSentencia -> bindParam(':fechaTermina',$txtDateF);
                    $sqlSentencia -> execute();
                }catch(Exception $e){
                    $accion = "Error";
                }
            }
            break;
        case "Modificar":

            if($txtNombre == "" ||$txtDateI == "" ||$txtDateF == ""){
                $accion = "Vacio";
            }else{
                try {
                    $sqlSentencia = $con ->prepare("UPDATE periodos SET nombrePeriodo = :nombrePeriodo, fechaInicia = :fechaInicia,
                    fechaTermina = :fechaTermina WHERE id_Periodo = :id_Periodo;");
                    
                    $sqlSentencia -> bindParam(':nombrePeriodo',$txtNombre);
                    $sqlSentencia -> bindParam(':fechaInicia',$txtDateI);
                    $sqlSentencia -> bindParam(':fechaTermina',$txtDateF);
                    $sqlSentencia -> bindParam(':id_Periodo',$txtId);
                    $sqlSentencia -> execute();
                }catch(Exception $e){
                    $accion = "Error";
                }
            }
            break;
        case "Cancelar":
            header('location: periodos.php');
            break;
        case "Seleccionar":
            $sqlSentencia = $con ->prepare("SELECT * 
                                            FROM periodos
                                            WHERE id_Periodo = :id");
            $sqlSentencia -> bindParam(':id',$txtId);
            $sqlSentencia -> execute();


            $periodo = $sqlSentencia -> fetch(PDO::FETCH_LAZY);
            $txtNombre = $periodo["nombrePeriodo"];
            $txtDateI = $periodo["fechaInicia"];
            $txtDateF = $periodo["fechaTermina"];
            
            $id_Periodo= $periodo["id_Periodo"];
            break;
        case "Eliminar":
            if($txtEliminar == ""){
                $_SESSION['accionEliminar'] = "Eliminar";
                $_SESSION['idEliminar'] = $txtId;
            }else if($txtEliminar == "SI"){
                $_SESSION['accionEliminar'] = "NO";
                
                $idEliminar = intval($_SESSION['idEliminar']);

                $sqlSentencia = $con ->prepare("UPDATE periodos
                                            SET isActivo = 'N'
                                            WHERE id_Periodo = :id;");
                $sqlSentencia -> bindParam(':id',$idEliminar);
                $sqlSentencia -> execute();
                
            }
            break;
    }


    $sqlSentencia = $con ->prepare("SELECT * 
                                    FROM periodos
                                    WHERE isActivo = 'A'
                                    ORDER BY id_Periodo;");

    $sqlSentencia -> execute();


    $listaPeriodos = $sqlSentencia -> fetchAll(PDO::FETCH_ASSOC);

    
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
        <input hidden type="text" name="txtPagina" id="txtPagina" value="periodos.php">
    </div>

    <div>
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col col-5">
                            <div class="card">
                                <h5 class="card-header">DATOS PERIODO</h5>
                                <div class="card-body">
                                    <form method="POST" enctype="multipart/form-data">

                                        <div hidden class="form-group" id="divId">
                                            <input type="text" name="txtId" id="txtId"
                                                value="<?php echo $id_Periodo ?>">

                                        </div>

                                        <div class="form-group" id="divId">
                                            <input hidden type="text" name="txAccion" id="txAccion"
                                                value="<?php echo $accion ?>">

                                        </div>

                                        <div class="form-group group-E">
                                            <label for="txtNombre">Nombre</label>
                                            <input type="text" class="form-control" name="txtNombre" id="txtNombre"
                                                placeholder="" value="<?php echo $txtNombre ?>">
                                        </div>

                                        <div class="form-group group-E">
                                            <label for="txtDateI">Fecha de Inicio</label>
                                            <input type="date" class="form-control" name="txtDateI" id="txtDateI"
                                                placeholder="" value="<?php echo $txtDateI ?>">
                                        </div>


                                        <div class="form-group group-E">
                                            <label for="txtDateF">Fecha de Fin</label>
                                            <input type="date" class="form-control" name="txtDateF" id="txtDateF"
                                                placeholder="" value="<?php echo $txtDateF ?>">
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
                                        <th scope="col">Nombre Periodo</th>
                                        <th scope="col">Fecha de Inicio</th>
                                        <th scope="col">Fecha de Fin</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>


                                    <?php foreach($listaPeriodos as $periodo){?>
                                    <tr>
                                        <td><?php echo $periodo["nombrePeriodo"]?></td>
                                        <td><?php echo $periodo["fechaInicia"]?></td>
                                        <td><?php echo $periodo["fechaTermina"]?>
                                        </td>
                                        <td>
                                            <form method="post" id="formUser">
                                                <input hidden type="text" name="txtId" id="txtId"
                                                    value="<?php echo $periodo['id_Periodo'] ?>">
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
<script type="text/javascript" src="js/var/periodos.js"></script>
<script type="text/javascript" src="js/notify.js"></script>

</html>