<?php

    class getApiInfo{

        function getCityInfo() {
            $json = new stdClass();
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
                        $json -> status = 1;
                        $json -> cityName = $city['name'];
                        
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
                            $json -> gas = $air['list'][0]['components'];
        
                            switch($airQuality) {
                                case "1":
                                    $json -> quality = "Boa";
                                    $json -> qualityClass = "quality-good";
                                    $json -> recommendation = "Bom para atividades ao ar livre";
                                    break;
                                case "2":
                                    $json -> quality = "Razoável";
                                    $json -> qualityClass = "quality-fair";
                                    $json -> recommendation = "Atividades ao ar livre são aceitáveis";
                                    break;
                                case "3":
                                    $json -> quality = "Moderada";
                                     $json -> qualityClass = "quality-moderate";
                                     $json -> recommendation = "Evite atividades intensas ao ar livre";
                                    break;
                                case "4":
                                    $json -> quality = "Ruim";
                                    $json -> qualityClass = "quality-poor";
                                    $json -> recommendation = "Evite atividades físicas ao ar livre";
                                    break;
                                case "5":
                                     $json -> quality = "Péssima";
                                     $json -> qualityClass = "quality-very-poor";
                                     $json -> recommendation = "Evite sair de casa";
                                    break; 
                                }
                                

                                $cityInfoJson = json_encode($json);
                                return $cityInfoJson;
                        }
                        else {
                            $json -> status = 3;
                            $json -> airError = "Informações sobre a qualidade do ar não foram encontradas";
                            return json_encode($json);
                        }
                    }
                }
                else{
                    $json -> status = 2;
                    $json -> geoError = "Nenhuma cidade encontrada";
                    return json_encode($json);
                }
            }
        }
    }

?>