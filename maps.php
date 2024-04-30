<!DOCTYPE html>
<html>

<head>
    <title>A8.3 Bing Mapsy OpenWeather</title>
    <meta charset="utf-8">
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
    </style>
</head>

<body>
    <div id="map"></div>

    <script src="js/mapsHelper.js"></script>
   
    <script type='text/javascript'
        src='http://www.bing.com/api/maps/mapcontrol?key="+ YOUR_BING_MAPS_API_KEY + "&callback=initMap' async
        defer></script>
</body>

</html>