<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API</title>
    <link rel="stylesheet" href="style/style.css">
</head>
    <body>
        <header>
            <div>
                <form action="" method="post">
                    <input type="text" name="name" id="name" placeholder="Buscar">
                </form>
            </div>
        </header>

        <?php

            if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['name'])) {

                $name = str_replace(" ","_",htmlspecialchars($_POST['name']));

                $curl = curl_init();
                curl_setopt_array($curl, [
            
                CURLOPT_URL => "http://api.openweathermap.org/geo/1.0/direct?q=". $name . "&limit=1&appid=e8637565bddc7298468754423329be60",
            
                CURLOPT_RETURNTRANSFER => true
                ]);
                $geoResponse = curl_exec($curl);
                curl_close($curl);

                $cities = json_decode($geoResponse, true);
                
                
                if(!empty($cities)){
                    foreach ($cities as $city){
                        $lat = $city['lat'];
                        $lon = $city['lon'];
                        $cityName = $city['name'];

                        $curl = curl_init();
                            curl_setopt_array($curl, [
            
                            CURLOPT_URL => "http://api.openweathermap.org/data/2.5/air_pollution?lat=".$lat."&lon=".$lon."&appid=e8637565bddc7298468754423329be60",
            
                            CURLOPT_RETURNTRANSFER => true
                        ]);
                        
                        $airResponse = curl_exec($curl);
                        curl_close($curl);

                        $air = json_decode($airResponse, true);

                        if (!empty($air['list'])) {
                            $airQuality = $air['list'][0]['main']['aqi'];
                            $gas = $air['list'][0]['components'];

                            switch($airQuality) {
                                case "1":
                                    $quality = "Boa";
                                    $recomendation = "Bom para atividades ao ar livre";
                                    break;
                                case "2":
                                    $quality = "Razoável";
                                    $recomendation = "Atividades ao ar livre são aceitaveis";
                                    break;
                                case "3":
                                    $quality = "Moderada";
                                    $recomendation = "Evite atividades intensas ao ar livre";
                                    break;
                                case "4":
                                    $quality = "Ruim";
                                    $recomendation = "Evite atividades fisicas ao ar livre";
                                    break;
                                case "5":
                                    $quality = "Péssima";
                                    $recomendation = "Evite sair de casa";
                                    break;
                            }
                            }
                        else {
                        $airError = "Informações sobre a qualidade do ar não foram encontradas";
                        }
                    }
                }
                else{
                    $geoError = "Nenhuma cidade encontrada";
                }

            }
            
        ?>
        <?php if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['name'] && !empty($cities))) :?>
            <main>
                <div>
                    <h1> CIDADE: <?=$cityName?> </h1>
                </div>
                <div class="full-box">    
                    <div>
                        <h2>Indice de qualidade do ar: <?=$quality?></h2>
                        <h3>Componentes de poluição:</h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>PM2.5</th>
                                    <th>PM10</th>
                                    <th>NO2</th>
                                    <th>SO2</th>
                                    <th>O3</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><?=$gas['pm2_5']?></td>
                                    <td><?=$gas['pm10']?></td>
                                    <td><?=$gas['no2']?></td>
                                    <td><?=$gas['so2']?></td>
                                    <td><?=$gas['o3']?></td>
                                </tr>
                            </tbody>
                        </table>
                        <div>
                            Recomendações: <?= $recomendation ?>;
                        </div>
                    </div>
                </div>
            </main>
        <?php elseif(!empty($cities) && empty($air)) :?>
            <main>
                <div><?= $airError ?></div>
            </main>
        <?php else :?>
            <main>
                <div><?= $geoError ?></div>
            </main>
        <?php endif ;?>
    </body>
</html>
