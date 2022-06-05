<?php

    $host = "localhost";
    $bd = "bd_fundacion";
    $usuario = "root";
    $constrasena = "";

    try{
      $con = new PDO("mysql:host={$host}; dbname={$bd}",$usuario,$constrasena);
      if($con){
        
      }
    }catch(Exception $ex){
        echo $ex->getMessage();
    }
?>

