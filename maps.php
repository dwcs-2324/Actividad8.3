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

    <script type="text/javascript">
        //Añade aquí tus API KEYs
        //Bing maps key        
        const YOUR_BING_MAPS_API_KEY = '';
        //OpenWeather key
        const YOUR_WEATHER_API_KEY = '';
        // El valor 156543.03392 es una constante utilizada en muchos sistemas cartográficos para convertir la escala en nivel de zoom y viceversa.
        // Representa el número de metros por píxel en el nivel de zoom 0 para un mapa con un tamaño de mosaico de 256x256 píxeles. Esta constante se deriva de la fórmula:

        // scale=(156543.03392 *meters/pixel)/(2^(zoom level))
        // donde:
        // scale es la escala del mapa (en metros por píxel).
        // zoom level es el nivel de zoom del mapa.
        
        const RELACION_ESCALA_ZOOM = 156543.03392




        //IES de Teis Vigo ubicación
        let latitude = 42.251543700640795;
        let longitude = -8.69009235767205

        const RADIO = 10; //distancia en km que quiero mostrar al menos alrededor de la ubicación
        const EXPAND_FACTOR = 8; //constante experimental para que el mapa muestre toda la región de interés

        //https://learn.microsoft.com/en-us/bingmaps/v8-web-control/


        //Crear un mapa básico
        //https://learn.microsoft.com/en-us/bingmaps/v8-web-control/creating-and-hosting-map-controls/creating-a-basic-map-control
        function initMap() {
            var map = new Microsoft.Maps.Map(document.getElementById('map'), {
                credentials: YOUR_BING_MAPS_API_KEY
            });


            //conseguimos la información OpenWeather de la ubicación 
            getWeather(latitude, longitude, map);
            var center = new Microsoft.Maps.Location(latitude, longitude);

            //obtenemos las dimensiones del mapa para obtener un nivel óptimo de zoom
            var mapElement = document.getElementById('map');
            var mapWidth = mapElement.clientWidth;
            var mapHeight = mapElement.clientHeight;

            let customZoom = calculateZoomLevel(mapWidth, mapHeight);

            //Fijamos el punto central del mapa en la ubicación original
            map.setView({ center: center, zoom: customZoom });
        }
        function getWeather(latitude, longitude, map) {

            var location = new Microsoft.Maps.Location(latitude, longitude);

            let url = "http://api.openweathermap.org/data/2.5/weather?lat=" + latitude + "&lon=" + longitude + "&appid=" + YOUR_WEATHER_API_KEY;
            fetch(url)
                .then(response => response.json())
                .then(data => {

                    if (data.weather && data.weather[0] && data.weather[0]['icon']) {
                        //obtenemos el icono del tiempo actual en esa ubicación
                        let iconCode = data['weather'][0]['icon'];
                        if (iconCode) {
                            iconUrl = "http://openweathermap.org/img/wn/" + iconCode + ".png";
                            //creamos un marcador (Pushpin) en la ubicación con un icono de OpenWeather
                            var pin = new Microsoft.Maps.Pushpin(location, {
                                icon: iconUrl
                            });
                            //Añadimos un tooltip (infobox) con texto genérico
                            //https://learn.microsoft.com/en-us/bingmaps/v8-web-control/map-control-api/infoboxoptions-object
                            var infobox = new Microsoft.Maps.Infobox(location, {
                                title: 'El tiempo',
                                description: 'weatherDescription',
                                visible: false
                            });

                            //Añadimos un manejador de eventos click al pin para que muestre el objeto infobox
                            Microsoft.Maps.Events.addHandler(pin, 'click', function (e) {
                                infobox.setOptions({
                                    location: e.target.getLocation(),
                                    visible: true
                                });
                            });

                            //Añadimos al mapa el pin y el objeto infobox
                            map.entities.push(pin);
                            map.entities.push(infobox);



                        }
                        else {
                            console.log("No available data found in OpenWeather");
                        }

                    }

                })
        }




        function calculateZoomLevel(widthInPixels, heightInPixels) {


            // Convert  kilometers to meters
            var distanceMeters = RADIO * 1000 * 8; //valor 8 experimental para establecer el zoom

            // Calculate the scale of the map based on the smaller dimension
            var scale = Math.min(
                distanceMeters / widthInPixels,
                distanceMeters / heightInPixels
            );

            // Calculate the zoom level based on the scale
            var zoomLevel = Math.log2(RELACION_ESCALA_ZOOM / scale);

            // Round the zoom level to the nearest integer
            return Math.round(zoomLevel);
        }




    </script>

    <script type='text/javascript'
        src='http://www.bing.com/api/maps/mapcontrol?key="+ YOUR_BING_MAPS_API_KEY + "&callback=initMap' async
        defer></script>
</body>

</html>