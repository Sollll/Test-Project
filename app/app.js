/**
 * Created by Sol on 23.12.2014.
 */

var app = angular.module("app", []);

app.controller("mainCtrl", function ($scope, requestsService) {

  requestsService.getLangs().then(function (rez) {
    $scope.langs = rez;
    $scope.language = 'en';
  });

  $scope.loadAreas = function () {
    requestsService.getAreas($scope.language).then(function (rez) {
      $scope.areas = rez.Area;
    });
  };

  $scope.loadLocations = function () {
    requestsService.getLocations($scope.language, $scope.area).then(function (rez) {
      $scope.locations = rez.Location;
    });
  };

  $scope.test = 9;

});

// I act a repository for the remote friend collection.
app.service(
  "requestsService",
  function ($http, $q) {

// Return public API.
    return ({
      getAreas: getAreas,
      getLangs: getLangs,
      getLocations: getLocations
    });

// ---
// PUBLIC METHODS.
// ---

// I get all of the friends in the remote collection.
    function getLangs() {

      var request = $http({
        method: "get",
        url: "requests.php",
        params: {
          type: "langs"
        }
      });

      return ( request.then(handleSuccess, handleError) );

    }

    function getLocations(language, area) {

      var request = $http({
        method: "get",
        url: "requests.php",
        params: {
          type: "locations",
          params: {
            language: language,
            area: area
          }
        }
      });

      return ( request.then(handleSuccess, handleError) );

    }

    function getAreas(language) {

      var request = $http({
        method: "get",
        url: "requests.php",
        params: {
          type: "areas",
          params: {
            language: language
          }
        }
      });

      return ( request.then(handleSuccess, handleError) );

    }


// ---
// PRIVATE METHODS.
// ---


// I transform the error response, unwrapping the application dta from
// the API response payload.
    function handleError(response) {

// The API response from the server should be returned in a
// nomralized format. However, if the request was not handled by the
// server (or what not handles properly - ex. server error), then we
// may have to normalize it on our end, as best we can.
      if (
        !angular.isObject(response.data) || !response.data.message
      ) {

        return ( $q.reject("An unknown error occurred.") );

      }

// Otherwise, use expected error message.
      return ( $q.reject(response.data.message) );

    }


// I transform the successful response, unwrapping the application data
// from the API response payload.
    function handleSuccess(response) {

      return ( response.data );

    }

  }
);

