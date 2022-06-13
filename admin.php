<?php 
  session_start();

  if(isset($_SESSION['usuario']) && $_SESSION['tipo'] == "Administrador" ){
    include('config/bd.php');
    $_SESSION['pagina'] = "indexAdmin";

    /* usuarios  */
    $sqlSentencia = $con ->prepare("SELECT Count(*) as usuarios 
    FROM usuarios WHERE isActivo = 'A';");
    $sqlSentencia -> execute();

    $dash = $sqlSentencia -> fetch(PDO::FETCH_LAZY);

    $numeroUsuarios = $dash["usuarios"];
    /* alumnos  */

    $sqlSentencia = $con ->prepare("SELECT Count(*) as nAlumnos 
                                    FROM alumnos
                                    WHERE isActivo = 'A';");
    $sqlSentencia -> execute();

    $dash2 = $sqlSentencia -> fetch(PDO::FETCH_LAZY);

    /* comprobantes  */
    $sqlSentencia = $con ->prepare("SELECT COUNT(*) as nComprobante
    FROM comprobante
    WHERE month(fechaComprobante) = month(now());");
    $sqlSentencia -> execute();

    $dash3 = $sqlSentencia -> fetch(PDO::FETCH_LAZY);

    /* becados  */
    $sqlSentencia = $con ->prepare("SELECT Count(*) as becados FROM alumnos WHERE isBecario = 1 AND isActivo = 'A';");
    $sqlSentencia -> execute();

    $v1 = $sqlSentencia -> fetch(PDO::FETCH_LAZY);

    /* Usuarios admin  */

    $sqlSentencia = $con ->prepare("SELECT COUNT(*) as usuariosAd FROM usuarios WHERE id_TipoUsuario = 1 AND isActivo = 'A';");
    $sqlSentencia -> execute();

    $v2 = $sqlSentencia -> fetch(PDO::FETCH_LAZY);

    /* Usuarios alumno  */

    $sqlSentencia = $con ->prepare("SELECT COUNT(*) as usuariosAl FROM usuarios WHERE id_TipoUsuario = 2 AND isActivo = 'A';");
    $sqlSentencia -> execute();

    $v3 = $sqlSentencia -> fetch(PDO::FETCH_LAZY);
  }else{
    header("Location:error.php");
  }

 
  
?>

<!DOCTYPE html>
<html lang="es">
<?php include('template/head.php') ?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@mdi/font@6.6.96/css/materialdesignicons.min.css">
<link rel="stylesheet" href="css/icons.css">

<body id="body-pd">

    <?php include('template/barra.php') ?>

    <div style="margin-top: 80px;"></div>

    <div>
        <div class="card">
            <div class="card-body">
                <div class="container">
                    <div class="row">
                        <div class="col col-4">
                            <div class="card">
                                <div class="card-body text-center" style="font-size:18px;">
                                    <span class="mdi mdi-account-circle icon" style="color:#719CD6"></span><br />
                                    Número de usuarios<br />

                                    <span data-count-from="100" data-count-to="<?php echo $numeroUsuarios; ?> "
                                        class="stats" style="font-size:15px;">00</span>
                                </div>
                            </div>
                        </div>
                        <div class="col col-4">
                            <div class="card">
                                <div class="card-body text-center" style="font-size:18px;">
                                    <span class="mdi mdi-account-school icon" style="color:#F13434"></span><br />
                                    Número de alumnos<br />

                                    <span data-count-from="100" data-count-to="<?php echo ($dash2["nAlumnos"]); ?> "
                                        class="stats" style="font-size:15px;">00</span>

                                </div>
                            </div>
                        </div>
                        <div class="col col-4">
                            <div class="card">
                                <div class="card-body text-center" style="font-size:18px;">
                                    <span class="mdi mdi-text-box-check icon" style="color:#34BA63"></span><br />
                                    Número de comprobantes del mes<br />

                                    <span data-count-from="100" data-count-to="<?php echo ($dash3["nComprobante"]); ?> "
                                        class="stats" style="font-size:15px;">00</span>

                                </div>
                            </div>
                        </div>
                    </div>
                    <br/>
                    <div class="row">
                    <input hidden type="text" class="form-control" id="txtBecados" value="<?php echo ($v1["becados"]); ?>">
                    <input hidden type="text" class="form-control" id="txtAlumnos" value="<?php echo ($dash2["nAlumnos"]); ?>">
                    <input hidden type="text" class="form-control" id="txtUAdmin" value="<?php echo ($v2["usuariosAd"]); ?>">
                    <input hidden type="text" class="form-control" id="txtUAlum" value="<?php echo ($v3["usuariosAl"]); ?>">
                        <div class="col col-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Becas</h5>
                                    <canvas id="chartBecados" width="400" height="400"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col col-6">
                            <div class="card">
                                <div class="card-body text-center">
                                    <h5 class="card-title">Usuarios</h5>
                                    <canvas id="chartUsuarios" width="400" height="400"></canvas>
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
<script src="assets/chartjs/chart.js"></script>
<script type="text/javascript" src="assets/timber/js/timber.master.min.js"></script>
<script type="text/javascript" src="js/panel.js"></script>


</html>