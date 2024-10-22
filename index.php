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
                <form action="" method="post" class="search-form">
                    <input type="text" name="name" id="name" placeholder="Buscar" class="search-input">
                </form>
            </div>
        </header>

        <?php

            if ($_SERVER['REQUEST_METHOD'] == 'POST' && !empty($_POST['name'])) {
                
                $name = str_replace(" ","_",htmlspecialchars($_POST['name']));
                $apiKey = "e8637565bddc7298468754423329be60";

                $curl = curl_init();
                curl_setopt_array($curl, [
            
                CURLOPT_URL => "http://api.openweathermap.org/geo/1.0/direct?q=". $name . "&limit=1&appid=" . $apiKey,
            
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
            
                            CURLOPT_URL => "http://api.openweathermap.org/data/2.5/air_pollution?lat=".$lat."&lon=".$lon."&appid=" . $apiKey,
            
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
                                    $recommendation = "Bom para atividades ao ar livre";
                                    break;
                                case "2":
                                    $quality = "Razoável";
                                    $recommendation = "Atividades ao ar livre são aceitaveis";
                                    break;
                                case "3":
                                    $quality = "Moderada";
                                    $recommendation = "Evite atividades intensas ao ar livre";
                                    break;
                                case "4":
                                    $quality = "Ruim";
                                    $recommendation = "Evite atividades fisicas ao ar livre";
                                    break;
                                case "5":
                                    $quality = "Péssima";
                                    $recommendation = "Evite sair de casa";
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
        <?php if($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['name']) && !empty($cities)) :?>
            <main>

            <div class="city-box">
                <div class="city-name">
                    <div>Cidade</div>
                    <h1><?=$cityName?> </h1>
                </div>
                <div>    
                    <div class="quality-box">
                        <h2>Indice de qualidade do ar: <?=$quality?></h2>
                        <h3>Componentes de poluição:</h3>
                        <table class="pollution-table">
                            <thead>
                                <tr>
                                    <th>PM2.5</th>
                                    <th>PM10</th>
                                    <th>NO₂</th>
                                    <th>SO₂</th>
                                    <th>O₃</th>
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
                        <div class="recommendation">
                            Recomendações: <?= $recommendation ?>;
                        </div>
                    </div>
                </div>
            </main>
        <?php elseif(!empty($cities) && empty($air)) :?>
            <main>
                <div class="error-message"><?= $airError ?></div>
            </main>
        <?php elseif ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['name']) && empty($cities)) :?>
            <main>
                <div class="error-message"><?= $geoError ?></div>
            </main>
        <?php endif ;?>
        </div>
    </body>
</html>
