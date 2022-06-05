<?php 
  session_start();

  if(isset($_SESSION['usuario']) && $_SESSION['tipo'] == "Administrador"){
    $_SESSION['pagina'] = "Pagos y Comprobantes";
    /*echo "<br/><br/><br/>";
    print_r($_POST);
    echo "<br/>";*/

    $txtEliminar = (isset($_POST["txtEliminar"]))? $_POST["txtEliminar"]: "" ;

    $comprobantesBuscar = "TODOS";

    $txtDateI =(isset($_POST["txtDateI"]))? $_POST["txtDateI"]: "" ;
    $txtDateF =(isset($_POST["txtDateF"]))? $_POST["txtDateF"]: "" ;

    $sltPeriodo =(isset($_POST["sltPeriodo"]))? intval($_POST["sltPeriodo"]): "" ;
    $sltAlumno =(isset($_POST["sltAlumno"]))? intval($_POST["sltAlumno"]): "" ;
    $txtMonto =(isset($_POST["txtMonto"]))? $_POST["txtMonto"]: "" ;
    $txtCuenta =(isset($_POST["txtCuenta"]))? $_POST["txtCuenta"]: "" ;
    
    $accion =(isset($_POST["accion"]))? $_POST["accion"]: "" ;
    $txtId =(isset($_POST["txtId"]))? intval($_POST["txtId"]): "" ;
    $txtIdC =(isset($_POST["txtIdC"]))? intval($_POST["txtIdC"]): "" ;

    $accionComprobante =(isset($_POST["accionComprobante"]))? $_POST["accionComprobante"]: "" ;
    $sltAlumnoC =(isset($_POST["sltAlumnoC"]))? intval($_POST["sltAlumnoC"]): "" ;
    
    /*echo $accionComprobante."<br/>";
    echo $txtIdC." ID DE CAMPO<br/>";
    echo $sltAlumnoC."<br/>";
    echo $sltPeriodo."<br/>";
    echo $sltAlumno."<br/>";
    echo $txtMonto."<br/>";
    echo $txtCuenta."<br/>";
    echo $txtId." ID DE CAMPO<br/>";*/

    if(isset($_SESSION['accionEliminar']) && $_SESSION['accionEliminar'] == "Eliminar"){
        $accion = "Eliminar";
    }

    if($txtEliminar == "NO"){
        $_SESSION['accionEliminar'] = "NO";
        $_SESSION['idEliminar'] = "";
        header('location: pagos.php');
        $accion = "";
    }

    include('config/bd.php');

    switch($accion){
        case "Agregar":
            //INSERT INTO pagos VALUES (default, id_Periodo, id_Alumno, monto,now(),cuenta, default);

            echo $txtMonto;

            if($sltPeriodo == "" || $sltAlumno == "" || $txtMonto == "" || $txtCuenta == "" ){
                $accion = "Vacio";
            }else{
                try {
                    $sqlSentencia = $con ->prepare("INSERT INTO pagos 
                    VALUES (default, :id_Periodo, :id_Alumno, :monto,now(),:cuenta, default);");
            
                    $sqlSentencia -> bindParam(':id_Periodo',$sltPeriodo);
                    $sqlSentencia -> bindParam(':id_Alumno',$sltAlumno);
                    $sqlSentencia -> bindParam(':monto',$txtMonto);
                    $sqlSentencia -> bindParam(':cuenta',$txtCuenta);
                    $sqlSentencia -> execute();
                } catch (Exception $e) {
                    $accion = "Error";
                }
            }
            break;
        case "Modificar":

            if($sltPeriodo == "" || $sltAlumno == "" || $txtMonto == "" || $txtCuenta == "" ){
                $accion = "Vacio";
            }else{
                try {
                    $sqlSentencia = $con ->prepare("UPDATE pagos 
                    SET id_Periodo = :id_Periodo, id_Alumno = :id_Alumno, monto= :monto, cuenta= :cuenta  WHERE id_Pago = :id;");
                    $sqlSentencia -> bindParam(':id_Periodo',$sltPeriodo);
                    $sqlSentencia -> bindParam(':id_Alumno',$sltAlumno);
                    $sqlSentencia -> bindParam(':monto',$txtMonto);
                    $sqlSentencia -> bindParam(':cuenta',$txtCuenta);
                    $sqlSentencia -> bindParam(':id',$txtId);
                    $sqlSentencia -> execute();
                } catch (Exception $e) {
                    $accion = "Error";
                }
            }

            break;
        case "Cancelar":
            header('location: pagos.php');
            break;
        case "Seleccionar":
            $sqlSentencia = $con ->prepare("SELECT * 
                                        FROM pagos
                                        WHERE id_Pago = :id;");
            $sqlSentencia -> bindParam(':id',$txtId);
            $sqlSentencia -> execute();


            $pago = $sqlSentencia -> fetch(PDO::FETCH_LAZY);
            $sltPeriodo = $pago["id_Periodo"];
            $sltAlumno = $pago["id_Alumno"];
            $txtMonto = $pago["monto"];
            $txtCuenta = $pago["cuenta"];
            $id_Pago = $pago["id_Pago"];
            
            
            break;
        case "Eliminar":
            if($txtEliminar == ""){
                $_SESSION['accionEliminar'] = "Eliminar";
                $_SESSION['idEliminar'] = $txtId;
            }else if($txtEliminar == "SI"){
                $_SESSION['accionEliminar'] = "NO";

                $idEliminar = intval($_SESSION['idEliminar']);

                $sqlSentencia = $con ->prepare("UPDATE pagos
                                            SET isActivo = 'N'
                                            WHERE id_Pago = :id;");
                $sqlSentencia -> bindParam(':id',$idEliminar);
                $sqlSentencia -> execute();
                
            }
            break;
        case "Descargar":
                $id_Comprobante =(isset($_POST["id_Comprobante"]))? intval($_POST["id_Comprobante"]): "" ;
                //echo $id_Comprobante." ID DE CAMPO 2<br/>";
                $sqlSentencia = $con ->prepare("SELECT *
                                                FROM comprobante
                                                WHERE id_Comprobante = :id_Comprobante;");
                $sqlSentencia -> bindParam(':id_Comprobante',$id_Comprobante);
                $sqlSentencia -> execute();
    
                $comprobanteD = $sqlSentencia -> fetch(PDO::FETCH_LAZY);
                $tipo = $comprobanteD["tipo"];
                $nombre = $comprobanteD["nombre"];
                $archivo = $comprobanteD["archivo"];
    
    
                //echo "<br/><br/><br/><br/>".$nombre;
                //header para tranformar la salida en el tipo de archivo que hemos guardado
                header("Content-type: $tipo"); 
                header('Content-Disposition: attachment; filename="'.$nombre.'"');
                
                //imprimir el archivo
                echo $archivo;
    
                break;
        case "Buscar";
            if($txtDateI == "" || $txtDateF == ""){
                $accion = "Vacio";
            }else{
                try {
                    $comprobantesBuscar = "AVANZADA";
                    $sqlSentencia = $con ->prepare("SELECT id_Comprobante, id_Pago, c.nombre, c.tipo, c.descripcionComprobante, 
                                                    c.archivo, CONCAT(nombreAlumno, ' ',apellidoPAlumno,' ',apellidoMAlumno) as alumno, 
                                                    CONCAT(gradoEscolar,' ',grupoEscolar) as grado
                                                    FROM comprobante c
                                                    INNER JOIN alumnos a ON c.id_Alumno = a.id_Alumno
                                                    INNER JOIN datos_escolares  d ON a.id_DatosEscolar = d.id_DatosEscolar
                                                    WHERE fechaComprobante BETWEEN :fechaInicio AND :fechaFin
                                                    ORDER BY id_Comprobante;");
                    $sqlSentencia -> bindParam(':fechaInicio',$txtDateI);
                    $sqlSentencia -> bindParam(':fechaFin',$txtDateF);
                    $sqlSentencia -> execute();


                    $listaComprobante = $sqlSentencia -> fetchAll(PDO::FETCH_ASSOC);
                } catch (Exception $e) {
                    $accion = "Error";
                }
            }
            
            break;
        case "Cancelar2";
            header('location: pagos.php');
            break;
        case "VisualizarDoc":
            
                $id_Comprobante =(isset($_POST["id_Comprobante"]))? intval($_POST["id_Comprobante"]): "" ;
                //echo $id_Comprobante." ID DE CAMPO 2<br/>";
                $sqlSentencia = $con ->prepare("SELECT *
                                                FROM comprobante
                                                WHERE id_Comprobante = :id_Comprobante;");
                $sqlSentencia -> bindParam(':id_Comprobante',$id_Comprobante);
                $sqlSentencia -> execute();
    
                $comprobanteD = $sqlSentencia -> fetch(PDO::FETCH_LAZY);
                $tipo = $comprobanteD["tipo"];
                $nombre = $comprobanteD["nombre"];
                $archivo = $comprobanteD["archivo"];
    
    
                //echo "<br/><br/><br/><br/>".$nombre;
                //header para visualizar archivo
                header("Content-type: $tipo"); 
                header("archivos/".$nombre); 
                
                //imprimir el archivo
                echo $archivo;
    
            break;
    }


    $sqlSentencia = $con ->prepare("SELECT id_Pago, pa.id_Periodo, CONCAT(nombreAlumno, ' ', apellidoPAlumno, ' ',apellidoMAlumno) as Nombre, 
                                    a.isBecario, monto, fechaPago, cuenta,
                                    CONCAT (p.nombrePeriodo, ' ', SUBSTR(fechaInicia,1,4), ' ',SUBSTR(fechaTermina,1,4))as Periodo
                                    FROM pagos pa
                                    INNER JOIN periodos p ON pa.id_Periodo = p.id_Periodo
                                    INNER JOIN alumnos a ON pa.id_Alumno = a.id_Alumno
                                    WHERE pa.isActivo = 'A'
                                    order by pa.id_Pago;");

    $sqlSentencia -> execute();


    $listaPagos = $sqlSentencia -> fetchAll(PDO::FETCH_ASSOC);


    $sqlSentencia = $con ->prepare("SELECT id_Alumno, CONCAT(nombreAlumno, ' ', 
                                    apellidoPAlumno, ' ', apellidoMAlumno) as Nombre, matricula, isBecario, CONCAT(gradoEscolar,' - ',grupoEscolar) as datos
                                    FROM Alumnos a
                                    INNER JOIN datos_escolares d on a.id_DatosEscolar = d.id_DatosEscolar
                                    WHERE a.isActivo LIKE 'A'
                                    ORDER BY id_Alumno;");

    $sqlSentencia -> execute();


    $listaAlumnos = $sqlSentencia -> fetchAll(PDO::FETCH_ASSOC);


    $sqlSentencia = $con ->prepare("SELECT id_Periodo, 
                                    CONCAT(nombrePeriodo, ' ', SUBSTR(fechaInicia, 1,4), ' - ' ,SUBSTR(fechaTermina, 1,4)) as periodo
                                    FROM periodos
                                    WHERE isActivo = 'A';");

    $sqlSentencia -> execute();


    $listaPeriodos = $sqlSentencia -> fetchAll(PDO::FETCH_ASSOC);


    if($comprobantesBuscar == "TODOS"){
        $sqlSentencia = $con ->prepare("SELECT id_Comprobante, id_Pago, c.nombre, c.tipo, c.descripcionComprobante, 
                                    c.archivo, CONCAT(nombreAlumno, ' ',apellidoPAlumno,' ',apellidoMAlumno) as alumno, 
                                    CONCAT(gradoEscolar,' ',grupoEscolar) as grado
                                    FROM comprobante c
                                    INNER JOIN alumnos a ON c.id_Alumno = a.id_Alumno
                                    INNER JOIN datos_escolares  d ON a.id_DatosEscolar = d.id_DatosEscolar
                                    ORDER BY id_Comprobante;");

        $sqlSentencia -> execute();


        $listaComprobante = $sqlSentencia -> fetchAll(PDO::FETCH_ASSOC);
    }

    


    $sqlSentencia = $con ->prepare("SELECT * 
                                    FROM alumnos
                                    WHERE isActivo = 'A' AND isBecario = 1;");

    $sqlSentencia -> execute();


    $listaBecados = $sqlSentencia -> fetchAll(PDO::FETCH_ASSOC);

    $sqlSentencia = $con ->prepare("SELECT * 
                                    FROM alumnos
                                    WHERE isActivo = 'A' AND isBecario = 0;");

    $sqlSentencia -> execute();


    $listaNoBecados = $sqlSentencia -> fetchAll(PDO::FETCH_ASSOC);
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
        <input hidden type="text" name="txtPagina" id="txtPagina" value="pagos.php">
    </div>


    <div>
        <div class="card">
            <div class="card-body">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                            type="button" role="tab" aria-controls="home" aria-selected="true">Pagos</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                            type="button" role="tab" aria-controls="profile" aria-selected="false">Comprobantes</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent" style="width: 100%;">
                    <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab"
                        style="width: 100%; margin:10px">
                        <div class="card">
                            <div class="card-body">
                                <div class="container">
                                    <div class="row">
                                        <div class="col col-5">
                                            <div class="card">
                                                <h5 class="card-header">DATOS PAGO</h5>
                                                <div class="card-body">
                                                    <form method="POST" enctype="multipart/form-data">

                                                        <div class="form-group" id="divId">
                                                            <input hidden type="text" name="txtId" id="txtId"
                                                                value="<?php echo $id_Pago ?>">

                                                        </div>

                                                        <div class="form-group" id="divId">
                                                            <input hidden type="text" name="txAccion" id="txAccion"
                                                                value="<?php echo $accion ?>">
                                                        </div>

                                                        <div hidden class="form-group" id="divId">
                                                            <p id="txtBecado"><?php print json_encode($listaBecados); ?>
                                                            </p>
                                                        </div>


                                                        <div hidden class="form-group" id="divId">
                                                            <p id="txtNoBecado">
                                                                <?php echo json_encode($listaNoBecados) ?></p>
                                                        </div>


                                                        <div class="form-group group-E">
                                                            <label for="sltPeriodo">Periodo</label>
                                                            <select class="form-select"
                                                                aria-label="Default select example" name="sltPeriodo"
                                                                id="sltPeriodo">
                                                                <option selected>Elige una opción</option>
                                                                <?php foreach($listaPeriodos as $periodo){?>

                                                                <option
                                                                    <?php if(intval($periodo["id_Periodo"]) == $sltPeriodo){echo "selected";} ?>
                                                                    value="<?php echo $periodo["id_Periodo"]?>">
                                                                    <?php echo $periodo["periodo"]?></option>

                                                                <?php } ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group group-E">
                                                            <label for="sltAlumno">Alumno</label>
                                                            <select class="form-select"
                                                                aria-label="Default select example" name="sltAlumno"
                                                                id="sltAlumno">
                                                                <option selected>Elige una opción</option>
                                                                <?php foreach($listaAlumnos as $alumno){?>

                                                                <option
                                                                    <?php if(intval($alumno["id_Alumno"]) == $sltAlumno){echo "selected";} ?>
                                                                    value="<?php echo $alumno["id_Alumno"]?>">
                                                                    <?php echo $alumno["Nombre"].", "; echo $alumno["datos"];?>
                                                                </option>

                                                                <?php } ?>
                                                            </select>
                                                        </div>



                                                        <div class="form-group group-E">
                                                            <label for="txtMonto">Monto</label>

                                                            <input hidden type="number" class="form-control"
                                                                name="txtMonto" id="txtMonto"
                                                                value="<?php echo $txtMonto ?>">
                                                        </div>

                                                        <div hidden class="form-check" id="radio1">
                                                            <input class="form-check-input" type="radio" name="opMonto"
                                                                id="opMonto" value="Primaria">
                                                            <label class="form-check-label" for="flexRadioDefault1">
                                                                Primaria - $1935
                                                            </label>
                                                        </div>
                                                        <div hidden class="form-check" id="radio2">
                                                            <input class="form-check-input" type="radio" name="opMonto"
                                                                id="opMonto" value="Secundaria">
                                                            <label class="form-check-label" for="flexRadioDefault2">
                                                                Secundaria - $2100
                                                            </label>
                                                        </div>

                                                        <div class="form-group group-E">
                                                            <label for="txtCuenta">Cuenta</label>
                                                            <input type="number" class="form-control" name="txtCuenta"
                                                                id="txtCuenta" value="<?php echo $txtCuenta ?>">
                                                        </div>


                                                        <br />
                                                        <div class="btn-group flex-wrap text-center" role="group"
                                                            aria-label="" style="width: 100%;">
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
                                        <div class="col col-7 table-wrapper">
                                            <table class="table table-responsive text-center">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Alumno</th>
                                                        <th scope="col">Becado</th>
                                                        <th scope="col">Monto</th>
                                                        <th scope="col">Cuenta</th>
                                                        <th scope="col">Periodo</th>
                                                        <th scope="col">Fecha Pago</th>
                                                        <th scope="col">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>


                                                    <?php foreach($listaPagos as $pago){?>
                                                    <tr>
                                                        <td><?php echo $pago["Nombre"]?></td>
                                                        <td><?php $becado = ($pago["isBecario"] == 1)? "Sí": "No"; echo($becado);?>
                                                        </td>
                                                        <td><?php echo "$".$pago["monto"]?></td>
                                                        <td><?php echo $pago["cuenta"]?></td>
                                                        <td><?php echo $pago["Periodo"]?></td>
                                                        <td><?php echo $pago["fechaPago"]?></td>
                                                        <td>
                                                            <form method="post" id="formUser">
                                                                <input hidden type="text" name="txtId" id="txtId"
                                                                    value="<?php echo $pago['id_Pago'] ?>">
                                                                <input hidden type="text" name="txtEliminar"
                                                                    id="txtEliminar">
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
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab"
                        style="width: 100%; margin:10px">
                        <div class="card ">
                            <div class="card-body ">
                                <div class="container text-center">
                                    <div class="row text-center">
                                        <div class="col col-12 text-center">
                                            <form method="POST" enctype="multipart/form-data">

                                                <div class="container">
                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label for="txtDateI">Fecha de Inicio</label>
                                                                <input type="date" class="form-control" name="txtDateI"
                                                                    id="txtDateI" placeholder="">
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="form-group">
                                                                <label for="txtDateF">Fecha de Fin</label>
                                                                <input type="date" class="form-control" name="txtDateF"
                                                                    id="txtDateF" placeholder="">
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>

                                                <br />

                                                <div class="btn-group flex-wrap text-center" role="group" aria-label=""
                                                    style="width: 40%;">
                                                    <button type="submit" name="accion" value="Buscar"
                                                        class="btn btn-primary">Buscar</button>
                                                    <button type="submit" name="accion" value="Cancelar2"
                                                        class="btn btn-secondary"
                                                        style="margin-left:10px!important;">Ver todos los
                                                        resultados</button>

                                                </div>


                                            </form>
                                            <br />
                                        </div>
                                        <br />
                                        <hr>

                                        <div class="container">
                                            <div class="row">
                                                <div class="col text-center">

                                                    <h4><?php
                                                    
                                                        if($accion == "Buscar"){
                                                            echo "Comprobantes del ".$txtDateI." a ".$txtDateF;
                                                        }

                                                    ?></h4>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col col-12 table-wrapper text-center" style="margin-top:30px;">
                                            <table class="table table-responsive text-center">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Nombre archivo</th>
                                                        <th scope="col">Descripción comprobante</th>
                                                        <th scope="col">Alumno</th>
                                                        <th scope="col">Grado y grupo</th>
                                                        <th scope="col">Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>


                                                    <?php foreach($listaComprobante as $comprobante){?>
                                                    <tr>
                                                        <td><?php echo $comprobante["nombre"]?></td>
                                                        <td><?php echo $comprobante["descripcionComprobante"]?></td>
                                                        <td><?php echo $comprobante["alumno"]?></td>
                                                        <td><?php echo $comprobante["grado"]?></td>
                                                        <td>
                                                            <form method="post">
                                                                <input hidden type="text" name="id_Comprobante"
                                                                    id="id_Comprobante"
                                                                    value="<?php echo $comprobante['id_Comprobante'] ?>">
                                                                <button type="submit" name="accion" value="Descargar"
                                                                    class="btn btn-primary">Descargar</button>
                                                                    <button type="submit" name="accion" value="VisualizarDoc"
                                                                    class="btn btn-secondary">Visualizar</button>
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
                </div>


            </div>
        </div>
    </div>


</body>


<?php include('template/scripts.php') ?>
<script type="text/javascript" src="js/pagos.js"></script>
<script type="text/javascript" src="js/notify.js"></script>

</html>