<?php

/**
 * The yahoo weather class contain all needed fuction to get current weather from yahoo weather API.
 * @author Khaled Hassan
 * @category weather
 * @link khaled.h.developer@gmail.com
 */
class weather {

    const URL = 'https://weather-ydn-yql.media.yahoo.com/forecastrss';
    const APP_ID = 'TD9aig4e';
    const CONSUMER_KEY = 'dj0yJmk9bGZFRWEzdXlxR090JmQ9WVdrOVZFUTVZV2xuTkdVbWNHbzlNQS0tJnM9Y29uc3VtZXJzZWNyZXQmc3Y9MCZ4PWJl';
    const CONSUMER_SECRET = 'e989814eeb700bcec00f2674f0a82fbe3e786af8';

    public const CELSIUS = 'C';
    public const FAHRENHEIT = 'f';


    private $weatherData = [];

    /**
     * Construct calass and send request to get weather data.
     * @param string $city [optional] <br> The city name like 'cairo' or 'cairo,eg'. <p></p>
     * @param float $latitude [optional] <br> The latitude coordinator. <br> <b>FALSE</b> is default value to ignore coordinator. <p></p>
     * @param float $longitude [optional] <br> The longitude coordinator. <br> <b>FALSE</b> is default value to ignore coordinator. <p></p>
     * @param string $unit [optional] <br> The data unit. <br> <b>c</b> get data by CELSIUS and kilometer unit <b>default value</b>. <br> <b>f</b> get data by Fahrenheit and mile unit. <p></p>
     * @return object <br> The handel to class.
     */
    function __construct($city = '', $latitude = FALSE, $longitude = FALSE, $unit = self::CELSIUS) {
        $param = [];
        $param['format'] = 'json';
        if (strtolower($unit) !== 'c' && strtolower($unit) !== 'f') {
            $unit = 'c';
        }
        $param['u'] = strtolower($unit);
        if (trim($city) !== '') {
            $param['location'] = strtolower($city);
        }
        if ($latitude && $longitude) {
            $param['lat'] = $latitude;
            $param['lon'] = $longitude;
        }

        $oauth = array(
            'oauth_consumer_key' => self::CONSUMER_KEY,
            'oauth_nonce' => uniqid(mt_rand(1, 1000)),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_timestamp' => time(),
            'oauth_version' => '1.0'
        );
        $base_info = $this->buildBaseString(self::URL, array_merge($param, $oauth));
        $composite_key = rawurlencode(self::CONSUMER_SECRET) . '&';

        $oauth_signature = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));
        $oauth['oauth_signature'] = $oauth_signature;

        $header = array($this->buildAuthorizationHeader($oauth), 'X-Yahoo-App-Id: ' . self::APP_ID);

        $options = array(
            CURLOPT_HTTPHEADER => $header,
            CURLOPT_HEADER => false,
            CURLOPT_URL => self::URL . '?' . http_build_query($param),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        );

        $ch = curl_init();
        curl_setopt_array($ch, $options);
        $response = curl_exec($ch);
        curl_close($ch);

        $this->weatherData = json_decode($response, TRUE);
    }

    /**
     * Get the city name.
     * @return string <br> The city name.
     */
    public function getCity() {
        return $this->weatherData['location']['city'];
    }

    /**
     * Get the region or state name.
     * @return string <br> The region or state name.
     */
    public function getRegion() {
        return $this->weatherData['location']['region'];
    }

    /**
     * Get the country name.
     * @return string <br> The country name.
     */
    public function getCountry() {
        return $this->weatherData['location']['country'];
    }

    /**
     * Get the latitude coordinator.
     * @return float <br> The latitude coordinator.
     */
    public function getLatitude() {
        return (float) number_format($this->weatherData['location']['lat'], 6);
    }

    /**
     * Get the longitude coordinator.
     * @return float <br> The longitude coordinator.
     */
    public function getLongitude() {
        return (float) number_format($this->weatherData['location']['long'], 6);
    }

    /**
     * Get the area timezone.
     * @return string <br> The area timezone.
     */
    public function getTimezone() {
        return $this->weatherData['location']['timezone_id'];
    }

    /**
     * Get the wind speed <br> <b>Kilometer Per Hour</b> when unit is <b>c</b> <br> <b>Mile Per Hour</b> when unit is <b>f</b>..
     * @return float <br> The wind speed.
     */
    public function getWindSpeed() {
        return (float) number_format($this->weatherData['current_observation']['wind']['speed'], 3);
    }

    /**
     * Get the humidity percentage.
     * @return int <br> The humidity percentage.
     */
    public function getHumidity() {
        return (int) $this->weatherData['current_observation']['atmosphere']['humidity'];
    }

    /**
     * Get the visibility meter.
     * @return float <br> The visibility meter.
     */
    public function getVisibility() {
        return (float) number_format($this->weatherData['current_observation']['atmosphere']['visibility'], 2);
    }

    /**
     * Get the pressure <br> <b>Millibar</b> when unit is <b>c</b> <br> <b>Inch Hg</b> when unit is <b>f</b>.
     * @return float <br> The city name.
     */
    public function getPressure() {
        return (float) number_format($this->weatherData['current_observation']['atmosphere']['pressure'], 2);
    }

    /**
     * Get the sunrise time.
     * @return string <br> The sunrise time.
     */
    public function getSunriseTime() {
        return $this->weatherData['current_observation']['astronomy']['sunrise'];
    }

    /**
     * Get the sunset time.
     * @return string <br> The sunset time.
     */
    public function getSunsetTime() {
        return $this->weatherData['current_observation']['astronomy']['sunset'];
    }

    /**
     * Get the weather status string.
     * @return string <br> The weather status string.
     */
    public function getStatus() {
        return $this->weatherData['forecasts']['0']['text'];
    }

    /**
     * Get the current temperature <br> <b>Celsius</b> when unit is <b>c</b> <br> <b>Fahrenheit</b> when unit is <b>f</b>.
     * @return int <br> The the temperature.
     */
    public function getTemperature() {
        return (int) $this->weatherData['current_observation']['condition']['temperature'];
    }

    /**
     * Get the low temperature <br> <b>Celsius</b> when unit is <b>c</b> <br> <b>Fahrenheit</b> when unit is <b>f</b>.
     * @return int <br> The the low temperature.
     */
    public function getLowTemperature() {
        return (int) $this->weatherData['forecasts'][0]['low'];
    }

    /**
     * Get the high temperature <br> <b>Celsius</b> when unit is <b>c</b> <br> <b>Fahrenheit</b> when unit is <b>f</b>.
     * @return int <br> The the high temperature.
     */
    public function getHighTemperature() {
        return (int) $this->weatherData['forecasts'][0]['high'];
    }

    /**
     * Get the current day make weather check.
     * @return date <br> The current day.
     */
    public function getCheckedDay() {
        return gmdate("Y-m-d", (int) $this->weatherData['forecasts'][0]['date']);
    }

    /**
     * Get the next days temperature up to 7 day after.
     * @param int $dayNumber <br> The number of days after today to get it's data. <p></p>
     * @return array <br> The day data in array like <br> ['date' => '2019-7-15', 'status' => 'cloudy', 'low' => 28, 'high' => 39].
     */
    public function getNextDayData($dayNumber) {
        if ((int) $dayNumber < 1) {
            $dayNumber = 1;
        }
        if ((int) $dayNumber > 7) {
            $dayNumber = 7;
        }
        $status = $this->weatherData['forecasts'][(int) $dayNumber]['text'];
        $low = (int) $this->weatherData['forecasts'][(int) $dayNumber]['low'];
        $high = (int) $this->weatherData['forecasts'][(int) $dayNumber]['high'];
        $date = gmdate("Y-m-d", (int) $this->weatherData['forecasts'][(int) $dayNumber]['date']);

        $temp = ['date' => $date, 'status' => $status, 'low' => $low, 'high' => $high];

        return $temp;
    }

    /**
     * Build the base URL string.
     * @param string $baseURI <br> The URL string. <p></p>
     * @param array $params <br> The paramerters array. <p></p>
     * @return string <b> The incoding URL.
     */
    private function buildBaseString($baseURI, $params) {
        $temp = array();
        ksort($params);
        foreach ($params as $key => $value) {
            $temp[] = "$key=" . rawurlencode($value);
        }
        return "GET&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $temp));
    }

    /**
     * Build the authorization request header.
     * @param array $oauth <br> The authorization array. <p></p>
     * @return string <b> The incoding authorization request.
     */
    private function buildAuthorizationHeader($oauth) {
        $temp = 'Authorization: OAuth ';
        $values = array();
        foreach ($oauth as $key => $value) {
            $values[] = "$key=\"" . rawurlencode($value) . "\"";
        }
        $temp .= implode(', ', $values);
        return $temp;
    }

}
