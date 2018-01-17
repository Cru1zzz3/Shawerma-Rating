<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />


 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.4/angular.min.js"></script>
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript"></script>

<script async src="shawermaMap.js"></script>

</head>
<body>
<?php
 // include 'authorize.php';
?>

<style type="text/css">
  type="text/css">
  html {height:100%}
  body {height:100%; width:100%; margin:0px;padding:0px}
  map {height:100% ; width: 100%}

  .rating {
      unicode-bidi: bidi-override;
      direction: rtl;
      text-align: center;
  }
  .rating > span {
      display: inline-block;
      position: relative;
      width: 1.1em;
  }
  .rating > span:hover,
  .rating > span:hover ~ span {
      color: transparent;
  }
  .rating > span:hover:before,
  .rating > span:hover ~ span:before {
      content: "\2605";
      position: absolute;
      left: 0;
      color: gold;
  }

</style>

<div class="pageGrid">
	<div class="Hat">
  		<div class="col-md-12" >
  			<!-- Navigation bar -->
			<nav class="navbar navbar-inverse">
			  <div class="container-fluid">
			    
			    <div class="navbar-header">
			      <div class="navbar-brand"> <img src="shawerma.png" height="20px" width="60px"></div>
			      <a class="navbar-brand" href="index.php">  Карта шаверм  </a>
			    </div>

			    <ul class="nav navbar-nav">
			    	<li><a href="index.php"> Привет, <?php echo 'Станислав'// print $user_record["login"]; ?></a></li>
			    
			      <!-- <li><a href="#">Link</a></li> -->
			    </ul>
			    <form action="logout.php">
			    <div>
			    <button  class="btn btn-danger navbar-btn"> Выход  <span class="glyphicon glyphicon-log-out"></span></button>
			  	
			  	</div>
			  	</form>
			  </div>
			  </nav>
			  
		   </div>
	</div>

    <script>
        var shawermaApp = angular.module("shawermaApp", []);

        shawermaApp.controller('shawermaCtrl', function($scope, $http) {
            $http.get("shawermaNewData.json").then(function (res) {
                $scope.points = res.data.features;
            });

            $scope.deletePoint = function(x){
                $scope.points.splice(x,1);
            }
        });
    </script>

	<div class="Content">
	  	<div class="col-md-4">
            <div ng-app="shawermaApp" ng-controller="shawermaCtrl">
                <table  class="table table-striped" >
                    <tbody id="tableBody">
                    <tr>
                        <th> Название точки c шавермой: </th>
                    </tr>
                    <tr  ng-repeat="point in points">
                        <td>
                            <div> {{point.properties.balloonContentHeader}} </div>
                        </td>
                        <td>
                            <div>
                                <span ng-click="deletePoint($index)" class="glyphicon glyphicon-remove"></span>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

            <div id="map" class="col-md-8" style="width: 100;height: 800px;"></div>

        <div class="Footer">
                <div class="col-md-12" style="background-color:red">col-md-12</div>
        </div>
    </body>
</html>
