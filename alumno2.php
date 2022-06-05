<?php 
  session_start();

  if(isset($_SESSION['usuario'])  && $_SESSION['tipo'] == "Alumno"){
    
    /*echo $_SESSION['usuario']."<br>";
    echo $_SESSION['alumno']."<br>";
*/
    $accion =(isset($_POST["accion"]))? $_POST["accion"]: "" ;
    $name_archivo =(isset($_FILES["txtFile"]["name"]))? $_FILES["txtFile"]["name"]: "" ;
    $archivo_tipo =(isset($_FILES["txtFile"]["type"]))? $_FILES["txtFile"]["type"]: "" ;
    $archivo_temp =(isset($_FILES["txtFile"]["tmp_name"]))? $_FILES["txtFile"]["tmp_name"]: "" ;
    $sltPago =(isset($_POST["sltPago"]))? $_POST["sltPago"]: "" ;
    $txtDescripcion =(isset($_POST["txtDescripcion"]))? $_POST["txtDescripcion"]: "" ;

    /*echo $accion."<br/>";
    echo $name_archivo."<br/>";
    echo $archivo_tipo."<br/>";
    echo $archivo_temp."<br/>";
    echo $sltPago."<br/>";
    echo $txtDescripcion."<br/>";*/
    //echo $_SESSION['alumno']." - alumno <br/>";

    //$archivo_binario = (file_get_contents($archivo_temp));

    include('config/bd.php');

    switch($accion){
        case "Agregar":
        //INSERT INTO comprobante VALUES(:id_Pago, :id_Alumno, :nombre, :tipo, :archivo, :descripcionComprobante);
        if($name_archivo == "" ||$archivo_tipo == "" ||  $txtDescripcion == ""){
            $accion = "Vacio";
        }else{
            try{
                $sqlSentencia = $con ->prepare("INSERT INTO comprobante 
                VALUES(DEFAULT,:id_Pago, :id_Alumno, :nombre, :tipo, :descripcionComprobante, :archivo);");
        
                $archivo_binario = (file_get_contents($archivo_temp));
                
        
                $_SESSION['pago'] = intval($sltPago);
        
                $sqlSentencia -> bindParam(':id_Pago', $_SESSION['pago']);
                $sqlSentencia -> bindParam(':id_Alumno',$_SESSION['alumno']);
                $sqlSentencia -> bindParam(':nombre',$name_archivo);
                $sqlSentencia -> bindParam(':tipo',$archivo_tipo);
                $sqlSentencia -> bindParam(':archivo',$archivo_binario);
                $sqlSentencia -> bindParam(':descripcionComprobante',$txtDescripcion);
                $sqlSentencia -> execute();
            }catch(Exception $e){
                    $accion = "Error";
            }
        }
        
        break;
        case "Cancelar":
            header('location: alumno.php');
        break;
    }



    $sqlSentencia = $con ->prepare("SELECT id_Pago, CONCAT(nombrePeriodo, ' ', fechaInicia, ' - ' ,fechaTermina) as periodo, id_Alumno, monto, fechaPago, cuenta, pa.isActivo
                                    FROM pagos pa
                                    INNER JOIN periodos p ON pa.id_Periodo = p.id_Periodo
                                    WHERE pa.id_Alumno = :id;");
    $sqlSentencia -> bindParam(':id',$_SESSION['alumno']);
    $sqlSentencia -> execute();


    $listaPagos = $sqlSentencia -> fetchAll(PDO::FETCH_ASSOC);
    //echo $accion."<br/>";
  }else{

    header("Location:error.php");
  }

 
  
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1,
        minimum-scale=1">
    <link rel="stylesheet" href="css/estilo1.css">
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="css/btnal.css">
</head>



<body>

    <form method="POST" enctype="multipart/form-data">
        <h2>Sube tu comprobante o descarga tu ficha de pago &#128578;</h2>

        <!--<input type="button" value="	&#128206; Subir comprobante" class="btn btn-primary" data-bs-toggle="modal"
            data-bs-target="#exampleModal" style="margin-bottom:10px!important;">
        <input type="button" value="	&#128317; Descargar ficha de pago" style="margin-bottom:10px!important;">-->


        <div>
        <input type="button" value="&#128206; Subir comprobante" class="btn btn-primary" data-bs-toggle="modal"
            data-bs-target="#modalSubir" style="margin-bottom:10px!important;">
        </div>

        <div>
        <input type="button" value="&#128317; Descargar ficha de pago" class="btn btn-primary" data-bs-toggle="modal"
            data-bs-target="#modalFicha" style="margin-bottom:10px!important;">
        </div>
        <div>
        <!---->
        </div>
            
            


        <a href="cerrar.php" style="margin-bottom:10px!important;"> &#11013; Salir</a>


    </form>




    </form>
    <!-- Modal -->
    <div class="modal fade" id="modalSubir" tabindex="-1" aria-labelledby="modalSubir" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSubir">Agregar Comprobante</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data" style="background-color: white;width: 100%;">
                        <div class="form-group" style="margin:10px!important;">
                            <label for="sltPago">Número Pago</label>
                            <select class="form-select" aria-label="Default select example" name="sltPago" id="sltPago">
                                <option selected>Elige una opción</option>
                                <?php foreach($listaPagos as $pago){?>

                                <option value="<?php echo $pago["id_Pago"]?>">Pago <?php echo $pago["id_Pago"]." ".$pago["periodo"]?>
                                </option>

                                <?php } ?>
                                <!--<option value="1">Pago 1</option>
                                <option value="2">Pago 2</option>-->
                            </select>
                        </div>
                        <div class="form-group" id="divId">
                            <input hidden type="text" name="txAccion" id="txAccion" value="<?php echo $accion ?>">

                        </div>

                        <div class="form-group" style="margin:10px!important;">
                            <label hidden for="txtFile" class="form-label">Seleccionar archivo</label>
                            <input class="form-control" type="file" id="txtFile" name="txtFile">
                        </div>

                        <div class="form-group" style="margin:10px!important;">
                            <label for="txtDescripcion" class="form-label">Descripción de comprobante</label>
                            <textarea class="form-control" id="txtDescripcion" name="txtDescripcion"
                                rows="3"></textarea>
                        </div>

                        <br />
                        <div class="btn-group text-center flex-wrap" role="group" aria-label="" style="width: 100%;">
                            <button type="submit" name="accion" value="Agregar" class="btn btn-primary">Agregar</button>
                            <button type="submit" name="accion" value="Cancelar" class="btn btn-secondary"
                                style="margin-left:10px!important;">Cancelar</button>
                        </div>

                    </form>
                </div>
                <!--<div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>-->
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalFicha" tabindex="-1" aria-labelledby="modalFicha" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalFicha">Descargar Ficha</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" enctype="multipart/form-data" style="background-color: white;width: 100%;">
                        <div class="form-group" style="margin:10px!important;">
                            <label for="sltPago">Número Pago</label>
                            <select class="form-select" aria-label="Default select example" name="sltPago" id="sltPago">
                                <option selected>Elige una opción</option>
                                <?php foreach($listaPagos as $pago){?>

                                <option value="<?php echo $pago["id_Pago"]?>">Pago <?php echo $pago["id_Pago"]." ".$pago["periodo"]?>
                                </option>

                                <?php } ?>
                                <!--<option value="1">Pago 1</option>
                                <option value="2">Pago 2</option>-->
                            </select>
                        </div>

                        <br />
                        <div class="btn-group text-center flex-wrap" role="group" aria-label="" style="width: 100%;">
                            <a href="pdf.php" class="btn btnAL btn-secondary" >Generar</a>
                        </div>

                    </form>
                </div>
                <!--<div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>-->
            </div>
        </div>
    </div>

    <script type="text/javascript" src="assets/jquery/jquery.js"></script>
    <script type="text/javascript" src="assets/plugins/bootstrap/js/bootstrap.js"></script>
    <script type="text/javascript" src="assets/sweetalert/sweetalert2.js"></script>
    <script type="text/javascript" src="js/notify.js"></script>
</body>

</html>