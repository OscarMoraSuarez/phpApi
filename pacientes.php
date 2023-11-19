<?php 
    require_once "classes/Respuestas.class.php";
    require_once "classes/pacientes.class.php";

    $_respuestas= new respuestas;
    $_pacientes=new pacientes;

    if($_SERVER['REQUEST_METHOD'] == "GET"){

        if (isset($_GET["page"])){
            $pagina = $_GET["page"];
            $listaPacientes=$_pacientes->listarPacientes($pagina);
            header("Content-Type:Application/json");
            echo json_encode($listaPacientes);
            http_response_code(200);
        }else if (isset($_GET["id"])) {
            $pacienteId=$_GET["id"]; 
            $datosPaciente=$_pacientes->obtenerPaciente($pacienteId);
            header("Content-Type:Application/json");
            echo json_encode($datosPaciente);     
            http_response_code(200);
        }
        
    }else if ($_SERVER['REQUEST_METHOD']=="POST") {
        //Recibo los datos enviados
        $postBody=file_get_contents("php://input");
        //enviamos esto al manejador
        $datosArray=$_pacientes->post($postBody);
        //devuelvo una respuesta
        header("Content-Type_:application/json");
       if (isset($datosArray["result"]["error_id"])) {
        $responseCode=$datosArray["result"]["error_id"];
        http_response_code($responseCode);
       } else {
        http_response_code(200);
       }
       echo json_encode($datosArray);
       
    }else if ($_SERVER['REQUEST_METHOD']=="PUT") {
        //Recibo los datos enviados
        $postBody=file_get_contents("php://input");
        //enviamos datos al manejador
        $datosArray=$_pacientes->put($postBody);
         //devuelvo una respuesta
         header('Content-Type: application/json');
         if(isset($datosArray["result"]["error_id"])){
             $responseCode = $datosArray["result"]["error_id"];
             http_response_code($responseCode);
         }else{
             http_response_code(200);
         }
         echo json_encode($datosArray);

    }else if ($_SERVER['REQUEST_METHOD']=="DELETE") {
        //Recibo los datos enviados
        $postBody=file_get_contents("php://input");
        //enviamos datos al manejador
        $datosArray=$_pacientes->delete($postBody);
         //devuelvo una respuesta
         header('Content-Type: application/json');
         if(isset($datosArray["result"]["error_id"])){
             $responseCode = $datosArray["result"]["error_id"];
             http_response_code($responseCode);
         }else{
             http_response_code(200);
         }
         echo json_encode($datosArray);
    }else{
        header("Content-Type_:application/json");
        $datosArray=$_respuestas->error_405();
        echo json_encode($datosArray);
    }



?>