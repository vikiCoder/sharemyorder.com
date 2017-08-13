var myApp = angular.module('myApp', ['ngRoute']);


myApp.config(['$routeProvider', function ($routeProvider) {
    $routeProvider
        .when('/', {
            templateUrl: 'pages/welcome.html'
        })
        .when('/signup', {
            templateUrl: 'pages/signup.html',
            controller: 'AppCtrlSignup'
        })
        .when('/login', {
            templateUrl: 'pages/login.html',
            controller: 'AppCtrlLogin'
        });

}]);

myApp.controller('AppCtrlSignup', ['$scope', '$log', '$http', '$window', '$location', function ($scope, $log, $http, $window, $location) {
    $scope.selectedcollage = "collage";
    $scope.collages = ["DAIICT", "PDPU", "NIRMA", "IITB", "IITGN"];
    console.log($scope.collages);
    $scope.signup = function () {


        var userAuthdata = {
            uname:$scope.unamesu,
            fname: $scope.fnamesu,
            lname: $scope.snamesu,

            email: $scope.emailidsu,
            password: $scope.passwsu,
            mobile: $scope.mobilenosu,
            pincode: $scope.pincodesu,
            collage: $scope.selectedcollage
        }


        //console.log("Coming to Signup controller with " + userAuthdata.firstname);


// $http.get('http://localhost:8888/followed/'+item).then(function (success){
//     console.log(success.data);
//
//     $scope.followedl = success.data.followed;
//     $scope.onlinel = success.data.online;
//     console.log($scope.onlinel);
//   //   $scope.contactl = success.data;
//     //console.log("SUCCESS   HURRRRAA"+success.data);
//    },function (error){
//
//    });


        $http.post('../api/registerUser.php', userAuthdata).then(function (success) {


            console.log("Account Successfully created!" + success.data);
            console.log(success.data.status);
            if(success.data.status == 200) {
                gotoMainPage(success.data.data.UID);
            }else{
                console.log(success.data.status_message);
            }

        }, function (error) {
            console.log("Error in making new Account!");
        });

    }

    $scope.assignCollage = function (item) {
        $scope.selectedcollage = item;
    }
}]);

myApp.controller('AppCtrlLogin', ['$scope', '$log', '$http', '$window', function ($scope, $log, $http, $window) {

    $scope.login = function () {
        console.log("Coming to Login controller with " + $scope.emailid + " " + $scope.passw);

        var userAuthdata = {
            email: $scope.emailid,
            password: $scope.passw
        }

        $http.post('../api/getUserDetails.php', userAuthdata).then(function (success) {

            if(success.data.status == 200){
                gotoMainPage(success.data.data.UID);
            }else{
                console.log(success.data.status_message);
            }

        }, function (error) {
            console.log("Error in login !");
        });
    }

}]);
