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
        <script type="text/javascript" src="source/y_weather.js"></script>
    </head>
    <body>
        <button onclick="weather.load('', 30.014300199999997, 31.280144299999996, 'c', 'showWeatherData')">Get weather</button>
        <div id="show"></div>
    </body>
</html>
<script>
    function showWeatherData(request){
        var tomorrow = weather.getNextDayData(1);
        var temp = 'Date: ' + weather.getCheckedDay() + '<br>City: ' + weather.getCity() + '<br>Area: ';
        temp += weather.getRegion() + '<br>Countery: ' + weather.getCountry() + '<br>Timezone: ';
        temp += weather.getTimezone() + '<br>Latitude: ' + weather.getLatitude() + '<br>Longitude';
        temp += weather.getLongitude() + '<br>Wind Speed: ' + weather.getWindSpeed() + ' Km/hour<br>Humidity: ';
        temp += weather.getHumidity() + '%<br>Pressure: ' + weather.getPressure() + ' Millibar<br>Sunrise Time: ';
        temp += weather.getSunriseTime() + '<br>Sunset Time: ' + weather.getSunsetTime() + '<br>Status: ';
        temp += weather.getStatus() + '<br>Temperature: ' + weather.getTemperature() + ' C<br>Low Temperature: ';
        temp += weather.getLowTemperature() + ' C<br>High Temperature: ' + weather.getHighTemperature() + ' C';
        temp += '<div style="padding-left:25px"><h4 style="text-align:center">Tomorrow</h4><br>Date: ';
        temp += tomorrow['date'] + '<br>Status: ' + tomorrow['status'] + '<br>Low  Temperature: ';
        temp += tomorrow['low'] + ' C<br>High  Temperature: ' + tomorrow['high'] + ' C</div>';
        
        document.getElementById('show').innerHTML = temp;
    }
</script>