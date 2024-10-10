<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cidades</title>
</head>
<?php

    $curl = curl_init();
    curl_setopt_array($curl, [
 
    CURLOPT_URL => "http://api.openweathermap.org/geo/1.0/direct?q=". $_POST['name']. "&limit=5&appid=e8637565bddc7298468754423329be60",
 
    CURLOPT_RETURNTRANSFER => true
    ]);
    $response = curl_exec($curl);
    curl_close($curl);

    $cidades = json_decode($response, true);
    
    var_dump($cidades);
    echo $_POST['name']
?>
<body>
    
</body>
</html>