<?php

class Weather {
    private $apiKey = '99fc369dc255349a67fd11c10e877ab1';
    private $geoApiUrl = 'http://api.openweathermap.org/geo/1.0/direct';
    private $weatherHourly = 'https://api.openweathermap.org/data/2.5/weather';
    private $weather5days = 'https://api.openweathermap.org/data/2.5/forecast';

    public function getCoordinates($city) {
        $url = "{$this->geoApiUrl}?q={$city}&limit=1&appid={$this->apiKey}";
        return $this->makeRequest($url);
    }

    public function getCurrentWeather($city) {
        $coordinates = $this->getCoordinates($city);
        if (isset($coordinates[0])) {
            $lat = $coordinates[0]['lat'];
            $lon = $coordinates[0]['lon'];
            $url = "{$this->weatherHourly}?lat={$lat}&lon={$lon}&appid={$this->apiKey}";
            return $this->makeRequest($url);
        }
        return null;
    }

    public function getCurrentWeatherByCoords($lat, $lon) {
        $url = "{$this->weatherHourly}?lat={$lat}&lon={$lon}&appid={$this->apiKey}";
        return $this->makeRequest($url);
    }

    public function get5DayForecast($city) {
        $coordinates = $this->getCoordinates($city);
        if (isset($coordinates[0])) {
            $lat = $coordinates[0]['lat'];
            $lon = $coordinates[0]['lon'];
            $url = "{$this->weather5days}?lat={$lat}&lon={$lon}&appid={$this->apiKey}";
            return $this->makeRequest($url);
        }
        return null;
    }

    public function get5DayForecastByCoords($lat, $lon) {
        $url = "{$this->weather5days}?lat={$lat}&lon={$lon}&appid={$this->apiKey}";
        return $this->makeRequest($url);
    }

    private function makeRequest($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

    /*private function helloWorld() {
        $h = 'hello world';
        return json_encode($h);
    }*/
}
?>