var myApp = angular.module('myAppMain', ['ngRoute']);


myApp.config(['$routeProvider',function($routeProvider){
    $routeProvider
        .when('/', {
            templateUrl : 'pages/homepagewelcome.html',
            controller : 'AppCtrl'
        })
        .when('/yourorders', {
            templateUrl : 'pages/yourorders.html',
            controller : 'AppCtrlOrders'
        })
        .when('/login', {
            templateUrl : 'pages/login.html',
            controller : 'AppCtrlLogin'
        });

}]);

myApp.controller('AppCtrl', ['$scope', '$log', '$http', '$window', '$location', function ($scope, $log, $http, $window, $location) {
    $scope.id = localStorage.getItem("uid");
    $scope.selectedstore = "Store";
    $scope.stores = ["AMAZON", "FLIPKART", "EBAY", "SNAPDEAL", "MYNTR","PAYTM","SHOPCLUES"];
    $scope.items = [];
    console.log($scope.id+" -----");
    $scope.user = null;
    $http.post('../api/getUserDetails.php', {uid:$scope.id}).then(function (success) {

        if (success.data.status == 200) {
            $scope.name = success.data.data.UNAME;
            $scope.user = success.data.data;
            console.log($scope.user);
            var colObj = {collage:$scope.user.COLLAGE};
            $http.post('../api/getGroupSuggestions.php',colObj).then(function (success) {
                console.log(success.data.status+"##@@#" + success.data.status_message);

                if (success.data.status == 200) {
                    console.log("got it");

                }else{

                }

            }, function (error) {
                console.log("Error in making new Account!");
            });
        }else{

        }

    }, function (error) {
        console.log("Error getting user detail!");
    });


    //get collage groups


    $scope.assignStore = function (item) {
        $scope.selectedstore = item;
    }
    var tprice = 0;
    $scope.addItem = function(item){
        tprice = tprice + $scope.itemprice;
        var itemObj = {
            name:$scope.itemname,
            price:$scope.itemprice,
            url:$scope.itemurl,
            store:$scope.selectedstore
        }
        $scope.items.unshift(itemObj);
    }

    $scope.newOrder = function(){

        var neworderObj = {
            buyer: $scope.user,
            itemarray: $scope.items,
            totalprice: tprice,
            store:$scope.selectedstore,
            people:1,
            userarray:null
        };
        console.log(neworderObj);
        $scope.items= [];
        tprice = 0;
        $scope.selectedstore = "store";
        $http.post('../api/createGroup.php', neworderObj).then(function (success) {

            $scope.items.length = 0;
            tprice = 0;
            $scope.selectedstore = "store";

        }, function (error) {

        });

    }
}]);

myApp.controller('AppCtrlOrders', ['$scope', '$log', '$http', '$window', '$location', function ($scope, $log, $http, $window, $location) {


}]);
