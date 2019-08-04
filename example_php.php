<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        include_once './source/y_weather.class.php';
        $wather = new weather('cairo,eg');
        
        $temp = 'Date: ' . $wather->getCheckedDay() . '<br>City: ' . $wather->getCity() . '<br>Area: ';
        $temp .= $wather->getRegion() . '<br>Countery: ' . $wather->getCountry() . '<br>Timezone: ';
        $temp .= $wather->getTimezone() . '<br>Latitude: ' . $wather->getLatitude() . '<br>Longitude';
        $temp .= $wather->getLongitude() . '<br>Wind Speed: ' . $wather->getWindSpeed() . ' Km/hour<br>Humidity: ';
        $temp .= $wather->getHumidity() . '%<br>Pressure: ' . $wather->getPressure() . ' Millibar<br>Sunrise Time: ';
        $temp .= $wather->getSunriseTime() . '<br>Sunset Time: ' . $wather->getSunsetTime() . '<br>Status: ';
        $temp .= $wather->getStatus() . '<br>Temperature: ' . $wather->getTemperature() . ' C<br>Low Temperature: ';
        $temp .= $wather->getLowTemperature() . ' C<br>High Temperature: ' . $wather->getHighTemperature() . ' C<b>';
        $tomorrow = $wather->getNextDayData(1);
        $temp .= '<div style="padding-left:25px"><h4 style="text-align:center">Tomorrow</h4><br>Date: ';
        $temp .= $tomorrow['date'] . '<br>Status: ' . $tomorrow['status'] . '<br>Low  Temperature: ';
        $temp .= $tomorrow['low'] . ' C<br>High  Temperature: ' . $tomorrow['high'] . ' C</div>';
        ?>
        <div id="show" style="padding-left:10px"><?php echo $temp; ?></div>
    </body>
</html>
