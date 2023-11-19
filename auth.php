<?php

    require_once './classes/auth.class.php';
    require_once './classes/Respuestas.class.php';

    $_auth= new auth;
    $_respuestas= new respuestas;

    if ($_SERVER['REQUEST_METHOD']=="POST") {
        //recibo los datps por post
        $postbody=file_get_contents("php://input");
        //enviamos los datos al manejador
        $datosArray=$_auth->login($postbody);
        //devolver una respuesta
        header("Content-Type_:application/json");
        if(isset($datosArray["result"]["error_id"])){
            $responseCode=$datosArray["result"]["error_id"];
            http_response_code($responseCode);
        }else{
            http_response_code(200);
        }
        echo json_encode($datosArray);
        
    }else {
        header("Content-Type_:application/json");
        $datosArray=$_respuestas->error_405();
        echo json_encode($datosArray);
    }



?>