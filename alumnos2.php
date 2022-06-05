<?php 
  session_start();
    
  if(isset($_SESSION['usuario']) && $_SESSION['tipo'] == "Administrador"){
    $_SESSION['pagina'] = "Alumnos";
    /*echo "<br/><br/><br/><br/>";
    print_r($_POST);
    echo "<br/>";*/

    $alumnosBuscar = "TODOS";

    $txtEliminar = (isset($_POST["txtEliminar"]))? $_POST["txtEliminar"]: "" ;

    $sltEtapa =(isset($_POST["sltEtapa"]))? $_POST["sltEtapa"] : "" ;
    $sltGruposP =(isset($_POST["sltGruposP"]))? intval($_POST["sltGruposP"]): "" ;
    $sltGruposS =(isset($_POST["sltGruposS"]))? intval($_POST["sltGruposS"]): "" ;


    $sltCorreo =(isset($_POST["sltCorreo"]))? intval($_POST["sltCorreo"]): "" ;
    $sltDatosE =(isset($_POST["sltDatosE"]))? intval($_POST["sltDatosE"]): "" ;
    $sltBeca =(isset($_POST["sltBeca"]))? $_POST["sltBeca"]: "" ;

    $txtMatricula =(isset($_POST["txtMatricula"]))? $_POST["txtMatricula"]: "" ;
    $txtNombre =(isset($_POST["txtNombre"]))? $_POST["txtNombre"]: "" ;
    $txtAP =(isset($_POST["txtAP"]))? $_POST["txtAP"]: "" ;
    $txtAM =(isset($_POST["txtAM"]))? $_POST["txtAM"]: "" ;
    

    $txtCorreo =(isset($_POST["txtCorreo"]))? $_POST["txtCorreo"]: "" ;
    $txtContra =(isset($_POST["txtContra"]))? $_POST["txtContra"]: "" ;
    $sltTipo =(isset($_POST["sltTipo"]))? intval($_POST["sltTipo"]): "" ;


    $accion =(isset($_POST["accion"]))? $_POST["accion"]: "" ;
    $txtId =(isset($_POST["txtId"]))? intval($_POST["txtId"]): "" ;
    $txtIdUsuario =(isset($_POST["txtIdUsuario"]))? intval($_POST["txtIdUsuario"]): "" ;
    $txtIdA =(isset($_POST["txtIdA"]))? intval($_POST["txtIdA"]): "" ;
    $txtIdU =(isset($_POST["txtIdU"]))? intval($_POST["txtIdU"]): "" ;

    if(isset($_SESSION['accionEliminar']) && $_SESSION['accionEliminar'] == "Eliminar"){
        $accion = "Eliminar";
    }

    if($txtEliminar == "NO"){
        $_SESSION['accionEliminar'] = "NO";
        $_SESSION['idEliminar'] = "";
        $_SESSION['idEliminar2'] = "";
        header('location: alumnos2.php');
        $accion = "";
    }

    /*echo $accion."<br/>";
    echo $sltCorreo."<br/>";
    echo $sltDatosE."<br/>";
    echo $sltBeca."<br/>";
    echo $txtMatricula."<br/>";
    echo $txtNombre."<br/>";
    echo $txtAP."<br/>";
    echo $txtAM."<br/>";
    echo $txtId."ID DE CAMPO<br/>";
    
    echo $txtIdA."ID DE CAMPO<br/>";
    echo $txtIdU."ID DE CAMPO<br/>";*/

    include('config/bd.php');

    switch($accion){
        case "Agregar":

            if($sltTipo == "" ||$txtCorreo == "" || $txtContra == "" || $sltDatosE == "" 
            || $txtMatricula == ""|| $txtNombre == ""|| $txtAP == ""
            || $txtAM == ""|| $sltBeca == ""){
                $accion = "Vacio";
            }else{
                $sqlSentencia = $con ->prepare("CALL CrearAlumno(2, :correo,:contrasena, :id_DatosEscolar, 
                :matricula, :nombreAlumno, :apellidoPAlumno, :apellidoMAlumno, :isBecario)");
                
                try{
                    $sqlSentencia -> bindParam(':correo',$txtCorreo);
                    $sqlSentencia -> bindParam(':contrasena',$txtContra);
                    $sqlSentencia -> bindParam(':id_DatosEscolar',$sltDatosE);
                    $sqlSentencia -> bindParam(':matricula',$txtMatricula);
                    $sqlSentencia -> bindParam(':nombreAlumno',$txtNombre);
                    $sqlSentencia -> bindParam(':apellidoPAlumno',$txtAP);
                    $sqlSentencia -> bindParam(':apellidoMAlumno',$txtAM);
                    $sqlSentencia -> bindParam(':isBecario',$sltBeca);
            $sqlSentencia -> execute();
                }catch(Exception $e){
                    $accion = "Error";
                }
            }

            break;
        case "Modificar":

            if($sltTipo == "" ||$txtCorreo == "" || $txtContra == "" || $sltDatosE == "" 
            || $txtMatricula == ""|| $txtNombre == ""|| $txtAP == ""
            || $txtAM == ""|| $sltBeca == ""){
                $accion = "Vacio";
            }else{
                $sqlSentencia = $con ->prepare("CALL ActualizarAlumno(:correo, :contrasena, :id_DatosEscolar, :matricula,
                :nombreAlumno, :apellidoPAlumno, :apellidoMAlumno, :isBecario, :idUsuario, :id_Alumno);");
                
                try{
                    $sqlSentencia -> bindParam(':correo',$txtCorreo);
                    $sqlSentencia -> bindParam(':contrasena',$txtContra);
                    $sqlSentencia -> bindParam(':id_DatosEscolar',$sltDatosE);
                    $sqlSentencia -> bindParam(':matricula',$txtMatricula);
                    $sqlSentencia -> bindParam(':nombreAlumno',$txtNombre);
                    $sqlSentencia -> bindParam(':apellidoPAlumno',$txtAP);
                    $sqlSentencia -> bindParam(':apellidoMAlumno',$txtAM);
                    $sqlSentencia -> bindParam(':isBecario',$sltBeca);
                    $sqlSentencia -> bindParam(':idUsuario',$txtIdU);
                    $sqlSentencia -> bindParam(':id_Alumno',$txtIdA);
                    $sqlSentencia -> execute();
                }catch(Exception $e){
                    $accion = "Error";
                }
            }
            break;
        case "Cancelar":
            header('location: alumnos2.php');
            break;
        case "Seleccionar":
            $sqlSentencia = $con ->prepare("SELECT * 
                                            FROM alumnos a
                                            INNER JOIN usuarios u ON a.id_Usuario = u.id_Usuario
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
            
            $txtCorreo = $alumno["correo"];
            $txtContra = $alumno["contrasena"];
            $sltTipo = $alumno["id_TipoUsuario"];

            $idUsuario = $alumno["id_Usuario"];
            $id_Alumno= $alumno["id_Alumno"];
            break;
        case "Eliminar":
            if($txtEliminar == ""){
                $_SESSION['accionEliminar'] = "Eliminar";
                $_SESSION['idEliminar'] = $txtIdUsuario;
                $_SESSION['idEliminar2'] = $txtId;

            }else if($txtEliminar == "SI"){
                $_SESSION['accionEliminar'] = "NO";
                
                $idEliminar = intval($_SESSION['idEliminar']);
                $idEliminar2 = intval($_SESSION['idEliminar2']);

                $sqlSentencia = $con ->prepare("CALL EliminarAlumno(:idUsuario, :id_Alumno);");
                $sqlSentencia -> bindParam(':idUsuario',$idEliminar);
                $sqlSentencia -> bindParam(':id_Alumno',$idEliminar2);
                $sqlSentencia -> execute();
                
            }
            break;
        case "Buscar";
            if($sltEtapa == "Primaria"){
                
                if($sltGruposP == 0){
                    $accion = "Vacio";
                }else{
                    try {
                        $alumnosBuscar = "PRIMARIA"; 
                        $sqlSentencia = $con ->prepare("SELECT 
                            id_Alumno,
                            CONCAT(nombreAlumno, ' ', apellidoPAlumno, ' ', apellidoMAlumno) as Nombre,
                            CONCAT(escolar.etapaEscolar, ' ', escolar.gradoEscolar, ' ', escolar.grupoEscolar) as datosEscolares,  
                            matricula, 
                            isBecario, 
                            id_Usuario
                        FROM alumnos 
                        INNER JOIN datos_escolares escolar ON escolar.id_DatosEscolar = alumnos.id_DatosEscolar
                        AND alumnos.isActivo LIKE 'A' and alumnos.id_DatosEscolar = :idGrupo
                        ORDER BY id_Alumno;");
                        $sqlSentencia -> bindParam(':idGrupo',$sltGruposP);
                        $sqlSentencia -> execute();


                        $listaAlumnos = $sqlSentencia -> fetchAll(PDO::FETCH_ASSOC);
                    } catch (Exception $e) {
                        $accion = "Error";
                    }
                }
            }else if($sltEtapa == "Secundaria"){
                if($sltGruposS == 0){
                    $accion = "Vacio";
                }else{
                    try {
                        $alumnosBuscar = "SECUNDARIA";
                        $sqlSentencia = $con ->prepare("SELECT 
                                        id_Alumno,
                                        CONCAT(nombreAlumno, ' ', apellidoPAlumno, ' ', apellidoMAlumno) as Nombre,
                                        CONCAT(escolar.etapaEscolar, ' ', escolar.gradoEscolar, ' ', escolar.grupoEscolar) as datosEscolares,  
                                        matricula, 
                                        isBecario, 
                                        id_Usuario
                                    FROM alumnos 
                                    INNER JOIN datos_escolares escolar ON escolar.id_DatosEscolar = alumnos.id_DatosEscolar
                                    AND alumnos.isActivo LIKE 'A' and alumnos.id_DatosEscolar = :idGrupo
                                    ORDER BY id_Alumno;");
                        $sqlSentencia -> bindParam(':idGrupo',$sltGruposS);
                        $sqlSentencia -> execute();


                        $listaAlumnos = $sqlSentencia -> fetchAll(PDO::FETCH_ASSOC);
                    } catch (Exception $e) {
                        $accion = "Error";
                    }
                }
            }
            break;
        case "Cancelar2";
            header('location: alumnos2.php');
            break;
    }

    if($alumnosBuscar == "TODOS"){
        $sqlSentencia = $con ->prepare("SELECT 
                            id_Alumno,
                            CONCAT(nombreAlumno, ' ', apellidoPAlumno, ' ', apellidoMAlumno) as Nombre,
                            CONCAT(escolar.etapaEscolar, ' ', escolar.gradoEscolar, ' ', escolar.grupoEscolar) as datosEscolares,  
                            matricula, 
                            isBecario, 
                            id_Usuario
                        FROM alumnos 
                        INNER JOIN datos_escolares escolar ON escolar.id_DatosEscolar = alumnos.id_DatosEscolar
                        AND alumnos.isActivo LIKE 'A'
                        ORDER BY id_Alumno;");

        $sqlSentencia -> execute();


        $listaAlumnos = $sqlSentencia -> fetchAll(PDO::FETCH_ASSOC);
    }

    



    $sqlSentencia = $con ->prepare("SELECT id_DatosEscolar, d.id_Periodo, CONCAT(gradoEscolar, ' - ', grupoEscolar) as Nombre , d.isActivo, etapaEscolar
                                    FROM datos_escolares d
                                    INNER JOIN periodos p ON d.id_Periodo = p.id_Periodo
                                    WHERE d.isActivo = 'A'
                                    ORDER BY d.id_DatosEscolar;");

    $sqlSentencia -> execute();


    $listaDatosE = $sqlSentencia -> fetchAll(PDO::FETCH_ASSOC);

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


    $sqlSentencia = $con ->prepare("SELECT *
                                    FROM datos_escolares d
                                    WHERE d.isActivo LIKE 'A' AND etapaEscolar LIKE 'Primaria';");

    $sqlSentencia -> execute();


    $listaGruposP = $sqlSentencia -> fetchAll(PDO::FETCH_ASSOC);


    $sqlSentencia = $con ->prepare("SELECT *
    FROM datos_escolares d
    WHERE d.isActivo LIKE 'A' AND etapaEscolar LIKE 'Secundaria';");

    $sqlSentencia -> execute();


    $listaGruposS = $sqlSentencia -> fetchAll(PDO::FETCH_ASSOC);



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
        <input hidden type="text" name="txtPagina" id="txtPagina" value="alumnos2.php">
    </div>


    <div>
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col col-12">
                            <div class="card">
                                <h5 class="card-header">DATOS ALUMNO</h5>
                                <div class="card-body">
                                    <form method="POST" enctype="multipart/form-data">

                                        <div hidden class="form-group" id="divId">
                                            <input type="text" name="txtIdA" id="txtIdA"
                                                value="<?php echo $id_Alumno ?>">

                                        </div>

                                        <div class="form-group" id="divId">
                                            <input hidden type="text" name="txtIdU" id="txtIdU"
                                                value="<?php echo $idUsuario ?>">

                                        </div>

                                        <div class="form-group" id="divId">
                                            <input hidden type="text" name="txAccion" id="txAccion"
                                                value="<?php echo $accion ?>">

                                        </div>


                                        <div class="accordion" id="accordionExample">
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingOne">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                                        aria-expanded="true" aria-controls="collapseOne">
                                                        Datos Usuario
                                                    </button>
                                                </h2>
                                                <div id="collapseOne" class="col col-12 accordion-collapse collapse"
                                                    aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body ">
                                                        <div class="form-group group-E">
                                                            <label for="txtCorreo">Correo</label>
                                                            <input type="text" class="form-control" name="txtCorreo"
                                                                id="txtCorreo" placeholder="ejemplo@gmail.com"
                                                                value="<?php echo $txtCorreo ?>">
                                                        </div>

                                                        <div class="form-group group-E">
                                                            <label for="txtContra">Contraseña</label>
                                                            <input type="password" class="form-control" name="txtContra"
                                                                id="txtContra" placeholder=""
                                                                value="<?php echo $txtContra ?>">
                                                        </div>

                                                        <div hidden class="form-group group-E">
                                                            <label for="sltTipo">Tipo Usuario</label>
                                                            <select class="form-select"
                                                                aria-label="Default select example" name="sltTipo"
                                                                id="sltTipo">
                                                                <option selected>Elige una opción</option>
                                                                <?php foreach($listaTUsuarios as $tUsuario){?>

                                                                <option
                                                                    <?php if(intval($tUsuario["id_TipoUsuario"]) == $sltTipo){echo "selected";} ?>
                                                                    value="<?php echo $tUsuario["id_TipoUsuario"]?>">
                                                                    <?php echo $tUsuario["nombreTipo"]?></option>

                                                                <?php } ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="headingTwo">
                                                    <button class="accordion-button collapsed" type="button"
                                                        data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                        aria-expanded="false" aria-controls="collapseTwo">
                                                        Datos Alumno
                                                    </button>
                                                </h2>
                                                <div id="collapseTwo" class="col col-12 accordion-collapse collapse"
                                                    aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="form-group group-E">
                                                            <label for="sltDatosE">Datos Escolares</label>
                                                            <select class="form-select"
                                                                aria-label="Default select example" name="sltDatosE"
                                                                id="sltDatosE">
                                                                <option selected>Elige una opción</option>
                                                                <?php foreach($listaDatosE as $datoE){?>

                                                                <option
                                                                    <?php if(intval($datoE["id_DatosEscolar"]) == $sltDatosE){echo "selected";} ?>
                                                                    value="<?php echo $datoE["id_DatosEscolar"]?>">
                                                                    <?php echo $datoE["Nombre"]." (".$datoE["etapaEscolar"].")" ?>
                                                                </option>

                                                                <?php } ?>
                                                            </select>
                                                        </div>

                                                        <div class="form-group group-E">
                                                            <label for="sltBeca">Becado</label>
                                                            <select class="form-select"
                                                                aria-label="Default select example" name="sltBeca"
                                                                id="sltBeca">
                                                                <option
                                                                    <?php if(isset($sltBecado) && $sltBecado == 1){echo "selected";} ?>
                                                                    value="1">Si</option>
                                                                <option
                                                                    <?php if(isset($sltBecado) && $sltBecado == 0){echo "selected";} ?>
                                                                    value="0">No</option>
                                                            </select>
                                                        </div>

                                                        <div class="form-group group-E">
                                                            <label for="txtMatricula">Matrícula</label>
                                                            <input type="text" class="form-control" name="txtMatricula"
                                                                id="txtMatricula" placeholder=""
                                                                value="<?php echo $txtMatricula ?>">
                                                        </div>

                                                        <div class="form-group group-E">
                                                            <label for="txtNombre">Nombre(s)</label>
                                                            <input type="text" class="form-control" name="txtNombre"
                                                                id="txtNombre" placeholder=""
                                                                value="<?php echo $txtNombre ?>">
                                                        </div>


                                                        <div class="form-group group-E">
                                                            <label for="txtAP">Apellido Paterno</label>
                                                            <input type="text" class="form-control" name="txtAP"
                                                                id="txtAP" placeholder="" value="<?php echo $txtAP ?>">
                                                        </div>


                                                        <div class="form-group group-E">
                                                            <label for="txtAM">Apellido Materno</label>
                                                            <input type="text" class="form-control" name="txtAM"
                                                                id="txtAM" placeholder="" value="<?php echo $txtAM ?>">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

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

                    </div>
                    <div class="row">

                        <div class="col col-12 text-center">
                            <br />
                            <form method="POST" enctype="multipart/form-data">

                                <div class="container">
                                    <div class="row">
                                        <div class="col">
                                            <div class="form-group">
                                                <label for="sltEtapa">Etapa Escolar</label>
                                                <select class="form-select" aria-label="Default select example"
                                                    name="sltEtapa" id="sltEtapa">
                                                    <option value="Primaria">Primaria</option>
                                                    <option value="Secundaria">Secundaria</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="gruposPrimaria" class="col">
                                            <div class="form-group">
                                                <label for="sltGruposP">Grado y Grupo</label>
                                                <select class="form-select" aria-label="Default select example"
                                                    name="sltGruposP" id="sltGruposP">
                                                    <option value="0" selected>Elige una opción</option>
                                                    <?php foreach($listaGruposP as $gPrimaria){?>

                                                    <option value="<?php echo $gPrimaria["id_DatosEscolar"]?>">
                                                        <?php echo $gPrimaria["gradoEscolar"]." - ".$gPrimaria["grupoEscolar"]?>
                                                    </option>

                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div id="gruposSecundaria" hidden class="col">
                                            <div class="form-group">
                                                <label for="sltGruposS">Grado y Grupo</label>
                                                <select class="form-select" aria-label="Default select example"
                                                    name="sltGruposS" id="sltGruposS">
                                                    <option value="0" selected>Elige una opción</option>
                                                    <?php foreach($listaGruposS as $gSecundaria){?>

                                                    <option value="<?php echo $gSecundaria["id_DatosEscolar"]?>">
                                                        <?php echo $gSecundaria["gradoEscolar"]." - ".$gSecundaria["grupoEscolar"]?>
                                                    </option>

                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <br />

                                <div class="btn-group flex-wrap text-center" role="group" aria-label=""
                                    style="width: 40%;">
                                    <button type="submit" name="accion" value="Buscar"
                                        class="btn btn-primary">Buscar</button>
                                    <button type="submit" name="accion" value="Cancelar2" class="btn btn-secondary"
                                        style="margin-left:10px!important;">Ver todos los
                                        resultados</button>
                                    <button class="btn btn-secondary" id="batchGroupModalButton"
                                        style="margin-left:10px!important;">Actualizar grupo</button>
                                    <div id="modalContainer"></div>

                                         


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
                                                    echo $alumnosBuscar."<br/>";

                                                    switch ($alumnosBuscar) {
                                                        case 'TODOS':
                                                            echo "Datos de todos los alumnos";
                                                            break;
                                                        case 'SECUNDARIA':
                                                            foreach($listaGruposS as $secundaria){

                                                                if($sltGruposS == $secundaria["id_DatosEscolar"]){
                                                                    echo "Datos de alumnos del ".
                                                                    $secundaria["gradoEscolar"]." - ".$secundaria["grupoEscolar"].
                                                                    " (".$secundaria["etapaEscolar"].")";
                                                                }

                                                                
                                                            }
                                                            break;
                                                        case 'PRIMARIA':
                                                            foreach($listaGruposP as $primaria){

                                                                if($sltGruposP == $primaria["id_DatosEscolar"]){
                                                                    echo "Datos de alumnos del ".
                                                                    $primaria["gradoEscolar"]." - ".$primaria["grupoEscolar"].
                                                                    " (".$primaria["etapaEscolar"].")";
                                                                }
                                                                
                                                            }
                                                            break;
                                                    }

                                                    

                                                    ?></h4>
                                </div>
                            </div>
                        </div>

                        <div class="col col-12 table-wrapper text-center">
                            <table class="table table-responsive text-center" style="witdh:100%;">
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
                                        <td><?php $becado = ($alumno["isBecario"] == 1)? "Si": "No"; echo($becado);?></td>
                                        <td>
                                            <form method="post"id="formUser" >
                                                <input hidden type="text" name="txtId" id="txtId"
                                                    value="<?php echo $alumno['id_Alumno'] ?>">
                                                <input hidden type="text" name="txtIdUsuario" id="txtIdUsuario"
                                                    value="<?php echo $alumno['id_Usuario'] ?>">
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
<?php 
include('config/bd.php');
$sqlSentencia = $con ->prepare("SELECT etapaEscolar, d.id_DatosEscolar, gradoEscolar, grupoEscolar, 
                                    CONCAT(nombrePeriodo, ' ', SUBSTR(fechaInicia, 1,4), ' - ' ,SUBSTR(fechaTermina, 1,4)) as periodo
                                    FROM datos_escolares d
                                    INNER JOIN periodos p ON d.id_Periodo = p.id_Periodo
                                    WHERE d.isActivo = 'A'
                                    ORDER BY id_DatosEscolar;");

    $sqlSentencia -> execute(); 
    $listaGrupos = $sqlSentencia -> fetchAll(PDO::FETCH_ASSOC); 
?>

<script type="text/javascript"> 
    const batchGroupModalButton = document.getElementById("batchGroupModalButton")
    const modalContainer = document.getElementById("modalContainer")

    const groups=<?php echo json_encode($listaGrupos)?>;
    const students=<?php echo json_encode($listaAlumnos)?>;
    console.log (students)

    let id_DatosEscolar = undefined

    batchGroupModalButton.addEventListener("click",(e) => { 
        e.preventDefault()
        modalContainer.innerHTML = `
            <div id="batchGroupModal"> 
                <p id="successMessage"></p>
                <p id="errorMessage"></p>
                <p>Selecciona el grupo que quieras actualizar</p>
                <select id="datosEscolarSelect"> 
                    ${groups.map((group) => {
                        return ('<option value="' + group.id_DatosEscolar + '">' + group.gradoEscolar + ' ' + group.grupoEscolar + ' ' + group.etapaEscolar + '</option>')
                    })}
                </select>
                <button class="btn btn-primary" id="saveButton">Actualizar</button>
                
                <button class="btn btn-default" id="closeModalButton">Cerrar</button>
            </div>`;

        const datosEscolarSelect = document.getElementById("datosEscolarSelect");
        datosEscolarSelect.addEventListener('change', (e) => { 
            id_DatosEscolar = e.target.value;
        } )

        const saveButton = document.getElementById("saveButton");
        saveButton.addEventListener("click", (e) => {
            e.preventDefault();
            updateStudents();
        });

        const closeModalButton = document.getElementById("closeModalButton");
        closeModalButton.addEventListener("click", (e) => {
            e.preventDefault();
           location.reload(); 
        });


    })

    const updateStudents = () => {
        const _datos = new FormData()
        _datos.append("id_DatosEscolar", id_DatosEscolar)
        _datos.append("students", students.map(student => {
            return student.id_Alumno;
        }))

        fetch('http://localhost/proyectoFundacion/update-students-group.php', {
            method: "POST",
            body: _datos,
        })
        .then(response => {
            return response.text();
        }) 
        .then(json => {
            const successMessage = document.getElementById("successMessage");
            const errorMessage = document.getElementById("errorMessage");
            try{
                const result = JSON.parse(json);
                if(result.status===200){
                    errorMessage.innerHTML = "";
                    successMessage.innerHTML = "Los datos escolares de los alumnos han sido actualizados";
                } 
            }catch (e){
                errorMessage.innerHTML = "No fue posible actualizar los datos escolares";
                successMessage.innerHTML = "";
                console.error(e);
            }
        })
        .catch(err => console.log(err));
        
    }

    
</script>

<?php include('template/scripts.php') ?>
<script type="text/javascript" src="js/alum.js"></script>
<script type="text/javascript" src="js/notify.js"></script>
</html>