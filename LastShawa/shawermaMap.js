ymaps.ready(init);
var myPoint;

function init() {
    var myMap = new ymaps.Map("map", {
        center: [60.04873258, 30.32558726],
        zoom: 15,
        behaviors: ['dbClickZoom', 'drag']
    }, {
        searchControlProvider: 'yandex#search'
    });

    myMap.behaviors.disable('dbClickZoom');
    myMap.behaviors.enable('drag');
    myMap.options.set('dragCursor', 'arrow');


    var objectManager = new ymaps.ObjectManager({
        clusterize: true,
        gridSize: 10,
        clusterDisableClickZoom: true
    });

    objectManager.objects.options.set('preset', 'twirl#greenStretchyIcon');
    objectManager.clusters.options.set('preset', 'islands#greenClusterIcons');
    myMap.geoObjects.add(objectManager);


    myMap.events.add('click', function (e) {
        var coords = e.get('coords');


        if (myPoint) {
            myPoint.geometry.setCoordinates(coords);
        }

        else {
            myPoint = createPoint(coords);
            myMap.geoObjects.add(myPoint);
            myPoint.events.add('dragend', function () {
                getAddress(myPoint.geometry.getCoordinates());

            });

        }

        getAddress(coords);

    });

    function createPoint(coords) {
        return new ymaps.Placemark(coords, {
            iconCaption: 'поиск...'
        }, {
            preset: 'islands#violetDotIconWithCaption',
            draggable: true
        })
    }

    function getAddress(coords) {
        myPoint.properties.set('iconCaption', 'поиск...');
        ymaps.geocode(coords).then(function (res) {
            var firstGeoObject = res.geoObjects.get(0);
            var pointAddress = firstGeoObject.getAddressLine();
            var long = coords;


            myPoint.properties
                .set({
                    iconCaption: firstGeoObject.getAddressLine(),


                    balloonContentBody: "<p>Адрес точки: " + pointAddress + "</p> " +
                    "<form action=" + "addPoint.php?coords=" + coords + "&>"
                    + "<p>Введите название точки c шавермой: <input type='text' placeholder='Название точки' name='pointName' required></p>"
                    + "<p><input  type='hidden' name='coordinates' value='" + coords + "' placeholder='Координаты' ></p>"

                    + "<p><button value='submit'>Добавить новую точку с шавермой</button></p></form>  "

                })
        })

    }
        /*
        function updateMap() {
          $.getJSON('shawermaNewData.json', function (json) {
                var objectManager = ymaps.geoQuery(json)
                    .addToMap(myMap, {checkZoomRange: true});
            });
        }
        setInterval(updateMap, 1000);
        */


            //function updateMap() {
                $.ajax({
                    url: "shawermaNewData.json",
                    success: function (data) {
                        objectManager.add(data);
                    }
                })
           // }
          //  setInterval(updateMap, 1000);


}
