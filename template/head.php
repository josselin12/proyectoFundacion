<head>
        <title><?php 
        
        if($_SESSION['pagina'] == "indexAdmin"){
            echo "Dashboard";
        }else{
            echo($_SESSION['pagina']);
        }
        
        ?></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1,
        minimum-scale=1">
        <link href="assets/plugins/fontawesome/css/all.css" rel="stylesheet">
        <link href='https://cdn.jsdelivr.net/npm/boxicons@2.0.5/css/boxicons.min.css' rel='stylesheet'>
        <meta name="viewport"
            content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">

        <link rel="stylesheet" href="css/estilos.css">
        <link rel="stylesheet" href="css/leftSidebar.css">
        <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.css">
        <link rel="stylesheet" href="assets/plugins/fontawesome/css/all.css">
        <style>
        #batchGroupModal {
            height: 150px;
            width: 600px;
            background-color:  #fff;
            position: absolute;
            left: 0px;
            top: 0px;
            z-index: 999;
            box-shadow: 5px 10px 18px #888888;
        }
        #successMessage {
            color: #5B2;
        }
        #errorMessage {
            color: #900;
        }

        </style>
        
    </head>