<?php

session_start();

if(isset($_SESSION['usuario'])  && $_SESSION['tipo'] == "Alumno"){
    header("Location:alumno.php");
}else if(isset($_SESSION['usuario'])  && $_SESSION['tipo'] == "Administrador"){
    header("Location:admin.php");
}

//echo $_SESSION['usuario']."<br/>";

$correo =(isset($_POST["correo"]))? $_POST["correo"]: "" ;
$contrasena =(isset($_POST["contrasena"]))? $_POST["contrasena"]: "" ;
$accion =(isset($_POST["accion"]))? $_POST["accion"]: "" ;

// echo $correo."<br/>";
// echo $contrasena."<br/>";


if($accion == "Ingresar"){
    include('config/bd.php');

    $sqlSentencia = $con ->prepare("SELECT * 
                            FROM usuarios u
                            INNER JOIN tipousuario t ON u.id_TipoUsuario = t.id_TipoUsuario
                            WHERE correo = :correo AND contrasena LIKE :contrasena AND u.isActivo LIKE 'A';");
    $sqlSentencia -> bindParam(':correo',$correo);
    $sqlSentencia -> bindParam(':contrasena',$contrasena);
    $sqlSentencia -> execute();


    $usuarioS = $sqlSentencia -> fetch(PDO::FETCH_LAZY);

    $id = (isset($usuarioS['id_Usuario']))? intval($usuarioS['id_Usuario']) : 0;

    if($id > 0 ){
        session_start();
        $_SESSION['usuario'] = $id;
        //echo $_SESSION['usuario']."</br>";
        //echo $id."</br>";
        $_SESSION['tipo'] = $usuarioS['nombreTipo'];
        $_SESSION['correo'] = $usuarioS['correo'];
        if($usuarioS['nombreTipo'] == "Alumno"){
            $sqlSentenciaA = $con ->prepare("SELECT *
                                            FROM usuarios u
                                            INNER JOIN tipousuario t ON u.id_TipoUsuario = t.id_TipoUsuario
                                            INNER JOIN alumnos a ON u.id_Usuario = a.id_Usuario
                                            WHERE a.id_Usuario = :id;");
            $sqlSentenciaA -> bindParam(':id',$id);
            $sqlSentenciaA -> execute();


            $usuarioSA = $sqlSentenciaA -> fetch(PDO::FETCH_LAZY);

            $idA = (isset($usuarioSA['id_Alumno']))? intval($usuarioSA['id_Alumno']) : 0;

            $_SESSION['alumno'] = $idA;
            header('location: alumno.php');
        }else{
            header ("location: admin.php");
        }

    }else{
        //echo "DATOS INCORRECTOS <br/>";
    }
    
}


//session_start();




?>


<!DOCTYPE html>
<html lang="es">

<head>
    <title>Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1,
        minimum-scale=1">
    <link rel="stylesheet" href="css/estilo1.css">
</head>


<body>


    <form method="post">
        <h2>Bienvenido! Ingresa tu usuario y contraseña para logearte</h2>
        <input type="text" placeholder="&#128273; Usuario" name="correo">
        <input type="password" placeholder="&#128273; Contraseña" name="contrasena">
        <input type="submit" name="accion" value="Ingresar">

    </form>

</body>

</html>