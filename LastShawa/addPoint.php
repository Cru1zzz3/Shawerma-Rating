<?php

$pointsAmount = 0;

if((!empty($_GET["pointName"]))){
    $pointName = $_GET["pointName"];
}

if((isset($_GET["coordinates"]))){
    $cords = explode(",", htmlspecialchars_decode($_GET['coordinates']));
}

echo ('longitude is: '.$cords[0].'<br/>');
echo ('altitude is: '.$cords[1].'<br/>');
echo ('pointName is: '.$pointName.'<br/>');

$longCord = floatval($cords[0]);
echo ('longitude as value is: '.$longCord.'<br/>');

$latCord =  floatval($cords[1]);
echo ('latitude as value is: '.$latCord.'<br/>');


function json_encode_cyr($str) {
    $arr_replace_utf = array('\u0410', '\u0430','\u0411','\u0431','\u0412','\u0432',
        '\u0413','\u0433','\u0414','\u0434','\u0415','\u0435','\u0401','\u0451','\u0416',
        '\u0436','\u0417','\u0437','\u0418','\u0438','\u0419','\u0439','\u041a','\u043a',
        '\u041b','\u043b','\u041c','\u043c','\u041d','\u043d','\u041e','\u043e','\u041f',
        '\u043f','\u0420','\u0440','\u0421','\u0441','\u0422','\u0442','\u0423','\u0443',
        '\u0424','\u0444','\u0425','\u0445','\u0426','\u0446','\u0427','\u0447','\u0428',
        '\u0448','\u0429','\u0449','\u042a','\u044a','\u042b','\u044b','\u042c','\u044c',
        '\u042d','\u044d','\u042e','\u044e','\u042f','\u044f','\u2606');
    $arr_replace_cyr = array('А', 'а', 'Б', 'б', 'В', 'в', 'Г', 'г', 'Д', 'д', 'Е', 'е',
        'Ё', 'ё', 'Ж','ж','З','з','И','и','Й','й','К','к','Л','л','М','м','Н','н','О','о',
        'П','п','Р','р','С','с','Т','т','У','у','Ф','ф','Х','х','Ц','ц','Ч','ч','Ш','ш',
        'Щ','щ','Ъ','ъ','Ы','ы','Ь','ь','Э','э','Ю','ю','Я','я','☆');
    $str1 = json_encode($str);
    $str2 = str_replace($arr_replace_utf,$arr_replace_cyr,$str1);
    return $str2;
}

$pointsDB = json_decode(file_get_contents("shawermaNewData.json"),true);

if(isset($pointsDB)){
    foreach ($pointsDB['features'] as $record){

              $pointsAmount++;
        }
    }

if (isset($pointsDB['type'])){

    $pointsDB['features'][$pointsAmount]['type'] = 'feature';
    $pointsDB['features'][$pointsAmount]['id'] = $pointsAmount;
   // $pointsDB['features'][$pointsAmount]['rating'] = 0;
    $pointsDB['features'][$pointsAmount]['geometry']['type'] = 'Point';
    $pointsDB['features'][$pointsAmount]['geometry']['coordinates'] = [$longCord,$latCord] ;
    $pointsDB['features'][$pointsAmount]['properties']['balloonContentHeader'] = $pointName ;
    $pointsDB['features'][$pointsAmount]['properties']['balloonContentBody'] = "<div class='rating'><span>☆</span><span>☆</span><span>☆</span><span>☆</span><span>☆</span></div>";
    $pointsDB['features'][$pointsAmount]['properties']['balloonContentFooter'] = "<form action='deletePoint.php'><input  type='hidden' name='id' value='$pointsAmount'><button value='submit'>Удалить выбранную точку?</button></form>";
    $pointsDB['features'][$pointsAmount]['properties']['clusterCaption'] = $pointName ;
    $pointsDB['features'][$pointsAmount]['properties']['hintContent'] = $pointName;

}

else {

    $pointsDB['type'] = 'FeatureCollection';
    $pointsDB['features'][$pointsAmount]['type'] = 'feature';
    $pointsDB['features'][$pointsAmount]['id'] = 0;
   // $pointsDB['features'][$pointsAmount]['rating'] = 0;
    $pointsDB['features'][$pointsAmount]['geometry']['type'] = 'Point';
    $pointsDB['features'][$pointsAmount]['geometry']['coordinates'] = [$longCord,$latCord];
    $pointsDB['features'][$pointsAmount]['properties']['balloonContentHeader'] = $pointName ;
    $pointsDB['features'][$pointsAmount]['properties']['balloonContentBody']= "<div class='rating'><span>☆</span><span>☆</span><span>☆</span><span>☆</span><span>☆</span></div>";
    $pointsDB['features'][$pointsAmount]['properties']['balloonContentFooter']= "<button value='submit'>Выбранную точку невозможно удалить</button>";
    $pointsDB['features'][$pointsAmount]['properties']['clusterCaption']= $pointName;
    $pointsDB['features'][$pointsAmount]['properties']['hintContent']= $pointName;

}
json_encode(file_put_contents("shawermaNewData.json", json_encode_cyr($pointsDB)));
header('Location: index.php');