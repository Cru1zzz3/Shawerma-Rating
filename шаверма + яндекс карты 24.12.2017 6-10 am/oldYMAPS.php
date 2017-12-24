<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Яндекс.Карта на Вашем сайте с возможностью добавления меток пользователями. Новая версия</title>

    <script src="http://api-maps.yandex.ru/1.1/index.xml?key=ACuF2bkBAAAAzahYCgIASggsFm9n8EPvNjaTc8nAWiETKgYcAAAAAAAAAAAC-q61vWtIK3Kzt2yQ9qFaGJGKzXw==" type="text/javascript"></script>

    <script type="text/javascript">

        var map;

        window.onload = function () {
            map = new YMaps.Map(document.getElementById("YMapsID"));
            map.setCenter(new YMaps.GeoPoint(43.99150,56.31534), 12);

            map.addControl(new YMaps.TypeControl());
            map.addControl(new YMaps.ToolBar());
            map.addControl(new YMaps.Zoom());
            map.addControl(new YMaps.ScaleLine());
            map.enableScrollZoom();

//Запрос данных и вывод маркеров на карту
            YMaps.jQuery.getJSON("vivodpointsmap.php",
                function(json){
                    for (i = 0; i < json.markers.length; i++) {
                        var placemark=new YMaps.Placemark(new YMaps.GeoPoint(json.markers[i].lat,json.markers[i].lng), {style: "default#redSmallPoint"});
                        placemark.description= '<div style="color:#ff0303;font-weight:bold">'+json.markers[i].name+'</div>';
                        placemark.description = placemark.description+'<strong>Описание:</strong> '+json.markers[i].descriptions;
                        map.addOverlay(placemark);
                    }

                });

            var myLayout = function (geoPoint) {
                var $element = YMaps.jQuery('<div>Название: <input type="text" id="name"/><br />Описание: <textarea id="descriptpoint" cols="20" rows="5"></textarea><br /><input type="button" value="Добавить" id="submit"/></div>');
                this.onAddToParent = function (parent) {
                    $element.find('#submit').bind('click', function () {
                        YMaps.jQuery.ajax({
                            url: 'addpoint.php',
                            data: {
                                namepoint: $element.find('#name')[0].value,
                                descriptpoint: $element.find('#descriptpoint')[0].value,
                                pcoord: geoPoint.toString()
                            },

                            dataType: 'json',
                            // Это функция обработки ответа сервера
                            success: function (res) {
                                if (res.success) {
                                    // если точка сохранилась, закрываем балун
                                    map.closeBalloon();
                                    // и ставим точку на карту
                                    map.addOverlay(new YMaps.Placemark(geoPoint));

                                } else {
                                    // иначе выдаем сообщение об ошибке
                                    // YMaps.jQuery('<p style="color:red">' + e.message + '</p>').appendTo("#scriptmes");
                                    YMaps.jQuery("#scriptmes").html('<p style="color:red">' + e.message + '</p>');
                                }
                            }
                        });

                    });
                    $element.appendTo(parent);
                };
                this.onRemoveFromParent = function () {
                    $element.remove();
                };

                this.update = function () {};
            }

            YMaps.Events.observe(map, map.Events.Click, function (map, e) {
                map.openBalloon(e.getCoordPoint(), new myLayout(e.getCoordPoint()));
            });

        }
    </script>

</head>

<body>

<div id="YMapsID" style="width:800px;height:600px"></div>
<div id="scriptmes"></div>

</body>
</html>