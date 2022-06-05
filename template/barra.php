
    <header class="header" id="header">
        <div class="header__toggle">
            <i class='bx bx-menu' id="header-toggle"></i>
        </div>
        <span><?php 
        
        if($_SESSION['pagina'] == "indexAdmin"){
            echo("Bienvenido ".$_SESSION['correo']);
        }else{
            echo($_SESSION['pagina']);
        }
        
        ?></span>
        <div class="header__img">
            <img src="img/icons/256x256/256_1.png" alt="">
        </div>
    </header>

    <?php $url="http://".$_SERVER['HTTP_HOST']."/proyectoFundacion"?>

    <div class="l-navbar" id="nav-bar">
        <nav class="navk">
            <div>
                <a href="<?php echo $url ?>/admin.php" class="nav_logo">
                    <!-- <i class='bx bx-layer nav__logo-icon'></i>
                    <span class="nav__logo-name">Komodo</span> -->
                    <img style="width: 150px;" src="img/imagen01.png" alt="" class="logo">
                
                    
                </a>

                <div class="nav__list">
                    <a href="<?php echo $url ?>/admin.php" class="nav__link activek">
                        <i class='bx bx-grid-alt nav__icon'></i>
                        <span class="nav__name">Inicio</span>
                    </a>

                    <a href="<?php echo $url ?>/usuarios.php" class="nav__link">
                        <i class='bx bx-user-circle nav__icon'></i>
                        <span class="nav__name">Usuarios</span>
                    </a>
                    <a href="<?php echo $url ?>/alumnos2.php" class="nav__link">
                        <i class='bx bx-user nav__icon'></i>
                        <span class="nav__name">Alumnos</span>
                    </a>

                    <a href="<?php echo $url ?>/pagos.php" class="nav__link">
                        <i class='bx bx-money nav__icon'></i>
                        <span class="nav__name">Pagos y Comprobantes</span>
                    </a>

                    <a href="<?php echo $url ?>/periodos.php" class="nav__link">
                        <i class='bx bx-calendar nav__icon'></i>
                        <span class="nav__name">Periodos</span>
                    </a>
                    <a href="<?php echo $url ?>/grupos.php" class="nav__link">
                        <i class='bx bx-group nav__icon'></i>
                        <span class="nav__name">Grupos</span>
                    </a>

                    

                    

                </div>
            </div>

            <a href="cerrar.php" class="nav__link">
                <i class='bx bx-log-out nav__icon'></i>
                <span class="nav__name">Cerrar Sesión</span>
            </a>
        </nav>
    </div>