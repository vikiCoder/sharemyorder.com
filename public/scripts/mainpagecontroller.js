var myApp = angular.module('myAppMain', ['ngRoute']);


myApp.config(['$routeProvider',function($routeProvider){
    $routeProvider
        .when('/', {
            templateUrl : 'pages/homepagewelcome.html',
            controller : 'AppCtrl'
        })
        .when('/signup', {
            templateUrl : 'pages/signup.html',
            controller : 'AppCtrlSignup'
        })
        .when('/login', {
            templateUrl : 'pages/login.html',
            controller : 'AppCtrlLogin'
        });

}]);

myApp.controller('AppCtrl', ['$scope', '$log', '$http', '$window', '$location', function ($scope, $log, $http, $window, $location) {
    $scope.id = getCookie('UID');
    console.log($scope.id+" -----");
    $http.post('../api/getUserDetails.php', {uid:$scope.id}).then(function (success) {

        if (success.data.status == 200) {
            $scope.name = success.data.data.UNAME;
        }else{

        }

    }, function (error) {
        console.log("Error in making new Account!");
    });
}]);

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
