<?php 
  session_start();

  if(isset($_SESSION['usuario']) && $_SESSION['tipo'] == "Administrador"){
    $_SESSION['pagina'] = "Grupos";
    /*echo "<br/><br/><br/><br/><br/>";
    print_r($_POST);*/

    $txtEliminar = (isset($_POST["txtEliminar"]))? $_POST["txtEliminar"]: "" ;


    $sltEtapa =(isset($_POST["sltEtapa"]))? $_POST["sltEtapa"]: "" ;
    $sltPeriodo =(isset($_POST["sltPeriodo"]))? $_POST["sltPeriodo"]: "" ;
    $txtGrado =(isset($_POST["txtGrado"]))? $_POST["txtGrado"]: "" ;
    $txtGrupo =(isset($_POST["txtGrupo"]))? $_POST["txtGrupo"]: "" ;

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
        header('location: grupos.php');
        $accion = "";
    }

    include('config/bd.php');

    switch($accion){
        case "Agregar":
            //INSERT INTO datos_escolares VALUES (default, :id_Periodo, :gradoEscolar, :grupoEscolar, default);

            if($sltPeriodo == "" ||$txtGrado == "" ||$txtGrupo == ""){
                $accion = "Vacio";
            }else{
                try {
                    $sqlSentencia = $con ->prepare("INSERT INTO datos_escolares 
                                            VALUES (default, :id_Periodo, :gradoEscolar, :grupoEscolar, default, :etapaEscolar);");
            
                    $sqlSentencia -> bindParam(':id_Periodo',$sltPeriodo);
                    $sqlSentencia -> bindParam(':gradoEscolar',$txtGrado);
                    $sqlSentencia -> bindParam(':grupoEscolar',$txtGrupo);
                    $sqlSentencia -> bindParam(':etapaEscolar',$sltEtapa);
                    $sqlSentencia -> execute();
                }catch(Exception $e){
                    $accion = "Error";
                }
            }
            
            break;
        case "Modificar":

            if($sltPeriodo == "" ||$txtGrado == "" ||$txtGrupo == ""){
                $accion = "Vacio";
            }else{
                try {
                    $sqlSentencia = $con ->prepare("UPDATE datos_escolares SET id_Periodo = :id_Periodo, gradoEscolar = :gradoEscolar,
                    grupoEscolar = :grupoEscolar, etapaEscolar = :etapaEscolar WHERE id_DatosEscolar = :id_DatosEscolar;");
                    
                    $sqlSentencia -> bindParam(':id_Periodo',$sltPeriodo);
                    $sqlSentencia -> bindParam(':gradoEscolar',$txtGrado);
                    $sqlSentencia -> bindParam(':grupoEscolar',$txtGrupo);
                    $sqlSentencia -> bindParam(':etapaEscolar',$sltEtapa);
                    $sqlSentencia -> bindParam(':id_DatosEscolar',$txtId);
                    $sqlSentencia -> execute();
                }catch(Exception $e){
                    $accion = "Error";
                }
            }
            
            break;
        case "Cancelar":
            header('location: grupos.php');
            break;
        case "Seleccionar":
            $sqlSentencia = $con ->prepare("SELECT * 
                                            FROM datos_escolares
                                            WHERE id_DatosEscolar = :id");
            $sqlSentencia -> bindParam(':id',$txtId);
            $sqlSentencia -> execute();


            $grupo = $sqlSentencia -> fetch(PDO::FETCH_LAZY);
            $sltPeriodo = $grupo["id_Periodo"];
            $txtGrado = $grupo["gradoEscolar"];
            $txtGrupo = $grupo["grupoEscolar"];
            $sltEtapa = $grupo["etapaEscolar"];
            $id_Grupo= $grupo["id_DatosEscolar"];


            break;
        case "Eliminar":
            if($txtEliminar == ""){
                $_SESSION['accionEliminar'] = "Eliminar";
                $_SESSION['idEliminar'] = $txtId;
            }else if($txtEliminar == "SI"){
                $_SESSION['accionEliminar'] = "NO";
                
                $idEliminar = intval($_SESSION['idEliminar']);

                $sqlSentencia = $con ->prepare("UPDATE datos_escolares
                                            SET isActivo = 'N'
                                            WHERE id_DatosEscolar = :id;");
                $sqlSentencia -> bindParam(':id',$idEliminar);
                $sqlSentencia -> execute();
                
            }
            /*$sqlSentencia = $con ->prepare("UPDATE datos_escolares
                                            SET isActivo = 'N'
                                            WHERE id_DatosEscolar = :id;");
            $sqlSentencia -> bindParam(':id',$txtId);
            $sqlSentencia -> execute();*/
            break;
    }


    $sqlSentencia = $con ->prepare("SELECT etapaEscolar, d.id_DatosEscolar, gradoEscolar, grupoEscolar, 
                                    CONCAT(nombrePeriodo, ' ', SUBSTR(fechaInicia, 1,4), ' - ' ,SUBSTR(fechaTermina, 1,4)) as periodo
                                    FROM datos_escolares d
                                    INNER JOIN periodos p ON d.id_Periodo = p.id_Periodo
                                    WHERE d.isActivo = 'A'
                                    ORDER BY id_DatosEscolar;");

    $sqlSentencia -> execute();


    $listaGrupos = $sqlSentencia -> fetchAll(PDO::FETCH_ASSOC);

    $sqlSentencia = $con ->prepare("SELECT id_Periodo, 
                                    CONCAT(nombrePeriodo, ' ', SUBSTR(fechaInicia, 1,4), ' - ' ,SUBSTR(fechaTermina, 1,4)) as periodo
                                    FROM  periodos p 
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
        <input hidden type="text" name="txtPagina" id="txtPagina" value="grupos.php">
    </div>

    <div>
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col col-5">
                            <div class="card">
                                <h5 class="card-header">DATOS GRUPO</h5>
                                <div class="card-body">
                                    <form method="POST" enctype="multipart/form-data">

                                        <div class="form-group group-E">
                                            <label for="sltEtapa">Etapa Escolar</label>
                                            <select class="form-select" aria-label="Default select example"
                                                name="sltEtapa" id="sltEtapa">
                                                <option
                                                    <?php if(isset($sltEtapa) && $sltEtapa == "Primaria"){echo "selected";} ?>
                                                    value="Primaria">Primaria</option>
                                                <option
                                                    <?php if(isset($sltEtapa) && $sltEtapa == "Secundaria"){echo "selected";} ?>
                                                    value="Secundaria">Secundaria</option>
                                            </select>
                                        </div>

                                        <div hidden class="form-group" id="divId">
                                            <input type="text" name="txtId" id="txtId" value="<?php echo $id_Grupo ?>">

                                        </div>

                                        <div class="form-group" id="divId">
                                            <input hidden type="text" name="txAccion" id="txAccion"
                                                value="<?php echo $accion ?>">

                                        </div>

                                        <div class="form-group group-E">
                                            <label for="sltPeriodo">Periodos</label>
                                            <select class="form-select" aria-label="Default select example"
                                                name="sltPeriodo" id="sltPeriodo">
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
                                            <label for="txtGrado">Grado</label>
                                            <input type="text" class="form-control" name="txtGrado" id="txtGrado"
                                                placeholder="" value="<?php echo $txtGrado ?>">
                                        </div>

                                        <div class="form-group group-E">
                                            <label for="txtGrupo">Grupo</label>
                                            <input type="text" class="form-control" name="txtGrupo" id="txtGrupo"
                                                placeholder="" value="<?php echo $txtGrupo ?>">
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
                                        <th scope="col">Etapa escolar</th>
                                        <th scope="col">Grado Escolar</th>
                                        <th scope="col">Grupo Escolar</th>
                                        <th scope="col">Periodo</th>
                                        <th scope="col">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>


                                    <?php foreach($listaGrupos as $grupo){?>
                                    <tr>
                                        <td><?php echo $grupo["etapaEscolar"]?></td>
                                        <td><?php echo $grupo["gradoEscolar"]?></td>
                                        <td><?php echo $grupo["grupoEscolar"]?></td>
                                        <td><?php echo $grupo["periodo"]?>
                                        </td>
                                        <td>
                                            <form method="post" id="formUser">
                                                <input hidden type="text" name="txtId" id="txtId"
                                                    value="<?php echo $grupo['id_DatosEscolar'] ?>">
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
<script type="text/javascript" src="js/var/grupos.js"></script>
<script type="text/javascript" src="js/notify.js"></script>

</html>