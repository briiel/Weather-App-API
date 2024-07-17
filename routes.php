<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, X-Auth-Token, Origin, Authorization');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
        header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    
    exit(0);
}

if (isset($_REQUEST['request'])) {
    $request = explode('/', $_REQUEST['request']);
} else {
    echo json_encode(["error" => "Not Found"]);
    http_response_code(404);
    exit();
}

require_once 'forecast/Weather.php';
$weather = new Weather();

switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        $data = json_decode(file_get_contents("php://input"));
        switch ($request[0]) {
            case 'current-weather':
                if (isset($request[1])) {
                    $city = $request[1];
                    $result = $weather->getCurrentWeather($city);
                    if ($result) {
                        echo json_encode($result);
                    } else {
                        echo json_encode(["error" => "City not found"]);
                        http_response_code(404);
                    }
                } else {
                    echo json_encode(["error" => "City not specified"]);
                    http_response_code(400);
                }
                break;

            case '5-day-forecast':
                if (isset($request[1])) {
                    $city = $request[1];
                    $result = $weather->get5DayForecast($city);
                    if ($result) {
                        echo json_encode($result);
                    } else {
                        echo json_encode(["error" => "City not found"]);
                        http_response_code(404);
                    }
                } else {
                    echo json_encode(["error" => "City not specified"]);
                    http_response_code(400);
                }
                break;

            case 'current-weather-coords':
                $url = $_SERVER['REQUEST_URI'];
                $parts = parse_url($url);
                parse_str($parts['query'], $query);
                if (isset($query['lat']) && isset($query['lon'])) {
                    $lat = $query['lat'];
                    $lon = $query['lon'];
                       $result = $weather->getCurrentWeatherByCoords($lat, $lon);
                    if ($result) {
                        echo json_encode($result);
                    } else {
                           echo json_encode(["error" => "Coordinates not found"]);
                           http_response_code(404);
                    }
                } else {
                     echo json_encode(["error" => "Coordinates not specified"]);
                     http_response_code(400);
                }
                break;

                case '5-day-forecast-coords':
                    $url = $_SERVER['REQUEST_URI'];
                    $parts = parse_url($url);
                    parse_str($parts['query'], $query);
                    if (isset($query['lat']) && isset($query['lon'])) {
                        $lat = $query['lat'];
                        $lon = $query['lon'];
                        $result = $weather->get5DayForecastByCoords($lat, $lon);
                        if ($result) {
                            echo json_encode($result);
                        } else {
                            echo json_encode(["error" => "Coordinates not found"]);
                            http_response_code(404);
                        }
                    } else {
                        echo json_encode(["error" => "Coordinates not specified"]);
                        http_response_code(400);
                    }
                    break;
            /*case 'hello-world':
                echo json_encode(["message" => "Hello World"]);
                break;*/

            default:
                echo json_encode(["error" => "Endpoint not available"]);
                http_response_code(404);
                break;
        }
        break;

    default:
        echo json_encode(["error" => "Method not available"]);
        http_response_code(405);
        break;
}

?>