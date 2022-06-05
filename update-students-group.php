<?php 
include ('config/bd.php');

$id_DatosEscolar = isset($_POST['id_DatosEscolar']) ? $_POST['id_DatosEscolar'] : '';
$students = isset($_POST['students']) ? $_POST['students'] : '';

try {
    $sqlSentencia = $con ->prepare("UPDATE alumnos 
        SET id_DatosEscolar = $id_DatosEscolar
        WHERE id_Alumno IN ($students);");
    $sqlSentencia -> execute();

    echo '{ "status" : 200, "error": null  }';

}catch (Exception $e) {
    echo '{ "status" : 500, "error": "Ocurrió un error"  }';
}

?>
