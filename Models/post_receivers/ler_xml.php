<?php 

date_default_timezone_set('America/Sao_Paulo');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $uploadDir = "uploads/"; // Diretório onde os arquivos serão salvos
    echo "__CODE__";
    $xmlFile = $_FILES["xmlFile"];

    if ($xmlFile["error"] == UPLOAD_ERR_OK) {
        $tempName = $xmlFile["tmp_name"];
        $newName = $uploadDir . basename($xmlFile["name"]);

    
            $caminho = "C:\\XamppOficial\\htdocs\\mixsalgadosonline\\31240226013236000156550010000886801362857503.xml";
            $xmlstring = file_get_contents($caminho);
            $xml = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA);
            $json = json_encode($xml);
            $array = json_decode($json,TRUE);
          
            $produtos = $array["NFe"]["infNFe"]["det"];
            $arrayValores = [];
            foreach ($produtos as $key => $value) {
                    array_push($arrayValores,$value);
            }
            print_r(json_encode($arrayValores));

    } else {
        echo "Error during file upload. Error code: " . $xmlFile["error"];
    }
} else {
    echo "Invalid request.";
}


?>