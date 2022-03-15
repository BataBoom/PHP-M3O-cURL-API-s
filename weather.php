<?php
//include requirements, keys, etc
require 'includes/config/config.php';
use Curl\Curl;
function WeatherForecast($location) {
global $weather;

$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $weather);
$curl->setHeader('Content-Type', 'application/json');
$curl->get('https://api.m3o.com/v1/weather/Forecast', [
    'days' => 0,
    'location' => $location
    
]);
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
    $fetchResponse = $curl->response;
    $jsonEncoded = json_encode($fetchResponse);
    $grabWeather = json_decode($jsonEncoded, true);
    $grabForecast = $grabWeather['forecast'];
    $loc = $grabWeather['location'];
    $country = $grabWeather['country'];
    $tz = $grabWeather['timezone'];
    $weatherDate = array_column($grabForecast, 'date');
    $maxTemp = array_column($grabForecast, 'max_temp_f');
    $minTemp = array_column($grabForecast, 'min_temp_f');
    $chanceRain = array_column($grabForecast, 'chance_of_rain');
    $rain = array_column($grabForecast, 'will_it_rain');
    $icon = array_column($grabForecast, 'icon_url');
    $condition = array_column($grabForecast, 'condition');
    $maxWind = array_column($grabForecast, 'max_wind_mph');
    $sunset = array_column($grabForecast, 'sunset');
    $returnWeather = array($loc, $country, $tz, $weatherDate[0], $maxTemp[0], $minTemp[0], $chanceRain[0], $rain[0], $icon[0], $condition[0], $maxWind[0],$sunset[0]);
    return $returnWeather;
    }
$curl->close();
}

//WeatherForecast("Los Angeles");


function Weather($location) {
global $weather;

$curl = new Curl();
$curl->setBasicAuthentication('Authorization', $weather);
$curl->setHeader('Content-Type', 'application/json');
$curl->get('https://api.m3o.com/v1/weather/Now', [
    'days' => 0,
    'location' => $location
    
]);
if ($curl->error) {
    echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";
} else {
    $fetchResponse = $curl->response;
    $jsonEncoded = json_encode($fetchResponse);
    $grabWeatherNow = json_decode($jsonEncoded, true);
    $localTime = $grabWeatherNow['local_time'];
    $clouds = $grabWeatherNow['cloud'];
    $condition = $grabWeatherNow['condition'];
    $isDay = $grabWeatherNow['daytime'];
    $feelsLike = $grabWeatherNow['feels_like_f'];
    $humidity = $grabWeatherNow['humidity'];
    $windDir = $grabWeatherNow['wind_direction'];
    $windDegree = $grabWeatherNow['wind_degree'];
    $wind = $grabWeatherNow['wind_mph'];
    $icon = $grabWeatherNow['icon_url'];
    $temp = $grabWeatherNow['temp_f'];
    $returnWeather = array($localTime, $clouds, $condition, $isDay, $temp, $feelsLike, $humidity, $windDir, $windDegree, $wind, $icon);
    return $returnWeather;
    }
$curl->close();
}

//Weather("Los Angeles");
