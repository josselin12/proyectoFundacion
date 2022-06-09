<?php

    $host = "localhost";
    $bd = "fbpasilo_bd_fundacion";
    $usuario = "fbpasilo";
    $constrasena = ")x_R72(X0cTa";

    try{
      $con = new PDO("mysql:host={$host}; dbname={$bd}",$usuario,$constrasena);
      if($con){
        
      }
    }catch(Exception $ex){
        echo $ex->getMessage();
    }
?>

