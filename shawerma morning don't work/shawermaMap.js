ymaps.ready(init)
    var myMap;


function init() {
    myMap = new ymaps.Map("shawermaMapID", {
        center: [60.05257923, 30.32882022],
        zoom: 14,

        // Поведения - реакция карты на действия пользователя
       behaviors: ['dbClickZoom','drag']

        // изменение вида карты на спутник
        // ,type: 'yandex#satellite'
    }, {
        searchControlProvider: 'yandex#search'
    });

    myMap.behaviors.disable('dbClickZoom');
    myMap.behaviors.enable('drag');
    myMap.options.set('dragCursor', 'arrow');

    jQuery.getJSON('shawermaData.json', function (json) {
             // Сохраним ссылку на геообъекты на случай, если понадобится какая-либо постобработка.
             // @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/GeoQueryResult.xml

            var geoObjects = ymaps.geoQuery(json)
                .addToMap(myMap)
                .applyBoundsToMap(myMap, {checkZoomRange: true});
        });
}



// Old project
/*
ymaps.ready(init);

var myMap;

function init(){
    myMap = new ymaps.Map("map", {
        center: [59.92949925, 30.29660251],
        zoom: 16

        // Поведения - реакция карты на действия пользователя
        //,behaviors: ['ruler', 'scrollZoom']

        // изменение вида карты на спутник
        // ,type: 'yandex#satellite'
    });



    var placemarkBM = new ymaps.Placemark([59.92949925, 30.29660251],{iconContent: 'Корпус на БМ'}, {preset: 'islands#blueStretchyIcon'});



    var placemarkLensoveta = new ymaps.Placemark([59.85574104, 30.33037510]);

    var placemarkGastello = new ymaps.Placemark([59.85736406, 30.32777050]);

    var universityCollection = new ymaps.GeoObjectCollection();

    universityCollection.add(placemarkBM);
    universityCollection.add(placemarkLensoveta);
    universityCollection.add(placemarkGastello);

    myMap.geoObjects.add(universityCollection);

    placemarkBM.balloon.close();


    <!-- Запрос местоположения пользователя -->
    ymaps.geolocation.get().then(function (res) {
        var $container = $('YMapsID'),
            bounds = res.geoObjects.get(0).properties.get('boundedBy'),
            mapState = ymaps.util.bounds.getCenterAndZoom(
                bounds,
                [$container.width(), $container.height()]
            ),
            map = new ymaps.Map('YMapsID', mapState);
    }, function (e) {
        console.log(e);
    });

}
 */